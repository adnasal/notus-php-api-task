<?php

require __DIR__ . '/vendor/autoload.php';

use App\Controller;
use Bramus\Router\Router;

$router = new Router();

$controller = new Controller();

// define route for fetching a list of products
$router->get('/products', function () use ($controller) {
    // get products (adjust the parameters as needed)
    $products = $controller->getProducts(10, 0, 'title', 'asc');

    // output the products as JSON
    echo json_encode($products);
});

// define route for fetching a single product by ID
$router->get('/product/(\d+)', function ($id) use ($controller) {
    // get single product by ID
    $product = $controller->getSingleProduct($id);

    // output the product as JSON
    echo json_encode($product);
});

// define route for searching products by query
$router->get('/search/(\w+)', function ($query) use ($controller) {
    // search products by query
    $searchResults = $controller->searchProducts($query);

    // output the search results as JSON
    echo json_encode($searchResults);
});

$router->run();