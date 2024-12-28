<?php

namespace App\Filament\Resources\TrimesterResource\Pages;

use App\Filament\Resources\TrimesterResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use App\Models\{ Trimester };
use App\Filament\Traits\ActiveYear;

class CreateTrimester extends CreateRecord
{
    use ActiveYear;

    protected static string $resource = TrimesterResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['academic_id'] = $this->active()->id;
    
        return $data;
    }
}
