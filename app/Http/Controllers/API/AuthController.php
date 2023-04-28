<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Brian2694\Toastr\Facades\Toastr;
use Validator;
use App\Models\User;

class AuthController extends BaseController
{
    
    
    public function signin(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $authUser = Auth::user(); 
            $success['token'] =  $authUser->createToken('MyAuthApp')->plainTextToken; 
            $success['name'] =  $authUser->name;
            
            if ($request->is('api/*')) {
                // API response
                return $this->sendResponse($success, 'User signed in.');
            } else {
                // Web redirection
                Toastr::success('Welcome back, ' . $authUser->name . '!', 'Success');
                return redirect(route('home'))->with('success', 'Welcome back, ' . $authUser->name . '!');
            }
        } 
        else{ 
            if ($request->is('api/*')) {
                // API response
                return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
            } else {
                // Web redirection
                Toastr::error('Fail, WRONG USERNAME OR PASSWORD', 'Error');
                return redirect(route('login'));
            }
        } 
    }
    
    
    public function signup(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
        'confirm_password' => 'required|same:password',
    ]);
    
    if($validator->fails()){
        if ($request->is('api/*')) {
            // API response
            return $this->sendError('Error validation', $validator->errors());       
        } else {
            // Web redirection
            Toastr::error($validator->errors()->first(),'Error');
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
    }
    
    $input = $request->all();
    $input['password'] = bcrypt($input['password']);
    $user = User::create($input);
    $success['token'] =  $user->createToken('MyAuthApp')->plainTextToken;
    $success['name'] =  $user->name;
    
    if ($request->is('api/*')) {
        // API response
        return $this->sendResponse($success, 'User created successfully.');
    } else {
        // Web redirection
        Toastr::success('User created successfully.','Success');
        return redirect(route('login'));
    }
}


    public function logout(Request $request)
{
    //auth()->user()->tokens()->delete();

    $request->user()->tokens()->delete();

    //return redirect('/login')->with('success', 'Logged out successfully.');
    
    if ($request->is('api/*')) {
        // API response
        return $this->sendResponse($success, 'Logged Out successfully.');
    } else {
        // Web redirection
        Toastr::success('User Logged Out successfully.','Success');
        return redirect(route('login'));
    }
}

 
    
}