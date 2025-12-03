<?php

namespace App\Filament\Resources\ReservationResource\Pages;

use App\Filament\Resources\ReservationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReservation extends EditRecord
{
    protected static string $resource = ReservationResource::class;

    // Titre de la page
    public function getTitle(): string
    {
        return 'Modifier la réservation';
    }

    // Redirection après modification
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    // Message de succès
    protected function getSavedNotificationTitle(): ?string
    {
        return 'Réservation modifié avec succès';
    }

    // Traduire les boutons du formulaire
    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction()
                ->label('Enregistrer les modifications'),
            $this->getCancelFormAction()
                ->label('Annuler'),
        ];
    }

    // Traduire les actions de l'en-tête
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Supprimer')
                ->successNotificationTitle('Utilisateur supprimé avec succès'),
        ];
    }
}
