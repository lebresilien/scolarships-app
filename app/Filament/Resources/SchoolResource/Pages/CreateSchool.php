<?php

namespace App\Filament\Resources\SchoolResource\Pages;

use App\Filament\Resources\SchoolResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class CreateSchool extends CreateRecord
{
    protected static string $resource = SchoolResource::class;

    protected function beforeCreate()
    {
        // Runs before the form fields are saved to the database.

        
        //dd($data->count());

        
    }

    protected function handleRecordCreation(array $data): Model {

        $data = \App\Models\School::all();

        if($data->count() > 0) {

            Notification::make()
                ->danger()
                ->title('Une Erreur est survenue')
                ->body('Vous ne pouvez ajouter q\'un seul etablissement')
                ->send();

        } else {

            $this->record->name = $data['name'];
            $this->record->postal_address = $data['postal_address'];
            $this->record->phone_number = $data['phone_number'];
            $this->record->registration_number = $data['registration_number'];
            $this->record->is_primary_school = $data['is_primary_school']; 
            $this->record->logo = $data['logo']; 
            $this->record->description = $data['description']; 
            $this->record->save();

            Notification::make()
                ->success()
                ->title('Etablissement enregistré')
                ->body('Etablissement enregistré avec succès')
                ->send();           
        }

        return $this->record;

    }

    protected function getRedirectUrl(): string {
        return $this->getResource()::getUrl('index');
    } 
}
