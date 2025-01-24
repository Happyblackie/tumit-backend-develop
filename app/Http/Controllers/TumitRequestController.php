<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TumitRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\Models\TumitaNotification;
use App\Models\Tumit;
use App\Models\Tumita;

class TumitRequestController extends Controller
{
    public function findTumitRequests($tumitId){
        $tumitInvites  = DB::table('tumit_requests')
        ->join('tumits', 'tumits.id', '=', 'tumit_requests.tumit_id')
        ->select('tumit_requests.id','tumit_requests.tumit_id','tumit_requests.tumita_id','tumit_requests.is_accepted','tumit_requests.is_rejected','tumit_requests.updated_at as request_updated_at','tumit_requests.created_at as request_created_at')  
        ->where('tumit_id','=',$tumitId)
        ->orderBy('tumit_requests.created_at', 'desc')
        ->get();
        $data = [
            'status'=>200,
            'tumit_requests'=>$tumitInvites
        ];
        return response()->json($data, 200);
    } 

    public function createTumitRequest(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'tumit_id'=>'required',
            'tumita_id'=>'required'        
        ]);
        $data = [
            'status'=>422,
            'message'=>$validator->messages()
        ];
        if($validator->fails())
        {
            return response()->json($data, 200);
        }else{
            $tumitRequest = new TumitRequest();
            $tumitRequest->tumit_id=$request->tumit_id;
            $tumitRequest->tumita_id=$request->tumita_id;
            $tumitRequest->is_accepted=0;
            $tumitRequest->is_rejected=0;
            $tumitRequest->is_retracted=0;
            $tumitRequest->save();

            // Create request message to notifify tumit owner
            $tumitaNotification = new TumitaNotification();
            $tumit = Tumit::find($request->tumit_id);
            $tumita = Tumita::find($request->tumita_id);
            $tumitaNotification->user_id=$tumit->user_id;
            $tumitaNotification->message=$tumita->name." has requested to join your tumit: ".$tumit->title;
            $tumitaNotification->save();

            $data = [
                'status'=>201,
                'message'=>"Tumit request created successfully"
            ];
            return response()->json($data, 200);
        }        
    }

    public function rejectTumitRequest($id){
        $tumitInvite = TumitRequest::find($id);
        if($tumitInvite == null){
            $data = [
                'status'=>404,
                'message'=>"Tumit request not found"
            ];
            return response()->json($data, 200);
        }
        if($tumitInvite->is_accepted == 1){
            TumitRequest::where('id', $id)->update(array('is_accepted' => 0));
        }
        TumitRequest::where('id', $id)->update(array('is_rejected' => 1));

        $data = [
            'status'=>204,
            'message'=>"Tumit request rejected successfully"
        ];
        return response()->json($data, 200);
    }

    public function acceptTumitRequest($id){
        $tumitInvite = TumitRequest::find($id);
        if($tumitInvite == null){
            $data = [
                'status'=>404,
                'message'=>"Tumit request not found"
            ];
            return response()->json($data, 200);
        }
        if($tumitInvite->is_rejected == 1){
            TumitRequest::where('id', $id)->update(array('is_rejected' => 0));
        }
        TumitRequest::where('id', $id)->update(array('is_accepted' => 1));

        $data = [
            'status'=>204,
            'message'=>"Tumit request accepted successfully"
        ];
        return response()->json($data, 200);
    }

    public function retractTumitRequest($id){
        $tumitInvite = TumitRequest::find($id);
        if($tumitInvite == null){
            $data = [
                'status'=>404,
                'message'=>"Tumit request not found"
            ];
            return response()->json($data, 200);
        }
        TumitRequest::where('id', $id)->update(array('is_retracted' => 1));

        $data = [
            'status'=>204,
            'message'=>"Tumit request retracted successfully"
        ];
        return response()->json($data, 200);
    }
}
