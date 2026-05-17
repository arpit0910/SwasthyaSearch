<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::departments() as $department) {
            Department::updateOrCreate(
                ['name_en' => $department['name']],
                [
                    'name_en' => $department['name'],
                    'name_hi' => $department['name_hi'] ?? self::hindiNameFor($department['name']),
                    'description_en' => $department['description'],
                    'description_hi' => $department['description_hi'] ?? $department['description'],
                    'is_active' => true,
                ]
            );
        }

        // Ensure any existing doctors have their primary department_id populated from the pivot table
        foreach (\App\Models\Doctor::whereNull('department_id')->get() as $doctor) {
            $firstDept = $doctor->departments()->first();
            if ($firstDept) {
                $doctor->update(['department_id' => $firstDept->id]);
            }
        }
    }

    public static function departments(): array
    {
        return [
            ['name' => 'Addiction Medicine', 'description' => 'Prevention, diagnosis, treatment, and recovery support for substance use disorders.'],
            ['name' => 'Adolescent Medicine', 'description' => 'Health care for teenagers and young adults.'],
            ['name' => 'Allergy and Immunology', 'description' => 'Care for allergies, asthma, immune deficiencies, and hypersensitivity disorders.'],
            ['name' => 'Anesthesiology', 'description' => 'Anesthesia, perioperative medicine, pain control, and critical care support.'],
            ['name' => 'Audiology', 'description' => 'Evaluation and management of hearing and balance disorders.'],
            ['name' => 'Bariatric Medicine', 'description' => 'Medical and surgical care for obesity and weight-related conditions.'],
            ['name' => 'Breast Surgery', 'description' => 'Diagnosis and surgical management of benign and malignant breast disease.'],
            ['name' => 'Cardiac Surgery', 'description' => 'Surgical treatment of heart and great vessel disease.'],
            ['name' => 'Cardiology', 'description' => 'Diagnosis and treatment of heart and vascular system disorders.'],
            ['name' => 'Clinical Genetics', 'description' => 'Evaluation and counseling for inherited and genetic disorders.'],
            ['name' => 'Colorectal Surgery', 'description' => 'Surgical care for colon, rectal, and anal disorders.'],
            ['name' => 'Critical Care Medicine', 'description' => 'Specialized care for life-threatening illness and organ support.'],
            ['name' => 'Dentistry', 'description' => 'Care for teeth, gums, oral health, and dental disease.'],
            ['name' => 'Dermatology', 'description' => 'Diagnosis and treatment of skin, hair, and nail conditions.'],
            ['name' => 'Developmental and Behavioral Pediatrics', 'description' => 'Care for developmental, learning, and behavioral concerns in children.'],
            ['name' => 'Emergency Medicine', 'description' => 'Immediate care for acute illness, injury, and medical emergencies.'],
            ['name' => 'Endocrinology', 'description' => 'Hormonal and metabolic disorders, including diabetes and thyroid disease.'],
            ['name' => 'ENT (Otolaryngology)', 'description' => 'Care for ear, nose, throat, head, and neck disorders.'],
            ['name' => 'Family Medicine', 'description' => 'Comprehensive primary care for people of all ages.'],
            ['name' => 'Gastroenterology', 'description' => 'Digestive system disorders involving the stomach, intestines, liver, and pancreas.'],
            ['name' => 'General Medicine', 'description' => 'Primary care and management of acute and chronic adult illnesses.'],
            ['name' => 'General Surgery', 'description' => 'Surgical care for common abdominal, soft tissue, endocrine, and emergency conditions.'],
            ['name' => 'Geriatrics', 'description' => 'Health care focused on older adults and age-related conditions.'],
            ['name' => 'Gynecology', 'description' => 'Care for women and reproductive system disorders.'],
            ['name' => 'Hematology', 'description' => 'Diagnosis and treatment of blood and bone marrow disorders.'],
            ['name' => 'Hepatology', 'description' => 'Care for liver, gallbladder, biliary tract, and pancreas-related liver disorders.'],
            ['name' => 'Infectious Diseases', 'description' => 'Diagnosis, treatment, and prevention of infections.'],
            ['name' => 'Internal Medicine', 'description' => 'Adult medical care for complex acute and chronic diseases.'],
            ['name' => 'Interventional Cardiology', 'description' => 'Catheter-based diagnosis and treatment of heart and vascular disease.'],
            ['name' => 'Interventional Radiology', 'description' => 'Image-guided minimally invasive procedures.'],
            ['name' => 'Neonatology', 'description' => 'Specialized medical care for newborns, especially premature or critically ill infants.'],
            ['name' => 'Nephrology', 'description' => 'Care for kidney disease, dialysis, and electrolyte disorders.'],
            ['name' => 'Neurology', 'description' => 'Treatment of disorders of the brain, spinal cord, nerves, and muscles.'],
            ['name' => 'Neurosurgery', 'description' => 'Surgical care for brain, spine, nerve, and skull disorders.'],
            ['name' => 'Nuclear Medicine', 'description' => 'Diagnostic imaging and therapy using radioactive tracers.'],
            ['name' => 'Nutrition and Dietetics', 'description' => 'Medical nutrition therapy, diet planning, and nutritional rehabilitation.'],
            ['name' => 'Obstetrics', 'description' => 'Care during pregnancy, childbirth, and the postpartum period.'],
            ['name' => 'Obstetrics and Gynecology', 'description' => 'Women\'s reproductive health, pregnancy care, and childbirth.'],
            ['name' => 'Occupational Medicine', 'description' => 'Health care related to work, workplace hazards, and fitness for duty.'],
            ['name' => 'Oncology', 'description' => 'Diagnosis, staging, and treatment of cancer.'],
            ['name' => 'Ophthalmology', 'description' => 'Medical and surgical eye care and vision evaluation.'],
            ['name' => 'Oral and Maxillofacial Surgery', 'description' => 'Surgery of the mouth, jaws, face, and related structures.'],
            ['name' => 'Orthodontics', 'description' => 'Correction of teeth and jaw alignment.'],
            ['name' => 'Orthopedics', 'description' => 'Care of bones, joints, ligaments, tendons, and the musculoskeletal system.'],
            ['name' => 'Pain Medicine', 'description' => 'Diagnosis and treatment of acute and chronic pain conditions.'],
            ['name' => 'Palliative Medicine', 'description' => 'Symptom relief and supportive care for serious illness.'],
            ['name' => 'Pathology', 'description' => 'Laboratory diagnosis of disease using tissues, cells, and body fluids.'],
            ['name' => 'Pediatric Cardiology', 'description' => 'Heart care for infants, children, and adolescents.'],
            ['name' => 'Pediatric Endocrinology', 'description' => 'Hormonal and growth disorders in children.'],
            ['name' => 'Pediatric Gastroenterology', 'description' => 'Digestive, liver, and nutrition disorders in children.'],
            ['name' => 'Pediatric Neurology', 'description' => 'Brain, nerve, and muscle disorders in children.'],
            ['name' => 'Pediatric Surgery', 'description' => 'Surgical care for infants, children, and adolescents.'],
            ['name' => 'Pediatrics', 'description' => 'Medical care of infants, children, and adolescents.'],
            ['name' => 'Physical Medicine and Rehabilitation', 'description' => 'Restoring function after injury, illness, surgery, or disability.'],
            ['name' => 'Physiotherapy', 'description' => 'Movement, exercise, and rehabilitation therapies.'],
            ['name' => 'Plastic and Reconstructive Surgery', 'description' => 'Reconstructive, restorative, and cosmetic surgical care.'],
            ['name' => 'Podiatry', 'description' => 'Care for foot, ankle, and lower limb disorders.'],
            ['name' => 'Preventive Medicine', 'description' => 'Disease prevention, screening, public health, and risk reduction.'],
            ['name' => 'Psychiatry', 'description' => 'Diagnosis and treatment of mental, emotional, and behavioral disorders.'],
            ['name' => 'Psychology', 'description' => 'Assessment and therapy for emotional, behavioral, and cognitive concerns.'],
            ['name' => 'Pulmonology', 'description' => 'Diseases involving the lungs and respiratory tract.'],
            ['name' => 'Radiation Oncology', 'description' => 'Cancer treatment using radiation therapy.'],
            ['name' => 'Radiology', 'description' => 'Medical imaging for diagnosis and treatment planning.'],
            ['name' => 'Reproductive Endocrinology and Infertility', 'description' => 'Fertility care and reproductive hormone disorders.'],
            ['name' => 'Rheumatology', 'description' => 'Autoimmune, inflammatory, joint, and connective tissue disorders.'],
            ['name' => 'Sleep Medicine', 'description' => 'Diagnosis and treatment of sleep disorders.'],
            ['name' => 'Sports Medicine', 'description' => 'Care for exercise-related injuries, performance, and musculoskeletal health.'],
            ['name' => 'Thoracic Surgery', 'description' => 'Surgery of the lungs, chest wall, esophagus, and mediastinum.'],
            ['name' => 'Transplant Medicine', 'description' => 'Care before and after organ or tissue transplantation.'],
            ['name' => 'Trauma Surgery', 'description' => 'Surgical care for traumatic injuries and emergencies.'],
            ['name' => 'Urology', 'description' => 'Diseases of the urinary tract and male reproductive system.'],
            ['name' => 'Vascular Surgery', 'description' => 'Surgical and endovascular care for artery, vein, and lymphatic disorders.'],
        ];
    }

    public static function hindiNameFor(string $name): string
    {
        return [
            'Addiction Medicine' => 'नशा मुक्ति चिकित्सा',
            'Adolescent Medicine' => 'किशोर चिकित्सा',
            'Allergy and Immunology' => 'एलर्जी और प्रतिरक्षा विज्ञान',
            'Anesthesiology' => 'एनेस्थीसियोलॉजी',
            'Audiology' => 'श्रवण विज्ञान',
            'Bariatric Medicine' => 'बैरिएट्रिक चिकित्सा',
            'Breast Surgery' => 'स्तन शल्य चिकित्सा',
            'Cardiac Surgery' => 'हृदय शल्य चिकित्सा',
            'Cardiology' => 'हृदय रोग विभाग',
            'Clinical Genetics' => 'क्लिनिकल आनुवंशिकी',
            'Colorectal Surgery' => 'कोलोरेक्टल शल्य चिकित्सा',
            'Critical Care Medicine' => 'गहन चिकित्सा',
            'Dentistry' => 'दंत चिकित्सा',
            'Dermatology' => 'त्वचा रोग विभाग',
            'Developmental and Behavioral Pediatrics' => 'विकासात्मक और व्यवहारिक बाल चिकित्सा',
            'Emergency Medicine' => 'आपातकालीन चिकित्सा',
            'Endocrinology' => 'अंतःस्राविकी',
            'ENT (Otolaryngology)' => 'कान, नाक और गला रोग विभाग',
            'Family Medicine' => 'पारिवारिक चिकित्सा',
            'Gastroenterology' => 'पाचन तंत्र रोग विभाग',
            'General Medicine' => 'सामान्य चिकित्सा',
            'General Surgery' => 'सामान्य शल्य चिकित्सा',
            'Geriatrics' => 'वृद्धावस्था चिकित्सा',
            'Gynecology' => 'स्त्री रोग विभाग',
            'Hematology' => 'रक्त रोग विभाग',
            'Hepatology' => 'यकृत रोग विभाग',
            'Infectious Diseases' => 'संक्रामक रोग विभाग',
            'Internal Medicine' => 'आंतरिक चिकित्सा',
            'Interventional Cardiology' => 'इंटरवेंशनल कार्डियोलॉजी',
            'Interventional Radiology' => 'इंटरवेंशनल रेडियोलॉजी',
            'Neonatology' => 'नवजात शिशु चिकित्सा',
            'Nephrology' => 'गुर्दा रोग विभाग',
            'Neurology' => 'तंत्रिका रोग विभाग',
            'Neurosurgery' => 'तंत्रिका शल्य चिकित्सा',
            'Nuclear Medicine' => 'न्यूक्लियर मेडिसिन',
            'Nutrition and Dietetics' => 'पोषण और आहार विज्ञान',
            'Obstetrics' => 'प्रसूति विभाग',
            'Obstetrics and Gynecology' => 'प्रसूति एवं स्त्री रोग विभाग',
            'Occupational Medicine' => 'व्यावसायिक चिकित्सा',
            'Oncology' => 'कैंसर रोग विभाग',
            'Ophthalmology' => 'नेत्र रोग विभाग',
            'Oral and Maxillofacial Surgery' => 'मुख एवं जबड़ा शल्य चिकित्सा',
            'Orthodontics' => 'ऑर्थोडॉन्टिक्स',
            'Orthopedics' => 'हड्डी रोग विभाग',
            'Pain Medicine' => 'दर्द चिकित्सा',
            'Palliative Medicine' => 'प्रशामक चिकित्सा',
            'Pathology' => 'पैथोलॉजी',
            'Pediatric Cardiology' => 'बाल हृदय रोग विभाग',
            'Pediatric Endocrinology' => 'बाल अंतःस्राविकी',
            'Pediatric Gastroenterology' => 'बाल पाचन तंत्र रोग विभाग',
            'Pediatric Neurology' => 'बाल तंत्रिका रोग विभाग',
            'Pediatric Surgery' => 'बाल शल्य चिकित्सा',
            'Pediatrics' => 'बाल रोग विभाग',
            'Physical Medicine and Rehabilitation' => 'भौतिक चिकित्सा एवं पुनर्वास',
            'Physiotherapy' => 'फिजियोथेरेपी',
            'Plastic and Reconstructive Surgery' => 'प्लास्टिक एवं पुनर्निर्माण शल्य चिकित्सा',
            'Podiatry' => 'पैर रोग चिकित्सा',
            'Preventive Medicine' => 'निवारक चिकित्सा',
            'Psychiatry' => 'मनोचिकित्सा',
            'Psychology' => 'मनोविज्ञान',
            'Pulmonology' => 'श्वसन रोग विभाग',
            'Radiation Oncology' => 'रेडिएशन ऑन्कोलॉजी',
            'Radiology' => 'रेडियोलॉजी',
            'Reproductive Endocrinology and Infertility' => 'प्रजनन अंतःस्राविकी और बांझपन',
            'Rheumatology' => 'रूमेटोलॉजी',
            'Sleep Medicine' => 'नींद चिकित्सा',
            'Sports Medicine' => 'खेल चिकित्सा',
            'Thoracic Surgery' => 'वक्ष शल्य चिकित्सा',
            'Transplant Medicine' => 'प्रत्यारोपण चिकित्सा',
            'Trauma Surgery' => 'ट्रॉमा शल्य चिकित्सा',
            'Urology' => 'मूत्र रोग विभाग',
            'Vascular Surgery' => 'रक्तवाहिनी शल्य चिकित्सा',
        ][$name];
    }
}
