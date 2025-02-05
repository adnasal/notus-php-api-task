<?php

namespace App;

class Controller
{
    private Provider $provider;
    private Parser $parser;

    public function __construct($provider, $parser)
    {
        $this->provider = $provider;
        $this->parser = $parser;
    }

    /**
     * handle fetching a list of products with pagination and sorting
     *
     * @param int $limit number of products per page
     * @param int $skip number of products to skip (offset)
     * @param string $sortBy field to sort by (default is 'title')
     * @param string $order order of sorting (default is 'asc')
     * @return array parsed product list with pagination
     */
    public function getProducts(int $limit = 10, int $skip = 0, string $sortBy = 'title', string $order = 'asc'): array
    {
        // fetch raw data from provider
        $data = $this->provider->getProducts($limit, $skip, $sortBy, $order);

        // parse and return formatted data
        return $this->parser->parseProductList($data, $skip / $limit + 1, $limit);
    }

    /**
     * handle fetching a single product by id
     *
     * @param int $id product id
     * @return array parsed product details
     */
    public function getSingleProduct(int $id): array
    {
        // fetch raw data for a single product
        $data = $this->provider->getProductById($id);

        // parse and return formatted data
        return $this->parser->parseSingleProduct($data);
    }

    /**
     * handle searching for products based on query
     *
     * @param string $query search query
     * @return array parsed product list matching the query
     */
    public function searchProducts(string $query): array
    {
        // fetch raw search results from provider
        $data = $this->provider->searchProducts($query);

        // parse and return formatted data
        return $this->parser->parseProductList($data, 1, count($data['products']));
    }
}
