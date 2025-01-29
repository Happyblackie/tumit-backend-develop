<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TumitTermsOfUseController extends Controller
{
    
    public function termsOfUse()
    {
        // $terms = LegalPage::where('type', 'terms')->first();

        $data = [
            "status" => "200",
            "url" => "http://localhost/tumit/tumit_terms.html"
        ];

        return response()->json($data, 200);

       // return view('legal.terms', compact('terms')); // A view for the terms of use
       
    }
}
