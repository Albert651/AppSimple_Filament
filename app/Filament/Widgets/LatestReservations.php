<?php

namespace App\Filament\Widgets;

use App\Models\Reservation;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestReservations extends BaseWidget
{
    protected static ?string $heading = 'Dernières réservations';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Reservation::query()
                    ->with(['salle', 'user'])
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('salle.nom')
                    ->label('Salle'),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Réservé par'),

                Tables\Columns\TextColumn::make('objet')
                    ->label('Objet')
                    ->limit(30),

                Tables\Columns\TextColumn::make('date_debut')
                    ->label('Date')
                    ->dateTime('d/m/Y H:i'),

                Tables\Columns\BadgeColumn::make('statut')
                    ->label('Statut')
                    ->colors([
                        'warning' => 'en_attente',
                        'success' => 'confirmee',
                        'danger' => 'annulee',
                        'gray' => 'terminee',
                    ])
                    ->formatStateUsing(fn (string $state): string => Reservation::getStatuts()[$state] ?? $state),

                Tables\Columns\TextColumn::make('montant_total')
                    ->label('Montant')
                    ->money('Ariary', true),
            ])
            ->paginated(false);
    }
}
