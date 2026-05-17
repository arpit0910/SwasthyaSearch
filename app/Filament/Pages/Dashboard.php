<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'Dashboard Overview';
    protected static \UnitEnum|string|null $navigationGroup = 'Overview';
    protected static ?int $navigationSort = -10;
}
