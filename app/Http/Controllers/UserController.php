<?php

namespace App\Http\Controllers;

use App\Domain\Products\Services\BrandsService;
use App\Domain\Products\Services\CategoriesService;
use App\Domain\Products\Services\ProductsService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    /**
     * Shows the home page for the user.
     *
     * @param Request                                         $request
     * @param \App\Domain\Products\Services\ProductsService   $productsService
     * @param \App\Domain\Products\Services\CategoriesService $categoriesService
     * @param \App\Domain\Products\Services\BrandsService     $brandsService
     *
     * @return Response
     */
    public function index(Request $request, ProductsService $productsService, CategoriesService $categoriesService, BrandsService $brandsService): Response
    {
        $search = $request->get('search');
        $category = $request->get('category');
        $brands = $request->get('brand');
        $sort = $request->get('sortBy');

        return Inertia::render('User/Home', [
            'products' => $productsService->listProducts($search, $category, $brands, $sort),
            'categories' => $categoriesService->list(),
            'brands' => $brandsService->brandsByCategory($category),
        ]);
    }
}
