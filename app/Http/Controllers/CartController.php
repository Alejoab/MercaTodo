<?php

namespace App\Http\Controllers;

use App\Contracts\Actions\Carts\AddProductCart;
use App\Contracts\Actions\Carts\DeleteProductCart;
use App\Http\Requests\CartRequest;
use App\Services\Carts\CartsService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CartController extends Controller
{
    /**
     * Shows the user's cart
     *
     * @param CartsService $service
     *
     * @return Response
     */
    public function index(CartsService $service): Response
    {
        return Inertia::render('Order/ViewCart', [
            'cart' => $service->getCartWithProducts(auth()->id()),
        ]);
    }

    /**
     * Adds a product to the user's cart
     *
     * @param CartRequest    $request
     * @param AddProductCart $action
     *
     * @return void
     */
    public function addProductToCart(CartRequest $request, AddProductCart $action): void
    {
        $action->execute($request->user()->id, $request->validated());
    }

    /**
     * Deletes a product from the user's cart
     *
     * @param Request           $request
     * @param DeleteProductCart $action
     *
     * @return void
     */
    public function deleteProductToCart(Request $request, DeleteProductCart $action): void
    {
        $action->execute(auth()->id(), $request->all());
    }

    /**
     * Returns the number of items in the user's cart
     *
     * @param Request      $request
     * @param CartsService $service
     *
     * @return int
     */
    public function getNumberOfItems(Request $request, CartsService $service): int
    {
        return $request->user() !== null ? $service->getNumberOfItems(auth()->id()) : 0;
    }
}
