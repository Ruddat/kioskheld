<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PartnerLead extends Model
{
    protected $fillable = [
        'business_name',
        'contact_name',
        'phone',
        'email',
        'street',
        'postcode',
        'city',
        'opening_hours_note',
        'delivery_possible',
        'message',
        'status',
        'source',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
        ];
    }

public function onboardings(): HasMany
{
    return $this->hasMany(PartnerOnboarding::class);
}

public function latestOnboarding(): HasOne
{
    return $this->hasOne(PartnerOnboarding::class)->latestOfMany();
}



}
