<?php

use App\Livewire\Auth\Login;
use App\Livewire\Dashboard;
use App\Livewire\Setup\PermissionSetup;
use App\Livewire\Setup\Role\CreateRole;
use App\Livewire\Setup\Role\RoleSetup;
use App\Livewire\Setup\User;
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
});