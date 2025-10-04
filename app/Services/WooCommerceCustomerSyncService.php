<?php

namespace App\Services;

use App\Models\Customer;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class WooCommerceCustomerSyncService
{
    public function __construct(private readonly WooCommerceClient $client) {}

    public function syncAll(int $perPage = 50, ?callable $progressCallback = null): int
    {
        $page = 1;
        $total = 0;

        do {
            $synced = $this->syncPage($page, $perPage);
            $total += $synced;

            if ($progressCallback !== null) {
                $progressCallback($page, $synced, $total);
            }

            $page++;
        } while ($synced === $perPage && $synced > 0);

        return $total;
    }

    public function syncPage(int $page = 1, int $perPage = 50): int
    {
        $customers = $this->client->listCustomers([
            'page' => $page,
            'per_page' => $perPage,
            'orderby' => 'date',
            'order' => 'desc',
        ]);

        foreach ($customers as $customer) {
            try {
                $this->syncCustomer($customer);
            } catch (Throwable $exception) {
                Log::error('Failed to sync WooCommerce customer', [
                    'customer_id' => Arr::get($customer, 'id'),
                    'message' => $exception->getMessage(),
                ]);
            }
        }

        return count($customers);
    }

    protected function syncCustomer(array $payload): Customer
    {
        return DB::transaction(function () use ($payload) {
            $customer = Customer::updateOrCreate(
                ['woocommerce_id' => Arr::get($payload, 'id')],
                $this->mapCustomerAttributes($payload)
            );

            $this->syncMeta($customer, Arr::get($payload, 'meta_data', []));

            return $customer;
        });
    }

    protected function syncMeta(Customer $customer, array $meta): void
    {
        $customer->metaData()->delete();

        foreach ($meta as $item) {
            $customer->metaData()->create([
                'woocommerce_id' => Arr::get($item, 'id'),
                'meta_key' => Arr::get($item, 'key'),
                'meta_value' => Arr::get($item, 'value'),
                'display_key' => Arr::get($item, 'display_key'),
                'display_value' => Arr::get($item, 'display_value'),
            ]);
        }
    }

    protected function mapCustomerAttributes(array $payload): array
    {
        $lastOrderId = $this->nullableInt(Arr::get($payload, 'last_order_id'));

        return [
            'email' => Arr::get($payload, 'email'),
            'first_name' => Arr::get($payload, 'first_name'),
            'last_name' => Arr::get($payload, 'last_name'),
            'role' => Arr::get($payload, 'role'),
            'username' => Arr::get($payload, 'username'),
            'billing' => Arr::get($payload, 'billing', []),
            'shipping' => Arr::get($payload, 'shipping', []),
            'is_paying_customer' => Arr::get($payload, 'is_paying_customer', false),
            'avatar_url' => Arr::get($payload, 'avatar_url'),
            'last_order_id' => $lastOrderId,
            'last_order_number' => Arr::get($payload, 'last_order_number'),
            'last_order_date' => $this->nullable(Arr::get($payload, 'last_order_date')),
            'last_order_date_gmt' => $this->nullable(Arr::get($payload, 'last_order_date_gmt')),
            'orders_count' => (int) Arr::get($payload, 'orders_count', 0),
            'total_spent' => $this->numericOrZero(Arr::get($payload, 'total_spent')),
            'date_created' => $this->nullable(Arr::get($payload, 'date_created')),
            'date_created_gmt' => $this->nullable(Arr::get($payload, 'date_created_gmt')),
            'date_modified' => $this->nullable(Arr::get($payload, 'date_modified')),
            'date_modified_gmt' => $this->nullable(Arr::get($payload, 'date_modified_gmt')),
            'links' => Arr::get($payload, '_links'),
        ];
    }

    protected function nullable(mixed $value): mixed
    {
        if ($value === '' || $value === []) {
            return null;
        }

        return $value;
    }

    protected function nullableInt(mixed $value): ?int
    {
        if ($value === null || $value === '' || $value === 0 || $value === '0') {
            return null;
        }

        return (int) $value;
    }

    protected function numericOrZero(mixed $value): float
    {
        if ($value === null || $value === '') {
            return 0.0;
        }

        return (float) $value;
    }
}
