<?php

namespace App\Http\Controllers;

use App\Models\Tumita;
use App\Models\OtpCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;

use App\Models\TumitaNotification;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */

    public function __construct()
    {
        # By default we are using here auth:api middleware
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = Auth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Generate OTP
        $otp = rand(1000,9999);

        // save otp
        $otpCode = new OtpCode();
        $otpCode->user_id=Auth::user()->id;
        $otpCode->code=$otp;
        $otpCode->active=1;
        $otpCode->save();

        // send otp to email

        return $this->respondWithToken($token); # If all credentials are correct - we are going to generate a new access token and send it back on response
    }

    public function verifyOtp(Request $request, $code){
        $otpCode = OtpCode::where('code', '=', $code)->where('active', '=', 1)->where('user_id', '=', Auth::user()->id)->first();
        
        if($otpCode == null){
            $data = [
                'status'=>404,
                'message'=>"Code not found"
            ];
            return response()->json($data, 200);
        }

        // Check if 5 minutes has passed
        $then = Carbon::createFromFormat('Y-m-d H:i:s', $otpCode->created_at);
        if($then->addMinutes(5)->isPast()) {
            $otpCode->active=0;
            $data = [
                'status'=>408,
                'message'=>"Code has expired"
            ];
            return response()->json($data, 200);
        }

        $otpCode->active=0;

        $otpCode->save();

        $data = [
            'status'=>200,
            'message'=>"OTP verified successfully",
            'data'=>Auth::user()
        ];
        return response()->json($data, 200);       
    }

    public function register(Request $request){
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
            $tumitaNotification->user_id=$tumita->id;
            $tumitaNotification->message="Welcome to Tumit, thank you for joining. We hope you enjoy using this app.";
            $tumitaNotification->save();

            $data = [
                'status'=>201,
                'message'=>"Tumita created successfully"
            ];
            return response()->json($data, 200);
        }
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        // if (!Auth::parseToken()) {
        //     $data = [
        //         'status'=>401,
        //         'message'=>"Unauthorized access"
        //     ];
        //     return response()->json($data, 200);            
        // }
        Auth::logout(); # This is just logout function that will destroy access token of current user
        return response()->json(['status'=>200, 'message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        # When access token will be expired, we are going to generate a new one wit this function 
        # and return it here in response
        return $this->respondWithToken(Auth::refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        # This function is used to make JSON response with new
        # access token of current user
        return response()->json([
            'status' => 200,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ]);
    }
}
