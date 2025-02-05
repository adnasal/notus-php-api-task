<?php

namespace App;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Provider
{
    private Client $client;
    private string $baseUrl = "https://dummyjson.com/products";

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * generic API request handler
     *
     * @param string $endpoint API endpoint (e.g., "/products" or "/products/1")
     * @param array $queryParams optional query parameters for GET requests
     * @return array decoded JSON response or error message
     */
    private function fetchFromApi(string $endpoint, array $queryParams = []): array
    {
        try {
            $response = $this->client->get($this->baseUrl . $endpoint, [
                'query' => $queryParams
            ]);

            return json_decode($response->getBody()->getContents(), true) ?? [];
        } catch (GuzzleException $e) {
            return $this->errorResponse("API request has failed with error: " . $e->getMessage());
        }
    }

    /**
     * fetch a list of products with pagination and sorting
     *
     * @param int $limit number of products per page
     * @param int $skip number of products to skip
     * @param string $sortBy field to sort by
     * @param string $order sort order (asc, desc)
     * @return array list of products or error message
     */
    public function getProducts(int $limit = 10, int $skip = 0, string $sortBy = "id", string $order = "asc"): array
    {
        // fetch the data from the API
        $response = $this->fetchFromApi("", [
            'limit' => $limit,
            'skip' => $skip,
            'sortBy' => $sortBy,
            'order' => $order
        ]);

        // ensure the response contains 'products'
        if (!isset($response['products']) || !is_array($response['products'])) {
            return [];
        }

        // calculate total pages based on the total number of products and the limit
        $totalProducts = count($response['products']);
        $totalPages = ($totalProducts > 0) ? ceil($totalProducts / $limit) : 0;

        // return the structured response
        return [
            'products' => $response['products'],
            'totalPages' => $totalPages,
            'total' => $totalProducts
        ];
    }

    /**
     * fetch a single product by its ID (can be changed for any other parameter)
     *
     * @param int $id product ID
     * @return array product details or error message
     */
    public function getProductById(int $id): array
    {
        return $this->fetchFromApi("/$id");
    }

    /**
     * search for products by a query string
     *
     * @param string $query search query
     * @return array search results or error message
     */
    public function searchProducts(string $query): array
    {
        return $this->fetchFromApi("/search", ['q' => $query]);
    }

    /**
     * standardized error response format
     *
     * @param string $message error message
     * @return array formatted error response
     */
    private function errorResponse(string $message): array
    {
        return [
            'success' => false,
            'error' => $message
        ];
    }
}