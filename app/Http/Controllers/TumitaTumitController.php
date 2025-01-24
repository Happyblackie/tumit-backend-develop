<?php

namespace App\Http\Controllers;

use App\Models\TumitaTumit;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class TumitaTumitController extends Controller
{
    //
    public function index(Request $request)
    {
        $tumitaTumits = TumitaTumit::all();
        $data = [
            'status'=>200,
            'tumita-tumits'=>$tumitaTumits
        ];
        return response()->json($data, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'user_id'=>'required',
            'tumit_id'=>'required',
            'status'=>'required'         
        ]);
        $data = [
            'status'=>422,
            'message'=>$validator->messages()
        ];
        if($validator->fails())
        {
            return response()->json($data, 200);
        }else{
            $tumitaTumit = new TumitaTumit();
            $tumitaTumit->user_id=$request->user_id;
            $tumitaTumit->tumit_id=$request->tumit_id;
            $tumitaTumit->status=$request->status;

            $tumitaTumit->save();

            $data = [
                'status'=>201,
                'message'=>"Tumita tumit created successfully"
            ];
            return response()->json($data, 200);
        }        
    }

    public function edit(Request $request,$id)
    {
        $validator = Validator::make($request->all(),
        [
            'user_id'=>'required',
            'tumit_id'=>'required',
            'status'=>'required'           
        ]);
        $data = [
            'status'=>422,
            'message'=>$validator->messages()
        ];
        if($validator->fails())
        {
            return response()->json($data, 200);
        }else{
            $tumitaTumit = TumitaTumit::find($id);
            if($tumitaTumit == null){
                $data = [
                    'status'=>404,
                    'message'=>"Tumita tumit not found"
                ];
                return response()->json($data, 200);
            }
            $tumitaTumit->user_id=$request->user_id;
            $tumitaTumit->tumit_id=$request->tumit_id;
            $tumitaTumit->status=$request->status;

            $tumitaTumit->save();

            $data = [
                'status'=>204,
                'message'=>"Tumita tumit updated successfully"
            ];
            return response()->json($data, 200);
        }        
    }

    public function softDelete(Request $request,$id)
    {
        $tumitaTumit = TumitaTumit::find($id);
        if($tumitaTumit == null){
            $data = [
                'status'=>404,
                'message'=>"Tumita tumit not found"
            ];
            return response()->json($data, 200);
        }
        
        $tumitaTumit->deleted_at = Carbon::now();

        $tumitaTumit->save();

        $data = [
            'status'=>204,
            'message'=>"Tumita tumit deleted successfully"
        ];
        return response()->json($data, 200);       
    }

    public function delete($id)
    {
        $tumitaTumit = TumitaTumit::find($id);
        $tumitaTumit->delete();

        $data = [
            'status'=>204,
            'message'=>"Tumita tumit deleted successfully"
        ];
        return response()->json($data, 200);
    }
}
