<?php

namespace App\Livewire;

use Livewire\Component;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use App\Models\Classroom;
use App\Filament\Traits\ActiveYear;

class ViewNote extends Component
{
    use ActiveYear;

    public $classrooms ;
    protected $academicYear;

    public function mount()
    {
        $this->academicYear = $this->active();

       /*  $this->classrooms = Classroom::whereHas('students', function($query) {
            $query->where('academic_id', $this->academicYear->id);
        })->get(); */

        $this->classrooms = Classroom::all();
    }

    public function render()
    {
        return view('livewire.view-note');
    }
}
