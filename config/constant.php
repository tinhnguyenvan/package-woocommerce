<?php

return [
    'MENU_ADMIN' => [
        [
            'title' => 'nav.menu_left.products',
            'url' => '',
            'icon' => 'fa fa-cube',
            'child' => [
                [
                    'title' => 'nav.menu_left.product_categories',
                    'url' => 'woocommerce/product_categories',
                    'icon' => 'fa fa-sitemap',
                ],
                [
                    'title' => 'nav.menu_left.product_list',
                    'url' => 'woocommerce/products',
                    'icon' => 'fa fa-list',
                ],
                [
                    'title' => 'nav.menu_left.add',
                    'url' => 'woocommerce/products/create',
                    'icon' => 'icon-plus',
                ],
            ]
        ],
        [
            'title' => 'nav.menu_left.woocommerces',
            'url' => '',
            'icon' => 'fa fa-shopping-bag',
            'child' => [
                [
                    'title' => 'nav.menu_left.orders_list',
                    'url' => 'woocommerce/orders',
                    'icon' => 'fa fa-shopping-cart',
                ],
                [
                    'title' => 'nav.menu_left.orders_report',
                    'url' => 'woocommerce/orders/report',
                    'icon' => 'icon-chart',
                ],
            ]
        ],
    ]
];
