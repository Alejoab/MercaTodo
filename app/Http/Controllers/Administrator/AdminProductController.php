<?php

namespace App\Http\Controllers\Administrator;

use App\Contracts\Actions\Products\CreateProduct;
use App\Contracts\Actions\Products\DeleteProduct;
use App\Contracts\Actions\Products\ForceDeleteProduct;
use App\Contracts\Actions\Products\RestoreProduct;
use App\Contracts\Actions\Products\UpdateProduct;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Services\Products\BrandsService;
use App\Services\Products\CategoriesService;
use App\Services\Products\ProductsService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
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
     * @return Response
     */
    public function index(): Response
    {
        return Inertia::render('Administrator/Products/Index');
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
     * @param Product $product
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
     * @param ProductRequest $request
     * @param CreateProduct  $createProductAction
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
     * @param ProductRequest $request
     * @param Product        $product
     * @param UpdateProduct  $updateProductAction
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
     * @param Product       $product
     * @param DeleteProduct $deleteProductAction
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
     * @param Product        $product
     * @param RestoreProduct $restoreProductAction
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
     * @param Product            $product
     * @param ForceDeleteProduct $forceDeleteProductAction
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
     * @param Request       $request
     * @param BrandsService $brandsService
     *
     * @return Collection|array
     */
    public function searchBrands(Request $request, BrandsService $brandsService): Collection|array
    {
        return $brandsService->searchBrands($request->get('search'));
    }

    /**
     * @param Request         $request
     * @param ProductsService $productsService
     *
     * @return LengthAwarePaginator
     */
    public function listProducts(Request $request, ProductsService $productsService): LengthAwarePaginator
    {
        $search = $request->get('search');
        $category = $request->get('category');
        $brand = $request->get('brand');

        return $productsService->listProductsAdmin($search, $category, $brand);
    }
}
