<?php

namespace App\Console\Commands;

use App\Services\WooCommerceAttributeSyncService;
use Illuminate\Console\Command;

class WooCommerceSyncAttributesCommand extends Command
{
    protected $signature = 'woocommerce:sync-attributes
        {--page= : Page number to sync}
        {--per-page=50 : Number of attributes per page}
        {--all : Sync all attribute pages sequentially}';

    protected $description = 'Synchronize WooCommerce product attributes into the local database.';

    public function handle(WooCommerceAttributeSyncService $service): int
    {
        $perPage = max(1, (int) $this->option('per-page'));
        $all = (bool) $this->option('all');
        $pageOption = $this->option('page');

        if ($all) {
            $this->info('Syncing all WooCommerce attributes...');
            $total = $service->syncAll($perPage, function (int $page, int $count, int $runningTotal): void {
                $this->line("Synced page {$page} ({$count} attributes, {$runningTotal} total).");
            });
            $this->info("Finished syncing {$total} attributes.");

            return self::SUCCESS;
        }

        $page = $pageOption !== null ? max(1, (int) $pageOption) : 1;
        $this->info("Syncing WooCommerce attributes page {$page} (per page {$perPage})...");
        $count = $service->syncPage($page, $perPage);
        $this->info("Synced {$count} attributes from page {$page}.");

        return self::SUCCESS;
    }
}
