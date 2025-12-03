<?php

namespace App\Filament\Widgets;

use App\Models\Reservation;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class ReservationsChart extends ChartWidget
{
    protected static ?string $heading = 'Réservations par mois';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $data = [];
        $labels = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $labels[] = $date->format('M Y');

            $data[] = Reservation::whereMonth('date_debut', $date->month)
                ->whereYear('date_debut', $date->year)
                ->where('statut', '!=', 'annulee')
                ->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Réservations',
                    'data' => $data,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.5)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
