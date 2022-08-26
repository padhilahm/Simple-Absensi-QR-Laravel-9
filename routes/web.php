<?php

use App\Models\Student;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentClassController;
use App\Models\StudentClass;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index');
});

Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('loginProses');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// route group for authentication
Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('/student', StudentController::class);
    Route::get('/student-search', [StudentController::class, 'search'])->name('student.search');
    Route::delete('/student-m', [StudentController::class, 'destroyMulti'])->name('student.destroy-multi');
    Route::get(('/student-card/{studentClassId}'), [StudentController::class, 'card'])->name('student.card');

    Route::get('/class', [StudentClassController::class, 'index'])->name('class.index');
    Route::post('/class', [StudentClassController::class, 'store'])->name('class.store');
    Route::get('/class/{studentClass}', [StudentClassController::class, 'show'])->name('class.show');
    Route::put('/class/{studentClass}', [StudentClassController::class, 'update'])->name('class.update');
    Route::delete('/class/{studentClass}', [StudentClassController::class, 'destroy'])->name('class.destroy');
});
