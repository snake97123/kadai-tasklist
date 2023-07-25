<?php

use App\Http\Controllers\TasksController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('dashboard');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::get('/', [TasksController::class, 'dashboard']);

Route::get('/dashboard', [TasksController::class, 'index'])->middleware(['auth'])->name('dashboard');



Route::group(['middleware' => ['auth']], function () {
    Route::resource('tasks', TasksController::class, ['only' => ['index', 'store', 'destroy', 'show', 'create', 'edit', 'update']]);
});

require __DIR__.'/auth.php';
