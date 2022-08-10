## How to boot the PetShopAPI demo application
   Please make sure you have installed the following dependencies on your environment
   
   - Composer version 2.3.10
   - PHP 8.1.8
   - mysql version 8.0.29

1) Clone the GitHub repository from https://github.com/SikandarShabbir/PetShopAPI.git link.

2) Change the directory to PetShopAPI.
    - `cd PetShopAPI/`
3) Copy .env example and rename to .env in the root directory. 
    - `cp .env.example .env`
4) Run `composer install`
5) Run `php artisan key:generate`
6) Configure database in .env file.
	- DB_CONNECTION=mysql
	- DB_HOST=127.0.0.1
	- DB_PORT=3306
	- DB_DATABASE=database_name
	- DB_USERNAME=your_database_user_name
	- DB_PASSWORD=your_database_password

7) Run `php artisan migrate --seed`
- This will seed an Admin and User account in the database with 10 additional user accounts.
    - **Admin Account** 
    - username: admin@gmail.com
    - Password: admin

    - **User Account** 
    - username: user@gmail.com
    - Password: user

8) Run `php artisan serve`

9) Go to link http://127.0.0.1:8000/api/documentation for Swagger API GUI.

10) Login with admin credentials 
	
11) Copy the token from the response and paste it into Authorization Popup.

And test the PetShopAPI Demo.

## Code quality and static analysis
 **Tools like larastan, phpinsights and IDE helper are also configured with the project.**
 - To Analyze the code quality 
    - Run `php artisan insights`
 
 - For Code Analysis
    - Run `./vendor/bin/phpstan analyse`

 - Laravel IDE Helper Generator also configured with the project
    - PHPDoc generation for Laravel Facades
        - Run `php artisan ide-helper:generate`
    
    - PHPDocs for models 
        - Run `php artisan ide-helper:models`


Thanks.
