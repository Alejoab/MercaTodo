<?php

namespace App\Http\Controllers\Web\Products;

use App\Domain\Products\Contracts\CreateProduct;
use App\Domain\Products\Contracts\DeleteProduct;
use App\Domain\Products\Contracts\ForceDeleteProduct;
use App\Domain\Products\Contracts\RestoreProduct;
use App\Domain\Products\Contracts\UpdateProduct;
use App\Domain\Products\Models\Product;
use App\Domain\Products\Services\BrandsService;
use App\Domain\Products\Services\CategoriesService;
use App\Domain\Products\Services\ProductsService;
use App\Http\Controllers\Web\Controller;
use App\Http\Requests\ProductRequest;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdminProductController extends Controller
{
    /**
     * Shows the index products pages.
     *
     * @param Request                                         $request
     * @param ProductsService                                 $productsService
     * @param \App\Domain\Products\Services\CategoriesService $categoriesService
     * @param \App\Domain\Products\Services\BrandsService     $brandsService
     *
     * @return Response
     */
    public function index(Request $request, ProductsService $productsService, CategoriesService $categoriesService, BrandsService $brandsService): Response
    {
        $search = $request->get('search');
        $category = $request->get('category');
        $brand = $request->get('brand');

        return Inertia::render('Administrator/Products/Index', [
            'products' => fn() => $productsService->listProductsAdmin($search, $category, $brand),
            'categories' => fn() => $categoriesService->list(),
            'brands' => fn() => $brandsService->brandsByCategory($category),
        ]);
    }

    /**
     * Shows the create product page.
     *
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render('Administrator/Products/CreateProduct');
    }

    /**
     * Shows the edit product page.
     *
     * @param \App\Domain\Products\Models\Product $product
     *
     * @return Response
     */
    public function productShow(Product $product): Response
    {
        return Inertia::render('Administrator/Products/EditProduct', [
            'product' => $product->load(['category:id,name', 'brand:id,name']),
        ]);
    }

    /**
     * Creates a new product.
     *
     * @param ProductRequest                               $request
     * @param \App\Domain\Products\Contracts\CreateProduct $createProductAction
     *
     * @return RedirectResponse
     */
    public function store(ProductRequest $request, CreateProduct $createProductAction): RedirectResponse
    {
        $createProductAction->execute($request->validated());

        return redirect()->route('admin.products');
    }

    /**
     * Updates a product.
     *
     * @param ProductRequest                               $request
     * @param \App\Domain\Products\Models\Product          $product
     * @param \App\Domain\Products\Contracts\UpdateProduct $updateProductAction
     *
     * @return RedirectResponse
     */
    public function update(ProductRequest $request, Product $product, UpdateProduct $updateProductAction): RedirectResponse
    {
        $updateProductAction->execute($product, $request->validated());

        return redirect()->route('admin.products.update', $product->getKey());
    }

    /**
     * Disables a product.
     *
     * @param \App\Domain\Products\Models\Product $product
     * @param DeleteProduct                       $deleteProductAction
     *
     * @return void
     */
    public function destroy(Product $product, DeleteProduct $deleteProductAction): void
    {
        $deleteProductAction->execute($product);
    }

    /**
     * Restores a product.
     *
     * @param \App\Domain\Products\Models\Product           $product
     * @param \App\Domain\Products\Contracts\RestoreProduct $restoreProductAction
     *
     * @return void
     */
    public function restore(Product $product, RestoreProduct $restoreProductAction): void
    {
        $restoreProductAction->execute($product);
    }

    /**
     * Force deletes a product.
     *
     * @param \App\Domain\Products\Models\Product $product
     * @param ForceDeleteProduct                  $forceDeleteProductAction
     *
     * @return void
     */
    public function forceDelete(Product $product, ForceDeleteProduct $forceDeleteProductAction): void
    {
        $forceDeleteProductAction->execute($product);
    }

    /**
     * @param Request           $request
     * @param CategoriesService $categoriesService
     *
     * @return Collection|array
     */
    public function searchCategories(Request $request, CategoriesService $categoriesService): Collection|array
    {
        return $categoriesService->searchCategories($request->get('search'));
    }

    /**
     * @param Request                                     $request
     * @param \App\Domain\Products\Services\BrandsService $brandsService
     *
     * @return Collection|array
     */
    public function searchBrands(Request $request, BrandsService $brandsService): Collection|array
    {
        return $brandsService->searchBrands($request->get('search'));
    }
}
