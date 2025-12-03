<?php

namespace App\Filament\Resources\ReservationResource\Pages;

use App\Filament\Resources\ReservationResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateReservation extends CreateRecord
{
    protected static string $resource = ReservationResource::class;

    function getTitle(): string
    {
        return 'Créer une Réservation';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    // Message de succès
    protected function getCreatedNotificationTitle(): ?string
    {
        return ' Réservation créée avec succès';

    }

    // Traduire les boutons
    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()
                ->label('Créer'),
            $this->getCreateAnotherFormAction()
                ->label('Créer et créer un autre'),
            $this->getCancelFormAction()
                ->label('Annuler'),
        ];
    }
}


