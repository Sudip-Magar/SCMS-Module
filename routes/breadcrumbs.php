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

Breadcrumbs::for('setup.permission', function (Trail $trail) {
    $trail->parent('setup');
    $trail->push(__('Permisison Setup'), route('setup.permission'));
});

// Academic Setup
Breadcrumbs::for('Academic-setup', function (Trail $trail) {
    $trail->push(__('Academic Setup'));
});

Breadcrumbs::for('academic-setup.academic-year', function (Trail $trail) {
    $trail->parent('Academic-setup');
    $trail->push(__('Academic Year Setup'));
});

Breadcrumbs::for('academic-setup.academic-program', function (Trail $trail) {
    $trail->parent('Academic-setup');
    $trail->push(__('Academic Program Setup'));
});

Breadcrumbs::for('academic-setup.academic-faculty', function (Trail $trail) {
    $trail->parent('Academic-setup');
    $trail->push(__('Academic Faculty Setup'));
});

Breadcrumbs::for('academic-setup.academic-level', function (Trail $trail) {
    $trail->parent('Academic-setup');
    $trail->push(__('Academic Level Setup'));
});

Breadcrumbs::for('academic-setup.academic-section', function (Trail $trail) {
    $trail->parent('Academic-setup');
    $trail->push(__('Academic Section Setup'));
});

Breadcrumbs::for('academic-setup.academic-subject', function (Trail $trail) {
    $trail->parent('Academic-setup');
    $trail->push(__('Academic Subject Setup'));
});

Breadcrumbs::for('academic-setup.academic-room', function (Trail $trail) {
    $trail->parent('Academic-setup');
    $trail->push(__('Room Setup'));
});

Breadcrumbs::for('academic-setup.academic-structure', function (Trail $trail) {
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

Breadcrumbs::for('student-setup', function (Trail $trail) {
    $trail->push(__('Student Setup'));
});

Breadcrumbs::for('student-setup.student-list', function (Trail $trail) {
    $trail->parent('student-setup');
    $trail->push(__('Student List') ,route('student-setup.student-list'));
});

Breadcrumbs::for('student-setup.student-add', function (Trail $trail) {
    $trail->parent('student-setup.student-list');
    $trail->push(__('Student Add'), route('student-setup.student-add'));
});


