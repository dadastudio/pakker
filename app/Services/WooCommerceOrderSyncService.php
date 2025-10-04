<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class WooCommerceOrderSyncService
{
	public function __construct(private readonly WooCommerceClient $client)
	{
	}

	public function syncAll(int $perPage = 50, ?callable $progressCallback = null, array $query = []): int
	{
		$this->truncateAllOrderTables();

		$page = 1;
		$total = 0;

		do {
			$synced = $this->syncPage($page, $perPage, $query);
			$total += $synced;

			if ($progressCallback !== null) {
				$progressCallback($page, $synced, $total);
			}

			$page++;
		} while ($synced === $perPage && $synced > 0);

		return $total;
	}

	/**
	 * Sync a limited number of orders (truncates first).
	 */
	public function syncLimited(int $limit = 10, int $perPage = 50, array $query = []): int
	{
		$this->truncateAllOrderTables();

		$orders = $this->client->listOrders(array_merge($query, [
			'page' => 1,
			'per_page' => $limit,
			'orderby' => 'date',
			'order' => 'desc',
			'product' => '124407 ',

		]));

		$synced = 0;
		foreach ($orders as $order) {
			try {
				$this->syncOrder($order);
				$synced++;
			} catch (Throwable $exception) {
				Log::error('Failed to sync WooCommerce order', [
					'order_id' => Arr::get($order, 'id'),
					'message' => $exception->getMessage(),
				]);
			}
		}

		return $synced;
	}

	/**
	 * Truncate all order-related tables and reset AUTO_INCREMENT.
	 */
	protected function truncateAllOrderTables(): void
	{
		$tables = [
			'order_meta',
			'order_refunds',
			'order_coupon_lines',
			'order_fee_lines',
			'order_shipping_lines',
			'order_tax_lines',
			'order_line_items',
			'orders',
		];

		try {
			DB::statement('SET FOREIGN_KEY_CHECKS=0');

			foreach ($tables as $table) {
				DB::table($table)->truncate();
				DB::statement("ALTER TABLE {$table} AUTO_INCREMENT = 1");
			}

			Log::info('All order tables truncated and AUTO_INCREMENT reset successfully');
		} finally {
			DB::statement('SET FOREIGN_KEY_CHECKS=1');
		}
	}

	public function syncPage(int $page = 1, int $perPage = 50, array $query = []): int
	{

		$orders = $this->client->listOrders(array_merge($query, [
			'page' => $page,
			'per_page' => $perPage,
			'orderby' => 'date',
			'order' => 'desc',
		]));

		foreach ($orders as $order) {
			try {
				$this->syncOrder($order);
			} catch (Throwable $exception) {
				Log::error('Failed to sync WooCommerce order', [
					'order_id' => Arr::get($order, 'id'),
					'message' => $exception->getMessage(),
				]);
			}
		}

		return count($orders);
	}

	protected function syncOrder(array $payload): Order
	{
		return DB::transaction(function () use ($payload) {
			$customerWooId = $this->nullableInt(Arr::get($payload, 'customer_id'));
			$customer = $customerWooId !== null
				? Customer::where('woocommerce_id', $customerWooId)->first()
				: null;

			$order = Order::updateOrCreate(
				['woocommerce_id' => Arr::get($payload, 'id')],
				$this->mapOrderAttributes($payload, $customer)
			);

			$this->syncLineItems($order, Arr::get($payload, 'line_items', []));
			$this->syncTaxLines($order, Arr::get($payload, 'tax_lines', []));
			$this->syncShippingLines($order, Arr::get($payload, 'shipping_lines', []));
			$this->syncFeeLines($order, Arr::get($payload, 'fee_lines', []));
			$this->syncCouponLines($order, Arr::get($payload, 'coupon_lines', []));
			$this->syncRefunds($order, Arr::get($payload, 'refunds', []));
			$this->syncMeta($order, Arr::get($payload, 'meta_data', []));

			return $order;
		});
	}

	protected function syncLineItems(Order $order, array $items): void
	{
		$order->lineItems()->delete();

		foreach ($items as $item) {
			// Get WooCommerce IDs
			$wooProductId = $this->nullableInt(Arr::get($item, 'product_id'));
			$wooVariationId = $this->nullableInt(Arr::get($item, 'variation_id'));

			// Lookup internal Product IDs
			$product = $wooProductId !== null
				? Product::where('woocommerce_id', $wooProductId)->first()
				: null;

			// Use parent product if this is a language variant
			if ($product && $product->parent_id) {
				$product = Product::find($product->parent_id);
			}

			$variation = $wooVariationId !== null
				? ProductVariation::where('woocommerce_id', $wooVariationId)->first()
				: null;

			$order->lineItems()->create([
				'woocommerce_id' => Arr::get($item, 'id'),
				'name' => Arr::get($item, 'name'),
				'product_id' => $product?->id,
				'woocommerce_product_id' => $wooProductId,
				'variation_id' => $variation?->id,
				'woocommerce_variation_id' => $wooVariationId,
				'quantity' => (int) Arr::get($item, 'quantity', 0),
				'tax_class' => Arr::get($item, 'tax_class'),
				'subtotal' => $this->nullableDecimal(Arr::get($item, 'subtotal')),
				'subtotal_tax' => $this->nullableDecimal(Arr::get($item, 'subtotal_tax')),
				'total' => $this->nullableDecimal(Arr::get($item, 'total')),
				'total_tax' => $this->nullableDecimal(Arr::get($item, 'total_tax')),
				'taxes' => Arr::get($item, 'taxes', []),
				'meta_data' => Arr::get($item, 'meta_data', []),
				'sku' => Arr::get($item, 'sku'),
				'price' => $this->nullableDecimal(Arr::get($item, 'price')),
				'parent_name' => Arr::get($item, 'parent_name'),
				'images' => Arr::get($item, 'images', []),
			]);
		}
	}

	protected function syncTaxLines(Order $order, array $lines): void
	{
		$order->taxLines()->delete();

		foreach ($lines as $line) {
			$order->taxLines()->create([
				'woocommerce_id' => Arr::get($line, 'id'),
				'rate_code' => Arr::get($line, 'rate_code'),
				'rate_id' => $this->nullableInt(Arr::get($line, 'rate_id')),
				'label' => Arr::get($line, 'label'),
				'compound' => (bool) Arr::get($line, 'compound', false),
				'tax_total' => $this->nullableDecimal(Arr::get($line, 'tax_total')),
				'shipping_tax_total' => $this->nullableDecimal(Arr::get($line, 'shipping_tax_total')),
				'meta_data' => Arr::get($line, 'meta_data', []),
			]);
		}
	}

	protected function syncShippingLines(Order $order, array $lines): void
	{
		$order->shippingLines()->delete();

		foreach ($lines as $line) {
			$order->shippingLines()->create([
				'woocommerce_id' => Arr::get($line, 'id'),
				'method_title' => Arr::get($line, 'method_title'),
				'method_id' => Arr::get($line, 'method_id'),
				'instance_id' => $this->nullableInt(Arr::get($line, 'instance_id')),
				'total' => $this->nullableDecimal(Arr::get($line, 'total')),
				'total_tax' => $this->nullableDecimal(Arr::get($line, 'total_tax')),
				'taxes' => Arr::get($line, 'taxes', []),
				'meta_data' => Arr::get($line, 'meta_data', []),
			]);
		}
	}

	protected function syncFeeLines(Order $order, array $lines): void
	{
		$order->feeLines()->delete();

		foreach ($lines as $line) {
			$order->feeLines()->create([
				'woocommerce_id' => Arr::get($line, 'id'),
				'name' => Arr::get($line, 'name'),
				'tax_class' => Arr::get($line, 'tax_class'),
				'tax_status' => Arr::get($line, 'tax_status'),
				'total' => $this->nullableDecimal(Arr::get($line, 'total')),
				'total_tax' => $this->nullableDecimal(Arr::get($line, 'total_tax')),
				'taxes' => Arr::get($line, 'taxes', []),
				'meta_data' => Arr::get($line, 'meta_data', []),
			]);
		}
	}

	protected function syncCouponLines(Order $order, array $lines): void
	{
		$order->couponLines()->delete();

		foreach ($lines as $line) {
			$order->couponLines()->create([
				'woocommerce_id' => Arr::get($line, 'id'),
				'code' => Arr::get($line, 'code'),
				'discount' => $this->nullableDecimal(Arr::get($line, 'discount')),
				'discount_tax' => $this->nullableDecimal(Arr::get($line, 'discount_tax')),
				'meta_data' => Arr::get($line, 'meta_data', []),
			]);
		}
	}

	protected function syncRefunds(Order $order, array $refunds): void
	{
		$order->refunds()->delete();

		foreach ($refunds as $refund) {
			$order->refunds()->create([
				'woocommerce_id' => Arr::get($refund, 'id'),
				'total' => $this->nullableDecimal(Arr::get($refund, 'total')),
				'reason' => Arr::get($refund, 'reason'),
				'refunded_by' => null,
				'meta_data' => Arr::get($refund, 'meta_data', []),
			]);
		}
	}

	protected function syncMeta(Order $order, array $meta): void
	{
		$order->metaData()->delete();

		foreach ($meta as $item) {
			$order->metaData()->create([
				'woocommerce_id' => Arr::get($item, 'id'),
				'meta_key' => Arr::get($item, 'key'),
				'meta_value' => Arr::get($item, 'value'),
				'display_key' => Arr::get($item, 'display_key'),
				'display_value' => Arr::get($item, 'display_value'),
			]);
		}
	}

	protected function mapOrderAttributes(array $payload, ?Customer $customer): array
	{
		return [
			'parent_id' => $this->nullableInt(Arr::get($payload, 'parent_id')),
			'number' => Arr::get($payload, 'number'),
			'order_key' => Arr::get($payload, 'order_key'),
			'created_via' => Arr::get($payload, 'created_via'),
			'version' => Arr::get($payload, 'version'),
			'status' => Arr::get($payload, 'status'),
			'currency' => Arr::get($payload, 'currency'),
			'date_created' => $this->nullable(Arr::get($payload, 'date_created')),
			'date_created_gmt' => $this->nullable(Arr::get($payload, 'date_created_gmt')),
			'date_modified' => $this->nullable(Arr::get($payload, 'date_modified')),
			'date_modified_gmt' => $this->nullable(Arr::get($payload, 'date_modified_gmt')),
			'discount_total' => $this->nullableDecimal(Arr::get($payload, 'discount_total')),
			'discount_tax' => $this->nullableDecimal(Arr::get($payload, 'discount_tax')),
			'shipping_total' => $this->nullableDecimal(Arr::get($payload, 'shipping_total')),
			'shipping_tax' => $this->nullableDecimal(Arr::get($payload, 'shipping_tax')),
			'cart_tax' => $this->nullableDecimal(Arr::get($payload, 'cart_tax')),
			'total' => $this->nullableDecimal(Arr::get($payload, 'total')),
			'total_tax' => $this->nullableDecimal(Arr::get($payload, 'total_tax')),
			'prices_include_tax' => (bool) Arr::get($payload, 'prices_include_tax', false),
			'customer_woocommerce_id' => $this->nullableInt(Arr::get($payload, 'customer_id')),
			'customer_id' => $customer?->id,
			'customer_ip_address' => Arr::get($payload, 'customer_ip_address'),
			'customer_user_agent' => Arr::get($payload, 'customer_user_agent'),
			'customer_note' => Arr::get($payload, 'customer_note'),
			'billing' => Arr::get($payload, 'billing', []),
			'shipping' => Arr::get($payload, 'shipping', []),
			'payment_method' => Arr::get($payload, 'payment_method'),
			'payment_method_title' => Arr::get($payload, 'payment_method_title'),
			'transaction_id' => Arr::get($payload, 'transaction_id'),
			'date_paid' => $this->nullable(Arr::get($payload, 'date_paid')),
			'date_paid_gmt' => $this->nullable(Arr::get($payload, 'date_paid_gmt')),
			'date_completed' => $this->nullable(Arr::get($payload, 'date_completed')),
			'date_completed_gmt' => $this->nullable(Arr::get($payload, 'date_completed_gmt')),
			'cart_hash' => Arr::get($payload, 'cart_hash'),
			'set_paid' => (bool) Arr::get($payload, 'set_paid', false),
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

	/**
	 * Returns the given value as an integer if it is not null, empty string, 0 or '0', otherwise returns null.
	 */
	protected function nullableInt(mixed $value): ?int
	{
		if ($value === null || $value === '' || $value === 0 || $value === '0') {
			return null;
		}

		return (int) $value;
	}

	protected function nullableDecimal(mixed $value): float
	{
		if ($value === null || $value === '') {
			return 0.0;
		}

		return (float) $value;
	}
}
