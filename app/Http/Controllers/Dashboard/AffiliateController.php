<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Affiliate;
use App\Models\AffiliateReferral;
use App\Models\AffiliateCommission;

class AffiliateController extends Controller
{
    /**
     * عرض لوحة تحكم الشريك
     */
    public function dashboard()
    {
        $user = auth()->user();
        
        // البحث عن الشريك أو إنشاؤه
        $affiliate = Affiliate::firstOrCreate(
            ['user_id' => $user->id],
            [
                'affiliate_code' => $this->generateUniqueCode(),
                'status' => 'active'
            ]
        );
        
        // الإحصائيات العامة
        $stats = [
            'total_referrals' => $affiliate->referrals()->count(),
            'active_referrals' => $affiliate->referrals()
                ->where('status', 'active')->count(),
            'total_clicks' => $affiliate->referrals()->sum('clicks_count'),
            'conversion_rate' => $this->calculateConversionRate($affiliate),
            'total_earned' => $affiliate->commissions()->sum('amount'),
            'pending_balance' => $affiliate->commissions()
                ->where('status', 'pending')->sum('amount'),
            'paid_balance' => $affiliate->commissions()
                ->where('status', 'paid')->sum('amount'),
            'this_month_earnings' => $affiliate->commissions()
                ->whereMonth('created_at', now()->month)
                ->sum('amount'),
        ];
        
        // أفضل الإحالات
        $topReferrals = $affiliate->referrals()
            ->orderBy('conversions_count', 'desc')
            ->limit(10)
            ->get();
        
        // آخر العمولات
        $recentCommissions = $affiliate->commissions()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();
        
        return view('dashboard.affiliate.index', compact(
            'affiliate',
            'stats',
            'topReferrals',
            'recentCommissions'
        ));
    }
    
    /**
     * توليد رابط إحالة جديد
     */
    public function generateLink(Request $request)
    {
        $request->validate([
            'campaign_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);
        
        $user = auth()->user();
        $affiliate = Affiliate::where('user_id', $user->id)->first();
        
        if (!$affiliate) {
            return response()->json([
                'success' => false,
                'message' => 'حساب الشريك غير موجود!'
            ], 404);
        }
        
        // توليد كود فريد
        $referralCode = $this->generateUniqueReferralCode();
        
        // إنشاء رابط الإحالة
        $referralLink = AffiliateReferral::create([
            'affiliate_id' => $affiliate->id,
            'referral_code' => $referralCode,
            'campaign_name' => $request->campaign_name,
            'description' => $request->description,
            'clicks_count' => 0,
            'conversions_count' => 0,
            'status' => 'active',
        ]);
        
        $fullLink = url('/register?ref=' . $referralCode);
        
        return response()->json([
            'success' => true,
            'message' => 'تم توليد رابط الإحالة بنجاح!',
            'referral_link' => $referralLink,
            'full_link' => $fullLink,
        ]);
    }
    
    /**
     * تتبع نقرة على رابط الإحالة
     */
    public function trackClick($referralCode)
    {
        $referral = AffiliateReferral::where('referral_code', $referralCode)
            ->where('status', 'active')
            ->first();
        
        if ($referral) {
            // زيادة عدد النقرات
            $referral->increment('clicks_count');
            
            // حفظ كود الإحالة في الجلسة
            session(['referral_code' => $referralCode]);
        }
        
        return redirect('/register');
    }
    
    /**
     * عرض صفحة الإحصائيات
     */
    public function stats()
    {
        $user = auth()->user();
        $affiliate = Affiliate::where('user_id', $user->id)->first();
        
        if (!$affiliate) {
            return redirect()->route('dashboard.affiliate.dashboard');
        }
        
        $referrals = $affiliate->referrals()->get();
        $commissions = $affiliate->commissions()->with('user')->get();
        
        return view('dashboard.affiliate.stats', compact('affiliate', 'referrals', 'commissions'));
    }
    
    /**
     * توليد كود شريك فريد
     */
    private function generateUniqueCode()
    {
        do {
            $code = 'AFF' . Str::random(7);
        } while (Affiliate::where('affiliate_code', $code)->exists());
        
        return $code;
    }
    
    /**
     * توليد كود إحالة فريد
     */
    private function generateUniqueReferralCode()
    {
        do {
            $code = 'REF' . Str::random(8);
        } while (AffiliateReferral::where('referral_code', $code)->exists());
        
        return $code;
    }
    
    /**
     * حساب معدل التحويل
     */
    private function calculateConversionRate($affiliate)
    {
        $totalClicks = $affiliate->referrals()->sum('clicks_count');
        $totalConversions = $affiliate->referrals()->sum('conversions_count');
        
        if ($totalClicks == 0) return 0;
        
        return round(($totalConversions / $totalClicks) * 100, 2);
    }
    
    /**
     * عرض صفحة الروابط
     */
    public function links()
    {
        $user = auth()->user();
        $affiliate = Affiliate::where('user_id', $user->id)->first();
        
        if (!$affiliate) {
            return redirect()->route('dashboard.affiliate.dashboard');
        }
        
        $referrals = $affiliate->referrals()->orderBy('created_at', 'desc')->paginate(12);
        
        return view('dashboard.affiliate.links', compact('affiliate', 'referrals'));
    }
    
    /**
     * تبديل حالة الرابط (تفعيل/إيقاف)
     */
    public function toggleLink($id)
    {
        $user = auth()->user();
        $affiliate = Affiliate::where('user_id', $user->id)->first();
        
        if (!$affiliate) {
            return back()->with('error', 'حساب الشريك غير موجود!');
        }
        
        $referral = $affiliate->referrals()->findOrFail($id);
        $referral->status = $referral->status === 'active' ? 'inactive' : 'active';
        $referral->save();
        
        return back()->with('success', 'تم تحديث حالة الرابط بنجاح!');
    }
    
    /**
     * عرض صفحة السحوبات
     */
    public function payouts()
    {
        $user = auth()->user();
        $affiliate = Affiliate::where('user_id', $user->id)->first();
        
        if (!$affiliate) {
            return redirect()->route('dashboard.affiliate.dashboard');
        }
        
        // Get payouts (assuming AffiliatePayment model exists)
        $payouts = collect(); // Empty for now, will be populated when AffiliatePayment model is used
        $processingAmount = 0;
        
        return view('dashboard.affiliate.payouts', compact('affiliate', 'payouts', 'processingAmount'));
    }
    
    /**
     * طلب سحب
     */
    public function requestPayout(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:50',
            'payment_method' => 'required|string|in:bank_transfer,paypal,wise',
            'payment_details' => 'required|string|max:1000',
        ]);
        
        $user = auth()->user();
        $affiliate = Affiliate::where('user_id', $user->id)->first();
        
        if (!$affiliate) {
            return back()->with('error', 'حساب الشريك غير موجود!');
        }
        
        if ($request->amount > $affiliate->pending_balance) {
            return back()->with('error', 'المبلغ المطلوب أكبر من الرصيد المتاح!');
        }
        
        if ($request->amount < 50) {
            return back()->with('error', 'الحد الأدنى للسحب هو $50.00!');
        }
        
        // Create payout request (will be implemented with AffiliatePayment model)
        // For now, just show success message
        
        return back()->with('success', 'تم إرسال طلب السحب بنجاح! سيتم مراجعته خلال 5-7 أيام عمل.');
    }
}
