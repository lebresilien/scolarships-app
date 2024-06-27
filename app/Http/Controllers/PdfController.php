<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Student;

class PdfController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Student $student)
    {
        return Pdf::loadView('pdf', ['record' => $student])
            ->download($student->matricule. '.pdf');
    }
}
