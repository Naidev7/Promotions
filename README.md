# Mytheresa Promotions Test

## Clone the Repository:
```bash
git clone https://github.com/your-username/mytheresa-promotions-test.git 
cd mytheresa-promotions-test 



## Configure the .env File:
Copy the .env.example file to .env and adjust any variables if necessary:

bash
Copia el codi


Build and Start the Containers:
bash
Copia el codi
docker-compose up -d --build

Install Composer Dependencies Inside the Container:
bash
Copia el codi
docker-compose exec app composer install

# MyTheresa Promotions Test

clone the repository

```bash
  git clone https://github.com/your-username/mytheresa-promotions-test.git 
cd mytheresa-promotions-test 
```
    
## Configure the .env File:
Copy the .env.example file to .env and adjust any variables if necessary.

## Build and Start the Containers
```bash
 docker-compose up -d --build
```

## Install Composer Dependencies Inside the Containers
```bash
docker-compose exec app composer install
```

## Generate the Application Key:

```bash
docker-compose exec app php artisan key:generate
```

## Run Migrations and Seeders:

```bash
docker-compose exec app php artisan migrate --seed
```

## Access the Application:
Open your browser and go to http://localhost:8000/api/products.

## Run the Tests:
```bash
docker-compose exec app php artisan test
```

Generate the Application Key:
bash
Copia el codi

docker-compose exec app php artisan key:generate


Run Migrations and Seeders:
bash
Copia el codi
docker-compose exec app php artisan migrate --seed


Access the Application:
Open your browser and go to http://localhost:8000/api/products.

Run the Tests:
bash
Copia el codi
docker-compose exec app php artisan test
