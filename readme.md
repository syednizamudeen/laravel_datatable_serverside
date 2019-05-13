<img alt="GitHub" src="https://img.shields.io/github/license/syednizamudeen/laravel_datatable_serverside.svg">
<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>

# Installation using github

### Step 1:

Clone Repository

`git clone https://github.com/syednizamudeen/laravel_datatable_serverside.git`

Clone Repository (_from branch_)

`git clone -b dev-table-relation https://github.com/syednizamudeen/laravel_datatable_serverside.git datatables`
### Step 2:
`cd laravel_datatable_serverside`

`cp .env.example .env` _[or]_ `copy .env.example .env` 

and enter necessary configurations including database detail

### Step 3:
Install Plugins required by application using Package Manager

`composer install`

`npm install`

### Step 4:
`php artisan key:generate`

### Step 5:
Create Database and run migration

`create database datatableserverside;`

`php artisan migrate`

### Step 6:
Sample Data

`php artisan db:seed --class=HomeSeeder`

`php artisan db:seed --class=DetailsTableSeeder`

### Step 7:
Application ready for use

`php artisan serve` _[or]_ `php artisan serve --host=some.other.domain --port=8001`

# F.A.Q.

### Icons not Loaded:

Filename: webpack.mix.js

`.setResourceRoot('../../public/');`

Comment or modify this line based on your Server Setup.
