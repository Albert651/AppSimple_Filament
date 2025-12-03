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

    protected static ?string $modelLabel = 'Salle';

    protected static ?string $pluralModelLabel = 'Salles';

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
                            ->prefix('€')
                            ->suffix('/heure'),

                        Forms\Components\Toggle::make('disponible')
                            ->label('Disponible à la réservation')
                            ->default(true),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Détails')
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->rows(3)
                            ->placeholder('Description de la salle...'),

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
                            ]),

                        Forms\Components\FileUpload::make('image')
                            ->label('Photo de la salle')
                            ->image()
                            ->directory('salles')
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('16:9')
                            ->imageResizeTargetWidth('1920')
                            ->imageResizeTargetHeight('1080'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Photo')
                    ->circular(),

                Tables\Columns\TextColumn::make('nom')
                    ->label('Nom')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('capacite')
                    ->label('Capacité')
                    ->suffix(' pers.')
                    ->sortable(),

                Tables\Columns\TextColumn::make('tarif_heure')
                    ->label('Tarif/h')
                    ->money('EUR')
                    ->sortable(),

                Tables\Columns\IconColumn::make('disponible')
                    ->label('Disponible')
                    ->boolean(),

                Tables\Columns\TextColumn::make('reservations_count')
                    ->label('Réservations')
                    ->counts('reservations')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créée le')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('disponible')
                    ->label('Disponibilité'),

                Tables\Filters\Filter::make('capacite')
                    ->form([
                        Forms\Components\TextInput::make('capacite_min')
                            ->label('Capacité minimum')
                            ->numeric(),
                    ])
                    ->query(function ($query, array $data) {
                        return $query->when(
                            $data['capacite_min'],
                            fn ($q) => $q->where('capacite', '>=', $data['capacite_min'])
                        );
                    }),
            ])
            ->actions([
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
            'index' => Pages\ListSalles::route('/'),
            'create' => Pages\CreateSalle::route('/create'),
            'edit' => Pages\EditSalle::route('/{record}/edit'),
        ];
    }
}
