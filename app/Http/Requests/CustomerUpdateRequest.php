<?php

namespace App\Http\Requests;

use App\Enums\DocumentType;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerUpdateRequest extends FormRequest
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
                'max:255'
            ],

            'surname' => [
                'required',
                'string',
                'max:255'
            ],

            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id)
            ],

            'document_type' => [
                'required',
                Rule::enum(DocumentType::class)
            ],

            'document' => [
                'required',
                'digits_between:8,11',
                Rule::unique(Customer::class)->ignore(
                    $this->user()->customer_id
                )
            ],

            'phone' => [
                'nullable',
                'string',
                'digits:10'
            ],

            'city_id' => [
                'required',
                Rule::exists('cities', 'id')
            ],

            'address' => [
                'required',
                'string',
                'max:255'
            ],
        ];
    }
}
