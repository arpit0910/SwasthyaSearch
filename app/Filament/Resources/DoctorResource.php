<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DoctorResource\Pages;
use App\Models\Doctor;
use Filament\Actions;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DoctorResource extends Resource
{
    protected static ?string $model = Doctor::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-user-group';
    protected static \UnitEnum|string|null $navigationGroup = 'Directory Management';
    protected static ?int $navigationSort = 2;


    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Section::make('Doctor Information')
                    ->schema([
                        Forms\Components\TextInput::make('first_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('last_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('department_id')
                            ->relationship('department', 'name.en')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('registration_number')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('medical_council')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('experience_years')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(70),
                        Forms\Components\Textarea::make('about.en')
                            ->label('About (English)')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('about.hi')
                            ->label('About (Hindi)')
                            ->required()
                            ->columnSpanFull(),
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
                Tables\Columns\TextColumn::make('first_name')
                    ->label('First Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->label('Last Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('department.name.en')
                    ->label('Department')
                    ->searchable()
                    ->sortable()
                    ->badge(),
                Tables\Columns\TextColumn::make('registration_number')
                    ->searchable()
                    ->fontFamily('mono'),
                Tables\Columns\TextColumn::make('experience_years')
                    ->label('Experience')
                    ->sortable()
                    ->suffix(' yrs'),
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
            'index' => Pages\ListDoctors::route('/'),
            'create' => Pages\CreateDoctor::route('/create'),
            'edit' => Pages\EditDoctor::route('/{record}/edit'),
        ];
    }
}
