<?php

namespace App\Http\Controllers\Web\Carts;

use App\Domain\Carts\Contracts\AddProductCart;
use App\Domain\Carts\Contracts\DeleteProductCart;
use App\Domain\Carts\Services\CartsService;
use App\Http\Controllers\Web\Controller;
use App\Http\Requests\CartRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CartController extends Controller
{
    /**
     * Shows the user's cart
     *
     * @param CartsService $cartsService
     *
     * @return Response
     */
    public function index(CartsService $cartsService): Response
    {
        return Inertia::render('Order/ViewCart', [
            'cart' => $cartsService->getCartWithProducts(auth()->id()),
        ]);
    }

    /**
     * Adds a product to the user's cart
     *
     * @param CartRequest                                $request
     * @param \App\Domain\Carts\Contracts\AddProductCart $addProductCartAction
     *
     * @return void
     */
    public function addProductToCart(CartRequest $request, AddProductCart $addProductCartAction): void
    {
        $addProductCartAction->execute($request->user()->id, $request->validated());
    }

    /**
     * Deletes a product from the user's cart
     *
     * @param Request                                       $request
     * @param \App\Domain\Carts\Contracts\DeleteProductCart $deleteProductCartAction
     *
     * @return void
     */
    public function deleteProductToCart(Request $request, DeleteProductCart $deleteProductCartAction): void
    {
        $deleteProductCartAction->execute(auth()->id(), $request->all());
    }

    /**
     * Returns the number of items in the user's cart
     *
     * @param Request      $request
     * @param CartsService $cartsService
     *
     * @return int
     */
    public function getNumberOfItems(Request $request, CartsService $cartsService): int
    {
        return $request->user() !== null ? $cartsService->getNumberOfItems(auth()->id()) : 0;
    }
}
