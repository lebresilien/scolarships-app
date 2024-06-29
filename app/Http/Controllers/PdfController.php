<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Spatie\LaravelPdf\Facades\Pdf;

class PdfController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Student $student)
    {
        return Pdf::view('pdf', ['record' => $student])
            ->format('a4')
            ->name('your-invoice.pdf')
            ->download();
    }
}
