<?php

namespace App\Filament\Resources\SalleResource\Pages;

use App\Filament\Resources\SalleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSalle extends CreateRecord
{
    protected static string $resource = SalleResource::class;

    // Titre de la page
    public function getTitle(): string
    {
        return 'Créer une salle';
    }

    // Redirection après création
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    // Message de succès
    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Salle créée avec succès';
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
