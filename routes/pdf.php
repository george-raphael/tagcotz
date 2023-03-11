<?php

use App\Http\Controllers\PagesController;
use Illuminate\Support\Facades\Route;


Route::get('/print-all-ids',[PagesController::class, 'printIds'])->name('dashboard.print.ids');
