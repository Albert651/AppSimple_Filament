<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReservationResource\Pages;
use App\Models\Reservation;
use App\Models\Salle;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ReservationResource extends Resource
{
    protected static ?string $model = Reservation::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationLabel = 'Réservations';

    protected static ?string $modelLabel = 'Réservation';

    protected static ?string $pluralModelLabel = 'Réservations';

    protected static ?string $navigationGroup = 'Gestion des Salles';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Détails de la réservation')
                    ->schema([
                        Forms\Components\Select::make('salle_id')
                            ->label('Salle')
                            ->relationship('salle', 'nom')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                if ($state && $get('date_debut') && $get('date_fin')) {
                                    $salle = Salle::find($state);
                                    if ($salle) {
                                        $heures = (strtotime($get('date_fin')) - strtotime($get('date_debut'))) / 3600;
                                        $set('montant_total', $heures * $salle->tarif_heure);
                                    }
                                }
                            }),

                        Forms\Components\Select::make('user_id')
                            ->label('Réservé par')
                            ->relationship('user', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->default(auth()->id()),

                        Forms\Components\DateTimePicker::make('date_debut')
                            ->label('Date et heure de début')
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y H:i')
                            ->minutesStep(15)
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                if ($state && $get('salle_id') && $get('date_fin')) {
                                    $salle = Salle::find($get('salle_id'));
                                    if ($salle) {
                                        $heures = (strtotime($get('date_fin')) - strtotime($state)) / 3600;
                                        $set('montant_total', max(0, $heures * $salle->tarif_heure));
                                    }
                                }
                            }),

                        Forms\Components\DateTimePicker::make('date_fin')
                            ->label('Date et heure de fin')
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y H:i')
                            ->minutesStep(15)
                            ->after('date_debut')
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                if ($state && $get('salle_id') && $get('date_debut')) {
                                    $salle = Salle::find($get('salle_id'));
                                    if ($salle) {
                                        $heures = (strtotime($state) - strtotime($get('date_debut'))) / 3600;
                                        $set('montant_total', max(0, $heures * $salle->tarif_heure));
                                    }
                                }
                            }),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Informations complémentaires')
                    ->schema([
                        Forms\Components\TextInput::make('objet')
                            ->label('Objet de la réservation')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Ex: Réunion d\'équipe, Formation, Conférence...'),

                        Forms\Components\TextInput::make('nombre_participants')
                            ->label('Nombre de participants')
                            ->numeric()
                            ->minValue(1)
                            ->suffix('personnes'),

                        Forms\Components\Select::make('statut')
                            ->label('Statut')
                            ->options(Reservation::getStatuts())
                            ->default(Reservation::STATUT_EN_ATTENTE)
                            ->required(),

                        Forms\Components\TextInput::make('montant_total')
                            ->label('Montant total')
                            ->numeric()
                            ->prefix('€')
                            ->disabled()
                            ->dehydrated(),

                        Forms\Components\Textarea::make('notes')
                            ->label('Notes')
                            ->rows(3)
                            ->columnSpanFull()
                            ->placeholder('Notes supplémentaires...'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('N°')
                    ->sortable(),

                Tables\Columns\TextColumn::make('salle.nom')
                    ->label('Salle')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Réservé par')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('objet')
                    ->label('Objet')
                    ->searchable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('date_debut')
                    ->label('Début')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('date_fin')
                    ->label('Fin')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('nombre_participants')
                    ->label('Participants')
                    ->suffix(' pers.')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('montant_total')
                    ->label('Montant')
                    ->money('EUR')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('statut')
                    ->label('Statut')
                    ->colors([
                        'warning' => 'en_attente',
                        'success' => 'confirmee',
                        'danger' => 'annulee',
                        'gray' => 'terminee',
                    ])
                    ->formatStateUsing(fn (string $state): string => Reservation::getStatuts()[$state] ?? $state),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créée le')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('date_debut', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('salle_id')
                    ->label('Salle')
                    ->relationship('salle', 'nom'),

                Tables\Filters\SelectFilter::make('statut')
                    ->label('Statut')
                    ->options(Reservation::getStatuts()),

                Tables\Filters\Filter::make('date_debut')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('Du'),
                        Forms\Components\DatePicker::make('until')
                            ->label('Au'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date_debut', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date_debut', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('confirmer')
                    ->label('Confirmer')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Reservation $record): bool => $record->statut === Reservation::STATUT_EN_ATTENTE)
                    ->action(fn (Reservation $record) => $record->update(['statut' => Reservation::STATUT_CONFIRMEE])),

                Tables\Actions\Action::make('annuler')
                    ->label('Annuler')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (Reservation $record): bool => in_array($record->statut, [Reservation::STATUT_EN_ATTENTE, Reservation::STATUT_CONFIRMEE]))
                    ->action(fn (Reservation $record) => $record->update(['statut' => Reservation::STATUT_ANNULEE])),

                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReservations::route('/'),
            'create' => Pages\CreateReservation::route('/create'),
            'edit' => Pages\EditReservation::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('statut', Reservation::STATUT_EN_ATTENTE)->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
