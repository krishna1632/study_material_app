<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SyllabusController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\OtherController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ViewSuperAdminController;
use App\Http\Controllers\ViewStudentController;
use App\Http\Controllers\RoadmapsController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\StudyMaterialController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Superadmin Dashboard Route
    Route::get('/superadmin/dashboard', [SuperAdminController::class, 'index'])
        ->name('superadmin.dashboard');

    // Other Dashboard Route
    Route::get('/others/dashboard', [OtherController::class, 'index'])
        ->name('others.dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Permissions routes 
    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::get('/permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
    Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');
    Route::get('/permissions/{id}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
    Route::post('/permissions/{id}', [PermissionController::class, 'update'])->name('permissions.update');
    Route::delete('/permissions/{id}', [PermissionController::class, 'destroy'])->name('permissions.destroy');




    // Roles
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/{id}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::post('/roles/{id}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{id}', [RoleController::class, 'destroy'])->name('roles.destroy');





    // User
    Route::get('/users', [UserController::class, 'index'])->name('users.list');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::post('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');


    // Faculty Routes
    Route::get('/faculties', [FacultyController::class, 'index'])->name('faculties.index');
    Route::get('/faculties/{id}/edit', [FacultyController::class, 'edit'])->name('faculties.edit');
    Route::post('/faculties/{id}', [FacultyController::class, 'update'])->name('faculties.update');
    Route::delete('/faculties/{id}', [FacultyController::class, 'destroy'])->name('faculties.destroy');

    Route::get('/faculties/{id}', [FacultyController::class, 'show'])->name('faculties.show');




    // Admin Routes
    Route::get('/admins', [AdminController::class, 'index'])->name('admins.index');
    Route::get('/admins/{id}', [AdminController::class, 'show'])->name('admins.show');
    Route::get('/admins/{id}/edit', [AdminController::class, 'edit'])->name('admins.edit');
    Route::post('/admins/{id}', [AdminController::class, 'update'])->name('admins.update');
    Route::delete('/admins/{id}', [AdminController::class, 'destroy'])->name('admins.destroy');


    //Super Admin Routes
    Route::get('/supers', [ViewSuperAdminController::class, 'index'])->name('superadminView.index');
    Route::get('/supers/{id}/edit', [ViewSuperAdminController::class, 'edit'])->name('superadminView.edit');
    Route::get('/supers/{id}', [ViewSuperAdminController::class, 'show'])->name('superadminView.show');
    Route::post('/supers/{id}', [ViewSuperAdminController::class, 'update'])->name('superadminView.update');
    Route::delete('/supers/{id}', [ViewSuperAdminController::class, 'destroy'])->name('superadminView.destroy');

    // Students Routes
    Route::get('/students', [ViewStudentController::class, 'index'])->name('students.index');
    Route::get('/students/{id}/edit', [ViewStudentController::class, 'edit'])->name('students.edit');
    Route::get('/students/{id}', [ViewStudentController::class, 'show'])->name('students.show');
    Route::post('/students/{id}', [ViewStudentController::class, 'update'])->name('students.update');
    Route::delete('/students/{id}', [ViewStudentController::class, 'destroy'])->name('students.destroy');

    // Others
    Route::post('/others/{id}', [OtherController::class, 'update'])->name('others.update');



    // Roadmaps

    Route::get('/roadmaps', [RoadmapsController::class, 'index'])->name('roadmaps.index');
    Route::get('/roadmaps/create', [RoadmapsController::class, 'create'])->name('roadmaps.create');
    Route::post('/roadmaps', [RoadmapsController::class, 'store'])->name('roadmaps.store');

    Route::get('/roadmaps/{id}', [RoadmapsController::class, 'show'])->name('roadmaps.show');


    Route::get('/roadmaps/{id}/edit', [RoadmapsController::class, 'edit'])->name('roadmaps.edit');
    Route::post('/roadmaps/{id}', [RoadmapsController::class, 'update'])->name('roadmaps.update');
    Route::delete('/roadmaps/{id}', [RoadmapsController::class, 'destroy'])->name('roadmaps.destroy');

    // Syllabus

    Route::get('/syllabus', [SyllabusController::class, 'index'])->name('syllabus.index');
    Route::get('/syllabus/create', [SyllabusController::class, 'create'])->name('syllabus.create');
    Route::post('/syllabus', [SyllabusController::class, 'store'])->name('syllabus.store');
    Route::get('/syllabus/{id}', [SyllabusController::class, 'show'])->name('syllabus.show');
    Route::get('/syllabus/{id}/edit', [SyllabusController::class, 'edit'])->name('syllabus.edit');
    Route::post('/syllabus/{id}', [SyllabusController::class, 'update'])->name('syllabus.update');
    Route::delete('/syllabus/{id}', [SyllabusController::class, 'destroy'])->name('syllabus.destroy');

    Route::post('/filter-subjects', [SyllabusController::class, 'filterSubjects'])->name('filter.subjects');

    // Subjects
    Route::get('/subjects', [SubjectController::class, 'index'])->name('subjects.index');
    Route::get('/subjects/create', [SubjectController::class, 'create'])->name('subjects.create');
    Route::post('/subjects', [SubjectController::class, 'store'])->name('subjects.store');
    Route::get('/subjects/{id}/edit', [SubjectController::class, 'edit'])->name('subjects.edit');
    Route::post('/subjects/{id}', [SubjectController::class, 'update'])->name('subjects.update');
    Route::delete('/subjects/{id}', [SubjectController::class, 'destroy'])->name('subjects.destroy');

    // Study Materials
    Route::get('/study_materials', [StudyMaterialController::class, 'index'])->name('study_materials.index');
    Route::get('/study_materials/create', [StudyMaterialController::class, 'create'])->name('study_materials.create');
    Route::post('/study_materials', [StudyMaterialController::class, 'store'])->name('study_materials.store');
    Route::get('/study_materials/{id}/edit', [StudyMaterialController::class, 'edit'])->name('study_materials.edit');
    Route::post('/study_materials/{id}', [StudyMaterialController::class, 'update'])->name('study_materials.update');
    Route::delete('/study_materials/{id}', [StudyMaterialController::class, 'destroy'])->name('study_materials.destroy');
    Route::get('/study_materials/elective', [StudyMaterialController::class, 'elective'])->name('study_materials.elective');
    Route::get('/fetch-study-materials/{subjectId}', [StudyMaterialController::class, 'fetchStudyMaterials'])->name('study_materials.fetchStudyMaterials');



});

require __DIR__ . '/auth.php';