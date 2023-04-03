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

### Administrator User Information
>ADMIN_NAME  
>ADMIN_SURNAME  
>ADMIN_DOCUMENT_TYPE  
>ADMIN_DOCUMENT  
>ADMIN_EMAIL  
>ADMIN_PHONE  
>ADMIN_ADDRESS  
>ADMIN_PASSWORD  
>ADMIN_CITY_ID  

**Notes:** The document type must be 'Identity Card' or 'Passport'. The city id must be a valid city id in the database (1, 1126).

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
