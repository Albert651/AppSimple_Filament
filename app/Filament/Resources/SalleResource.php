<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SalleResource\Pages;
use App\Models\Salle;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SalleResource extends Resource
{
    protected static ?string $model = Salle::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationLabel = 'Salles';

    protected static ?string $modelLabel = 'salle';

    protected static ?string $pluralModelLabel = 'salles';

    protected static ?string $navigationGroup = 'Gestion des Salles';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informations générales')
                    ->schema([
                        Forms\Components\TextInput::make('nom')
                            ->label('Nom de la salle')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Ex: Salle de conférence A'),

                        Forms\Components\TextInput::make('capacite')
                            ->label('Capacité (personnes)')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(1000)
                            ->suffix('personnes'),

                        Forms\Components\TextInput::make('tarif_heure')
                            ->label('Tarif horaire')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->prefix('Ar')
                            ->suffix('/heure'),

                        Forms\Components\Toggle::make('disponible')
                            ->label('Disponible à la réservation')
                            ->default(true),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Détails')
                    ->schema([
                        Forms\Components\FileUpload::make('photo')
                            ->label('Photo')
                            ->image()
                            ->directory('salles')
                            ->disk('public')
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '16:9',
                                '4:3',
                                '1:1',
                            ])
                            ->maxSize(2048),


                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->rows(3)
                            ->placeholder('Description de la salle...')
                            ->columnSpanFull(),

                        Forms\Components\TagsInput::make('equipements')
                            ->label('Équipements')
                            ->placeholder('Ajouter un équipement')
                            ->suggestions([
                                'Vidéoprojecteur',
                                'Écran',
                                'Tableau blanc',
                                'Climatisation',
                                'WiFi',
                                'Visioconférence',
                                'Microphone',
                                'Système audio',
                                'Machine à café',
                                'Paperboard',
                                'Ordinateurs',
                            ])
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('photo')
                    ->label('Photo')
                    ->disk('public')
                    ->circular()
                    ->defaultImageUrl(fn() => asset('images/no-image.png')),

                Tables\Columns\TextColumn::make('nom')
                    ->label('Nom')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('capacite')
                    ->label('Capacité')
                    ->suffix(' pers.')
                    ->alignCenter()
                    ->sortable(),

                Tables\Columns\TextColumn::make('tarif_heure')
                    ->label('Tarif/h')
                    ->money('MGA')
                    ->alignEnd()
                    ->sortable(),

                Tables\Columns\IconColumn::make('disponible')
                    ->label('Disponible')
                    ->boolean()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('reservations_count')
                    ->label('Réservations')
                    ->counts('reservations')
                    ->badge()
                    ->color('primary')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créée le')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('disponible')
                    ->label('Disponibilité')
                    ->placeholder('Toutes les salles')
                    ->trueLabel('Disponibles uniquement')
                    ->falseLabel('Non disponibles uniquement'),

                Tables\Filters\Filter::make('capacite')
                    ->form([
                        Forms\Components\TextInput::make('capacite_min')
                            ->label('Capacité minimum')
                            ->numeric()
                            ->placeholder('Ex: 20'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query->when(
                            $data['capacite_min'],
                            fn ($q) => $q->where('capacite', '>=', $data['capacite_min'])
                        );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (!$data['capacite_min']) {
                            return null;
                        }
                        return 'Capacité ≥ ' . $data['capacite_min'];
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Voir'),
                Tables\Actions\EditAction::make()
                    ->label('Modifier'),
                Tables\Actions\DeleteAction::make()
                    ->label('Supprimer')
                    ->modalHeading('Supprimer la salle')
                    ->modalDescription('Êtes-vous sûr de vouloir supprimer cette salle ? Cette action est irréversible.')
                    ->modalSubmitActionLabel('Oui, supprimer')
                    ->modalCancelActionLabel('Annuler')
                    ->successNotificationTitle('Salle supprimée avec succès'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Supprimer la sélection')
                        ->modalHeading('Supprimer les salles sélectionnées')
                        ->modalDescription('Êtes-vous sûr de vouloir supprimer ces salles ?')
                        ->modalSubmitActionLabel('Supprimer')
                        ->modalCancelActionLabel('Annuler')
                        ->successNotificationTitle('Salles supprimées avec succès'),

                ]),
            ])
            ->emptyStateHeading('Aucune salle')
            ->emptyStateDescription('Créez votre première salle pour commencer.')
            ->emptyStateIcon('heroicon-o-building-office')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Créer une salle')
                    ->icon('heroicon-o-plus'),
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
            'index' => Pages\ListSalles::route('/'),
            'create' => Pages\CreateSalle::route('/create'),
            'edit' => Pages\EditSalle::route('/{record}/edit'),
        ];
    }

    // Badge affichant le nombre total de salles dans la navigation
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    // Badge affichant le nombre de salles disponibles
    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::where('disponible', true)->count() > 0
            ? 'success'
            : 'warning';
    }
}
