<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SampleDownloadController extends Controller
{
    public function download(Request $request)
    {
        $type = $request->query('type', 'doctors');
        $filename = "sample_{$type}_template.csv";

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $content = "";

        switch ($type) {
            case 'departments':
                $content .= "name_en,name_hi,description_en,description_hi\n";
                $content .= "Cardiology,Cardiology Hindi,Heart specialist department,Heart specialist department Hindi\n";
                break;
            case 'diseases':
                $content .= "name_en,name_hi,department_name_en,symptoms_en,symptoms_hi\n";
                $content .= "Angina,Angina Hindi,Cardiology,\"chest pain; shortness of breath; sweating\",\"chest pain hindi; shortness of breath hindi; sweating hindi\"\n";
                break;
            case 'hospitals':
                $content .= "name_en,name_hi,type,address,city,emergency_phone,latitude,longitude\n";
                $content .= "Apollo Hospital,Apollo Hospital Hindi,Hospital,Greams Road,Chennai,\"+91 44 2829 0200, +91 44 2829 0300\",13.0604,80.2496\n";
                break;
            case 'medicines':
                $content .= "name,slug,generic_name,brand_names_json,composition,strength,medicine_type,category,prescription_required,purpose_en,purpose_hi,overview_en,overview_hi,review_status,is_published\n";
                $content .= "Paracetamol,paracetamol,Acetaminophen,\"[\"\"Dolo 650\"\",\"\"Crocin\"\"]\",Paracetamol IP,650 mg,Tablet,Pain relief,0,Fever reduction,बुखार कम करने के लिए,Commonly used for fever,बुखार में आमतौर पर उपयोग,published,1\n";
                break;
            case 'articles':
                $content .= "id,title_en,title_hi,excerpt_en,excerpt_hi,content_en,content_hi,category,author_name,is_published\n";
                $content .= "1,Understanding Heart Health,Heart Health Hindi,\"A patient-friendly overview of heart health.\",\"Heart health hindi excerpt\",\"Heart health article body\",\"Heart health article body hindi\",Cardiology,Arogio Team,1\n";
                break;
            case 'doctors':
            default:
                $content .= "doctor_hospital_link_id,doctor_id,hospital_id,registration_number,first_name,last_name,department_name_en,department_name_hi,medical_council,country_code_1,country_code_2,phone_1,phone_2,consultation_fee,experience_years,education_degrees,about_en,about_hi,email,website,city,state,pincode,address_line1,address_line2,landmark,languages_spoken,gender,is_verified,latitude,longitude,hospital_name,hospital_city,doctor_hospital_role,consultation_mode,availability,days_of_week,start_time,end_time,hospital_consultation_fee\n";
                $content .= "LINK-JPR-001,,12,MCI-55412,Ramesh,Kumar,Cardiology,हृदय रोग विभाग,Medical Council of India,+91,,9876543210,,800,15,\"MBBS; MD Cardiology\",Expert cardiologist,,doctor@example.com,,Jaipur,Rajasthan,302001,Malviya Nagar,,,Hindi; English,male,1,26.8467,75.8497,Apex Heart Hospital,Jaipur,Consultant,In-person,\"Mon-Fri OPD\",Mon-Fri,10:00,13:00,900\n";
                break;
        }

        return response($content, 200, $headers);
    }
}
