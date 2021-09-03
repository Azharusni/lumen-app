<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function register (Request $request)
    {
        $dataValidation = $this->validate($request,[
            'username' => 'required|unique:users',
            'email'=>'required|unique:users',
            'password'=>'required',
        ]);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' =>Hash::make($request->password),
            'api_token' =>Str::random(50),
        ]);

        return response()->json(['user'=>$user],201);

    }

    public function login (Request $request)
     {
        $dataValidation = $this->validate($request,[
            'email'=>'required',
            'password'=>'required',
        ]);
        $user = User::where('email', $request->email)->first();

        if(!$user){
            return response()->json(['status'=> 'error', 'message'=>'User not found'],404);
        }
        if(Hash::check($request->password, $user->password)){
            $user->update(['api_token'=>Str::random(50)]);
            return response()->json(['status'=>'success', 'user'=>$user],200);
        }

        return response()->json(['status'=> 'error', 'message'=>'Credentials is invalid'],401);
    }

    public function logout(Request $request)
    {
        $api_token = $request->api_token;
        $user = User::where('api_token', $api_token)->first();

        if(!$user){
            return response()->json(['status'=>'error', 'message'=>'not logged in'],401);
        }

        $user->api_token =null;
        $user->save();

        return response()->json(['status'=>'success', 'message'=>'You are logged out'],200);
    }

}
