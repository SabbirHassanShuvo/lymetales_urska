<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email'          => ['required', 'email:rfc,dns'],
            'full_name'      => ['required', 'string', 'max:100'],
            'address'        => ['required', 'string', 'max:255'],
            'city'           => ['required', 'string', 'max:100'],
            'postal_code'    => ['required', 'string', 'max:20'],
            'country'        => ['required', 'string', 'max:100'],
            'phone'          => ['required', 'regex:/^[+\d\s\-]{1,20}$/'],
            'payment_method' => ['required', Rule::in(['cod', 'stripe', 'paypal'])],
            'fast_production' => ['sometimes', 'boolean'],
            // stripe_payment_intent_id is only needed for direct PaymentIntent flow,
            // NOT for Stripe Checkout Session (link-based) flow.
            // 'stripe_payment_intent_id' => ['sometimes', 'nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'payment_method.required' => 'Please select a payment method.',
            'payment_method.in'       => 'Please select a payment method.',
        ];
    }
}
