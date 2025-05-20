<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
     public function login(Request $request)
     {
         // Validasi input
         $request->validate([
             'email' => 'required|email',
             'password' => 'required',
         ]);
 
         $user = User::where('email', $request->email)->first();
 
         if ($user && Hash::check($request->password, $user->password)) {
             $token = $user->createToken('API Token')->plainTextToken;
 
             return response()->json([
                'status' => 'true',
                'message' => 'login success',
                'user' => $user,
                'token' => $token,
            ], 200);
         }
 
         return response()->json([
            'status' => 'false',
            'message' => 'Invalid credentials'
        ], 401);    
     }
 
    
     public function user(Request $request)
     {
         return response()->json($request->user());
     }

     public function register(Request $request)
    {
        // Validasi input untuk pendaftaran
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|confirmed|min:8',
            'telephone' => 'required|unique:users,telephone'
        ]);

      
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

       
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'telephone' => $request->telephone
        ]);

       
        $token = $user->createToken('API Token')->plainTextToken;

       
        return response()->json([
            'status' => 'true',
            'message' => 'User registered successfully!',
            'token' => $token,
        ], 201);
    }
}
