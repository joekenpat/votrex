<?php

namespace App\Http\Controllers\API;

use App\Contest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PulkitJalan\GeoIP\Facades\GeoIP;

class AuthController extends Controller
{
  /**
   * login function.
   *
   * @param \illuminate\Http\Client\Request $request
   * @return \Illuminate\Http\
   */
  public function login(Request $request)
  {
    $credentials = [
      'email' => $request->email,
      'password' => $request->password
    ];
    if (auth()->attempt($credentials)) {
      $user = Auth::user();
      $success['token'] = $user->createToken('realstate')->accessToken;
      $success['data'] = $user;
      return response()->json([
        'success' => $success,
      ], Response::HTTP_CREATED);
    } else {
      return response()->json([
        'error' => 'Unauthorised',
      ], Response::HTTP_UNAUTHORIZED);
    }
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    try {
      $status = $request->filled('status') ? $request->status : null;
      $sort = $request->filled('sort') ? $request->sort : null;
      $result_count = $request->filled('result_count') ? $request->result_count : 10;


      $user_status = function () use ($status) {
        $status_map = ['disabled', 'active'];
        if (in_array($status, $status_map)) {
          return $status;
        } else {
          return 'active';
        }
      };

      $user_sort = function () use ($sort) {
        $sort_map = ["desc", 'asc'];
        if (in_array($sort, $sort_map)) {
          return $sort;
        } else {
          return 'asc';
        }
      };

      $user_result_count = function () use ($result_count) {
        if (in_array($result_count, [10, 25, 50, 100])) {
          return $result_count;
        } else {
          return 10;
        }
      };

      $users = User::where('status', $user_status())
        ->orderBy('created_at', $user_sort())
        ->paginate($user_result_count());
      foreach ($users as $key => $user) {
        $users[$key]->vote_count = $user->votes()->count();
      }
      $success['data'] = $users;
      return response()->json([
        'success' => $success,
      ], Response::HTTP_OK);
    } catch (ModelNotFoundException $mnt) {
      return response()->json([
        'error' => 'No Item Found',
      ], Response::HTTP_NOT_FOUND);
    } catch (\Exception $e) {
      return response()->json([
        'error' => sprintf("message: %s. Error File: %s. Error Line: %s", $e->getMessage(), $e->getFile(), $e->getLine()),
      ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'first_name' => 'required|alpha|max:25|min:2',
      'last_name' => 'required|alpha|max:25|min:2',
      'middle_name' => 'nullable|alpha|max:25|min:2',
      'username' => 'required|alpha_dash|max:25|min:2',
      'phone' => 'required|string|max:15|min:8',
      'email' => 'required|email:rfc,dns|max:150|min:5',
      'password' => 'required|string',
      'gender' => 'required|alpha|min:4|max:6|',
    ]);
    if ($validator->fails()) {
      return response()->json([
        'error' => $validator->errors()
      ]);
    }

    $data = $request->all();
    $data['password'] = Hash::make($data['password']);
    $data['last_ip'] = $request->getClientIp();
    $data['media'] = [];
    $data['last_login'] = now();
    $user = User::create($data);
    if ($user) {
      $success['token'] = $user->createToken('realstate')->accessToken;
      $success['data'] = $user;
      return response()->json([
        'success' => $success,
      ], Response::HTTP_CREATED);
    } else {
      return response()->json([
        'error' => 'Unauthorised',
      ], Response::HTTP_UNAUTHORIZED);
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    // $user = Auth::user()
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
      'first_name' => 'required|alpha|max:25|min:2',
      'last_name' => 'required|alpha|max:25|min:2',
      'middle_name' => 'required|alpha|max:25|min:2',
      'username' => 'required|alpha_dash|max:25|min:2',
      'phone' => 'required|string|max:15|min:8',
      'email' => 'required|email:rfc,dns|max:150|min:5',
      'password' => 'required|string',
      'gender' => 'required|alpha|min:4|max:6|',
      'bio' => 'required|string|min:20|max:250',
      'avatar' => 'required|file|image|mimes:jpeg,png,gif,jpg|max:2048',
      'media*' => 'required|file|image|mimes:jpeg,png,gif,jpg|max:2048',
      'sch_id' => 'required|uuid|exists:schools,id',
      'sch_level' => 'required|string|min:5|max:10'
    ]);
    if ($validator->fails()) {
      return response()->json([
        'error' => $validator->$validator->errors()
      ]);
    }
    try {
      $data = $request->all();
      //adding images
      $avatar = $request->file('avatar_file');
      $img_ext = $avatar->getClientOriginalExtension();
      $img_name = sprintf("USER_%s.%s", $id, $img_ext);
      $avatar->storeAs("images/users/{$id}/profile", $img_name);
      $data['avatar'] = $img_name;
      if ($request->hasFile('media')) {
        $image_url = [];
        $media = $request->file('media');
        foreach ($media as $md) {
          $img_ext = $md->getClientOriginalExtension();
          $img_name = sprintf("PIMG_%s.%s", now()->format('Y-m-d H:i:s.u'), $img_ext);
          $image_url[] = $md->storeAs("images/users/{$id}/media", $img_name);
        }
        $data['media'] = $image_url;
      }
      User::where('id', $id)->update($data);
      $updated_user = User::with('votes')->with('contests')->where('id', $id)->firstOrFail();
      $success['data'] = $updated_user;
      return response()->json([
        'success' => $success,
      ], Response::HTTP_OK);
    } catch (ModelNotFoundException $mnt) {
      return response()->json([
        'error' => 'User not Found',
      ], Response::HTTP_NOT_FOUND);
    } catch (\Exception $e) {
      return response()->json([
        'error' => $e->getMessage(),
      ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    //
  }

}
