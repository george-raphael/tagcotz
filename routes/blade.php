<?php

use App\Http\Controllers\PagesController;
use Illuminate\Support\Facades\Route;


Route::get('/{event}/print-all-ids',[PagesController::class, 'printIds'])->name('dashboard.print.ids');
Route::get('/export', [PagesController::class, 'export'])->name('dashboard.export');
