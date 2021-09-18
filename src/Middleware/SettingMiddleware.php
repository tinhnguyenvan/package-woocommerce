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
            'seo_title',
        ];
        $loadConfig = Setting::query()
            ->whereIn('name', $keyConfig)
            ->get(['name', 'value'])
            ->toArray();

        $config = !empty($loadConfig) ? array_column($loadConfig, 'value', 'name') : [];

        // facebook
        config(['app.currency' => $config['currency'] ?? 'VND']);
        config(['app.woocommerce.seo_title' => $config['seo_title'] ?? '']);

        return $next($request);
    }
}
