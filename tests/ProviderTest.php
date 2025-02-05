<?php

use PHPUnit\Framework\TestCase;
use App\Provider;

class ProviderTest extends TestCase
{
    private Provider $provider;

    // set up provider instance before each test
    protected function setUp(): void
    {
        error_reporting(E_ALL | E_DEPRECATED);
        $this->provider = new Provider();
    }

    // test fetching products with valid parameters
    public function testGetProductsWithValidParameters()
    {
        $response = $this->provider->getProducts(10, 0, 'title');
        $this->assertIsArray($response);
        $this->assertArrayHasKey('products', $response);
        $this->assertGreaterThan(0, $response['totalPages']);
        $this->assertArrayHasKey('total', $response);
        $this->assertNotEmpty($response['products']);
    }

    // test pagination by changing limit and skip
    public function testGetProductsWithPagination()
    {
        $responsePage1 = $this->provider->getProducts(10, 0, 'title');
        $responsePage2 = $this->provider->getProducts(10, 10, 'title');

        $this->assertNotEquals($responsePage1['products'], $responsePage2['products']);
    }

    // test sorting by price
    public function testGetProductsSortedByPrice()
    {
        $response = $this->provider->getProducts(10, 0, 'price');
        $this->assertIsArray($response);
        $this->assertArrayHasKey('products', $response);

        // extract the prices from the products
        $prices = array_map(fn($product) => $product['price'], $response['products']);

        // create a copy of the prices array for sorting
        $pricesSorted = $prices;

        // sort the copy of the prices array
        sort($pricesSorted);

        // assert that the original prices array is equal to the sorted array
        $this->assertTrue($prices === $pricesSorted, "The prices are not sorted correctly.");
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
        $this->assertStringContainsString('404 Not Found', $response['error']);
    }

    // test searching products
    public function testSearchProducts()
    {
        $response = $this->provider->searchProducts('laptop');
        $this->assertIsArray($response);
        $this->assertArrayHasKey('products', $response);
        $this->assertGreaterThan(0, count($response['products']));
    }

}