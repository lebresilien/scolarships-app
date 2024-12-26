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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Filament\Notifications\Notification;

class ViewNote extends Component
{
    use ActiveYear;

    public $sequences;
    public $record;
    protected $academicYear;
    public $isOpen = false;
    public $course;
    public $se;
    public $students = [];
    public $form = [];

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
        $this->se = $seq;
        
        $this->dispatch('open-modal', id: 'modal');

        $data = $this->record->students()
                            ->wherePivot('academic_id', $this->active()->id)
                            ->wherePivot('status', true)
                            ->get();

        $result = collect([]);

        foreach($data as $index => $student) {

            $note = Note::where('classroom_student_id', $student->pivot->id)
                        ->where('course_id', $course['id'])
                        ->where('sequence_id', $seq['id'])
                        ->first();

            $result->push([
                'id' =>  $student->pivot->id,
                'name' => $student->lname . ' ' . $student->fname,
                'value' => $note ?  $note->value : 0,
            ]);

            $this->form[$index]['policy'] = $student->pivot->id;
            $this->form[$index]['value'] = $note ?  $note->value : 0;
        }

        $this->students = $result;
    }

    public function hide()
    {
        $this->dispatch('close-modal', id: 'modal');
    }

    public function save() {

        $error = false;

        foreach($this->form as $item) {
            if($item['value'] > 20) {
                $error = true;
            }
        }

        if($error) {
            Notification::make()
                ->danger()
                ->title('Erreur')
                ->body('Une note superieure à 20 a été detectée.')
                ->send();
        } else 
        {
            DB::transaction(function () {

                foreach ($this->form as $item) {
    
                    
                    Note::updateOrCreate(
                        [
                            "classroom_student_id" => $item['policy'],
                            "sequence_id" => $this->se['id'],
                            "course_id" => $this->course['id']
                        ],
                        [
                            "value" => $item['value']
                        ]
                    );
    
                }
    
                $this->hide();
    
            });
    
            Notification::make()
                ->success()
                ->title('Opération réussie')
                ->body('Notes enregistrées.')
                ->send();
        }

    }

    public function render()
    {
        return view('livewire.view-note');
    }
}