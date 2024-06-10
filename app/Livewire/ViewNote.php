<?php

namespace App\Livewire;

use Livewire\Component;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;

class ViewNote extends Component
{

    public function mount($classroom_id)
    {
       
        /* $students = $classroom->students()
                            ->where('status', true)
                            ->where('academic_id', $academic->id)
                            ->get();

        return $this->students = $students; */
    }

    public function render()
    {
        return view('livewire.view-note');
    }
}
