<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{ Sequence, Student, ClassroomStudent, Classroom };
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Filament\Traits\ActiveYear;

class PdfController extends Controller
{
    use ActiveYear;
    /**
     * Handle the incoming request.
     */
    public function __invoke(Student $student, string $seq)
    {
        $classroom = Classroom::find($student->current_classroom->id);

        $averageGrades = DB::table('notes')
                            ->select('classroom_student_id', DB::raw('(SUM(value * courses.coefficient) / SUM(courses.coefficient)) as average'))
                            ->where('sequence_id', $seq)
                            ->whereIn('classroom_student_id', $classroom->students->where('pivot.academic_id', $this->active()->id)->pluck('pivot.id'))
                            ->join('courses', 'courses.id', '=', 'notes.course_id')
                            ->groupBy('classroom_student_id')
                            ->get();
        $range = 1;
        
        foreach($averageGrades as $item) {
            if($averageGrades->where('classroom_student_id', $student->policy)->first()->average > $item->average) {
                $range++;
            }
        }
        
        return view('pdf', [
            'record' => $student,
            'seq' => Sequence::find($seq),
            'policy' => ClassroomStudent::find($student->policy),
            'statistics' => $averageGrades,
            'range' => $range
        ]);
    }
}
