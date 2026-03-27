<?php

use Tabuna\Breadcrumbs\Breadcrumbs;
use Tabuna\Breadcrumbs\Trail;

// Setup Breadcrumbs
Breadcrumbs::for('setup', function (Trail $trail) {
    $trail->push(__('Setup'));
});

Breadcrumbs::for('setup.role', function (Trail $trail) {
    $trail->parent('setup');
    $trail->push(__('Role Setup'), route('setup.role'));
});

Breadcrumbs::for('setup.role.create', function (Trail $trail) {
    $trail->parent('setup.role');
    $trail->push(__('Add Role'));
});

Breadcrumbs::for('setup.user', function (Trail $trail) {
    $trail->parent('setup');
    $trail->push(__('User Setup'));
});

Breadcrumbs::for('setup.permission', function (Trail $trail) {
    $trail->parent('setup');
    $trail->push(__('Permission Setup'), route('setup.permission'));
});

// Academic Setup
Breadcrumbs::for('Academic-setup', function (Trail $trail) {
    $trail->push(__('Academic Setup'));
});

Breadcrumbs::for('academic-module.academic-year', function (Trail $trail) {
    $trail->parent('Academic-setup');
    $trail->push(__('Academic Year Setup'));
});

Breadcrumbs::for('academic-module.academic-program', function (Trail $trail) {
    $trail->parent('Academic-setup');
    $trail->push(__('Academic Program Setup'));
});

Breadcrumbs::for('academic-module.academic-faculty', function (Trail $trail) {
    $trail->parent('Academic-setup');
    $trail->push(__('Academic Faculty Setup'));
});

Breadcrumbs::for('academic-module.academic-level', function (Trail $trail) {
    $trail->parent('Academic-setup');
    $trail->push(__('Academic Level Setup'));
});

Breadcrumbs::for('academic-module.academic-section', function (Trail $trail) {
    $trail->parent('Academic-setup');
    $trail->push(__('Academic Section Setup'));
});

Breadcrumbs::for('academic-module.academic-subject', function (Trail $trail) {
    $trail->parent('Academic-setup');
    $trail->push(__('Academic Subject Setup'));
});

Breadcrumbs::for('academic-module.academic-room', function (Trail $trail) {
    $trail->parent('Academic-setup');
    $trail->push(__('Room Setup'));
});

Breadcrumbs::for('academic-module.academic-structure', function (Trail $trail) {
    $trail->parent('Academic-setup');
    $trail->push(__('Structure Setup'));
});

//timetable setup breadcrumbs
Breadcrumbs::for('timetable-setup', function (Trail $trail) {
    $trail->push(__('Timetable Setup'));
});

Breadcrumbs::for('timetable-setup.daily-schedule', function (Trail $trail) {
    $trail->parent('timetable-setup');
    $trail->push(__('Daily Schedule'));
});


Breadcrumbs::for('timetable-setup.timetable-setup', function (Trail $trail) {
    $trail->parent('timetable-setup');
    $trail->push(__('Timetable'));
});

Breadcrumbs::for('timetable-setup.timetable-setup.add', function (Trail $trail) {
    $trail->parent('timetable-setup.timetable-setup');
    $trail->push(__('Timetable Add'));
});

Breadcrumbs::for('student-module', function (Trail $trail) {
    $trail->push(__('Student Setup'));
});

Breadcrumbs::for('student-module.student-list', function (Trail $trail) {
    $trail->parent('student-module');
    $trail->push(__('Student List') ,route('student-module.student-list'));
});

Breadcrumbs::for('student-module.student-add', function (Trail $trail) {
    $trail->parent('student-module.student-list');
    $trail->push(__('Student Add'), route('student-module.student-add'));
});


