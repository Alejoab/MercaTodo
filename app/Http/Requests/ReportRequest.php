<?php

namespace App\Http\Requests;

use App\Domain\Reports\Enums\ReportType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'from' => ['nullable', 'date', 'before_or_equal:'.today()->format('Y-m-d')],
            'to' => ['nullable', 'date', 'before_or_equal:'.today()->format('Y-m-d')],
            'reports' => ['required', 'array', Rule::in(array_column(ReportType::cases(), 'value'))],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->date('from') && $this->date('to') && $this->date('from') > $this->date('to')) {
                $validator->errors()->add('from', 'The from date must be before the to date.');
            }
        });
    }
}
