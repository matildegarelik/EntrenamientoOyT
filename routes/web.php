<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth']], function() {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::group(['middleware' => ['role:admin']], function() {
        Route::get('/admin', [App\Http\Controllers\AdminController::class, 'index'])->name('admin');
    });
    Route::group(['middleware' => ['role:usuario']], function() {
        Route::get('/usuario', [App\Http\Controllers\UsuarioController::class, 'index'])->name('usuario');
    });
});

Route::resource('topics', App\Http\Controllers\TopicController::class);

Route::resource('tests', App\Http\Controllers\TestController::class);
Route::get('topics/create/{parent_id?}', [App\Http\Controllers\TopicController::class, 'create'])->name('topics.create_child');


// Ruta adicional para obtener temas hijos
Route::get('topics/{topic}/children', [App\Http\Controllers\TopicController::class, 'children'])->name('topics.children');
Route::get('topics/{topic}/test', [App\Http\Controllers\TestController::class, 'showUserTest'])->name('user.test.show');
Route::post('topics/{topic}/test', [App\Http\Controllers\TestController::class, 'submitUserTest'])->name('user.test.submit');
Route::get('user-tests/{userTest}/results', [App\Http\Controllers\TestController::class, 'showUserTestResults'])->name('user.test.results');

Route::get('/profile', [App\Http\Controllers\UsuarioController::class, 'edit'])->name('profile.edit');
Route::post('/profile', [App\Http\Controllers\UsuarioController::class, 'update'])->name('profile.update');
Route::get('/update-card-settings', [App\Http\Controllers\UsuarioController::class, 'edit_card_settings'])->name('profile.edit_card_settings');
Route::post('/update-card-settings', [App\Http\Controllers\UsuarioController::class, 'update_card_settings'])->name('profile.update_card_settings');


Route::post('tests/{test}/submit', [App\Http\Controllers\TestController::class, 'submitTest'])->name('tests.submit');
Route::get('tests_results', [App\Http\Controllers\TestController::class, 'results'])->name('tests.results');


// Tarjetas 
Route::middleware(['auth'])->group(function () {
    Route::resource('cards', App\Http\Controllers\CardController::class);
});
Route::post('cards/{card}/update-next-reminder', [App\Http\Controllers\CardController::class, 'updateNextReminder'])->name('cards.updateNextReminder');