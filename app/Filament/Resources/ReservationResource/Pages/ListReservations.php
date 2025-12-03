<?php

namespace App\Filament\Resources\ReservationResource\Pages;

use App\Filament\Resources\ReservationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReservations extends ListRecords
{
    protected static string $resource = ReservationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Nouvelle Reservation')  // ← Ajoutez cette ligne
                ->icon('heroicon-o-plus'), // ← Et cette ligne (optionnel)
        ];
    }

    // Titre de la page
    public function getTitle(): string
    {
        return 'Réservations';
    }
}
