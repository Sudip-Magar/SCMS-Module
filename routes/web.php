<?php

use App\Livewire\Auth\Login;
use App\Livewire\Scms\AcademicSetup\AcademicYearSetup;
use App\Livewire\Scms\Dashboard;
use App\Livewire\Scms\Setup\PermissionSetup;
use App\Livewire\Scms\Setup\Role\CreateRole;
use App\Livewire\Scms\Setup\Role\RoleSetup;
use App\Livewire\Scms\Setup\User;
use Illuminate\Support\Facades\Route;

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
    });

    Route::prefix('academic-setup')->group(function () {
        Route::get('/academic-year', AcademicYearSetup::class)->name('academic-setup.academi-year');
    });
});