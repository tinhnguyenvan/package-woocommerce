<?php

namespace TinhPHP\Woocommerce\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminController;

class AdminWoocommerceController extends AdminController
{
    public $page_number;
    protected $data;
    protected $theme = 'default';

    public function __construct()
    {
        parent::__construct();
        $this->middleware(['auth', 'auth.console', 'locale', 'woo_setting']);
    }
}
