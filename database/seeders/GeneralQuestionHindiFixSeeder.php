<?php

namespace Database\Seeders;

use App\Models\GeneralQuestion;
use Illuminate\Database\Seeder;

class GeneralQuestionHindiFixSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'question_en' => 'Hi',
                'question_hi' => 'नमस्ते',
                'answer_hi' => 'नमस्ते! मैं डॉक्टर, अस्पताल, ब्लड बैंक और स्वास्थ्य मार्गदर्शन में आपकी मदद कर सकता/सकती हूँ।',
                'detailed_answer_hi' => "शुरू करने का आसान तरीका:\n1) अपना शहर चुनें\n2) अपना प्रश्न लिखें\n3) Doctors, Hospitals या Blood Banks के विकल्प चुनें\n4) पूरी जानकारी के लिए संबंधित पेज खोलें",
            ],
            [
                'question_en' => 'How does this website work?',
                'question_hi' => 'यह वेबसाइट कैसे काम करती है?',
                'answer_hi' => 'अपना शहर चुनें, लक्षण/सेवा/स्पेशलिटी से खोजें और सत्यापित स्वास्थ्य सेवाएं देखें।',
                'detailed_answer_hi' => "यह प्रक्रिया ऐसे काम करती है:\n- शहर चुनने पर स्थानीय परिणाम मिलते हैं\n- खोज से डॉक्टर/अस्पताल/ब्लड बैंक मिलते हैं\n- आप फिल्टर लगाकर परिणाम और बेहतर कर सकते हैं",
            ],
            [
                'question_en' => 'How to see doctors?',
                'question_hi' => 'डॉक्टर कैसे देखें?',
                'answer_hi' => 'Find Doctors चुनें, फिर पूरा परिणाम देखने के लिए See all doctors खोलें।',
                'detailed_answer_hi' => "डॉक्टर खोजने के चरण:\n1) शहर चुनें\n2) लक्षण/स्पेशलिटी/नाम लिखें\n3) सुझाए गए कार्ड देखें\n4) See all doctors खोलें\n5) department, experience, city और nearby filters लगाएं",
            ],
            [
                'question_en' => 'How to call doctors?',
                'question_hi' => 'डॉक्टर को कॉल कैसे करें?',
                'answer_hi' => 'डॉक्टर कार्ड खोलें और Call बटन या फोन नंबर पर क्लिक करें।',
                'detailed_answer_hi' => "कॉल करते समय यह पुष्टि करें:\n- डॉक्टर का समय\n- फीस\n- अपॉइंटमेंट प्रक्रिया\n- सही लोकेशन",
            ],
            [
                'question_en' => 'How to find hospitals?',
                'question_hi' => 'अस्पताल कैसे खोजें?',
                'answer_hi' => 'Find Hospitals चुनें या अस्पताल/सेवा का नाम लिखकर खोजें।',
                'detailed_answer_hi' => "अस्पताल पेज पर आप ये फिल्टर लगा सकते हैं:\n- type\n- city\n- benefits\n- nearby\nफिर Call Now या Secondary Number से संपर्क करें",
            ],
            [
                'question_en' => 'How to find blood banks?',
                'question_hi' => 'ब्लड बैंक कैसे खोजें?',
                'answer_hi' => 'शहर चुनें और Find Blood Banks से संबंधित ब्लड बैंक देखें।',
                'detailed_answer_hi' => 'जाने से पहले ब्लड ग्रुप उपलब्धता और समय की पुष्टि के लिए कॉल जरूर करें।',
            ],
            [
                'question_en' => 'I have fever, what should I do?',
                'question_hi' => 'मुझे बुखार है, क्या करूँ?',
                'answer_hi' => 'आराम करें, पर्याप्त पानी पिएँ और तापमान देखते रहें। तेज या लंबे बुखार में डॉक्टर से मिलें।',
                'detailed_answer_hi' => "मूल देखभाल:\n- तरल पदार्थ लेते रहें\n- हल्का भोजन करें\n- आराम करें\nयदि सांस लेने में दिक्कत, भ्रम या बहुत तेज बुखार हो तो तुरंत डॉक्टर से संपर्क करें",
            ],
            [
                'question_en' => 'I have cough and cold, what can I do?',
                'question_hi' => 'मुझे खाँसी-जुकाम है, क्या करूँ?',
                'answer_hi' => 'आराम करें, गरम तरल लें और भाप लें।',
                'detailed_answer_hi' => "यदि तेज बुखार, सीने में दर्द या सांस फूलना हो तो तुरंत डॉक्टर से मिलें।",
            ],
            [
                'question_en' => 'I have frequent headache, what should I do?',
                'question_hi' => 'मुझे बार-बार सिरदर्द होता है, क्या करूँ?',
                'answer_hi' => 'पानी पिएँ, स्क्रीन समय कम करें और पर्याप्त नींद लें।',
                'detailed_answer_hi' => "यदि अचानक बहुत तेज सिरदर्द, उल्टी, कमजोरी या नजर में बदलाव हो तो तुरंत चिकित्सा लें।",
            ],
            [
                'question_en' => 'I have acidity, what should I do?',
                'question_hi' => 'मुझे एसिडिटी है, क्या करूँ?',
                'answer_hi' => 'मसालेदार और तैलीय भोजन से बचें, थोड़ा-थोड़ा करके खाएँ।',
                'detailed_answer_hi' => "यदि लगातार दर्द, उल्टी, काला मल या वजन घटने की समस्या हो तो डॉक्टर से जाँच कराएँ।",
            ],
            [
                'question_en' => 'I have loose motions, what should I do?',
                'question_hi' => 'मुझे दस्त हैं, क्या करूँ?',
                'answer_hi' => 'ORS लें, पानी की कमी न होने दें और हल्का भोजन करें।',
                'detailed_answer_hi' => "यदि मल में खून, पेशाब बहुत कम, तेज कमजोरी या लगातार उल्टी हो तो तुरंत डॉक्टर से मिलें।",
            ],
            [
                'question_en' => 'My blood pressure is high, what should I do now?',
                'question_hi' => 'मेरा BP हाई है, अभी क्या करूँ?',
                'answer_hi' => 'शांत बैठें, आराम के बाद BP दोबारा जाँचें और घबराएँ नहीं।',
                'detailed_answer_hi' => "यदि सीने में दर्द, सांस फूलना, बहुत तेज सिरदर्द या बोलने/चलने में दिक्कत हो तो तुरंत emergency care लें।",
            ],
            [
                'question_en' => 'My sugar is low or high, what should I do?',
                'question_hi' => 'मेरी शुगर कम या ज़्यादा है, क्या करूँ?',
                'answer_hi' => 'तुरंत शुगर जाँचें। कम शुगर में मीठा लें, बहुत ज़्यादा शुगर में डॉक्टर से जल्द सलाह लें।',
                'detailed_answer_hi' => "यदि उल्टी, बहुत ज्यादा सुस्ती, भ्रम या बेहोशी जैसे लक्षण हों तो तुरंत आपात चिकित्सा लें।",
            ],
            [
                'question_en' => 'What to do in dehydration?',
                'question_hi' => 'डिहाइड्रेशन में क्या करें?',
                'answer_hi' => 'तुरंत ORS और पानी लेना शुरू करें।',
                'detailed_answer_hi' => "मुंह सूखना, चक्कर, पेशाब कम होना जैसे लक्षण दिखें तो सतर्क रहें। तरल न ले पाने पर डॉक्टर से मिलें।",
            ],
            [
                'question_en' => 'What to do for minor burn first aid?',
                'question_hi' => 'हल्की जलन में फर्स्ट-एड क्या करें?',
                'answer_hi' => '10-20 मिनट तक बहते पानी से ठंडा करें। टूथपेस्ट/तेल न लगाएँ।',
                'detailed_answer_hi' => "साफ पट्टी करें, छाले न फोड़ें। गहरी या बड़ी जलन में तुरंत डॉक्टर से मिलें।",
            ],
            [
                'question_en' => 'When should I go to emergency immediately?',
                'question_hi' => 'मुझे कब तुरंत emergency जाना चाहिए?',
                'answer_hi' => 'सीने में दर्द, सांस की तकलीफ, स्ट्रोक के लक्षण, बहुत ज्यादा खून बहना या बेहोशी में तुरंत emergency जाएँ।',
                'detailed_answer_hi' => 'रेड-फ्लैग लक्षणों में देरी न करें। emergency services को कॉल करें और नज़दीकी emergency सुविधा में जाएँ।',
            ],
        ];

        foreach ($rows as $row) {
            GeneralQuestion::where('question_en', $row['question_en'])->update([
                'question_hi' => $row['question_hi'],
                'answer_hi' => $row['answer_hi'],
                'detailed_answer_hi' => $row['detailed_answer_hi'],
            ]);
        }
    }
}

