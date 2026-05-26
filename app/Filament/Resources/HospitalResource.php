<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HospitalResource\Pages;
use App\Models\Hospital;
use Filament\Tables\Actions;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class HospitalResource extends Resource
{
    protected static ?string $model = Hospital::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-building-office-2';
    protected static \UnitEnum|string|null $navigationGroup = 'Directory Management';
    protected static ?int $navigationSort = 1;


    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Section::make('Hospital/Clinic Details')
                    ->schema([
                        Forms\Components\TextInput::make('name.en')
                            ->label('Name (English)')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('name.hi')
                            ->label('Name (Hindi)')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('type')
                            ->options([
                                'Hospital' => 'Hospital',
                                'Clinic' => 'Clinic',
                                'Nursing Home' => 'Nursing Home',
                                'Diagnostic Center' => 'Diagnostic Center',
                            ])
                            ->required()
                            ->default('Hospital'),
                        Forms\Components\TextInput::make('emergency_phone_1')
                            ->label('Emergency Phone 1')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('emergency_phone_2')
                            ->label('Emergency Phone 2')
                            ->maxLength(255),
                        Forms\Components\Textarea::make('address')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('city')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('latitude')
                            ->numeric(),
                        Forms\Components\TextInput::make('longitude')
                            ->numeric(),
                        Forms\Components\Toggle::make('is_verified')
                            ->required()
                            ->default(true),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name.en')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->searchable()
                    ->sortable()
                    ->badge(),
                Tables\Columns\TextColumn::make('city')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('emergency_phone_1')
                    ->label('Emergency Phone 1')
                    ->searchable(),
                Tables\Columns\TextColumn::make('emergency_phone_2')
                    ->label('Emergency Phone 2')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_verified')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListHospitals::route('/'),
            'create' => Pages\CreateHospital::route('/create'),
            'edit' => Pages\EditHospital::route('/{record}/edit'),
        ];
    }
}
