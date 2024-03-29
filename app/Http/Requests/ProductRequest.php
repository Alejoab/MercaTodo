<?php

namespace App\Http\Requests;

use App\Domain\Products\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, Rule|array|string>
     */
    public function rules(): array
    {
        /** @var ?Product $product */
        $product = $this->route('product') ?: null;

        return [
            'code' => ['required', 'size:6', Rule::unique(Product::class)->ignore($product?->getKey())],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'category_name' => ['required', 'string', 'max:255'],
            'brand_name' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image'],
        ];
    }
}
