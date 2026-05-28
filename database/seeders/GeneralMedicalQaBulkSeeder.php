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
        $symptomPhrases = json_decode(<<<'JSON'
        [
            {
                "en": "headache",
                "hi": "सिर दर्द",
                "care_en": "Rest in a quiet room, drink enough water, reduce mobile or computer use, and avoid skipping meals. A mild headache often improves with sleep, hydration, and reducing strain.",
                "care_hi": "शांत कमरे में आराम करें, पर्याप्त पानी पिएं, मोबाइल या कंप्यूटर का उपयोग कम करें और भोजन न छोड़ें। हल्का सिर दर्द अक्सर नींद, पानी और तनाव कम करने से बेहतर हो जाता है।",
                "red_en": "Seek urgent medical care if the headache is sudden and severe, follows a head injury, comes with fever, neck stiffness, confusion, weakness, vision loss, repeated vomiting, or does not improve.",
                "red_hi": "यदि सिर दर्द अचानक बहुत तेज हो, सिर पर चोट के बाद शुरू हुआ हो, बुखार, गर्दन अकड़ना, भ्रम, कमजोरी, नजर कम होना, बार-बार उल्टी या सुधार न हो, तो तुरंत चिकित्सक से मिलें।"
            },
            {
                "en": "fever",
                "hi": "बुखार",
                "care_en": "Measure temperature with a thermometer, rest, drink fluids frequently, wear light clothing, and avoid unnecessary antibiotics. Fever is a sign that the body is fighting an infection.",
                "care_hi": "थर्मामीटर से तापमान जांचें, आराम करें, बार-बार तरल लें, हल्के कपड़े पहनें और बिना सलाह एंटीबायोटिक न लें। बुखार अक्सर शरीर में संक्रमण से लड़ने का संकेत होता है।",
                "red_en": "Get urgent help if fever is very high, lasts more than three days, occurs with breathing difficulty, rash, severe headache, stiff neck, dehydration, seizures, confusion, or in infants.",
                "red_hi": "यदि बुखार बहुत तेज हो, तीन दिन से अधिक रहे, सांस लेने में दिक्कत, चकत्ते, तेज सिर दर्द, गर्दन अकड़ना, पानी की कमी, दौरा, भ्रम या शिशु में बुखार हो, तो तुरंत चिकित्सा सहायता लें।"
            },
            {
                "en": "sore throat",
                "hi": "गले में खराश",
                "care_en": "Drink warm fluids, do salt-water gargles, rest your voice, and avoid smoke, dust, and very cold drinks. Most mild throat irritation improves with supportive care.",
                "care_hi": "गर्म तरल लें, नमक-पानी से गरारे करें, आवाज को आराम दें और धुआं, धूल तथा बहुत ठंडे पेय से बचें। हल्की गले की खराश अक्सर सहायक देखभाल से ठीक हो जाती है।",
                "red_en": "See a doctor if there is difficulty breathing, inability to swallow, high fever, pus on tonsils, severe one-sided pain, neck swelling, or symptoms lasting more than a few days.",
                "red_hi": "यदि सांस लेने में कठिनाई, निगलने में असमर्थता, तेज बुखार, टॉन्सिल पर मवाद, एक तरफ तेज दर्द, गर्दन में सूजन या कई दिनों तक लक्षण बने रहें, तो चिकित्सक से मिलें।"
            },
            {
                "en": "mild cough",
                "hi": "हल्की खांसी",
                "care_en": "Drink warm water, take steam if comfortable, avoid cold air and smoke, and keep the room well ventilated. A mild cough can follow a cold and may take a few days to settle.",
                "care_hi": "गर्म पानी पिएं, सुविधा हो तो भाप लें, ठंडी हवा और धुएं से बचें तथा कमरे में हवा का आवागमन रखें। सर्दी के बाद हल्की खांसी कुछ दिन तक रह सकती है।",
                "red_en": "Consult a doctor if cough lasts more than two weeks, has blood, chest pain, breathing trouble, high fever, wheezing, weight loss, or occurs in a child or elderly person.",
                "red_hi": "यदि खांसी दो सप्ताह से अधिक रहे, खून आए, सीने में दर्द, सांस में दिक्कत, तेज बुखार, घरघराहट, वजन घटना या बच्चे/बुजुर्ग में खांसी हो, तो चिकित्सक से सलाह लें।"
            },
            {
                "en": "acidity",
                "hi": "अम्लता",
                "care_en": "Eat smaller meals, avoid spicy and oily foods, reduce tea and coffee, and do not lie down for at least two to three hours after eating.",
                "care_hi": "छोटे-छोटे भोजन करें, मसालेदार और तैलीय भोजन से बचें, चाय-कॉफी कम करें और खाने के बाद कम से कम दो से तीन घंटे तक न लेटें।",
                "red_en": "Seek medical advice if acidity is frequent, causes chest pain, black stools, vomiting blood, difficulty swallowing, unexplained weight loss, or pain spreading to the arm or jaw.",
                "red_hi": "यदि अम्लता बार-बार हो, सीने में दर्द, काला मल, खून की उल्टी, निगलने में कठिनाई, बिना कारण वजन घटना या दर्द बांह/जबड़े तक जाए, तो चिकित्सक से मिलें।"
            },
            {
                "en": "mild stomach pain",
                "hi": "हल्का पेट दर्द",
                "care_en": "Take light food, drink water, avoid oily meals, and observe whether pain improves within a day. Note the location, timing, and relation with food.",
                "care_hi": "हल्का भोजन करें, पानी पिएं, तैलीय भोजन से बचें और देखें कि एक दिन में दर्द कम होता है या नहीं। दर्द की जगह, समय और भोजन से संबंध पर ध्यान दें।",
                "red_en": "Urgent care is needed for severe or worsening pain, pain with fever, persistent vomiting, blood in stool, rigid abdomen, pregnancy, fainting, or pain in the right lower abdomen.",
                "red_hi": "तेज या बढ़ता दर्द, बुखार के साथ दर्द, लगातार उल्टी, मल में खून, पेट सख्त होना, गर्भावस्था, बेहोशी या दाहिने निचले पेट में दर्द हो तो तुरंत सहायता लें।"
            },
            {
                "en": "constipation",
                "hi": "कब्ज",
                "care_en": "Increase water, fiber-rich foods, fruits, vegetables, whole grains, and gentle walking. Avoid delaying the urge to pass stool.",
                "care_hi": "पानी, रेशेयुक्त भोजन, फल, सब्जियां, साबुत अनाज और हल्की पैदल चाल बढ़ाएं। मल त्याग की इच्छा को देर तक न रोकें।",
                "red_en": "See a doctor if constipation is new and persistent, occurs with severe pain, vomiting, bloating, blood in stool, weight loss, or no stool or gas passage.",
                "red_hi": "यदि कब्ज नई और लगातार हो, तेज दर्द, उल्टी, पेट फूलना, मल में खून, वजन घटना या मल/गैस बिल्कुल न निकलना हो, तो चिकित्सक से मिलें।"
            },
            {
                "en": "diarrhea",
                "hi": "दस्त",
                "care_en": "Take oral rehydration solution, drink fluids after each loose stool, eat light food, and avoid oily or very sweet drinks. Prevent dehydration first.",
                "care_hi": "ओआरएस घोल लें, हर पतले मल के बाद तरल पिएं, हल्का भोजन करें और तैलीय या बहुत मीठे पेय से बचें। सबसे पहले पानी की कमी रोकना जरूरी है।",
                "red_en": "Seek urgent care for blood in stool, high fever, severe dehydration, continuous vomiting, severe abdominal pain, diarrhea in infants, elderly people, or symptoms lasting more than two days.",
                "red_hi": "मल में खून, तेज बुखार, गंभीर पानी की कमी, लगातार उल्टी, तेज पेट दर्द, शिशु/बुजुर्ग में दस्त या दो दिन से अधिक लक्षण रहने पर तुरंत चिकित्सा सहायता लें।"
            },
            {
                "en": "body pain",
                "hi": "शरीर दर्द",
                "care_en": "Rest, hydrate, take light nutritious food, and do gentle stretching only if it does not increase pain. Body pain may occur with viral illness, fatigue, or exertion.",
                "care_hi": "आराम करें, पानी पिएं, हल्का पौष्टिक भोजन लें और दर्द न बढ़े तो ही हल्की स्ट्रेचिंग करें। शरीर दर्द वायरल बीमारी, थकान या अधिक मेहनत से हो सकता है।",
                "red_en": "Consult a doctor if pain is severe, one-sided, associated with high fever, weakness, swelling, dark urine, injury, chest pain, or does not improve.",
                "red_hi": "यदि दर्द बहुत तेज, एक तरफ, तेज बुखार, कमजोरी, सूजन, गहरे रंग का पेशाब, चोट, सीने में दर्द या सुधार न हो, तो चिकित्सक से मिलें।"
            },
            {
                "en": "minor burn",
                "hi": "हल्का जलना",
                "care_en": "Cool the burned area under clean running water for ten to twenty minutes, remove tight jewelry, and cover with a clean non-stick dressing. Do not apply toothpaste, oil, or ice directly.",
                "care_hi": "जले हुए हिस्से को साफ बहते पानी के नीचे दस से बीस मिनट ठंडा करें, तंग आभूषण हटाएं और साफ चिपकने-रहित पट्टी से ढकें। टूथपेस्ट, तेल या बर्फ सीधे न लगाएं।",
                "red_en": "Get medical help for burns on the face, hands, genitals, large areas, chemical or electrical burns, deep blisters, severe pain, infection, or burns in children or elderly people.",
                "red_hi": "चेहरे, हाथ, जननांग, बड़े हिस्से, रासायनिक/बिजली से जलना, गहरे फफोले, बहुत दर्द, संक्रमण या बच्चे/बुजुर्ग में जलने पर चिकित्सक से मिलें।"
            },
            {
                "en": "minor cut",
                "hi": "हल्का कटना",
                "care_en": "Wash with clean running water, apply gentle pressure to stop bleeding, use an antiseptic if suitable, and cover with a sterile bandage. Keep the wound clean and dry.",
                "care_hi": "साफ बहते पानी से धोएं, खून रोकने के लिए हल्का दबाव दें, उचित हो तो एंटीसेप्टिक लगाएं और स्टेराइल पट्टी से ढकें। घाव को साफ और सूखा रखें।",
                "red_en": "See a doctor if bleeding does not stop, the cut is deep, caused by a rusty object or animal bite, has dirt inside, shows pus, redness spreading, or tetanus vaccination is not updated.",
                "red_hi": "यदि खून न रुके, कट गहरा हो, जंग लगी वस्तु या जानवर के काटने से हुआ हो, घाव में गंदगी हो, मवाद आए, लालिमा फैले या टिटनेस टीका अद्यतन न हो, तो चिकित्सक से मिलें।"
            },
            {
                "en": "dizziness",
                "hi": "चक्कर आना",
                "care_en": "Sit or lie down immediately, drink water, avoid sudden standing, and eat something light if meals were skipped. Do not drive until dizziness settles.",
                "care_hi": "तुरंत बैठें या लेटें, पानी पिएं, अचानक खड़े न हों और भोजन छूटा हो तो हल्का कुछ खाएं। चक्कर ठीक होने तक वाहन न चलाएं।",
                "red_en": "Urgent care is needed if dizziness comes with fainting, chest pain, severe headache, weakness, slurred speech, vision changes, irregular heartbeat, severe dehydration, or repeated episodes.",
                "red_hi": "यदि चक्कर के साथ बेहोशी, सीने में दर्द, तेज सिर दर्द, कमजोरी, बोलने में गड़बड़ी, नजर बदलना, धड़कन अनियमित होना, गंभीर पानी की कमी या बार-बार चक्कर हों, तो तुरंत सहायता लें।"
            },
            {
                "en": "nausea",
                "hi": "मतली",
                "care_en": "Take small sips of water, eat bland food, avoid strong smells, and rest. Ginger tea may help some people if it suits them.",
                "care_hi": "पानी के छोटे घूंट लें, सादा भोजन करें, तेज गंध से बचें और आराम करें। कुछ लोगों को अदरक की चाय से राहत मिल सकती है, यदि यह अनुकूल हो।",
                "red_en": "Seek help if nausea is persistent, accompanied by severe abdominal pain, repeated vomiting, dehydration, blood in vomit, chest pain, severe headache, or pregnancy-related concern.",
                "red_hi": "यदि मतली लगातार रहे, तेज पेट दर्द, बार-बार उल्टी, पानी की कमी, उल्टी में खून, सीने में दर्द, तेज सिर दर्द या गर्भावस्था से जुड़ी चिंता हो, तो चिकित्सक से मिलें।"
            },
            {
                "en": "vomiting",
                "hi": "उल्टी",
                "care_en": "Rest the stomach for a short time, take small frequent sips of oral rehydration solution, and restart light food gradually when vomiting reduces.",
                "care_hi": "कुछ समय पेट को आराम दें, ओआरएस घोल के छोटे-छोटे घूंट बार-बार लें और उल्टी कम होने पर धीरे-धीरे हल्का भोजन शुरू करें।",
                "red_en": "Urgent care is needed for repeated vomiting, blood or green vomit, severe dehydration, severe headache, stiff neck, abdominal swelling, pregnancy, or vomiting in infants.",
                "red_hi": "बार-बार उल्टी, खून या हरी उल्टी, गंभीर पानी की कमी, तेज सिर दर्द, गर्दन अकड़ना, पेट फूलना, गर्भावस्था या शिशु में उल्टी हो तो तुरंत सहायता लें।"
            },
            {
                "en": "common cold",
                "hi": "सामान्य सर्दी",
                "care_en": "Rest, drink warm fluids, use saline drops if needed, and avoid close contact to prevent spread. Most colds improve within a week.",
                "care_hi": "आराम करें, गर्म तरल लें, जरूरत हो तो नमक-पानी की नाक की बूंदें उपयोग करें और संक्रमण फैलने से बचने के लिए निकट संपर्क कम रखें। सामान्य सर्दी अक्सर एक सप्ताह में सुधरती है।",
                "red_en": "See a doctor if there is high fever, breathing difficulty, chest pain, severe sinus pain, symptoms longer than ten days, or worsening after initial improvement.",
                "red_hi": "तेज बुखार, सांस लेने में दिक्कत, सीने में दर्द, साइनस में तेज दर्द, दस दिन से अधिक लक्षण या पहले सुधार के बाद फिर बिगड़ना हो तो चिकित्सक से मिलें।"
            },
            {
                "en": "blocked nose",
                "hi": "नाक बंद होना",
                "care_en": "Use steam carefully, saline spray, warm fluids, and keep the head slightly elevated while sleeping. Avoid overusing decongestant nasal sprays.",
                "care_hi": "सावधानी से भाप लें, नमक-पानी स्प्रे उपयोग करें, गर्म तरल लें और सोते समय सिर थोड़ा ऊंचा रखें। नाक खोलने वाले स्प्रे का अधिक उपयोग न करें।",
                "red_en": "Medical advice is needed if blockage is one-sided, associated with facial pain, high fever, foul discharge, breathing trouble, nose injury, or lasts for many days.",
                "red_hi": "यदि नाक बंद होना एक तरफ हो, चेहरे में दर्द, तेज बुखार, बदबूदार स्राव, सांस में दिक्कत, नाक की चोट या कई दिन तक बना रहे, तो चिकित्सक से सलाह लें।"
            },
            {
                "en": "allergy symptoms",
                "hi": "एलर्जी के लक्षण",
                "care_en": "Avoid the suspected trigger, wash the exposed area, keep rooms dust-free, and use medicines only as advised. Track what causes sneezing, itching, rash, or watery eyes.",
                "care_hi": "संभावित कारण से बचें, संपर्क वाले हिस्से को धोएं, कमरे को धूल-मुक्त रखें और दवा केवल सलाह अनुसार लें। छींक, खुजली, चकत्ते या आंखों से पानी आने के कारणों को नोट करें।",
                "red_en": "Get emergency help for swelling of lips or tongue, breathing difficulty, wheezing, dizziness, fainting, or widespread rapidly increasing rash.",
                "red_hi": "होंठ या जीभ में सूजन, सांस लेने में कठिनाई, घरघराहट, चक्कर, बेहोशी या तेजी से फैलते चकत्ते होने पर तुरंत आपात सहायता लें।"
            },
            {
                "en": "skin rash",
                "hi": "त्वचा पर चकत्ते",
                "care_en": "Keep the area clean, avoid scratching, use mild soap, and identify any new food, medicine, cosmetic, or insect exposure.",
                "care_hi": "प्रभावित हिस्सा साफ रखें, खुजलाने से बचें, हल्का साबुन उपयोग करें और नया भोजन, दवा, सौंदर्य उत्पाद या कीट संपर्क ध्यान में रखें।",
                "red_en": "Consult a doctor if rash is painful, spreading fast, has fever, blisters, pus, facial swelling, breathing difficulty, or appears after a new medicine.",
                "red_hi": "यदि चकत्ते दर्दनाक हों, तेजी से फैलें, बुखार, फफोले, मवाद, चेहरे की सूजन, सांस में दिक्कत या नई दवा के बाद आएं, तो चिकित्सक से मिलें।"
            },
            {
                "en": "itching",
                "hi": "खुजली",
                "care_en": "Avoid hot baths, use mild soap, moisturize dry skin, and avoid scratching. Check for new detergents, foods, medicines, or insect bites.",
                "care_hi": "बहुत गर्म पानी से न नहाएं, हल्का साबुन उपयोग करें, सूखी त्वचा पर मॉइस्चराइज़र लगाएं और खुजलाने से बचें। नए डिटर्जेंट, भोजन, दवा या कीट काटने पर ध्यान दें।",
                "red_en": "See a doctor if itching is severe, widespread, with jaundice, fever, rash, swelling, breathing difficulty, or persists without a clear reason.",
                "red_hi": "यदि खुजली बहुत तेज, पूरे शरीर में, पीलिया, बुखार, चकत्ते, सूजन, सांस में दिक्कत के साथ हो या बिना कारण लंबे समय तक रहे, तो चिकित्सक से मिलें।"
            },
            {
                "en": "eye irritation",
                "hi": "आंखों में जलन",
                "care_en": "Avoid rubbing the eyes, wash hands, rest the eyes, reduce screen use, and rinse with clean water if dust entered. Do not use steroid eye drops without prescription.",
                "care_hi": "आंखें न रगड़ें, हाथ साफ रखें, आंखों को आराम दें, स्क्रीन का उपयोग कम करें और धूल जाने पर साफ पानी से धोएं। बिना पर्चे स्टेरॉयड आई ड्रॉप न डालें।",
                "red_en": "Urgent eye care is needed for severe pain, vision loss, chemical exposure, injury, light sensitivity, thick discharge, or redness with contact lens use.",
                "red_hi": "तेज दर्द, नजर कम होना, रसायन लगना, चोट, रोशनी से परेशानी, गाढ़ा स्राव या कॉन्टैक्ट लेंस के साथ लालिमा हो तो तुरंत नेत्र चिकित्सक से मिलें।"
            },
            {
                "en": "ear pain",
                "hi": "कान दर्द",
                "care_en": "Keep the ear dry, avoid inserting earbuds or oil, and use warm compress outside the ear if comfortable. Note fever, discharge, or hearing changes.",
                "care_hi": "कान सूखा रखें, ईयरबड या तेल अंदर न डालें और सुविधा हो तो कान के बाहर हल्की गर्म सिकाई करें। बुखार, स्राव या सुनाई में बदलाव पर ध्यान दें।",
                "red_en": "See a doctor if ear pain is severe, with discharge, hearing loss, fever, dizziness, swelling behind ear, injury, or in small children.",
                "red_hi": "यदि कान दर्द तेज हो, कान से स्राव, सुनाई कम होना, बुखार, चक्कर, कान के पीछे सूजन, चोट या छोटे बच्चे में दर्द हो, तो चिकित्सक से मिलें।"
            },
            {
                "en": "toothache",
                "hi": "दांत दर्द",
                "care_en": "Rinse the mouth with warm salt water, keep the area clean, avoid very hot or cold foods, and book a dental check-up. Tooth pain usually needs dental evaluation.",
                "care_hi": "गुनगुने नमक-पानी से कुल्ला करें, हिस्सा साफ रखें, बहुत गर्म या ठंडे भोजन से बचें और दंत चिकित्सक से जांच कराएं। दांत दर्द में अक्सर दंत जांच जरूरी होती है।",
                "red_en": "Urgent dental care is needed for facial swelling, fever, pus, difficulty opening mouth, swallowing trouble, trauma, or severe uncontrolled pain.",
                "red_hi": "चेहरे की सूजन, बुखार, मवाद, मुंह खोलने में कठिनाई, निगलने में परेशानी, चोट या बहुत तेज अनियंत्रित दर्द हो तो तुरंत दंत चिकित्सक से मिलें।"
            },
            {
                "en": "mouth ulcer",
                "hi": "मुंह के छाले",
                "care_en": "Avoid spicy and acidic foods, drink enough water, maintain oral hygiene, and eat soft foods. Most small ulcers heal within one to two weeks.",
                "care_hi": "मसालेदार और खट्टे भोजन से बचें, पर्याप्त पानी पिएं, मुख की सफाई रखें और नरम भोजन करें। छोटे छाले अक्सर एक से दो सप्ताह में ठीक हो जाते हैं।",
                "red_en": "See a doctor if ulcers are large, repeated, very painful, last more than two weeks, bleed, occur with fever, weight loss, or difficulty eating.",
                "red_hi": "यदि छाले बड़े, बार-बार, बहुत दर्दनाक, दो सप्ताह से अधिक, खून वाले, बुखार/वजन घटने के साथ या खाने में कठिनाई पैदा करें, तो चिकित्सक से मिलें।"
            },
            {
                "en": "back pain",
                "hi": "पीठ दर्द",
                "care_en": "Avoid bed rest for long periods, use gentle movement, maintain posture, apply heat if helpful, and avoid lifting heavy weight.",
                "care_hi": "लंबे समय तक बिस्तर पर पड़े न रहें, हल्की गति रखें, बैठने-उठने की मुद्रा सही रखें, लाभ हो तो गर्म सिकाई करें और भारी वजन न उठाएं।",
                "red_en": "Seek medical care if back pain follows injury, goes down the leg with weakness, causes numbness in private areas, bladder or bowel issues, fever, weight loss, or severe night pain.",
                "red_hi": "चोट के बाद पीठ दर्द, पैर में कमजोरी के साथ दर्द उतरना, गुप्तांग क्षेत्र में सुन्नपन, पेशाब/मल नियंत्रण की समस्या, बुखार, वजन घटना या रात में बहुत तेज दर्द हो तो चिकित्सक से मिलें।"
            },
            {
                "en": "neck pain",
                "hi": "गर्दन दर्द",
                "care_en": "Rest the neck, avoid long phone bending, use gentle stretches, and apply heat or cold as tolerated. Keep the pillow supportive but not too high.",
                "care_hi": "गर्दन को आराम दें, फोन देखते समय ज्यादा झुकने से बचें, हल्की स्ट्रेचिंग करें और सहन हो तो गर्म या ठंडी सिकाई करें। तकिया सहायक हो पर बहुत ऊंचा न हो।",
                "red_en": "Urgent care is needed if neck pain follows injury, comes with fever, stiff neck, severe headache, arm weakness, numbness, chest pain, or loss of balance.",
                "red_hi": "चोट के बाद गर्दन दर्द, बुखार, गर्दन अकड़ना, तेज सिर दर्द, हाथ में कमजोरी, सुन्नपन, सीने में दर्द या संतुलन बिगड़ना हो तो तुरंत सहायता लें।"
            },
            {
                "en": "knee pain",
                "hi": "घुटने का दर्द",
                "care_en": "Rest from heavy activity, apply cold compress after strain, keep the knee elevated, and do gentle movement if tolerated. Avoid sudden twisting.",
                "care_hi": "भारी गतिविधि से आराम लें, खिंचाव के बाद ठंडी सिकाई करें, घुटना ऊंचा रखें और सहन हो तो हल्की गति करें। अचानक मुड़ने से बचें।",
                "red_en": "See a doctor if there is major swelling, inability to bear weight, deformity, fever, redness, severe injury, locking of knee, or pain that does not improve.",
                "red_hi": "यदि अधिक सूजन, वजन न दे पाना, आकार बिगड़ना, बुखार, लालिमा, गंभीर चोट, घुटना अटकना या सुधार न हो, तो चिकित्सक से मिलें।"
            },
            {
                "en": "ankle sprain",
                "hi": "टखने में मोच",
                "care_en": "Rest the ankle, apply cold compress, use compression if suitable, and keep it elevated. Avoid walking on severe pain.",
                "care_hi": "टखने को आराम दें, ठंडी सिकाई करें, उचित हो तो दबाव पट्टी लगाएं और टखना ऊंचा रखें। तेज दर्द में चलने से बचें।",
                "red_en": "Medical evaluation is needed if you cannot stand, there is severe swelling, deformity, numbness, open wound, repeated sprains, or pain persists after a few days.",
                "red_hi": "यदि खड़े न हो पाएं, बहुत सूजन, आकार बिगड़ना, सुन्नपन, खुला घाव, बार-बार मोच या कुछ दिनों बाद भी दर्द रहे, तो चिकित्सक से जांच कराएं।"
            },
            {
                "en": "muscle cramps",
                "hi": "मांसपेशियों में ऐंठन",
                "care_en": "Gently stretch the affected muscle, massage lightly, drink fluids, and correct dehydration or excessive sweating. Avoid sudden intense exercise.",
                "care_hi": "प्रभावित मांसपेशी को धीरे-धीरे खींचें, हल्की मालिश करें, तरल लें और पानी की कमी या अधिक पसीने को ठीक करें। अचानक तीव्र व्यायाम से बचें।",
                "red_en": "See a doctor if cramps are frequent, severe, linked with swelling, weakness, dark urine, medicine use, kidney disease, or do not improve.",
                "red_hi": "यदि ऐंठन बार-बार, बहुत तेज, सूजन, कमजोरी, गहरे पेशाब, दवा सेवन, गुर्दे की बीमारी या सुधार न होने से जुड़ी हो, तो चिकित्सक से मिलें।"
            },
            {
                "en": "fatigue",
                "hi": "थकान",
                "care_en": "Prioritize sleep, regular meals, hydration, gentle activity, and stress control. Persistent fatigue may be related to anemia, thyroid disease, diabetes, infection, or mental stress.",
                "care_hi": "नींद, नियमित भोजन, पानी, हल्की गतिविधि और तनाव नियंत्रण पर ध्यान दें। लगातार थकान एनीमिया, थायराइड, मधुमेह, संक्रमण या मानसिक तनाव से जुड़ी हो सकती है।",
                "red_en": "Consult a doctor if fatigue is severe, persistent, with weight loss, fever, breathlessness, chest pain, fainting, depression, heavy bleeding, or reduced daily functioning.",
                "red_hi": "यदि थकान बहुत अधिक, लगातार, वजन घटने, बुखार, सांस फूलने, सीने में दर्द, बेहोशी, अवसाद, अधिक रक्तस्राव या रोजमर्रा के काम घटने के साथ हो, तो चिकित्सक से मिलें।"
            },
            {
                "en": "sleep problem",
                "hi": "नींद की समस्या",
                "care_en": "Keep a fixed sleep time, avoid caffeine late in the day, reduce screen use before bed, keep the room dark and quiet, and avoid long daytime naps.",
                "care_hi": "सोने-जागने का समय नियमित रखें, शाम के बाद कैफीन से बचें, सोने से पहले मोबाइल/कंप्यूटर कम करें, कमरा अंधेरा और शांत रखें तथा दिन में लंबी नींद न लें।",
                "red_en": "Seek help if sleeplessness lasts weeks, affects work, occurs with severe anxiety, depression, snoring with choking, daytime sleep attacks, or dependence on sleeping pills.",
                "red_hi": "यदि नींद की समस्या कई सप्ताह रहे, काम प्रभावित करे, तीव्र चिंता/अवसाद, खर्राटे के साथ सांस रुकना, दिन में अचानक नींद आना या नींद की गोलियों पर निर्भरता हो, तो सहायता लें।"
            },
            {
                "en": "anxiety symptoms",
                "hi": "चिंता के लक्षण",
                "care_en": "Slow breathing, grounding exercises, regular sleep, reduced stimulants, and talking to a trusted person can help. Anxiety is treatable and should not be ignored.",
                "care_hi": "धीमी सांस, वर्तमान क्षण पर ध्यान, नियमित नींद, उत्तेजक पदार्थ कम करना और भरोसेमंद व्यक्ति से बात करना मदद कर सकता है। चिंता का उपचार संभव है, इसे नजरअंदाज न करें।",
                "red_en": "Seek urgent help if anxiety comes with chest pain, fainting, suicidal thoughts, inability to function, substance misuse, or repeated panic attacks.",
                "red_hi": "यदि चिंता के साथ सीने में दर्द, बेहोशी, आत्महत्या के विचार, काम न कर पाना, नशे का उपयोग या बार-बार घबराहट के दौरे हों, तो तुरंत सहायता लें।"
            },
            {
                "en": "mild dehydration",
                "hi": "हल्की पानी की कमी",
                "care_en": "Drink water and oral rehydration solution slowly, rest in a cool place, and avoid heavy activity until urine becomes light yellow.",
                "care_hi": "पानी और ओआरएस घोल धीरे-धीरे पिएं, ठंडी जगह आराम करें और पेशाब हल्का पीला होने तक भारी गतिविधि से बचें।",
                "red_en": "Urgent care is needed for confusion, very little urine, extreme weakness, sunken eyes, persistent vomiting, high fever, rapid heartbeat, or dehydration in infants and elderly people.",
                "red_hi": "भ्रम, बहुत कम पेशाब, अत्यधिक कमजोरी, धंसी आंखें, लगातार उल्टी, तेज बुखार, तेज धड़कन या शिशु/बुजुर्ग में पानी की कमी हो तो तुरंत सहायता लें।"
            },
            {
                "en": "heat exhaustion",
                "hi": "गर्मी से थकावट",
                "care_en": "Move to a cool place, loosen clothing, drink oral rehydration solution, apply cool cloths, and rest. Do not return to heat quickly.",
                "care_hi": "ठंडी जगह जाएं, कपड़े ढीले करें, ओआरएस घोल पिएं, ठंडी पट्टियां लगाएं और आराम करें। तुरंत दोबारा गर्मी में न जाएं।",
                "red_en": "Emergency help is needed if body temperature is very high, sweating stops, confusion occurs, fainting, seizures, severe weakness, or symptoms do not improve quickly.",
                "red_hi": "यदि शरीर का तापमान बहुत अधिक हो, पसीना बंद हो जाए, भ्रम, बेहोशी, दौरा, बहुत कमजोरी या लक्षण जल्दी न सुधरें, तो आपात सहायता लें।"
            },
            {
                "en": "menstrual cramps",
                "hi": "मासिक धर्म का दर्द",
                "care_en": "Use a warm compress on the lower abdomen, rest, drink warm fluids, and do gentle stretching if comfortable. Track cycle pattern and pain severity.",
                "care_hi": "निचले पेट पर गर्म सिकाई करें, आराम करें, गर्म तरल लें और सुविधा हो तो हल्की स्ट्रेचिंग करें। चक्र और दर्द की तीव्रता नोट करें।",
                "red_en": "See a gynecologist if pain is severe, new, worsening, associated with very heavy bleeding, fever, foul discharge, dizziness, pregnancy possibility, or pain between periods.",
                "red_hi": "यदि दर्द बहुत तेज, नया, बढ़ता हुआ, बहुत अधिक रक्तस्राव, बुखार, बदबूदार स्राव, चक्कर, गर्भावस्था की संभावना या पीरियड्स के बीच दर्द हो, तो स्त्री रोग विशेषज्ञ से मिलें।"
            },
            {
                "en": "irregular periods",
                "hi": "अनियमित मासिक धर्म",
                "care_en": "Track dates, flow, pain, weight changes, stress, sleep, and exercise. Irregular cycles can be linked to stress, thyroid issues, PCOS, weight change, or medicines.",
                "care_hi": "तारीख, रक्तस्राव, दर्द, वजन परिवर्तन, तनाव, नींद और व्यायाम नोट करें। अनियमित चक्र तनाव, थायराइड, पीसीओएस, वजन बदलने या दवाओं से जुड़ा हो सकता है।",
                "red_en": "Consult a doctor if periods stop for months, bleeding is very heavy, severe pain occurs, pregnancy is possible, cycles are consistently irregular, or there is unusual hair growth or weight gain.",
                "red_hi": "यदि कई महीने मासिक धर्म न आए, रक्तस्राव बहुत अधिक हो, तेज दर्द हो, गर्भावस्था संभव हो, चक्र लगातार अनियमित हों या असामान्य बाल बढ़ना/वजन बढ़ना हो, तो चिकित्सक से मिलें।"
            },
            {
                "en": "urinary burning",
                "hi": "पेशाब में जलन",
                "care_en": "Drink enough water, do not hold urine, maintain hygiene, and avoid irritants. Burning urine may indicate infection and should be watched carefully.",
                "care_hi": "पर्याप्त पानी पिएं, पेशाब न रोकें, स्वच्छता रखें और जलन बढ़ाने वाली चीजों से बचें। पेशाब में जलन संक्रमण का संकेत हो सकती है, इसलिए ध्यान रखें।",
                "red_en": "See a doctor if there is fever, back pain, blood in urine, pregnancy, repeated infection, vomiting, severe pain, or symptoms lasting more than a day or two.",
                "red_hi": "यदि बुखार, कमर/पीठ दर्द, पेशाब में खून, गर्भावस्था, बार-बार संक्रमण, उल्टी, तेज दर्द या एक-दो दिन से अधिक लक्षण रहें, तो चिकित्सक से मिलें।"
            },
            {
                "en": "frequent urination",
                "hi": "बार-बार पेशाब आना",
                "care_en": "Note fluid intake, caffeine use, burning, urgency, night urination, and blood sugar symptoms. Avoid holding urine for long periods.",
                "care_hi": "पानी की मात्रा, कैफीन, जलन, तेज पेशाब की इच्छा, रात में पेशाब और शुगर जैसे लक्षणों को नोट करें। लंबे समय तक पेशाब न रोकें।",
                "red_en": "Medical review is needed if frequent urination comes with excessive thirst, weight loss, fever, burning, blood in urine, pregnancy, bedwetting, or night urination.",
                "red_hi": "यदि बार-बार पेशाब के साथ बहुत प्यास, वजन घटना, बुखार, जलन, पेशाब में खून, गर्भावस्था, बिस्तर गीला होना या रात में बार-बार पेशाब हो, तो जांच कराएं।"
            },
            {
                "en": "mild chest discomfort",
                "hi": "हल्की सीने की असहजता",
                "care_en": "Stop activity, sit comfortably, breathe calmly, and note whether discomfort is related to food, exertion, stress, or position. Do not ignore chest symptoms.",
                "care_hi": "गतिविधि रोकें, आराम से बैठें, शांत सांस लें और देखें कि असहजता भोजन, मेहनत, तनाव या स्थिति से जुड़ी है या नहीं। सीने के लक्षणों को नजरअंदाज न करें।",
                "red_en": "Call emergency care for chest pressure, pain spreading to arm, jaw or back, sweating, breathlessness, fainting, nausea, irregular heartbeat, or symptoms in a person with diabetes, hypertension, or heart disease.",
                "red_hi": "सीने में दबाव, बांह/जबड़े/पीठ तक दर्द, पसीना, सांस फूलना, बेहोशी, मतली, अनियमित धड़कन या मधुमेह/उच्च रक्तचाप/हृदय रोग वाले व्यक्ति में लक्षण हों तो आपात सहायता लें।"
            },
            {
                "en": "breathlessness",
                "hi": "सांस फूलना",
                "care_en": "Sit upright, loosen tight clothing, avoid exertion, and try slow breathing. Breathlessness should be taken seriously, especially if new or worsening.",
                "care_hi": "सीधे बैठें, तंग कपड़े ढीले करें, मेहनत से बचें और धीरे-धीरे सांस लेने की कोशिश करें। सांस फूलना नया या बढ़ता हो तो गंभीरता से लें।",
                "red_en": "Emergency care is needed for severe breathlessness, blue lips, chest pain, wheezing not improving, confusion, fainting, swelling of face, or breathlessness at rest.",
                "red_hi": "बहुत तेज सांस फूलना, होंठ नीले होना, सीने में दर्द, न सुधरती घरघराहट, भ्रम, बेहोशी, चेहरे की सूजन या आराम में भी सांस फूलना हो तो आपात सहायता लें।"
            },
            {
                "en": "palpitations",
                "hi": "दिल की धड़कन तेज महसूस होना",
                "care_en": "Sit down, avoid caffeine or nicotine, drink water, and note duration, triggers, and associated symptoms. Occasional palpitations can occur with stress but need observation.",
                "care_hi": "बैठ जाएं, कैफीन या निकोटिन से बचें, पानी पिएं और अवधि, कारण तथा साथ के लक्षण नोट करें। कभी-कभी तनाव में धड़कन महसूस हो सकती है, लेकिन ध्यान रखना जरूरी है।",
                "red_en": "Urgent care is needed if palpitations come with chest pain, fainting, severe breathlessness, dizziness, known heart disease, very fast pulse, or irregular rhythm lasting longer.",
                "red_hi": "यदि धड़कन के साथ सीने में दर्द, बेहोशी, बहुत सांस फूलना, चक्कर, ज्ञात हृदय रोग, बहुत तेज नाड़ी या लंबे समय तक अनियमित धड़कन हो, तो तुरंत सहायता लें।"
            },
            {
                "en": "high blood pressure reading",
                "hi": "उच्च रक्तचाप की रीडिंग",
                "care_en": "Sit quietly for five minutes and recheck with a proper cuff. Avoid panic, caffeine, exercise, and smoking before repeat measurement. Keep a log of readings.",
                "care_hi": "पांच मिनट शांत बैठकर सही कफ से दोबारा जांचें। दोबारा मापने से पहले घबराहट, कैफीन, व्यायाम और धूम्रपान से बचें। रीडिंग लिखकर रखें।",
                "red_en": "Seek urgent care if blood pressure is very high with chest pain, breathlessness, severe headache, weakness, vision changes, confusion, or pregnancy.",
                "red_hi": "यदि रक्तचाप बहुत अधिक हो और सीने में दर्द, सांस फूलना, तेज सिर दर्द, कमजोरी, नजर बदलना, भ्रम या गर्भावस्था हो, तो तुरंत चिकित्सा सहायता लें।"
            },
            {
                "en": "low blood sugar symptoms",
                "hi": "कम रक्त शर्करा के लक्षण",
                "care_en": "If the person is awake and able to swallow, give a quick sugar source, then a snack or meal. Recheck sugar if a glucometer is available.",
                "care_hi": "यदि व्यक्ति होश में है और निगल सकता है, तो तुरंत मीठा स्रोत दें, फिर हल्का नाश्ता या भोजन दें। ग्लूकोमीटर उपलब्ध हो तो शर्करा दोबारा जांचें।",
                "red_en": "Emergency help is needed if the person is unconscious, confused, having seizures, unable to swallow, or symptoms do not improve after sugar intake.",
                "red_hi": "यदि व्यक्ति बेहोश, भ्रमित, दौरे में, निगलने में असमर्थ हो या मीठा देने के बाद भी सुधार न हो, तो आपात सहायता लें।"
            },
            {
                "en": "high blood sugar symptoms",
                "hi": "उच्च रक्त शर्करा के लक्षण",
                "care_en": "Drink water, avoid sugary foods, follow the prescribed diabetes plan, and check blood sugar if possible. Do not stop medicines without medical advice.",
                "care_hi": "पानी पिएं, मीठे भोजन से बचें, निर्धारित मधुमेह योजना का पालन करें और संभव हो तो रक्त शर्करा जांचें। बिना सलाह दवा बंद न करें।",
                "red_en": "Urgent care is needed for vomiting, abdominal pain, deep breathing, fruity breath, confusion, severe weakness, dehydration, very high readings, or pregnancy.",
                "red_hi": "उल्टी, पेट दर्द, गहरी सांस, सांस में फल जैसी गंध, भ्रम, अत्यधिक कमजोरी, पानी की कमी, बहुत अधिक रीडिंग या गर्भावस्था हो तो तुरंत सहायता लें।"
            },
            {
                "en": "low back stiffness",
                "hi": "कमर में जकड़न",
                "care_en": "Use gentle mobility, avoid prolonged sitting, maintain posture, and apply heat if it relaxes muscles. Start slowly and avoid sudden bending.",
                "care_hi": "हल्की गतिशीलता रखें, लंबे समय तक बैठे रहने से बचें, सही मुद्रा रखें और मांसपेशी ढीली हो तो गर्म सिकाई करें। धीरे शुरू करें और अचानक झुकने से बचें।",
                "red_en": "See a doctor if stiffness follows injury, is associated with fever, leg weakness, numbness, bladder issues, severe night pain, or does not improve.",
                "red_hi": "चोट के बाद जकड़न, बुखार, पैर की कमजोरी, सुन्नपन, पेशाब की समस्या, रात में तेज दर्द या सुधार न हो तो चिकित्सक से मिलें।"
            },
            {
                "en": "indigestion",
                "hi": "अपच",
                "care_en": "Eat slowly, take smaller meals, avoid heavy oily food, walk gently after meals, and do not lie down immediately.",
                "care_hi": "धीरे खाएं, छोटे भोजन लें, भारी तैलीय भोजन से बचें, खाने के बाद हल्की चाल करें और तुरंत न लेटें।",
                "red_en": "Medical review is needed if indigestion is recurrent, with chest pain, vomiting blood, black stool, difficulty swallowing, weight loss, anemia, or persistent vomiting.",
                "red_hi": "यदि अपच बार-बार हो, सीने में दर्द, खून की उल्टी, काला मल, निगलने में कठिनाई, वजन घटना, एनीमिया या लगातार उल्टी हो, तो चिकित्सक से मिलें।"
            },
            {
                "en": "gas and bloating",
                "hi": "गैस और पेट फूलना",
                "care_en": "Eat slowly, avoid carbonated drinks, reduce very spicy or gas-forming foods, walk after meals, and observe which foods trigger bloating.",
                "care_hi": "धीरे खाएं, गैस वाले पेय से बचें, बहुत मसालेदार या गैस बनाने वाले भोजन कम करें, खाने के बाद चलें और कौन सा भोजन पेट फुलाता है, यह नोट करें।",
                "red_en": "See a doctor if bloating is persistent, painful, with vomiting, weight loss, blood in stool, fever, severe constipation, or abdominal swelling.",
                "red_hi": "यदि पेट फूलना लगातार, दर्दनाक, उल्टी, वजन घटने, मल में खून, बुखार, गंभीर कब्ज या पेट में अधिक सूजन के साथ हो, तो चिकित्सक से मिलें।"
            },
            {
                "en": "loss of appetite",
                "hi": "भूख कम लगना",
                "care_en": "Take small frequent meals, drink fluids, choose nutritious soft foods, and watch for stress, fever, medicines, digestive issues, or mood changes.",
                "care_hi": "छोटे-छोटे भोजन बार-बार लें, तरल पिएं, पौष्टिक नरम भोजन चुनें और तनाव, बुखार, दवाओं, पाचन समस्या या मनोदशा बदलाव पर ध्यान दें।",
                "red_en": "Consult a doctor if appetite loss lasts more than a week, causes weight loss, weakness, fever, vomiting, abdominal pain, depression, or occurs in elderly people.",
                "red_hi": "यदि भूख एक सप्ताह से अधिक कम रहे, वजन घटे, कमजोरी, बुखार, उल्टी, पेट दर्द, अवसाद या बुजुर्ग में समस्या हो, तो चिकित्सक से सलाह लें।"
            },
            {
                "en": "mild swelling in feet",
                "hi": "पैरों में हल्की सूजन",
                "care_en": "Elevate the legs, reduce prolonged standing, move ankles gently, and check salt intake. Observe whether swelling is one-sided or both-sided.",
                "care_hi": "पैर ऊंचे रखें, लंबे समय तक खड़े रहने से बचें, टखनों को हल्के हिलाएं और नमक की मात्रा पर ध्यान दें। देखें कि सूजन एक पैर में है या दोनों में।",
                "red_en": "Seek medical care if swelling is one-sided, painful, sudden, with breathlessness, chest pain, pregnancy, kidney or heart disease, redness, fever, or reduced urine.",
                "red_hi": "यदि सूजन एक तरफ, दर्दनाक, अचानक, सांस फूलने, सीने में दर्द, गर्भावस्था, गुर्दे/हृदय रोग, लालिमा, बुखार या पेशाब कम होने के साथ हो, तो चिकित्सक से मिलें।"
            },
            {
                "en": "minor nosebleed",
                "hi": "हल्की नाक से खून आना",
                "care_en": "Sit upright, lean slightly forward, pinch the soft part of the nose for ten minutes, and avoid blowing the nose afterward.",
                "care_hi": "सीधे बैठें, थोड़ा आगे झुकें, नाक के नरम हिस्से को दस मिनट दबाएं और बाद में जोर से नाक न साफ करें।",
                "red_en": "Get medical help if bleeding is heavy, follows injury, lasts more than twenty minutes, occurs repeatedly, or the person takes blood thinners or has high blood pressure.",
                "red_hi": "यदि खून अधिक हो, चोट के बाद हो, बीस मिनट से अधिक चले, बार-बार हो या व्यक्ति रक्त पतला करने की दवा लेता हो/उच्च रक्तचाप हो, तो चिकित्सक से मिलें।"
            },
            {
                "en": "mild weakness",
                "hi": "हल्की कमजोरी",
                "care_en": "Rest, hydrate, eat a balanced meal, and note sleep, stress, recent illness, blood sugar, and menstrual blood loss if relevant.",
                "care_hi": "आराम करें, पानी पिएं, संतुलित भोजन लें और नींद, तनाव, हाल की बीमारी, रक्त शर्करा तथा लागू हो तो मासिक रक्तस्राव पर ध्यान दें।",
                "red_en": "See a doctor if weakness is sudden, one-sided, severe, with chest pain, breathlessness, fainting, fever, confusion, slurred speech, or persistent fatigue.",
                "red_hi": "यदि कमजोरी अचानक, एक तरफ, बहुत अधिक, सीने में दर्द, सांस फूलना, बेहोशी, बुखार, भ्रम, बोलने में गड़बड़ी या लगातार थकान के साथ हो, तो चिकित्सक से मिलें।"
            },
            {
                "en": "mild fever with cough",
                "hi": "खांसी के साथ हल्का बुखार",
                "care_en": "Rest, drink fluids, monitor temperature, cover mouth while coughing, and avoid close contact. Use medicines only as advised, especially in children or elderly people.",
                "care_hi": "आराम करें, तरल लें, तापमान जांचें, खांसते समय मुंह ढकें और निकट संपर्क से बचें। बच्चों या बुजुर्गों में दवा केवल सलाह अनुसार दें।",
                "red_en": "Urgent care is needed for breathing difficulty, chest pain, high fever, bluish lips, confusion, dehydration, blood in sputum, or symptoms worsening.",
                "red_hi": "सांस में दिक्कत, सीने में दर्द, तेज बुखार, होंठ नीले होना, भ्रम, पानी की कमी, बलगम में खून या लक्षण बढ़ने पर तुरंत सहायता लें।"
            },
            {
                "en": "mild skin burn from hot water",
                "hi": "गर्म पानी से हल्का जलना",
                "care_en": "Cool the area under clean running water for ten to twenty minutes, remove tight items, and cover gently. Do not burst blisters.",
                "care_hi": "प्रभावित हिस्से को साफ बहते पानी के नीचे दस से बीस मिनट ठंडा करें, तंग चीजें हटाएं और हल्के से ढकें। फफोले न फोड़ें।",
                "red_en": "Medical help is needed for large burns, deep blisters, burns on face, hands or genitals, infection, severe pain, or burns in children and elderly people.",
                "red_hi": "बड़ा जलना, गहरे फफोले, चेहरा/हाथ/जननांग जलना, संक्रमण, बहुत दर्द या बच्चे/बुजुर्ग में जलना हो तो चिकित्सक से मिलें।"
            },
            {
                "en": "food poisoning symptoms",
                "hi": "खाद्य विषाक्तता के लक्षण",
                "care_en": "Use oral rehydration solution, take small sips often, eat light food when able, and avoid milk, alcohol, oily food, and unsafe leftovers.",
                "care_hi": "ओआरएस घोल लें, बार-बार छोटे घूंट पिएं, संभव हो तो हल्का भोजन करें और दूध, शराब, तैलीय भोजन तथा असुरक्षित बचा हुआ भोजन से बचें।",
                "red_en": "Seek care for blood in stool, high fever, severe dehydration, persistent vomiting, severe abdominal pain, symptoms in pregnancy, infants, elderly people, or weak immunity.",
                "red_hi": "मल में खून, तेज बुखार, गंभीर पानी की कमी, लगातार उल्टी, तेज पेट दर्द, गर्भावस्था, शिशु, बुजुर्ग या कमजोर प्रतिरक्षा में लक्षण हों तो चिकित्सा सहायता लें।"
            },
            {
                "en": "minor insect bite",
                "hi": "कीट के हल्के काटने",
                "care_en": "Wash the area, apply a cold compress, avoid scratching, and keep nails clean. Observe for swelling, pain, or spreading redness.",
                "care_hi": "हिस्से को धोएं, ठंडी सिकाई करें, खुजलाने से बचें और नाखून साफ रखें। सूजन, दर्द या फैलती लालिमा पर ध्यान दें।",
                "red_en": "Emergency help is needed for breathing difficulty, facial swelling, dizziness, widespread hives, severe pain, pus, fever, or suspected poisonous bite.",
                "red_hi": "सांस में दिक्कत, चेहरे की सूजन, चक्कर, पूरे शरीर पर चकत्ते, बहुत दर्द, मवाद, बुखार या विषैले काटने की आशंका हो तो तुरंत सहायता लें।"
            },
            {
                "en": "motion sickness",
                "hi": "यात्रा में जी मिचलाना",
                "care_en": "Sit facing forward, look at the horizon, avoid heavy meals before travel, keep fresh air, and take small sips of water.",
                "care_hi": "आगे की दिशा में बैठें, दूर क्षितिज की ओर देखें, यात्रा से पहले भारी भोजन न करें, ताजी हवा लें और पानी के छोटे घूंट लें।",
                "red_en": "See a doctor if vomiting is persistent, dehydration occurs, dizziness is severe, symptoms occur without travel, or medicines are needed frequently.",
                "red_hi": "यदि उल्टी लगातार हो, पानी की कमी हो, चक्कर बहुत तेज हों, यात्रा के बिना लक्षण हों या बार-बार दवा की जरूरत पड़े, तो चिकित्सक से मिलें।"
            },
            {
                "en": "mild sunburn",
                "hi": "हल्की धूप से जलन",
                "care_en": "Move out of the sun, cool the skin with clean water, drink fluids, and apply a gentle moisturizer. Avoid further sun exposure until healed.",
                "care_hi": "धूप से हटें, साफ पानी से त्वचा ठंडी करें, तरल लें और हल्का मॉइस्चराइज़र लगाएं। ठीक होने तक दोबारा तेज धूप से बचें।",
                "red_en": "Medical care is needed for blistering over large areas, fever, chills, severe pain, dizziness, dehydration, or sunburn in infants.",
                "red_hi": "बड़े हिस्से में फफोले, बुखार, ठंड लगना, बहुत दर्द, चक्कर, पानी की कमी या शिशु में धूप से जलन हो तो चिकित्सक से मिलें।"
            },
            {
                "en": "mild acne",
                "hi": "हल्के मुंहासे",
                "care_en": "Wash the face gently twice daily, avoid squeezing pimples, use non-oily products, and be consistent for several weeks.",
                "care_hi": "चेहरा दिन में दो बार हल्के से धोएं, मुंहासे न दबाएं, तेल-रहित उत्पाद उपयोग करें और कई सप्ताह नियमित रहें।",
                "red_en": "See a dermatologist if acne is painful, cystic, leaving scars, sudden, associated with irregular periods or excessive hair growth, or not improving.",
                "red_hi": "यदि मुंहासे दर्दनाक, गांठदार, दाग छोड़ने वाले, अचानक, अनियमित मासिक धर्म या अधिक बाल बढ़ने से जुड़े हों या सुधार न हो, तो त्वचा विशेषज्ञ से मिलें।"
            },
            {
                "en": "hair fall",
                "hi": "बाल झड़ना",
                "care_en": "Avoid harsh styling, eat protein-rich foods, manage stress, and check for dandruff, anemia, thyroid issues, recent illness, or new medicines.",
                "care_hi": "कठोर हेयर स्टाइलिंग से बचें, प्रोटीनयुक्त भोजन लें, तनाव नियंत्रित करें और रूसी, एनीमिया, थायराइड, हाल की बीमारी या नई दवाओं पर ध्यान दें।",
                "red_en": "Consult a doctor if hair fall is sudden, patchy, severe, with scalp infection, weight loss, menstrual changes, fatigue, or persists for months.",
                "red_hi": "यदि बाल झड़ना अचानक, चकत्तेदार, बहुत अधिक, सिर की त्वचा के संक्रमण, वजन घटने, मासिक बदलाव, थकान या महीनों तक बना रहे, तो चिकित्सक से सलाह लें।"
            }
        ]
        JSON, true);

        $audienceContexts = json_decode(<<<'JSON'
        [
            {
                "en": "in adults",
                "hi": "वयस्कों में",
                "advice_en": "For adults, self-care is reasonable only when symptoms are mild and there are no serious warning signs.",
                "advice_hi": "वयस्कों में घर पर देखभाल तभी उचित है जब लक्षण हल्के हों और कोई गंभीर चेतावनी संकेत न हो।"
            },
            {
                "en": "in senior citizens",
                "hi": "वरिष्ठ नागरिकों में",
                "advice_en": "Older adults can worsen quickly, so monitor symptoms more closely and seek earlier medical advice.",
                "advice_hi": "वरिष्ठ नागरिकों में स्थिति जल्दी बिगड़ सकती है, इसलिए लक्षणों पर अधिक ध्यान दें और जल्दी चिकित्सकीय सलाह लें।"
            },
            {
                "en": "in teenagers",
                "hi": "किशोरों में",
                "advice_en": "In teenagers, hydration, rest, nutrition, and school stress should also be considered.",
                "advice_hi": "किशोरों में पानी, आराम, पोषण और पढ़ाई/तनाव को भी ध्यान में रखें।"
            },
            {
                "en": "in children",
                "hi": "बच्चों में",
                "advice_en": "Children should not be given adult medicines or doses without medical advice. Watch activity, feeding, urination, and alertness.",
                "advice_hi": "बच्चों को बिना सलाह बड़ों की दवा या मात्रा न दें। गतिविधि, भोजन, पेशाब और सतर्कता पर ध्यान दें।"
            },
            {
                "en": "at home",
                "hi": "घर पर",
                "advice_en": "At home, focus on safe supportive care and avoid experimenting with strong medicines or unknown remedies.",
                "advice_hi": "घर पर सुरक्षित सहायक देखभाल करें और तेज दवाओं या अनजान घरेलू उपायों का प्रयोग न करें।"
            },
            {
                "en": "during travel",
                "hi": "यात्रा के दौरान",
                "advice_en": "During travel, prioritize hydration, hygiene, rest breaks, and access to nearby medical help if symptoms worsen.",
                "advice_hi": "यात्रा के दौरान पानी, स्वच्छता, बीच-बीच में आराम और लक्षण बढ़ने पर नजदीकी चिकित्सा सहायता को प्राथमिकता दें।"
            },
            {
                "en": "at night",
                "hi": "रात में",
                "advice_en": "At night, observe whether symptoms disturb sleep, breathing, hydration, or alertness; do not delay emergency care for warning signs.",
                "advice_hi": "रात में देखें कि लक्षण नींद, सांस, पानी की कमी या सतर्कता को प्रभावित कर रहे हैं या नहीं; चेतावनी संकेतों में सुबह तक प्रतीक्षा न करें।"
            },
            {
                "en": "in summer",
                "hi": "गर्मी में",
                "advice_en": "In summer, dehydration and heat exposure can worsen symptoms, so keep fluids and cooling measures in mind.",
                "advice_hi": "गर्मी में पानी की कमी और धूप से लक्षण बढ़ सकते हैं, इसलिए तरल और ठंडक का ध्यान रखें।"
            },
            {
                "en": "in winter",
                "hi": "सर्दियों में",
                "advice_en": "In winter, cold air, low activity, and infections may worsen symptoms; keep warm and maintain ventilation.",
                "advice_hi": "सर्दियों में ठंडी हवा, कम गतिविधि और संक्रमण लक्षण बढ़ा सकते हैं; गर्म रहें और हवा का आवागमन रखें।"
            },
            {
                "en": "during pregnancy",
                "hi": "गर्भावस्था में",
                "advice_en": "During pregnancy, avoid self-medication and contact a doctor sooner, even for symptoms that seem mild.",
                "advice_hi": "गर्भावस्था में स्वयं दवा न लें और हल्के दिखने वाले लक्षणों में भी जल्दी चिकित्सक से सलाह लें।"
            },
            {
                "en": "with diabetes",
                "hi": "मधुमेह होने पर",
                "advice_en": "With diabetes, infections, dehydration, wounds, and appetite changes need careful monitoring along with blood sugar.",
                "advice_hi": "मधुमेह होने पर संक्रमण, पानी की कमी, घाव और भूख में बदलाव के साथ रक्त शर्करा की निगरानी जरूरी है।"
            },
            {
                "en": "with high blood pressure",
                "hi": "उच्च रक्तचाप होने पर",
                "advice_en": "With high blood pressure, avoid random painkillers or decongestants and monitor blood pressure if symptoms feel unusual.",
                "advice_hi": "उच्च रक्तचाप होने पर मनमानी दर्दनिवारक या नाक खोलने वाली दवाएं न लें और असामान्य लक्षणों में रक्तचाप जांचें।"
            }
        ]
        JSON, true);

        $questionTemplates = json_decode(<<<'JSON'
        [
            {
                "en": "What should I do for %s %s?",
                "hi": "%2$s %1$s हो तो क्या करना चाहिए?"
            },
            {
                "en": "How can I manage %s %s?",
                "hi": "%2$s %1$s को कैसे संभालें?"
            },
            {
                "en": "What are safe home care steps for %s %s?",
                "hi": "%2$s %1$s के लिए सुरक्षित घरेलू देखभाल क्या है?"
            },
            {
                "en": "When should I see a doctor for %s %s?",
                "hi": "%2$s %1$s में चिकित्सक को कब दिखाना चाहिए?"
            },
            {
                "en": "Is %s dangerous %s?",
                "hi": "%2$s %1$s कब खतरनाक हो सकता है?"
            },
            {
                "en": "What precautions should I take for %s %s?",
                "hi": "%2$s %1$s में कौन-सी सावधानियां रखनी चाहिए?"
            },
            {
                "en": "What should I avoid during %s %s?",
                "hi": "%2$s %1$s में किन बातों से बचना चाहिए?"
            },
            {
                "en": "Can %s be treated at home %s?",
                "hi": "%2$s %1$s का घर पर ध्यान रखा जा सकता है या नहीं?"
            },
            {
                "en": "What are the warning signs in %s %s?",
                "hi": "%2$s %1$s में चेतावनी संकेत कौन-से हैं?"
            },
            {
                "en": "How long should I wait before consulting a doctor for %s %s?",
                "hi": "%2$s %1$s में चिकित्सक से मिलने से पहले कितनी देर प्रतीक्षा करनी चाहिए?"
            },
            {
                "en": "What first aid is useful for %s %s?",
                "hi": "%2$s %1$s में कौन-सी प्राथमिक चिकित्सा उपयोगी है?"
            },
            {
                "en": "What daily care helps with %s %s?",
                "hi": "%2$s %1$s में रोज़ की देखभाल कैसे करें?"
            },
            {
                "en": "What food and drink choices help during %s %s?",
                "hi": "%2$s %1$s में खान-पान कैसा रखें?"
            },
            {
                "en": "What symptoms along with %s need urgent care %s?",
                "hi": "%2$s %1$s के साथ कौन-से लक्षण तुरंत इलाज मांगते हैं?"
            },
            {
                "en": "How can I reduce discomfort from %s %s?",
                "hi": "%2$s %1$s से होने वाली परेशानी कैसे कम करें?"
            },
            {
                "en": "What should family members watch for in %s %s?",
                "hi": "%2$s %1$s में परिवार वालों को किन बातों पर ध्यान देना चाहिए?"
            },
            {
                "en": "What simple routine can help with %s %s?",
                "hi": "%2$s %1$s में कौन-सी सरल दिनचर्या मदद कर सकती है?"
            },
            {
                "en": "What common mistakes should be avoided in %s %s?",
                "hi": "%2$s %1$s में कौन-सी सामान्य गलतियां नहीं करनी चाहिए?"
            },
            {
                "en": "How should I monitor %s %s?",
                "hi": "%2$s %1$s पर निगरानी कैसे रखें?"
            },
            {
                "en": "What can I do immediately for %s %s?",
                "hi": "%2$s %1$s में तुरंत क्या कर सकते हैं?"
            },
            {
                "en": "What care is needed if %s keeps coming back %s?",
                "hi": "%2$s %1$s बार-बार हो तो क्या देखभाल जरूरी है?"
            },
            {
                "en": "Can lifestyle changes help with %s %s?",
                "hi": "%2$s %1$s में जीवनशैली बदलाव मदद कर सकते हैं या नहीं?"
            },
            {
                "en": "What should I tell the doctor about %s %s?",
                "hi": "%2$s %1$s के बारे में चिकित्सक को क्या जानकारी देनी चाहिए?"
            },
            {
                "en": "How do I know if %s is improving %s?",
                "hi": "%2$s %1$s में सुधार हो रहा है या नहीं, यह कैसे समझें?"
            }
        ]
        JSON, true);

        $records = [];
        $seen = [];

        foreach ($symptomPhrases as $symptom) {
            foreach ($audienceContexts as $context) {
                foreach ($questionTemplates as $template) {
                    $questionEn = sprintf($template['en'], $symptom['en'], $context['en']);
                    $questionHi = sprintf($template['hi'], $symptom['hi'], $context['hi']);

                    // Remove accidental duplicate spacing and keep questions unique.
                    $questionEn = trim(preg_replace('/\s+/', ' ', $questionEn));
                    $questionHi = trim(preg_replace('/\s+/', ' ', $questionHi));

                    if (isset($seen[$questionEn])) {
                        continue;
                    }

                    $answerEn = $symptom['care_en'].' '.$context['advice_en'].' '.$symptom['red_en'];
                    $answerHi = $symptom['care_hi'].' '.$context['advice_hi'].' '.$symptom['red_hi'];

                    $records[] = [
                        'question_en' => $questionEn,
                        'question_hi' => $questionHi,
                        'answer_en' => $answerEn,
                        'answer_hi' => $answerHi,
                        'category' => 'General Medical',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    $seen[$questionEn] = true;
                }
            }
        }

        $insertedCount = 0;
        foreach (array_chunk($records, 1000) as $chunk) {
            $insertedCount += CachedMedicalQuestion::insertOrIgnore($chunk);
        }

        $this->command?->info('Generated '.count($records).' proper Hindi general cached medical Q&A records.');
        $this->command?->info("Inserted {$insertedCount} new cached medical Q&A records successfully.");
    }
}
