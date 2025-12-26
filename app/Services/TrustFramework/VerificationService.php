<?php
namespace App\Services\TrustFramework;
use Illuminate\Support\Facades\DB;
class VerificationService
{
    public function verify(string $code): ?array
    {
        $cert = DB::table('document_certificates')
            ->where('verification_code', $code)
            ->first();
        
        
        DB::table('document_certificates')
            ->where('id', $cert->id)
            ->increment('verification_count');
        
        DB::table('document_certificates')
            ->where('id', $cert->id)
            ->update(['last_verified_at' => now()]);
        
        return [
            'verification_code' => $cert->verification_code,
            'certificate_number' => $cert->cert_id,
            'status' => $cert->status,
            'issued_at' => $cert->issued_at,
            'expires_at' => $cert->expires_at,
            'translator_name' => $cert->translator_name,
            'compliance_seals' => json_decode($cert->compliance_seals ?? '[]'),
            'sworn_translation' => (bool) $cert->sworn_translation,
            'verification_count' => $cert->verification_count + 1,
        ];
    }
    
    public function generateCode(): string
    {
        return bin2hex(random_bytes(16));
    }
}
