<?php

namespace Database\Seeders;

use App\Models\Quiz;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->quizzes() as $quiz) {
            Quiz::updateOrCreate(['slug' => $quiz['slug']], $quiz);
        }
    }

    private function quizzes(): array
    {
        return [
            [
                'title_en' => 'Stress Check Quiz',
                'title_hi' => 'तनाव जाँच क्विज़',
                'slug' => 'stress-check-quiz',
                'category' => 'Stress Relief',
                'description_en' => 'A short awareness quiz to reflect on current stress levels.',
                'description_hi' => 'वर्तमान तनाव स्तर पर सोचने के लिए छोटा जागरूकता क्विज़।',
                'intro_en' => 'This quiz is for general self-awareness only. It does not diagnose any condition.',
                'intro_hi' => 'यह क्विज़ केवल सामान्य आत्म-जागरूकता के लिए है। यह किसी स्थिति का निदान नहीं करता।',
                'questions_json' => [
                    [
                        'question_en' => 'How often have you felt overwhelmed this week?',
                        'question_hi' => 'इस सप्ताह आपने कितनी बार बहुत अधिक दबाव महसूस किया?',
                        'options' => [
                            ['label_en' => 'Rarely', 'label_hi' => 'बहुत कम', 'score' => 0],
                            ['label_en' => 'Sometimes', 'label_hi' => 'कभी-कभी', 'score' => 1],
                            ['label_en' => 'Often', 'label_hi' => 'अक्सर', 'score' => 2],
                            ['label_en' => 'Almost every day', 'label_hi' => 'लगभग हर दिन', 'score' => 3],
                        ],
                    ],
                    [
                        'question_en' => 'How well have you been sleeping recently?',
                        'question_hi' => 'हाल में आपकी नींद कैसी रही है?',
                        'options' => [
                            ['label_en' => 'Well', 'label_hi' => 'अच्छी', 'score' => 0],
                            ['label_en' => 'A little disturbed', 'label_hi' => 'थोड़ी बाधित', 'score' => 1],
                            ['label_en' => 'Frequently disturbed', 'label_hi' => 'अक्सर बाधित', 'score' => 2],
                            ['label_en' => 'Very poor', 'label_hi' => 'बहुत खराब', 'score' => 3],
                        ],
                    ],
                    [
                        'question_en' => 'How hard has it been to relax?',
                        'question_hi' => 'आराम करना कितना कठिन रहा है?',
                        'options' => [
                            ['label_en' => 'Not hard', 'label_hi' => 'कठिन नहीं', 'score' => 0],
                            ['label_en' => 'A little hard', 'label_hi' => 'थोड़ा कठिन', 'score' => 1],
                            ['label_en' => 'Quite hard', 'label_hi' => 'काफी कठिन', 'score' => 2],
                            ['label_en' => 'Very hard', 'label_hi' => 'बहुत कठिन', 'score' => 3],
                        ],
                    ],
                ],
                'result_ranges_json' => [
                    ['min' => 0, 'max' => 2, 'title_en' => 'Low stress signals', 'title_hi' => 'तनाव के कम संकेत', 'message_en' => 'Your answers suggest lower current stress. Keep protecting your rest and routines.', 'message_hi' => 'आपके उत्तर वर्तमान में कम तनाव का संकेत देते हैं। अपने आराम और दिनचर्या का ध्यान रखें।'],
                    ['min' => 3, 'max' => 5, 'title_en' => 'Moderate stress signals', 'title_hi' => 'मध्यम तनाव के संकेत', 'message_en' => 'You may benefit from short calming breaks, breathing, and better rest.', 'message_hi' => 'आपको छोटे शांत विराम, श्वास अभ्यास और बेहतर आराम से लाभ हो सकता है।'],
                    ['min' => 6, 'max' => 9, 'title_en' => 'Higher stress signals', 'title_hi' => 'अधिक तनाव के संकेत', 'message_en' => 'Your answers suggest higher stress. Consider supportive activities and talking to a qualified professional if this continues.', 'message_hi' => 'आपके उत्तर अधिक तनाव का संकेत देते हैं। सहायक गतिविधियाँ आज़माएँ और स्थिति बनी रहे तो योग्य पेशेवर से बात करें।'],
                ],
                'disclaimer_en' => 'This quiz is for awareness only and does not diagnose any mental or physical health condition.',
                'disclaimer_hi' => 'यह क्विज़ केवल जागरूकता के लिए है और किसी मानसिक या शारीरिक स्थिति का निदान नहीं करता।',
                'meta_title_en' => 'Stress Check Quiz | General Wellness Awareness',
                'meta_title_hi' => 'तनाव जाँच क्विज़ | सामान्य वेलनेस जागरूकता',
                'meta_description_en' => 'Take a short stress awareness quiz for general self-reflection.',
                'meta_description_hi' => 'सामान्य आत्म-जागरूकता के लिए छोटा तनाव क्विज़ लें।',
                'is_published' => true,
            ],
            [
                'title_en' => 'Health Myth or Fact Quiz',
                'title_hi' => 'हेल्थ मिथ या फैक्ट क्विज़',
                'slug' => 'health-myth-fact-quiz',
                'category' => 'Health Awareness',
                'description_en' => 'A simple health awareness quiz using common myths and facts.',
                'description_hi' => 'सामान्य स्वास्थ्य मिथकों और तथ्यों पर आधारित आसान जागरूकता क्विज़।',
                'intro_en' => 'This quiz is educational and should not replace medical advice.',
                'intro_hi' => 'यह क्विज़ शैक्षणिक है और चिकित्सा सलाह का विकल्प नहीं है।',
                'questions_json' => [
                    [
                        'question_en' => 'Antibiotics help against all kinds of fever.',
                        'question_hi' => 'एंटीबायोटिक हर तरह के बुखार में मदद करती है।',
                        'options' => [
                            ['label_en' => 'Myth', 'label_hi' => 'मिथ', 'score' => 1],
                            ['label_en' => 'Fact', 'label_hi' => 'तथ्य', 'score' => 0],
                        ],
                    ],
                    [
                        'question_en' => 'Chest pain with breathing trouble may need urgent care.',
                        'question_hi' => 'सांस की परेशानी के साथ छाती में दर्द को तुरंत देखभाल की जरूरत हो सकती है।',
                        'options' => [
                            ['label_en' => 'Myth', 'label_hi' => 'मिथ', 'score' => 0],
                            ['label_en' => 'Fact', 'label_hi' => 'तथ्य', 'score' => 1],
                        ],
                    ],
                ],
                'result_ranges_json' => [
                    ['min' => 0, 'max' => 0, 'title_en' => 'Keep learning', 'title_hi' => 'सीखते रहें', 'message_en' => 'A few myths may still need reviewing. Read verified health information and consult a doctor when in doubt.', 'message_hi' => 'कुछ मिथकों की अभी समीक्षा की जरूरत है। सत्यापित स्वास्थ्य जानकारी पढ़ें और संदेह में डॉक्टर से सलाह लें।'],
                    ['min' => 1, 'max' => 2, 'title_en' => 'Good awareness', 'title_hi' => 'अच्छी जागरूकता', 'message_en' => 'You answered several awareness questions correctly. Keep using verified sources.', 'message_hi' => 'आपने कई जागरूकता प्रश्न सही किए। सत्यापित स्रोतों का उपयोग जारी रखें।'],
                ],
                'disclaimer_en' => 'This quiz supports awareness only and does not diagnose any condition.',
                'disclaimer_hi' => 'यह क्विज़ केवल जागरूकता के लिए है और किसी स्थिति का निदान नहीं करता।',
                'is_published' => true,
            ],
        ];
    }
}
