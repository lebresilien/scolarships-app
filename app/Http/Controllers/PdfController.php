<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{ Sequence, Student, ClassroomStudent, Classroom, Trimester, Note, School };
use Illuminate\Support\Facades\DB;
use App\Filament\Traits\ActiveYear;

class PdfController extends Controller
{
    use ActiveYear;
    /**
     * Handle the incoming request.
     */

    public function sequence(Student $student, string $seq)
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
            if($item->average > $averageGrades->where('classroom_student_id', $student->policy)->first()->average) {
                $range++;
            }
        }
      
        return view('pdf.sequence', [
            'record' => $student,
            'seq' => Sequence::find($seq),
            'policy' => ClassroomStudent::find($student->policy),
            'statistics' => $averageGrades,
            'range' => $range,
            'school' => School::all()->first(),
        ]);
    }

    public function trimester(Student $student, Trimester $trimester)
    {
        $classroom = Classroom::find($student->current_classroom->id);
        $sequences_id = [];
        
        foreach($trimester->sequence as $item) {
            array_push($sequences_id, $item['sequence']);
        }
        
        $averageGrades = DB::table('notes')
                            ->select('classroom_student_id', DB::raw('(SUM(value * courses.coefficient) / SUM(courses.coefficient)) as average'))
                            ->whereIn('sequence_id', $sequences_id)
                            ->whereIn('classroom_student_id', $classroom->students->where('pivot.academic_id', $this->active()->id)->pluck('pivot.id'))
                            ->join('courses', 'courses.id', '=', 'notes.course_id')
                            ->groupBy('classroom_student_id')
                            ->get();
        
        $range = 1;
        
        foreach($averageGrades as $item) {
            if($item->average > $averageGrades->where('classroom_student_id', $student->policy)->first()->average) {
                $range++;
            }
        }
       // return $trimester->notes($sequences_id)->get();
        return view('pdf.trimester', [
            'record' => $student,
            'seq' => $trimester,
            'policy' => ClassroomStudent::find($student->policy),
            'statistics' => $averageGrades,
            'range' => $range,
            'school' => School::all()->first(),
            'sequences_id' => $sequences_id
        ]);
    }

    public function calculerMoyenneTrimestrielle($classroom_student_id, $sequence_id)
    {

        // Récupérer les notes de l'étudiant pour ces séquences
        $notes = Note::where('classroom_student_id', $classroom_student_id)
            ->whereIn('sequence_id', $sequence_id)
            ->get();

        // Calcul de la moyenne pondérée
        $totalValeurCoefficient = 0;
        $totalCoefficient = 0;

        foreach ($notes as $note) {
            $totalValeurCoefficient += $note->value * $note->course->coefficient;
            $totalCoefficient += $note->course->coefficient;
        }

        if ($totalCoefficient == 0) {
            return null; // Pas de coefficient, donc pas de moyenne
        }

        return $totalValeurCoefficient / $totalCoefficient;
    }

}
