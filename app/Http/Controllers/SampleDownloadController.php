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
            case 'doctors':
            default:
                $content .= "first_name,last_name,department_name_en,registration_number,medical_council,education_degrees,experience_years,phone,about_en,about_hi\n";
                $content .= "Ramesh,Kumar,Cardiologist,MCI-55412,Medical Council of India,MBBS; MD Cardiology,15,\"+91 9876543210, +91 9988776655\",Expert cardiologist,Expert cardiologist Hindi\n";
                break;
        }

        return response($content, 200, $headers);
    }
}
