<?php

namespace Database\Seeders;

use App\Models\Disease;
use App\Models\CachedMedicalQuestion;
use Illuminate\Database\Seeder;

class DiseaseFaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $diseases = Disease::query()
            ->with('department:id,name_en,name_hi')
            ->get(['id', 'name_en', 'name_hi', 'department_id']);

        if ($diseases->isEmpty()) {
            $this->command?->warn('No diseases found. Please run DiseaseSeeder first.');
            return;
        }

        $questionTypes = [
            'Definition' => [
                'templates_en' => [
                    'What is %s?',
                    'Can you explain what %s is?',
                    'What does a diagnosis of %s mean?',
                ],
                'templates_hi' => [
                    '%s क्या है?',
                    'क्या आप बता सकते हैं कि %s क्या है?',
                    '%s का निदान होने का क्या मतलब है?',
                ],
                'answer_en' => '%s is a medical condition managed under the %s department. It may affect different people in different ways. For an accurate diagnosis and personalized advice, consult a qualified specialist.',
                'answer_hi' => '%s एक चिकित्सीय स्थिति है जिसका प्रबंधन %s विभाग के अंतर्गत किया जाता है। यह अलग-अलग लोगों में अलग तरीके से प्रभाव डाल सकती है। सही निदान और व्यक्तिगत सलाह के लिए योग्य विशेषज्ञ से परामर्श करें।',
            ],
            'Symptoms' => [
                'templates_en' => [
                    'What are the symptoms of %s?',
                    'What are the early signs of %s?',
                    'How can I recognize symptoms of %s?',
                ],
                'templates_hi' => [
                    '%s के लक्षण क्या हैं?',
                    '%s के शुरुआती संकेत क्या हैं?',
                    '%s के लक्षणों को कैसे पहचानें?',
                ],
                'answer_en' => 'Symptoms of %s can vary based on severity and individual health status. If warning signs are persistent, worsening, or severe, seek prompt medical evaluation through the %s department.',
                'answer_hi' => '%s के लक्षण इसकी गंभीरता और व्यक्ति की स्वास्थ्य स्थिति के अनुसार अलग हो सकते हैं। यदि लक्षण लगातार बने रहें, बढ़ें, या गंभीर हों, तो %s विभाग के माध्यम से तुरंत चिकित्सीय जांच कराएं।',
            ],
            'Treatment' => [
                'templates_en' => [
                    'How is %s treated?',
                    'What treatment options are available for %s?',
                    'Can %s be managed effectively?',
                ],
                'templates_hi' => [
                    '%s का इलाज कैसे किया जाता है?',
                    '%s के लिए कौन-कौन से उपचार विकल्प उपलब्ध हैं?',
                    'क्या %s को प्रभावी रूप से नियंत्रित किया जा सकता है?',
                ],
                'answer_en' => 'Treatment for %s is usually planned by the %s department based on disease stage, symptoms, and overall health. Care may include lifestyle measures, medicines, procedures, and regular follow-up as advised by your doctor.',
                'answer_hi' => '%s का उपचार सामान्यतः %s विभाग द्वारा रोग की अवस्था, लक्षण और समग्र स्वास्थ्य के आधार पर तय किया जाता है। देखभाल में जीवनशैली में बदलाव, दवाएं, प्रक्रियाएं और चिकित्सक द्वारा सुझाया गया नियमित फॉलो-अप शामिल हो सकता है।',
            ],
        ];

        $now = now();
        $records = [];

        foreach ($diseases as $disease) {
            $departmentEn = $disease->department?->name_en ?? 'General Medicine';
            $departmentHi = $disease->department?->name_hi ?? 'सामान्य चिकित्सा';
            $diseaseNameEn = $disease->name_en;
            $diseaseNameHi = $disease->name_hi ?: $disease->name_en;

            foreach ($questionTypes as $typeData) {
                foreach ($typeData['templates_en'] as $index => $templateEn) {
                    $templateHi = $typeData['templates_hi'][$index];

                    $records[] = [
                        'question_en' => sprintf($templateEn, $diseaseNameEn),
                        'question_hi' => sprintf($templateHi, $diseaseNameHi),
                        'answer_en' => sprintf($typeData['answer_en'], $diseaseNameEn, $departmentEn),
                        'answer_hi' => sprintf($typeData['answer_hi'], $diseaseNameHi, $departmentHi),
                        'category' => $departmentEn,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }
        }

        $generatedCount = count($records);
        $insertedCount = 0;

        foreach (array_chunk($records, 1000) as $chunk) {
            $insertedCount += CachedMedicalQuestion::insertOrIgnore($chunk);
        }

        $this->command?->info("Generated {$generatedCount} disease cached medical Q&A records.");
        $this->command?->info("Inserted {$insertedCount} new cached medical Q&A records successfully.");
    }
}
