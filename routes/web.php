<?php

use App\Livewire\Auth\Login;
use App\Livewire\Scms\AcademicSetup\AcademicFacultySetup;
use App\Livewire\Scms\AcademicSetup\AcademicProgramSetup;
use App\Livewire\Scms\AcademicSetup\AcademicYearSetup;
use App\Livewire\Scms\Dashboard;
use App\Livewire\Scms\Setup\PermissionSetup;
use App\Livewire\Scms\Setup\Role\CreateRole;
use App\Livewire\Scms\Setup\Role\RoleSetup;
use App\Livewire\Scms\Setup\User;
use Illuminate\Support\Facades\Route;
use Tabuna\Breadcrumbs\Trail;

Route::get('/login', Login::class)->name('login');
Route::get('/lang/{loacle}', function ($locale) {
    if (!in_array($locale, ['en', 'np'])) {
        abort(400);
    }

    session()->put('locale', $locale);
    return redirect()->back();
})->name('lang.switch');

Route::middleware(['auth'])->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');

    Route::prefix('setup')->group(function () {
        Route::get('/role-setup/add/{id?}', CreateRole::class)->name('setup.role.create');
        Route::get('/role-setup', RoleSetup::class)->name('setup.role');
        Route::get('/permission-setup', PermissionSetup::class)->name('setup.permission');
        Route::get('/user-setup', User::class)->name('setup.user');
    })->name('setup');

    Route::prefix('academic-setup')->group(function () {
        Route::get('/academic-year', AcademicYearSetup::class)->name('academic-setup.academic-year');
        Route::get('/academic-program', AcademicProgramSetup::class)->name('academic-setup.academic-program');
        Route::get('/academic-faculty', AcademicFacultySetup::class)->name('academic-setup.academic-faculty');
    });
});

require __DIR__ . '/breadcrumbs.php';