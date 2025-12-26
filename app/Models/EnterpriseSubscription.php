<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class EnterpriseSubscription extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'company_id',
        'subscription_code',
        'company_name',
        'company_email',
        'company_phone',
        'company_address',
        'tax_id',
        'billing_contact_name',
        'billing_contact_email',
        'plan_type',
        'status',
        'price_per_word',
        'price_per_character',
        'price_per_api_call',
        'price_per_voice_second',
        'committed_words_monthly',
        'discount_percentage',
        'overage_multiplier',
        'total_words_translated',
        'total_characters_translated',
        'total_api_calls',
        'total_voice_seconds',
        'current_month_words',
        'current_month_characters',
        'current_month_api_calls',
        'current_month_voice_seconds',
        'current_month_start',
        'current_month_cost',
        'total_billed',
        'currency',
        'billing_cycle',
        'last_billing_date',
        'next_billing_date',
        'payment_method',
        'stripe_customer_id',
        'stripe_subscription_id',
        'payment_terms_days',
        'spending_limit_monthly',
        'auto_suspend_on_limit',
        'warning_threshold_percentage',
        'spending_alert_sent',
        'enabled_features',
        'max_team_members',
        'max_projects',
        'max_api_keys',
        'dedicated_support',
        'custom_integrations',
        'white_label',
        'sso_enabled',
        'audit_logs',
        'priority_processing',
        'sla_tier',
        'contract_start_date',
        'contract_end_date',
        'contract_term_months',
        'auto_renew',
        'special_terms',
        'notes',
        'account_manager_name',
        'account_manager_email',
        'account_manager_phone',
    ];

    protected $casts = [
        'current_month_start' => 'datetime',
        'last_billing_date' => 'datetime',
        'next_billing_date' => 'datetime',
        'contract_start_date' => 'datetime',
        'contract_end_date' => 'datetime',
        'enabled_features' => 'array',
        'auto_suspend_on_limit' => 'boolean',
        'spending_alert_sent' => 'boolean',
        'dedicated_support' => 'boolean',
        'custom_integrations' => 'boolean',
        'white_label' => 'boolean',
        'sso_enabled' => 'boolean',
        'audit_logs' => 'boolean',
        'priority_processing' => 'boolean',
        'auto_renew' => 'boolean',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function usageLogs()
    {
        return $this->hasMany(EnterpriseUsageLog::class);
    }

    public function invoices()
    {
        return $this->hasMany(EnterpriseInvoice::class);
    }

    // Usage Tracking Methods
    public function trackUsage(array $data)
    {
        $wordCount = $data['word_count'] ?? 0;
        $characterCount = $data['character_count'] ?? 0;
        $apiCalls = $data['api_calls'] ?? 1;
        $voiceSeconds = $data['voice_seconds'] ?? 0;

        // Calculate cost based on plan type
        $cost = $this->calculateCost($wordCount, $characterCount, $apiCalls, $voiceSeconds);

        // Create usage log
        $log = $this->usageLogs()->create([
            'user_id' => $data['user_id'] ?? $this->user_id,
            'usage_type' => $data['usage_type'] ?? 'translation',
            'action' => $data['action'] ?? null,
            'word_count' => $wordCount,
            'character_count' => $characterCount,
            'api_calls' => $apiCalls,
            'voice_seconds' => $voiceSeconds,
            'unit_price' => $this->getCurrentUnitPrice($data['usage_type'] ?? 'translation'),
            'calculated_cost' => $cost,
            'discount_applied' => $this->getApplicableDiscount(),
            'final_cost' => $cost * (1 - ($this->getApplicableDiscount() / 100)),
            'source_language' => $data['source_language'] ?? null,
            'target_language' => $data['target_language'] ?? null,
            'industry' => $data['industry'] ?? null,
            'dialect' => $data['dialect'] ?? null,
            'quality_tier' => $data['quality_tier'] ?? 'standard',
            'ip_address' => $data['ip_address'] ?? request()->ip(),
            'user_agent' => $data['user_agent'] ?? request()->userAgent(),
            'api_key_id' => $data['api_key_id'] ?? null,
            'project_id' => $data['project_id'] ?? null,
            'metadata' => $data['metadata'] ?? null,
            'status' => $data['status'] ?? 'completed',
        ]);

        // Update current month usage
        $this->increment('current_month_words', $wordCount);
        $this->increment('current_month_characters', $characterCount);
        $this->increment('current_month_api_calls', $apiCalls);
        $this->increment('current_month_voice_seconds', $voiceSeconds);
        $this->increment('current_month_cost', $log->final_cost);

        // Update total usage
        $this->increment('total_words_translated', $wordCount);
        $this->increment('total_characters_translated', $characterCount);
        $this->increment('total_api_calls', $apiCalls);
        $this->increment('total_voice_seconds', $voiceSeconds);

        // Check spending limits
        $this->checkSpendingLimits();

        return $log;
    }

    public function calculateCost(int $words, int $characters, int $apiCalls, int $voiceSeconds): float
    {
        $cost = 0;

        if ($this->plan_type === 'pay_as_you_go') {
            $cost += $words * $this->price_per_word;
            $cost += $characters * $this->price_per_character;
            $cost += $apiCalls * $this->price_per_api_call;
            $cost += $voiceSeconds * $this->price_per_voice_second;
        } elseif ($this->plan_type === 'committed_volume') {
            // Check if within committed volume
            if ($this->current_month_words + $words <= $this->committed_words_monthly) {
                // Within committed volume - discounted rate
                $cost += $words * $this->price_per_word * (1 - ($this->discount_percentage / 100));
            } else {
                // Overage - apply overage multiplier
                $overageWords = ($this->current_month_words + $words) - $this->committed_words_monthly;
                $committedWords = $words - $overageWords;
                
                $cost += $committedWords * $this->price_per_word * (1 - ($this->discount_percentage / 100));
                $cost += $overageWords * $this->price_per_word * $this->overage_multiplier;
            }
            
            $cost += $apiCalls * $this->price_per_api_call * (1 - ($this->discount_percentage / 100));
            $cost += $voiceSeconds * $this->price_per_voice_second * (1 - ($this->discount_percentage / 100));
        }

        return round($cost, 4);
    }

    public function getCurrentUnitPrice(string $usageType): float
    {
        return match($usageType) {
            'translation' => $this->price_per_word,
            'api_call' => $this->price_per_api_call,
            'voice' => $this->price_per_voice_second,
            default => $this->price_per_word,
        };
    }

    public function getApplicableDiscount(): float
    {
        if ($this->plan_type === 'committed_volume' && 
            $this->current_month_words <= $this->committed_words_monthly) {
            return $this->discount_percentage;
        }
        return 0;
    }

    public function checkSpendingLimits()
    {
        if (!$this->spending_limit_monthly) {
            return;
        }

        $percentage = ($this->current_month_cost / $this->spending_limit_monthly) * 100;

        // Send warning at threshold
        if (!$this->spending_alert_sent && $percentage >= $this->warning_threshold_percentage) {
            // Send notification
            $this->user->notify(new \App\Notifications\EnterpriseSpendingWarning($this, $percentage));
            $this->update(['spending_alert_sent' => true]);
        }

        // Auto-suspend if limit reached
        if ($this->auto_suspend_on_limit && $this->current_month_cost >= $this->spending_limit_monthly) {
            $this->update(['status' => 'suspended']);
            $this->user->notify(new \App\Notifications\EnterpriseSubscriptionSuspended($this));
        }
    }

    public function resetMonthlyUsage()
    {
        $this->update([
            'current_month_words' => 0,
            'current_month_characters' => 0,
            'current_month_api_calls' => 0,
            'current_month_voice_seconds' => 0,
            'current_month_cost' => 0,
            'current_month_start' => now(),
            'spending_alert_sent' => false,
        ]);
    }

    public function generateInvoice(Carbon $periodStart, Carbon $periodEnd)
    {
        // Get usage for period
        $usage = $this->usageLogs()
            ->whereBetween('created_at', [$periodStart, $periodEnd])
            ->selectRaw('
                SUM(word_count) as total_words,
                SUM(character_count) as total_characters,
                SUM(api_calls) as total_api_calls,
                SUM(voice_seconds) as total_voice_seconds,
                SUM(final_cost) as total_cost
            ')
            ->first();

        $subtotal = $usage->total_cost ?? 0;
        $taxAmount = $subtotal * ($this->getTaxRate() / 100);
        $totalAmount = $subtotal + $taxAmount;

        // Generate invoice number
        $invoiceNumber = $this->generateInvoiceNumber();

        // Create invoice
        $invoice = $this->invoices()->create([
            'invoice_number' => $invoiceNumber,
            'invoice_date' => now(),
            'due_date' => now()->addDays($this->payment_terms_days),
            'period_start' => $periodStart,
            'period_end' => $periodEnd,
            'subtotal' => $subtotal,
            'tax_rate' => $this->getTaxRate(),
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'amount_due' => $totalAmount,
            'currency' => $this->currency,
            'total_words' => $usage->total_words ?? 0,
            'total_characters' => $usage->total_characters ?? 0,
            'total_api_calls' => $usage->total_api_calls ?? 0,
            'total_voice_seconds' => $usage->total_voice_seconds ?? 0,
            'usage_breakdown' => $this->getUsageBreakdown($periodStart, $periodEnd),
            'status' => 'draft',
        ]);

        return $invoice;
    }

    protected function generateInvoiceNumber(): string
    {
        $prefix = 'ENT-' . date('Ym') . '-';
        $lastInvoice = EnterpriseInvoice::where('invoice_number', 'like', $prefix . '%')
            ->orderBy('invoice_number', 'desc')
            ->first();

        if ($lastInvoice) {
            $lastNumber = (int) substr($lastInvoice->invoice_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    protected function getTaxRate(): float
    {
        // You can implement country-specific tax rates here
        return 0; // Default no tax
    }

    protected function getUsageBreakdown(Carbon $start, Carbon $end): array
    {
        return $this->usageLogs()
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('
                usage_type,
                COUNT(*) as count,
                SUM(word_count) as words,
                SUM(character_count) as characters,
                SUM(api_calls) as api_calls,
                SUM(voice_seconds) as voice_seconds,
                SUM(final_cost) as cost
            ')
            ->groupBy('usage_type')
            ->get()
            ->toArray();
    }

    // Status checks
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function canUseService(): bool
    {
        if (!$this->isActive()) {
            return false;
        }

        if ($this->spending_limit_monthly && $this->current_month_cost >= $this->spending_limit_monthly) {
            return false;
        }

        return true;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePendingBilling($query)
    {
        return $query->where('next_billing_date', '<=', now())
            ->where('status', 'active');
    }

    // Attributes
    public function getUsagePercentageAttribute(): float
    {
        if (!$this->committed_words_monthly) {
            return 0;
        }
        return ($this->current_month_words / $this->committed_words_monthly) * 100;
    }

    public function getSpendingPercentageAttribute(): float
    {
        if (!$this->spending_limit_monthly) {
            return 0;
        }
        return ($this->current_month_cost / $this->spending_limit_monthly) * 100;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($subscription) {
            if (!$subscription->subscription_code) {
                $subscription->subscription_code = 'ENT-' . strtoupper(uniqid());
            }
            if (!$subscription->current_month_start) {
                $subscription->current_month_start = now();
            }
        });
    }
}
