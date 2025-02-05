<?php

use PHPUnit\Framework\TestCase;
use App\Parser;

class ParserTest extends TestCase
{
    private Parser $parser;

    // set up parser instance before each test
    protected function setUp(): void
    {
        error_reporting(E_ALL | E_DEPRECATED);
        $this->parser = new Parser();
    }

    // test parsing a list of products
    public function testParseProductList()
    {
        $data = [
            'products' => [
                ['id' => 1, 'title' => 'Product 1', 'price' => 10.99, 'stock' => 10, 'thumbnail' => 'image1.jpg'],
                ['id' => 2, 'title' => 'Product 2', 'price' => 20.99, 'stock' => 0, 'thumbnail' => 'image2.jpg']
            ],
            'totalPages' => 2,
        ];
        $parsed = $this->parser->parseProductList($data, 1, 2);

        $this->assertArrayHasKey('data', $parsed);
        $this->assertArrayHasKey('meta', $parsed);
        $this->assertCount(2, $parsed['data']);
    }

    // test parsing a single product
    public function testParseSingleProduct()
    {
        $data = ['id' => 1, 'title' => 'Product 1', 'price' => 10.99, 'stock' => 10, 'thumbnail' => 'image1.jpg'];
        $parsed = $this->parser->parseSingleProduct($data);

        $this->assertArrayHasKey('id', $parsed);
        $this->assertEquals(1, $parsed['id']);
        $this->assertArrayHasKey('price', $parsed);
        $this->assertEquals('€10.99', $parsed['price']);
    }

    // test parsing stock status for out-of-stock products
    public function testParseStockStatusNoStock()
    {
        $data = ['id' => 1, 'title' => 'Product 1', 'price' => 10.99, 'stock' => 0, 'thumbnail' => 'image1.jpg'];
        $parsed = $this->parser->parseSingleProduct($data);

        $this->assertEquals('No stock available.', $parsed['stock']);
    }

    // test parsing stock status for products with low stock
    public function testParseStockStatusLowStock()
    {
        $data = ['id' => 1, 'title' => 'Product 1', 'price' => 10.99, 'stock' => 3, 'thumbnail' => 'image1.jpg'];
        $parsed = $this->parser->parseSingleProduct($data);

        $this->assertEquals('Stock is containing few products!', $parsed['stock']);
    }

    // test parsing stock status for in-stock products
    public function testParseStockStatusInStock()
    {
        $data = ['id' => 1, 'title' => 'Product 1', 'price' => 10.99, 'stock' => 10, 'thumbnail' => 'image1.jpg'];
        $parsed = $this->parser->parseSingleProduct($data);

        $this->assertEquals('On stock.', $parsed['stock']);
    }
}
