<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\ViewTransaction;
use App\Livewire\ViewNote;
use App\Http\Controllers\PdfController;
use Illuminate\Support\Facades\Artisan;

Route::get('routes', function () {
    $routeCollection = Illuminate\Support\Facades\Route::getRoutes();

    foreach ($routeCollection as $value) {
        echo $value->getName() .'<br>';
    }
})->name('trx');

Route::get('dashboard/notes/{classroom_id}', ViewNote::class)->name('classrooms.notes');
Route::get('pdf/{student}', PdfController::class)->name('pdf');