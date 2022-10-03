<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

class AuthController extends Controller
{
    public function index(){
        if($name = Session::get('name')){
            return redirect()->route('dashboard', compact('name'));
        }else{return view('login');}
    }

    public function register(){
        return view('register');
    }

    public function validate_registration(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|regex: /^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/us|unique:users',
            'password' => 'required|min:6',
        ]);

        $data = $request->all();

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);

        return redirect('login')->with('success', 'Registration was successfull!Now you can login.');
    }

    public function validate_login(Request $request){
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $userDetails = $request->only('email','password');

        if(Auth::attempt($userDetails)){
            $uDetail = User::find(Auth::id());
            $name = $uDetail['name'];
            $request->session()->put('name', $name);
            return redirect()->route('dashboard', $name);
        }
        else{
            return redirect('login')->with('success','Login credentials are not valid!');
        }
    }

    public function dashboard(){
        if(Auth::check()){
            $uDetail = User::find(Auth::id());
            $name = $uDetail['name'];
            return view('dashboard', compact('name'));
        }else{
            return redirect('login')->with('success', 'You are not having access to this page!');
        }
    }

    public function logout(){
        Session::flush();
        Auth::logout();
        return redirect('login');
    }
}
