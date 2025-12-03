<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
    function getTitle(): string
    {
        return 'Créer un Utilisateur';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    // Message de succès
    protected function getCreatedNotificationTitle(): ?string
    {
        return ' Utilisateur créée avec succès';

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
