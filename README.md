<div align="center">

# MercaTodo

</div>

## About MercaTodo

MercaTodo is part of the project proposed for the PHP Bootcamp by Evertec. It is an e-commerce application that allows users to buy products.

### Version 1.0.0
- Customers can register and log in to the application.
- Administrators can update the customer's information.
- Administrators can disable and enable the customer's account, as such force de deletion of the account.
- Email verification is required for the customer to access special features.

### Version 2.0.0
- The administrator can manage his products in such a way that he can create, update, enable and disable them.
- Registered customers will be able to see the list of products created, so that they can see a showcase of products separated by pages and their data such as photo and price.
- Customers will also be able to perform a customized search for these products to quickly find what they are looking for.

### Version 3.0.0
- Customers will be able to view available products and add them to a shopping cart.
- The customer will be able to consult his order and make modifications before confirming the order and proceeding with the payment.
- Customers will be able to review their order history and retry the payment for those that were not successful.
- Integrates the web checkout of the Place To Pay payment gateway.

## Configuration

In order to run the application, you must do the following:

- Clone the repository.
- Create the .env file from the .env.example file.
- Configure the needed information in the .env file.
- Run `composer install` to install the dependencies.
- Run `npm install` to install the dependencies
- Run `php artisan key:generate` to generate the application key.
- Run `php artisan migrate:fresh --seed` to create the database tables.
- Run `npm run dev` to compile the assets.
- Run `php artisan serve` to start the application.
- Run - `php artisan storage:link` to make the images available to the application.

## .Env file

The .env file contains the configuration of the application. It is important to configure the following variables. 

### Database Connection
>DB_CONNECTION  
>DB_HOST  
>DB_PORT  
>DB_DATABASE
>DB_USERNAME  
>DB_PASSWORD

### Mail Configuration

>MAIL_MAILER  
>MAIL_HOST  
>MAIL_PORT  
>MAIL_USERNAME  
>MAIL_PASSWORD  
>MAIL_ENCRYPTION  
>MAIL_FROM_ADDRESS  
>MAIL_FROM_NAME  

### User Information of the Administrator
>ADMIN_NAME  
>ADMIN_SURNAME  
>ADMIN_DOCUMENT_TYPE  
>ADMIN_DOCUMENT  
>ADMIN_EMAIL  
>ADMIN_PHONE  
>ADMIN_ADDRESS  
>ADMIN_PASSWORD  
>ADMIN_CITY_ID  

**Notes:** The document type must be CC, NIT or RUT. The city id must be a valid city id in the database (1, 1126).

### Seeders Configuration
>BRAND_SEEDER  
>CATEGORY_SEEDER  
>USER_SEEDER  
>PRODUCT_SEEDER 

### Place To Pay Configuration
>PLACETOPAY_LOGIN  
>PLACETOPAY_TRANKEY  
>PLACETOPAY_URL  

## Migrations and Seeds

The application has migrations and seeds to create the database tables and populate them with the necessary information. To do this, you must run the following commands:

- `php artisan migrate` to create the database tables.
- `php artisan db:seed` to populate the database tables.

If you want to run the migrations and seeds in a single command, you can run the following command:

- `php artisan migrate:fresh --seed`

If you want to run the migrations but not the user seed, you can run the following command:

- `php artisan migrate` to create the database tables.
- `php artisan db:seed --class=DepartmentsCitiesSeeder` to populate the cities table.
- `php artisan db:seed --class=RolesSeeder` to populate the products table.

## Product Images

The application is configured to store all product images in the `storage/app/public/product_images` folder. By default, the folder is created when the main seeder is executed. In the case that you do not want to execute the seeder you must create the folder manually. 

To make the images available to the application, you must run the following command:

- `php artisan storage:link`

In the case that you want to change the folder where the images are stored, you must change the path of the `product_images` driver in the `config/filesystems.php` file.

## Payment Gateway
The configuration of the expiration time for an order is in the `config/payment.php` file. This value is in minutes.

While an order is active, the customer can retry the payment as many times as he wants. If the order expires, the customer will not be able to retry the payment and will have to create a new order.

To check the order status periodically, you must run the following command:

- `php artisan schedule:work`

By default, this action is executed every minute. If you want to change the frequency, you must change the value of the `app:check-payment-session` command in the `app/Console/Kernel.php` file.
