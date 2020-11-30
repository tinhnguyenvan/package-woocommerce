<?php

namespace TinhPHP\Woocommerce\Facades;

use Illuminate\Support\Facades\Facade;

class WoocommerceFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'woocommerce';
    }
}
