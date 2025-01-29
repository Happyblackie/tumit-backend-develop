<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TumitPrivacyPolicyController extends Controller
{
    
    
    public function privacyPolicy()
    {
        // $privacy = LegalPage::where('type', 'privacy')->first();
        $data = [
            "status" => "200",
            "url" => "http://localhost/tumit/tumit_privacy.html"
        ];

        return response()->json($data, 200);

        //return view('legal.privacy', compact('privacy'));   // A view for the privacy policy
       
    }
}
