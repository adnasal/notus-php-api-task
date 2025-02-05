<?php

require __DIR__ . '/../vendor/autoload.php';
require_once '../app/Provider.php';
require_once '../app/Parser.php';
require_once '../app/Controller.php';

use App\Provider;
use App\Parser;
use App\Controller;
use Bramus\Router\Router;

$router = new Router();
$provider = new Provider();
$parser = new Parser();
$controller = new Controller($provider, $parser);

function renderHtml($title, $content): string
{
    return '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>' . $title . '</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                background-color: #f4f4f9;
                color: #333;
            }
            .container {
                max-width: 900px;
                margin: 50px auto;
                padding: 20px;
                background-color: white;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }
            h1 {
                color: #4CAF50;
            }
            h2 {
                color: #555;
            }
            p {
                font-size: 16px;
                line-height: 1.6;
            }
            .project-info {
                background-color: #f0f0f0;
                padding: 15px;
                border-radius: 8px;
                margin-top: 20px;
            }
            .project-info ul {
                list-style-type: none;
                padding-left: 0;
            }
            .project-info li {
                margin: 5px 0;
            }
        </style>
    </head>
    <body>
        <div class="container">
            ' . $content . '
        </div>
    </body>
    </html>
    ';
}

$router->get('/', function () {
    $response = [
        'message' => 'Welcome to the BE-PHP-TASK API!',
        'author' => 'Adna Salković',
        'description' => 'This project is an API solution for listing and searching products using data from the DummyJSON API.',
        'project_info' => [
            'technologies' => 'PHP, Guzzle, Router, PHPUnit',
            'purpose' => 'The API provides product listings, individual product details, and search functionality.',
        ],
        'contact' => 'For more information, visit GitHub repository or contact robert.jarec@notus.hr',
    ];

    $content = '
    <h1>' . $response['message'] . '</h1>
    <h2>About the API</h2>
    <p><strong>Author:</strong> ' . $response['author'] . '</p>
    <p><strong>Description:</strong> ' . $response['description'] . '</p>

    <div class="project-info">
        <h3>Project Information:</h3>
        <ul>
            <li><strong>Technologies:</strong> ' . $response['project_info']['technologies'] . '</li>
            <li><strong>Purpose:</strong> ' . $response['project_info']['purpose'] . '</li>
        </ul>
    </div>

    <p><strong>Contact:</strong> ' . $response['contact'] . '</p>
    ';

    echo renderHtml('BE-PHP-TASK API', $content);
});

// define route for fetching a list of products
$router->get('/products', function () use ($controller) {
    $products = $controller->getProducts();

    header('Content-Type: application/json');

    echo json_encode($products, JSON_PRETTY_PRINT);
});

// define route for fetching a single product by ID
$router->get('/products/(\d+)', function ($id) use ($controller) {
    $product = $controller->getSingleProduct($id);

    header('Content-Type: application/json');

    echo json_encode($product, JSON_PRETTY_PRINT);
});

// define route for searching products by query
$router->get('/products/search/(\w+)', function ($query) use ($controller) {
    $searchResults = $controller->searchProducts($query);

    header('Content-Type: application/json');

    echo json_encode($searchResults, JSON_PRETTY_PRINT);
});

// define route for empty search
$router->get('/products/search/', function () {
    $content = '
    <h1>Search Products</h1>
    <p><strong>Error:</strong> Query parameter is missing. Please provide a search term in the URL like /products/search/query.</p>
    ';
    echo renderHtml('Product Search Error', $content);
});

$router->run();