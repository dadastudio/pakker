<?php

namespace App\Console\Commands;

use App\Services\WooCommerceCustomerSyncService;
use Illuminate\Console\Command;

class WooCommerceSyncCustomersCommand extends Command
{
    protected $signature = 'woocommerce:sync-customers
        {--page= : Page number to sync}
        {--per-page=50 : Number of customers per page}
        {--all : Sync all customer pages sequentially}';

    protected $description = 'Synchronize WooCommerce customers and metadata into the local database.';

    public function handle(WooCommerceCustomerSyncService $service): int
    {
        $perPage = max(1, (int) $this->option('per-page'));
        $all = (bool) $this->option('all');
        $pageOption = $this->option('page');

        if ($all) {
            $this->info('Syncing all WooCommerce customers...');
            $total = $service->syncAll($perPage, function (int $page, int $count, int $runningTotal): void {
                $this->line("Synced page {$page} ({$count} customers, {$runningTotal} total).");
            });
            $this->info("Finished syncing {$total} customers.");

            return self::SUCCESS;
        }

        $page = $pageOption !== null ? max(1, (int) $pageOption) : 1;
        $this->info("Syncing WooCommerce customers page {$page} (per page {$perPage})...");
        $count = $service->syncPage($page, $perPage);
        $this->info("Synced {$count} customers from page {$page}.");

        return self::SUCCESS;
    }
}
