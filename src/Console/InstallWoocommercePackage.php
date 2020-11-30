<?php

namespace TinhPHP\Woocommerce\Console;

use Illuminate\Console\Command;

class InstallWoocommercePackage extends Command
{
    protected $signature = 'package_woocommerce:install';

    protected $description = 'Install the WoocommercePackage';

    public function handle()
    {
        $this->info('Installing WoocommercePackage...');

        $this->info('Publishing configuration...');

        $this->call('vendor:publish', [
            '--provider' => "TinhPHP\Woocommerce\WoocommerceServiceProvider",
            '--tag' => 'config'
        ]);

        $this->call('vendor:publish', [
            '--provider' => "TinhPHP\Woocommerce\WoocommerceServiceProvider",
            '--tag' => 'migrations',
            '--force' => true
        ]);

        $this->info('Installed WoocommercePackage');
    }
}
