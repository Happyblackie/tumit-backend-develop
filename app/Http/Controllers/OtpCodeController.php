<?php

namespace App\Http\Controllers;

use App\Models\OtpCode;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class OtpCodeController extends Controller
{
    //
    public function index(Request $request)
    {
        $otps = OtpCode::all();
        $data = [
            'status'=>200,
            'otps'=>$otps
        ];
        return response()->json($data, 200);
    }
    
}
