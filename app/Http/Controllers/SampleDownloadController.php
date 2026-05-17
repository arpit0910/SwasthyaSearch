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
                $content .= "Cardiology,हृदय रोग विभाग,Heart specialist department,हृदय विशेषज्ञ\n";
                break;
            case 'diseases':
                $content .= "name_en,name_hi,department_name_en\n";
                $content .= "Chest Pain,छाती में दर्द,Cardiologist\n";
                break;
            case 'hospitals':
                $content .= "name_en,name_hi,type,address,city,emergency_phone,latitude,longitude\n";
                $content .= "Apollo Hospital,अपोलो अस्पताल,Hospital,Greams Road,Chennai,+91 44 2829 0200,13.0604,80.2496\n";
                break;
            case 'doctors':
            default:
                $content .= "first_name,last_name,department_name_en,registration_number,medical_council,education_degrees,experience_years,about_en,about_hi\n";
                $content .= "Ramesh,Kumar,Cardiologist,MCI-55412,Medical Council of India,MBBS; MD Cardiology,15,Expert cardiologist,विशेषज्ञ कार्डियोलॉजिस्ट\n";
                break;
        }

        return response($content, 200, $headers);
    }
}
