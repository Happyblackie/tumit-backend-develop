<?php

namespace App\Http\Controllers;

use App\Models\Help;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TumitHelpController extends Controller
{
    public function index()
    {
        // Get all help features
        $helpFeatures = Help::all();
        $data = [
            "status" => 200,
            // "helpFeatures" => $helpFeatures
            "url" => "http://localhost/tumit/tumit_help.html"
        ];

        // Return the list of help features as a JSON response
        return response()->json( $data, 200, );
    }

    
}
