<?php

namespace App\Domain\Products\Resources;

use App\Domain\Products\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Product
 */
class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     *
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
            'category' => new CategoryResource($this->category),
            'brand' => new BrandResource($this->brand),
            'price' => $this->price,
            'stock' => $this->stock,
            'status' => $this->getStatusAttribute($this->deleted_at),
            'image' => $this->image,
            'created_at' => $this->created_at,
            'updated_at' => $this->when($this->updated_at->ne($this->created_at), $this->updated_at),
        ];
    }
}
