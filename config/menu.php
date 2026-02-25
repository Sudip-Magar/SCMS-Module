<?php
return [
    [
        'title' => 'Dashboard',
        'icon' => 'fa-solid fa-gauge',
        'route' => 'dashboard',
        'search' => ['Dashboard'],
    ],

    [
        'title' => 'Setup',
        'icon' => 'fa-solid fa-sliders',
        'children' => [
            ['title' => 'Users', 'route' => 'setup.user', 'search' => ['User Setup', 'Setup']],
            ['title' => 'Permission', 'route' => 'setup.permission', 'search' => ['Permission Setup', 'Setup']],
            ['title' => 'Role', 'route' => 'setup.role', 'search' => ['Role Setup', 'Setup']],
        ],
    ],

    [
        'title' => 'Academic Setup',
        'icon' => 'fa-solid fa-graduation-cap',
        'children' => [
            ['title' => 'Academic Year', 'route' => 'academic-setup.academic-year', 'search' => ['Academic year Setup', 'Academic Setup']],
            ['title' => 'Programs', 'route' => 'academic-setup.academic-program', 'search' => ['Academic Programs Setup', 'Academic Setup']],
            ['title' => 'Faculty', 'route' => 'academic-setup.academic-faculty', 'search' => ['Academic Programs Setup', 'Academic Setup']],
        ],
    ],

    [
        'title' => 'Settings',
        'icon' => 'o-home',
        'route' => 'dashboard',
        'search' => ['Setting']
    ],

];
