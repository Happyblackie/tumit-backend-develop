<?php

namespace App\Http\Controllers;

use App\Models\TumitaActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class TumitaActivityController extends Controller
{
    //
    public function index(Request $request)
    {
        $tumitaActivities = TumitaActivity::all();
        $data = [
            'status'=>200,
            'tumita-activities'=>$tumitaActivities
        ];
        return response()->json($data, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'user_id'=>'required',
            'action'=>'required'         
        ]);
        $data = [
            'status'=>422,
            'message'=>$validator->messages()
        ];
        if($validator->fails())
        {
            return response()->json($data, 200);
        }else{
            $tumitaActivity = new TumitaActivity();
            $tumitaActivity->user_id=$request->user_id;
            $tumitaActivity->action=$request->action;

            $tumitaActivity->save();

            $data = [
                'status'=>201,
                'message'=>"Tumita activity created successfully"
            ];
            return response()->json($data, 200);
        }        
    }

    /**
     * No editing activities
     */

     public function softDelete(Request $request,$id)
     {
         $tumitaActivity = TumitaActivity::find($id);
         if($tumitaActivity == null){
             $data = [
                 'status'=>404,
                 'message'=>"Tumita activity not found"
             ];
             return response()->json($data, 200);
         }
         
         $tumitaActivity->deleted_at = Carbon::now();
 
         $tumitaActivity->save();
 
         $data = [
             'status'=>204,
             'message'=>"Tumita activity deleted successfully"
         ];
         return response()->json($data, 200);       
     }
 
     public function delete($id)
     {
         $tumitaActivity = TumitaActivity::find($id);
         $tumitaActivity->delete();
 
         $data = [
             'status'=>204,
             'message'=>"Tumita activity deleted successfully"
         ];
         return response()->json($data, 200);
     } 
}
