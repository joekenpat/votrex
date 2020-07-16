<?php

namespace App\Http\Controllers;

use App\Contest;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ContestController extends Controller
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
  public function index(Request $request)
  {
    $status = $request->filled('status') ? $request->status : null;
    $sort_type = $request->filled('sort_type') ? $request->sort_type : 'asc';
    $result_count = $request->filled('result_count') ? $request->result_count : 10;

    $contest_status = function () use ($status) {
      $contest_status_map = [
        'valid',
        'invalid',
      ];
      if (in_array($status, $contest_status_map)) {
        return $status;
      } else {
        return 'valid';
      }
    };

    $contest_sort_type = function () use ($sort_type) {
      $contest_sort_type_map = ["asc", 'desc'];
      if (in_array($sort_type, $contest_sort_type_map)) {
        return $sort_type;
      } else {
        return 'asc';
      }
    };

    $item_result_count = function () use ($result_count) {
      if (in_array($result_count, [10, 25, 50, 100])) {
        return $result_count;
      } else {
        return 10;
      }
    };

    try {
      $contest = Contest::with('users')
        ->with('votes')
        ->where('status', $contest_status())
        ->orderBy('created', $contest_sort_type())
        ->paginate($item_result_count());
      $success['data'] = $contest;
      return response()->json([
        'success' => $success,
      ], Response::HTTP_OK);
    } catch (ModelNotFoundException $mnt) {
      return response()->json([
        'error' => 'No vote Found',
      ], Response::HTTP_NOT_FOUND);
    } catch (\Exception $e) {
      return response()->json([
        'error' => sprintf("message: %s. Error File: %s. Error Line: %s", $e->getMessage(), $e->getFile(), $e->getLine()),
      ], Response::HTTP_INTERNAL_SERVER_ERROR);
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
    $validator = Validator::make($request->all(), [
      'title' => 'required|string|min:5|max:250|',
      'reg_fee' => 'nullable|numeric|min:0|max:100000',
      'vote_fee' => 'nullable|numeric|min:0|max:100000',
      'started_at' => 'required|date|',
      'ended_at' => 'required|date|',
      'image' => 'required|file|image|mimes:jpeg,png,gif,jpg|max:2048',
    ]);
    if ($validator->fails()) {
      return response()->json([
        'error' => $validator->errors()
      ], Response::HTTP_BAD_REQUEST);
    }

    try {
      $data = $request->all();
      $data['image'] = null;
      $new_contest = Contest::create($data);
      $new_contest->refresh();



      //adding images
      $avatar = $request->file('avatar_file');
      $img_ext = $avatar->getClientOriginalExtension();
      $img_name = sprintf("contest_%s.%s", $new_contest->id, $img_ext);
      $avatar->storeAs("images/contest/{$new_contest->id}/cover", $img_name);
      $data['image'] = $img_name;

      $new_contest->image = $data['image'];
      $new_contest->update();
      $success['data'] = $new_contest;
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
   * Display the specified resource.
   *
   * @param  \App\Contest  $contest
   * @return \Illuminate\Http\Response
   */
  public function show(Contest $contest)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Contest  $contest
   * @return \Illuminate\Http\Response
   */
  public function edit(Contest $contest)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Contest  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
      'title' => 'required|string|min:5|max:250|',
      'reg_fee' => 'nullable|numeric|min:0|max:100000',
      'vote_fee' => 'nullable|numeric|min:0|max:100000',
      'started_at' => 'required|date|',
      'ended_at' => 'required|date|',
      'image' => 'required|file|image|mimes:jpeg,png,gif,jpg|max:2048',
    ]);
    if ($validator->fails()) {
      return response()->json([
        'error' => $validator->errors()
      ], Response::HTTP_BAD_REQUEST);
    }

    try {
      $data = $request->all();
      $data['image'] = null;
      $updateable_contest = Contest::where('id', $id)->firstOrFail();
      $updateable_contest->update($data);
      $updateable_contest->refresh();



      if ($request->hasFile('avatar_file')) {
        //adding images
        $avatar = $request->file('avatar_file');
        $img_ext = $avatar->getClientOriginalExtension();
        $img_name = sprintf("contest_%s.%s", $updateable_contest->id, $img_ext);
        $avatar->storeAs("images/contest/{$updateable_contest->id}/cover", $img_name);
        $data['image'] = $img_name;
        $updateable_contest->image = $data['image'];
        $updateable_contest->update();
      }

      $success['data'] = $updateable_contest;
      return response()->json([
        'success' => $success,
      ], Response::HTTP_OK);
    } catch (ModelNotFoundException $mnt) {
      return response()->json([
        'error' => 'Contest not Found',
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
   * @param  \App\Contest  $contest
   * @return \Illuminate\Http\Response
   */
  public function destroy(Contest $contest)
  {
    //
  }

  /**
   * attach a contestant to specified contest.
   *
   * @param  uuid  $contest_id
   * * @param  uuid  $contestant_id
   * @return \Illuminate\Http\Response
   */
  public function join($contest_id = null)
  {
    if ($contest_id = null) {
      return back()->with('error', 'Contest ID cannot be null');
    }
    $contestant_id = Auth::user()->id;
    try {
      $contestant = User::where('id', $contestant_id)->firstOrFail();
      if (Contest::where('contest_id', $contest_id)->isOpen()) {
        if (!$contestant->contests()->where('contest_id', $contest_id)->exists()) {
          $contestant->contests()->attach([$contest_id]);
        }
      }
      $updated_contestant = User::with('votes')->with('contests')->where('id', $contest_id)->firstOrFail();

      return redirect()->route('contest', ['contest_id' => $contest_id])->with('success', 'Contest Joined Successfully.');
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
}
