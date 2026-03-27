<?php

use App\Http\Controllers\searchSelect2Controller;
use App\Livewire\Auth\Login;
use App\Livewire\Scms\AcademicModule\AcademicDailyScheduleSetup;
use App\Livewire\Scms\AcademicModule\AcademicFacultySetup;
use App\Livewire\Scms\AcademicModule\AcademicLevelSetup;
use App\Livewire\Scms\AcademicModule\AcademicProgramSetup;
use App\Livewire\Scms\AcademicModule\AcademicRoomSetup;
use App\Livewire\Scms\AcademicModule\AcademicSectionSetup;
use App\Livewire\Scms\AcademicModule\AcademicStructureSetup;
use App\Livewire\Scms\AcademicModule\AcademicSubjectSetup;
use App\Livewire\Scms\AcademicModule\AcademicYearSetup;
use App\Livewire\Scms\Dashboard;
use App\Livewire\Scms\Setup\PermissionSetup;
use App\Livewire\Scms\Setup\Role\CreateRole;
use App\Livewire\Scms\Setup\Role\RoleSetup;
use App\Livewire\Scms\Setup\User;
use Illuminate\Support\Facades\Route;
use App\Livewire\Scms\AcademicModule\Timetable\AcademicTimetableSetup;
use App\Livewire\Scms\AcademicModule\Timetable\AcademicTimetableAdd;
use App\Livewire\Scms\Numbering\AcademicNumbering;
use  App\Livewire\Scms\StudentModule\Student\StudentList;
use  App\Livewire\Scms\StudentModule\Student\StudentAdd;
use App\Livewire\Scms\StaffModule\DepartmentSetup;
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
    Route::get('/searchSelect2', searchSelect2Controller::class)->name('searchSelect2');
    Route::get('/', Dashboard::class)->name('dashboard');

    Route::prefix('setup')->group(function () {
        Route::get('/role-setup/add/{id?}', CreateRole::class)->name('setup.role.create');
        Route::get('/role-setup', RoleSetup::class)->name('setup.role');
        Route::get('/permission-setup', PermissionSetup::class)->name('setup.permission');
        Route::get('/user-setup', User::class)->name('setup.user');
    })->name('setup');

    Route::prefix('academic-module')->group(function () {
        Route::get('/academic-year', AcademicYearSetup::class)->name('academic-module.academic-year');
        Route::get('/academic-program', AcademicProgramSetup::class)->name('academic-module.academic-program');
        Route::get('/academic-faculty', AcademicFacultySetup::class)->name('academic-module.academic-faculty');
        Route::get('/academic-level', AcademicLevelSetup::class)->name('academic-module.academic-level');
        Route::get('/academic-section', AcademicSectionSetup::class)->name('academic-module.academic-section');
        Route::get('/academic-subject', AcademicSubjectSetup::class)->name('academic-module.academic-subject');
        Route::get('/academic-room', AcademicRoomSetup::class)->name('academic-module.academic-room');
        Route::get('/academic-structure', AcademicStructureSetup::class)->name('academic-module.academic-structure');
    });


    Route::prefix('student-module')->group(function () {
        Route::get('/student-list', StudentList::class)->name('student-module.student-list');
        Route::get('/student-list/add/{id?}', StudentAdd::class)->name('student-module.student-add');
        Route::get('/admission-numbering', AcademicNumbering::class)->name('student-module.admission-numbering');
    });

    Route::prefix('staff-module')->group(function () {
       Route::get('/department-setup', DepartmentSetup::class)->name('staff-module.department-setup');
    });

    Route::prefix('timetable-setup')->group(function () {
        Route::get('/daily-schedule', AcademicDailyScheduleSetup::class)->name('timetable-setup.daily-schedule');
        Route::get('/timetable-setup', AcademicTimetableSetup::class)->name('timetable-setup.timetable-setup');
        Route::get('/timetable-setup/add/{id?}', AcademicTimetableAdd::class)->name('timetable-setup.timetable-setup.add');
    });
});

require __DIR__ . '/breadcrumbs.php';
