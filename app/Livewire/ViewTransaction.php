<?php

namespace App\Livewire;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Livewire\Component;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;

class ViewTransaction extends Component
{
    
    public function render()
    {
        return view('livewire.view-transaction');
    }
}
