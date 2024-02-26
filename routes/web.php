<?php

use App\Http\Controllers\EventController;
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

// Route::get('/test', function(){
//     echo phpinfo();
// });

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->middleware(['guest']);

Route::get('/usajili', [PagesController::class, 'usajili'])->name('usajili');
Route::get('/successful', [PagesController::class, 'successful'])->name('successful');
Route::post('/usajili', [PagesController::class, 'storeUsajili'])->name('store.usajili');


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [PagesController::class, 'dashboard'])->name('dashboard');
    Route::get('/attendances/{event}', [PagesController::class, 'attendances'])->name('attendances');

    Route::post('/store-event',[EventController::class, 'store'])->name('event.store');
    Route::post('/update-event/{event}',[EventController::class, 'update'])->name('event.update');
    Route::post('/jisajili-event/{event}',[EventController::class, 'jisajili'])->name('event.jisajili');
    Route::delete('/destroy-event/{event}',[EventController::class, 'destroy'])->name('event.destroy');
    Route::get('/view-event/{event}',[EventController::class, 'viewEvent'])->name('event.view');

    Route::post('/lipia/{attendance}',[EventController::class, 'lipia'])->name('attendance.lipia');
    
    Route::post('/{attendance}/update-usajili',[PagesController::class, 'updateUsajili'])->name('update.usajili');
    Route::post('/{attendance}/delete-usajili', [PagesController::class, 'deleteUsajili'])->name('delete.usajili');
});
