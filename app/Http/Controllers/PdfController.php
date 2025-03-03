<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{ Sequence, Student, ClassroomStudent, Classroom, Trimester, Note, School };
use Illuminate\Support\Facades\DB;
use App\Filament\Traits\ActiveYear;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PdfController extends Controller
{
    use ActiveYear;
    /**
     * Handle the incoming request.
     */

    public function sequence(Student $student, string $seq)
    {
        $classroom = Classroom::find($student->current_classroom->id);
        
        $error = false;
        foreach($classroom->group->teachings as $ue) {
            foreach($ue->courses as $course) {
                $value = Note::where('course_id', $course->id)->where('sequence_id', $seq)->where('classroom_student_id', $student->policy)->first() ? Note::where('course_id', $course->id)->where('sequence_id', $seq)->where('classroom_student_id', $student->policy)->first()->value : null;
                if($value === null) $error = true;
            }
        }

        if ($error) return view('pdf.hello');

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

        $textLines = [
            'Matricule: ' .$student->matricule,
            'Noms: ' .Str::ascii($student->lname),
            'Prenoms: ' .Str::ascii($student->fname),
            'Date de Naissance: ' .Carbon::parse($student->born_at)->format('d/m/Y'),
            'Lieu de Naissance: ' .Str::ascii($student->born_place),
            'Examen: ' .Str::ascii(Sequence::find($seq)->name),
            'Moyenne: ' .$averageGrades->where('classroom_student_id', $student->policy)->first()->average,
            'Rang: '.$range
            // Add more lines as needed
        ];

        $text = implode(PHP_EOL, $textLines);
        $qr_code = QrCode::size(60)->generate($text);
     
        return view('pdf.sequence', [
            'record' => $student,
            'seq' => Sequence::find($seq),
            'policy' => ClassroomStudent::find($student->policy),
            'statistics' => $averageGrades,
            'range' => $range,
            'school' => School::all()->first(),
            'qr_code' => $qr_code
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
       
        $textLines = [
            'Matricule: ' .$student->matricule,
            'Noms: ' .Str::ascii($student->lname),
            'Prenoms: ' .Str::ascii($student->fname),
            'Date de Naissance: ' .Carbon::parse($student->born_at)->format('d/m/Y'),
            'Lieu de Naissance: ' .Str::ascii($student->born_place),
            'Examen: ' .Str::ascii($trimester->name),
            'Moyenne: ' .$averageGrades->where('classroom_student_id', $student->policy)->first()->average,
            'Rang: ' .$range
            // Add more lines as needed
        ];

        $text = implode(PHP_EOL, $textLines);
        $qr_code = QrCode::size(60)->generate($text);

        return view('pdf.trimester', [
            'record' => $student,
            'seq' => $trimester,
            'policy' => ClassroomStudent::find($student->policy),
            'statistics' => $averageGrades,
            'range' => $range,
            'school' => School::all()->first(),
            'sequences_id' => $sequences_id,
            'qr_code' => $qr_code
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
