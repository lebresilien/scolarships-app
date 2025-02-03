<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Log;

class Note extends Component
{
    public function mount($record)
    {
        $this->record = $record;
    }

    public function render()
    {
        return view('pdf.hello');
    }
}