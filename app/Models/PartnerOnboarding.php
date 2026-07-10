<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartnerOnboarding extends Model
{
    protected $fillable = [
        'partner_lead_id',
        'token',
        'status',
        'justdeliver_import_status',
        'justdeliver_shop_id',
        'justdeliver_shop_slug',
        'justdeliver_import_error',
        'justdeliver_import_response',
        'justdeliver_imported_at',
        'business_data',
        'selected_categories',
        'opening_hours',
        'delivery_settings',
        'payment_settings',
        'accepted_terms_at',
        'accepted_terms_ip',
        'submitted_at',
        'expires_at',
        'justdeliver_remote_status',
        'justdeliver_can_import_products',
        'justdeliver_can_accept_orders',
        'justdeliver_status_response',
        'justdeliver_status_error',
        'justdeliver_status_checked_at',
        'justdeliver_activated_at',
    ];

    protected function casts(): array
    {
        return [
            'business_data' => 'array',
            'selected_categories' => 'array',
            'opening_hours' => 'array',
            'delivery_settings' => 'array',
            'payment_settings' => 'array',
            'justdeliver_import_response' => 'array',
            'accepted_terms_at' => 'datetime',
            'submitted_at' => 'datetime',
            'expires_at' => 'datetime',
            'justdeliver_imported_at' => 'datetime',
            'justdeliver_can_import_products' => 'boolean',
            'justdeliver_can_accept_orders' => 'boolean',
            'justdeliver_status_response' => 'array',
            'justdeliver_status_checked_at' => 'datetime',
            'justdeliver_activated_at' => 'datetime',
        ];
    }

    public function partnerLead(): BelongsTo
    {
        return $this->belongsTo(PartnerLead::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }

    public function isSubmitted(): bool
    {
        return $this->status === 'submitted';
    }

    public function canAcceptOrders(): bool
    {
        return $this->justdeliver_can_accept_orders === true;
    }
}
