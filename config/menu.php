<?php
return [
    [
        'title' => 'Dashboard',
        'icon' => 'fa-solid fa-gauge',
        'route' => 'dashboard',
        'search' => ['Dashboard'],
        'ability' => 'dashboard-view-list',
    ],

    [
        'title' => 'Setup',
        'icon' => 'fa-solid fa-sliders',
        'children' => [
            ['title' => 'Users', 'route' => 'setup.user', 'ability' => 'setup-user-list', 'search' => ['User Setup', 'Setup']],
            ['title' => 'Permission', 'route' => 'setup.permission', 'ability' => 'setup-permission-list', 'search' => ['Permission Setup', 'Setup']],
            ['title' => 'Role', 'route' => 'setup.role', 'ability' => 'setup-role-list', 'search' => ['Role Setup', 'Setup']],
        ],
    ],

    [
        'title' => 'Academic Setup',
        'icon' => 'fa-solid fa-graduation-cap',
        'children' => [
            ['title' => 'Academic Year', 'route' => 'academic-module.academic-year', 'ability' => 'academic_setup-year-list', 'search' => ['Academic year Setup', 'Academic Setup']],
            ['title' => 'Programs', 'route' => 'academic-module.academic-program', 'ability' => 'academic_setup-program-list', 'search' => ['Academic Programs Setup', 'Academic Setup']],
            ['title' => 'Faculty', 'route' => 'academic-module.academic-faculty', 'ability' => 'academic_setup-faculty-list', 'search' => ['Academic Faculty Setup', 'Academic Setup']],
            ['title' => 'Level', 'route' => 'academic-module.academic-level', 'ability' => 'academic_setup-level-list', 'search' => ['Academic Level Setup', 'Academic Setup']],
            ['title' => 'Section', 'route' => 'academic-module.academic-section', 'ability' => 'academic_setup-section-list', 'search' => ['Academic section Setup', 'Academic Setup']],
            ['title' => 'Subject', 'route' => 'academic-module.academic-subject', 'ability' => 'academic_setup-subject-list', 'search' => ['Academic subject Setup', 'Academic Setup']],
            ['title' => 'Room', 'route' => 'academic-module.academic-room', 'ability' => 'academic_setup-room-list', 'search' => ['Academic room Setup', 'Academic Setup']],
            ['title' => 'Academic Structure', 'route' => 'academic-module.academic-structure', 'ability' => 'academic_setup-structure-list', 'search' => ['Academic Structure Setup', 'Academic Setup']],

        ],
    ],

    [
        'title' => 'Student Setup',
        'icon' => 'fa-solid fa-user-graduate',
        'children' => [
            ['title' => 'Student', 'route' => 'student-module.student-list', 'ability' => 'student_setup-student-list', 'search' => ['Student Setup', 'Student']],
            ['title' => 'Admission Numbering', 'route' => 'student-module.admission-numbering', 'ability' => 'student_setup-admission_numbering-list', 'search' => ['Student Setup', 'Student']],
        ],
    ],

    [
        'title' => 'Staff Setup',
        'icon' => 'fa-solid fa-user-gear',
        'children' => [
            ['title' => 'Department', 'route' => 'staff-module.department-setup', 'ability' => 'staff_setup-department-list', 'search' => ['Student Setup', 'Student']],
//            ['title' => 'Admission Numbering', 'route' => 'student-module.admission-numbering', 'ability' => 'student_setup-admission_numbering-list', 'search' => ['Student Setup', 'Student']],
        ],
    ],

    [
        'title' => 'Timetable Setup',
        'icon' => 'fas fa-calendar-days',
        'children' => [
            ['title' => 'Daily Schedule', 'route' => 'timetable-setup.daily-schedule', 'ability' => 'timetable_setup-daily_schedule-list', 'search' => ['Academic schedule Setup', 'Academic Setup']],
            ['title' => 'Timetable', 'route' => 'timetable-setup.timetable-setup', 'ability' => 'timetable_setup-timetable-list', 'search' => ['Academic Timetable Setup', 'Academic Setup']],

        ],
    ],

];
