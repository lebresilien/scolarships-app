<?php

namespace App\Filament\Resources\SequenceResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Illuminate\Support\Facades\DB;
use App\Models\Note;
use Illuminate\Support\Facades\Log;
use App\Models\Sequence;

class StatsOverview extends BaseWidget
{
    use InteractsWithRecord;

    public function mount(int | string $record): void
    {
        $this->record = $record;
    }

    protected function getStats(): array
    {
        $participate_success = 0;
        
        $participates = Note::where('sequence_id', json_decode($this->record)->id)
                    ->distinct('classroom_student_id')
                    ->count();
        //dd($participates);
        $averageGrades = DB::table('notes')
                ->select('classroom_student_id', DB::raw('(SUM(value * courses.coefficient) / SUM(courses.coefficient)) as average'))
                ->where('sequence_id', json_decode($this->record)->id)
                ->join('courses', 'courses.id', '=', 'notes.course_id')
                ->groupBy('classroom_student_id')
                ->get();
      
        foreach($averageGrades as $item) {
            if($item->average >= 10) {
                $participate_success++;
            }
        }
        
        return $participates > 0 ?  [
            Stat::make('Nombre d\'apprenants ayants composés', $participates),
            Stat::make('Pourcentage de reussite', round(($participate_success * 100) / $participates, 2) .'%'),
            Stat::make('Note maximale', round($averageGrades->max('average'), 2)),
            Stat::make('Note minimale', round($averageGrades->min('average'), 2)),
        ] : [];
    }
}
