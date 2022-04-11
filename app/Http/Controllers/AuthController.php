<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth, Validator;

class AuthController extends Controller
{
    public function viewLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $input = $request->all();
        $fieldType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $messages = [
            'username.required' => 'Username harus diisi.',
            'password.required' => 'Password harus diisi.'
        ];
        $validate = Validator::make($input,[
            'username' => 'required',
            'password' => 'required'
        ],$messages);
        if($validate->passes())
        {
            if(auth()->attempt(array($fieldType => $input['username'], 'password' => $input['password'])))
            {
                return redirect('/dashboard');
            }
            else
            {
                session()->flash('error','Username atau password salah.');
                return redirect('/');
            }
        }
        return redirect()->back()->withErrors($validate)->withInput();

    }
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/');
    }
}
