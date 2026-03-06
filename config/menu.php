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
            ['title' => 'Academic Structure', 'route' => 'academic-setup.academic-structure', 'search' => ['Academic Structure Setup', 'Academic Setup']],

        ],
    ],

    [
        'title' => 'Timetable Setup',
        'icon' => 'fas fa-calendar-days',
        'children' => [
            ['title' => 'Daily Schedule', 'route' => 'timetable-setup.daily-schedule', 'search' => ['Academic schedule Setup', 'Academic Setup']],
            ['title' => 'Timetable', 'route' => 'timetable-setup.timetable-setup', 'search' => ['Academic Timetable Setup', 'Academic Setup']],

        ],
    ],

    [
        'title' => 'Student Setup',
        'icon' => 'fa-solid fa-user-graduate',
        'children' => [
            ['title' => 'Student', 'route' => 'student-setup.student-list', 'search' => ['Student Setup', 'Student']],
        ],
    ],

];
