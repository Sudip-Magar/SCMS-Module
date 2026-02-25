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