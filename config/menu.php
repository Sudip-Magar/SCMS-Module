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
            ['title' => 'Faculty', 'route' => 'academic-setup.academic-faculty', 'search' => ['Academic Faculty Setup', 'Academic Setup']],
            ['title' => 'Level', 'route' => 'academic-setup.academic-level', 'search' => ['Academic Level Setup', 'Academic Setup']],
            ['title' => 'Section', 'route' => 'academic-setup.academic-section', 'search' => ['Academic section Setup', 'Academic Setup']],
            ['title' => 'Subject', 'route' => 'academic-setup.academic-subject', 'search' => ['Academic subject Setup', 'Academic Setup']],
            ['title' => 'Room', 'route' => 'academic-setup.academic-room', 'search' => ['Academic room Setup', 'Academic Setup']],
            ['title' => 'Academic Schedule', 'route' => 'academic-setup.academic-schedule', 'search' => ['Academic schedule Setup', 'Academic Setup']],
            ['title' => 'Academic Structure', 'route' => 'academic-setup.academic-structure', 'search' => ['Academic Structure Setup', 'Academic Setup']],

        ],
    ],

    [
        'title' => 'Settings',
        'icon' => 'o-home',
        'route' => 'dashboard',
        'search' => ['Setting']
    ],

];
