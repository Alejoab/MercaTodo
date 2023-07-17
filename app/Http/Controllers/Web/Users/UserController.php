<?php

namespace App\Http\Controllers\Web\Users;

use App\Domain\Products\Resources\ProductResource;
use App\Domain\Products\Services\BrandsService;
use App\Domain\Products\Services\CategoriesService;
use App\Domain\Products\Services\ProductsService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    /**
     * Shows the home page for the user.
     *
     * @param Request           $request
     * @param ProductsService   $productsService
     * @param CategoriesService $categoriesService
     * @param BrandsService     $brandsService
     *
     * @return Response
     */
    public function index(Request $request, ProductsService $productsService, CategoriesService $categoriesService, BrandsService $brandsService): Response
    {
        $search = $request->get('search');
        $category = $request->integer('category') === 0 ? null : $request->integer('category');
        $brands = $request->get('brand');
        $sort = $request->integer('sortBy');

        $products = $productsService->listProducts($search, $category, $brands, $sort);

        return Inertia::render('User/Home', [
            'products' => ProductResource::collection($products),
            'categories' => $categoriesService->list(),
            'brands' => $brandsService->brandsByCategory($category),
        ]);
    }
}
