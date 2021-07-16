<?php
namespace TinhPHP\Woocommerce\Middleware;

use Closure;
use TinhPHP\Woocommerce\Models\Setting;

class SettingMiddleware
{
    public function handle($request, Closure $next)
    {
        // load config from database
        $keyConfig = [
            'currency',
        ];
        $loadConfig = Setting::query()
            ->whereIn('name', $keyConfig)
            ->get(['name', 'value'])
            ->toArray();

        $config = !empty($loadConfig) ? array_column($loadConfig, 'value', 'name') : [];

        // facebook
        config(['app.currency' => $config['currency'] ?? 'VND']);

        return $next($request);
    }
}
