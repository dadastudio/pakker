<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class WooCommerceProductSyncService
{
    protected ?int $previousProductId = null;

    protected int $productCount = 0;

    public function __construct(
        private readonly WooCommerceClient $client
    ) {}

    /**
     * Sync every product page until the API returns fewer than the requested records.
     */
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

    /**
     * Truncate all product-related tables and reset AUTO_INCREMENT.
     */
    protected function truncateAllProductTables(): void
    {
        $tables = [
            'product_variation_meta',
            'product_variation_attributes',
            'product_variation_downloads',
            'product_variations',
            'product_meta',
            'product_tags',
            'product_categories',
            'product_default_attributes',
            'product_attributes',
            'product_downloads',
            'product_images',
            'products',
        ];

        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            foreach ($tables as $table) {
                DB::table($table)->truncate();
                DB::statement("ALTER TABLE {$table} AUTO_INCREMENT = 1");
            }

            Log::info('All product tables truncated and AUTO_INCREMENT reset successfully');
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    /**
     * Sync a single page of products.
     */
    public function syncPage(int $page = 1, int $perPage = 50): int
    {
        $products = $this->client->listProducts([
            'page' => $page,
            'per_page' => $perPage,
            'orderby' => 'date',
            'order' => 'asc',
        ]);

        foreach ($products as $productPayload) {
            try {
                $this->productCount++;
                $this->syncProduct($productPayload);
            } catch (Throwable $exception) {
                Log::error('Failed to sync WooCommerce product', [
                    'product_id' => Arr::get($productPayload, 'id'),
                    'product_name' => Arr::get($productPayload, 'name'),
                    'message' => $exception->getMessage(),
                ]);
            }
        }

        return count($products);
    }

    protected function syncProduct(array $payload): Product
    {
        return DB::transaction(function () use ($payload) {
            $product = Product::updateOrCreate(
                ['woocommerce_id' => Arr::get($payload, 'id')],
                $this->mapProductAttributes($payload)
            );

            // Link every second product to the previous one (EN variant to PL parent)
            if ($this->productCount % 2 === 0 && $this->previousProductId !== null) {
                $product->update(['parent_id' => $this->previousProductId]);
            }

            // Use parent product for variations if this is a language variant
            $productForVariations = $product->parent_id
                ? Product::find($product->parent_id)
                : $product;

            $this->syncProductImages($productForVariations, Arr::get($payload, 'images', []));
            $this->syncProductDownloads($productForVariations, Arr::get($payload, 'downloads', []));
            $this->syncProductAttributesRelation($productForVariations, Arr::get($payload, 'attributes', []));
            $this->syncProductDefaultAttributes($productForVariations, Arr::get($payload, 'default_attributes', []));
            $this->syncProductCategories($productForVariations, Arr::get($payload, 'categories', []));
            $this->syncProductTags($productForVariations, Arr::get($payload, 'tags', []));
            $this->syncProductMeta($productForVariations, Arr::get($payload, 'meta_data', []));
            $this->syncProductVariations($productForVariations, Arr::get($payload, 'id'));

            // Store current product ID for next iteration
            $this->previousProductId = $product->id;

            return $product;
        });
    }

    protected function syncProductImages(Product $product, array $images): void
    {
        $product->images()->delete();

        foreach ($images as $image) {
            $product->images()->create([
                'woocommerce_id' => Arr::get($image, 'id'),
                'date_created' => $this->nullable(Arr::get($image, 'date_created')),
                'date_created_gmt' => $this->nullable(Arr::get($image, 'date_created_gmt')),
                'src' => Arr::get($image, 'src'),
                'name' => Arr::get($image, 'name'),
                'alt' => Arr::get($image, 'alt'),
                'position' => Arr::get($image, 'position'),
            ]);
        }
    }

    protected function syncProductDownloads(Product $product, array $downloads): void
    {
        $product->downloads()->delete();

        foreach ($downloads as $download) {
            $product->downloads()->create([
                'woocommerce_id' => Arr::get($download, 'id'),
                'name' => Arr::get($download, 'name'),
                'file' => Arr::get($download, 'file'),
            ]);
        }
    }

    protected function syncProductAttributesRelation(Product $product, array $attributes): void
    {
        $product->attributes()->delete();

        foreach ($attributes as $attribute) {
            $product->attributes()->create([
                'attribute_id' => Arr::get($attribute, 'id'),
                'name' => Arr::get($attribute, 'name'),
                'position' => Arr::get($attribute, 'position'),
                'visible' => Arr::get($attribute, 'visible', true),
                'variation' => Arr::get($attribute, 'variation', false),
                'options' => Arr::get($attribute, 'options', []),
            ]);
        }
    }

    protected function syncProductDefaultAttributes(Product $product, array $attributes): void
    {
        $product->defaultAttributes()->delete();

        foreach ($attributes as $attribute) {
            $product->defaultAttributes()->create([
                'attribute_id' => Arr::get($attribute, 'id'),
                'name' => Arr::get($attribute, 'name'),
                'option' => Arr::get($attribute, 'option'),
            ]);
        }
    }

    protected function syncProductCategories(Product $product, array $categories): void
    {
        $product->categories()->delete();

        foreach ($categories as $category) {
            $product->categories()->create([
                'term_id' => Arr::get($category, 'id'),
                'name' => Arr::get($category, 'name'),
                'slug' => Arr::get($category, 'slug'),
            ]);
        }
    }

    protected function syncProductTags(Product $product, array $tags): void
    {
        $product->tags()->delete();

        foreach ($tags as $tag) {
            $product->tags()->create([
                'term_id' => Arr::get($tag, 'id'),
                'name' => Arr::get($tag, 'name'),
                'slug' => Arr::get($tag, 'slug'),
            ]);
        }
    }

    protected function syncProductMeta(Product $product, array $meta): void
    {
        $product->metaData()->delete();

        $seenWooIds = [];
        foreach ($meta as $item) {
            $wooId = Arr::get($item, 'id');

            // Skip duplicate woocommerce_ids
            if (in_array($wooId, $seenWooIds, true)) {
                continue;
            }

            $seenWooIds[] = $wooId;

            $product->metaData()->create([
                'woocommerce_id' => $wooId,
                'meta_key' => Arr::get($item, 'key'),
                'meta_value' => Arr::get($item, 'value'),
                'display_key' => Arr::get($item, 'display_key'),
                'display_value' => Arr::get($item, 'display_value'),
            ]);
        }
    }

    protected function syncProductVariations(Product $product, ?int $productWooId): void
    {
        if ($productWooId === null) {
            $product->variations()->delete();

            return;
        }

        $variations = $this->fetchVariations($productWooId);
        $variationWooIds = [];

        foreach ($variations as $variationPayload) {
            $variation = ProductVariation::updateOrCreate(
                ['woocommerce_id' => Arr::get($variationPayload, 'id')],
                $this->mapVariationAttributes($product, $variationPayload)
            );

            $variationWooIds[] = $variation->woocommerce_id;

            $this->syncVariationDownloads($variation, Arr::get($variationPayload, 'downloads', []));
            $this->syncVariationAttributesRelation($variation, Arr::get($variationPayload, 'attributes', []));
            $this->syncVariationMeta($variation, Arr::get($variationPayload, 'meta_data', []));
        }

        if ($variationWooIds === []) {
            $product->variations()->delete();
        } else {
            $product->variations()->whereNotIn('woocommerce_id', $variationWooIds)->delete();
        }
    }

    protected function syncVariationDownloads(ProductVariation $variation, array $downloads): void
    {
        $variation->downloads()->delete();

        foreach ($downloads as $download) {
            $variation->downloads()->create([
                'woocommerce_id' => Arr::get($download, 'id'),
                'name' => Arr::get($download, 'name'),
                'file' => Arr::get($download, 'file'),
            ]);
        }
    }

    protected function syncVariationAttributesRelation(ProductVariation $variation, array $attributes): void
    {
        $variation->attributes()->delete();

        foreach ($attributes as $attribute) {
            $variation->attributes()->create([
                'attribute_id' => Arr::get($attribute, 'id'),
                'name' => Arr::get($attribute, 'name'),
                'option' => Arr::get($attribute, 'option'),
            ]);
        }
    }

    protected function syncVariationMeta(ProductVariation $variation, array $meta): void
    {
        $variation->metaData()->delete();

        $seenWooIds = [];
        foreach ($meta as $item) {
            $wooId = Arr::get($item, 'id');

            // Skip duplicate woocommerce_ids
            if (in_array($wooId, $seenWooIds, true)) {
                continue;
            }

            $seenWooIds[] = $wooId;

            $variation->metaData()->create([
                'woocommerce_id' => $wooId,
                'meta_key' => Arr::get($item, 'key'),
                'meta_value' => Arr::get($item, 'value'),
                'display_key' => Arr::get($item, 'display_key'),
                'display_value' => Arr::get($item, 'display_value'),
            ]);
        }
    }

    protected function fetchVariations(int $productWooId, int $perPage = 100): array
    {
        $page = 1;
        $variations = [];

        do {
            $chunk = $this->client->get("products/{$productWooId}/variations", [
                'page' => $page,
                'per_page' => $perPage,
                'orderby' => 'date',
                'order' => 'asc',
            ]);

            $variations = array_merge($variations, $chunk);
            $page++;
        } while (count($chunk) === $perPage && $chunk !== []);

        return $variations;
    }

    protected function mapProductAttributes(array $payload): array
    {
        return [
            'name' => Arr::get($payload, 'name'),
            'slug' => Arr::get($payload, 'slug'),
            'permalink' => Arr::get($payload, 'permalink'),
            'type' => Arr::get($payload, 'type'),
            'status' => Arr::get($payload, 'status'),
            'featured' => Arr::get($payload, 'featured', false),
            'catalog_visibility' => Arr::get($payload, 'catalog_visibility'),
            'description' => Arr::get($payload, 'description'),
            'short_description' => Arr::get($payload, 'short_description'),
            'sku' => Arr::get($payload, 'sku'),
            'price' => $this->nullable(Arr::get($payload, 'price')),
            'regular_price' => $this->nullable(Arr::get($payload, 'regular_price')),
            'sale_price' => $this->nullable(Arr::get($payload, 'sale_price')),
            'date_on_sale_from' => $this->nullable(Arr::get($payload, 'date_on_sale_from')),
            'date_on_sale_from_gmt' => $this->nullable(Arr::get($payload, 'date_on_sale_from_gmt')),
            'date_on_sale_to' => $this->nullable(Arr::get($payload, 'date_on_sale_to')),
            'date_on_sale_to_gmt' => $this->nullable(Arr::get($payload, 'date_on_sale_to_gmt')),
            'on_sale' => Arr::get($payload, 'on_sale', false),
            'total_sales' => Arr::get($payload, 'total_sales', 0),
            'virtual' => Arr::get($payload, 'virtual', false),
            'downloadable' => Arr::get($payload, 'downloadable', false),
            'download_limit' => Arr::get($payload, 'download_limit'),
            'download_expiry' => Arr::get($payload, 'download_expiry'),
            'external_url' => Arr::get($payload, 'external_url'),
            'button_text' => Arr::get($payload, 'button_text'),
            'tax_status' => Arr::get($payload, 'tax_status'),
            'tax_class' => Arr::get($payload, 'tax_class'),
            'manage_stock' => Arr::get($payload, 'manage_stock', false),
            'stock_quantity' => $this->nullable(Arr::get($payload, 'stock_quantity')),
            'stock_status' => Arr::get($payload, 'stock_status'),
            'backorders' => Arr::get($payload, 'backorders'),
            'backorders_allowed' => Arr::get($payload, 'backorders_allowed', false),
            'backordered' => Arr::get($payload, 'backordered', false),
            'low_stock_amount' => $this->nullable(Arr::get($payload, 'low_stock_amount')),
            'sold_individually' => Arr::get($payload, 'sold_individually', false),
            'weight' => $this->nullable(Arr::get($payload, 'weight')),
            'dimensions' => Arr::get($payload, 'dimensions'),
            'shipping_required' => Arr::get($payload, 'shipping_required', true),
            'shipping_taxable' => Arr::get($payload, 'shipping_taxable', true),
            'shipping_class' => Arr::get($payload, 'shipping_class'),
            'shipping_class_id' => Arr::get($payload, 'shipping_class_id'),
            'reviews_allowed' => Arr::get($payload, 'reviews_allowed', true),
            'average_rating' => $this->nullable(Arr::get($payload, 'average_rating')),
            'rating_count' => Arr::get($payload, 'rating_count', 0),
            'related_ids' => Arr::get($payload, 'related_ids', []),
            'upsell_ids' => Arr::get($payload, 'upsell_ids', []),
            'cross_sell_ids' => Arr::get($payload, 'cross_sell_ids', []),
            'parent_id' => Arr::get($payload, 'parent_id'),
            'purchase_note' => Arr::get($payload, 'purchase_note'),
            'variations' => Arr::get($payload, 'variations', []),
            'grouped_products' => Arr::get($payload, 'grouped_products', []),
            'menu_order' => Arr::get($payload, 'menu_order', 0),
            'price_html' => Arr::get($payload, 'price_html'),
            'has_options' => Arr::get($payload, 'has_options', false),
            'default_attributes_snapshot' => Arr::get($payload, 'default_attributes', []),
            'meta_data_snapshot' => Arr::get($payload, 'meta_data', []),
            'links' => Arr::get($payload, '_links'),
            'date_created' => $this->nullable(Arr::get($payload, 'date_created')),
            'date_created_gmt' => $this->nullable(Arr::get($payload, 'date_created_gmt')),
            'date_modified' => $this->nullable(Arr::get($payload, 'date_modified')),
            'date_modified_gmt' => $this->nullable(Arr::get($payload, 'date_modified_gmt')),
        ];
    }

    protected function mapVariationAttributes(Product $product, array $payload): array
    {
        $manageStock = Arr::get($payload, 'manage_stock', false);
        // Handle 'parent' string value - convert to false
        if ($manageStock === 'parent') {
            $manageStock = false;
        }

        return [
            'product_id' => $product->id,
            'product_woocommerce_id' => $product->woocommerce_id,
            'woocommerce_id' => Arr::get($payload, 'id'),
            'permalink' => Arr::get($payload, 'permalink'),
            'description' => Arr::get($payload, 'description'),
            'status' => Arr::get($payload, 'status'),
            'menu_order' => Arr::get($payload, 'menu_order', 0),
            'sku' => Arr::get($payload, 'sku'),
            'price' => $this->nullable(Arr::get($payload, 'price')),
            'regular_price' => $this->nullable(Arr::get($payload, 'regular_price')),
            'sale_price' => $this->nullable(Arr::get($payload, 'sale_price')),
            'date_on_sale_from' => $this->nullable(Arr::get($payload, 'date_on_sale_from')),
            'date_on_sale_from_gmt' => $this->nullable(Arr::get($payload, 'date_on_sale_from_gmt')),
            'date_on_sale_to' => $this->nullable(Arr::get($payload, 'date_on_sale_to')),
            'date_on_sale_to_gmt' => $this->nullable(Arr::get($payload, 'date_on_sale_to_gmt')),
            'on_sale' => Arr::get($payload, 'on_sale', false),
            'purchasable' => Arr::get($payload, 'purchasable', false),
            'virtual' => Arr::get($payload, 'virtual', false),
            'downloadable' => Arr::get($payload, 'downloadable', false),
            'download_limit' => Arr::get($payload, 'download_limit'),
            'download_expiry' => Arr::get($payload, 'download_expiry'),
            'tax_status' => Arr::get($payload, 'tax_status'),
            'tax_class' => Arr::get($payload, 'tax_class'),
            'manage_stock' => $manageStock,
            'stock_quantity' => $this->nullable(Arr::get($payload, 'stock_quantity')),
            'stock_status' => Arr::get($payload, 'stock_status'),
            'backorders' => Arr::get($payload, 'backorders'),
            'backordered' => Arr::get($payload, 'backordered', false),
            'visible' => Arr::get($payload, 'visible', true),
            'shipping_required' => Arr::get($payload, 'shipping_required', true),
            'shipping_taxable' => Arr::get($payload, 'shipping_taxable', true),
            'weight' => $this->nullable(Arr::get($payload, 'weight')),
            'dimensions' => Arr::get($payload, 'dimensions'),
            'shipping_class' => Arr::get($payload, 'shipping_class'),
            'shipping_class_id' => Arr::get($payload, 'shipping_class_id'),
            'price_html' => Arr::get($payload, 'price_html'),
            'image' => Arr::get($payload, 'image'),
            'links' => Arr::get($payload, '_links'),
            'meta_data_snapshot' => Arr::get($payload, 'meta_data', []),
            'date_created' => $this->nullable(Arr::get($payload, 'date_created')),
            'date_created_gmt' => $this->nullable(Arr::get($payload, 'date_created_gmt')),
            'date_modified' => $this->nullable(Arr::get($payload, 'date_modified')),
            'date_modified_gmt' => $this->nullable(Arr::get($payload, 'date_modified_gmt')),
        ];
    }

    protected function nullable(mixed $value): mixed
    {
        if ($value === '' || $value === []) {
            return null;
        }

        return $value;
    }
}
