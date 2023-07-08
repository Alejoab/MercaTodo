<?php

namespace App\Http\Controllers\Web\Products;

use App\Domain\Products\Models\Product;
use App\Domain\Products\Services\ProductsService;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    /**
     * Shows the product page for the customer.
     *
     * @param Product $product
     *
     * @return Response
     */
    public function show(Product $product): Response
    {
        return Inertia::render('User/Product', [
            'product' => $product->load(['brand:id,name']),
        ]);
    }

    /**
     * Lists the products for the customer.
     *
     * @param Request         $request
     * @param ProductsService $productsService
     *
     * @return LengthAwarePaginator
     */
    public function listProducts(Request $request, ProductsService $productsService): LengthAwarePaginator
    {
        $search = $request->get('search');
        $category = $request->get('category');
        $brands = $request->get('brand');
        $sort = $request->get('sortBy');

        return $productsService->listProducts($search, $category, $brands, $sort);
    }

    public function productInformation(Product $product): Product
    {
        return $product;
    }
}
