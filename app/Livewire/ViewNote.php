<?php

namespace App\Livewire;

use Livewire\Component;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use App\Models\{ Sequence, Classroom, Note };
use App\Filament\Traits\ActiveYear;
use Illuminate\Support\Facades\Log;

class ViewNote extends Component
{
    use ActiveYear;

    public $sequences;
    public $record;
    protected $academicYear;
    public $isOpen = false;
    public $course;
    public $seq;
    public $students = [];

    public function mount($record)
    {
        $this->academicYear = $this->active();

        $this->sequences = Sequence::where('academic_id', $this->academicYear->id)->get();

        $this->record = $record;
    }

    public function show($course, $seq)
    {
        $this->isOpen = true;
        $this->course = $course;
        $this->seq = $seq; 
        
        $data = $this->record->students()
                            ->wherePivot('academic_id', $this->active()->id)
                            ->wherePivot('status', true)
                            ->get();
        //dd($data);

        $result = collect([]);

        foreach($data as $student) {

            $note = Note::where('classroom_student_id', $student->pivot->id)
                        ->where('course_id', $course['id'])
                        ->where('sequence_id', $seq['id'])
                        ->first();
            
            $result->push([
                'id' =>  $student->pivot->id,
                'name' => $student->lname . ' ' . $student->fname,
                'value' => $note ?  $note->value : 0,
            ]);
        }

        $this->students = $result;
    }

    public function hide()
    {
        $this->isOpen = false;
    }

    public function render()
    {
        return view('livewire.view-note');
    }
}
