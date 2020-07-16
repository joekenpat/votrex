<?php

namespace App\Http\Controllers;

use App\Contest;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class ContestantController extends Controller
{

/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('auth:admin');
    }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index($contest_id)
  {
    try {
      $contest = Contest::where('id', $contest_id)->firstOrFail();
    } catch (ModelNotFoundException $mnt) {
      return back()->with('error', 'Contest not found');
    } catch (\Exception $e) {
      return back()->with('error', $e->getMessage());
    }

    try {
      $contestants = $contest->contestants()->paginate(10);
      return view('contestant.index', ['contestants' => $contestants]);
    } catch (ModelNotFoundException $mnt) {
      return back()->with('error', 'Contestants not found');
    } catch (\Exception $e) {
      return back()->with('error', $e->getMessage());
    }
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
  public function show($contest_id, $contestant_id)
  {
    try {
      $contest = Contest::where('id', $contest_id)->firstOrFail();
    } catch (ModelNotFoundException $mnt) {
      return back()->with('error', 'Contest not found');
    } catch (\Exception $e) {
      return back()->with('error', $e->getMessage());
    }

    try {
      $contestant = Contest::where('id', $contestant_id)->firstOrFail();
    } catch (ModelNotFoundException $mnt) {
      return back()->with('error', 'Contestant not found');
    } catch (\Exception $e) {
      return back()->with('error', $e->getMessage());
    }


    try {
      $contestant_votes = $contestant->votes()->where('contest_id', $contest_id)->where('status', 'valid')->get();
      return view('contestant.show', ['contest' => $contest, 'constestant' => $contestant, 'contestant_votes' => $contestant_votes]);
    } catch (ModelNotFoundException $mnt) {
      return back()->with('error', 'Contestant Votes not found');
    } catch (\Exception $e) {
      return back()->with('error', $e->getMessage());
    }
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\User  $id
   * @return \Illuminate\Http\Response
   */
  public function edit()
  {
    return view('contestant.edit');
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\User  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $contestant_id)
  {
    $validator = Validator::make($request->all(), [
      'first_name' => 'required|alpha|max:25|min:2',
      'last_name' => 'required|alpha|max:25|min:2',
      'middle_name' => 'required|alpha|max:25|min:2',
      'phone' => 'required|string|max:15|min:8',
      'email' => 'required|email:rfc,dns|max:150|min:5',
      'gender' => 'required|alpha|min:4|max:6|',
      'state' => 'required|alpha|min:4',
      'age' => 'required|numeric|between:1,100',
      'bio' => 'required|string|min:20|max:250',
      'sch_id' => 'required|uuid|exists:schools,id',
      'sch_level' => 'required|string|min:5|max:10',
      'sch_faculty' => 'required|string|min:5|max:10',
      'avatar' => 'required|file|image|mimes:jpeg,png,gif,jpg|max:2048',
      'media*' => 'file|image|mimes:jpeg,png,gif,jpg|max:2048',
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
      $img_name = sprintf("CAVATAR_%s.%s", $contestant_id, $img_ext);
      $avatar->storeAs("images/users/{$contestant_id}/profile", $img_name);
      $data['avatar'] = $img_name;
      if ($request->hasFile('media')) {
        $image_url = [];
        $media = $request->file('media');
        foreach ($media as $md) {
          $img_ext = $md->getClientOriginalExtension();
          $img_name = sprintf("CMEDIA_%s.%s", now()->format('Y-m-d H:i:s.u'), $img_ext);
          $image_url[] = $md->storeAs("images/users/{$contestant_id}/media", $img_name);
        }
        $data['media'] = $image_url;
      }
      User::where('id', $contestant_id)->update($data);
      $updated_user = User::with('votes')->with('contests')->where('id', $contestant_id)->firstOrFail();
      return redirect()->intended(route('contestant_view_profile'));
    } catch (ModelNotFoundException $mnt) {
      return back()->with('error', 'Contestant not found');
    } catch (\Exception $e) {
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
