<?php

namespace App\Http\Controllers\Auth;

use App\Admin;
use App\User;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PulkitJalan\GeoIP\Facades\GeoIP;

class RegisterController extends Controller
{
  /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

  use RegistersUsers;

  /**
   * Where to redirect users after registration.
   *
   * @var string
   */
  protected $redirectTo = 'login';

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('guest');
    $this->middleware('guest:admin');
  }

  /**
   * Show the admin registration form base on entry link.
   *
   * @return \Illuminate\Http\Response
   */

  public function show_admin_register_form()
  {
    return view('auth.register', ['url' => 'admin']);
  }


  /**
   * process the admin registration form base on entry link.
   *
   * @return \Illuminate\Http\Request
   */
  public function admin_register(Request $request)
  {
    $validator = Validator::make($request, [
      'first_name' => 'required|alpha|max:25|min:2',
      'last_name' => 'required|alpha|max:25|min:2',
      'email' => 'required|email|unique:admins',
      'password' => 'required|min:4',
    ]);

    if ($validator->fails()) {
      return back()->withErrors($validator)->withInput();
    }

    $data = $request->all();
    $data['password'] = Hash::make($data['password']);
    $data['last_ip'] = $request->getClientIp();
    $data['last_login'] = now();
    Admin::create($data);

    return redirect()->intended(route('admin_login'));
  }

  /**
   * Show the contestant registration form base on entry link.
   *
   * @return \Illuminate\Http\Response
   */

  public function show_contestant_register_form()
  {
    return view('auth.register', ['url' => 'contestant']);
  }


  /**
   * process the contestant registration form base on entry link.
   *
   * @return \Illuminate\Http\Request
   */
  public function contestant_register(Request $request)
  {
    $validator = Validator::make($request->post(), [
      'first_name' => 'required|alpha|max:25|min:2',
      'last_name' => 'required|alpha|max:25|min:2',
      'middle_name' => 'nullable|alpha|max:25|min:2',
      'phone' => 'required|string|max:15|min:8|unique:users',
      'email' => 'required|email|max:150|min:5|unique:users',
      'password' => 'required|string',
      'gender' => 'required|string|in:male,female',
    ]);

    if ($validator->fails()) {
      return back()->withErrors($validator)->withInput();
    }

    $data = $request->all();
    $data['password'] = Hash::make($data['password']);
    $data['last_ip'] = $request->getClientIp();
    $data['media'] = [];
    $data['last_login'] = now();
    User::create($data);

    return redirect()->intended(route('login'));
  }
}
