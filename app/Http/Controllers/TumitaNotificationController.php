<?php

namespace App\Http\Controllers;

use App\Models\TumitaNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class TumitaNotificationController extends Controller
{
    //
    public function index(Request $request)
    {
        $tumitaNotifications= TumitaNotification::all();
        $data = [
            'status'=>200,
            'tumita-notifications'=>$tumitaNotifications
        ];
        return response()->json($data, 200);
    }

    public function findTumitaNotifications($tumitaId){
        $tumitaNotifications = DB::table('tumita_notifications')
        ->select('tumita_notifications.id','tumita_notifications.user_id','tumita_notifications.message','tumita_notifications.is_read','tumita_notifications.updated_at as notification_updated_at','tumita_notifications.created_at as notification_created_at')        
        ->where('user_id','=',$tumitaId)
        ->orderBy('tumita_notifications.created_at', 'desc')
        ->get();
        $data = [
            'status'=>200,
            'tumita_notifications'=>$tumitaNotifications
        ];
        return response()->json($data, 200);        
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'user_id'=>'required',
            'message'=>'required'         
        ]);
        $data = [
            'status'=>422,
            'message'=>$validator->messages()
        ];
        if($validator->fails())
        {
            return response()->json($data, 200);
        }else{
            $tumitaNotification = new TumitaNotification();
            $tumitaNotification->user_id=$request->user_id;
            $tumitaNotification->message=$request->message;

            $tumitaNotification->save();

            $data = [
                'status'=>201,
                'message'=>"Tumita notification created successfully"
            ];
            return response()->json($data, 200);
        }        
    }

    /**
     * No editing notifications
     */

     public function softDelete(Request $request,$id)
     {
         $tumitaNotification = TumitaNotification::find($id);
         if($tumitaNotification == null){
             $data = [
                 'status'=>404,
                 'message'=>"Tumita notification not found"
             ];
             return response()->json($data, 200);
         }
         
         $tumitaNotification->deleted_at = Carbon::now();
 
         $tumitaNotification->save();
 
         $data = [
             'status'=>204,
             'message'=>"Tumita notification deleted successfully"
         ];
         return response()->json($data, 200);       
     }
 
     public function delete($id)
     {
         $tumitaNotification = TumitaNotification::find($id);
         $tumitaNotification->delete();
 
         $data = [
             'status'=>204,
             'message'=>"Tumita notification deleted successfully"
         ];
         return response()->json($data, 200);
     }

}
