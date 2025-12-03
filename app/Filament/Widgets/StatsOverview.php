<?php

namespace App\Filament\Widgets;

use App\Models\Reservation;
use App\Models\Salle;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $reservationsEnAttente = Reservation::where('statut', 'en_attente')->count();
        $reservationsCeMois = Reservation::whereMonth('date_debut', now()->month)
            ->whereYear('date_debut', now()->year)
            ->count();
        $revenuMensuel = Reservation::where('statut', 'confirmee')
            ->whereMonth('date_debut', now()->month)
            ->whereYear('date_debut', now()->year)
            ->sum('montant_total');

        return [
            Stat::make('Salles disponibles', Salle::where('disponible', true)->count())
                ->description('Sur ' . Salle::count() . ' salles au total')
                ->descriptionIcon('heroicon-m-building-office')
                ->color('success'),

            Stat::make('Réservations en attente', $reservationsEnAttente)
                ->description('À valider')
                ->descriptionIcon('heroicon-m-clock')
                ->color($reservationsEnAttente > 0 ? 'warning' : 'success'),

            Stat::make('Réservations ce mois', $reservationsCeMois)
                ->description(now()->format('F Y'))
                ->descriptionIcon('heroicon-m-calendar')
                ->color('primary'),

            Stat::make('Revenu mensuel', number_format($revenuMensuel, 2, ',', ' ') . ' Ar')
                ->description('Réservations confirmées')
                ->color('success'),
        ];
    }
}
