<?php

use App\Http\Controllers\PagesController;
use App\Models\Attendance;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get( '/usajili', [PagesController::class, 'usajili'])->name('usajili');
Route::get('/successful', [PagesController::class, 'successful'])->name('successful');
Route::post('/usajili', [PagesController::class,'storeUsajili'])->name('store.usajili');


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    ])->group(function () {
        Route::get('/dashboard', [PagesController::class, 'dashboard'])->name('dashboard');
        Route::post('/{attendance}/update-usajili', [PagesController::class,'updateUsajili'])->name('update.usajili');
});
