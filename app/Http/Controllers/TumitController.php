<?php

namespace App\Http\Controllers;

use App\Models\Tumit;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class TumitController extends Controller
{
    //
    public function index(Request $request)
    {
        // $tumits = Tumit::all();        
        $tumits  = DB::table('tumits')
        ->join('tumitas', 'tumitas.id', '=', 'tumits.user_id')
        ->select('tumits.id','title','banner','description','tags','place','cost','target_date_time','expected_people','tumits.status','tumitas.id as tumita_id',
        'tumitas.name as tumita_name','prof_pic','tumitas.user_status','tumits.updated_at as tumit_updated_at','tumits.created_at as tumit_created_at')
        ->orderBy('tumits.created_at', 'desc')
        ->get();
        $data = [
            'status'=>200,
            'tumits'=>$tumits
        ];
        return response()->json($data, 200);
    }

    public function tumitsByTumita(Request $request,$id){
        $tumits = Tumit::where('user_id','=',$id)
        ->join('tumitas', 'tumitas.id', '=', 'tumits.user_id')
        ->select('tumits.id','title','banner','description','tags','place','cost','target_date_time','expected_people','tumits.status','tumitas.id as tumita_id',
        'tumitas.name as tumita_name','prof_pic','tumitas.user_status','tumits.deleted_at as tumit_deleted_at','tumits.updated_at as tumit_updated_at','tumits.created_at as tumit_created_at')
        ->orderBy('tumits.created_at', 'desc')        
        ->get();
        $data = [
            'status'=>200,
            'tumits'=>$tumits
        ];
        return response()->json($data, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'title'=>'required',
            'banner' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tags'=>'required',
            'description'=>'required',
            'place'=>'required',
            'target_date_time'=>'required'           
        ]);
        $data = [
            'status'=>422,
            'message'=>$validator->messages()
        ];
        if($validator->fails())
        {
            return response()->json($data, 200);
        }else{
            $imageName = time().'.'.$request->banner->extension();
            $request->banner->move(public_path('banners'), $imageName);
            $tumit = new Tumit();
            $tumit->user_id=$request->user_id;
            $tumit->title=$request->title;
            $tumit->banner = 'banners/'.$imageName;
            $tumit->tags=$request->tags;
            $tumit->description=$request->description;
            $tumit->place=$request->place;
            $tumit->cost=$request->cost;            
            $tumit->target_date_time=$request->target_date_time;
            $tumit->expected_people=$request->expected_people;
            $tumit->status=$request->status;

            $tumit->save();

            $data = [
                'status'=>201,
                'message'=>"Tumit created successfully"
            ];
            return response()->json($data, 200);
        }        
    }
    
    public function edit(Request $request,$id)
    {
        $validator = Validator::make($request->all(),
        [
            'title'=>'required',
            'tags'=>'required',
            'description'=>'required',
            'place'=>'required',
            'target_date_time'=>'required'           
        ]);
        $data = [
            'status'=>422,
            'message'=>$validator->messages()
        ];
        if($validator->fails())
        {
            return response()->json($data, 200);
        }else{
            $tumit = Tumit::find($id);
            if($tumit == null){
                $data = [
                    'status'=>404,
                    'message'=>"Tumit not found"
                ];
                return response()->json($data, 200);
            }
            $tumit->title=$request->title;
            $tumit->tags=$request->tags;
            $tumit->description=$request->description;
            $tumit->place=$request->place;
            $tumit->cost=$request->cost;
            //$tumit->target_date_time=Carbon::parse($request->target_date_time)->toDateTimeString();
            $tumit->target_date_time=Carbon::createFromFormat('Y-m-d H:i:s', $request->target_date_time)->timestamp;

            $tumit->expected_people=$request->expected_people;
            $tumit->status=$request->status;

            $tumit->save();

            $data = [
                'status'=>204,
                'message'=>"Tumit updated successfully"
            ];
            return response()->json($data, 200);
        }        
    }

    public function softDelete(Request $request,$id)
    {
        $tumit = Tumit::find($id);
        if($tumit == null){
            $data = [
                'status'=>404,
                'message'=>"Tumit not found"
            ];
            return response()->json($data, 200);
        }
        
        $tumit->deleted_at = Carbon::now();

        $tumit->save();

        $data = [
            'status'=>204,
            'message'=>"Tumit deleted successfully"
        ];
        return response()->json($data, 200);       
    }

    public function delete($id)
    {
        $tumit = Tumit::find($id);
        $tumit->delete();

        $data = [
            'status'=>204,
            'message'=>"Tumit deleted successfully"
        ];
        return response()->json($data, 200);
    }

    //search tumit
    public function search($id)
    {  
        // search tumit by title,tags,description,place or by character set
        $tumitSearchResult= Tumit::where('title',"like","%". $id."%")
                        ->orWhere('tags', "like","%".$id."%")
                        ->orWhere('description',"like","%".$id."%")
                        ->orWhere('place',"like","%".$id."%")->get();
        
            $data = [
                "status" => 200,
                "tumit" => $tumitSearchResult
            ];

         return response()->json($data, 200);


    }
}
