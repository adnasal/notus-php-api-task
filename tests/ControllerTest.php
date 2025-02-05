<?php

use PHPUnit\Framework\TestCase;
use App\Controller;
use App\Provider;
use App\Parser;

class ControllerTest extends TestCase
{
    private $controller;
    private $providerMock;
    private $parserMock;

    // set up controller instance before each test
    protected function setUp(): void
    {
        $this->providerMock = $this->createMock(Provider::class);
        $this->parserMock = $this->createMock(Parser::class);
        $this->controller = new Controller($this->providerMock, $this->parserMock);
    }

    // test get products controller method
    public function testGetProducts()
    {
        $products = [
            'products' => [['id' => 1, 'title' => 'Product 1', 'price' => 10.99]],
            'totalPages' => 1
        ];

        $this->providerMock->expects($this->once())
            ->method('getProducts')
            ->willReturn($products);

        $this->parserMock->expects($this->once())
            ->method('parseProductList')
            ->with($products)
            ->willReturn(['data' => $products['products'], 'meta' => ['total_pages' => 1, 'page' => 1, 'per_page' => 10]]);

        $response = $this->controller->getProducts(10, 0, 'title', 'asc');
        $this->assertArrayHasKey('data', $response);
        $this->assertCount(1, $response['data']);
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
            'products' => [['id' => 1, 'title' => 'Product 1', 'price' => 10.99]],
            'totalPages' => 1
        ];

        $this->providerMock->expects($this->once())
            ->method('searchProducts')
            ->with('laptop')
            ->willReturn($products);

        $this->parserMock->expects($this->once())
            ->method('parseProductList')
            ->with($products)
            ->willReturn(['data' => $products['products'], 'meta' => ['total_pages' => 1, 'page' => 1, 'per_page' => 10]]);

        $response = $this->controller->searchProducts('laptop');
        $this->assertArrayHasKey('data', $response);
        $this->assertCount(1, $response['data']);
    }
}