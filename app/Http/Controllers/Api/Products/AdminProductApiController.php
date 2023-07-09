<?php

namespace App\Http\Controllers\Api\Products;

use App\Domain\Products\Contracts\CreateProduct;
use App\Domain\Products\Contracts\DeleteProduct;
use App\Domain\Products\Contracts\ForceDeleteProduct;
use App\Domain\Products\Contracts\RestoreProduct;
use App\Domain\Products\Contracts\UpdateProduct;
use App\Domain\Products\Models\Product;
use App\Domain\Products\Resources\ProductCollection;
use App\Domain\Products\Resources\ProductCollectionResource;
use App\Domain\Products\Resources\ProductResource;
use App\Domain\Products\Services\ProductsService;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminProductApiController extends Controller
{
    public function index(Request $request, ProductsService $productsService): JsonResponse
    {
        $search = $request->get('search');
        $category = $request->get('category');
        $brand = $request->get('brand');

        $products = $productsService->listProductsAdmin($search, $category, $brand);

        return response()->json([
            'message' => 'Products retrieved successfully',
            'data' => ProductResource::collection($products)->resource,
        ]);
    }

    public function store(ProductRequest $request, CreateProduct $createProductAction): JsonResponse
    {
        $product = $createProductAction->execute($request->validated());

        return response()->json([
            'message' => 'Product created successfully',
            'data' => new ProductResource($product),
        ], 201);
    }

    public function show(Product $product): JsonResponse
    {
        return response()->json([
            'message' => 'Product retrieved successfully',
            'data' => new ProductResource($product),
        ]);
    }

    public function update(ProductRequest $request, Product $product, UpdateProduct $updateProductAction): JsonResponse
    {
        $product = $updateProductAction->execute($product, $request->validated());

        return response()->json([
            'message' => 'Product update successfully',
            'data' => new ProductResource($product),
        ]);
    }

    public function destroy(Product $product, DeleteProduct $deleteProductAction): JsonResponse
    {
        $deleteProductAction->execute($product);

        return response()->json([
            'message' => 'Product deleted successfully',
        ]);
    }

    public function restore(Product $product, RestoreProduct $restoreProductAction): JsonResponse
    {
        $restoreProductAction->execute($product);

        return response()->json([
            'message' => 'Product restored successfully',
        ]);
    }

    public function forceDelete(Product $product, ForceDeleteProduct $forceDeleteProductAction): JsonResponse
    {
        $forceDeleteProductAction->execute($product);

        return response()->json([
            'message' => 'Product force deleted successfully',
        ]);
    }
}
