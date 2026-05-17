<?php

namespace App\Filament\Resources\DepartmentResource\Pages;

use App\Filament\Resources\DepartmentResource;
use App\Models\Department;
use App\Services\SpreadsheetService;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListDepartments extends ListRecords
{
    protected static string $resource = DepartmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('import')
                ->label('Import Departments')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('warning')
                ->modalHeading('Import Departments via CSV/Excel')
                ->modalDescription(new \Illuminate\Support\HtmlString('<p class="text-sm text-gray-600 dark:text-gray-300">Upload your CSV or Excel file to import departments. Make sure the file matches the sample template columns. <br><br><a href="'.route('sample.download', ['type' => 'departments']).'" target="_blank" class="text-primary-600 underline font-bold inline-flex items-center gap-1">📥 Download Sample Excel/CSV Template</a></p>'))
                ->form([
                    FileUpload::make('attachment')
                        ->label('Select File')
                        ->required()
                        ->acceptedFileTypes(['text/csv', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                        ->disk('local')
                        ->directory('imports'),
                ])
                ->action(function (array $data) {
                    $filePath = storage_path('app/' . $data['attachment']);
                    $rows = SpreadsheetService::parseSpreadsheet($filePath);

                    if (empty($rows)) {
                        Notification::make()
                            ->title('Import Failed')
                            ->body('The file is empty or could not be parsed.')
                            ->danger()
                            ->send();
                        return;
                    }

                    $count = 0;
                    foreach ($rows as $row) {
                        if (empty($row['name_en'])) {
                            continue;
                        }

                        Department::firstOrCreate(
                            ['name_en' => $row['name_en']],
                            [
                                'name_en' => $row['name_en'],
                                'name_hi' => $row['name_hi'] ?? $row['name_en'],
                                'description_en' => $row['description_en'] ?? '',
                                'description_hi' => $row['description_hi'] ?? '',
                            ]
                        );
                        $count++;
                    }

                    Notification::make()
                        ->title('Import Successful')
                        ->body("Successfully imported {$count} departments.")
                        ->success()
                        ->send();
                }),
            Actions\CreateAction::make(),
        ];
    }
}
