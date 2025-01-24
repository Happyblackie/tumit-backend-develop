<?php

namespace App\Http\Controllers;

use App\Models\LegalPage;
use Illuminate\Http\Request;

class TumitLegalController extends Controller
{
    public function termsOfUse()
    {
        $terms = LegalPage::where('type', 'terms')->first();

        $data = [
            "status" => "200",
            "message" => "tumit terms displayed and read as stipulated"
        ];

        return response()->json($data, 200);

       // return view('legal.terms', compact('terms')); // A view for the terms of use
       
    }

    public function privacyPolicy()
    {
        $privacy = LegalPage::where('type', 'privacy')->first();
        $data = [
            "status" => "200",
            "message" => "tumit policy laws displayed and read as stipulated"
        ];

        return response()->json($data, 200);

        //return view('legal.privacy', compact('privacy'));   // A view for the privacy policy
       
    }

   
}
