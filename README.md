# Product API System

This project is a PHP-based system that interacts with an external API to fetch product data, parse it, and provide structured output. It follows a modular approach with `Provider`, `Parser`, and `Controller` classes, along with PHPUnit tests.

## **Technologies Used**
![PHP](https://img.shields.io/badge/-PHP-777BB4?style=flat&logo=php&logoColor=white)  
![MySQL](https://img.shields.io/badge/-MySQL-05122A?style=flat&logo=mysql&logoColor=4479A1)  
![Composer](https://img.shields.io/badge/-Composer-885630?style=flat&logo=composer&logoColor=white)  
![PHPUnit](https://img.shields.io/badge/-PHPUnit-FF9800?style=flat&logo=php&logoColor=white)

---

## **1. Provider Class**

### **Description**
The `Provider` class handles interactions with an external API to fetch product data. It provides methods to retrieve product lists, search for products, and fetch single product details.

### **Methods**

#### **`getProducts(int $limit, int $skip, string $sortBy, string $order): array`**
- **description**: fetches a paginated list of products from the external API.
- **parameters**:
    - `$limit` *(int)* – number of products per request.
    - `$skip` *(int)* – number of products to skip (for pagination).
    - `$sortBy` *(string)* – field to sort by (e.g., 'price', 'title').
    - `$order` *(string)* – sorting order ('asc' or 'desc').
- **returns**: array containing `products`, `totalPages`, and `total`.

#### **`getProductById(int $id): array`**
- **description**: fetches a single product based on the given ID.
- **parameters**:
    - `$id` *(int)* – unique product identifier.
- **returns**: array containing product details.

#### **`searchProducts(string $query): array`**
- **description**: searches for products using a keyword.
- **parameters**:
    - `$query` *(string)* – search term.
- **returns**: array of matched products.

---

## **2. Parser Class**

### **Description**
The `Parser` class processes raw API data and formats it into a structured response. It converts stock values into readable text and formats pricing.

### **Methods**

#### **`parseProductList(array $data, int $page, int $perPage): array`**
- **description**: processes a list of products, applying pagination metadata.
- **parameters**:
    - `$data` *(array)* – raw product data from API.
    - `$page` *(int)* – current page number.
    - `$perPage` *(int)* – items per page.
- **returns**: formatted array with products and pagination details.

#### **`parseSingleProduct(array $product): array`**
- **description**: processes a single product and formats pricing and stock information.
- **parameters**:
    - `$product` *(array)* – raw product details from API.
- **returns**: formatted product details.

---

## **3. Controller Class**

### **Description**
The `Controller` class manages the interaction between the `Provider` and `Parser`, handling API requests and returning structured responses.

### **Methods**

#### **`getProducts(int $limit, int $skip, string $sortBy, string $order): array`**
- **description**: fetches and formats a paginated list of products.
- **parameters**: same as `Provider::getProducts()`.
- **returns**: formatted product list with metadata.

#### **`getSingleProduct(int $id): array`**
- **description**: fetches and formats a single product.
- **parameters**: `$id` *(int)* – product ID.
- **returns**: formatted product details.

#### **`searchProducts(string $query): array`**
- **description**: fetches and formats search results.
- **parameters**: `$query` *(string)* – search keyword.
- **returns**: formatted search results.

---
## **4. Unit Tests**

The project includes PHPUnit tests for `Provider`, `Parser`, and `Controller` classes.

### **Running Tests**
Run the following command to execute unit tests:

```sh
vendor/bin/phpunit tests/
```
---
## **5. Installation & Setup**

### **Clone the Repository**
```sh
git clone https://github.com/adnasal/notus-php-api-task.git
cd notus-php-api-task
```

### **Install Dependencies**
```sh
composer install
```

### **Run the Application**
```sh
php -S localhost:8000 -t public
```