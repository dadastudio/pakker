<?php

namespace App\Console\Commands;

use App\Services\WooCommerceClient;
use Illuminate\Console\Command;
use Throwable;

class WooCommerceFetchCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'woocommerce:fetch
        {resource=products : WooCommerce REST resource to query (e.g. products, orders)}
        {--page=1 : Page number to request}
        {--per-page=10 : Number of records to fetch per page}
        {--raw : Output the raw JSON response}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch data from the configured WooCommerce store.';

    /**
     * Execute the console command.
     */
    public function handle(WooCommerceClient $client): int
    {
        $resource = (string) $this->argument('resource');

        $query = array_filter([
            'page' => max(1, (int) $this->option('page')),
            'per_page' => max(1, (int) $this->option('per-page')),
        ]);

        try {
            $payload = $client->get($resource, $query);
        } catch (Throwable $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }

        if ($this->option('raw')) {
            $this->line(json_encode($payload, JSON_PRETTY_PRINT));

            return self::SUCCESS;
        }

        if (array_is_list($payload)) {
            $count = count($payload);
            $this->info("Fetched {$count} records from {$resource}.");

            $preview = array_slice($payload, 0, min(5, $count));

            if ($preview === []) {
                return self::SUCCESS;
            }

            $this->line(json_encode($preview, JSON_PRETTY_PRINT));

            if ($count > count($preview)) {
                $this->comment('Use the --raw option to see the full response.');
            }

            return self::SUCCESS;
        }

        if ($payload === null) {
            $this->warn('The API returned an empty response.');

            return self::SUCCESS;
        }

        $this->line(json_encode($payload, JSON_PRETTY_PRINT));

        return self::SUCCESS;
    }
}
