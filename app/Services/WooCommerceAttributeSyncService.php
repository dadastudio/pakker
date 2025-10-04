<?php

namespace App\Services;

use App\Models\Attribute;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class WooCommerceAttributeSyncService
{
    public function __construct(private readonly WooCommerceClient $client) {}

    public function syncAll(int $perPage = 50, ?callable $progressCallback = null): int
    {

        $this->truncateAllProductTables();

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

    public function syncPage(int $page = 1, int $perPage = 1): int
    {
        $attributes = $this->client->listAttributes([
            'page' => $page,
            'per_page' => $perPage,
            'orderby' => 'id',
            'order' => 'asc',
        ]);

        foreach ($attributes as $attribute) {
            try {
                $this->syncAttribute($attribute);
            } catch (Throwable $exception) {
                Log::error('Failed to sync WooCommerce attribute', [
                    'attribute_id' => Arr::get($attribute, 'id'),
                    'message' => $exception->getMessage(),
                ]);
            }
        }

        return count($attributes);
    }

    protected function truncateAllProductTables(): void
    {
        $tables = [
            'attributes',

        ];

        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            foreach ($tables as $table) {
                DB::table($table)->truncate();
                DB::statement("ALTER TABLE {$table} AUTO_INCREMENT = 1");
            }

            Log::info('All attributes tables truncated and AUTO_INCREMENT reset successfully');
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    protected function syncAttribute(array $payload): Attribute
    {
        return DB::transaction(function () use ($payload) {
            return Attribute::updateOrCreate(
                ['woocommerce_id' => Arr::get($payload, 'id')],
                $this->mapAttributeAttributes($payload)
            );
        });
    }

    protected function mapAttributeAttributes(array $payload): array
    {
        return [
            'name' => Arr::get($payload, 'name'),
            'slug' => Arr::get($payload, 'slug'),
            'type' => Arr::get($payload, 'type'),
            'order_by' => Arr::get($payload, 'order_by'),
            'has_archives' => Arr::get($payload, 'has_archives', false),
        ];
    }
}
