<?php

namespace App\Http\Controllers;

use App\Contest;
use App\School;
use App\User;
use App\Vote;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ContestantController extends Controller
{

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $contestants = User::paginate(10);
    return view('contestant.admin_index', ['contestants' => $contestants]);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    //
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\User  $contestant_id
   * @param  \App\Contest  $contest_id
   * @return \Illuminate\Http\Response
   */
  public function show()
  {
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\User  $contestant_id
   * @param  \App\Contest  $contest_id
   * @return \Illuminate\Http\Response
   */
  public function visit_contestant($contestant_id)
  {
    try {
      $contestant = User::where('id', $contestant_id)->firstOrFail();
      return view('contestant.visit', ['contestant' => $contestant,]);
    } catch (ModelNotFoundException $mnt) {
      return back()->with('error', 'Contestant not found');
    } catch (\Exception $e) {
      return back()->with('error', $e->getMessage());
    }
  }


  /**
   * Display the specified resource.
   *
   * @param  \App\User  $contestant_id
   * @param  \App\Contest  $contest_id
   * @return \Illuminate\Http\Response
   */
  public function view_profile()
  {
    if (Auth::user()->is_admin()) {
      $contest_count = Contest::count();
      $contestant_count = User::count();
      $vote_count = Vote::where('status', 'valid')->count();
      $school_count = School::count();
      $pending_application_count = DB::table('contest_user')->where('status','pending')->count();
      return view('home', [
        'contest_count' => $contest_count,
        'contestant_count' => $contestant_count,
        'vote_count' => $vote_count,
        'school_count' => $school_count,
        'pending_application_count'=> $pending_application_count,
      ]);
    }
    return view('home');
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\User  $id
   * @return \Illuminate\Http\Response
   */
  public function edit()
  {
    $schools = School::select('id', 'name', 'type')->get();
    return view('contestant.edit', ['schools' => $schools]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\User  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request)
  {
    if (!Auth::user()->is_admin()) {
      $validator = Validator::make($request->all(), [
        'first_name' => 'required|alpha|max:25|min:2',
        'last_name' => 'required|alpha|max:25|min:2',
        'middle_name' => 'nullable|alpha|max:25|min:2',
        'phone' => 'required|string|max:15|min:8',
        'gender' => 'required|string|min:4|max:7|',
        'state' => 'required|string|min:4',
        'age' => 'required|numeric|between:1,100',
        'bio' => 'required|string|min:20|max:5000',
        'sch_id' => 'required|integer|exists:schools,id',
        'sch_level' => 'required|string|min:3|max:5',
        'sch_faculty' => 'required|string|min:3|max:15',
        'avatar' => 'file|image|mimes:jpeg,png,gif,jpg|max:2048',
        'media.*' => 'file|image|mimes:jpeg,png,gif,jpg|max:2048',
      ]);
    } else {
      $validator = Validator::make($request->all(), [
        'first_name' => 'required|alpha|max:25|min:2',
        'last_name' => 'required|alpha|max:25|min:2',
        'phone' => 'required|string|max:15|min:8',
        'gender' => 'required|string|min:4|max:7|',
        'avatar' => 'file|image|mimes:jpeg,png,gif,jpg|max:2048',
      ]);
    }
    if ($validator->fails()) {
      // dd($validator->errors());
      return back()->withErrors($validator->errors())->withInput();
    }
    try {
      $data = $request->all();
      //adding images
      if ($request->hasFile('avatar')) {
        $avatar = $request->file('avatar');
        $img_ext = $avatar->getClientOriginalExtension();
        $img_name = sprintf("CAVATAR_%s.%s", bin2hex(random_bytes(15)), $img_ext);
        $destination_path = public_path(sprintf("images/users/%s", Auth::user()->id));
        $avatar->move($destination_path, $img_name);
        $data['avatar'] = $img_name;
        if (Auth::user()->avatar != null) {
          $path = public_path(sprintf("images/users/%s/%s", Auth::user()->id, Auth::user()->avatar));
          if (File::exists($path)) {
            File::delete($path);
          }
        }
      }
      if ($request->hasFile('media')) {
        $image_url = [];
        $media = $request->file('media');
        $max_media_count = 1;
        foreach ($media as $md) {
          if ($max_media_count <= 5) {
            $img_ext = $md->getClientOriginalExtension();
            $img_name = sprintf("CMEDIA_%s.%s", bin2hex(random_bytes(10)), $img_ext);
            $destination_path = public_path(sprintf("images/users/%s", Auth::user()->id));
            $md->move($destination_path, $img_name);
            $image_url[] = $img_name;
            $max_media_count++;
          } else {
            break;
          }
        }
        if (count(Auth::user()->media) > 0) {
          foreach (Auth::user()->media as $user_media) {
            $path = public_path(sprintf("images/users/%s/%s", Auth::user()->id, $user_media));
            if (File::exists($path)) {
              File::delete($path);
            }
          }
        }
        $data['media'] = $image_url;
      }
      User::find(Auth::user()->id)->update($data);
      return redirect()->route('contestant_view_profile')->with('success', 'Profile Updated');
    } catch (ModelNotFoundException $mnt) {
      return back()->with('error', 'Contestant not found');
    } catch (\Exception $e) {
      // dd($e);
      return back()->with('error', $e->getMessage());
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\User  $contestant_id
   * @return \Illuminate\Http\Response
   */
  public function destroy($contestant_id)
  {
    //
  }
}
