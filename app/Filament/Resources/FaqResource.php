<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FaqResource\Pages;
use App\Models\Faq;
use Filament\Actions;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FaqResource extends Resource
{
    protected static ?string $model = Faq::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-question-mark-circle';
    protected static \UnitEnum|string|null $navigationGroup = 'Content Management';
    protected static ?int $navigationSort = 2;


    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Section::make('FAQ Details')
                    ->schema([
                        Forms\Components\TextInput::make('question.en')
                            ->label('Question (English)')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('question.hi')
                            ->label('Question (Hindi)')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('answer.en')
                            ->label('Answer (English)')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('answer.hi')
                            ->label('Answer (Hindi)')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('category')
                            ->required()
                            ->maxLength(255)
                            ->default('General'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('question.en')
                    ->label('Question')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category')
                    ->searchable()
                    ->sortable()
                    ->badge(),
            ])
            ->filters([
                //
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
            'index' => Pages\ListFaqs::route('/'),
            'create' => Pages\CreateFaq::route('/create'),
            'edit' => Pages\EditFaq::route('/{record}/edit'),
        ];
    }
}
