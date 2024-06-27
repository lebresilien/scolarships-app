<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\ViewTransaction;
use App\Livewire\ViewNote;
use App\Http\Controllers\PdfController;

/* Route::get('/trx/{record}', function ($record) {
    return $record;
})->name('trx'); */

Route::get('dashboard/notes/{classroom_id}', ViewNote::class)->name('classrooms.notes');
Route::get('pdf/{student}', PdfController::class)->name('pdf');