<?php

namespace App\Http\Requests;

use App\Domain\Customers\Enums\DocumentType;
use App\Domain\Customers\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\Password;

class CustomerRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'surname' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],

            'document_type' => [
                'required',
                new Enum(DocumentType::class),
            ],

            'document' => [
                'required',
                'digits_between:7,10',
                Rule::unique(Customer::class),
            ],

            'phone' => [
                'nullable',
                'string',
                'digits:10',
            ],

            'city_id' => [
                'required',
                Rule::exists('cities', 'id'),
            ],

            'address' => [
                'required',
                'string',
                'max:255',
            ],

            'password' => [
                'required',
                'confirmed',
                Password::defaults(),
            ],
        ];
    }
}
