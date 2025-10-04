<?php

namespace App\Console\Commands;

use App\Services\WooCommerceOrderSyncService;
use Illuminate\Console\Command;

class SyncOrders extends Command
{
	protected $signature = 'woocommerce:sync-orders
        {--page= : Page number to sync}
        {--per-page=30 : Number of orders per page}
        {--limit=30 : Maximum number of orders to sync (default: 10)}
        {--all : Sync all order pages sequentially}
        {--status=* : Limit to specific order statuses (repeatable option)}';

	protected $description = 'Synchronize WooCommerce orders and related resources into the local database.';

	public function handle(WooCommerceOrderSyncService $service): int
	{
		$perPage = max(1, (int) $this->option('per-page'));
		$limit = $this->option('limit') !== null ? max(1, (int) $this->option('limit')) : 10;
		$all = (bool) $this->option('all');
		$pageOption = $this->option('page');
		$query = $this->buildQuery();

		if ($all) {
			$this->info('Syncing all WooCommerce orders...');
			$total = $service->syncAll($perPage, function (int $page, int $count, int $runningTotal): void {
				$this->line("Synced page {$page} ({$count} orders, {$runningTotal} total).");
			}, $query);
			$this->info("Finished syncing {$total} orders.");

			return self::SUCCESS;
		}

		// Default behavior: truncate and sync limited orders
		$this->info("Truncating order tables and syncing last {$limit} orders...");
		$total = $service->syncLimited($limit, $perPage, $query);
		$this->info("Finished syncing {$total} orders.");

		return self::SUCCESS;
	}

	protected function buildQuery(): array
	{
		$statuses = array_filter((array) $this->option('status'));

		if ($statuses === []) {
			return [];
		}

		return [
			'status' => implode(',', $statuses),
		];
	}
}
