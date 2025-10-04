<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Throwable;

class ImportProductImagesCommand extends Command
{
    protected $signature = 'products:import-images
        {--product= : Import images for a specific product ID}
        {--limit= : Limit number of products to process}
        {--force : Re-import images even if they already exist}
        {--wp-path= : Path to WordPress uploads directory (e.g., /Users/user/Herd/pakker_wp/wp-content/uploads)}';

    protected $description = 'Import product images from ProductImage URLs into Spatie MediaLibrary';

    public function handle(): int
    {
        $this->info('Starting product images import...');

        $query = Product::query()->with('images');

        if ($productId = $this->option('product')) {
            $query->where('id', $productId);
        }

        if ($limit = $this->option('limit')) {
            $query->limit((int) $limit);
        }

        $products = $query->get();
        $totalProducts = $products->count();

        if ($totalProducts === 0) {
            $this->warn('No products found to process.');

            return self::SUCCESS;
        }

        $this->info("Found {$totalProducts} product(s) to process.");

        $progressBar = $this->output->createProgressBar($totalProducts);
        $progressBar->start();

        $stats = [
            'processed' => 0,
            'imported' => 0,
            'skipped' => 0,
            'failed' => 0,
        ];

        foreach ($products as $product) {
            $stats['processed']++;

            try {
                $result = $this->importProductImages($product);
                $stats['imported'] += $result['imported'];
                $stats['skipped'] += $result['skipped'];
                $stats['failed'] += $result['failed'];
            } catch (Throwable $exception) {
                $stats['failed']++;
                Log::error('Failed to import images for product', [
                    'product_id' => $product->id,
                    'message' => $exception->getMessage(),
                ]);
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        $this->info('Import completed!');
        $this->table(
            ['Metric', 'Count'],
            [
                ['Products processed', $stats['processed']],
                ['Images imported', $stats['imported']],
                ['Images skipped', $stats['skipped']],
                ['Images failed', $stats['failed']],
            ]
        );

        return self::SUCCESS;
    }

    protected function importProductImages(Product $product): array
    {
        $stats = [
            'imported' => 0,
            'skipped' => 0,
            'failed' => 0,
        ];

        $productImages = $product->images()->orderBy('position')->get();

        if ($productImages->isEmpty()) {
            return $stats;
        }

        $force = $this->option('force');

        // Skip if product already has media and not forcing
        if (! $force && $product->hasMedia('images')) {
            $stats['skipped'] += $productImages->count();

            return $stats;
        }

        // Clear existing media if forcing
        if ($force) {
            $product->clearMediaCollection('images');
        }

        foreach ($productImages as $productImage) {
            try {
                if (empty($productImage->src)) {
                    $stats['skipped']++;

                    continue;
                }

                // Try to convert URL to local file path if wp-path is provided
                $filePath = $this->resolveFilePath($productImage->src);

                // If file doesn't exist, try with .webp suffix
                if ($filePath && ! file_exists($filePath)) {
                    $webpPath = $filePath.'.webp';
                    if (file_exists($webpPath)) {
                        $filePath = $webpPath;
                    }
                }

                if ($filePath && file_exists($filePath)) {
                    // Use local file
                    $media = $product
                        ->addMedia($filePath)
                        ->withCustomProperties([
                            'woocommerce_id' => $productImage->woocommerce_id,
                            'alt' => $productImage->alt,
                            'position' => $productImage->position,
                        ])
                        ->usingName($productImage->name ?: 'Product Image')
                        ->usingFileName($this->sanitizeFileName($productImage->name, $productImage->src))
                        ->toMediaCollection('images');
                } else {
                    // Use URL - try .webp suffix first
                    $srcUrl = $productImage->src;
                    $webpUrl = $srcUrl.'.webp';
                    
                    try {
                        $media = $product
                            ->addMediaFromUrl($webpUrl)
                            ->withCustomProperties([
                                'woocommerce_id' => $productImage->woocommerce_id,
                                'alt' => $productImage->alt,
                                'position' => $productImage->position,
                            ])
                            ->usingName($productImage->name ?: 'Product Image')
                            ->usingFileName($this->sanitizeFileName($productImage->name, $productImage->src))
                            ->toMediaCollection('images');
                    } catch (Throwable $webpException) {
                        // If .webp fails, try original URL
                        $media = $product
                            ->addMediaFromUrl($srcUrl)
                            ->withCustomProperties([
                                'woocommerce_id' => $productImage->woocommerce_id,
                                'alt' => $productImage->alt,
                                'position' => $productImage->position,
                            ])
                            ->usingName($productImage->name ?: 'Product Image')
                            ->usingFileName($this->sanitizeFileName($productImage->name, $productImage->src))
                            ->toMediaCollection('images');
                    }
                }

                // Update order based on position
                if ($productImage->position !== null) {
                    $media->order_column = $productImage->position;
                    $media->save();
                }

                $stats['imported']++;
            } catch (Throwable $exception) {
                $stats['failed']++;
                Log::error('Failed to import image', [
                    'product_id' => $product->id,
                    'product_image_id' => $productImage->id,
                    'src' => $productImage->src,
                    'message' => $exception->getMessage(),
                ]);
            }
        }

        return $stats;
    }

    protected function sanitizeFileName(?string $name, string $url): string
    {
        if ($name) {
            $filename = preg_replace('/[^a-z0-9_\-\.]/i', '_', $name);

            return $filename.'_'.md5($url).'.'.pathinfo($url, PATHINFO_EXTENSION);
        }

        return basename(parse_url($url, PHP_URL_PATH));
    }

    protected function resolveFilePath(string $url): ?string
    {
        $wpPath = $this->option('wp-path');

        if (! $wpPath) {
            return null;
        }

        // Extract the path after wp-content/uploads/
        if (preg_match('#/wp-content/uploads/(.+)$#', $url, $matches)) {
            $relativePath = $matches[1];

            return rtrim($wpPath, '/').'/'.$relativePath;
        }

        return null;
    }
}
