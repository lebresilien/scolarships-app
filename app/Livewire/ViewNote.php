<?php

namespace App\Livewire;

use Livewire\Component;

class ViewNote extends Component
{
    public $students;

    public function mount($students)
    {
        $this->students = $students;
    }

    public function render()
    {
        return view('livewire.view-note');
    }
}
