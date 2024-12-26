<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Transaction;
use App\Filament\Traits\ActiveYear;
use Illuminate\Support\Facades\Log;

class DashboardTransactionOverview extends ChartWidget
{
    use ActiveYear;

    protected static ?string $heading = 'Historique';

    protected function getData(): array
    {
        // Obtenir la date du jour
        $today = now();

        $months = collect([]);

        $period = \Carbon\CarbonPeriod::create(now()->subMonths(10), '1 months', $today);

        foreach($period as $p) {
            $months->push($p);
        }
        
        $arr = [];
        $transactions = collect([]);

          // Récupérer le nombre de versements pour chaque mois
          foreach($months as $month) {
            // Obtenir la date de début du mois
            $start = $month->startOfMonth()->format('Y-m-d');

            // Obtenir la date de fin du mois
            $end = $month->endOfMonth()->format('Y-m-d');

            // Obtenir le nombre de versements
            $total = Transaction::whereBetween('created_at', [$start, $end])->whereIn('classroom_student_id', $this->active()->policies->pluck('id'))->sum('amount');
            $transactions->push($total);

            array_push($arr, $month->format('F'));
            // Renvoyer le mois et le nombre de transactions
        };


        return [
            'datasets' => [
                [
                    'label' => 'Historique de versements des 9 deniers mois',
                    'data' => $transactions,
                ],
            ],
            'labels' => $arr
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
