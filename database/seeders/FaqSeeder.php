<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Faq::updateOrCreate(
            ['question_en' => 'Is Arogio completely free for patients?'],
            [
                'question_hi' => 'क्या Arogio मरीजों के लिए पूरी तरह से मुफ़्त है?',
                'answer_en' => 'Yes! Arogio is 100% free and contains zero advertisements or hidden commissions. You can contact doctors directly.',
                'answer_hi' => 'हाँ! Arogio 100% मुफ़्त है और इसमें कोई विज्ञापन या छिपे हुए कमीशन नहीं हैं। आप सीधे डॉक्टरों से संपर्क कर सकते हैं।',
                'category' => 'General',
            ]
        );

        Faq::updateOrCreate(
            ['question_en' => 'How does the AI Chatbot find the right doctor?'],
            [
                'question_hi' => 'एआई चैटबॉट सही डॉक्टर कैसे खोजता है?',
                'answer_en' => 'Our AI analyzes your symptoms using semantic vector embeddings and instantly matches them with the correct medical department and verified doctors near you.',
                'answer_hi' => 'हमारा एआई सिमेंटिक वेक्टर एम्बेडिंग का उपयोग करके आपके लक्षणों का विश्लेषण करता है और तुरंत आपके आस-पास के सही चिकित्सा विभाग और सत्यापित डॉक्टरों से मेल खाता है।',
                'category' => 'Technology',
            ]
        );

        $medicalEntries = config('medical_qa.entries', []);
        foreach ($medicalEntries as $entry) {
            Faq::updateOrCreate(
                ['question_en' => $entry['question_en']],
                [
                    'question_hi' => $entry['question_hi'] ?? $entry['question_en'],
                    'answer_en' => $entry['answer_en'] ?? '',
                    'answer_hi' => $entry['answer_hi'] ?? ($entry['answer_en'] ?? ''),
                    'category' => $entry['category'] ?? 'General Medical',
                ]
            );
        }
    }
}
