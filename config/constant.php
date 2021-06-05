<?php

return [
    'MENU_APP' => [
        'nav.menu_left.products' => [
            'title' => 'nav.menu_left.products',
            'plugin' => 'woocommerce',
            'url' => '',
            'icon' => 'fa fa-cube',
            'child' => [
                'nav.menu_left.product_categories' => [
                    'title' => 'nav.menu_left.product_categories',
                    'plugin' => 'woocommerce',
                    'url' => 'woocommerce/product_categories',
                    'icon' => 'fa fa-sitemap',
                ],
                'nav.menu_left.product_list' => [
                    'title' => 'nav.menu_left.product_list',
                    'plugin' => 'woocommerce',
                    'url' => 'woocommerce/products',
                    'icon' => 'fa fa-list',
                ],
                'nav.menu_left.add' => [
                    'title' => 'nav.menu_left.add',
                    'plugin' => 'woocommerce',
                    'url' => 'woocommerce/products/create',
                    'icon' => 'icon-plus',
                ],
            ]
        ],
        'nav.menu_left.woocommerces' => [
            'title' => 'nav.menu_left.woocommerces',
            'plugin' => 'woocommerce',
            'url' => '',
            'icon' => 'fa fa-shopping-bag',
            'child' => [
                'nav.menu_left.orders_list' => [
                    'title' => 'nav.menu_left.orders_list',
                    'plugin' => 'woocommerce',
                    'url' => 'woocommerce/orders',
                    'icon' => 'fa fa-shopping-cart',
                ],
                'nav.menu_left.orders_report' => [
                    'title' => 'nav.menu_left.orders_report',
                    'plugin' => 'woocommerce',
                    'url' => 'woocommerce/orders/report',
                    'icon' => 'icon-chart',
                ],
            ]
        ],
    ]
];
