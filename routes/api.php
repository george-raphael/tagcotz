<?php

use App\Http\Controllers\PagesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/region/{region}/districts', [PagesController::class, 'getDistrictsByRegionId'])->name('region.districts');
Route::get('/attendees/search',[PagesController::class,'searchAttendees'])->name('search.attendees');
Route::get('/get-verified-ID',[PagesController::class, 'getVerifiedIdAPI']);
