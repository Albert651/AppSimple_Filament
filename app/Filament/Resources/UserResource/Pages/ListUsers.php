<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Nouveaux Utilisateur')  // ← Ajoutez cette ligne
                ->icon('heroicon-o-plus'),
        ];
    }

    // Titre de la page
     public function getTitle(): string
    {
        return 'Utilisateurs';
    }
}
