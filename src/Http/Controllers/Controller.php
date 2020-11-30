<?php

namespace TinhPHP\Woocommerce\Http\Controllers;

use App\Http\Controllers\Site\SiteController;

class Controller extends SiteController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware(['auth']);
    }
}
