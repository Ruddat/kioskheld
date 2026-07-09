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
        'business_data',
        'selected_categories',
        'opening_hours',
        'delivery_settings',
        'payment_settings',
        'accepted_terms_at',
        'accepted_terms_ip',
        'submitted_at',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'business_data' => 'array',
            'selected_categories' => 'array',
            'opening_hours' => 'array',
            'delivery_settings' => 'array',
            'payment_settings' => 'array',
            'accepted_terms_at' => 'datetime',
            'submitted_at' => 'datetime',
            'expires_at' => 'datetime',
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
}
