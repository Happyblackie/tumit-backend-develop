<?php

namespace App\Http\Controllers;

use App\Models\Help;
use Illuminate\Http\Request;

class HelpController extends Controller
{
    //
    public function index()
    {
        // Get all help features
        $helpFeatures = Help::all();
        $data = [
            "status" => 200,
            "helpFeatures" => $helpFeatures
        ];

        // Return the list of help features as a JSON response
        return response()->json( $data, 200);
    }

    public function show($id)
    {
        // Get a single help feature by ID
        $helpFeature = Help::find($id);

    
        $data = [
            "status" => 200,
            "helpFeature" => $helpFeature
        ];
    
        return response()->json($data, 200);
        
       
        
    }
}
