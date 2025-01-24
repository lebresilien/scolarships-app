<?php

namespace App\Filament\Resources\SchoolResource\Pages;

use App\Filament\Resources\SchoolResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use App\Models\School;

class CreateSchool extends CreateRecord
{
    protected static string $resource = SchoolResource::class;

    protected function handleRecordCreation(array $data): Model {

        $data_list = School::all();

        if($data_list->count() > 0) {

            Notification::make()
                ->danger()
                ->title('Une Erreur est survenue')
                ->body('Vous ne pouvez ajouter q\'un seul etablissement')
                ->send();
            
            $model = new School();
            return $model;

        } else {
         
            $school = School::create($data);

            Notification::make()
                ->success()
                ->title('Etablissement enregistré')
                ->body('Etablissement enregistré avec succès')
                ->send(); 

            return $school;        
        }

    }

    protected function getCreatedNotificationTitle(): ?string {
        return '';
    }

    protected function getRedirectUrl(): string {
        return $this->getResource()::getUrl('index');
    } 
}
