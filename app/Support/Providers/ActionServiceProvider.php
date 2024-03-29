<?php

namespace App\Support\Providers;

use App\Domain\Carts\Actions\AddProductCartAction;
use App\Domain\Carts\Actions\DeleteProductCartAction;
use App\Domain\Carts\Contracts\AddProductCart;
use App\Domain\Carts\Contracts\DeleteProductCart;
use App\Domain\Customers\Actions\CreateCustomerAction;
use App\Domain\Customers\Actions\UpdateCustomerAction;
use App\Domain\Customers\Contracts\CreateCustomer;
use App\Domain\Customers\Contracts\UpdateCustomer;
use App\Domain\Orders\Actions\AcceptOrderAction;
use App\Domain\Orders\Actions\CreateOrderAction;
use App\Domain\Orders\Actions\CreateOrderDetailAction;
use App\Domain\Orders\Actions\DeleteOrderAction;
use App\Domain\Orders\Actions\RejectOrderAction;
use App\Domain\Orders\Actions\RestoreProductsAction;
use App\Domain\Orders\Actions\UpdateOrderAction;
use App\Domain\Orders\Contracts\AcceptOrder;
use App\Domain\Orders\Contracts\CreateOrder;
use App\Domain\Orders\Contracts\CreateOrderDetail;
use App\Domain\Orders\Contracts\DeleteOrder;
use App\Domain\Orders\Contracts\RejectOrder;
use App\Domain\Orders\Contracts\RestoreProducts;
use App\Domain\Orders\Contracts\UpdateOrder;
use App\Domain\Products\Actions\CreateProductAction;
use App\Domain\Products\Actions\DeleteProductAction;
use App\Domain\Products\Actions\ForceDeleteProductAction;
use App\Domain\Products\Actions\RestoreProductAction;
use App\Domain\Products\Actions\UpdateProductAction;
use App\Domain\Products\Contracts\CreateProduct;
use App\Domain\Products\Contracts\DeleteProduct;
use App\Domain\Products\Contracts\ForceDeleteProduct;
use App\Domain\Products\Contracts\RestoreProduct;
use App\Domain\Products\Contracts\UpdateProduct;
use App\Domain\Users\Actions\CreateUserAction;
use App\Domain\Users\Actions\DeleteUserAction;
use App\Domain\Users\Actions\ForceDeleteUserAction;
use App\Domain\Users\Actions\RestoreUserAction;
use App\Domain\Users\Actions\UpdateUserAction;
use App\Domain\Users\Actions\UpdateUserPasswordAction;
use App\Domain\Users\Actions\UpdateUserRoleAction;
use App\Domain\Users\Contracts\CreateUser;
use App\Domain\Users\Contracts\DeleteUser;
use App\Domain\Users\Contracts\ForceDeleteUser;
use App\Domain\Users\Contracts\RestoreUser;
use App\Domain\Users\Contracts\UpdateUser;
use App\Domain\Users\Contracts\UpdateUserPassword;
use App\Domain\Users\Contracts\UpdateUserRole;
use App\Support\Actions\CreateJobsByUserAction;
use App\Support\Contracts\CreateJobsByUser;
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

            CreateJobsByUser::class => CreateJobsByUserAction::class,
        ];
}
