<?php

namespace App\Providers;

use App\Actions\Carts\AddProductCartAction;
use App\Actions\Carts\DeleteProductCartAction;
use App\Actions\Customers\CreateCustomerAction;
use App\Actions\Customers\UpdateCustomerAction;
use App\Actions\Orders\AcceptOrderAction;
use App\Actions\Orders\CreateOrderAction;
use App\Actions\Orders\CreateOrderDetailAction;
use App\Actions\Orders\DeleteOrderAction;
use App\Actions\Orders\RejectOrderAction;
use App\Actions\Orders\RestoreProductsAction;
use App\Actions\Orders\UpdateOrderAction;
use App\Actions\Products\CreateProductAction;
use App\Actions\Products\DeleteProductAction;
use App\Actions\Products\ForceDeleteProductAction;
use App\Actions\Products\RestoreProductAction;
use App\Actions\Products\UpdateProductAction;
use App\Actions\Users\CreateUserAction;
use App\Actions\Users\DeleteUserAction;
use App\Actions\Users\ForceDeleteUserAction;
use App\Actions\Users\RestoreUserAction;
use App\Actions\Users\UpdateUserAction;
use App\Actions\Users\UpdateUserPasswordAction;
use App\Actions\Users\UpdateUserRoleAction;
use App\Contracts\Actions\Carts\AddProductCart;
use App\Contracts\Actions\Carts\DeleteProductCart;
use App\Contracts\Actions\Customers\CreateCustomer;
use App\Contracts\Actions\Customers\UpdateCustomer;
use App\Contracts\Actions\Orders\AcceptOrder;
use App\Contracts\Actions\Orders\CreateOrder;
use App\Contracts\Actions\Orders\CreateOrderDetail;
use App\Contracts\Actions\Orders\DeleteOrder;
use App\Contracts\Actions\Orders\RejectOrder;
use App\Contracts\Actions\Orders\RestoreProducts;
use App\Contracts\Actions\Orders\UpdateOrder;
use App\Contracts\Actions\Products\CreateProduct;
use App\Contracts\Actions\Products\DeleteProduct;
use App\Contracts\Actions\Products\ForceDeleteProduct;
use App\Contracts\Actions\Products\RestoreProduct;
use App\Contracts\Actions\Products\UpdateProduct;
use App\Contracts\Actions\Users\CreateUser;
use App\Contracts\Actions\Users\DeleteUser;
use App\Contracts\Actions\Users\ForceDeleteUser;
use App\Contracts\Actions\Users\RestoreUser;
use App\Contracts\Actions\Users\UpdateUser;
use App\Contracts\Actions\Users\UpdateUserPassword;
use App\Contracts\Actions\Users\UpdateUserRole;
use Illuminate\Support\ServiceProvider;

class ActionServiceProvider extends ServiceProvider
{
    public array $bindings
        = [
            CreateUser::class => CreateUserAction::class,
            UpdateUser::class => UpdateUserAction::class,
            UpdateUserPassword::class => UpdateUserPasswordAction::class,
            UpdateUserRole::class => UpdateUserRoleAction::class,
            DeleteUser::class => DeleteUserAction::class,
            RestoreUser::class => RestoreUserAction::class,
            ForceDeleteUser::class => ForceDeleteUserAction::class,

            CreateCustomer::class => CreateCustomerAction::class,
            UpdateCustomer::class => UpdateCustomerAction::class,

            CreateProduct::class => CreateProductAction::class,
            UpdateProduct::class => UpdateProductAction::class,
            DeleteProduct::class => DeleteProductAction::class,
            RestoreProduct::class => RestoreProductAction::class,
            ForceDeleteProduct::class => ForceDeleteProductAction::class,

            AddProductCart::class => AddProductCartAction::class,
            DeleteProductCart::class => DeleteProductCartAction::class,

            CreateOrder::class => CreateOrderAction::class,
            CreateOrderDetail::class => CreateOrderDetailAction::class,
            AcceptOrder::class => AcceptOrderAction::class,
            DeleteOrder::class => DeleteOrderAction::class,
            RejectOrder::class => RejectOrderAction::class,
            UpdateOrder::class => UpdateOrderAction::class,
            RestoreProducts::class => RestoreProductsAction::class,
        ];
}
