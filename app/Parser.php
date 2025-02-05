<?php

namespace App;

class Parser
{
    /**
     * parse a list of products into the required response format
     *
     * @param array $products raw products data from the API
     * @param int $page current page number
     * @param int $perPage number of products per page
     * @return array parsed product list with pagination details
     */
    public function parseProductList(array $products, int $page, int $perPage): array
    {
        $parsedProducts = [];
        foreach ($products['products'] as $product) {
            $parsedProducts[] = $this->parseProduct($product);
        }

        return [
            'data' => $parsedProducts,
            'meta' => [
                'total_pages' => $products['totalPages'] ?? 0,
                'page' => $page,
                'per_page' => $perPage
            ]
        ];
    }

    /**
     * parse a single product into the required response format
     *
     * @param array $product raw product data from the API
     * @return array parsed product details
     */
    public function parseSingleProduct(array $product): array
    {
        return [
            'id' => $product['id'],
            'title' => $product['title'],
            'description' => $product['description'] ?? 'No description available.',
            'category' => $product['category'] ?? 'Uncategorized',
            'tags' => isset($product['tags']) && is_array($product['tags']) ? implode(', ', $product['tags']) : 'No tags available',
            'short_description' => substr($product['description'] ?? 'No description available.', 0, 30),
            'price' => $this->formatPrice($product['price']),
            'stock' => $this->getStockStatus($product['stock']),
            'thumbnail' => $product['thumbnail']
        ];
    }

    /**
     * format the price into a currency string
     *
     * @param float $price raw product price
     * @param string $currencySymbol the currency symbol (e.g. '€', '$', 'BAM')
     * @return string formatted price
     */
    private function formatPrice(float $price, string $currencySymbol = '€'): string
    {
        return $currencySymbol . number_format($price, 2);
    }

    /**
     * get the stock status based on product stock
     *
     * @param int $stock raw stock quantity
     * @return string stock status
     */
    private function getStockStatus(int $stock): string
    {
        if ($stock === 0) {
            return 'No stock available.';
        }
        if ($stock < 5) {
            return 'Stock is containing few products!';
        }
        return 'On stock.';
    }

    /**
     * parse and return product data with necessary fields
     *
     * @param array $product raw product data
     * @return array parsed product
     */
    private function parseProduct(array $product): array
    {
        return [
            'id' => $product['id'],
            'title' => $product['title'],
            'description' => $product['description'] ?? 'No description available.',
            'short_description' => substr($product['description'] ?? 'No description available.', 0, 30),
            'price' => $this->formatPrice($product['price']),
            'stock' => $this->getStockStatus($product['stock']),
            'thumbnail' => $product['thumbnail']
        ];
    }
}