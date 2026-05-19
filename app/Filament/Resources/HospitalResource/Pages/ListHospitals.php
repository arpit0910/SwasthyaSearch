<?php

namespace App\Filament\Resources\HospitalResource\Pages;

use App\Filament\Resources\HospitalResource;
use App\Models\Hospital;
use App\Services\SpreadsheetService;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListHospitals extends ListRecords
{
    protected static string $resource = HospitalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('import')
                ->label('Import Hospitals')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('warning')
                ->modalHeading('Import Hospitals via CSV/Excel')
                ->modalDescription(new \Illuminate\Support\HtmlString('<p class="text-sm text-gray-600 dark:text-gray-300">Upload your CSV or Excel file to import hospitals. Make sure the file matches the sample template columns. <br><br><a href="'.route('sample.download', ['type' => 'hospitals']).'" target="_blank" class="text-primary-600 underline font-bold inline-flex items-center gap-1">📥 Download Sample Excel/CSV Template</a></p>'))
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

                        Hospital::firstOrCreate(
                            ['name_en' => $row['name_en']],
                            [
                                'name_en' => $row['name_en'],
                                'name_hi' => $row['name_hi'] ?? $row['name_en'],
                                'type' => $row['type'] ?? 'Hospital',
                                'address' => $row['address'] ?? '',
                                'city' => $row['city'] ?? '',
                                'emergency_phone' => $row['emergency_phone'] ?? '',
                                'latitude' => is_numeric($row['latitude'] ?? null) ? (float)$row['latitude'] : null,
                                'longitude' => is_numeric($row['longitude'] ?? null) ? (float)$row['longitude'] : null,
                                'is_verified' => true,
                            ]
                        );
                        $count++;
                    }

                    Notification::make()
                        ->title('Import Successful')
                        ->body("Successfully imported {$count} hospitals.")
                        ->success()
                        ->send();
                }),
            Action::make('sync')
                ->label('Live Data Sync')
                ->icon('heroicon-o-arrow-path')
                ->color('success')
                ->modalHeading('Live Scrape & Synchronize Hospitals')
                ->modalDescription('Select a city to live-scrape and synchronize verified hospitals. Existing records will be updated automatically with high-fidelity data.')
                ->form([
                    \Filament\Forms\Components\Select::make('city')
                        ->label('Select City')
                        ->options([
                            'Jaipur' => 'Jaipur',
                            'Delhi' => 'Delhi',
                            'Jodhpur' => 'Jodhpur',
                            'Kota' => 'Kota',
                            'Mumbai' => 'Mumbai',
                            'Pune' => 'Pune',
                            'Bangalore' => 'Bangalore',
                            'Hyderabad' => 'Hyderabad',
                            'Ahmedabad' => 'Ahmedabad',
                            'Kolkata' => 'Kolkata',
                            'Chennai' => 'Chennai',
                        ])
                        ->required()
                        ->default('Jaipur'),
                ])
                ->action(function (array $data) {
                    \App\Services\ScraperService::scrapeHospitals($data['city'], false, null, 'scrape_progress_hospitals');
                    \Filament\Notifications\Notification::make()
                        ->title('Synchronization Complete')
                        ->body("Successfully synchronized hospitals for {$data['city']}!")
                        ->success()
                        ->send();
                }),
            Actions\CreateAction::make(),
        ];
    }
}
