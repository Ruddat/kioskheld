<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StorePartnerLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'business_name' => ['required', 'string', 'max:160'],
            'contact_name' => ['nullable', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:60'],
            'email' => ['nullable', 'email', 'max:160'],

            'street' => ['nullable', 'string', 'max:180'],
            'postcode' => ['required', 'regex:/^[0-9]{5}$/'],
            'city' => ['nullable', 'string', 'max:120'],

            'opening_hours_note' => ['nullable', 'string', 'max:1000'],
            'delivery_possible' => ['required', Rule::in(['yes', 'no', 'maybe'])],
            'message' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'business_name.required' => 'Bitte gib den Namen deines Kiosks ein.',
            'phone.required' => 'Bitte gib eine Telefonnummer oder WhatsApp-Nummer ein.',
            'postcode.required' => 'Bitte gib eine Postleitzahl ein.',
            'postcode.regex' => 'Bitte gib eine gültige 5-stellige Postleitzahl ein.',
            'email.email' => 'Bitte gib eine gültige E-Mail-Adresse ein.',
            'delivery_possible.required' => 'Bitte wähle aus, ob Lieferung möglich ist.',
        ];
    }
}
