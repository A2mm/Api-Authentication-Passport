<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Validator; 

class PassportController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'       => 'required|min:3',
            'email'      => 'required|email|unique:users',
            'password'   => 'required|min:6',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) 
        {
        	return response()->json([
        		'success' => false, 
        		'errors'  => $validator->errors()
        	], 400);
        }
        else
        {
	        $user = User::create([
	            'name'     => $request->name,
	            'email'    => $request->email,
	            'password' => bcrypt($request->password)
	        ]);
	 
	        $token = $user->createToken('AppName')->accessToken;
	 
	        return response()->json(['token' => $token], 200);
	    }
    }
 
    public function login(Request $request)
    {
    	$validator = Validator::make($request->all(), [
            'email'      => 'required|email',
            'password'   => 'required|min:6',
        ]);

        if ($validator->fails()) 
        {
        	return response()->json([
        		'success' => false, 
        		'errors'  => $validator->errors()
        	], 400);
        }
        else
        {
	        $credentials = [
	            'email'    => $request->email,
	            'password' => $request->password
	        ];
	 
	        if (auth()->attempt($credentials)) 
	        {
	            $token = auth()->user()->createToken('AppName')->accessToken;
	            return response()->json(['token' => $token], 200);
	        } 
	        else 
	        {
	            return response()->json(['error' => 'UnAuthorised'], 401);
	        }
	    }
	}

	public function details()
    {
        return response()->json(['user' => auth()->user()], 200);
    }
}
