## Overview
This is a small Laravel API application that provides a /products endpoint to retrieve a list of products, with the ability to filter by category and price, and with discounts applied according to certain rules.

The application is containerized using Docker and uses MySQL as the database.

### Features:
 REST API Endpoint /api/products:

- Returns a list of products with discounts applied when necessary.
- Can be filtered by category.
- Can be filtered by priceLessThan.
- Returns at most 5 elements.
- Discount Rules:

- Products in the boots category have a 30% discount.
- The product with sku = 000003 has a 15% discount.
- When multiple discounts apply, the highest discount is used.
Performance Consideration:

The application is designed to handle more than 20,000 products efficiently.
Testing:

Includes unit tests to verify functionality and ensure the application is working as expected.

### Table of Contents
Getting Started

Prerequisites:
1. Clone the Repository
2. Configure the Environment
3. Build and Start the Docker Containers
4. Install Composer Dependencies
5. Generate the Application Key
6. Run Migrations and Seeders
7. Access the Application
8. Run the Tests


### Prerequisites
Docker and Docker Compose installed on your machine.
Git installed to clone the repository.
1. Clone the Repository
Clone the repository to your local machine:


```bash
git clone https://github.com/naidev7/mytheresa-promotions-test.git
cd promotions
```


2. Configure the Environment
Copy the .env.example file to .env and adjust any variables if necessary:

```bash
cp .env.example .env
Note: The provided .env file is already configured to work with the Docker environment. You shouldn't need to change anything unless you have specific requirements.
```

3. Build and Start the Docker Containers
Build the Docker images and start the containers:

```bash
docker-compose up -d --build
```

- The -d flag runs the containers in detached mode.
- The --build flag builds the images before starting the containers.

4. Install Composer Dependencies
Install Laravel dependencies inside the application container:


```bash
docker-compose exec app composer install
```

5. Generate the Application Key
Generate a new application key for Laravel:

```bash
docker-compose exec app php artisan key:generate
```

6. Run Migrations and Seeders
Run the database migrations and seed the database with initial data:

```bash
docker-compose exec app php artisan migrate --seed
```

7. Access the Application
You can now access the API endpoint in your browser or via a tool like Postman:

```bash
http://localhost:8000/api/products
```

8. Run the Tests
You can run the test suite to ensure everything is working as expected:

```bash
docker-compose exec app php artisan test
```

### API Usage
- Endpoint: GET /api/products
- Query Parameters: 
  - category (optional): Filter products by category (boots, sandals, sneakers).
  -  priceLessThan (optional): Filter products with an original price less than or equal to this value (before discounts).
- Example Requests
Get all products:

```bash
http://localhost:8000/api/products
Get products in the "boots" category:

http://localhost:8000/api/products?category=boots
Get products with an original price less than or equal to 80000:

http://localhost:8000/api/products?priceLessThan=80000
Get products in the "sandals" category with an original price less than or equal to 80000:

http://localhost:8000/api/products?category=sandals&priceLessThan=80000
```
### Response Format
The API returns a JSON response with a list of products, each with the following structure:

Product with Discount Applied:
```bash
json

{
  "sku": "000001",
  "name": "BV Lean leather ankle boots",
  "category": "boots",
  "price": {
    "original": 89000,
    "final": 62300,
    "discount_percentage": "30%",
    "currency": "EUR"
  }
}
Product without Discount:

json
Copia el codi
{
  "sku": "000005",
  "name": "Nathane leather sneakers",
  "category": "sneakers",
  "price": {
    "original": 59000,
    "final": 59000,
    "discount_percentage": null,
    "currency": "EUR"
  }
}
```

## Application Structure and Decisions
Dockerization
The application is containerized using Docker and Docker Compose for consistent environments and ease of setup.

#### Dockerfile:

```bash
Base Image: php:8.2-apache.
PHP Extensions: Installs required PHP extensions (pdo_mysql, zip).
Apache Configuration: Enables mod_rewrite for Laravel's routing.
Application Code: Copies the application code into the container.
Composer Installation: Installs Composer inside the container.
Node.js Installation: Installs Node.js (if needed for future development).
docker-compose.yml:
```

#### Services:

```bash
app: The Laravel application container.
db: A MySQL 5.7 database container.
phpmyadmin: For database management (optional).
Volumes:

Mounts the application code into the container for live updates during development.
Persists database data using Docker volumes.
Networks:

Uses a custom bridge network laravel_network for container communication.
```

#### Database
MySQL is used as the database.
Migrations and Seeders are used to set up the database schema and initial data.
The initial products are seeded into the database using a seeder (ProductSeeder).

#### Models and Controllers
Product Model (App\Models\Product):

- Key Features:
- - Custom primary key (sku).
- - Eloquent Scopes: scopeCategory, scopePriceLessThan for filtering.
- - Discount Methods: calculateMaxDiscount, applyDiscount.
- - Product Controller (App\Http\Controllers\ProductController):

#### Responsibilities:
Handles the /products endpoint.
Retrieves and filters products based on query parameters.
Applies discounts and formats the response according to the requirements.

Test Suite located in tests/Feature/ProductControllerTest.php.

##### Tests Include:

- Retrieving all products with discounts applied.
- Applying the highest discount when multiple discounts apply.
- Filtering products by category.
- Filtering products by price before discounts.
- Limiting the response to five products.

Testing Framework:

Uses Laravel's built-in testing tools. Utilizes factories and the RefreshDatabase trait for test isolation.

### Decisions Taken

Separation of Concerns:

Business logic related to products (like discount calculations) is encapsulated within the Product model.
This promotes clean code, maintainability, and adheres to the Single Responsibility Principle.
Eloquent Scopes:

Used for filtering by category and price, making queries more readable and reusable.


## Contact
For any questions or suggestions, please contact Naidaly Ruiz at naidalyruiz@gmail.com or my Linkedin [@naidalyruiz](https://www.linkedin.com/in/naidalyruiz/).
