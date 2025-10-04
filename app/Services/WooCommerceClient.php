<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class WooCommerceClient
{
    private string $baseUrl;

    private string $consumerKey;

    private string $consumerSecret;

    public function __construct()
    {
        $config = config('services.woocommerce');

        $this->baseUrl = rtrim((string) ($config['url'] ?? ''), '/');
        $this->consumerKey = (string) ($config['key'] ?? '');
        $this->consumerSecret = (string) ($config['secret'] ?? '');

        if ($this->baseUrl === '' || $this->consumerKey === '' || $this->consumerSecret === '') {
            throw new RuntimeException('WooCommerce API credentials are missing.');
        }
    }

    /**
     * Perform a GET request against the WooCommerce REST API.
     */
    public function get(string $endpoint, array $query = []): array
    {
        $response = $this->request()->get($this->normalizeEndpoint($endpoint), $query);

        $response->throw();

        return $response->json();
    }

    /**
     * Fetch a list of products.
     */
    public function listProducts(array $query = []): array
    {
        return $this->get('products', $query);
    }

    /**
     * Fetch a list of customers.
     */
    public function listCustomers(array $query = []): array
    {
        return $this->get('customers', $query);
    }

    /**
     * Fetch a list of orders.
     */
    public function listOrders(array $query = []): array
    {
        return $this->get('orders', $query);
    }

    /**
     * Fetch a list of product attributes.
     */
    public function listAttributes(array $query = []): array
    {
        return $this->get('products/attributes', $query);
    }

    private function request(): PendingRequest
    {
        return Http::baseUrl($this->apiRoot())
            ->withBasicAuth($this->consumerKey, $this->consumerSecret)
            ->acceptJson();
    }

    private function apiRoot(): string
    {
        if (str_contains($this->baseUrl, 'wp-json')) {
            return $this->baseUrl;
        }

        return $this->baseUrl.'/wp-json/wc/v3';
    }

    private function normalizeEndpoint(string $endpoint): string
    {
        return ltrim($endpoint, '/');
    }
}
