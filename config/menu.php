<?php
return [
    [
        'title' => 'Dashboard',
        'icon' => 'fa-solid fa-gauge',
        'route' => 'dashboard'
    ],

    [
        'title' => 'Setup',
        'icon' => 'fa-solid fa-sliders',
        'children' => [
            ['title' => 'Users', 'route' => 'dashboard'],
            ['title' => 'Permission', 'route' => 'setup.permission'],
            ['title' => 'Role', 'route' => 'setup.role'],
        ],
    ],

    [
        'title' => 'Academic Setup',
        'icon' => 'fa-solid fa-graduation-cap',
        'children' => [
            ['title' => 'Academic Year', 'route' => 'dashboard'],
            ['title' => 'Subject', 'route' => 'dashboard'],
        ],
    ],

    [
        'title' => 'Products',
        'icon' => 'o-home',
        'children' => [
            ['title' => 'All Products', 'route' => 'dashboard'],
            ['title' => 'Add Product', 'route' => 'dashboard'],
        ],
    ],

    [
        'title' => 'Settings',
        'icon' => 'o-home',
        'route' => 'dashboard',
    ],

];
