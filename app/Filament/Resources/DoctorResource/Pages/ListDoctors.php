<?php

namespace App\Filament\Resources\DoctorResource\Pages;

use App\Filament\Resources\DoctorResource;
use App\Models\Department;
use App\Models\Doctor;
use App\Services\SpreadsheetService;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListDoctors extends ListRecords
{
    protected static string $resource = DoctorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('import')
                ->label('Import Doctors')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('warning')
                ->modalHeading('Import Doctors via CSV/Excel')
                ->modalDescription(new \Illuminate\Support\HtmlString('<p class="text-sm text-gray-600 dark:text-gray-300">Upload your CSV or Excel file to import doctors. Make sure the file matches the sample template columns. <br><br><a href="'.route('sample.download', ['type' => 'doctors']).'" target="_blank" class="text-primary-600 underline font-bold inline-flex items-center gap-1">📥 Download Sample Excel/CSV Template</a></p>'))
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
                        if (empty($row['first_name']) || empty($row['registration_number'])) {
                            continue;
                        }

                        $dept = null;
                        if (!empty($row['department_name_en'])) {
                            $deptName = $row['department_name_en'];
                            $dept = Department::where('name_en', 'LIKE', "%{$deptName}%")->first();
                        }

                        Doctor::firstOrCreate(
                            ['registration_number' => $row['registration_number']],
                            [
                                'first_name' => $row['first_name'],
                                'last_name' => $row['last_name'] ?? '',
                                'department_id' => $dept ? $dept->id : null,
                                'medical_council' => $row['medical_council'] ?? '',
                                'education_degrees' => explode(';', $row['education_degrees'] ?? 'MBBS'),
                                'experience_years' => (int)($row['experience_years'] ?? 5),
                                'about_en' => $row['about_en'] ?? '',
                                'about_hi' => $row['about_hi'] ?? '',
                                'is_verified' => true,
                            ]
                        );
                        $count++;
                    }

                    Notification::make()
                        ->title('Import Successful')
                        ->body("Successfully imported {$count} doctors.")
                        ->success()
                        ->send();
                }),
            Action::make('sync')
                ->label('Live Data Sync')
                ->icon('heroicon-o-arrow-path')
                ->color('success')
                ->modalHeading('Live Scrape & Synchronize Directory')
                ->modalDescription('Select a city to live-scrape and synchronize verified healthcare records. Existing records will be updated automatically with high-fidelity data.')
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
                    \App\Services\ScraperService::scrapeDoctors($data['city'], false, null, 'scrape_progress_doctors');
                    \Filament\Notifications\Notification::make()
                        ->title('Synchronization Complete')
                        ->body("Successfully synchronized doctors for {$data['city']}!")
                        ->success()
                        ->send();
                }),
            Actions\CreateAction::make(),
        ];
    }
}
