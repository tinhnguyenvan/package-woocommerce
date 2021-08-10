<?php

use App\Models\Role;

return [
    'MENU_APP' => [
        'nav.menu_left.products' => [
            'title' => 'nav.menu_left.products',
            'plugin' => 'woocommerce',
            'url' => '',
            'icon' => 'fa fa-cube',
            'role' => [Role::ROLE_ADMIN, Role::ROLE_MANAGER_MANAGER],
            'child' => [
                'nav.menu_left.product_categories' => [
                    'title' => 'nav.menu_left.product_categories',
                    'plugin' => 'woocommerce',
                    'url' => 'woocommerce/product_categories',
                    'icon' => 'fa fa-sitemap',
                    'role' => [Role::ROLE_ADMIN, Role::ROLE_MANAGER_MANAGER],
                ],
                'nav.menu_left.product_list' => [
                    'title' => 'nav.menu_left.product_list',
                    'plugin' => 'woocommerce',
                    'url' => 'woocommerce/products',
                    'icon' => 'fa fa-list',
                    'role' => [Role::ROLE_ADMIN, Role::ROLE_MANAGER_MANAGER],
                ],
                'nav.menu_left.add' => [
                    'title' => 'nav.menu_left.add',
                    'plugin' => 'woocommerce',
                    'url' => 'woocommerce/products/create',
                    'icon' => 'icon-plus',
                    'role' => [Role::ROLE_ADMIN, Role::ROLE_MANAGER_MANAGER],
                ],
                'nav.menu_left.settings' => [
                    'title' => 'Setting',
                    'plugin' => 'woocommerce',
                    'url' => 'woocommerce/settings',
                    'icon' => 'fa fa-cogs',
                    'role' => [Role::ROLE_ADMIN, Role::ROLE_MANAGER_MANAGER],
                ],
            ]
        ],
        'nav.menu_left.woocommerces' => [
            'title' => 'lang_woocommerce::message.menu_left.woocommerces',
            'plugin' => 'woocommerce',
            'url' => '',
            'icon' => 'fa fa-shopping-bag',
            'role' => [Role::ROLE_ADMIN, Role::ROLE_MANAGER_MANAGER],
            'child' => [
                'nav.menu_left.orders_list' => [
                    'title' => 'lang_woocommerce::message.menu_left.woocommerces.order',
                    'plugin' => 'woocommerce',
                    'url' => 'woocommerce/orders',
                    'icon' => 'fa fa-shopping-cart',
                    'role' => [Role::ROLE_ADMIN, Role::ROLE_MANAGER_MANAGER],
                ],
                'nav.menu_left.orders_report' => [
                    'title' => 'nav.menu_left.orders_report',
                    'plugin' => 'woocommerce',
                    'url' => 'woocommerce/orders/report',
                    'icon' => 'icon-chart',
                    'role' => [Role::ROLE_ADMIN, Role::ROLE_MANAGER_MANAGER],
                ]
            ]
        ],
    ]
];
