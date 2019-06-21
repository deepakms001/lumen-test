# Agriculture Process Management API with Swagger 

This project contains APIs to manage agriculture process on fields and its documentation in Swagger

Project consists of following features
  - Auth module
  - Crops management 
  - Fields management
  - Tractors management
  - Process Fields
  - Reports


#### Prerequisites 

This project is build in laravel lumen 5.8, the server should have the following lumen requirements.

- PHP >= 7.1.3
- OpenSSL PHP Extension
- PDO PHP Extension
- Mbstring PHP Extension


#### How to setup?

- Download or clone this repo 

- Open terminal inside the project folder 

- Run below command to setup the lumen project
```sh 
composer install
```
- Run below command to copy the env file
```sh 
cp .env.example .env
```

- Create database and update the database, username and password fields on .env file
```sh 
.
.
DB_DATABASE=YOUR_DATABASE_NAME
DB_USERNAME=YOUR_DATABASE_USERNAME
DB_PASSWORD=YOUR_DATABASE_PASSWORD
.
.
.
```


- Run migration command 
```sh 
php artisan migrate
```
- Run seeds command
```sh 
php artisan db:seed*
```

Crops seed will create default crops : "Wheat","Broccoli" and "Strawberry"

Users seed creates following test users

| Name | Email | Password | Role |
| ------ | ------ | ------- | ----- |
| Admin | admin@test.com | admin | Admin |
| Supervisor | supervisor@test.com | supervisor | Supervisor |
| Customer1 | customer1@test.com | customer | Customer |
| Customer2 | customer2@test.com | customer | Customer |
| Customer3 | customer3@test.com | customer | Customer |
| Customer4 | customer3@test.com | customer | Customer |


- Start server by running command
```sh 
php -S localhost:8000 -t public
```

- Now the project is all setup and APIs will be available at [http://localhost:8000/](http://localhost:8000/)

Swagger UI is available at [http://localhost:8000/swagger-ui/](http://localhost:8000/swagger-ui/)

Swagger JSON file is available at [http://localhost:8000/swagger-ui/swagger.json](http://localhost:8000/swagger-ui/swagger.json)

