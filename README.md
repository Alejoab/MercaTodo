<div align="center">

# MercaTodo

</div>

## About MercaTodo

MercaTodo is part of the project proposed for the PHP Bootcamp by Evertec. It is an e-commerce application that allows
users to buy products.

**PHP:** `8.2.4`  
**Laravel Framework:** `10.9.0`

### Version 1.0.0

- Customers can register and log in to the application.
- Administrators can update the customer's information.
- Administrators can disable and enable the customer's account, as such force de deletion of the account.
- Email verification is required for the customer to access special features.

### Version 2.0.0

- The administrator can manage his products in such a way that he can create, update, enable and disable them.
- Registered customers will be able to see the list of products created, so that they can see a showcase of products
  separated by pages and their data such as photo and price.
- Customers will also be able to perform a customized search for these products to quickly find what they are looking
  for.

### Version 3.0.0

- Customers will be able to view available products and add them to a shopping cart.
- The customer will be able to consult his order and make modifications before confirming the order and proceeding with
  the payment.
- Customers will be able to review their order history and retry the payment for those that were not successful.
- Integrates the web checkout of the Place To Pay payment gateway.

### Version 4.0.0

- The administrator will be able to massively import to the system a list of products in Excel.
- The administrator will be able to download an Excel list of the registered products in order to modify them and upload
  them again to the system in a massive way.
- The administrator will be able to generate system reports with relevant information for the management of your
  business.
- The use of the system's functionalities must be allowed only to those users with permissions (ACL).
- The system must allow managing products from a REST API.

## Configuration

In order to run the application, you must do the following:

- Clone the repository.
- Create the .env file from the .env.example file.
- Configure the needed information in the .env file.
- Run `composer install` to install the dependencies.
- Run `npm install` to install the dependencies
- Run `php artisan key:generate` to generate the application key.
- Run `php artisan migrate:fresh --seed` to create the database tables.
- Run `php artisan serve` to start the application.
- Run - `php artisan storage:link` to make the images available to the application.
- Run `php artisan schedule:work` to check the order status periodically and delete the export files.
- Run `php artisan queue:work` to export and import processes.

**Note:** Note: In order to use image manipulation features in the application, the PHP `gd` extension must be enabled.
Please ensure that this extension is enabled on your server.

## .Env file

The .env file contains the configuration of the application. It is important to configure the following variables.

### Database Connection

> DB_CONNECTION  
> DB_HOST  
> DB_PORT  
> DB_DATABASE
> DB_USERNAME  
> DB_PASSWORD

### Mail Configuration

> MAIL_MAILER  
> MAIL_HOST  
> MAIL_PORT  
> MAIL_USERNAME  
> MAIL_PASSWORD  
> MAIL_ENCRYPTION  
> MAIL_FROM_ADDRESS  
> MAIL_FROM_NAME

### User Information of the Administrator

> ADMIN_NAME  
> ADMIN_SURNAME  
> ADMIN_DOCUMENT_TYPE  
> ADMIN_DOCUMENT  
> ADMIN_EMAIL  
> ADMIN_PHONE  
> ADMIN_ADDRESS  
> ADMIN_PASSWORD  
> ADMIN_CITY_ID

**Notes:** The document type must be CC, NIT or RUT. The city id must be a valid city id in the database (1, 1126).

### Seeders Configuration

> BRAND_SEEDER  
> CATEGORY_SEEDER  
> USER_SEEDER  
> PRODUCT_SEEDER
> ORDER_SEEDER

**Note:** The value of the category seeder must not be greater than 62.

### PlaceToPay Configuration

> PLACETOPAY_LOGIN  
> PLACETOPAY_TRANKEY  
> PLACETOPAY_URL

**Note:** These credentials are provided by PlaceToPay.

## Migrations and Seeders

The application has migrations and seeds to create the database tables and populate them with the necessary information.
To do this, you must run the following commands:

- `php artisan migrate` to create the database tables.
- `php artisan db:seed` to populate the database tables.

If you want to run the migrations and seeds in a single command, you can run the following command:

- `php artisan migrate:fresh --seed`

## Roles and Permissions

The application has a role and permission management system. The roles and permissions are created in the database
seeds. The roles are:

- Super Admin
- Admin
- Customer

The permissions are:

- Create
- Update
- Delete

The Super Admin role has all the permissions. The Admin role has no permissions, but it can be assigned to a user. The
Customer role has no permissions and cannot be assigned to a user.

## Product Images

The application is configured to store all product images in the `storage/app/public/product_images` folder.

To make the images available to the application, you must run the following command:

- `php artisan storage:link`

In the case that you want to change the folder where the images are stored, you must change the path of
the `product_images` driver in the `config/filesystems.php` file.

## Payment Gateway

The configuration of the expiration time for an order is in the `config/payment.php` file. This value is in minutes.

While an order is active, the customer can retry the payment as many times as he wants. If the order expires, the
customer will not be able to retry the payment and will have to create a new order.

To check the order status periodically, you must run the following command:

- `php artisan schedule:work`

By default, this action is executed every minute. If you want to change the frequency, you must change the value of
the `app:check-payment-session` command in the `app/Console/Kernel.php` file.

## Export Files

The application allows the administrator to export the list of products and the list of orders in Excel format. These
files are stored in the `storage/app/export_files` folder.
After a certain time, these files are deleted from the folder. The time and the path of the folder is configured in
the `config/filesystem.php` file.

To delete the files automatically, you must run the following command:

- `php artisan schedule:work`

By default, this action is executed every ten minutes. If you want to change the frequency, you must change the value of
the `app:delete-export-files` command in the `app/Console/Kernel.php` file.

Additionally, all the export and import processes are executed in the background using queues. To execute the queues,
you must run the following command:

- `php artisan queue:work`

## REST API

The application allows the administrator to manage the products through a REST API. The documentation of the API is
available in the following link: [MercaTodo API](https://documenter.getpostman.com/view/28417506/2s946fet9q)
