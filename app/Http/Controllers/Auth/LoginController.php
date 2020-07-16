<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
  /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

  use AuthenticatesUsers;

  /**
   * Where to redirect users after login.
   *
   * @var string
   */
  protected $redirectTo = RouteServiceProvider::HOME;

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('guest')->except('logout');
    $this->middleware('guest:admin')->except('logout');
  }
  protected function redirectTo()
  {
    if (Auth::guard('admin')) {
      return route('admin_home');
    }
    return route('contestant_home');
  }

  protected function authenticated(Request $request, $user)
  {
    $user->update([
      'last_login' => Carbon::now()->format('Y-m-d H:i:s.u'),
      'last_ip' => $request->getClientIp(),
    ]);
    // Auth::User()->createToken('moni')->accessToken;
  }

  public function show_admin_login_form()
  {
    return view('auth.login', ['url' => 'admin']);
  }

  public function admin_login(Request $request)
  {
    $this->validate($request, [
      'email' => 'required|email',
      'password' => 'required|min:4',
    ]);

    if (Auth::guard('admin')->Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
      return redirect()->intended(route('admin_home'));
    }
    return back()->withInput($request->only('email', 'remember'));
  }


  public function show_contestant_login_form()
  {
    return view('auth.login', ['url' => 'contestant']);
  }

  public function contestant_login(Request $request)
  {
    $this->validate($request, [
      'email' => 'required|email|exists:users,email',
      'password' => 'required|min:4',
    ],[
      'email.exists'=>'Specified email does not exist.'
    ]);

    if (Auth::guard('admin')->Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
      return redirect()->intended(route('contestant_home'));
    }
    return back()->withInput($request->only('email', 'remember'));
  }
}
