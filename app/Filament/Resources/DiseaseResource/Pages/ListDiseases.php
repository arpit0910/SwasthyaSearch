<?php

namespace App\Filament\Resources\DiseaseResource\Pages;

use App\Filament\Resources\DiseaseResource;
use App\Models\Department;
use App\Models\Disease;
use App\Services\SpreadsheetService;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListDiseases extends ListRecords
{
    protected static string $resource = DiseaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('import')
                ->label('Import Diseases')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('warning')
                ->modalHeading('Import Diseases via CSV/Excel')
                ->modalDescription(new \Illuminate\Support\HtmlString('<p class="text-sm text-gray-600 dark:text-gray-300">Upload your CSV or Excel file to import diseases. Make sure the file matches the sample template columns. <br><br><a href="'.route('sample.download', ['type' => 'diseases']).'" target="_blank" class="text-primary-600 underline font-bold inline-flex items-center gap-1">📥 Download Sample Excel/CSV Template</a></p>'))
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

                        $dept = null;
                        if (!empty($row['department_name_en'])) {
                            $deptName = $row['department_name_en'];
                            $dept = Department::where('name_en', 'LIKE', "%{$deptName}%")->first();
                        }

                        if ($dept) {
                            Disease::firstOrCreate(
                                [
                                    'name_en' => $row['name_en'],
                                    'department_id' => $dept->id,
                                ],
                                [
                                    'name_en' => $row['name_en'],
                                    'name_hi' => $row['name_hi'] ?? $row['name_en'],
                                    'department_id' => $dept->id,
                                ]
                            );
                        }
                        $count++;
                    }

                    Notification::make()
                        ->title('Import Successful')
                        ->body("Successfully imported {$count} diseases.")
                        ->success()
                        ->send();
                }),
            Actions\CreateAction::make(),
        ];
    }
}
