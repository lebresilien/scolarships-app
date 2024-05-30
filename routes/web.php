<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\ViewTransaction;

/* Route::get('/trx/{record}', function ($record) {
    return $record;
})->name('trx'); */

Route::get('trx', ViewTransaction::class)->name('trx');
