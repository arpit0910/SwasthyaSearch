<?php

namespace App\Filament\Widgets;

use App\Models\Article;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\Hospital;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Verified Doctors', Doctor::where('is_verified', true)->count())
                ->description('Active healthcare professionals')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('primary')
                ->chart([7, 3, 4, 5, 6, 8, 10]),

            Stat::make('Verified Hospitals', Hospital::where('is_verified', true)->count())
                ->description('Registered medical centers')
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('success')
                ->chart([2, 4, 3, 5, 7, 6, 8]),

            Stat::make('Medical Articles', Article::count())
                ->description('Expert health publications')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('info')
                ->chart([1, 2, 3, 4, 5, 6, 6]),

            Stat::make('Active Specialties', Department::where('is_active', true)->count())
                ->description('AI matching departments')
                ->descriptionIcon('heroicon-m-rectangle-group')
                ->color('warning'),
        ];
    }
}
