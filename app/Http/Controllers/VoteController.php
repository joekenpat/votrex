<?php

namespace App\Http\Controllers;

use App\Vote;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class VoteController extends Controller
{

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

    $vote_status = function () use ($status) {
      $vote_status_map = [
        'valid',
        'invalid',
      ];
      if (in_array($status, $vote_status_map)) {
        return $status;
      } else {
        return 'valid';
      }
    };

    $vote_sort_type = function () use ($sort_type) {
      $vote_sort_type_map = ["asc", 'desc'];
      if (in_array($sort_type, $vote_sort_type_map)) {
        return $vote_sort_type_map[$sort_type];
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

    if (Auth()->user()) {
      try {
        $votes = Vote::with('user')
          ->with('tags:name')
          ->where('status', $vote_status())
          ->orderBy('created', $vote_sort_type())
          ->paginate($item_result_count());
        $success['data'] = $votes;
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
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    // return view('vote.create');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $media_url = [];
    $this->validate($request, [
      'first_name' => 'required|alpha|max:25|min:2|',
      'last_name' => 'required|alpha|max:25|min:2|',
      'email' => 'required|email:rfc,dns|max:150|min:5|',
      'quantity' => 'numeric|min:1|max:10000|',
      'user_id' => 'required|uuid|exists:users,id|',
      'contest_id' => 'required|uuid|exists:contests,id|',

    ]);

    try {
      $new_vote = new Vote();
      $new_vote->first_name = $request->input('first_name');
      $new_vote->last_name = $request->input('last_name');
      $new_vote->quantity = $request->input('quantity');
      $new_vote->status = 'invalid';
      $new_vote->paystack_ref = null;
      $new_vote->save();
      $new_vote->user()->associate($request->input('user_id'));
      $new_vote->contest()->associate($request->input('contest_id'));
    } catch (\Exception $e) {
      $message = sprintf("message: %s. Error File: %s. Error Line: %s", $e->getMessage(), $e->getFile(), $e->getLine());
      return response()->json([
        'error' => $message,
      ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Vote  $vote
   * @return \Illuminate\Http\Response
   */
  public function show(Vote $vote)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Vote  $vote
   * @return \Illuminate\Http\Response
   */
  public function edit(Vote $vote)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Vote  $vote
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Vote $vote)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Vote  $vote
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    try {
      $vote = Vote::where('id', $id)->firstOrFail();
      $vote->delete();
      return response()->json([
        'success' => 'Vote Deleted!',
      ], Response::HTTP_OK);
    } catch (ModelNotFoundException $mnt) {
      return response()->json([
        'error' => 'No Vote Found',
      ], Response::HTTP_NOT_FOUND);
    } catch (\Exception $e) {
      return response()->json([
        'error' => $e->getMessage(),
      ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
  }
}
