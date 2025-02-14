<?php

namespace App\Filament\Resources\SequenceResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Illuminate\Support\Facades\DB;
use App\Models\Note;
use Illuminate\Support\Facades\Log;
use App\Models\Sequence;
use Illuminate\Database\Eloquent\Model;

class TrimesterOverview extends BaseWidget
{
    use InteractsWithRecord;

    public function mount(Model $record): void
    {
        $this->record = $record;        
    }

    protected function getStats(): array
    {
        $sequences_id = [];
        $participate_success = 0;
        
        foreach($this->record->sequence as $item) {
            array_push($sequences_id, $item['sequence']);
        }

        $participates = Note::whereIn('sequence_id', $sequences_id)
                            ->distinct('classroom_student_id')
                            ->count();

        $averageGrades = DB::table('notes')
                            ->select('classroom_student_id', DB::raw('(SUM(value * courses.coefficient) / SUM(courses.coefficient)) as average'))
                            ->whereIn('sequence_id', $sequences_id)
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