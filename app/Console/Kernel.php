<?php

namespace App\Console;

use App\Console\Commands\WooCommerceFetchCommand;
use App\Console\Commands\WooCommerceSyncCustomersCommand;
use App\Console\Commands\WooCommerceSyncOrdersCommand;
use App\Console\Commands\WooCommerceSyncProductsCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array<int, class-string>
     */
    protected $commands = [
        WooCommerceFetchCommand::class,
        WooCommerceSyncCustomersCommand::class,
        WooCommerceSyncOrdersCommand::class,
        WooCommerceSyncProductsCommand::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        //
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
    }
}
