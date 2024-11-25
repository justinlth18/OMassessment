<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function login(Request $request) {
        $incomingFields = $request->validate([
            'loginname' => 'required',
            'loginpassword' => 'required'
        ]);
    
        if (auth()->attempt(['name' => $incomingFields['loginname'], 'password' => $incomingFields['loginpassword']])) {
            $token = auth()->user()->createToken('auth_token')->plainTextToken; 
            $request->session()->put('token', $token); 
            
            return response()->json([
                'token' => $token,
                'message' => 'Login successful'
            ], 200);
        }
    
        return response()->json(['message' => 'Invalid credentials'], 401);
    }
    
    public function register(Request $request) {
        $incomingFields = $request->validate([
            'name' => ['required', 'min:4', 'max:15', Rule::unique('users', 'name')],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'min:8', 'max:30']
        ]);
    
        $incomingFields['password'] = bcrypt($incomingFields['password']);
        $user = User::create($incomingFields);
        $token = $user->createToken('auth_token')->plainTextToken;
        $request->session()->put('token', $token); 
        auth()->login($user);
        return response()->json(['token' => $token], 201); 
    }
    
    public function logout(Request $request) {
        if ($request->method() !== 'POST') {
            return response()->json(['message' => 'Invalid request method. Use POST for logout.'], 405);
        }
    
        auth()->logout();
    
        $request->session()->forget('token'); 
    
        return response()->json(['message' => 'Logged out'], 200);
    }
    
    
    
    public function checkSessionToken() {
        if (!session('token')) {
            return redirect('/login');
        }
    }
}
