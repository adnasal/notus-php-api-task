<?php

use PHPUnit\Framework\TestCase;
use App\Provider;
use GuzzleHttp\Exception\GuzzleException;

class ProviderTest extends TestCase
{
    private $provider;

    // set up provider instance before each test
    protected function setUp(): void
    {
        $this->provider = new Provider();
    }

    // test fetching products with valid parameters
    public function testGetProductsWithValidParameters()
    {
        $response = $this->provider->getProducts(10, 0, 'title', 'asc');
        $this->assertIsArray($response);
        $this->assertArrayHasKey('products', $response);
        $this->assertGreaterThan(0, $response['totalPages']);
        $this->assertArrayHasKey('total', $response);
        $this->assertNotEmpty($response['products']);
    }

    // test pagination by changing limit and skip
    public function testGetProductsWithPagination()
    {
        $responsePage1 = $this->provider->getProducts(10, 0, 'title', 'asc');
        $responsePage2 = $this->provider->getProducts(10, 10, 'title', 'asc');

        $this->assertNotEquals($responsePage1['products'], $responsePage2['products']);
    }

    // test sorting by price
    public function testGetProductsSortedByPrice()
    {
        $response = $this->provider->getProducts(10, 0, 'price', 'asc');
        $this->assertIsArray($response);
        $this->assertArrayHasKey('products', $response);
        $prices = array_map(fn($product) => $product['price'], $response['products']);
        $this->assertTrue($prices === array_sort($prices)); // assuming array_sort is a function that sorts the prices
    }

    // test fetching a single product by ID
    public function testGetProductById()
    {
        $response = $this->provider->getProductById(1);
        $this->assertIsArray($response);
        $this->assertArrayHasKey('id', $response);
        $this->assertEquals(1, $response['id']);
    }

    // test API failure scenario (e.g., invalid endpoint)
    public function testGetProductByIdWithInvalidId()
    {
        $response = $this->provider->getProductById(99999); // assuming this ID doesn't exist
        $this->assertArrayHasKey('error', $response);
        $this->assertEquals('API request has failed with error: Not Found', $response['error']);
    }

    // test searching products
    public function testSearchProducts()
    {
        $response = $this->provider->searchProducts('laptop');
        $this->assertIsArray($response);
        $this->assertArrayHasKey('products', $response);
        $this->assertGreaterThan(0, count($response['products']));
    }

    // test API failure during search (e.g., malformed query)
    public function testSearchProductsWithInvalidQuery()
    {
        $response = $this->provider->searchProducts('');
        $this->assertArrayHasKey('error', $response);
        $this->assertEquals('API request has failed with error: Invalid query parameter', $response['error']);
    }
}