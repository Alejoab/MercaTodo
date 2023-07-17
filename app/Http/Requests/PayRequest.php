<?php

namespace App\Http\Requests;

use App\Domain\Payments\Enums\PaymentMethod;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class PayRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'paymentMethod' => ['required', 'string', new Enum(PaymentMethod::class)],
        ];
    }
}
