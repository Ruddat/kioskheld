<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
