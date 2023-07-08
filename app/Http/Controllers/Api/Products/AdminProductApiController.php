<?php

namespace App\Http\Controllers\Api\Products;

use App\Domain\Products\Contracts\CreateProduct;
use App\Domain\Products\Contracts\UpdateProduct;
use App\Domain\Products\Models\Product;
use App\Domain\Products\Resources\ProductResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminProductApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request, CreateProduct $createProductAction): JsonResponse
    {
        $product = $createProductAction->execute($request->validated());

        return response()->json([
            'message' => 'Product created successfully',
            'data' => new ProductResource($product),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, Product $product, UpdateProduct $updateProductAction)
    {
        $product = $updateProductAction->execute($product, $request->validated());

        return response()->json([
            'message' => 'Product update successfully',
            'data' => new ProductResource($product),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function restore(string $id)
    {
        //
    }
}
