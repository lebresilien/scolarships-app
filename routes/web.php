<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\ViewTransaction;
use App\Livewire\ViewNote;
use App\Http\Controllers\PdfController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;

Route::get('routes', function () {
    /* $routeCollection = Illuminate\Support\Facades\Route::getRoutes();

    foreach ($routeCollection as $value) {
        echo $value->getName() .'<br>';
    } */
    //return \App\Models\School::all()->first()->is_primary_school;
    return \App\Models\User::factory(2)->state(new Sequence(
        ['remember_token' => 'YZZZEERTYUII102'],
        ['remember_token' => 'NDKKDJDJDOD8LD'],
    ))->create();
})->name('trx');

Route::get('test', function() {
    
    // ... in your controller or service ...
    
    $data = [
        // Your PDF data
    ];
    
    $watermarkText = 'Confidential'; // Or make this dynamic
    
    $pdf = PDF::loadHTML(View::make('pdf-file', $data)->render());
    
    // Add the watermark using a background image (more control over positioning)
    $pdf->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]); // Important for background-image

    
    // Or add the watermark using the view (less control over positioning, easier for text)
    $watermark = View::make('watermark', ['watermarkText' => $watermarkText])->render();
    
    // Add the watermark HTML before or after the PDF content (depending on your needs)
    $pdf->loadHTML($watermark . View::make('pdf-file', $data)->render()); // Watermark behind content
    // $pdf->loadHTML(View::make('your.pdf.view', $data)->render() . $watermark); // Watermark over content
    
    
    return $pdf->stream('document.pdf'); // Or ->download()
});

Route::get('dashboard/notes/{classroom_id}', ViewNote::class)->name('classrooms.notes');
Route::get('report/{student}/{seq}', [PdfController::class, 'sequence'])->name('report');
Route::get('trimester-report/{student}/{trimester}', [PdfController::class, 'trimester'])->name('trimester-report');
Route::get('reports/{student}/{seq}', [PdfController::class, 'loadReport'])->name('reports');