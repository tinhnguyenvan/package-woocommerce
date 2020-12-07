<?php

namespace TinhPHP\Woocommerce\Console;

use App\Models\Plugin;
use Carbon\Carbon;
use Illuminate\Console\Command;

class InstallWoocommercePackage extends Command
{
    protected $signature = 'package_woocommerce:install';

    protected $description = 'Install the WoocommercePackage';

    public function handle()
    {
        $this->info('Installing WoocommercePackage...');

        Plugin::query()->updateOrInsert(
            [
                'code' => 'woocommerce',
            ],
            [
                'version' => '1.0.1',
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),

            ]
        );

        $this->info('Publishing configuration...');

        $this->call(
            'vendor:publish',
            [
                '--provider' => "TinhPHP\Woocommerce\WoocommerceServiceProvider",
                '--tag' => 'config'
            ]
        );

        $this->info('Installed WoocommercePackage');
    }
}
