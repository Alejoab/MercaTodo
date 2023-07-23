<?php

namespace App\Http\Controllers\Web\Products;

use App\Domain\Products\Contracts\CreateProduct;
use App\Domain\Products\Contracts\DeleteProduct;
use App\Domain\Products\Contracts\ForceDeleteProduct;
use App\Domain\Products\Contracts\RestoreProduct;
use App\Domain\Products\Contracts\UpdateProduct;
use App\Domain\Products\Models\Product;
use App\Domain\Products\Resources\ProductResource;
use App\Domain\Products\Services\BrandsService;
use App\Domain\Products\Services\CategoriesService;
use App\Domain\Products\Services\ProductsService;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdminProductController extends Controller
{
    public function index(Request $request, ProductsService $productsService, CategoriesService $categoriesService, BrandsService $brandsService): Response
    {
        $search = $request->get('search');
        $category = $request->integer('category') === 0 ? null : $request->integer('category');
        $brand = $request->integer('brand') === 0 ? null : $request->integer('brand');

        $products = $productsService->listProductsAdmin($search, $category, $brand);

        return Inertia::render('Administrator/Products/Index', [
            'products' => fn() => ProductResource::collection($products),
            'categories' => fn() => $categoriesService->list(),
            'brands' => fn() => $brandsService->brandsByCategory($category),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Administrator/Products/CreateProduct');
    }

    public function productShow(Product $product): Response
    {
        return Inertia::render('Administrator/Products/EditProduct', [
            'product' => $product->load(['category:id,name', 'brand:id,name']),
        ]);
    }

    public function store(ProductRequest $request, CreateProduct $createProductAction): RedirectResponse
    {
        $createProductAction->execute($request->validated());

        return redirect()->route('admin.products');
    }

    public function update(ProductRequest $request, Product $product, UpdateProduct $updateProductAction): RedirectResponse
    {
        $updateProductAction->execute($product, $request->validated());

        return redirect()->route('admin.products.update', $product->getKey());
    }

    public function destroy(Product $product, DeleteProduct $deleteProductAction): void
    {
        $deleteProductAction->execute($product);
    }

    public function restore(Product $product, RestoreProduct $restoreProductAction): void
    {
        $restoreProductAction->execute($product);
    }

    public function forceDelete(Product $product, ForceDeleteProduct $forceDeleteProductAction): void
    {
        $forceDeleteProductAction->execute($product);
    }

    public function searchCategories(Request $request, CategoriesService $categoriesService): Collection|array
    {
        return $categoriesService->searchCategories($request->get('search'));
    }

    public function searchBrands(Request $request, BrandsService $brandsService): Collection|array
    {
        return $brandsService->searchBrands($request->get('search'));
    }
}
