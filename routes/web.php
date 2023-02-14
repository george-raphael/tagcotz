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
Route::post('/usajili', [PagesController::class,'storeUsajili'])->name('store.usajili');
Route::get('/successful', [PagesController::class, 'successful'])->name('successful');


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        $data['attendees'] = Attendance::with(['region','district'])->latest()
        ->paginate(20);
        return Inertia::render('Dashboard',$data);
    })->name('dashboard');
});
