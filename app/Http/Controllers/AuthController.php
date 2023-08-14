<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Exception;





class AuthController extends Controller
{

  public function index()
  {

    return view('Auth/index');
  }


  public function authenticate(Request $request)
  {

    $credentials = $request->validate([
      'ussername' => 'required',
      'password' => ['required']
    ]);

    if (Auth::attempt($credentials)) {

      $request->session()->regenerate();
      $ussername = $request->input('ussername');
      Session::put('ussername', $ussername);
      return redirect('dashboard')->with('success', "Login success");
    } else {
      dd('sino');

      return redirect('/')->with('failed', 'Login Failed!');
    }
  }



  public function logout(Request $request)
  {
    Auth::logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    return redirect('/');
  }
}
