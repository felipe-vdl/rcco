<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;


class AuthController extends Controller
{
	public function login()
	{
		if(Auth::user() && Auth::user()->is_enabled)
		{
			return redirect()->intended('/home');
		}
		
		return view('auth.login');
	}

	public function entrar(Request $request)
	{
		$request->validate([
			'email' 	=> 'required|email',
			'password'  => 'required',
		]);

		$credentials = ['email' => $request->email, 'password' => $request->password, 'is_enabled' => 1];

		if(Auth::attempt($credentials))
      {
				return redirect()->intended('home');
      } else {
				return redirect()->back()->with('msg','Acesso negado.');
      }
	}

	public function logout(Request $request) {
		Auth::logout();
		return redirect('/login');
  }
}

