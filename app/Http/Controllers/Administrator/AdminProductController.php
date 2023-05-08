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
     * @param CreateProduct  $action
     *
     * @return RedirectResponse
     */
    public function store(ProductRequest $request, CreateProduct $action): RedirectResponse
    {
        $action->execute($request->validated());

        return redirect()->route('admin.products');
    }

    /**
     * Updates a product.
     *
     * @param ProductRequest $request
     * @param Product        $product
     * @param UpdateProduct  $action
     *
     * @return RedirectResponse
     */
    public function update(ProductRequest $request, Product $product, UpdateProduct $action): RedirectResponse
    {
        $action->execute($product, $request->validated());

        return redirect()->route('admin.products.update', $product->getKey());
    }

    /**
     * Disables a product.
     *
     * @param Product       $product
     * @param DeleteProduct $action
     *
     * @return void
     */
    public function destroy(Product $product, DeleteProduct $action): void
    {
        $action->execute($product);
    }

    /**
     * Restores a product.
     *
     * @param Product        $product
     * @param RestoreProduct $action
     *
     * @return void
     */
    public function restore(Product $product, RestoreProduct $action): void
    {
        $action->execute($product);
    }

    /**
     * Force deletes a product.
     *
     * @param Product            $product
     * @param ForceDeleteProduct $action
     *
     * @return void
     */
    public function forceDelete(Product $product, ForceDeleteProduct $action): void
    {
        $action->execute($product);
    }

    /**
     * @param Request           $request
     * @param CategoriesService $service
     *
     * @return Collection|array
     */
    public function searchCategories(Request $request, CategoriesService $service): Collection|array
    {
        return $service->searchCategories($request->get('search'));
    }

    /**
     * @param Request       $request
     * @param BrandsService $service
     *
     * @return Collection|array
     */
    public function searchBrands(Request $request, BrandsService $service): Collection|array
    {
        return $service->searchBrands($request->get('search'));
    }

    /**
     * @param Request         $request
     * @param ProductsService $service
     *
     * @return LengthAwarePaginator
     */
    public function listProducts(Request $request, ProductsService $service): LengthAwarePaginator
    {
        $search = $request->get('search');
        $category = $request->get('category');
        $brand = $request->get('brand');

        return $service->listProductsAdmin($search, $category, $brand);
    }
}
