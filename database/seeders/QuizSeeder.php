<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuizSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        foreach ($this->quizzes() as $quiz) {
            foreach (['questions_json', 'result_ranges_json'] as $jsonColumn) {
                if (isset($quiz[$jsonColumn]) && is_array($quiz[$jsonColumn])) {
                    $quiz[$jsonColumn] = json_encode($quiz[$jsonColumn], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                }
            }

            $quiz['created_at'] = $now;
            $quiz['updated_at'] = $now;

            DB::table('quizzes')->upsert(
                [$quiz],
                ['slug'],
                [
                    'title_en',
                    'title_hi',
                    'category',
                    'description_en',
                    'description_hi',
                    'intro_en',
                    'intro_hi',
                    'questions_json',
                    'result_ranges_json',
                    'disclaimer_en',
                    'disclaimer_hi',
                    'meta_title_en',
                    'meta_title_hi',
                    'meta_description_en',
                    'meta_description_hi',
                    'is_published',
                    'updated_at',
                ]
            );
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
                'description_en' => 'A short awareness quiz to reflect on current stress signals.',
                'description_hi' => 'वर्तमान तनाव के संकेतों को समझने के लिए छोटा जागरूकता क्विज़।',
                'intro_en' => 'This quiz is for general self-awareness only. It does not diagnose stress, anxiety, depression, or any medical condition.',
                'intro_hi' => 'यह क्विज़ केवल सामान्य आत्म-जागरूकता के लिए है। यह तनाव, चिंता, अवसाद या किसी बीमारी का निदान नहीं करता।',
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
                            ['label_en' => 'Good and refreshing', 'label_hi' => 'अच्छी और ताजगी देने वाली', 'score' => 0],
                            ['label_en' => 'A little disturbed', 'label_hi' => 'थोड़ी बाधित', 'score' => 1],
                            ['label_en' => 'Frequently disturbed', 'label_hi' => 'अक्सर बाधित', 'score' => 2],
                            ['label_en' => 'Very poor', 'label_hi' => 'बहुत खराब', 'score' => 3],
                        ],
                    ],
                    [
                        'question_en' => 'How hard has it been to relax your mind?',
                        'question_hi' => 'मन को शांत करना आपके लिए कितना कठिन रहा है?',
                        'options' => [
                            ['label_en' => 'Not hard', 'label_hi' => 'कठिन नहीं', 'score' => 0],
                            ['label_en' => 'A little hard', 'label_hi' => 'थोड़ा कठिन', 'score' => 1],
                            ['label_en' => 'Quite hard', 'label_hi' => 'काफी कठिन', 'score' => 2],
                            ['label_en' => 'Very hard', 'label_hi' => 'बहुत कठिन', 'score' => 3],
                        ],
                    ],
                    [
                        'question_en' => 'How often has stress affected your appetite, work, or daily routine?',
                        'question_hi' => 'तनाव ने आपकी भूख, काम या दिनचर्या को कितनी बार प्रभावित किया?',
                        'options' => [
                            ['label_en' => 'Rarely', 'label_hi' => 'बहुत कम', 'score' => 0],
                            ['label_en' => 'Sometimes', 'label_hi' => 'कभी-कभी', 'score' => 1],
                            ['label_en' => 'Often', 'label_hi' => 'अक्सर', 'score' => 2],
                            ['label_en' => 'Almost daily', 'label_hi' => 'लगभग रोज़', 'score' => 3],
                        ],
                    ],
                ],
                'result_ranges_json' => [
                    ['min' => 0, 'max' => 3, 'title_en' => 'Low stress signals', 'title_hi' => 'तनाव के कम संकेत', 'message_en' => 'Your answers suggest lower current stress. Keep protecting your sleep, breaks, hydration, and daily routine.', 'message_hi' => 'आपके उत्तर वर्तमान में कम तनाव का संकेत देते हैं। अपनी नींद, विराम, पानी और दिनचर्या का ध्यान रखें।'],
                    ['min' => 4, 'max' => 7, 'title_en' => 'Moderate stress signals', 'title_hi' => 'मध्यम तनाव के संकेत', 'message_en' => 'You may benefit from short calming breaks, breathing exercises, better sleep, and sharing concerns with someone trusted.', 'message_hi' => 'आपको छोटे शांत विराम, श्वास अभ्यास, बेहतर नींद और भरोसेमंद व्यक्ति से बात करने से लाभ हो सकता है।'],
                    ['min' => 8, 'max' => 12, 'title_en' => 'Higher stress signals', 'title_hi' => 'अधिक तनाव के संकेत', 'message_en' => 'Your answers suggest higher stress. If this continues, affects daily life, or feels unmanageable, consider speaking with a qualified health professional.', 'message_hi' => 'आपके उत्तर अधिक तनाव का संकेत देते हैं। यदि यह जारी रहे, दिनचर्या प्रभावित करे या संभालना कठिन लगे, तो योग्य स्वास्थ्य विशेषज्ञ से बात करें।'],
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
                'description_en' => 'A simple quiz to understand common health myths and facts.',
                'description_hi' => 'सामान्य स्वास्थ्य मिथकों और तथ्यों को समझने के लिए आसान क्विज़।',
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
                        'question_hi' => 'सांस की परेशानी के साथ छाती में दर्द में तुरंत देखभाल की जरूरत हो सकती है।',
                        'options' => [
                            ['label_en' => 'Myth', 'label_hi' => 'मिथ', 'score' => 0],
                            ['label_en' => 'Fact', 'label_hi' => 'तथ्य', 'score' => 1],
                        ],
                    ],
                    [
                        'question_en' => 'High blood pressure can exist without obvious symptoms.',
                        'question_hi' => 'हाई ब्लड प्रेशर बिना स्पष्ट लक्षणों के भी हो सकता है।',
                        'options' => [
                            ['label_en' => 'Myth', 'label_hi' => 'मिथ', 'score' => 0],
                            ['label_en' => 'Fact', 'label_hi' => 'तथ्य', 'score' => 1],
                        ],
                    ],
                    [
                        'question_en' => 'Taking leftover medicines without advice is always safe.',
                        'question_hi' => 'बची हुई दवाइयाँ बिना सलाह के लेना हमेशा सुरक्षित होता है।',
                        'options' => [
                            ['label_en' => 'Myth', 'label_hi' => 'मिथ', 'score' => 1],
                            ['label_en' => 'Fact', 'label_hi' => 'तथ्य', 'score' => 0],
                        ],
                    ],
                    [
                        'question_en' => 'Emergency symptoms should not be ignored while waiting for home remedies.',
                        'question_hi' => 'घरेलू उपायों का इंतज़ार करते हुए आपातकालीन लक्षणों को नज़रअंदाज़ नहीं करना चाहिए।',
                        'options' => [
                            ['label_en' => 'Myth', 'label_hi' => 'मिथ', 'score' => 0],
                            ['label_en' => 'Fact', 'label_hi' => 'तथ्य', 'score' => 1],
                        ],
                    ],
                ],
                'result_ranges_json' => [
                    ['min' => 0, 'max' => 2, 'title_en' => 'Needs more awareness', 'title_hi' => 'और जागरूकता की जरूरत', 'message_en' => 'Some common myths may still be confusing. Read verified health information and consult a doctor when in doubt.', 'message_hi' => 'कुछ सामान्य मिथक अभी भी भ्रमित कर सकते हैं। सत्यापित स्वास्थ्य जानकारी पढ़ें और संदेह में डॉक्टर से सलाह लें।'],
                    ['min' => 3, 'max' => 4, 'title_en' => 'Good awareness', 'title_hi' => 'अच्छी जागरूकता', 'message_en' => 'You answered several awareness questions correctly. Keep using reliable sources for health decisions.', 'message_hi' => 'आपने कई जागरूकता प्रश्न सही किए। स्वास्थ्य निर्णयों के लिए भरोसेमंद स्रोतों का उपयोग जारी रखें।'],
                    ['min' => 5, 'max' => 5, 'title_en' => 'Excellent awareness', 'title_hi' => 'बहुत अच्छी जागरूकता', 'message_en' => 'Great work. You understand several important health safety basics.', 'message_hi' => 'बहुत अच्छा। आप स्वास्थ्य सुरक्षा की कई महत्वपूर्ण बुनियादी बातें समझते हैं।'],
                ],
                'disclaimer_en' => 'This quiz supports awareness only and does not diagnose any condition.',
                'disclaimer_hi' => 'यह क्विज़ केवल जागरूकता के लिए है और किसी स्थिति का निदान नहीं करता।',
                'meta_title_en' => 'Health Myth or Fact Quiz | Medical Awareness',
                'meta_title_hi' => 'हेल्थ मिथ या फैक्ट क्विज़ | स्वास्थ्य जागरूकता',
                'meta_description_en' => 'Check your awareness about common health myths and safe medical decisions.',
                'meta_description_hi' => 'सामान्य स्वास्थ्य मिथकों और सुरक्षित चिकित्सा निर्णयों के बारे में अपनी जागरूकता जाँचें।',
                'is_published' => true,
            ],

            [
                'title_en' => 'Sleep Quality Awareness Quiz',
                'title_hi' => 'नींद की गुणवत्ता जागरूकता क्विज़',
                'slug' => 'sleep-quality-awareness-quiz',
                'category' => 'Sleep Wellness',
                'description_en' => 'A self-reflection quiz to understand sleep habits and sleep disturbance signals.',
                'description_hi' => 'नींद की आदतों और नींद में बाधा के संकेतों को समझने के लिए आत्म-जागरूकता क्विज़।',
                'intro_en' => 'This quiz helps you reflect on sleep habits. It does not diagnose insomnia or any sleep disorder.',
                'intro_hi' => 'यह क्विज़ आपकी नींद की आदतों पर सोचने में मदद करता है। यह अनिद्रा या किसी नींद विकार का निदान नहीं करता।',
                'questions_json' => [
                    [
                        'question_en' => 'How often do you wake up feeling tired?',
                        'question_hi' => 'आप कितनी बार थकान के साथ जागते हैं?',
                        'options' => [
                            ['label_en' => 'Rarely', 'label_hi' => 'बहुत कम', 'score' => 0],
                            ['label_en' => 'Sometimes', 'label_hi' => 'कभी-कभी', 'score' => 1],
                            ['label_en' => 'Often', 'label_hi' => 'अक्सर', 'score' => 2],
                            ['label_en' => 'Almost daily', 'label_hi' => 'लगभग रोज़', 'score' => 3],
                        ],
                    ],
                    [
                        'question_en' => 'How often do you use a phone or screen right before sleep?',
                        'question_hi' => 'आप सोने से ठीक पहले कितनी बार फोन या स्क्रीन का उपयोग करते हैं?',
                        'options' => [
                            ['label_en' => 'Rarely', 'label_hi' => 'बहुत कम', 'score' => 0],
                            ['label_en' => 'Sometimes', 'label_hi' => 'कभी-कभी', 'score' => 1],
                            ['label_en' => 'Often', 'label_hi' => 'अक्सर', 'score' => 2],
                            ['label_en' => 'Almost every night', 'label_hi' => 'लगभग हर रात', 'score' => 3],
                        ],
                    ],
                    [
                        'question_en' => 'How regular is your sleep and wake-up time?',
                        'question_hi' => 'आपके सोने और जागने का समय कितना नियमित है?',
                        'options' => [
                            ['label_en' => 'Very regular', 'label_hi' => 'बहुत नियमित', 'score' => 0],
                            ['label_en' => 'Mostly regular', 'label_hi' => 'अधिकतर नियमित', 'score' => 1],
                            ['label_en' => 'Often irregular', 'label_hi' => 'अक्सर अनियमित', 'score' => 2],
                            ['label_en' => 'Very irregular', 'label_hi' => 'बहुत अनियमित', 'score' => 3],
                        ],
                    ],
                    [
                        'question_en' => 'How often do worries disturb your sleep?',
                        'question_hi' => 'चिंताएँ आपकी नींद को कितनी बार प्रभावित करती हैं?',
                        'options' => [
                            ['label_en' => 'Rarely', 'label_hi' => 'बहुत कम', 'score' => 0],
                            ['label_en' => 'Sometimes', 'label_hi' => 'कभी-कभी', 'score' => 1],
                            ['label_en' => 'Often', 'label_hi' => 'अक्सर', 'score' => 2],
                            ['label_en' => 'Almost every night', 'label_hi' => 'लगभग हर रात', 'score' => 3],
                        ],
                    ],
                ],
                'result_ranges_json' => [
                    ['min' => 0, 'max' => 3, 'title_en' => 'Healthy sleep signals', 'title_hi' => 'अच्छी नींद के संकेत', 'message_en' => 'Your answers suggest relatively healthy sleep habits. Continue keeping a regular routine and calming bedtime habits.', 'message_hi' => 'आपके उत्तर अपेक्षाकृत अच्छी नींद की आदतों का संकेत देते हैं। नियमित दिनचर्या और शांत सोने की आदतें बनाए रखें।'],
                    ['min' => 4, 'max' => 7, 'title_en' => 'Sleep can improve', 'title_hi' => 'नींद बेहतर हो सकती है', 'message_en' => 'Your sleep may improve with a consistent bedtime, reduced screen use before sleep, and stress management.', 'message_hi' => 'नियमित सोने का समय, सोने से पहले स्क्रीन का कम उपयोग और तनाव प्रबंधन से आपकी नींद बेहतर हो सकती है।'],
                    ['min' => 8, 'max' => 12, 'title_en' => 'Disturbed sleep signals', 'title_hi' => 'नींद में बाधा के संकेत', 'message_en' => 'Your answers suggest frequent sleep disturbance. If this continues or affects daily life, consider speaking with a qualified professional.', 'message_hi' => 'आपके उत्तर बार-बार नींद में बाधा का संकेत देते हैं। यदि यह जारी रहे या दिनचर्या प्रभावित करे, तो योग्य विशेषज्ञ से सलाह लें।'],
                ],
                'disclaimer_en' => 'This quiz is for awareness only and does not diagnose insomnia, sleep apnea, anxiety, or any medical condition.',
                'disclaimer_hi' => 'यह क्विज़ केवल जागरूकता के लिए है और अनिद्रा, स्लीप एपनिया, चिंता या किसी बीमारी का निदान नहीं करता।',
                'meta_title_en' => 'Sleep Quality Awareness Quiz | Sleep Wellness',
                'meta_title_hi' => 'नींद की गुणवत्ता क्विज़ | स्लीप वेलनेस',
                'meta_description_en' => 'Reflect on sleep habits, bedtime routine, screen use, and sleep disturbance signals.',
                'meta_description_hi' => 'नींद की आदतों, सोने की दिनचर्या, स्क्रीन उपयोग और नींद में बाधा के संकेतों को समझें।',
                'is_published' => true,
            ],

            [
                'title_en' => 'Hydration & Heat Safety Quiz',
                'title_hi' => 'पानी और गर्मी सुरक्षा क्विज़',
                'slug' => 'hydration-heat-safety-quiz',
                'category' => 'Preventive Health',
                'description_en' => 'A practical quiz to understand hydration and heat-related safety habits.',
                'description_hi' => 'पानी और गर्मी से जुड़ी सुरक्षा आदतों को समझने के लिए व्यावहारिक क्विज़।',
                'intro_en' => 'This quiz helps you think about hydration and heat safety. It is not a medical assessment.',
                'intro_hi' => 'यह क्विज़ पानी पीने और गर्मी से सुरक्षा के बारे में सोचने में मदद करता है। यह चिकित्सा मूल्यांकन नहीं है।',
                'questions_json' => [
                    [
                        'question_en' => 'During hot weather, how often do you drink water before feeling very thirsty?',
                        'question_hi' => 'गर्मी में बहुत प्यास लगने से पहले आप कितनी बार पानी पीते हैं?',
                        'options' => [
                            ['label_en' => 'Regularly', 'label_hi' => 'नियमित रूप से', 'score' => 0],
                            ['label_en' => 'Sometimes', 'label_hi' => 'कभी-कभी', 'score' => 1],
                            ['label_en' => 'Rarely', 'label_hi' => 'बहुत कम', 'score' => 2],
                            ['label_en' => 'Only when very thirsty', 'label_hi' => 'सिर्फ बहुत प्यास लगने पर', 'score' => 3],
                        ],
                    ],
                    [
                        'question_en' => 'How often do you avoid direct afternoon heat when possible?',
                        'question_hi' => 'संभव होने पर आप दोपहर की सीधी गर्मी से कितनी बार बचते हैं?',
                        'options' => [
                            ['label_en' => 'Usually', 'label_hi' => 'आमतौर पर', 'score' => 0],
                            ['label_en' => 'Sometimes', 'label_hi' => 'कभी-कभी', 'score' => 1],
                            ['label_en' => 'Rarely', 'label_hi' => 'बहुत कम', 'score' => 2],
                            ['label_en' => 'Never', 'label_hi' => 'कभी नहीं', 'score' => 3],
                        ],
                    ],
                    [
                        'question_en' => 'Do you notice warning signs like dizziness, extreme weakness, or confusion in heat?',
                        'question_hi' => 'क्या गर्मी में चक्कर, बहुत कमजोरी या भ्रम जैसे चेतावनी संकेत दिखते हैं?',
                        'options' => [
                            ['label_en' => 'No', 'label_hi' => 'नहीं', 'score' => 0],
                            ['label_en' => 'Rarely', 'label_hi' => 'बहुत कम', 'score' => 1],
                            ['label_en' => 'Sometimes', 'label_hi' => 'कभी-कभी', 'score' => 2],
                            ['label_en' => 'Often', 'label_hi' => 'अक्सर', 'score' => 3],
                        ],
                    ],
                    [
                        'question_en' => 'When sweating a lot, do you replace fluids and take rest?',
                        'question_hi' => 'बहुत पसीना आने पर क्या आप पानी/तरल लेते हैं और आराम करते हैं?',
                        'options' => [
                            ['label_en' => 'Yes, regularly', 'label_hi' => 'हाँ, नियमित रूप से', 'score' => 0],
                            ['label_en' => 'Sometimes', 'label_hi' => 'कभी-कभी', 'score' => 1],
                            ['label_en' => 'Rarely', 'label_hi' => 'बहुत कम', 'score' => 2],
                            ['label_en' => 'No', 'label_hi' => 'नहीं', 'score' => 3],
                        ],
                    ],
                ],
                'result_ranges_json' => [
                    ['min' => 0, 'max' => 3, 'title_en' => 'Good heat safety habits', 'title_hi' => 'गर्मी से सुरक्षा की अच्छी आदतें', 'message_en' => 'Your answers suggest good hydration and heat safety habits. Keep drinking fluids and resting in hot weather.', 'message_hi' => 'आपके उत्तर पानी और गर्मी से सुरक्षा की अच्छी आदतों का संकेत देते हैं। गर्मी में तरल लेते रहें और आराम करें।'],
                    ['min' => 4, 'max' => 7, 'title_en' => 'Needs better heat care', 'title_hi' => 'गर्मी में बेहतर देखभाल की जरूरत', 'message_en' => 'You may need better hydration, shade, rest, and awareness of heat warning signs.', 'message_hi' => 'आपको बेहतर पानी, छाया, आराम और गर्मी के चेतावनी संकेतों की जागरूकता की जरूरत हो सकती है।'],
                    ['min' => 8, 'max' => 12, 'title_en' => 'Higher heat risk habits', 'title_hi' => 'गर्मी से अधिक जोखिम वाली आदतें', 'message_en' => 'Your answers suggest higher heat-related risk habits. Severe dizziness, confusion, fainting, or very high body temperature needs urgent medical attention.', 'message_hi' => 'आपके उत्तर गर्मी से जुड़े अधिक जोखिम का संकेत देते हैं। तेज चक्कर, भ्रम, बेहोशी या बहुत अधिक शरीर तापमान में तुरंत चिकित्सा सहायता लें।'],
                ],
                'disclaimer_en' => 'This quiz is for awareness only. Severe heat symptoms can be urgent and require medical care.',
                'disclaimer_hi' => 'यह क्विज़ केवल जागरूकता के लिए है। गर्मी के गंभीर लक्षण आपातकालीन हो सकते हैं और चिकित्सा देखभाल की जरूरत हो सकती है।',
                'meta_title_en' => 'Hydration and Heat Safety Quiz | Preventive Health',
                'meta_title_hi' => 'पानी और गर्मी सुरक्षा क्विज़ | रोकथाम स्वास्थ्य',
                'meta_description_en' => 'Check your hydration habits and heat safety awareness.',
                'meta_description_hi' => 'पानी पीने की आदतों और गर्मी से सुरक्षा की जागरूकता जाँचें।',
                'is_published' => true,
            ],

            [
                'title_en' => 'Diabetes Risk Awareness Quiz',
                'title_hi' => 'डायबिटीज़ जोखिम जागरूकता क्विज़',
                'slug' => 'diabetes-risk-awareness-quiz',
                'category' => 'Diabetes Awareness',
                'description_en' => 'A simple quiz to reflect on diabetes risk-related lifestyle signals.',
                'description_hi' => 'डायबिटीज़ जोखिम से जुड़े जीवनशैली संकेतों पर विचार करने के लिए आसान क्विज़।',
                'intro_en' => 'This quiz is not a diabetes test. Only proper blood tests and medical consultation can confirm diabetes.',
                'intro_hi' => 'यह क्विज़ डायबिटीज़ टेस्ट नहीं है। केवल सही ब्लड टेस्ट और डॉक्टर की सलाह से डायबिटीज़ की पुष्टि हो सकती है।',
                'questions_json' => [
                    [
                        'question_en' => 'How often do you consume sugary drinks or sweets in a week?',
                        'question_hi' => 'आप सप्ताह में कितनी बार मीठे पेय या मिठाई लेते हैं?',
                        'options' => [
                            ['label_en' => 'Rarely', 'label_hi' => 'बहुत कम', 'score' => 0],
                            ['label_en' => '1 to 2 times', 'label_hi' => '1 से 2 बार', 'score' => 1],
                            ['label_en' => '3 to 5 times', 'label_hi' => '3 से 5 बार', 'score' => 2],
                            ['label_en' => 'Almost daily', 'label_hi' => 'लगभग रोज़', 'score' => 3],
                        ],
                    ],
                    [
                        'question_en' => 'How physically active are you on most days?',
                        'question_hi' => 'अधिकतर दिनों में आप कितने शारीरिक रूप से सक्रिय रहते हैं?',
                        'options' => [
                            ['label_en' => 'Active', 'label_hi' => 'सक्रिय', 'score' => 0],
                            ['label_en' => 'Somewhat active', 'label_hi' => 'थोड़ा सक्रिय', 'score' => 1],
                            ['label_en' => 'Mostly inactive', 'label_hi' => 'अधिकतर निष्क्रिय', 'score' => 2],
                            ['label_en' => 'Very inactive', 'label_hi' => 'बहुत निष्क्रिय', 'score' => 3],
                        ],
                    ],
                    [
                        'question_en' => 'Do you have frequent thirst, frequent urination, or unexplained weight change?',
                        'question_hi' => 'क्या आपको बार-बार प्यास, बार-बार पेशाब या बिना कारण वजन में बदलाव होता है?',
                        'options' => [
                            ['label_en' => 'No', 'label_hi' => 'नहीं', 'score' => 0],
                            ['label_en' => 'Rarely', 'label_hi' => 'बहुत कम', 'score' => 1],
                            ['label_en' => 'Sometimes', 'label_hi' => 'कभी-कभी', 'score' => 2],
                            ['label_en' => 'Often', 'label_hi' => 'अक्सर', 'score' => 3],
                        ],
                    ],
                    [
                        'question_en' => 'When was your blood sugar last checked?',
                        'question_hi' => 'आपकी ब्लड शुगर आखिरी बार कब जाँची गई थी?',
                        'options' => [
                            ['label_en' => 'Recently', 'label_hi' => 'हाल ही में', 'score' => 0],
                            ['label_en' => 'Within the last year', 'label_hi' => 'पिछले एक साल में', 'score' => 1],
                            ['label_en' => 'More than a year ago', 'label_hi' => 'एक साल से अधिक पहले', 'score' => 2],
                            ['label_en' => 'Never or not sure', 'label_hi' => 'कभी नहीं या पता नहीं', 'score' => 3],
                        ],
                    ],
                ],
                'result_ranges_json' => [
                    ['min' => 0, 'max' => 3, 'title_en' => 'Lower lifestyle risk signals', 'title_hi' => 'कम जीवनशैली जोखिम संकेत', 'message_en' => 'Your answers suggest relatively lower lifestyle risk signals. Continue healthy eating, activity, and regular checkups.', 'message_hi' => 'आपके उत्तर अपेक्षाकृत कम जीवनशैली जोखिम का संकेत देते हैं। स्वस्थ भोजन, सक्रियता और नियमित जाँच जारी रखें।'],
                    ['min' => 4, 'max' => 7, 'title_en' => 'Some risk signals', 'title_hi' => 'कुछ जोखिम संकेत', 'message_en' => 'Your answers suggest some risk-related signals. Consider healthier food choices, physical activity, and routine screening.', 'message_hi' => 'आपके उत्तर कुछ जोखिम संकेत बताते हैं। स्वस्थ भोजन, शारीरिक गतिविधि और नियमित जाँच पर ध्यान दें।'],
                    ['min' => 8, 'max' => 12, 'title_en' => 'Higher awareness need', 'title_hi' => 'अधिक जागरूकता की जरूरत', 'message_en' => 'Your answers suggest you should not ignore diabetes-related risk signals. Consider consulting a doctor for proper testing and guidance.', 'message_hi' => 'आपके उत्तर बताते हैं कि डायबिटीज़ से जुड़े संकेतों को नज़रअंदाज़ नहीं करना चाहिए। सही टेस्ट और सलाह के लिए डॉक्टर से मिलें।'],
                ],
                'disclaimer_en' => 'This quiz does not diagnose diabetes. Blood sugar testing and medical consultation are required for diagnosis.',
                'disclaimer_hi' => 'यह क्विज़ डायबिटीज़ का निदान नहीं करता। निदान के लिए ब्लड शुगर टेस्ट और डॉक्टर की सलाह जरूरी है।',
                'meta_title_en' => 'Diabetes Risk Awareness Quiz | Blood Sugar Awareness',
                'meta_title_hi' => 'डायबिटीज़ जोखिम क्विज़ | ब्लड शुगर जागरूकता',
                'meta_description_en' => 'Reflect on lifestyle signals related to diabetes risk and screening awareness.',
                'meta_description_hi' => 'डायबिटीज़ जोखिम और स्क्रीनिंग जागरूकता से जुड़े जीवनशैली संकेत समझें।',
                'is_published' => true,
            ],

            [
                'title_en' => 'Blood Pressure Awareness Quiz',
                'title_hi' => 'ब्लड प्रेशर जागरूकता क्विज़',
                'slug' => 'blood-pressure-awareness-quiz',
                'category' => 'Heart Health',
                'description_en' => 'A quiz to understand blood pressure awareness, lifestyle signals, and checkup habits.',
                'description_hi' => 'ब्लड प्रेशर जागरूकता, जीवनशैली संकेत और जाँच आदतों को समझने के लिए क्विज़।',
                'intro_en' => 'This quiz is for awareness. It cannot measure or diagnose high blood pressure.',
                'intro_hi' => 'यह क्विज़ जागरूकता के लिए है। यह हाई ब्लड प्रेशर को माप या निदान नहीं कर सकता।',
                'questions_json' => [
                    [
                        'question_en' => 'When was your blood pressure last checked?',
                        'question_hi' => 'आपका ब्लड प्रेशर आखिरी बार कब जाँचा गया था?',
                        'options' => [
                            ['label_en' => 'Recently', 'label_hi' => 'हाल ही में', 'score' => 0],
                            ['label_en' => 'Within the last year', 'label_hi' => 'पिछले एक साल में', 'score' => 1],
                            ['label_en' => 'More than a year ago', 'label_hi' => 'एक साल से अधिक पहले', 'score' => 2],
                            ['label_en' => 'Never or not sure', 'label_hi' => 'कभी नहीं या पता नहीं', 'score' => 3],
                        ],
                    ],
                    [
                        'question_en' => 'How often do you eat very salty or highly processed foods?',
                        'question_hi' => 'आप बहुत नमकीन या अधिक प्रोसेस्ड भोजन कितनी बार खाते हैं?',
                        'options' => [
                            ['label_en' => 'Rarely', 'label_hi' => 'बहुत कम', 'score' => 0],
                            ['label_en' => 'Sometimes', 'label_hi' => 'कभी-कभी', 'score' => 1],
                            ['label_en' => 'Often', 'label_hi' => 'अक्सर', 'score' => 2],
                            ['label_en' => 'Almost daily', 'label_hi' => 'लगभग रोज़', 'score' => 3],
                        ],
                    ],
                    [
                        'question_en' => 'How active are you during a normal week?',
                        'question_hi' => 'सामान्य सप्ताह में आप कितने सक्रिय रहते हैं?',
                        'options' => [
                            ['label_en' => 'Active most days', 'label_hi' => 'अधिकतर दिन सक्रिय', 'score' => 0],
                            ['label_en' => 'Moderately active', 'label_hi' => 'मध्यम सक्रिय', 'score' => 1],
                            ['label_en' => 'Low activity', 'label_hi' => 'कम सक्रिय', 'score' => 2],
                            ['label_en' => 'Mostly sitting', 'label_hi' => 'अधिकतर बैठे रहते हैं', 'score' => 3],
                        ],
                    ],
                    [
                        'question_en' => 'Do you think high blood pressure always has clear symptoms?',
                        'question_hi' => 'क्या आपको लगता है कि हाई ब्लड प्रेशर में हमेशा स्पष्ट लक्षण होते हैं?',
                        'options' => [
                            ['label_en' => 'No', 'label_hi' => 'नहीं', 'score' => 0],
                            ['label_en' => 'Not sure', 'label_hi' => 'पता नहीं', 'score' => 1],
                            ['label_en' => 'Maybe', 'label_hi' => 'शायद', 'score' => 2],
                            ['label_en' => 'Yes', 'label_hi' => 'हाँ', 'score' => 3],
                        ],
                    ],
                ],
                'result_ranges_json' => [
                    ['min' => 0, 'max' => 3, 'title_en' => 'Good BP awareness', 'title_hi' => 'अच्छी बीपी जागरूकता', 'message_en' => 'Your answers suggest good blood pressure awareness. Continue regular checks and heart-healthy habits.', 'message_hi' => 'आपके उत्तर अच्छी ब्लड प्रेशर जागरूकता का संकेत देते हैं। नियमित जाँच और हृदय-स्वस्थ आदतें जारी रखें।'],
                    ['min' => 4, 'max' => 7, 'title_en' => 'Improve BP habits', 'title_hi' => 'बीपी से जुड़ी आदतें सुधारें', 'message_en' => 'You may benefit from regular BP checks, less salt, more activity, and healthier food choices.', 'message_hi' => 'आपको नियमित बीपी जाँच, कम नमक, अधिक सक्रियता और स्वस्थ भोजन से लाभ हो सकता है।'],
                    ['min' => 8, 'max' => 12, 'title_en' => 'Higher BP awareness need', 'title_hi' => 'अधिक बीपी जागरूकता की जरूरत', 'message_en' => 'Your answers suggest a need for better blood pressure awareness. Consider getting your BP checked and discussing risks with a doctor.', 'message_hi' => 'आपके उत्तर बेहतर बीपी जागरूकता की जरूरत बताते हैं। अपना बीपी जाँचें और जोखिमों पर डॉक्टर से बात करें।'],
                ],
                'disclaimer_en' => 'This quiz cannot diagnose high blood pressure. Measuring blood pressure is necessary.',
                'disclaimer_hi' => 'यह क्विज़ हाई ब्लड प्रेशर का निदान नहीं कर सकता। ब्लड प्रेशर मापना जरूरी है।',
                'meta_title_en' => 'Blood Pressure Awareness Quiz | Heart Health',
                'meta_title_hi' => 'ब्लड प्रेशर जागरूकता क्विज़ | हृदय स्वास्थ्य',
                'meta_description_en' => 'Understand blood pressure checkup habits and lifestyle awareness.',
                'meta_description_hi' => 'ब्लड प्रेशर जाँच आदतों और जीवनशैली जागरूकता को समझें।',
                'is_published' => true,
            ],

            [
                'title_en' => 'Heart Warning Signs Quiz',
                'title_hi' => 'हृदय चेतावनी संकेत क्विज़',
                'slug' => 'heart-warning-signs-quiz',
                'category' => 'Emergency Awareness',
                'description_en' => 'A safety quiz to recognize warning signs that may need urgent medical attention.',
                'description_hi' => 'ऐसे चेतावनी संकेत पहचानने के लिए सुरक्षा क्विज़ जिनमें तुरंत चिकित्सा सहायता की जरूरत हो सकती है।',
                'intro_en' => 'This quiz teaches emergency awareness. It is not a diagnosis. Severe symptoms should be treated urgently.',
                'intro_hi' => 'यह क्विज़ आपातकालीन जागरूकता सिखाता है। यह निदान नहीं है। गंभीर लक्षणों में तुरंत सहायता लें।',
                'questions_json' => [
                    [
                        'question_en' => 'Chest discomfort with shortness of breath should be taken seriously.',
                        'question_hi' => 'सांस फूलने के साथ छाती में असुविधा को गंभीरता से लेना चाहिए।',
                        'options' => [
                            ['label_en' => 'True', 'label_hi' => 'सही', 'score' => 1],
                            ['label_en' => 'False', 'label_hi' => 'गलत', 'score' => 0],
                        ],
                    ],
                    [
                        'question_en' => 'Pain spreading to the arm, jaw, neck, back, or shoulder may be a warning sign.',
                        'question_hi' => 'बांह, जबड़े, गर्दन, पीठ या कंधे तक फैलता दर्द चेतावनी संकेत हो सकता है।',
                        'options' => [
                            ['label_en' => 'True', 'label_hi' => 'सही', 'score' => 1],
                            ['label_en' => 'False', 'label_hi' => 'गलत', 'score' => 0],
                        ],
                    ],
                    [
                        'question_en' => 'Severe chest pain should always wait for home remedies first.',
                        'question_hi' => 'तेज छाती दर्द में हमेशा पहले घरेलू उपायों का इंतज़ार करना चाहिए।',
                        'options' => [
                            ['label_en' => 'True', 'label_hi' => 'सही', 'score' => 0],
                            ['label_en' => 'False', 'label_hi' => 'गलत', 'score' => 1],
                        ],
                    ],
                    [
                        'question_en' => 'Unusual sweating, nausea, dizziness, or extreme tiredness with chest discomfort can be important.',
                        'question_hi' => 'छाती में असुविधा के साथ असामान्य पसीना, उल्टी जैसा मन, चक्कर या अत्यधिक थकान महत्वपूर्ण हो सकते हैं।',
                        'options' => [
                            ['label_en' => 'True', 'label_hi' => 'सही', 'score' => 1],
                            ['label_en' => 'False', 'label_hi' => 'गलत', 'score' => 0],
                        ],
                    ],
                ],
                'result_ranges_json' => [
                    ['min' => 0, 'max' => 1, 'title_en' => 'Urgent awareness needed', 'title_hi' => 'आपातकालीन जागरूकता की जरूरत', 'message_en' => 'You may need to review important heart warning signs. Severe chest symptoms should not be ignored.', 'message_hi' => 'आपको हृदय के महत्वपूर्ण चेतावनी संकेतों को दोबारा समझने की जरूरत हो सकती है। गंभीर छाती लक्षणों को नज़रअंदाज़ न करें।'],
                    ['min' => 2, 'max' => 3, 'title_en' => 'Good emergency awareness', 'title_hi' => 'अच्छी आपातकालीन जागरूकता', 'message_en' => 'You know some important warning signs. Keep learning when urgent care may be needed.', 'message_hi' => 'आप कुछ महत्वपूर्ण चेतावनी संकेत जानते हैं। यह सीखते रहें कि कब तुरंत सहायता चाहिए।'],
                    ['min' => 4, 'max' => 4, 'title_en' => 'Strong warning-sign awareness', 'title_hi' => 'मजबूत चेतावनी संकेत जागरूकता', 'message_en' => 'Great. You recognize key symptoms that may require urgent medical attention.', 'message_hi' => 'बहुत अच्छा। आप ऐसे प्रमुख लक्षण पहचानते हैं जिनमें तुरंत चिकित्सा सहायता की जरूरत हो सकती है।'],
                ],
                'disclaimer_en' => 'This quiz is educational only. If someone has severe chest pain, breathing trouble, fainting, or serious symptoms, seek urgent medical care.',
                'disclaimer_hi' => 'यह क्विज़ केवल शैक्षणिक है। यदि किसी को तेज छाती दर्द, सांस में परेशानी, बेहोशी या गंभीर लक्षण हों, तो तुरंत चिकित्सा सहायता लें।',
                'meta_title_en' => 'Heart Warning Signs Quiz | Emergency Awareness',
                'meta_title_hi' => 'हृदय चेतावनी संकेत क्विज़ | आपातकालीन जागरूकता',
                'meta_description_en' => 'Learn common warning signs that may need urgent medical attention.',
                'meta_description_hi' => 'ऐसे सामान्य चेतावनी संकेत सीखें जिनमें तुरंत चिकित्सा सहायता की जरूरत हो सकती है।',
                'is_published' => true,
            ],

            [
                'title_en' => 'Medicine Safety Quiz',
                'title_hi' => 'दवा सुरक्षा क्विज़',
                'slug' => 'medicine-safety-quiz',
                'category' => 'Medicine Safety',
                'description_en' => 'A practical quiz about safe medicine use, antibiotics, labels, and dosage habits.',
                'description_hi' => 'दवाइयों के सुरक्षित उपयोग, एंटीबायोटिक, लेबल और खुराक आदतों पर व्यावहारिक क्विज़।',
                'intro_en' => 'This quiz is for medicine safety awareness. Always follow a qualified healthcare professional for treatment decisions.',
                'intro_hi' => 'यह क्विज़ दवा सुरक्षा जागरूकता के लिए है। इलाज के निर्णयों के लिए हमेशा योग्य स्वास्थ्य विशेषज्ञ की सलाह मानें।',
                'questions_json' => [
                    [
                        'question_en' => 'Antibiotics should be taken only when prescribed for a suitable infection.',
                        'question_hi' => 'एंटीबायोटिक केवल उचित संक्रमण में डॉक्टर की सलाह से लेनी चाहिए।',
                        'options' => [
                            ['label_en' => 'Correct', 'label_hi' => 'सही', 'score' => 1],
                            ['label_en' => 'Incorrect', 'label_hi' => 'गलत', 'score' => 0],
                        ],
                    ],
                    [
                        'question_en' => 'It is safe to share your prescription medicine with a friend who has similar symptoms.',
                        'question_hi' => 'मिलते-जुलते लक्षण वाले दोस्त को अपनी प्रिस्क्रिप्शन दवा देना सुरक्षित है।',
                        'options' => [
                            ['label_en' => 'Correct', 'label_hi' => 'सही', 'score' => 0],
                            ['label_en' => 'Incorrect', 'label_hi' => 'गलत', 'score' => 1],
                        ],
                    ],
                    [
                        'question_en' => 'Checking expiry date, dose, and instructions is part of safe medicine use.',
                        'question_hi' => 'एक्सपायरी डेट, खुराक और निर्देश जाँचना सुरक्षित दवा उपयोग का हिस्सा है।',
                        'options' => [
                            ['label_en' => 'Correct', 'label_hi' => 'सही', 'score' => 1],
                            ['label_en' => 'Incorrect', 'label_hi' => 'गलत', 'score' => 0],
                        ],
                    ],
                    [
                        'question_en' => 'If a medicine causes swelling of face, breathing trouble, or severe allergy, urgent help may be needed.',
                        'question_hi' => 'दवा से चेहरे पर सूजन, सांस में परेशानी या गंभीर एलर्जी हो तो तुरंत सहायता की जरूरत हो सकती है।',
                        'options' => [
                            ['label_en' => 'Correct', 'label_hi' => 'सही', 'score' => 1],
                            ['label_en' => 'Incorrect', 'label_hi' => 'गलत', 'score' => 0],
                        ],
                    ],
                ],
                'result_ranges_json' => [
                    ['min' => 0, 'max' => 1, 'title_en' => 'Medicine safety needs attention', 'title_hi' => 'दवा सुरक्षा पर ध्यान दें', 'message_en' => 'You may need to review safe medicine habits. Avoid self-medication and always check instructions.', 'message_hi' => 'आपको दवा सुरक्षा आदतों को दोबारा समझने की जरूरत हो सकती है। स्वयं दवा लेने से बचें और निर्देश जरूर जाँचें।'],
                    ['min' => 2, 'max' => 3, 'title_en' => 'Good medicine awareness', 'title_hi' => 'अच्छी दवा जागरूकता', 'message_en' => 'You understand several safe medicine habits. Continue checking labels and following professional advice.', 'message_hi' => 'आप दवा सुरक्षा की कई बातें समझते हैं। लेबल जाँचते रहें और विशेषज्ञ की सलाह मानें।'],
                    ['min' => 4, 'max' => 4, 'title_en' => 'Strong medicine safety awareness', 'title_hi' => 'मजबूत दवा सुरक्षा जागरूकता', 'message_en' => 'Great. You understand key medicine safety basics, including prescription safety and urgent allergy warning signs.', 'message_hi' => 'बहुत अच्छा। आप प्रिस्क्रिप्शन सुरक्षा और गंभीर एलर्जी चेतावनी संकेतों सहित दवा सुरक्षा की महत्वपूर्ण बातें समझते हैं।'],
                ],
                'disclaimer_en' => 'This quiz is educational only and does not provide treatment advice. Consult a qualified healthcare professional before starting, stopping, or changing medicines.',
                'disclaimer_hi' => 'यह क्विज़ केवल शैक्षणिक है और इलाज की सलाह नहीं देता। दवा शुरू, बंद या बदलने से पहले योग्य स्वास्थ्य विशेषज्ञ से सलाह लें।',
                'meta_title_en' => 'Medicine Safety Quiz | Safe Medication Awareness',
                'meta_title_hi' => 'दवा सुरक्षा क्विज़ | सुरक्षित दवा जागरूकता',
                'meta_description_en' => 'Learn safe medicine habits, antibiotic awareness, label checking, and urgent allergy signs.',
                'meta_description_hi' => 'सुरक्षित दवा आदतें, एंटीबायोटिक जागरूकता, लेबल जाँच और गंभीर एलर्जी संकेत सीखें।',
                'is_published' => true,
            ],

            [
                'title_en' => 'Nutrition Balance Quiz',
                'title_hi' => 'संतुलित आहार क्विज़',
                'slug' => 'nutrition-balance-quiz',
                'category' => 'Nutrition',
                'description_en' => 'A simple quiz to reflect on daily eating habits and balanced nutrition awareness.',
                'description_hi' => 'दैनिक भोजन आदतों और संतुलित आहार जागरूकता पर विचार करने के लिए आसान क्विज़।',
                'intro_en' => 'This quiz is for general nutrition awareness and does not replace diet advice from a qualified professional.',
                'intro_hi' => 'यह क्विज़ सामान्य पोषण जागरूकता के लिए है और योग्य विशेषज्ञ की आहार सलाह का विकल्प नहीं है।',
                'questions_json' => [
                    [
                        'question_en' => 'How often do you include fruits or vegetables in your meals?',
                        'question_hi' => 'आप अपने भोजन में फल या सब्जियाँ कितनी बार शामिल करते हैं?',
                        'options' => [
                            ['label_en' => 'Most meals', 'label_hi' => 'अधिकतर भोजन में', 'score' => 0],
                            ['label_en' => 'Once daily', 'label_hi' => 'दिन में एक बार', 'score' => 1],
                            ['label_en' => 'A few times a week', 'label_hi' => 'सप्ताह में कुछ बार', 'score' => 2],
                            ['label_en' => 'Rarely', 'label_hi' => 'बहुत कम', 'score' => 3],
                        ],
                    ],
                    [
                        'question_en' => 'How often do you eat packaged, fried, or highly processed foods?',
                        'question_hi' => 'आप पैकेज्ड, तला हुआ या अधिक प्रोसेस्ड भोजन कितनी बार खाते हैं?',
                        'options' => [
                            ['label_en' => 'Rarely', 'label_hi' => 'बहुत कम', 'score' => 0],
                            ['label_en' => 'Sometimes', 'label_hi' => 'कभी-कभी', 'score' => 1],
                            ['label_en' => 'Often', 'label_hi' => 'अक्सर', 'score' => 2],
                            ['label_en' => 'Almost daily', 'label_hi' => 'लगभग रोज़', 'score' => 3],
                        ],
                    ],
                    [
                        'question_en' => 'Do you usually include a protein source such as dal, beans, eggs, dairy, fish, chicken, paneer, tofu, or nuts?',
                        'question_hi' => 'क्या आप आमतौर पर दाल, बीन्स, अंडा, डेयरी, मछली, चिकन, पनीर, टोफू या मेवे जैसे प्रोटीन स्रोत लेते हैं?',
                        'options' => [
                            ['label_en' => 'Most days', 'label_hi' => 'अधिकतर दिन', 'score' => 0],
                            ['label_en' => 'Some days', 'label_hi' => 'कुछ दिन', 'score' => 1],
                            ['label_en' => 'Rarely', 'label_hi' => 'बहुत कम', 'score' => 2],
                            ['label_en' => 'Not sure', 'label_hi' => 'पता नहीं', 'score' => 3],
                        ],
                    ],
                    [
                        'question_en' => 'How often do you skip meals and then overeat later?',
                        'question_hi' => 'आप कितनी बार भोजन छोड़कर बाद में अधिक खा लेते हैं?',
                        'options' => [
                            ['label_en' => 'Rarely', 'label_hi' => 'बहुत कम', 'score' => 0],
                            ['label_en' => 'Sometimes', 'label_hi' => 'कभी-कभी', 'score' => 1],
                            ['label_en' => 'Often', 'label_hi' => 'अक्सर', 'score' => 2],
                            ['label_en' => 'Almost daily', 'label_hi' => 'लगभग रोज़', 'score' => 3],
                        ],
                    ],
                ],
                'result_ranges_json' => [
                    ['min' => 0, 'max' => 3, 'title_en' => 'Balanced habits', 'title_hi' => 'संतुलित आदतें', 'message_en' => 'Your answers suggest fairly balanced eating habits. Keep choosing varied foods, fruits, vegetables, protein, and regular meals.', 'message_hi' => 'आपके उत्तर काफी संतुलित भोजन आदतों का संकेत देते हैं। विविध भोजन, फल, सब्जियाँ, प्रोटीन और नियमित भोजन जारी रखें।'],
                    ['min' => 4, 'max' => 7, 'title_en' => 'Nutrition can improve', 'title_hi' => 'पोषण बेहतर हो सकता है', 'message_en' => 'Your eating habits may improve with more whole foods, regular meals, and fewer highly processed foods.', 'message_hi' => 'अधिक प्राकृतिक भोजन, नियमित भोजन और कम प्रोसेस्ड भोजन से आपकी आहार आदतें बेहतर हो सकती हैं।'],
                    ['min' => 8, 'max' => 12, 'title_en' => 'Needs nutrition attention', 'title_hi' => 'पोषण पर ध्यान देने की जरूरत', 'message_en' => 'Your answers suggest nutrition habits that may need attention. Consider speaking with a qualified professional, especially if you have weight, sugar, BP, kidney, or other health concerns.', 'message_hi' => 'आपके उत्तर बताते हैं कि पोषण आदतों पर ध्यान देने की जरूरत हो सकती है। वजन, शुगर, बीपी, किडनी या अन्य समस्या हो तो योग्य विशेषज्ञ से सलाह लें।'],
                ],
                'disclaimer_en' => 'This quiz is for general nutrition awareness and does not provide a personal diet plan.',
                'disclaimer_hi' => 'यह क्विज़ सामान्य पोषण जागरूकता के लिए है और व्यक्तिगत डाइट प्लान नहीं देता।',
                'meta_title_en' => 'Nutrition Balance Quiz | Healthy Eating Awareness',
                'meta_title_hi' => 'संतुलित आहार क्विज़ | स्वस्थ भोजन जागरूकता',
                'meta_description_en' => 'Reflect on fruits, vegetables, protein, processed food, and meal regularity.',
                'meta_description_hi' => 'फल, सब्जियाँ, प्रोटीन, प्रोसेस्ड भोजन और नियमित भोजन आदतों पर विचार करें।',
                'is_published' => true,
            ],

            [
                'title_en' => 'First Aid Readiness Quiz',
                'title_hi' => 'फर्स्ट एड तैयारी क्विज़',
                'slug' => 'first-aid-readiness-quiz',
                'category' => 'Emergency Awareness',
                'description_en' => 'A practical quiz to check basic first aid and emergency readiness awareness.',
                'description_hi' => 'बुनियादी फर्स्ट एड और आपातकालीन तैयारी की जागरूकता जाँचने के लिए व्यावहारिक क्विज़।',
                'intro_en' => 'This quiz is educational. In an emergency, call local emergency services and seek trained medical help.',
                'intro_hi' => 'यह क्विज़ शैक्षणिक है। आपात स्थिति में स्थानीय आपातकालीन सेवा को कॉल करें और प्रशिक्षित चिकित्सा सहायता लें।',
                'questions_json' => [
                    [
                        'question_en' => 'Do you know the emergency numbers used in your area?',
                        'question_hi' => 'क्या आपको अपने क्षेत्र में उपयोग होने वाले आपातकालीन नंबर पता हैं?',
                        'options' => [
                            ['label_en' => 'Yes', 'label_hi' => 'हाँ', 'score' => 0],
                            ['label_en' => 'Some numbers', 'label_hi' => 'कुछ नंबर', 'score' => 1],
                            ['label_en' => 'Not sure', 'label_hi' => 'पता नहीं', 'score' => 2],
                            ['label_en' => 'No', 'label_hi' => 'नहीं', 'score' => 3],
                        ],
                    ],
                    [
                        'question_en' => 'Do you keep a basic first aid kit at home or workplace?',
                        'question_hi' => 'क्या आप घर या कार्यस्थल पर बुनियादी फर्स्ट एड किट रखते हैं?',
                        'options' => [
                            ['label_en' => 'Yes and updated', 'label_hi' => 'हाँ और अपडेटेड', 'score' => 0],
                            ['label_en' => 'Yes but not checked recently', 'label_hi' => 'हाँ लेकिन हाल में जाँचा नहीं', 'score' => 1],
                            ['label_en' => 'Incomplete kit', 'label_hi' => 'अधूरी किट', 'score' => 2],
                            ['label_en' => 'No', 'label_hi' => 'नहीं', 'score' => 3],
                        ],
                    ],
                    [
                        'question_en' => 'In severe bleeding, what is generally the first helpful step while arranging urgent help?',
                        'question_hi' => 'गंभीर खून बहने पर तुरंत सहायता की व्यवस्था करते समय आमतौर पर पहला सहायक कदम क्या होता है?',
                        'options' => [
                            ['label_en' => 'Apply firm pressure with clean cloth', 'label_hi' => 'साफ कपड़े से मजबूत दबाव डालना', 'score' => 0],
                            ['label_en' => 'Ignore and wait', 'label_hi' => 'नज़रअंदाज़ करके इंतज़ार करना', 'score' => 3],
                            ['label_en' => 'Apply random powders', 'label_hi' => 'कोई भी पाउडर लगाना', 'score' => 2],
                            ['label_en' => 'Give unnecessary medicines', 'label_hi' => 'बिना जरूरत दवा देना', 'score' => 2],
                        ],
                    ],
                    [
                        'question_en' => 'For burns, what is generally safer in the first few minutes?',
                        'question_hi' => 'जलने पर शुरुआती कुछ मिनटों में आमतौर पर क्या अधिक सुरक्षित होता है?',
                        'options' => [
                            ['label_en' => 'Cool running water and medical advice if serious', 'label_hi' => 'ठंडा बहता पानी और गंभीर होने पर चिकित्सा सलाह', 'score' => 0],
                            ['label_en' => 'Apply toothpaste', 'label_hi' => 'टूथपेस्ट लगाना', 'score' => 3],
                            ['label_en' => 'Rub the burn hard', 'label_hi' => 'जले स्थान को जोर से रगड़ना', 'score' => 3],
                            ['label_en' => 'Burst blisters', 'label_hi' => 'फफोले फोड़ना', 'score' => 3],
                        ],
                    ],
                ],
                'result_ranges_json' => [
                    ['min' => 0, 'max' => 3, 'title_en' => 'Good first aid readiness', 'title_hi' => 'अच्छी फर्स्ट एड तैयारी', 'message_en' => 'Your answers suggest good basic emergency readiness. Keep emergency contacts and first aid supplies updated.', 'message_hi' => 'आपके उत्तर अच्छी बुनियादी आपातकालीन तैयारी का संकेत देते हैं। आपातकालीन संपर्क और फर्स्ट एड सामान अपडेट रखें।'],
                    ['min' => 4, 'max' => 7, 'title_en' => 'Improve readiness', 'title_hi' => 'तैयारी बेहतर करें', 'message_en' => 'You may benefit from updating emergency contacts, first aid supplies, and basic safety knowledge.', 'message_hi' => 'आपको आपातकालीन संपर्क, फर्स्ट एड सामान और बुनियादी सुरक्षा जानकारी अपडेट करने से लाभ हो सकता है।'],
                    ['min' => 8, 'max' => 12, 'title_en' => 'First aid awareness needed', 'title_hi' => 'फर्स्ट एड जागरूकता की जरूरत', 'message_en' => 'Your answers suggest first aid readiness needs attention. Learn basic first aid and keep emergency contacts accessible.', 'message_hi' => 'आपके उत्तर बताते हैं कि फर्स्ट एड तैयारी पर ध्यान देने की जरूरत है। बुनियादी फर्स्ट एड सीखें और आपातकालीन संपर्क उपलब्ध रखें।'],
                ],
                'disclaimer_en' => 'This quiz is educational only and is not a substitute for certified first aid training or emergency medical care.',
                'disclaimer_hi' => 'यह क्विज़ केवल शैक्षणिक है और प्रमाणित फर्स्ट एड प्रशिक्षण या आपातकालीन चिकित्सा देखभाल का विकल्प नहीं है।',
                'meta_title_en' => 'First Aid Readiness Quiz | Emergency Preparedness',
                'meta_title_hi' => 'फर्स्ट एड तैयारी क्विज़ | आपातकालीन तैयारी',
                'meta_description_en' => 'Check your first aid kit, emergency contact, burn, and bleeding awareness.',
                'meta_description_hi' => 'फर्स्ट एड किट, आपातकालीन संपर्क, जलने और खून बहने की जागरूकता जाँचें।',
                'is_published' => true,
            ],

            [
                'title_en' => 'Mental Wellness Support Quiz',
                'title_hi' => 'मानसिक वेलनेस सहायता क्विज़',
                'slug' => 'mental-wellness-support-quiz',
                'category' => 'Mental Wellness',
                'description_en' => 'A gentle quiz to reflect on emotional support, mood, routine, and when to seek help.',
                'description_hi' => 'भावनात्मक सहायता, मूड, दिनचर्या और कब मदद लेनी चाहिए, इस पर विचार करने के लिए सरल क्विज़।',
                'intro_en' => 'This quiz is for self-awareness only. It does not diagnose depression, anxiety, or any mental health condition.',
                'intro_hi' => 'यह क्विज़ केवल आत्म-जागरूकता के लिए है। यह अवसाद, चिंता या किसी मानसिक स्वास्थ्य स्थिति का निदान नहीं करता।',
                'questions_json' => [
                    [
                        'question_en' => 'How often have you felt low, hopeless, or emotionally exhausted recently?',
                        'question_hi' => 'हाल में आप कितनी बार उदास, निराश या भावनात्मक रूप से थका हुआ महसूस करते हैं?',
                        'options' => [
                            ['label_en' => 'Rarely', 'label_hi' => 'बहुत कम', 'score' => 0],
                            ['label_en' => 'Sometimes', 'label_hi' => 'कभी-कभी', 'score' => 1],
                            ['label_en' => 'Often', 'label_hi' => 'अक्सर', 'score' => 2],
                            ['label_en' => 'Almost daily', 'label_hi' => 'लगभग रोज़', 'score' => 3],
                        ],
                    ],
                    [
                        'question_en' => 'How often do you talk to someone trusted when you feel emotionally heavy?',
                        'question_hi' => 'भावनात्मक रूप से भारी महसूस होने पर आप भरोसेमंद व्यक्ति से कितनी बार बात करते हैं?',
                        'options' => [
                            ['label_en' => 'Usually', 'label_hi' => 'आमतौर पर', 'score' => 0],
                            ['label_en' => 'Sometimes', 'label_hi' => 'कभी-कभी', 'score' => 1],
                            ['label_en' => 'Rarely', 'label_hi' => 'बहुत कम', 'score' => 2],
                            ['label_en' => 'Never', 'label_hi' => 'कभी नहीं', 'score' => 3],
                        ],
                    ],
                    [
                        'question_en' => 'How much has your mood affected work, study, relationships, or daily routine?',
                        'question_hi' => 'आपके मूड ने काम, पढ़ाई, रिश्तों या दिनचर्या को कितना प्रभावित किया है?',
                        'options' => [
                            ['label_en' => 'Not much', 'label_hi' => 'ज्यादा नहीं', 'score' => 0],
                            ['label_en' => 'A little', 'label_hi' => 'थोड़ा', 'score' => 1],
                            ['label_en' => 'Quite a lot', 'label_hi' => 'काफी ज्यादा', 'score' => 2],
                            ['label_en' => 'Severely', 'label_hi' => 'बहुत ज्यादा', 'score' => 3],
                        ],
                    ],
                    [
                        'question_en' => 'Do you know that professional help is appropriate when emotional distress continues or feels unsafe?',
                        'question_hi' => 'क्या आप जानते हैं कि भावनात्मक परेशानी जारी रहे या असुरक्षित लगे तो पेशेवर मदद लेना उचित है?',
                        'options' => [
                            ['label_en' => 'Yes', 'label_hi' => 'हाँ', 'score' => 0],
                            ['label_en' => 'Somewhat', 'label_hi' => 'कुछ हद तक', 'score' => 1],
                            ['label_en' => 'Not sure', 'label_hi' => 'पता नहीं', 'score' => 2],
                            ['label_en' => 'No', 'label_hi' => 'नहीं', 'score' => 3],
                        ],
                    ],
                ],
                'result_ranges_json' => [
                    ['min' => 0, 'max' => 3, 'title_en' => 'Good support signals', 'title_hi' => 'अच्छे सहायता संकेत', 'message_en' => 'Your answers suggest relatively good emotional support and coping awareness. Continue healthy routines and supportive conversations.', 'message_hi' => 'आपके उत्तर अपेक्षाकृत अच्छी भावनात्मक सहायता और समझ का संकेत देते हैं। स्वस्थ दिनचर्या और सहायक बातचीत जारी रखें।'],
                    ['min' => 4, 'max' => 7, 'title_en' => 'Support may help', 'title_hi' => 'सहायता मदद कर सकती है', 'message_en' => 'You may benefit from talking to someone trusted, improving sleep and routine, and seeking professional support if distress continues.', 'message_hi' => 'आपको भरोसेमंद व्यक्ति से बात करने, नींद और दिनचर्या सुधारने और परेशानी जारी रहे तो पेशेवर सहायता लेने से लाभ हो सकता है।'],
                    ['min' => 8, 'max' => 12, 'title_en' => 'Higher support need', 'title_hi' => 'अधिक सहायता की जरूरत', 'message_en' => 'Your answers suggest higher emotional distress signals. If you feel unsafe, have thoughts of self-harm, or cannot cope, seek immediate support from emergency services or a mental health professional.', 'message_hi' => 'आपके उत्तर अधिक भावनात्मक परेशानी का संकेत देते हैं। यदि आप असुरक्षित महसूस करें, खुद को नुकसान पहुँचाने के विचार हों या संभालना कठिन लगे, तो तुरंत आपातकालीन सेवा या मानसिक स्वास्थ्य विशेषज्ञ से सहायता लें।'],
                ],
                'disclaimer_en' => 'This quiz does not diagnose any mental health condition. If there is risk of self-harm or immediate danger, seek emergency help now.',
                'disclaimer_hi' => 'यह क्विज़ किसी मानसिक स्वास्थ्य स्थिति का निदान नहीं करता। यदि खुद को नुकसान पहुँचाने या तुरंत खतरे का जोखिम हो, तो अभी आपातकालीन सहायता लें।',
                'meta_title_en' => 'Mental Wellness Support Quiz | Emotional Health Awareness',
                'meta_title_hi' => 'मानसिक वेलनेस सहायता क्विज़ | भावनात्मक स्वास्थ्य जागरूकता',
                'meta_description_en' => 'Reflect on mood, support, daily routine, and when to seek professional help.',
                'meta_description_hi' => 'मूड, सहायता, दिनचर्या और कब पेशेवर मदद लें, इस पर विचार करें।',
                'is_published' => true,
            ],

            [
                'title_en' => 'Daily Fitness Readiness Quiz',
                'title_hi' => 'दैनिक फिटनेस तैयारी क्विज़',
                'slug' => 'daily-fitness-readiness-quiz',
                'category' => 'Fitness',
                'description_en' => 'A simple quiz to reflect on movement, sitting time, strength, and safe activity habits.',
                'description_hi' => 'चलने-फिरने, बैठने के समय, ताकत और सुरक्षित गतिविधि आदतों पर विचार करने के लिए आसान क्विज़।',
                'intro_en' => 'This quiz is for general fitness awareness. People with medical conditions should seek professional advice before major exercise changes.',
                'intro_hi' => 'यह क्विज़ सामान्य फिटनेस जागरूकता के लिए है। जिन लोगों को कोई स्वास्थ्य समस्या है, वे बड़े व्यायाम बदलाव से पहले विशेषज्ञ की सलाह लें।',
                'questions_json' => [
                    [
                        'question_en' => 'How often do you walk, exercise, or do active movement in a normal week?',
                        'question_hi' => 'सामान्य सप्ताह में आप कितनी बार पैदल चलते, व्यायाम करते या सक्रिय गतिविधि करते हैं?',
                        'options' => [
                            ['label_en' => 'Most days', 'label_hi' => 'अधिकतर दिन', 'score' => 0],
                            ['label_en' => '2 to 3 days', 'label_hi' => '2 से 3 दिन', 'score' => 1],
                            ['label_en' => '1 day', 'label_hi' => '1 दिन', 'score' => 2],
                            ['label_en' => 'Rarely', 'label_hi' => 'बहुत कम', 'score' => 3],
                        ],
                    ],
                    [
                        'question_en' => 'How long do you sit continuously without a short movement break?',
                        'question_hi' => 'आप बिना छोटे मूवमेंट ब्रेक के लगातार कितनी देर बैठते हैं?',
                        'options' => [
                            ['label_en' => 'Less than 1 hour', 'label_hi' => '1 घंटे से कम', 'score' => 0],
                            ['label_en' => '1 to 2 hours', 'label_hi' => '1 से 2 घंटे', 'score' => 1],
                            ['label_en' => '2 to 4 hours', 'label_hi' => '2 से 4 घंटे', 'score' => 2],
                            ['label_en' => 'More than 4 hours', 'label_hi' => '4 घंटे से अधिक', 'score' => 3],
                        ],
                    ],
                    [
                        'question_en' => 'Do you include stretching, mobility, or strength activities?',
                        'question_hi' => 'क्या आप स्ट्रेचिंग, मोबिलिटी या ताकत बढ़ाने वाली गतिविधियाँ शामिल करते हैं?',
                        'options' => [
                            ['label_en' => 'Regularly', 'label_hi' => 'नियमित रूप से', 'score' => 0],
                            ['label_en' => 'Sometimes', 'label_hi' => 'कभी-कभी', 'score' => 1],
                            ['label_en' => 'Rarely', 'label_hi' => 'बहुत कम', 'score' => 2],
                            ['label_en' => 'Never', 'label_hi' => 'कभी नहीं', 'score' => 3],
                        ],
                    ],
                    [
                        'question_en' => 'Do you stop and seek advice if exercise causes chest pain, fainting, or severe breathing difficulty?',
                        'question_hi' => 'व्यायाम से छाती दर्द, बेहोशी या सांस की गंभीर परेशानी होने पर क्या आप रुककर सलाह लेते हैं?',
                        'options' => [
                            ['label_en' => 'Yes', 'label_hi' => 'हाँ', 'score' => 0],
                            ['label_en' => 'Probably', 'label_hi' => 'शायद', 'score' => 1],
                            ['label_en' => 'Not sure', 'label_hi' => 'पता नहीं', 'score' => 2],
                            ['label_en' => 'No', 'label_hi' => 'नहीं', 'score' => 3],
                        ],
                    ],
                ],
                'result_ranges_json' => [
                    ['min' => 0, 'max' => 3, 'title_en' => 'Active habits', 'title_hi' => 'सक्रिय आदतें', 'message_en' => 'Your answers suggest good activity awareness. Keep moving regularly and listen to your body.', 'message_hi' => 'आपके उत्तर अच्छी सक्रियता जागरूकता का संकेत देते हैं। नियमित रूप से सक्रिय रहें और शरीर के संकेतों पर ध्यान दें।'],
                    ['min' => 4, 'max' => 7, 'title_en' => 'Activity can improve', 'title_hi' => 'सक्रियता बेहतर हो सकती है', 'message_en' => 'You may benefit from more walking, short movement breaks, stretching, and gradual strength activities.', 'message_hi' => 'आपको अधिक पैदल चलने, छोटे मूवमेंट ब्रेक, स्ट्रेचिंग और धीरे-धीरे ताकत गतिविधियों से लाभ हो सकता है।'],
                    ['min' => 8, 'max' => 12, 'title_en' => 'Low activity signals', 'title_hi' => 'कम सक्रियता के संकेत', 'message_en' => 'Your answers suggest low activity or safety-awareness gaps. Start gently and seek professional advice if you have medical conditions or warning symptoms.', 'message_hi' => 'आपके उत्तर कम सक्रियता या सुरक्षा जागरूकता की कमी बताते हैं। धीरे शुरुआत करें और स्वास्थ्य समस्या या चेतावनी लक्षण हों तो विशेषज्ञ से सलाह लें।'],
                ],
                'disclaimer_en' => 'This quiz is for fitness awareness only. It does not replace medical or physiotherapy advice.',
                'disclaimer_hi' => 'यह क्विज़ केवल फिटनेस जागरूकता के लिए है। यह डॉक्टर या फिजियोथेरेपी सलाह का विकल्प नहीं है।',
                'meta_title_en' => 'Daily Fitness Readiness Quiz | Movement Awareness',
                'meta_title_hi' => 'दैनिक फिटनेस तैयारी क्विज़ | मूवमेंट जागरूकता',
                'meta_description_en' => 'Reflect on movement, sitting time, stretching, and safe exercise habits.',
                'meta_description_hi' => 'चलने-फिरने, बैठने के समय, स्ट्रेचिंग और सुरक्षित व्यायाम आदतों पर विचार करें।',
                'is_published' => true,
            ],
        ];
    }
}
