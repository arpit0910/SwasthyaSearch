<?php

namespace App\Filament\Widgets;

use App\Models\Department;
use Filament\Widgets\ChartWidget;

class DoctorsByDepartmentChart extends ChartWidget
{
    protected static ?string $heading = 'Doctors per Specialty';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $departments = Department::withCount('doctors')->get();

        return [
            'datasets' => [
                [
                    'label' => 'Doctors',
                    'data' => $departments->pluck('doctors_count')->toArray(),
                    'backgroundColor' => '#14b8a6',
                    'borderColor' => '#0d9488',
                ],
            ],
            'labels' => $departments->map(fn ($d) => $d->getTranslation('name', 'en'))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
