<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Filament\Traits\{ ClassroomStudent, ActiveYear };
use Illuminate\Support\Facades\DB;

class DashboardOverview extends BaseWidget
{
    use ActiveYear;

    protected function getStats(): array
    {
        $students = $this->active()->policies;


        $boys = DB::table('classroom_student')
                ->where('academic_id', $this->active()->id)
                ->where('students.sexe', true)
                ->join('students', 'students.id', '=', 'classroom_student.student_id')
                ->count();
        
        $girls = DB::table('classroom_student')
                ->where('academic_id', $this->active()->id)
                ->where('students.sexe', false)
                ->join('students', 'students.id', '=', 'classroom_student.student_id')
                ->count();

        return [
            Stat::make('Nombre d\'apprenants', $students->count()),
            Stat::make('Nombre de garçons', $boys),
            Stat::make('Nombre de filles', $girls),
            Stat::make('Nombre de démissionnaires', $students->where('status', false)->count()),
        ];
    }
}
