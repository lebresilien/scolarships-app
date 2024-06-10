<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\ViewTransaction;
use App\Livewire\ViewNote;

/* Route::get('/trx/{record}', function ($record) {
    return $record;
})->name('trx'); */

Route::get('dashboard/notes/{classroom_id}', ViewNote::class)->name('classrooms.notes');
