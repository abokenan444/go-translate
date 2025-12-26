<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnterpriseController extends Controller
{
    /**
     * Display enterprise dashboard
     */
    public function index()
    {
        $companies = DB::table('companies')
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->get();
            
        $stats = [
            'total_companies' => DB::table('companies')->count(),
            'active_companies' => DB::table('companies')->where('status', 'active')->count(),
            'pending_requests' => DB::table('enterprise_requests')->where('status', 'pending')->count(),
            'active_features' => DB::table('enterprise_features')->where('is_active', true)->count(),
        ];
        
        return view('admin.enterprise.index', compact('companies', 'stats'));
    }

    /**
     * Display company details
     */
    public function show($id)
    {
        $company = DB::table('companies')->where('id', $id)->first();
        
        if (!$company) {
            return redirect()->route('admin.enterprise.index')->with('error', 'Company not found');
        }
        
        $features = DB::table('enterprise_features')
            ->where('company_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        $limits = DB::table('enterprise_limits')
            ->where('company_id', $id)
            ->get();
            
        $requests = DB::table('enterprise_requests')
            ->where('company_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.enterprise.show', compact('company', 'features', 'limits', 'requests'));
    }

    /**
     * Add feature to company
     */
    public function addFeature(Request $request, $id)
    {
        $validated = $request->validate([
            'feature_name' => 'required|string',
            'feature_description' => 'nullable|string',
            'feature_type' => 'required|string',
            'feature_config' => 'required|array',
        ]);
        
        DB::table('enterprise_features')->insert([
            'company_id' => $id,
            'feature_name' => $validated['feature_name'],
            'feature_description' => $validated['feature_description'],
            'feature_type' => $validated['feature_type'],
            'feature_config' => json_encode($validated['feature_config']),
            'is_active' => true,
            'activated_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        return redirect()->back()->with('success', 'Feature added successfully');
    }

    /**
     * Update company limit
     */
    public function updateLimit(Request $request, $id)
    {
        $validated = $request->validate([
            'limit_type' => 'required|string',
            'limit_value' => 'required|integer',
            'is_unlimited' => 'boolean',
        ]);
        
        DB::table('enterprise_limits')->updateOrInsert(
            ['company_id' => $id, 'limit_type' => $validated['limit_type']],
            [
                'limit_value' => $validated['limit_value'],
                'is_unlimited' => $validated['is_unlimited'] ?? false,
                'updated_at' => now(),
            ]
        );
        
        return redirect()->back()->with('success', 'Limit updated successfully');
    }

    /**
     * Update request status
     */
    public function updateRequest(Request $request, $requestId)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,completed,rejected',
        ]);
        
        DB::table('enterprise_requests')
            ->where('id', $requestId)
            ->update([
                'status' => $validated['status'],
                'resolved_at' => in_array($validated['status'], ['completed', 'rejected']) ? now() : null,
                'updated_at' => now(),
            ]);
        
        return redirect()->back()->with('success', 'Request updated successfully');
    }
}
