<?php

namespace App\Filament\Resources\SalleResource\Pages;

use App\Filament\Resources\SalleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSalles extends ListRecords
{
    protected static string $resource = SalleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Nouvelle salle')  // ← Ajoutez cette ligne
                ->icon('heroicon-o-plus'), // ← Et cette ligne (optionnel)
        ];
    }

    // Titre de la page
    public function getTitle(): string
    {
        return 'Salles';
    }
}
