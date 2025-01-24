<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TumitInvite;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class TumitInviteController extends Controller
{
    public function findTumitInvites($tumitId){
        $tumitInvites  = DB::table('tumit_invites')
        ->join('tumits', 'tumits.id', '=', 'tumit_invites.tumit_id')
        ->select('tumit_invites.id','tumit_invites.name','tumit_invites.phone_number','tumit_invites.is_cancelled','tumit_invites.updated_at as invite_updated_at','tumit_invites.created_at as invite_created_at')        
        ->where('is_cancelled','=',0)
        ->where('tumit_id','=',$tumitId)
        ->orderBy('tumit_invites.created_at', 'desc')
        ->get();
        $data = [
            'status'=>200,
            'tumit_invites'=>$tumitInvites
        ];
        return response()->json($data, 200);
    } 

    public function createTumitInvite(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'tumit_id'=>'required',
            'name'=>'required',
            'phone_number' => 'required'          
        ]);
        $data = [
            'status'=>422,
            'message'=>$validator->messages()
        ];
        if($validator->fails())
        {
            return response()->json($data, 200);
        }else{
            $tumitInvite = new TumitInvite();
            $tumitInvite->tumit_id=$request->tumit_id;
            $tumitInvite->name=$request->name;
            $tumitInvite->phone_number=$request->phone_number;
            $tumitInvite->is_cancelled=0;

            $tumitInvite->save();

            $data = [
                'status'=>201,
                'message'=>"Tumit invite created successfully"
            ];
            return response()->json($data, 200);
        }        
    }

    public function cancelTumitInviteBy($id){
        $tumitInvite = TumitInvite::find($id);
        if($tumitInvite == null){
            $data = [
                'status'=>404,
                'message'=>"Tumit invite not found"
            ];
            return response()->json($data, 200);
        }
        TumitInvite::where('id', $id)->update(array('is_cancelled' => 1));

        $data = [
            'status'=>204,
            'message'=>"Tumit invite cancelled successfully"
        ];
        return response()->json($data, 200);
    }
}
