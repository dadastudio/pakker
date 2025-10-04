<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class WooCommerceTranslationGrouper
{
    /**
     * Group WooCommerce products by their base product (translations).
     * Products with identical names are considered translations of the same product.
     */
    public function groupProducts(array $products): Collection
    {
        $groups = collect();

        foreach ($products as $product) {
            $name = Arr::get($product, 'name');
            $sku = Arr::get($product, 'sku');

            // Create a unique key for grouping
            // Products with same name and SKU are considered translations
            $groupKey = $this->createGroupKey($name, $sku);

            if (! $groups->has($groupKey)) {
                $groups->put($groupKey, [
                    'products' => [],
                    'primary_sku' => $sku,
                    'name' => $name,
                ]);
            }

            // Get the group, add product, and update
            $group = $groups->get($groupKey);
            $group['products'][] = $product;
            $groups->put($groupKey, $group);
        }

        return $groups;
    }

    /**
     * Detect the language of a product.
     * This can be enhanced based on your WooCommerce setup.
     */
    public function detectLanguage(array $product): string
    {
        // First, try to detect from permalink URL
        // Match patterns like /en/product/, /pl/produkt/, etc.
        $permalink = Arr::get($product, 'permalink', '');
        if (preg_match('#/([a-z]{2})/(product|produkt)/#i', $permalink, $matches)) {
            return strtolower($matches[1]); // e.g., "pl", "en"
        }

        // Check meta data for language indicators
        $metaData = Arr::get($product, 'meta_data', []);

        foreach ($metaData as $meta) {
            $key = Arr::get($meta, 'key');
            $value = Arr::get($meta, 'value');

            // WPML language meta
            if ($key === 'wpml_language') {
                return $value;
            }

            // Polylang language meta
            if ($key === '_polylang_language') {
                return $value;
            }
        }

        // Fallback: detect by WooCommerce ID pattern
        // Lower IDs are often the original language
        // You can customize this logic based on your setup
        $wooId = Arr::get($product, 'id');

        return $this->detectLanguageByPattern($product, $wooId);
    }

    /**
     * Detect language based on patterns or ID ordering.
     * Override this method to customize for your specific setup.
     */
    protected function detectLanguageByPattern(array $product, int $wooId): string
    {
        // Default strategy: assume first created (lower ID) is English
        // You can enhance this with more sophisticated detection
        return 'en'; // Default to English, will be overridden by primary product
    }

    /**
     * Select the primary product from a group of translations.
     * The primary product will be used for non-translatable fields.
     */
    public function selectPrimaryProduct(array $products): array
    {
        // Strategy: Use the product with the lowest WooCommerce ID
        // This is typically the original language version
        return collect($products)->sortBy('id')->first();
    }

    /**
     * Create a unique key for grouping products.
     */
    protected function createGroupKey(string $name, ?string $sku): string
    {
        // Remove common language suffixes from names
        $baseName = preg_replace('/\s*\((EN|PL|DE|FR|ES|IT|Copy)\)\s*$/i', '', $name);

        // Use SKU if available, otherwise just the normalized name
        return $sku ? "{$sku}_{$baseName}" : $baseName;
    }
}
