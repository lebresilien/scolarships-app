<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\ViewTransaction;
use App\Livewire\ViewNote;
use App\Http\Controllers\PdfController;
use Illuminate\Support\Facades\Artisan;

Route::get('routes', function () {
    /* $routeCollection = Illuminate\Support\Facades\Route::getRoutes();

    foreach ($routeCollection as $value) {
        echo $value->getName() .'<br>';
    } */
    return \App\Models\School::all()->first()->is_primary_school;
})->name('trx');

Route::get('dashboard/notes/{classroom_id}', ViewNote::class)->name('classrooms.notes');
Route::get('report/{student}/{seq}', [PdfController::class, 'sequence'])->name('report');
Route::get('trimester-report/{student}/{trimester}', [PdfController::class, 'trimester'])->name('trimester-report');