<?php

namespace App\Http\Controllers;

use App\Http\Requests\GovernmentRegistrationRequest;
use App\Models\GovernmentVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GovernmentRegistrationController extends Controller
{
    /**
     * Show the government registration form
     */
    public function showForm()
    {
        $countries = $this->getCountries();
        $entityTypes = $this->getEntityTypes();
        $documentTypes = $this->getDocumentTypes();
        $emailDomains = DB::table('government_email_domains')
            ->select('domain', 'country_name as country')
            ->where('is_verified', true)
            ->get()
            ->groupBy('country');

        return view('auth.government-register', compact(
            'countries',
            'entityTypes',
            'documentTypes',
            'emailDomains'
        ));
    }

    /**
     * Process government registration with Protection System
     */
    public function register(GovernmentRegistrationRequest $request)
    {
        DB::beginTransaction();

        try {
            // Extract email domain for validation
            $emailDomain = substr(strrchr($request->contact_email, "@"), 1);

            // Create or get user
            $user = auth()->user();

            if (!$user) {
                // Create new user with PENDING status - NO IMMEDIATE ACCESS
                $user = User::create([
                    'name' => $request->contact_name,
                    'email' => $request->contact_email,
                    'password' => bcrypt(Str::random(32)), // Temporary password
                    'account_type' => 'government',
                    'account_status' => 'pending_verification', // CRITICAL: No access until approved
                    'is_government_verified' => false, // CRITICAL: Starts as false
                ]);
            } else {
                // Update existing user to government with pending status
                $user->update([
                    'account_type' => 'government',
                    'account_status' => 'pending_verification',
                    'is_government_verified' => false,
                ]);
            }

            // Create verification request
            $verification = GovernmentVerification::create([
                'user_id' => $user->id,
                'entity_name' => $request->entity_name,
                'entity_name_local' => $request->entity_name_local,
                'entity_type' => $request->entity_type,
                'country_code' => $request->country_code,
                'department' => $request->department,
                'official_website' => $request->official_website,
                'contact_name' => $request->contact_name,
                'contact_position' => $request->contact_position,
                'contact_email' => $request->contact_email,
                'contact_phone' => $request->contact_phone,
                'employee_id' => $request->employee_id,
                'status' => 'pending',
                'priority' => 'normal',
                'legal_accepted' => true,
                'legal_accepted_at' => now(),
                'legal_ip' => $request->ip(),
                'submission_ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Update user with verification reference
            $user->update([
                'government_verification_id' => $verification->id,
            ]);

            // Upload documents
            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $index => $file) {
                    $docType = $request->input("documents.{$index}.type", 'other');
                    
                    $path = $file->store('government-documents/' . $verification->id, 'private');

                    DB::table('government_documents')->insert([
                        'verification_id' => $verification->id,
                        'document_type' => $docType,
                        'original_filename' => $file->getClientOriginalName(),
                        'file_path' => $path,
                        'file_size' => $file->getSize(),
                        'mime_type' => $file->getMimeType(),
                        'is_verified' => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // Create audit log
            DB::table('government_audit_logs')->insert([
                'verification_id' => $verification->id,
                'action' => 'submitted',
                'performed_by' => $user->id,
                'notes' => 'Initial submission',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => now(),
            ]);

            DB::commit();

            // Send notification email (optional)
            // $this->notifyAdmins($verification);

            return response()->json([
                'success' => true,
                'message' => 'Your government verification request has been submitted successfully. Our team will review your application within 2-5 business days.',
                'reference_id' => $verification->id,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Government registration failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your request. Please try again.',
            ], 500);
        }
    }

    /**
     * Check verification status
     */
    public function checkStatus(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        $verification = GovernmentVerification::where('user_id', $user->id)
            ->latest()
            ->first();

        if (!$verification) {
            return response()->json([
                'success' => false,
                'message' => 'No verification request found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'status' => $verification->status,
            'status_label' => $this->getStatusLabel($verification->status),
            'submitted_at' => $verification->created_at->format('Y-m-d H:i'),
            'reviewed_at' => $verification->reviewed_at?->format('Y-m-d H:i'),
            'rejection_reason' => $verification->rejection_reason,
            'info_request' => $verification->info_request_message,
            'is_verified' => $user->is_government_verified,
        ]);
    }

    /**
     * Submit additional information
     */
    public function submitAdditionalInfo(Request $request)
    {
        $user = auth()->user();
        
        $verification = GovernmentVerification::where('user_id', $user->id)
            ->where('status', 'info_requested')
            ->latest()
            ->first();

        if (!$verification) {
            return response()->json([
                'success' => false,
                'message' => 'No pending information request found',
            ], 404);
        }

        DB::beginTransaction();

        try {
            // Upload additional documents
            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $file) {
                    $path = $file->store('government-documents/' . $verification->id, 'private');

                    DB::table('government_documents')->insert([
                        'verification_id' => $verification->id,
                        'document_type' => $request->input('document_type', 'other'),
                        'original_filename' => $file->getClientOriginalName(),
                        'file_path' => $path,
                        'file_size' => $file->getSize(),
                        'mime_type' => $file->getMimeType(),
                        'is_verified' => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // Update verification
            $verification->update([
                'status' => 'under_review',
                'additional_info' => $request->input('additional_info'),
                'info_provided_at' => now(),
            ]);

            // Log
            DB::table('government_audit_logs')->insert([
                'verification_id' => $verification->id,
                'action' => 'info_provided',
                'performed_by' => $user->id,
                'notes' => 'Additional information submitted',
                'ip_address' => $request->ip(),
                'created_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Additional information submitted successfully.',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit additional information.',
            ], 500);
        }
    }

    /**
     * Get country list
     */
    protected function getCountries(): array
    {
        return [
            'AE' => 'United Arab Emirates',
            'SA' => 'Saudi Arabia',
            'EG' => 'Egypt',
            'JO' => 'Jordan',
            'KW' => 'Kuwait',
            'QA' => 'Qatar',
            'BH' => 'Bahrain',
            'OM' => 'Oman',
            'LB' => 'Lebanon',
            'IQ' => 'Iraq',
            'SY' => 'Syria',
            'PS' => 'Palestine',
            'YE' => 'Yemen',
            'LY' => 'Libya',
            'TN' => 'Tunisia',
            'DZ' => 'Algeria',
            'MA' => 'Morocco',
            'SD' => 'Sudan',
            'US' => 'United States',
            'GB' => 'United Kingdom',
            'DE' => 'Germany',
            'FR' => 'France',
            'NL' => 'Netherlands',
            'BE' => 'Belgium',
            'IT' => 'Italy',
            'ES' => 'Spain',
            'PT' => 'Portugal',
            'CH' => 'Switzerland',
            'AT' => 'Austria',
            'SE' => 'Sweden',
            'NO' => 'Norway',
            'DK' => 'Denmark',
            'FI' => 'Finland',
            'IE' => 'Ireland',
            'PL' => 'Poland',
            'CZ' => 'Czech Republic',
            'GR' => 'Greece',
            'TR' => 'Turkey',
            'RU' => 'Russia',
            'UA' => 'Ukraine',
            'CA' => 'Canada',
            'AU' => 'Australia',
            'NZ' => 'New Zealand',
            'JP' => 'Japan',
            'KR' => 'South Korea',
            'CN' => 'China',
            'IN' => 'India',
            'PK' => 'Pakistan',
            'BD' => 'Bangladesh',
            'MY' => 'Malaysia',
            'SG' => 'Singapore',
            'ID' => 'Indonesia',
            'TH' => 'Thailand',
            'VN' => 'Vietnam',
            'PH' => 'Philippines',
            'ZA' => 'South Africa',
            'NG' => 'Nigeria',
            'KE' => 'Kenya',
            'GH' => 'Ghana',
            'BR' => 'Brazil',
            'MX' => 'Mexico',
            'AR' => 'Argentina',
            'CL' => 'Chile',
            'CO' => 'Colombia',
            'PE' => 'Peru',
        ];
    }

    /**
     * Get entity types
     */
    protected function getEntityTypes(): array
    {
        return [
            'ministry' => '🏛️ Ministry / وزارة',
            'embassy' => '🏢 Embassy / سفارة',
            'consulate' => '🏣 Consulate / قنصلية',
            'municipality' => '🏙️ Municipality / بلدية',
            'agency' => '🏗️ Agency / هيئة',
            'department' => '📋 Department / إدارة',
            'court' => '⚖️ Court / محكمة',
            'parliament' => '🏛️ Parliament / برلمان',
            'other' => '📌 Other / أخرى',
        ];
    }

    /**
     * Get document types
     */
    protected function getDocumentTypes(): array
    {
        return [
            'official_id' => '🪪 Official ID / هوية رسمية',
            'authorization_letter' => '📜 Authorization Letter / خطاب تفويض',
            'business_card' => '💼 Official Business Card / بطاقة عمل رسمية',
            'appointment_letter' => '📋 Appointment Letter / خطاب تعيين',
            'official_website_proof' => '🌐 Website Screenshot / إثبات الموقع الرسمي',
            'mou_agreement' => '📄 MoU / Agreement / مذكرة تفاهم',
            'other' => '📎 Other Document / مستند آخر',
        ];
    }

    /**
     * Get status label
     */
    protected function getStatusLabel(string $status): string
    {
        return match ($status) {
            'pending' => 'Pending Review / قيد المراجعة',
            'under_review' => 'Under Review / تحت المراجعة',
            'info_requested' => 'More Information Needed / مطلوب معلومات إضافية',
            'approved' => 'Approved / تمت الموافقة',
            'rejected' => 'Rejected / مرفوض',
            'suspended' => 'Suspended / معلق',
            default => 'Unknown / غير معروف',
        };
    }
}
