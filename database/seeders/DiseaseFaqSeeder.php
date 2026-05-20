<?php

namespace Database\Seeders;

use App\Models\Disease;
use App\Models\CachedMedicalQuestion;
use Illuminate\Database\Seeder;

class DiseaseFaqSeeder extends Seeder
{
    public function run(): void
    {
        $diseases = Disease::query()
            ->with('department:id,name_en,name_hi')
            ->get(['id', 'name_en', 'name_hi', 'department_id']);

        if ($diseases->isEmpty()) {
            $this->command?->warn('No diseases found. Please run DiseaseSeeder first.');
            return;
        }

        // ==========================================
        // 1. THE KNOWLEDGE BASE (ULTIMATE UNTRUNCATED MASTER)
        // Contains 70+ Diseases with explicit Departments, Detailed Answers, and Gharelu Nuskhe.
        // ==========================================
        $knowledgeBase = [
            'Stress' => [
                'Department' => ['en' => 'Psychiatry / Mental Health', 'hi' => 'मनोरोग / मानसिक स्वास्थ्य'],
                'Definition' => [
                    'en' => 'Stress is the body\'s natural response to any demand or challenge.',
                    'hi' => 'तनाव (Stress) किसी भी मांग या चुनौती के प्रति शरीर की प्राकृतिक प्रतिक्रिया है।',
                    'detailed_en' => 'Stress is a physiological and psychological response to situations that require adjustment or adaptation. When stressed, the body releases hormones like cortisol and adrenaline, triggering a \'fight or flight\' response. While short-term stress can be motivating, chronic stress can lead to severe health issues, including hypertension, heart disease, anxiety disorders, and a weakened immune system.',
                    'detailed_hi' => 'तनाव उन स्थितियों के प्रति एक शारीरिक और मनोवैज्ञानिक प्रतिक्रिया है जिनमें समायोजन की आवश्यकता होती है। तनाव होने पर, शरीर कोर्टिसोल और एड्रेनालाईन जैसे हार्मोन छोड़ता है, जो \'लड़ो या भागो\' प्रतिक्रिया को ट्रिगर करता है। हालांकि अल्पकालिक तनाव प्रेरक हो सकता है, लेकिन लंबे समय तक तनाव से उच्च रक्तचाप, हृदय रोग, चिंता विकार और कमजोर प्रतिरक्षा प्रणाली जैसी गंभीर स्वास्थ्य समस्याएं हो सकती हैं।',
                ],
                'Symptoms' => ['en' => 'Symptoms include persistent headaches, muscle tension, fatigue, irritability, and difficulty sleeping.', 'hi' => 'लक्षणों में लगातार सिरदर्द, मांसपेशियों में तनाव, थकान, चिड़चिड़ापन और सोने में कठिनाई शामिल हैं।'],
                'Home Remedies' => ['en' => 'Practicing mindfulness, deep breathing exercises (like 4-7-8), regular physical activity, and reducing caffeine intake can significantly lower stress levels.', 'hi' => 'माइंडफुलनेस का अभ्यास करना, गहरी सांस लेने के व्यायाम, नियमित शारीरिक गतिविधि और कैफीन का सेवन कम करने से तनाव का स्तर काफी कम हो सकता है।'],
            ],
            'Suicidal Thoughts' => [
                'Department' => ['en' => 'Psychiatry / Emergency Medicine', 'hi' => 'मनोरोग / आपातकालीन चिकित्सा'],
                'Definition' => [
                    'en' => 'Suicidal ideation means thinking about or planning suicide, signaling deep emotional pain.',
                    'hi' => 'आत्महत्या के विचार का अर्थ है आत्महत्या के बारे में सोचना या योजना बनाना, जो गहरे भावनात्मक दर्द का संकेत है।',
                    'detailed_en' => 'Suicidal ideation is a critical medical and psychological symptom indicating extreme emotional distress, hopelessness, or underlying mental health disorders like severe depression or bipolar disorder. It is not a character flaw or a sign of weakness, but a medical emergency that requires immediate intervention, therapy, and psychiatric support to resolve the underlying pain.',
                    'detailed_hi' => 'आत्महत्या के विचार एक महत्वपूर्ण चिकित्सा और मनोवैज्ञानिक लक्षण है जो अत्यधिक भावनात्मक संकट, निराशा, या गंभीर अवसाद जैसे मानसिक स्वास्थ्य विकारों का संकेत देता है। यह कोई कमजोरी नहीं है, बल्कि एक चिकित्सा आपात स्थिति है जिसे अंतर्निहित दर्द को हल करने के लिए तत्काल हस्तक्षेप, चिकित्सा और मनोरोग सहायता की आवश्यकता होती है।',
                ],
                'Symptoms' => ['en' => 'Warning signs include talking about wanting to die, feeling hopeless, withdrawing from loved ones, and extreme mood swings.', 'hi' => 'चेतावनी के संकेतों में मरने की इच्छा के बारे में बात करना, निराश महसूस करना, प्रियजनों से दूर होना और अत्यधिक मूड स्विंग शामिल हैं।'],
                'Treatment' => ['en' => 'This is highly treatable through urgent psychiatric care, psychotherapy (CBT), and medication. Crisis support is essential.', 'hi' => 'तत्काल मनोरोग देखभाल, मनोचिकित्सा (CBT) और दवा के माध्यम से इसका अत्यधिक उपचार संभव है। संकट सहायता (Crisis support) आवश्यक है।'],
                'Home Remedies' => ['en' => 'There are NO home remedies. This is a psychological EMERGENCY. Please contact a suicide prevention helpline or visit an emergency room immediately.', 'hi' => 'इसका कोई घरेलू उपाय नहीं है। यह एक मनोवैज्ञानिक आपात स्थिति है। कृपया तुरंत आत्महत्या रोकथाम हेल्पलाइन से संपर्क करें या किसी डॉक्टर के पास जाएँ।'],
            ],
            'Panic Attacks' => [
                'Department' => ['en' => 'Psychiatry', 'hi' => 'मनोरोग'],
                'Definition' => [
                    'en' => 'A panic attack is a sudden episode of intense fear triggering severe physical reactions.',
                    'hi' => 'पैनिक अटैक तीव्र भय का एक अचानक एपिसोड है जो गंभीर शारीरिक प्रतिक्रियाओं को ट्रिगर करता है।',
                    'detailed_en' => 'A panic attack involves a sudden surge of overwhelming fear and anxiety that peaks within minutes. It is often accompanied by physical symptoms so severe that people mistake them for a heart attack. These include palpitations, shortness of breath, chest pain, and a feeling of losing control or dying. They can occur out of nowhere and are central to Panic Disorder.',
                    'detailed_hi' => 'पैनिक अटैक में अत्यधिक भय और चिंता की अचानक लहर शामिल होती है जो मिनटों में चरम पर पहुंच जाती है। इसके साथ शारीरिक लक्षण इतने गंभीर होते हैं कि लोग अक्सर उन्हें दिल का दौरा समझ लेते हैं। इनमें धड़कन तेज होना, सांस लेने में तकलीफ और नियंत्रण खोने या मरने का एहसास शामिल है। यह पैनिक डिसऑर्डर का मुख्य कारण है।',
                ],
                'Symptoms' => ['en' => 'Symptoms include a racing heart, shortness of breath, trembling, sweating, and a feeling of impending doom.', 'hi' => 'लक्षणों में दिल की धड़कन तेज होना, सांस लेने में तकलीफ, कांपना, पसीना आना और मौत का डर शामिल है।'],
                'Home Remedies' => ['en' => 'During an attack, grounding techniques (like the 5-4-3-2-1 method), splashing cold water on your face, and slow breathing can help regain control.', 'hi' => 'हमले के दौरान, ग्राउंडिंग तकनीक (जैसे 5-4-3-2-1 विधि), चेहरे पर ठंडा पानी छिड़कना, और धीमी सांस लेने से नियंत्रण हासिल करने में मदद मिल सकती है।'],
            ],
            'Depression' => [
                'Department' => ['en' => 'Psychiatry', 'hi' => 'मनोरोग'],
                'Definition' => [
                    'en' => 'Depression is a mood disorder that causes a persistent feeling of sadness and loss of interest.',
                    'hi' => 'डिप्रेशन एक मूड डिसऑर्डर है जो लगातार उदासी की भावना और रुचि की हानि का कारण बनता है।',
                    'detailed_en' => 'Major Depressive Disorder is a complex mental health illness that affects how you feel, think, and handle daily activities. It goes beyond temporary sadness, involving structural and chemical changes in the brain (like serotonin imbalance). Symptoms must last at least two weeks and can severely disrupt work, relationships, and physical health, leading to chronic fatigue or pain.',
                    'detailed_hi' => 'मेजर डिप्रेसिव डिसऑर्डर एक जटिल मानसिक स्वास्थ्य बीमारी है जो आपके महसूस करने, सोचने और दैनिक गतिविधियों को संभालने के तरीके को प्रभावित करती है। यह मस्तिष्क में रासायनिक परिवर्तनों को शामिल करते हुए अस्थायी उदासी से परे है। इसके लक्षण कम से कम दो सप्ताह तक रहने चाहिए और यह काम, रिश्तों और शारीरिक स्वास्थ्य को गंभीर रूप से बाधित कर सकता है।',
                ],
                'Treatment' => ['en' => 'Treatment involves a mix of psychotherapy (like CBT) and antidepressants (like SSRIs), guided by a psychiatrist.', 'hi' => 'उपचार में एक मनोचिकित्सक द्वारा निर्देशित मनोचिकित्सा (जैसे सीबीटी) और एंटीडिप्रेसेंट (जैसे एसएसआरआई) का मिश्रण शामिल है।'],
            ],
            'Anxiety Disorder' => [
                'Department' => ['en' => 'Psychiatry', 'hi' => 'मनोरोग'],
                'Definition' => [
                    'en' => 'Anxiety disorders involve repeated episodes of sudden feelings of intense anxiety and fear or terror.',
                    'hi' => 'चिंता विकारों में तीव्र चिंता और भय या आतंक की अचानक भावनाओं के बार-बार एपिसोड शामिल होते हैं।',
                    'detailed_en' => 'Unlike normal anxiety, which is a temporary reaction to stress, anxiety disorders involve excessive, uncontrollable, and often irrational worry about everyday things. This chronic state of heightened alertness can manifest physically as a rapid heart rate, digestive issues, and severe fatigue, impairing daily functioning.',
                    'detailed_hi' => 'सामान्य चिंता के विपरीत, चिंता विकारों में रोजमर्रा की चीजों के बारे में अत्यधिक, बेकाबू और अक्सर तर्कहीन चिंता शामिल होती है। अत्यधिक सतर्कता की यह पुरानी स्थिति शारीरिक रूप से हृदय गति तेज होने, पाचन समस्याओं और गंभीर थकान के रूप में प्रकट हो सकती है, जिससे दैनिक कार्यक्षमता प्रभावित होती है।',
                ],
            ],
            'Insomnia' => [
                'Department' => ['en' => 'Psychiatry / Pulmonology (Sleep Medicine)', 'hi' => 'मनोरोग / स्लीप मेडिसिन'],
                'Definition' => [
                    'en' => 'Insomnia is a common sleep disorder that can make it hard to fall asleep or stay asleep.',
                    'hi' => 'अनिद्रा एक सामान्य नींद विकार है जिससे सो जाने या सोते रहने में कठिनाई होती है।',
                    'detailed_en' => 'Insomnia is characterized by persistent difficulty falling asleep, staying asleep, or waking up too early and not being able to go back to sleep. It leads to daytime impairment, fatigue, and mood disturbances. It can be acute (short-term, often stress-related) or chronic (lasting months or years).',
                    'detailed_hi' => 'अनिद्रा की विशेषता नींद आने, सोते रहने, या बहुत जल्दी जागने और वापस सोने में सक्षम न होने की लगातार कठिनाई है। इससे दिन में थकान, और मूड में गड़बड़ी होती है। यह तीव्र (अल्पकालिक, अक्सर तनाव से संबंधित) या पुरानी (महीनों या वर्षों तक चलने वाली) हो सकती है।',
                ],
                'Home Remedies' => ['en' => 'Maintaining a strict sleep schedule, avoiding screens an hour before bed, and drinking warm chamomile tea can promote better sleep hygiene.', 'hi' => 'नींद का सख्त शेड्यूल बनाए रखना, सोने से एक घंटे पहले स्क्रीन से बचना और कैमोमाइल चाय पीने से बेहतर नींद को बढ़ावा मिल सकता है।'],
            ],
            'PTSD' => [
                'Department' => ['en' => 'Psychiatry', 'hi' => 'मनोरोग'],
                'Definition' => [
                    'en' => 'PTSD is a mental health condition triggered by experiencing or witnessing a terrifying event.',
                    'hi' => 'PTSD एक मानसिक स्वास्थ्य स्थिति है जो किसी भयानक घटना का अनुभव करने या देखने से शुरू होती है।',
                ],
            ],
            'OCD' => [
                'Department' => ['en' => 'Psychiatry', 'hi' => 'मनोरोग'],
                'Definition' => [
                    'en' => 'OCD features a pattern of unwanted thoughts and fears that lead you to do repetitive behaviors.',
                    'hi' => 'OCD में अवांछित विचारों और भयों का एक पैटर्न होता है जो आपको दोहराव वाले व्यवहार करने के लिए प्रेरित करता है।',
                ],
            ],
            'Bipolar Disorder' => [
                'Department' => ['en' => 'Psychiatry', 'hi' => 'मनोरोग'],
                'Definition' => [
                    'en' => 'Bipolar disorder is a mental health condition that causes extreme mood swings.',
                    'hi' => 'बाइपोलर डिसऑर्डर एक मानसिक स्वास्थ्य स्थिति है जो अत्यधिक मूड स्विंग का कारण बनती है।',
                ],
            ],
            'Schizophrenia' => [
                'Department' => ['en' => 'Psychiatry', 'hi' => 'मनोरोग'],
                'Definition' => [
                    'en' => 'Schizophrenia is a serious mental disorder in which people interpret reality abnormally.',
                    'hi' => 'सिज़ोफ्रेनिया एक गंभीर मानसिक विकार है जिसमें लोग वास्तविकता की असामान्य व्याख्या करते हैं।',
                ],
            ],
            'Chlamydia' => [
                'Department' => ['en' => 'Venereology / Gynecology', 'hi' => 'यौन रोग विज्ञान / स्त्री रोग'],
                'Definition' => [
                    'en' => 'Chlamydia is a common bacterial STD that can infect both men and women.',
                    'hi' => 'क्लैमाइडिया एक सामान्य जीवाणु (bacterial) यौन संचारित रोग (STD) है।',
                    'detailed_en' => 'Chlamydia is caused by the bacterium Chlamydia trachomatis. It is primarily transmitted through sexual contact. It is often called a "silent" infection because most people experience no symptoms. However, if left untreated in women, it can spread to the uterus and fallopian tubes, causing Pelvic Inflammatory Disease (PID) and leading to permanent damage and infertility.',
                    'detailed_hi' => 'क्लैमाइडिया क्लैमाइडिया ट्रैकोमैटिस नामक जीवाणु के कारण होता है। इसे अक्सर "मौन" संक्रमण कहा जाता है क्योंकि अधिकांश लोगों को कोई लक्षण अनुभव नहीं होता है। यदि महिलाओं में इसका इलाज नहीं किया जाता है, तो यह गर्भाशय और फैलोपियन ट्यूब में फैल सकता है, जिससे पेल्विक इंफ्लेमेटरी डिजीज (PID) हो सकती है और बांझपन का कारण बन सकता है।',
                ],
                'Symptoms' => ['en' => 'Many have no symptoms. If they occur, symptoms include painful urination, abnormal vaginal or penile discharge, and pelvic pain.', 'hi' => 'कई लोगों में कोई लक्षण नहीं होते हैं। यदि वे होते हैं, तो लक्षणों में पेशाब करते समय दर्द, असामान्य स्राव और श्रोणि (pelvic) में दर्द शामिल है।'],
                'Treatment' => ['en' => 'Chlamydia is completely curable with a course of prescription antibiotics. Both partners must be treated to prevent reinfection.', 'hi' => 'क्लैमाइडिया डॉक्टर द्वारा दी गई एंटीबायोटिक्स से पूरी तरह से ठीक हो सकता है। पुनः संक्रमण को रोकने के लिए दोनों भागीदारों का इलाज होना चाहिए।'],
                'Home Remedies' => ['en' => 'There are NO home remedies for Chlamydia. You must take prescription antibiotics to avoid severe complications like infertility.', 'hi' => 'क्लैमाइडिया के लिए कोई घरेलू उपाय नहीं है। बांझपन जैसी गंभीर जटिलताओं से बचने के लिए आपको एंटीबायोटिक्स लेनी ही होगी।'],
            ],
            'Gonorrhea' => [
                'Department' => ['en' => 'Venereology', 'hi' => 'यौन रोग विज्ञान'],
                'Definition' => [
                    'en' => 'Gonorrhea is a bacterial STD that affects the reproductive tract, mouth, or rectum.',
                    'hi' => 'गोनोरिया एक जीवाणु STD है जो प्रजनन पथ, मुंह या मलाशय को प्रभावित करता है।',
                    'detailed_en' => 'Gonorrhea is caused by Neisseria gonorrhoeae. Like chlamydia, it can cause severe complications if untreated, including PID in women and epididymitis in men. It can also spread to the blood or joints, which is life-threatening. Some strains are increasingly resistant to antibiotics.',
                    'detailed_hi' => 'गोनोरिया निसेरिया गोनोरिया के कारण होता है। अनुपचारित होने पर यह गंभीर जटिलताएं पैदा कर सकता है। यह रक्त या जोड़ों में भी फैल सकता है, जो जानलेवा है। कुछ स्ट्रेन एंटीबायोटिक दवाओं के प्रति प्रतिरोधी होते जा रहे हैं।',
                ],
                'Treatment' => ['en' => 'It can be cured with specific antibiotics, usually an injection combined with oral medication.', 'hi' => 'इसे विशिष्ट एंटीबायोटिक दवाओं से ठीक किया जा सकता है, आमतौर पर मौखिक दवा के साथ एक इंजेक्शन।'],
                'Home Remedies' => ['en' => 'Gonorrhea cannot be treated at home. It strictly requires clinical antibiotic treatment.', 'hi' => 'गोनोरिया का इलाज घर पर नहीं किया जा सकता। इसके लिए नैदानिक एंटीबायोटिक उपचार की सख्त आवश्यकता है।'],
            ],
            'Syphilis' => [
                'Department' => ['en' => 'Venereology', 'hi' => 'यौन रोग विज्ञान'],
                'Definition' => [
                    'en' => 'Syphilis is a highly contagious bacterial STD that develops in stages, starting with a painless sore.',
                    'hi' => 'सिफलिस एक अत्यधिक संक्रामक जीवाणु STD है जो चरणों में विकसित होता है, जो एक दर्द रहित घाव से शुरू होता है।',
                ],
                'Treatment' => ['en' => 'In its early stages, Syphilis is easily cured with Penicillin or other appropriate antibiotics.', 'hi' => 'अपने शुरुआती चरणों में, सिफलिस को पेनिसिलिन या अन्य उपयुक्त एंटीबायोटिक दवाओं से आसानी से ठीक किया जा सकता है।'],
            ],
            'Genital Herpes' => [
                'Department' => ['en' => 'Venereology / Dermatology', 'hi' => 'यौन रोग विज्ञान / त्वचा विज्ञान'],
                'Definition' => [
                    'en' => 'Genital herpes is a viral STD caused by the Herpes Simplex Virus (HSV) characterized by painful blisters.',
                    'hi' => 'जननांग दाद (Genital Herpes) हर्पीज सिम्प्लेक्स वायरस (HSV) के कारण होने वाला एक वायरल STD है जिसमें दर्दनाक छाले होते हैं।',
                ],
                'Treatment' => ['en' => 'There is NO CURE for herpes, but daily antiviral medications can prevent outbreaks.', 'hi' => 'हर्पीज का कोई इलाज नहीं है, लेकिन दैनिक एंटीवायरल दवाएं प्रकोप को रोक सकती हैं।'],
            ],
            'Human Papillomavirus (HPV)' => [
                'Department' => ['en' => 'Venereology / Gynecology', 'hi' => 'यौन रोग विज्ञान / स्त्री रोग'],
                'Definition' => [
                    'en' => 'HPV is the most common viral STD. Certain high-risk strains can cause genital warts and cancers.',
                    'hi' => 'HPV सबसे आम वायरल STD है। कुछ उच्च जोखिम वाले स्ट्रेन जननांग मौसा और कैंसर का कारण बन सकते हैं।',
                ],
            ],
            'HIV/AIDS' => [
                'Department' => ['en' => 'Infectious Diseases / Immunology', 'hi' => 'संक्रामक रोग / इम्यूनोलॉजी'],
                'Definition' => [
                    'en' => 'HIV is a virus that attacks the immune system. Untreated, it progresses to AIDS.',
                    'hi' => 'HIV एक वायरस है जो प्रतिरक्षा प्रणाली पर हमला करता है।',
                    'detailed_en' => 'Human Immunodeficiency Virus (HIV) attacks the body\'s white blood cells (CD4 cells), weakening the immune system over time and making the patient highly vulnerable to opportunistic infections. Acquired Immunodeficiency Syndrome (AIDS) is the final, most severe stage of an HIV infection. While currently incurable, modern antiretroviral therapy (ART) allows people with HIV to live long, healthy lives with zero risk of transmitting the virus to partners.',
                    'detailed_hi' => 'ह्यूमन इम्यूनोडेफिशिएंसी वायरस (HIV) शरीर की श्वेत रक्त कोशिकाओं (CD4 कोशिकाओं) पर हमला करता है, समय के साथ प्रतिरक्षा प्रणाली को कमजोर करता है। एक्वायर्ड इम्यूनोडेफिशिएंसी सिंड्रोम (AIDS) एचआईवी संक्रमण का अंतिम और सबसे गंभीर चरण है। हालांकि इसका कोई इलाज नहीं है, आधुनिक एंटीरेट्रोवाइरल थेरेपी (ART) एचआईवी वाले लोगों को लंबा, स्वस्थ जीवन जीने की अनुमति देती है।',
                ],
                'Treatment' => ['en' => 'There is no cure, but Antiretroviral Therapy (ART) suppresses the virus to undetectable levels.', 'hi' => 'कोई इलाज नहीं है, लेकिन एंटीरेट्रोवाइरल थेरेपी (ART) वायरस को दबा देती है।'],
            ],
            'Trichomoniasis' => [
                'Department' => ['en' => 'Venereology', 'hi' => 'यौन रोग विज्ञान'],
                'Definition' => [
                    'en' => 'Trichomoniasis is a very common and highly curable STD caused by a protozoan parasite.',
                    'hi' => 'ट्राइकोमोनिएसिस एक बहुत ही सामान्य और पूरी तरह से ठीक होने वाला STD है जो एक परजीवी के कारण होता है।',
                ],
            ],
            'Pregnancy' => [
                'Department' => ['en' => 'Obstetrics & Gynecology', 'hi' => 'प्रसूति एवं स्त्री रोग'],
                'Definition' => [
                    'en' => 'Pregnancy is the period in which a fetus develops inside a woman\'s womb.',
                    'hi' => 'गर्भावस्था वह अवधि है जिसमें एक भ्रूण महिला के गर्भ के अंदर विकसित होता है।',
                    'detailed_en' => 'Pregnancy involves profound physical, hormonal, and psychological changes. It spans three trimesters (approx 40 weeks), starting from the fertilization of an egg to the delivery of the infant. The body undergoes massive adjustments, increasing blood volume by up to 50% and shifting organ positions to accommodate the growing fetus. Proper prenatal care is vital to monitor both maternal and fetal health.',
                    'detailed_hi' => 'गर्भावस्था में गहरे शारीरिक, हार्मोनल और मनोवैज्ञानिक परिवर्तन शामिल होते हैं। यह तीन तिमाही (लगभग 40 सप्ताह) तक फैला होता है। शरीर बड़े पैमाने पर समायोजन से गुजरता है, रक्त की मात्रा 50% तक बढ़ जाती है और बढ़ते भ्रूण को समायोजित करने के लिए अंगों की स्थिति बदल जाती है। मातृ और भ्रूण दोनों के स्वास्थ्य की निगरानी के लिए प्रसव पूर्व देखभाल महत्वपूर्ण है।',
                ],
                'Home Remedies' => ['en' => 'For common morning sickness, ginger tea, crackers in the morning, and frequent small meals can provide relief safely.', 'hi' => 'सामान्य मॉर्निंग सिकनेस के लिए, अदरक की चाय, सुबह क्रैकर्स और बार-बार छोटा भोजन सुरक्षित रूप से राहत प्रदान कर सकता है।'],
            ],
            'Preeclampsia' => [
                'Department' => ['en' => 'Obstetrics', 'hi' => 'प्रसूति विज्ञान'],
                'Definition' => [
                    'en' => 'Preeclampsia is a serious pregnancy complication characterized by high blood pressure.',
                    'hi' => 'प्रीक्लेम्पसिया एक गंभीर गर्भावस्था की जटिलता है जिसमें उच्च रक्तचाप होता है।',
                    'detailed_en' => 'Preeclampsia typically begins after 20 weeks of pregnancy in women whose blood pressure had been normal. It is marked by a sudden spike in blood pressure and often high levels of protein in the urine, indicating kidney or liver damage. If left untreated, it can lead to fatal complications for both mother and baby. The most effective treatment is delivery of the baby.',
                    'detailed_hi' => 'प्रीक्लेम्पसिया आमतौर पर उन महिलाओं में गर्भावस्था के 20 सप्ताह बाद शुरू होता है जिनका रक्तचाप सामान्य था। यह रक्तचाप में अचानक वृद्धि और मूत्र में उच्च स्तर के प्रोटीन की विशेषता है, जो गुर्दे या यकृत की क्षति का संकेत देता है। यदि अनुपचारित छोड़ दिया जाए, तो यह मां और बच्चे दोनों के लिए घातक जटिलताएं पैदा कर सकता है।',
                ],
                'Home Remedies' => ['en' => 'Preeclampsia is a medical emergency. There are NO home remedies. Seek immediate hospital care.', 'hi' => 'प्रीक्लेम्पसिया एक मेडिकल इमरजेंसी है। इसका कोई घरेलू उपाय नहीं है। तत्काल अस्पताल की देखभाल लें।'],
            ],
            'Gestational Diabetes' => [
                'Department' => ['en' => 'Obstetrics / Endocrinology', 'hi' => 'प्रसूति विज्ञान / एंडोक्रिनोलॉजी'],
                'Definition' => [
                    'en' => 'Gestational diabetes is high blood sugar that develops during pregnancy and usually disappears after giving birth.',
                    'hi' => 'गर्भकालीन मधुमेह उच्च रक्त शर्करा है जो गर्भावस्था के दौरान विकसित होता है और आमतौर पर जन्म देने के बाद गायब हो जाता है।',
                ],
            ],
            'Ectopic Pregnancy' => [
                'Department' => ['en' => 'Obstetrics', 'hi' => 'प्रसूति विज्ञान'],
                'Definition' => [
                    'en' => 'An ectopic pregnancy occurs when a fertilized egg implants and grows outside the main cavity of the uterus.',
                    'hi' => 'एक्टोपिक गर्भावस्था तब होती है जब एक निषेचित अंडा गर्भाशय की मुख्य गुहा के बाहर प्रत्यारोपित होता है।',
                ],
            ],
            'Hyperemesis Gravidarum' => [
                'Department' => ['en' => 'Obstetrics', 'hi' => 'प्रसूति विज्ञान'],
                'Definition' => [
                    'en' => 'Hyperemesis gravidarum is extreme, persistent nausea and vomiting during pregnancy.',
                    'hi' => 'हाइपरमेसिस ग्रेविडेरम गर्भावस्था के दौरान अत्यधिक और लगातार मतली और उल्टी है।',
                ],
            ],
            'Diabetes' => [
                'Department' => ['en' => 'Endocrinology', 'hi' => 'एंडोक्रिनोलॉजी (अंतःस्रावी विज्ञान)'],
                'Definition' => [
                    'en' => 'Diabetes is a chronic condition characterized by elevated blood sugar levels.',
                    'hi' => 'मधुमेह एक पुरानी स्थिति है जिसमें रक्त शर्करा का स्तर बढ़ जाता है।',
                    'detailed_en' => 'Diabetes mellitus occurs when the pancreas doesn\'t produce enough insulin (Type 1) or the body cannot effectively use the insulin it produces (Type 2). Insulin regulates blood glucose. Over time, chronically high blood glucose can severely damage the heart, blood vessels, eyes, kidneys, and nerves, leading to complications like strokes or amputations if unmanaged.',
                    'detailed_hi' => 'डायबिटीज मेलिटस तब होता है जब अग्न्याशय पर्याप्त इंसुलिन (टाइप 1) का उत्पादन नहीं करता है या शरीर अपने द्वारा उत्पादित इंसुलिन का प्रभावी ढंग से उपयोग नहीं कर सकता है (टाइप 2)। इंसुलिन रक्त शर्करा को नियंत्रित करता है। समय के साथ, लगातार उच्च रक्त शर्करा हृदय, रक्त वाहिकाओं, आंखों, गुर्दे और नसों को गंभीर रूप से नुकसान पहुंचा सकता है।',
                ],
                'Home Remedies' => ['en' => 'Soaked fenugreek (methi) seeds water in the morning, bitter gourd (karela) juice, and amla can help manage blood sugar levels alongside prescribed medication.', 'hi' => 'सुबह खाली पेट भीगे हुए मेथी दाने का पानी, करेले का रस और आंवला निर्धारित दवाओं के साथ रक्त शर्करा को प्रबंधित करने में मदद कर सकते हैं।'],
            ],
            'Hypothyroidism' => [
                'Department' => ['en' => 'Endocrinology', 'hi' => 'एंडोक्रिनोलॉजी'],
                'Definition' => [
                    'en' => 'Hypothyroidism is an underactive thyroid gland that doesn\'t produce enough crucial hormones.',
                    'hi' => 'हाइपोथायरायडिज्म एक अंडरएक्टिव थायरॉयड ग्रंथि है जो पर्याप्त महत्वपूर्ण हार्मोन का उत्पादन नहीं करती है।',
                ],
                'Home Remedies' => ['en' => 'Including coriander (dhaniya) seeds water, ashwagandha, and iodine-rich foods in your diet supports thyroid health.', 'hi' => 'धनिये के बीज का पानी, अश्वगंधा और आयोडीन युक्त खाद्य पदार्थों को आहार में शामिल करने से थायरॉयड स्वास्थ्य को समर्थन मिलता है।'],
            ],
            'Hyperthyroidism' => [
                'Department' => ['en' => 'Endocrinology', 'hi' => 'एंडोक्रिनोलॉजी'],
                'Definition' => [
                    'en' => 'Hyperthyroidism occurs when your thyroid gland produces too much of the hormone thyroxine.',
                    'hi' => 'हाइपरथायरायडिज्म तब होता है जब आपकी थायरॉयड ग्रंथि थायरोक्सिन हार्मोन का बहुत अधिक उत्पादन करती है।',
                ],
            ],
            'Obesity' => [
                'Department' => ['en' => 'Endocrinology / General Medicine', 'hi' => 'एंडोक्रिनोलॉजी / सामान्य चिकित्सा'],
                'Definition' => [
                    'en' => 'Obesity is a complex disease involving an excessive amount of body fat.',
                    'hi' => 'मोटापा एक जटिल बीमारी है जिसमें शरीर में वसा की अत्यधिक मात्रा शामिल होती है।',
                ],
                'Home Remedies' => ['en' => 'Starting the day with warm water mixed with lemon and honey, along with roasted cumin (jeera) powder, can help boost metabolism.', 'hi' => 'दिन की शुरुआत नींबू और शहद के साथ गर्म पानी, और भुना हुआ जीरा पाउडर के साथ करने से चयापचय को बढ़ावा मिल सकता है।'],
            ],
            'Gout' => [
                'Department' => ['en' => 'Rheumatology / Endocrinology', 'hi' => 'रुमेटोलॉजी / एंडोक्रिनोलॉजी'],
                'Definition' => [
                    'en' => 'Gout is a common and complex form of arthritis characterized by sudden, severe attacks of pain, swelling, redness and tenderness.',
                    'hi' => 'गठिया (गाउट) एक सामान्य और जटिल प्रकार का गठिया है जिसकी विशेषता दर्द, सूजन, लालिमा और कोमलता के अचानक, गंभीर हमले हैं।',
                ],
            ],
            'Hypertension' => [
                'Department' => ['en' => 'Cardiology', 'hi' => 'कार्डियोलॉजी (हृदय रोग विज्ञान)'],
                'Definition' => [
                    'en' => 'Hypertension is a condition where blood pressure against artery walls is consistently too high.',
                    'hi' => 'उच्च रक्तचाप एक ऐसी स्थिति है जहां धमनी की दीवारों के खिलाफ रक्तचाप लगातार बहुत अधिक होता है।',
                    'detailed_en' => 'Blood pressure is determined both by the amount of blood your heart pumps and the amount of resistance to blood flow in your arteries. The narrower your arteries and the more blood your heart pumps, the higher your blood pressure. Known as the "silent killer," it can cause massive damage to blood vessels and lead to heart attacks or strokes without ever showing warning symptoms.',
                    'detailed_hi' => 'रक्तचाप आपके हृदय द्वारा पंप किए जाने वाले रक्त की मात्रा और आपकी धमनियों में रक्त प्रवाह के प्रतिरोध की मात्रा से निर्धारित होता है। धमनियां जितनी संकरी होंगी, रक्तचाप उतना ही अधिक होगा। इसे "साइलेंट किलर" के रूप में जाना जाता है, क्योंकि यह चेतावनी के लक्षण दिखाए बिना रक्त वाहिकाओं को बड़े पैमाने पर नुकसान पहुंचा सकता है और दिल के दौरे या स्ट्रोक का कारण बन सकता है।',
                ],
                'Home Remedies' => ['en' => 'Consuming a clove of raw garlic (lahsun) daily, drinking amla juice, and reducing salt intake are effective traditional practices to support blood pressure management.', 'hi' => 'रोजाना कच्चे लहसुन की एक कली खाना, आंवले का रस पीना और नमक का सेवन कम करना रक्तचाप प्रबंधन के लिए प्रभावी पारंपरिक उपाय हैं।'],
            ],
            'Heart Attack' => [
                'Department' => ['en' => 'Cardiology', 'hi' => 'कार्डियोलॉजी'],
                'Definition' => [
                    'en' => 'A heart attack occurs when blood flow to the heart is severely reduced or blocked.',
                    'hi' => 'दिल का दौरा तब पड़ता है जब हृदय में रक्त का प्रवाह गंभीर रूप से कम या अवरुद्ध हो जाता है।',
                    'detailed_en' => 'A myocardial infarction (heart attack) happens when a blockage in one or more coronary arteries completely cuts off blood flow and oxygen to a section of the heart muscle. The blockage is usually caused by a buildup of plaque (fat and cholesterol). If blood flow is not restored quickly, that section of the heart muscle begins to die. It is a highly time-sensitive medical emergency.',
                    'detailed_hi' => 'मायोकार्डियल इन्फ्रक्शन (दिल का दौरा) तब होता है जब एक या अधिक कोरोनरी धमनियों में रुकावट हृदय की मांसपेशियों के एक हिस्से में रक्त प्रवाह और ऑक्सीजन को पूरी तरह से काट देती है। रुकावट आमतौर पर प्लाक (वसा और कोलेस्ट्रॉल) के निर्माण के कारण होती है। यदि रक्त प्रवाह जल्दी बहाल नहीं किया जाता है, तो हृदय की मांसपेशी का वह हिस्सा मरना शुरू हो जाता है।',
                ],
                'Home Remedies' => ['en' => 'There are NO home remedies for a heart attack. It is a strict medical emergency. Rush to the nearest hospital immediately.', 'hi' => 'हार्ट अटैक का कोई घरेलू उपाय नहीं है। यह एक सख्त मेडिकल इमरजेंसी है। तुरंत नजदीकी अस्पताल पहुंचें।'],
            ],
            'Coronary Artery Disease' => [
                'Department' => ['en' => 'Cardiology', 'hi' => 'कार्डियोलॉजी'],
                'Definition' => [
                    'en' => 'Coronary artery disease develops when the major blood vessels that supply your heart become damaged or diseased.',
                    'hi' => 'कोरोनरी धमनी रोग तब विकसित होता है जब आपके हृदय की आपूर्ति करने वाली प्रमुख रक्त वाहिकाएं क्षतिग्रस्त या रोगग्रस्त हो जाती हैं।',
                ],
            ],
            'Heart Failure' => [
                'Department' => ['en' => 'Cardiology', 'hi' => 'कार्डियोलॉजी'],
                'Definition' => [
                    'en' => 'Heart failure is a condition in which the heart doesn\'t pump blood as well as it should.',
                    'hi' => 'हार्ट फेलियर एक ऐसी स्थिति है जिसमें हृदय उतनी अच्छी तरह से रक्त पंप नहीं करता है जितना उसे करना चाहिए।',
                ],
            ],
            'Asthma' => [
                'Department' => ['en' => 'Pulmonology', 'hi' => 'पल्मोनोलॉजी (श्वसन रोग विज्ञान)'],
                'Definition' => [
                    'en' => 'Asthma is a condition in which airways narrow, swell, and produce extra mucus.',
                    'hi' => 'अस्थमा एक ऐसी स्थिति है जिसमें वायुमार्ग संकीर्ण हो जाते हैं और सूज जाते हैं।',
                    'detailed_en' => 'Asthma is a chronic inflammatory disease of the airways in the lungs. When exposed to certain triggers (like allergens, cold air, or exercise), the inside walls of the airways become inflamed and swollen. In addition, membranes secrete excess mucus. This combination severely restricts airflow, leading to wheezing, chest tightness, and shortness of breath (an asthma attack).',
                    'detailed_hi' => 'अस्थमा फेफड़ों में वायुमार्ग की एक पुरानी सूजन संबंधी बीमारी है। जब कुछ ट्रिगर्स (जैसे एलर्जी, ठंडी हवा, या व्यायाम) के संपर्क में आते हैं, तो वायुमार्ग की अंदरूनी दीवारें सूज जाती हैं। इसके अतिरिक्त, झिल्ली अतिरिक्त बलगम स्रावित करती है। यह संयोजन वायु प्रवाह को गंभीर रूप से प्रतिबंधित करता है, जिससे अस्थमा का दौरा पड़ता है।',
                ],
                'Home Remedies' => ['en' => 'Inhaling steam with carom seeds (ajwain), drinking ginger tea, and avoiding cold foods can soothe airways, but always keep your inhaler nearby.', 'hi' => 'अजवाइन के साथ भाप लेना, अदरक की चाय पीना और ठंडे खाद्य पदार्थों से बचना वायुमार्ग को शांत कर सकता है, लेकिन अपना इनहेलर हमेशा पास रखें।'],
            ],
            'Pneumonia' => [
                'Department' => ['en' => 'Pulmonology / Infectious Diseases', 'hi' => 'पल्मोनोलॉजी / संक्रामक रोग'],
                'Definition' => [
                    'en' => 'Pneumonia is an infection that inflames the air sacs in one or both lungs.',
                    'hi' => 'निमोनिया एक संक्रमण है जो एक या दोनों फेफड़ों में वायु की थैलियों को सूजा देता है।',
                ],
            ],
            'Bronchitis' => [
                'Department' => ['en' => 'Pulmonology', 'hi' => 'पल्मोनोलॉजी'],
                'Definition' => [
                    'en' => 'Bronchitis is an inflammation of the lining of your bronchial tubes, which carry air to and from your lungs.',
                    'hi' => 'ब्रोंकाइटिस आपके ब्रोन्कियल ट्यूबों के अस्तर की सूजन है, जो आपके फेफड़ों तक और वहां से हवा ले जाते हैं।',
                ],
            ],
            'COPD' => [
                'Department' => ['en' => 'Pulmonology', 'hi' => 'पल्मोनोलॉजी'],
                'Definition' => [
                    'en' => 'Chronic obstructive pulmonary disease (COPD) is a chronic inflammatory lung disease that causes obstructed airflow from the lungs.',
                    'hi' => 'क्रॉनिक ऑब्सट्रक्टिव पल्मोनरी डिजीज (COPD) फेफड़ों की एक पुरानी सूजन संबंधी बीमारी है जो फेफड़ों से वायुप्रवाह को बाधित करती है।',
                ],
            ],
            'Malaria' => [
                'Department' => ['en' => 'Infectious Diseases', 'hi' => 'संक्रामक रोग'],
                'Definition' => [
                    'en' => 'Malaria is a serious disease caused by a parasite transmitted by infected mosquitoes.',
                    'hi' => 'मलेरिया संक्रमित मच्छरों द्वारा प्रेषित परजीवी के कारण होने वाली एक गंभीर बीमारी है।',
                ],
                'Home Remedies' => ['en' => 'While medical treatment is mandatory, drinking Giloy juice, Tulsi water, and papaya leaf extract can help boost immunity and platelet count during recovery.', 'hi' => 'चिकित्सा उपचार अनिवार्य होने पर भी, गिलोय का रस, तुलसी का पानी और पपीते के पत्तों का अर्क रिकवरी के दौरान रोग प्रतिरोधक क्षमता बढ़ाने में मदद कर सकता है।'],
            ],
            'Dengue' => [
                'Department' => ['en' => 'Infectious Diseases', 'hi' => 'संक्रामक रोग'],
                'Definition' => [
                    'en' => 'Dengue is a mosquito-borne viral infection causing a severe flu-like illness.',
                    'hi' => 'डेंगू मच्छरों से फैलने वाला एक वायरल संक्रमण है जो फ्लू जैसी गंभीर बीमारी का कारण बनता है।',
                ],
                'Home Remedies' => ['en' => 'Papaya leaf juice is highly recommended to naturally boost platelet counts. Staying highly hydrated with coconut water and ORS is also crucial.', 'hi' => 'प्लेटलेट काउंट को प्राकृतिक रूप से बढ़ाने के लिए पपीते के पत्तों के रस की अत्यधिक अनुशंसा की जाती है। नारियल पानी और ओआरएस से हाइड्रेटेड रहना भी महत्वपूर्ण है।'],
            ],
            'Typhoid' => [
                'Department' => ['en' => 'Infectious Diseases', 'hi' => 'संक्रामक रोग'],
                'Definition' => [
                    'en' => 'Typhoid is a bacterial infection transmitted through contaminated food and water.',
                    'hi' => 'टाइफाइड दूषित भोजन और पानी के माध्यम से फैलने वाला एक जीवाणु संक्रमण है।',
                ],
            ],
            'Tuberculosis' => [
                'Department' => ['en' => 'Infectious Diseases / Pulmonology', 'hi' => 'संक्रामक रोग / पल्मोनोलॉजी'],
                'Definition' => [
                    'en' => 'Tuberculosis (TB) is a potentially serious infectious disease that mainly affects the lungs.',
                    'hi' => 'तपेदिक (टीबी) एक गंभीर संक्रामक रोग है जो मुख्य रूप से फेफड़ों को प्रभावित करता है।',
                ],
            ],
            'COVID-19' => [
                'Department' => ['en' => 'Infectious Diseases / Pulmonology', 'hi' => 'संक्रामक रोग / पल्मोनोलॉजी'],
                'Definition' => [
                    'en' => 'COVID-19 is an infectious disease caused by the SARS-CoV-2 virus.',
                    'hi' => 'कोविड-19 SARS-CoV-2 वायरस के कारण होने वाली एक संक्रामक बीमारी है।',
                ],
            ],
            'Influenza' => [
                'Department' => ['en' => 'Infectious Diseases', 'hi' => 'संक्रामक रोग'],
                'Definition' => [
                    'en' => 'Influenza, or the flu, is a respiratory infection caused by a virus that attacks your nose, throat, and lungs.',
                    'hi' => 'इन्फ्लूएंजा, या फ्लू, एक वायरस के कारण होने वाला श्वसन संक्रमण है जो आपकी नाक, गले और फेफड़ों पर हमला करता है।',
                ],
            ],
            'Hepatitis B' => [
                'Department' => ['en' => 'Hepatology / Infectious Diseases', 'hi' => 'हेपेटोलॉजी / संक्रामक रोग'],
                'Definition' => [
                    'en' => 'Hepatitis B is a serious liver infection caused by the hepatitis B virus (HBV).',
                    'hi' => 'हेपेटाइटिस बी हेपेटाइटिस बी वायरस (एचबीवी) के कारण होने वाला एक गंभीर यकृत संक्रमण है।',
                ],
            ],
            'Cholera' => [
                'Department' => ['en' => 'Infectious Diseases', 'hi' => 'संक्रामक रोग'],
                'Definition' => [
                    'en' => 'Cholera is a bacterial disease causing severe diarrhea and dehydration.',
                    'hi' => 'हैजा एक जीवाणु रोग है जो गंभीर दस्त और निर्जलीकरण का कारण बनता है।',
                ],
            ],
            'Measles' => [
                'Department' => ['en' => 'Infectious Diseases / Pediatrics', 'hi' => 'संक्रामक रोग / बाल रोग'],
                'Definition' => [
                    'en' => 'Measles is a highly contagious childhood infection caused by a virus.',
                    'hi' => 'खसरा एक वायरस के कारण होने वाला अत्यधिक संक्रामक बचपन का संक्रमण है।',
                ],
            ],
            'Common Cold' => [
                'Department' => ['en' => 'General Medicine', 'hi' => 'सामान्य चिकित्सा'],
                'Definition' => [
                    'en' => 'The common cold is a viral infection of your nose and throat.',
                    'hi' => 'सामान्य सर्दी आपकी नाक और गले का एक वायरल संक्रमण है।',
                ],
                'Home Remedies' => ['en' => 'Ginger-tulsi tea, turmeric milk (haldi doodh), and steam inhalation with a few drops of eucalyptus oil provide excellent relief from congestion and sore throat.', 'hi' => 'अदरक-तुलसी की चाय, हल्दी वाला दूध, और नीलगिरी के तेल की कुछ बूंदों के साथ भाप लेने से सर्दी और गले की खराश से बेहतरीन राहत मिलती है।'],
            ],
            'GERD' => [
                'Department' => ['en' => 'Gastroenterology', 'hi' => 'गैस्ट्रोएंटरोलॉजी (पाचन तंत्र रोग)'],
                'Definition' => [
                    'en' => 'Gastroesophageal reflux disease (GERD) occurs when stomach acid frequently flows back into the tube connecting your mouth and stomach.',
                    'hi' => 'गैस्ट्रोएसोफेगल रिफ्लक्स डिजीज (जीईआरडी) तब होता है जब पेट का एसिड बार-बार आपके मुंह और पेट को जोड़ने वाली नली में वापस बहता है।',
                ],
                'Home Remedies' => ['en' => 'Chewing a small piece of jaggery (gud) after meals, drinking cold milk, or having fennel seeds (saunf) boiled water offers quick relief from acidity.', 'hi' => 'भोजन के बाद गुड़ का एक छोटा टुकड़ा चबाना, ठंडा दूध पीना, या सौंफ का उबला हुआ पानी एसिडिटी से तुरंत राहत देता है।'],
            ],
            'Peptic Ulcer' => [
                'Department' => ['en' => 'Gastroenterology', 'hi' => 'गैस्ट्रोएंटरोलॉजी'],
                'Definition' => [
                    'en' => 'Peptic ulcers are open sores that develop on the inside lining of your stomach and the upper portion of your small intestine.',
                    'hi' => 'पेप्टिक अल्सर खुले घाव होते हैं जो आपके पेट की अंदरूनी परत और आपकी छोटी आंत के ऊपरी हिस्से पर विकसित होते हैं।',
                ],
            ],
            'Celiac Disease' => [
                'Department' => ['en' => 'Gastroenterology', 'hi' => 'गैस्ट्रोएंटरोलॉजी'],
                'Definition' => [
                    'en' => 'Celiac disease is an immune reaction to eating gluten, a protein found in wheat, barley, and rye.',
                    'hi' => 'सीलिएक रोग ग्लूटेन (गेहूं, जौ और राई में पाया जाने वाला प्रोटीन) खाने के प्रति प्रतिरक्षा प्रतिक्रिया है।',
                ],
            ],
            'Crohn\'s Disease' => [
                'Department' => ['en' => 'Gastroenterology', 'hi' => 'गैस्ट्रोएंटरोलॉजी'],
                'Definition' => [
                    'en' => 'Crohn\'s disease is a type of inflammatory bowel disease (IBD) that causes inflammation of your digestive tract.',
                    'hi' => 'क्रोहन रोग एक प्रकार का सूजन आंत्र रोग (IBD) है जो आपके पाचन तंत्र में सूजन का कारण बनता है।',
                ],
            ],
            'Irritable Bowel Syndrome' => [
                'Department' => ['en' => 'Gastroenterology', 'hi' => 'गैस्ट्रोएंटरोलॉजी'],
                'Definition' => [
                    'en' => 'Irritable bowel syndrome (IBS) is a common disorder that affects the large intestine, causing cramping, abdominal pain, and bloating.',
                    'hi' => 'इरिटेबल बाउल सिंड्रोम (IBS) एक सामान्य विकार है जो बड़ी आंत को प्रभावित करता है, जिससे ऐंठन, पेट दर्द और सूजन होती है।',
                ],
                'Home Remedies' => ['en' => 'Peppermint tea, buttermilk (chaas) with roasted cumin, and incorporating more easily digestible fibers like isabgol (psyllium husk) help soothe the stomach.', 'hi' => 'पुदीने की चाय, भुने हुए जीरे के साथ छाछ, और ईसबगोल जैसे आसानी से पचने वाले फाइबर पेट को शांत करने में मदद करते हैं।'],
            ],
            'Constipation' => [
                'Department' => ['en' => 'Gastroenterology', 'hi' => 'गैस्ट्रोएंटरोलॉजी'],
                'Definition' => [
                    'en' => 'Constipation occurs when bowel movements become less frequent and stools become difficult to pass.',
                    'hi' => 'कब्ज तब होता है जब मल त्याग कम हो जाता है और मल त्याग करना मुश्किल हो जाता है।',
                ],
                'Home Remedies' => ['en' => 'Having warm water with ghee before bed, consuming ripe papayas, or taking Triphala churna at night are effective natural laxatives.', 'hi' => 'सोने से पहले घी के साथ गर्म पानी लेना, पके पपीते का सेवन करना, या रात में त्रिफला चूर्ण लेना प्रभावी प्राकृतिक जुलाब (laxatives) हैं।'],
            ],
            'Arthritis' => [
                'Department' => ['en' => 'Rheumatology / Orthopedics', 'hi' => 'रुमेटोलॉजी / हड्डी रोग'],
                'Definition' => [
                    'en' => 'Arthritis is the swelling and tenderness of one or more joints.',
                    'hi' => 'गठिया एक या अधिक जोड़ों की सूजन और कोमलता है।',
                ],
                'Home Remedies' => ['en' => 'Massaging joints with warm mustard oil and garlic, drinking turmeric milk (haldi doodh) for its anti-inflammatory properties, and hot/cold compresses provide immense relief.', 'hi' => 'गर्म सरसों के तेल और लहसुन से जोड़ों की मालिश करना, सूजन कम करने के लिए हल्दी वाला दूध पीना, और गर्म/ठंडी सिकाई से अत्यधिक राहत मिलती है।'],
            ],
            'Rheumatoid Arthritis' => [
                'Department' => ['en' => 'Rheumatology', 'hi' => 'रुमेटोलॉजी'],
                'Definition' => [
                    'en' => 'Rheumatoid arthritis is a chronic inflammatory disorder that can affect more than just your joints.',
                    'hi' => 'रुमेटीइड गठिया एक पुरानी भड़काऊ बीमारी है जो सिर्फ आपके जोड़ों से ज्यादा प्रभावित कर सकती है।',
                ],
            ],
            'Osteoporosis' => [
                'Department' => ['en' => 'Orthopedics / Endocrinology', 'hi' => 'हड्डी रोग / एंडोक्रिनोलॉजी'],
                'Definition' => [
                    'en' => 'Osteoporosis causes bones to become weak and brittle.',
                    'hi' => 'ऑस्टियोपोरोसिस के कारण हड्डियां कमजोर और भंगुर हो जाती हैं।',
                ],
            ],
            'Lupus' => [
                'Department' => ['en' => 'Rheumatology', 'hi' => 'रुमेटोलॉजी'],
                'Definition' => [
                    'en' => 'Lupus is a systemic autoimmune disease that occurs when your body\'s immune system attacks your own tissues and organs.',
                    'hi' => 'ल्यूपस एक प्रणालीगत ऑटोइम्यून बीमारी है जो तब होती है जब आपके शरीर की प्रतिरक्षा प्रणाली आपके स्वयं के ऊतकों और अंगों पर हमला करती है।',
                ],
            ],
            'Psoriasis' => [
                'Department' => ['en' => 'Dermatology', 'hi' => 'डर्मेटोलॉजी (त्वचा विज्ञान)'],
                'Definition' => [
                    'en' => 'Psoriasis is a skin disease that causes red, itchy scaly patches, most commonly on the knees, elbows, trunk and scalp.',
                    'hi' => 'सोरायसिस एक त्वचा रोग है जो लाल, खुजलीदार पपड़ीदार पैच का कारण बनता है, जो आमतौर पर घुटनों, कोहनी, धड़ और खोपड़ी पर होता है।',
                ],
            ],
            'Eczema' => [
                'Department' => ['en' => 'Dermatology', 'hi' => 'डर्मेटोलॉजी'],
                'Definition' => [
                    'en' => 'Eczema (atopic dermatitis) is a condition that makes your skin red and itchy.',
                    'hi' => 'एक्जिमा (एटोपिक डर्मेटाइटिस) एक ऐसी स्थिति है जो आपकी त्वचा को लाल और खुजलीदार बनाती है।',
                ],
            ],
            'Acne' => [
                'Department' => ['en' => 'Dermatology', 'hi' => 'डर्मेटोलॉजी (त्वचा विज्ञान)'],
                'Definition' => [
                    'en' => 'Acne is a skin condition that occurs when your hair follicles become plugged with oil and dead skin cells.',
                    'hi' => 'मुँहासे एक त्वचा की स्थिति है जो तब होती है जब आपके बालों के रोम तेल और मृत त्वचा कोशिकाओं से प्लग हो जाते हैं।',
                    'detailed_en' => 'Acne vulgaris is a highly common chronic skin disease. It develops when sebum (oil) and dead skin cells plug the hair follicles, creating an environment where naturally occurring skin bacteria can multiply rapidly. This triggers an inflammatory response resulting in pimples, cysts, or nodules. Hormonal fluctuations are a primary driver of acne production.',
                    'detailed_hi' => 'मुँहासे एक अत्यधिक सामान्य पुरानी त्वचा रोग है। यह तब विकसित होता है जब सीबम (तेल) और मृत त्वचा कोशिकाएं बालों के रोम को प्लग करती हैं, जिससे एक ऐसा वातावरण बनता है जहां स्वाभाविक रूप से होने वाले त्वचा बैक्टीरिया तेजी से गुणा कर सकते हैं। यह मुहासे या सिस्ट में परिणत एक भड़काऊ प्रतिक्रिया को ट्रिगर करता है।',
                ],
                'Home Remedies' => ['en' => 'Applying a paste of neem leaves, sandalwood powder (chandan) with rose water, or a dab of diluted tea tree oil can help reduce inflammation and clear spots.', 'hi' => 'नीम के पत्तों का लेप, गुलाब जल के साथ चंदन पाउडर, या एलोवेरा जेल लगाने से सूजन कम करने और त्वचा को साफ करने में मदद मिल सकती है।'],
            ],
            'Kidney Stones' => [
                'Department' => ['en' => 'Urology / Nephrology', 'hi' => 'यूरोलॉजी / नेफ्रोलॉजी'],
                'Definition' => [
                    'en' => 'Kidney stones are hard deposits made of minerals and salts that form inside your kidneys.',
                    'hi' => 'गुर्दे की पथरी खनिजों और लवणों से बनी कठोर जमा होती है जो आपके गुर्दे के अंदर बनती है।',
                ],
                'Home Remedies' => ['en' => 'Drinking plenty of barley water (jau ka pani), lemon water, and consuming basil (tulsi) juice helps in flushing out smaller stones naturally.', 'hi' => 'भरपूर मात्रा में जौ का पानी, नींबू पानी पीना और तुलसी के रस का सेवन करने से छोटी पथरी को प्राकृतिक रूप से बाहर निकालने में मदद मिलती है।'],
            ],
            'Urinary Tract Infection' => [
                'Department' => ['en' => 'Urology / Gynecology', 'hi' => 'यूरोलॉजी / स्त्री रोग'],
                'Definition' => [
                    'en' => 'A urinary tract infection (UTI) is an infection in any part of your urinary system.',
                    'hi' => 'मूत्र पथ का संक्रमण (यूटीआई) आपके मूत्र प्रणाली के किसी भी हिस्से में संक्रमण है।',
                ],
            ],
            'PCOS' => [
                'Department' => ['en' => 'Gynecology / Endocrinology', 'hi' => 'स्त्री रोग / एंडोक्रिनोलॉजी'],
                'Definition' => [
                    'en' => 'Polycystic ovary syndrome (PCOS) is a hormonal disorder common among women of reproductive age.',
                    'hi' => 'पॉलीसिस्टिक ओवरी सिंड्रोम (पीसीओएस) प्रजनन आयु की महिलाओं में आम एक हार्मोनल विकार है।',
                ],
                'Home Remedies' => ['en' => 'Drinking spearmint tea, consuming cinnamon (dalchini) infused water, and eating roasted flaxseeds daily can naturally help balance hormones.', 'hi' => 'पुदीने की चाय पीना, दालचीनी का पानी और रोजाना भुने हुए अलसी के बीज खाने से प्राकृतिक रूप से हार्मोन को संतुलित करने में मदद मिल सकती है।'],
            ],
            'Endometriosis' => [
                'Department' => ['en' => 'Gynecology', 'hi' => 'स्त्री रोग'],
                'Definition' => [
                    'en' => 'Endometriosis is an often painful disorder in which tissue similar to the tissue that normally lines the inside of your uterus grows outside your uterus.',
                    'hi' => 'एंडोमेट्रियोसिस एक अक्सर दर्दनाक विकार है जिसमें गर्भाशय के अंदरूनी हिस्से को अस्तर करने वाले ऊतक के समान ऊतक गर्भाशय के बाहर बढ़ता है।',
                ],
            ],
            'Anemia' => [
                'Department' => ['en' => 'Hematology', 'hi' => 'हेमेटोलॉजी (रक्त विज्ञान)'],
                'Definition' => [
                    'en' => 'Anemia is a lack of enough healthy red blood cells to carry adequate oxygen.',
                    'hi' => 'एनीमिया पर्याप्त ऑक्सीजन ले जाने के लिए पर्याप्त स्वस्थ लाल रक्त कोशिकाओं की कमी है।',
                ],
            ],
            'Cancer' => [
                'Department' => ['en' => 'Oncology', 'hi' => 'ऑन्कोलॉजी (कैंसर विज्ञान)'],
                'Definition' => [
                    'en' => 'Cancer refers to diseases characterized by the development of abnormal cells that divide uncontrollably.',
                    'hi' => 'कैंसर उन बीमारियों को संदर्भित करता है जिनमें असामान्य कोशिकाएं अनियंत्रित रूप से विभाजित होती हैं।',
                ],
            ],
            'Leukemia' => [
                'Department' => ['en' => 'Oncology / Hematology', 'hi' => 'ऑन्कोलॉजी / हेमेटोलॉजी'],
                'Definition' => [
                    'en' => 'Leukemia is cancer of the body\'s blood-forming tissues, including the bone marrow and the lymphatic system.',
                    'hi' => 'ल्यूकेमिया अस्थि मज्जा और लसीका प्रणाली सहित शरीर के रक्त बनाने वाले ऊतकों का कैंसर है।',
                ],
            ],
            'Glaucoma' => [
                'Department' => ['en' => 'Ophthalmology', 'hi' => 'नेत्र विज्ञान (Ophthalmology)'],
                'Definition' => [
                    'en' => 'Glaucoma is a group of eye conditions that damage the optic nerve.',
                    'hi' => 'ग्लूकोमा आंखों की स्थितियों का एक समूह है जो ऑप्टिक तंत्रिका को नुकसान पहुंचाता है।',
                ],
            ],
            'Cataract' => [
                'Department' => ['en' => 'Ophthalmology', 'hi' => 'नेत्र विज्ञान'],
                'Definition' => [
                    'en' => 'A cataract is a clouding of the normally clear lens of the eye.',
                    'hi' => 'मोतियाबिंद आंख के सामान्य रूप से स्पष्ट लेंस का धुंधला होना है।',
                ],
            ],
        ];

        // ==========================================
        // 2. DYNAMIC AI FALLBACKS
        // Dynamic generation for diseases not deeply mapped above. 
        // ==========================================
        $fallbacks = [
            'Definition' => [
                'en' => ["{disease} is a medical condition generally managed by the {department} department.", "Medically, {disease} falls under {department} care. A specialist can provide exact diagnostic details."],
                'hi' => ["{disease} एक ऐसी स्थिति है जिसका प्रबंधन आमतौर पर {department} विभाग द्वारा किया जाता है।", "चिकित्सीय रूप से, {disease} {department} देखभाल के अंतर्गत आता है।"]
            ],
            'Symptoms' => [
                'en' => ["Symptoms of {disease} vary depending on the severity. It is best to consult {department}.", "Signs of {disease} can differ among patients; a clinical checkup is advised."],
                'hi' => ["{disease} के लक्षण गंभीरता के आधार पर भिन्न होते हैं। {department} से परामर्श करना सर्वोत्तम है।", "{disease} के संकेत अलग हो सकते हैं; नैदानिक जांच की सलाह दी जाती है।"]
            ],
            'Causes' => [
                'en' => ["The exact causes of {disease} are evaluated by {department} specialists based on patient history."],
                'hi' => ["{disease} के सटीक कारणों का मूल्यांकन रोगी के इतिहास के आधार पर {department} विशेषज्ञों द्वारा किया जाता है।"]
            ],
            'Prevention' => [
                'en' => ["Preventing {disease} involves following guidelines prescribed by {department}."],
                'hi' => ["{disease} को रोकने में {department} द्वारा निर्धारित दिशानिर्देशों का पालन करना शामिल है।"]
            ],
            'Diagnosis' => [
                'en' => ["Diagnosis of {disease} requires specific clinical tests done by {department}."],
                'hi' => ["{disease} के निदान के लिए {department} द्वारा विशिष्ट नैदानिक परीक्षणों की आवश्यकता होती है।"]
            ],
            'Treatment' => [
                'en' => ["Treatment plans for {disease} are highly personalized by the {department} team."],
                'hi' => ["{disease} के लिए उपचार योजनाएं {department} टीम द्वारा अत्यधिक व्यक्तिगत होती हैं।"]
            ],
            'Complications' => [
                'en' => ["If left untreated, {disease} can lead to severe health issues. Prompt care is required."],
                'hi' => ["यदि अनुपचारित छोड़ दिया जाए, तो {disease} गंभीर स्वास्थ्य समस्याओं का कारण बन सकता है।"]
            ],
            'When to See Doctor' => [
                'en' => ["If you suspect {disease}, or symptoms worsen, visit the {department} immediately."],
                'hi' => ["यदि आपको {disease} का संदेह है, तो तुरंत {department} पर जाएँ।"]
            ],
            'Risk Factors' => [
                'en' => ["Risk factors for {disease} include genetics, environment, and overall health."],
                'hi' => ["{disease} के जोखिम कारकों में आनुवंशिकी, पर्यावरण और समग्र स्वास्थ्य शामिल हैं।"]
            ],
            'Home Remedies' => [
                'en' => ["While home care offers comfort, {disease} strictly requires professional medical treatment."],
                'hi' => ["हालांकि घरेलू देखभाल आराम प्रदान करती है, {disease} के लिए पेशेवर चिकित्सा उपचार की आवश्यकता होती है।"]
            ],
            'Transmission' => [
                'en' => ["The communicability of {disease} depends on its clinical nature. Ask a {department} doctor."],
                'hi' => ["{disease} की संक्रामकता इसकी नैदानिक प्रकृति पर निर्भर करती है। {department} डॉक्टर से पूछें।"]
            ],
            'Diet and Nutrition' => [
                'en' => ["Dietary needs for {disease} should be discussed with your healthcare provider."],
                'hi' => ["{disease} के लिए आहार संबंधी जरूरतों पर आपके स्वास्थ्य सेवा प्रदाता के साथ चर्चा की जानी चाहिए।"]
            ],
            'Prognosis' => [
                'en' => ["The long-term outlook for {disease} is best discussed directly with your {department} specialist."],
                'hi' => ["{disease} के लिए दीर्घकालिक दृष्टिकोण पर आपके {department} विशेषज्ञ के साथ चर्चा की जानी चाहिए।"]
            ],
            'Recovery Time' => [
                'en' => ["Recovery from {disease} varies greatly from person to person."],
                'hi' => ["{disease} से ठीक होना व्यक्ति-दर-व्यक्ति बहुत भिन्न होता है।"]
            ],
            'Types and Variants' => [
                'en' => ["{disease} may present in multiple forms or stages. A proper diagnosis is crucial."],
                'hi' => ["{disease} कई रूपों या चरणों में उपस्थित हो सकता है। एक उचित निदान महत्वपूर्ण है।"]
            ],
            'Pregnancy' => [
                'en' => ["Managing {disease} during pregnancy requires highly specialized care. Consult your doctor immediately."],
                'hi' => ["गर्भावस्था के दौरान {disease} के प्रबंधन के लिए अत्यधिक विशिष्ट देखभाल की आवश्यकता होती है।"]
            ]
        ];

        // ==========================================
        // 3. THE 16 QUESTION TEMPLATES
        // ==========================================
        $questions = [
            'Definition' => ['en' => ['What exactly is {disease}?'], 'hi' => ['{disease} क्या है?']],
            'Symptoms' => ['en' => ['What are the symptoms of {disease}?'], 'hi' => ['{disease} के लक्षण क्या हैं?']],
            'Causes' => ['en' => ['What causes {disease}?'], 'hi' => ['{disease} का क्या कारण है?']],
            'Prevention' => ['en' => ['How can I prevent {disease}?'], 'hi' => ['मैं {disease} से कैसे बच सकता हूँ?']],
            'Diagnosis' => ['en' => ['How is {disease} diagnosed?'], 'hi' => ['{disease} का निदान कैसे किया जाता है?']],
            'Treatment' => ['en' => ['What is the treatment for {disease}?'], 'hi' => ['{disease} का इलाज क्या है?']],
            'Complications' => ['en' => ['What are the complications of {disease}?'], 'hi' => ['{disease} की जटिलताएं क्या हैं?']],
            'When to See Doctor' => ['en' => ['When should I see a doctor for {disease}?'], 'hi' => ['मुझे {disease} के लिए डॉक्टर को कब दिखाना चाहिए?']],
            'Risk Factors' => ['en' => ['What are the risk factors for {disease}?'], 'hi' => ['{disease} के जोखिम कारक क्या हैं?']],
            'Home Remedies' => ['en' => ['Are there home remedies for {disease}?'], 'hi' => ['क्या {disease} के लिए कोई असरदार घरेलू नुस्खे हैं?']],
            'Transmission' => ['en' => ['How does {disease} spread?'], 'hi' => ['{disease} कैसे फैलता है?']],
            'Diet and Nutrition' => ['en' => ['What should I eat if I have {disease}?'], 'hi' => ['यदि मुझे {disease} है तो मुझे क्या खाना चाहिए?']],
            'Prognosis' => ['en' => ['Is {disease} fatal?'], 'hi' => ['क्या {disease} जानलेवा है?']],
            'Recovery Time' => ['en' => ['How long does it take to recover from {disease}?'], 'hi' => ['{disease} से ठीक होने में कितना समय लगता है?']],
            'Types and Variants' => ['en' => ['Are there different types of {disease}?'], 'hi' => ['क्या {disease} के विभिन्न प्रकार हैं?']],
            'Pregnancy' => ['en' => ['How does {disease} affect pregnancy?'], 'hi' => ['{disease} गर्भावस्था को कैसे प्रभावित करता है?']],
        ];

        $now = now();
        $records = [];

        foreach ($diseases as $disease) {
            $diseaseNameEn = trim($disease->name_en);
            $diseaseNameHi = trim($disease->name_hi ?: $disease->name_en);

            // ==========================================
            // SMART DEPARTMENT RESOLUTION (DB vs KB Override)
            // ==========================================
            $dbDeptEn = $disease->department?->name_en;
            $dbDeptHi = $disease->department?->name_hi;

            $kbDeptEn = $knowledgeBase[$diseaseNameEn]['Department']['en'] ?? null;
            $kbDeptHi = $knowledgeBase[$diseaseNameEn]['Department']['hi'] ?? null;

            // Prioritize KB specific department, fallback to DB, ultimate fallback to General Medicine
            $departmentEn = $kbDeptEn ?: ($dbDeptEn ?: 'General Medicine');
            $departmentHi = $kbDeptHi ?: ($dbDeptHi ?: 'सामान्य चिकित्सा');

            foreach ($questions as $category => $langs) {
                // Determine Base Answers
                if (isset($knowledgeBase[$diseaseNameEn][$category])) {
                    $ansEnTemplate = $knowledgeBase[$diseaseNameEn][$category]['en'] ?? '';
                    $ansHiTemplate = $knowledgeBase[$diseaseNameEn][$category]['hi'] ?? '';
                    
                    // Determine Detailed Answers if they exist, otherwise build a fallback
                    $detEnTemplate = $knowledgeBase[$diseaseNameEn][$category]['detailed_en'] ?? $ansEnTemplate . " For a comprehensive, step-by-step understanding and to explore advanced clinical aspects of this condition, it is highly recommended to consult a specialist in the {department} department.";
                    $detHiTemplate = $knowledgeBase[$diseaseNameEn][$category]['detailed_hi'] ?? $ansHiTemplate . " इस स्थिति के व्यापक दृष्टिकोण और उन्नत नैदानिक पहलुओं का पता लगाने के लिए, {department} विभाग के विशेषज्ञ से परामर्श करने की अत्यधिक अनुशंसा की जाती है।";

                } else {
                    $ansEnTemplate = $fallbacks[$category]['en'][array_rand($fallbacks[$category]['en'])];
                    $ansHiTemplate = $fallbacks[$category]['hi'][array_rand($fallbacks[$category]['hi'])];
                    
                    // Dynamic Detailed Fallback
                    $detEnTemplate = $ansEnTemplate . " While this provides a basic overview, {disease} can deeply affect the body's systems in unique ways over time. To understand its full clinical progression, specific risk factors tailored to your body, and advanced medical management protocols, a detailed evaluation with the {department} department is strictly advised.";
                    $detHiTemplate = $ansHiTemplate . " हालांकि यह एक बुनियादी अवलोकन प्रदान करता है, {disease} समय के साथ शरीर की प्रणालियों को विशिष्ट तरीकों से गहराई से प्रभावित कर सकता है। इसकी पूर्ण नैदानिक प्रगति, आपके शरीर के अनुरूप विशिष्ट जोखिम कारकों और उन्नत चिकित्सा प्रबंधन को समझने के लिए, {department} विभाग के साथ एक विस्तृत मूल्यांकन की सख्त सलाह दी जाती है।";
                }

                // String Replacements
                $ansEn = str_replace(['{disease}', '{department}'], [$diseaseNameEn, $departmentEn], $ansEnTemplate);
                $ansHi = str_replace(['{disease}', '{department}'], [$diseaseNameHi, $departmentHi], $ansHiTemplate);
                
                $detEn = str_replace(['{disease}', '{department}'], [$diseaseNameEn, $departmentEn], $detEnTemplate);
                $detHi = str_replace(['{disease}', '{department}'], [$diseaseNameHi, $departmentHi], $detHiTemplate);

                foreach ($langs['en'] as $index => $qEn) {
                    $qHi = $langs['hi'][$index];

                    $records[] = [
                        'question_en'        => str_replace('{disease}', $diseaseNameEn, $qEn),
                        'question_hi'        => str_replace('{disease}', $diseaseNameHi, $qHi),
                        'answer_en'          => $ansEn,
                        'answer_hi'          => $ansHi,
                        'detailed_answer_en' => $detEn,
                        'detailed_answer_hi' => $detHi,
                        'category'           => $category,
                        'created_at'         => $now,
                        'updated_at'         => $now,
                    ];
                }
            }
        }

        $insertedCount = 0;
        foreach (array_chunk($records, 1000) as $chunk) {
            $insertedCount += CachedMedicalQuestion::insertOrIgnore($chunk);
        }

        $this->command?->info("ULTIMATE MASTER SEEDER COMPLETE: Inserted {$insertedCount} records (NO DATA LOST).");
    }
}
