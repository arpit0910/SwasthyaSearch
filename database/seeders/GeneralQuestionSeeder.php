<?php

namespace Database\Seeders;

use App\Models\GeneralQuestion;
use Illuminate\Database\Seeder;

class GeneralQuestionSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'question_en' => 'I have fever, what should I do?',
                'question_hi' => 'Mujhe bukhar hai, kya karun?',
                'answer_en' => 'Rest, hydrate, and monitor temperature. Consult doctor if high fever persists.',
                'answer_hi' => 'Aaram karein, paani piyen, aur temperature monitor karein. Tez bukhar rahe to doctor se milen.',
                'detailed_answer_en' => 'Take fluids and light diet. Seek urgent care for breathing trouble, confusion, or persistent high fever.',
                'detailed_answer_hi' => 'Taral padarth lete rahen. Saans ki dikkat, uljhan, ya lagatar tez bukhar ho to turant ilaaj len.',
            ],
            [
                'question_en' => 'I have cough and cold, what can I do?',
                'question_hi' => 'Mujhe khansi-jukam hai, kya karun?',
                'answer_en' => 'Take rest, warm fluids, and steam inhalation.',
                'answer_hi' => 'Aaram karein, garam taral padarth lein, aur bhaap lein.',
                'detailed_answer_en' => 'If high fever, chest pain, or shortness of breath occurs, consult doctor quickly.',
                'detailed_answer_hi' => 'Tez bukhar, seene mein dard, ya saans ki dikkat ho to jaldi doctor se milen.',
            ],
            [
                'question_en' => 'I have frequent headache, what should I do?',
                'question_hi' => 'Mujhe bar-bar sir dard hota hai, kya karun?',
                'answer_en' => 'Hydrate, reduce screen strain, and sleep properly.',
                'answer_hi' => 'Paani piyen, screen strain kam karein, aur puri neend len.',
                'detailed_answer_en' => 'Seek urgent care for sudden severe headache, vomiting, weakness, or vision changes.',
                'detailed_answer_hi' => 'Achanak tez sir dard, ulti, kamzori, ya nazar badalne par turant doctor se milen.',
            ],
            [
                'question_en' => 'I have acidity, what should I do?',
                'question_hi' => 'Mujhe acidity hai, kya karun?',
                'answer_en' => 'Avoid spicy/oily food and eat small frequent meals.',
                'answer_hi' => 'Masaledar aur teliya khana kam karein, thoda-thoda karke khayen.',
                'detailed_answer_en' => 'Consult doctor for persistent pain, vomiting, black stool, or weight loss.',
                'detailed_answer_hi' => 'Lagatar dard, ulti, kala stool, ya weight loss ho to doctor se milen.',
            ],
            [
                'question_en' => 'I have loose motions, what should I do?',
                'question_hi' => 'Mujhe dast hain, kya karun?',
                'answer_en' => 'Take ORS, drink fluids, and eat light food.',
                'answer_hi' => 'ORS lein, paani piyen, aur halka khana khayen.',
                'detailed_answer_en' => 'Seek care if blood in stool, severe weakness, very low urine, or persistent vomiting.',
                'detailed_answer_hi' => 'Stool mein khoon, bahut kamzori, kam peshab, ya lagatar ulti ho to turant doctor se milen.',
            ],
            [
                'question_en' => 'My blood pressure is high, what should I do now?',
                'question_hi' => 'Mera BP high hai, abhi kya karun?',
                'answer_en' => 'Sit calmly, rest, and recheck BP after a few minutes.',
                'answer_hi' => 'Shant baithen, aaram karein, aur kuch der baad BP dobara check karein.',
                'detailed_answer_en' => 'Emergency signs: chest pain, breathlessness, severe headache, weakness/speech issues.',
                'detailed_answer_hi' => 'Emergency signs: seene ka dard, saans ki dikkat, tez sir dard, bolne ya chalne mein dikkat.',
            ],
            [
                'question_en' => 'My sugar is low or high, what should I do?',
                'question_hi' => 'Meri sugar low ya high ho gayi hai, kya karun?',
                'answer_en' => 'Check glucose immediately. Low sugar: take fast carbs. High sugar: consult doctor soon.',
                'answer_hi' => 'Turant glucose check karein. Low sugar mein meetha lein. High sugar mein doctor se jaldi milen.',
                'detailed_answer_en' => 'If vomiting, confusion, drowsiness, or unconsciousness appears, seek emergency care.',
                'detailed_answer_hi' => 'Ulti, uljhan, zyada neend, ya behoshi ho to emergency care len.',
            ],
            [
                'question_en' => 'What to do in dehydration?',
                'question_hi' => 'Dehydration mein kya karein?',
                'answer_en' => 'Start ORS and fluids immediately.',
                'answer_hi' => 'Turant ORS aur paani lena shuru karein.',
                'detailed_answer_en' => 'Watch for dry mouth, dizziness, low urine. Seek care if unable to drink fluids.',
                'detailed_answer_hi' => 'Muh sukhna, chakkar, kam peshab par dhyan dein. Taral na le pa rahe hon to doctor se milen.',
            ],
            [
                'question_en' => 'What to do for minor burn first aid?',
                'question_hi' => 'Halki jalne par first-aid kya karein?',
                'answer_en' => 'Cool under running water for 10-20 minutes. Do not apply toothpaste or oil.',
                'answer_hi' => '10-20 minute tak bahte paani se thanda karein. Toothpaste ya tel na lagayen.',
                'detailed_answer_en' => 'Cover with clean dressing. Do not burst blisters. Seek doctor for deep/large burns.',
                'detailed_answer_hi' => 'Saaf dressing karein. Chhale na phoden. Gehari ya badi jalne par doctor se milen.',
            ],
            [
                'question_en' => 'When should I go to emergency immediately?',
                'question_hi' => 'Mujhe kab turant emergency jana chahiye?',
                'answer_en' => 'Go immediately for chest pain, breathing difficulty, stroke symptoms, severe bleeding, or unconsciousness.',
                'answer_hi' => 'Seene ka dard, saans ki dikkat, stroke signs, zyada bleeding, ya behoshi mein turant emergency jayen.',
                'detailed_answer_en' => 'Do not delay red-flag symptoms. Call emergency services and go to nearest emergency facility.',
                'detailed_answer_hi' => 'Red-flag symptoms mein der na karein. Emergency services ko call karein aur najdeeki emergency facility jayen.',
            ],
            [
                'question_en' => 'I have vomiting, what should I do?',
                'question_hi' => 'Mujhe ulti ho rahi hai, kya karun?',
                'answer_en' => 'Take small sips of ORS/water and rest. Avoid oily/spicy food.',
                'answer_hi' => 'ORS/paani chhote ghunto mein lein aur aaram karein. Teliya-masaledar khana na khayen.',
                'detailed_answer_en' => 'Seek care if vomiting is persistent, contains blood, or causes dehydration.',
                'detailed_answer_hi' => 'Lagatar ulti, ulti mein khoon, ya dehydration ke lakshan ho to doctor se milen.',
            ],
            [
                'question_en' => 'I have sore throat, what should I do?',
                'question_hi' => 'Gale mein dard hai, kya karun?',
                'answer_en' => 'Drink warm fluids, do salt-water gargles, and rest your voice.',
                'answer_hi' => 'Garam taral padarth lein, namak paani se gargle karein, aur awaaz ko aaram dein.',
                'detailed_answer_en' => 'If high fever, swallowing difficulty, or symptoms >3 days, consult a doctor.',
                'detailed_answer_hi' => 'Tez bukhar, nigalne mein dikkat, ya 3 din se jyada lakshan ho to doctor se milen.',
            ],
            [
                'question_en' => 'I have burning urination, what should I do?',
                'question_hi' => 'Peshab mein jalan hai, kya karun?',
                'answer_en' => 'Increase water intake and do not hold urine.',
                'answer_hi' => 'Paani zyada piyen aur peshab rok kar na rakhen.',
                'detailed_answer_en' => 'If fever, back pain, blood in urine, or frequent urgency occurs, seek medical evaluation for UTI.',
                'detailed_answer_hi' => 'Bukhar, kamar dard, peshab mein khoon, ya bar-bar urgency ho to UTI ki jaanch karayen.',
            ],
            [
                'question_en' => 'I have lower back pain, what should I do?',
                'question_hi' => 'Kamar dard hai, kya karun?',
                'answer_en' => 'Take short rest, avoid heavy lifting, and use gentle stretching.',
                'answer_hi' => 'Thoda aaram karein, bhaari saman na uthayen, aur halka stretching karein.',
                'detailed_answer_en' => 'Seek urgent care if weakness, numbness, bladder/bowel issues, or trauma is present.',
                'detailed_answer_hi' => 'Kamzori, sunnpan, peshab/mal control issue, ya chot ho to turant doctor se milen.',
            ],
            [
                'question_en' => 'I have skin allergy/rash, what should I do?',
                'question_hi' => 'Skin allergy/rash hai, kya karun?',
                'answer_en' => 'Avoid possible trigger and keep skin clean and dry.',
                'answer_hi' => 'Possible trigger se bachain aur skin saaf-sukhi rakhen.',
                'detailed_answer_en' => 'If face/lip swelling or breathing trouble occurs, seek urgent care immediately.',
                'detailed_answer_hi' => 'Chehre/hoonth sujan ya saans ki dikkat ho to turant emergency care len.',
            ],
            [
                'question_en' => 'I am unable to sleep, what should I do?',
                'question_hi' => 'Neend nahi aa rahi, kya karun?',
                'answer_en' => 'Keep fixed sleep timing, avoid caffeine at night, and reduce screen time before bed.',
                'answer_hi' => 'Niyamit sleep timing rakhen, raat ko caffeine se bachain, aur bed se pehle screen time kam karein.',
                'detailed_answer_en' => 'If insomnia persists for weeks or affects daily life, consult a doctor.',
                'detailed_answer_hi' => 'Agar ye kai hafton tak rahe ya daily life prabhavit ho, to doctor se salah len.',
            ],
            [
                'question_en' => 'Should I take antibiotics on my own?',
                'question_hi' => 'Kya main khud se antibiotics le sakta/sakti hoon?',
                'answer_en' => 'No. Avoid self-medication with antibiotics without doctor advice.',
                'answer_hi' => 'Nahi. Doctor ki salah ke bina antibiotics khud se na lein.',
                'detailed_answer_en' => 'Incorrect use may cause resistance and side effects. Complete only prescribed course.',
                'detailed_answer_hi' => 'Galat use se resistance aur side effects ho sakte hain. Sirf prescribed course complete karein.',
            ],
        ];

        foreach ($items as $item) {
            GeneralQuestion::updateOrCreate(
                ['question_en' => $item['question_en']],
                $item
            );
        }
    }
}

