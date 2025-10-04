<?php

namespace App\Console\Commands;

use App\Services\WooCommerceProductSyncService;
use Illuminate\Console\Command;

class SyncProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'woocommerce:sync-products
        {--page= : Page number to sync}
        {--per-page=50 : Number of products per page}
        {--all : Sync all product pages sequentially}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize WooCommerce products, variations, and related data into the local database.';

    public function handle(WooCommerceProductSyncService $service): int
    {
        $perPage = max(1, (int) $this->option('per-page'));
        $all = (bool) $this->option('all');
        $pageOption = $this->option('page');

        if ($all) {
            $this->info('Syncing all WooCommerce products...');
            $total = $service->syncAll($perPage, function (int $page, int $count, int $runningTotal): void {
                $this->line("Synced page {$page} ({$count} products, {$runningTotal} total).");
            });
            $this->info("Finished syncing {$total} products.");

            return self::SUCCESS;
        }

        $page = $pageOption !== null ? max(1, (int) $pageOption) : 1;
        $this->info("Syncing WooCommerce products page {$page} (per page {$perPage})...");
        $count = $service->syncPage($page, $perPage);
        $this->info("Synced {$count} products from page {$page}.");

        return self::SUCCESS;
    }
}
