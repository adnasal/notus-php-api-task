<?php

use PHPUnit\Framework\TestCase;
use App\Controller;
use App\Provider;
use App\Parser;

class ControllerTest extends TestCase
{
    private Controller $controller;
    private Provider $providerMock;
    private Parser $parserMock;

    // set up controller instance before each test
    protected function setUp(): void
    {
        error_reporting(E_ALL | E_DEPRECATED);

        // create mocks and handle any errors that may occur
        try {
            $this->providerMock = $this->createMock(Provider::class);
        } catch (\PHPUnit\Framework\MockObject\Exception $e) {
            $this->fail("Failed to create mock for Provider: " . $e->getMessage());
        }

        try {
            $this->parserMock = $this->createMock(Parser::class);
        } catch (\PHPUnit\Framework\MockObject\Exception $e) {
            $this->fail("Failed to create mock for Parser: " . $e->getMessage());
        }

        $this->controller = new Controller($this->providerMock, $this->parserMock);
    }

    // test get products controller method
    public function testGetProducts()
    {
        $products = [
            'products' => array_map(function ($i) {
                return [
                    'id' => $i,
                    'title' => "Product $i",
                    'price' => 10.99
                ];
            }, range(1, 10)), // Mock 10 products
            'totalPages' => 1
        ];

        $this->providerMock->expects($this->once())
            ->method('getProducts')
            ->willReturn($products);

        $this->parserMock->expects($this->once())
            ->method('parseProductList')
            ->with($products)
            ->willReturn([
                'data' => $products['products'],
                'meta' => ['total_pages' => 1, 'page' => 1, 'per_page' => 10]
            ]);

        $response = $this->controller->getProducts();
        $this->assertArrayHasKey('data', $response);
        $this->assertCount(10, $response['data']);  // Expecting 10 products
    }

    // test get single product controller method
    public function testGetSingleProduct()
    {
        $product = ['id' => 1, 'title' => 'Product 1', 'price' => 10.99];

        $this->providerMock->expects($this->once())
            ->method('getProductById')
            ->with(1)
            ->willReturn($product);

        $this->parserMock->expects($this->once())
            ->method('parseSingleProduct')
            ->with($product)
            ->willReturn($product);

        $response = $this->controller->getSingleProduct(1);
        $this->assertArrayHasKey('id', $response);
        $this->assertEquals(1, $response['id']);
    }

    // test search products controller method
    public function testSearchProducts()
    {
        $products = [
            'products' => array_map(function ($i) {
                return [
                    'id' => $i,
                    'title' => "Product $i",
                    'price' => 10.99
                ];
            }, range(1, 5)), // mock 5 products
            'totalPages' => 1
        ];

        $this->providerMock->expects($this->once())
            ->method('searchProducts')
            ->with('laptop')
            ->willReturn($products);

        $this->parserMock->expects($this->once())
            ->method('parseProductList')
            ->with($products)
            ->willReturn([
                'data' => $products['products'],
                'meta' => ['total_pages' => 1, 'page' => 1, 'per_page' => 5]
            ]);

        $response = $this->controller->searchProducts('laptop');
        $this->assertArrayHasKey('data', $response);
        $this->assertCount(5, $response['data']);
    }
}