<?php

namespace Database\Seeders;

use App\Models\CachedMedicalQuestion;
use Illuminate\Database\Seeder;

class GeneralMedicalQaBulkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $symptomPhrases = [
            ['en' => 'headache', 'hi' => 'सिर दर्द', 'care_en' => 'Rest, hydrate, reduce screen strain, and monitor symptoms.', 'care_hi' => 'आराम करें, पानी पिएं, स्क्रीन का उपयोग कम करें और लक्षणों पर नजर रखें।'],
            ['en' => 'fever', 'hi' => 'बुखार', 'care_en' => 'Drink fluids, rest, and monitor body temperature every few hours.', 'care_hi' => 'पर्याप्त तरल लें, आराम करें और हर कुछ घंटों में तापमान जांचें।'],
            ['en' => 'sore throat', 'hi' => 'गले में खराश', 'care_en' => 'Use warm fluids, salt-water gargles, and avoid irritants like smoke.', 'care_hi' => 'गर्म तरल लें, नमक-पानी से गरारे करें और धुएं जैसी चीजों से बचें।'],
            ['en' => 'mild cough', 'hi' => 'हल्की खांसी', 'care_en' => 'Stay hydrated, use warm fluids, and avoid cold irritants.', 'care_hi' => 'पानी पर्याप्त लें, गर्म तरल लें और ठंडी उत्तेजक चीजों से बचें।'],
            ['en' => 'acidity', 'hi' => 'एसिडिटी', 'care_en' => 'Eat light meals, avoid spicy food, and avoid lying down right after eating.', 'care_hi' => 'हल्का भोजन करें, मसालेदार खाना कम करें और खाने के तुरंत बाद न लेटें।'],
            ['en' => 'mild stomach pain', 'hi' => 'हल्का पेट दर्द', 'care_en' => 'Take bland food, hydrate, and observe if pain improves in 24 hours.', 'care_hi' => 'सादा भोजन लें, पानी पिएं और 24 घंटे में दर्द कम होता है या नहीं देखें।'],
            ['en' => 'constipation', 'hi' => 'कब्ज', 'care_en' => 'Increase fiber, water intake, and gentle physical movement.', 'care_hi' => 'फाइबर और पानी बढ़ाएं और हल्की शारीरिक गतिविधि करें।'],
            ['en' => 'diarrhea', 'hi' => 'दस्त', 'care_en' => 'Use ORS, hydrate often, and avoid oily food.', 'care_hi' => 'ओआरएस लें, बार-बार तरल लें और तैलीय भोजन से बचें।'],
            ['en' => 'body pain', 'hi' => 'शरीर दर्द', 'care_en' => 'Rest well, hydrate, and do light stretching if tolerated.', 'care_hi' => 'अच्छा आराम करें, पानी पिएं और सहन हो तो हल्की स्ट्रेचिंग करें।'],
            ['en' => 'minor burn', 'hi' => 'हल्का जलना', 'care_en' => 'Cool under running water for 10 to 20 minutes and cover with a clean dressing.', 'care_hi' => '10 से 20 मिनट तक बहते पानी से ठंडा करें और साफ ड्रेसिंग लगाएं।'],
            ['en' => 'minor cut', 'hi' => 'हल्की कट', 'care_en' => 'Clean with running water, apply pressure for bleeding, and dress with sterile bandage.', 'care_hi' => 'बहते पानी से साफ करें, खून रोकने को दबाव दें और स्टेराइल पट्टी लगाएं।'],
            ['en' => 'dizziness', 'hi' => 'चक्कर', 'care_en' => 'Sit or lie down safely, hydrate, and rise slowly after resting.', 'care_hi' => 'सुरक्षित जगह बैठें या लेटें, पानी पिएं और आराम के बाद धीरे उठें।'],
        ];

        $audiences = [
            ['en' => 'for adults', 'hi' => 'वयस्कों के लिए'],
            ['en' => 'for senior citizens', 'hi' => 'वरिष्ठ नागरिकों के लिए'],
            ['en' => 'for teenagers', 'hi' => 'किशोरों के लिए'],
            ['en' => 'at home', 'hi' => 'घर पर'],
            ['en' => 'during travel', 'hi' => 'यात्रा के दौरान'],
            ['en' => 'at night', 'hi' => 'रात में'],
            ['en' => 'in summer', 'hi' => 'गर्मी में'],
            ['en' => 'in winter', 'hi' => 'सर्दियों में'],
        ];

        $questionTemplates = [
            ['en' => 'What should I do if I have %s %s?', 'hi' => 'अगर मुझे %s हो तो %s क्या करना चाहिए?'],
            ['en' => 'How can I manage %s %s?', 'hi' => '%s को %s कैसे संभालें?'],
            ['en' => 'Home care tips for %s %s?', 'hi' => '%s %s के लिए घरेलू देखभाल क्या है?'],
            ['en' => 'Is %s dangerous %s?', 'hi' => 'क्या %s %s खतरनाक है?'],
            ['en' => 'When should I see a doctor for %s %s?', 'hi' => '%s %s में डॉक्टर को कब दिखाना चाहिए?'],
            ['en' => 'Which precautions are best for %s %s?', 'hi' => '%s %s में कौन सी सावधानियां रखें?'],
        ];

        $records = [];
        foreach ($symptomPhrases as $symptom) {
            foreach ($audiences as $audience) {
                foreach ($questionTemplates as $template) {
                    $questionEn = sprintf($template['en'], $symptom['en'], $audience['en']);
                    $questionHi = sprintf($template['hi'], $symptom['hi'], $audience['hi']);

                    $answerEn = $symptom['care_en'] . ' Seek urgent care if symptoms are severe, worsening, or not improving.';
                    $answerHi = $symptom['care_hi'] . ' लक्षण गंभीर हों, बढ़ रहे हों, या सुधार न हो तो तुरंत डॉक्टर से मिलें।';

                    $records[] = [
                        'question_en' => $questionEn,
                        'question_hi' => $questionHi,
                        'answer_en' => $answerEn,
                        'answer_hi' => $answerHi,
                        'category' => 'General Medical',
                    ];
                }
            }
        }

        // Expand to thousands using numbered conversational variants.
        $expanded = [];
        foreach ($records as $record) {
            for ($i = 1; $i <= 5; $i++) {
                $expanded[] = [
                    'question_en' => "{$record['question_en']} (Variant {$i})",
                    'question_hi' => "{$record['question_hi']} (वैरिएंट {$i})",
                    'answer_en' => $record['answer_en'],
                    'answer_hi' => $record['answer_hi'],
                    'category' => 'General Medical',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Base records: 12*8*6 = 576, expanded x5 = 2,880 entries.
        // Insert in bulk chunks and ignore existing entries using unique question_en.
        $insertedCount = 0;
        foreach (array_chunk($expanded, 1000) as $chunk) {
            $insertedCount += CachedMedicalQuestion::insertOrIgnore($chunk);
        }

        $this->command?->info('Generated '.count($expanded).' general cached medical Q&A records.');
        $this->command?->info("Inserted {$insertedCount} new cached medical Q&A records successfully.");
    }
}
