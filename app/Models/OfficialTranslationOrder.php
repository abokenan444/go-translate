<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OfficialTranslationOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'document_id',
        'page_count',
        'word_count',
        'price',
        'currency',
        'payment_provider',
        'payment_status',
        'payment_reference',
        'paid_at',
    ];

    protected $casts = [
        'page_count' => 'integer',
        'word_count' => 'integer',
        'price' => 'decimal:2',
        'paid_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the document associated with the order.
     */
    public function document(): BelongsTo
    {
        return $this->belongsTo(OfficialDocument::class, 'document_id');
    }

    /**
     * Check if order is paid.
     */
    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    /**
     * Check if order is pending payment.
     */
    public function isPending(): bool
    {
        return in_array($this->payment_status, ['unpaid', 'pending']);
    }

    /**
     * Mark order as paid.
     */
    public function markAsPaid(?string $paymentReference = null): void
    {
        $this->update([
            'payment_status' => 'paid',
            'paid_at' => now(),
            'payment_reference' => $paymentReference ?? $this->payment_reference,
        ]);
    }

    /**
     * Get formatted price.
     */
    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 2) . ' ' . $this->currency;
    }

    /**
     * Scope for filtering paid orders.
     */
    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    /**
     * Scope for filtering unpaid orders.
     */
    public function scopeUnpaid($query)
    {
        return $query->whereIn('payment_status', ['unpaid', 'pending']);
    }
}
