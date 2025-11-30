<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Laravel\Sanctum\HasApiTokens;
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'company_id',
        'role',
        'account_status',
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            // 'password' => 'hashed', // Removed - causing issues
            // 'account_status' => 'boolean', // Removed - using string value
        ];
    }
    /**
     * Determine if the user can access the Filament panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // Role-based access for Filament panels
        $adminRoles = ['super_admin', 'support_admin', 'financial_admin', 'technical_admin'];
        $status = $this->account_status ?? 'active';
        return in_array($this->role, $adminRoles, true) && $status === 'active';
    }
    /**
     * Get the company that owns the user.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
    /**
     * Get the user's subscriptions.
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
    
    /**
     * Get the user's active subscription.
     */
    public function subscription()
    {
        return $this->hasOne(Subscription::class)
            ->where('status', 'active')
            ->latest();
    }
    
    /**
     * Get the user's active subscription (alias).
     */
    public function activeSubscription()
    {
        return $this->subscription();
    }
    
    /**
     * Get the user's payment transactions.
     */
    public function paymentTransactions()
    {
        return $this->hasMany(PaymentTransaction::class);
    }
    
    /**
     * Get the user's complaints.
     */
    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }
    /**
     * Get the user's token usage logs.
     */
    public function tokenUsageLogs()
    {
        return $this->hasMany(TokenUsageLog::class);
    }
    /**
     * Check if user has an active subscription.
     */
    public function hasActiveSubscription(): bool
    {
        return $this->subscriptions()
            ->where('status', 'active')
            ->exists();
    }
    /**
     * Check if user has sufficient tokens.
     */
    public function hasTokens(int $amount = 1): bool
    {
        $subscription = $this->activeSubscription;
        
        if (!$subscription) {
            return false;
        }
        
        return $subscription->tokens_remaining >= $amount;
    }

    /**
     * Get the user's OAuth integrations.
     */
    public function userIntegrations()
    {
        return $this->hasMany(UserIntegration::class);
    }

    /**
     * Check if user has a specific integration connected.
     */
    public function hasIntegration(string $platform): bool
    {
        return $this->userIntegrations()
            ->where('platform', $platform)
            ->where('status', 'active')
            ->exists();
    }

    /**
     * Get a specific integration.
     */
    public function getIntegration(string $platform)
    {
        return $this->userIntegrations()
            ->where('platform', $platform)
            ->first();
    }
}
