<?php

namespace App\Http\Controllers;

use App\Models\Tumita;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\TumitaNotification;

class TumitaController extends Controller
{
    /**
     * The table associated with the model.
     *
     * @var string
     */

    // public function __construct()
    // {
    //      $this->middleware('auth:api');
    // } 

    public function index(Request $request)
    {
        $tumitas = Tumita::all();
        $data = [
            'status'=>200,
            'tumitas'=>$tumitas
        ];
        return response()->json($data, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'name'=>'required',
            'phone'=>'required',
            'email'=>'required',
            'password'=>'required'         
        ]);
        $data = [
            'status'=>422,
            'message'=>$validator->messages()
        ];
        if($validator->fails())
        {
            return response()->json($data, 200);
        }else{
            // $imageName = time().'.'.$request->prof_pic->extension();
            // $request->prof_pic->move(public_path('profiles'), $imageName);
            $tumita = new Tumita();
            $tumita->name=$request->name;
            // $tumita->prof_pic = 'profiles/'.$imageName;
            $tumita->prof_pic = "";
            $tumita->user_status = "";
            $tumita->phone=$request->phone;
            $tumita->email=$request->email;
            $tumita->password=$request->password;
            $tumita->save();

            // Create welcome message for recently signed tumita
            $tumitaNotification = new TumitaNotification();
            $tumitaNotification->user_id=$request->user_id;
            $tumitaNotification->message="Welcome to Tumit, thank you for joining. We hope you enjoy using this app.";
            $tumitaNotification->save();

            $data = [
                'status'=>201,
                'message'=>"Tumita created successfully"
            ];
            return response()->json($data, 200);
        }        
    }

    public function changeUsername(Request $request,$id)
    {
        $validator = Validator::make($request->all(),
        [
            'name'=>'required'         
        ]);
        $data = [
            'status'=>422,
            'message'=>$validator->messages()
        ];
        if($validator->fails())
        {
            return response()->json($data, 200);
        }else{
            $tumita = Tumita::find($id);
            if($tumita == null){
                $data = [
                    'status'=>404,
                    'message'=>"Tumita not found"
                ];
                return response()->json($data, 200);
            }

            $name=$request->name;
            Tumita::where('id', $id)->update(array('name' => $name));

            $data = [
                'status'=>204,
                'message'=>"Tumita username updated successfully",
                'name'=>$name
            ];
            return response()->json($data, 200);
        }
    }

    public function changeEmail(Request $request,$id)
    {
        $validator = Validator::make($request->all(),
        [
            'email'=>'required'         
        ]);
        $data = [
            'status'=>422,
            'message'=>$validator->messages()
        ];
        if($validator->fails())
        {
            return response()->json($data, 200);
        }else{
            $tumita = Tumita::find($id);
            if($tumita == null){
                $data = [
                    'status'=>404,
                    'message'=>"Tumita not found"
                ];
                return response()->json($data, 200);
            }

            $email=$request->email;
            Tumita::where('id', $id)->update(array('email' => $email));

            $data = [
                'status'=>204,
                'message'=>"Tumita email updated successfully"
            ];
            return response()->json($data, 200);
        }
    }   
    
    public function changeProfilePic(Request $request,$id)
    {
        $validator = Validator::make($request->all(),
        [
            'prof_pic' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'         
        ]);
        $data = [
            'status'=>422,
            'message'=>$validator->messages()
        ];
        if($validator->fails())
        {
            return response()->json($data, 200);
        }else{
            $tumita = Tumita::find($id);
            if($tumita == null){
                $data = [
                    'status'=>404,
                    'message'=>"Tumita not found"
                ];
                return response()->json($data, 200);
            }
            $imageName = time().'.'.$request->prof_pic->extension();
            $request->prof_pic->move(public_path('profiles'), $imageName);
            $profPic='profiles/'.$imageName;
            Tumita::where('id', $id)->update(array('prof_pic' => $profPic));

            $data = [
                'status'=>204,
                'message'=>"Tumita profile pic updated successfully"
            ];
            return response()->json($data, 200);
        }
    } 

    public function changePassword(Request $request,$id)
    {
        $validator = Validator::make($request->all(),
        [
            'current_password'=>'required',  
            'new_password'=>'required',       
        ]);
        $data = [
            'status'=>422,
            'message'=>$validator->messages()
        ];
        if($validator->fails())
        {
            return response()->json($data, 200);
        }else{
            if(Auth::user()->id != $id){
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            $tumita = Tumita::find($id);
            if($tumita == null){
                $data = [
                    'status'=>404,
                    'message'=>"Tumita not found"
                ];
                return response()->json($data, 404);
            }

            if (!Hash::check($request->current_password, $tumita->password)) 
            {
                $data = [
                    'status'=>422,
                    'message'=>"current password is wrong"
                ];
                return response()->json($data, 422);
            }

            if (strcmp($request->current_password, $request->new_password) == 0) 
            {
                $data = [
                    'status'=>422,
                    'message'=>"current password cannot be the same as new passoword"
                ];
                return response()->json($data, 200);
            }
            $tumita->password = $request->new_password;

            $tumita->save();

            $data = [
                'status'=>204,
                'message'=>"Tumita password updated successfully"
            ];
            return response()->json($data, 200);
        }
    } 

    public function edit(Request $request,$id)
    {
        $validator = Validator::make($request->all(),
        [
            'name'=>'required',
            'phone'=>'required',
            'email'=>'required',
            'password'=>'required'          
        ]);
        $data = [
            'status'=>422,
            'message'=>$validator->messages()
        ];
        if($validator->fails())
        {
            return response()->json($data, 200);
        }else{
            $tumita = Tumita::find($id);
            if($tumita == null){
                $data = [
                    'status'=>404,
                    'message'=>"Tumita not found"
                ];
                return response()->json($data, 200);
            }
            $tumita->name=$request->name;
            $tumita->phone=$request->phone;
            $tumita->email=$request->email;
            $tumita->password=$request->password;

            $tumita->save();

            $data = [
                'status'=>204,
                'message'=>"Tumita updated successfully"
            ];
            return response()->json($data, 200);
        }        
    }

    public function softDelete(Request $request,$id)
    {
        $tumita = Tumita::find($id);
        if($tumita == null){
            $data = [
                'status'=>404,
                'message'=>"Tumita not found"
            ];
            return response()->json($data, 200);
        }
        
        $tumita->deleted_at = Carbon::now();

        $tumita->save();

        $data = [
            'status'=>204,
            'message'=>"Tumita deleted successfully"
        ];
        return response()->json($data, 200);       
    }

    public function delete($id)
    {
        $tumita = Tumita::find($id);
        $tumita->delete();

        $data = [
            'status'=>204,
            'message'=>"Tumita deleted successfully"
        ];
        return response()->json($data, 200);
    }

    //search tumit
    public function search($id)
    {
        // search tumit by name,phone,email or by character set
       $tumitaSearchResult =  Tumita::where('name',"like","%". $id."%")
                        ->orWhere('phone',"like","%". $id."%")
                        ->orWhere('email',"like","%". $id."%")->get();

         $data = [
            "status" => 200,
            "tumit" => $tumitaSearchResult 
        ];

        return response()->json($data, 200);

    }
}
