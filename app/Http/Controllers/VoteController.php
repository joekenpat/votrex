<?php

namespace App\Http\Controllers;

use App\Contest;
use App\User;
use App\Vote;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Unicodeveloper\Paystack\Facades\Paystack;


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

    if (Auth()->user()) {
      try {
        $votes = Vote::where('status', $vote_status())
          ->orderBy('created_at', $vote_sort_type())
          ->paginate($item_result_count());
        return view('vote.index', ['votes' => $votes]);
      } catch (ModelNotFoundException $mnt) {
        return back()->with('error', 'Vote not found');
      } catch (\Exception $e) {
        return back()->with('error', $e->getMessage());
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
  public function store(Request $request, $contest_id, $contestant_id)
  {
    // $validator = Validator::make($request->all(), [
    //   'first_name' => 'required|alpha|max:25|min:2|',
    //   'last_name' => 'required|alpha|max:25|min:2|',
    //   'email' => 'required|email|max:150|min:5|',
    //   'quantity' => 'numeric|min:1|max:10000|',
    // ]);
    // if ($validator->fails()) {
    //   // dd($validator->errors());
    //   return back()->withErrors($validator->errors())->withInput();
    // }
    // try {
    //   $contest = Contest::where('id', $contest_id)->firstOrFail();
    // } catch (ModelNotFoundException $mnt) {
    //   return back()->with('error', 'Contest not found');
    // } catch (\Exception $e) {
    //   return back()->with('error', $e->getMessage());
    // }
    // try {
    //   $contestant = User::where('id', $contestant_id)->firstOrFail();
    // } catch (ModelNotFoundException $mnt) {
    //   return back()->with('error', 'Contestant not found')->withInput();
    // } catch (\Exception $e) {
    //   return back()->with('error', $e->getMessage())->withInput();
    // }

    // try {
    //   $paystack_ref = Paystack::genTranxRef();
    //   $new_vote = new Vote();
    //   $new_vote->first_name = $request->input('first_name');
    //   $new_vote->last_name = $request->input('last_name');
    //   $new_vote->quantity = $request->input('quantity');
    //   $new_vote->email = $request->input('email');
    //   $new_vote->status = 'invalid';
    //   $new_vote->amount = $request->input('quantity') * $contest->vote_fee;
    //   $new_vote->paystack_ref = $paystack_ref;
    //   $new_vote->user_id = $contestant->id;
    //   $new_vote->contest_id = $contest->id;
    //   $new_vote->save();

    //   $data = [
    //     "amount" => intval($new_vote->amount),
    //     "quantity" => intval(1),
    //     "reference" => $paystack_ref,
    //     "email" => $new_vote->email,
    //     "first_name" => $new_vote->first_name,
    //     "last_name" => $new_vote->last_name,
    //     "currency" => "NGN",
    //   ];
    //   return redirect()->back()->with('success', sprintf('Your Vote for %s was Successful!', $contestant->get_full_name()));
    // } catch (\Exception $e) {
    //   return back()->with('error', $e->getMessage())->withInput();
    // }
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
