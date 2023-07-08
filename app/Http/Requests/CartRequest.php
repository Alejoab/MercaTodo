<?php

namespace App\Http\Requests;

use App\Domain\Products\Models\Product;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CartRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        /**
         * @var ?Product $product
         */
        $product = Product::query()->find($this->request->get('product_id'));

        return [
            'product_id' => ['required', Rule::exists('products', 'id')],
            'quantity' => ['required', 'min:1', 'integer', "max:".($product?->stock ?? 0)],
        ];
    }
}
