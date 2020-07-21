<?php

namespace App\Http\Controllers;

use App\Contest;
use App\School;
use App\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ContestController extends Controller
{
  /**
   * Create a new controller instance.
   *
   * @return void
   */

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    if (Auth::check() && Auth::user()->is_admin()) {
      $sort_type = $request->filled('sort_type') ? $request->sort_type : 'asc';
      $result_count = $request->filled('result_count') ? $request->result_count : 10;

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

      $contests = Contest::
        // with('votes')
        orderBy('created_at', $contest_sort_type())
        ->paginate($item_result_count());
      // dd($contests);
      return view('contest.index', ['contests' => $contests]);
    } else {
      $sort_type = $request->filled('sort_type') ? $request->sort_type : 'asc';
      $result_count = $request->filled('result_count') ? $request->result_count : 10;

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

      $contests = Contest::where('ended_at', '>', now())
        ->orderBy('created_at', $contest_sort_type())
        ->paginate($item_result_count());
      // dd($contests);
      return view('contest.index', ['contests' => $contests]);
    }
  }


  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function list_contest_contestant($contest_id)
  {
    if ($contest_id !== null) {
      try {
        $contest = Contest::where('id', $contest_id)->firstOrFail();
        $contestants = $contest->contestants()->paginate(10);
        // $contestant_votes = $contestant->votes()->where('contest_id', $contest_id)->where('status', 'valid')->get();
        // dd($contestants);
        return view('contestant.index', ['contest' => $contest, 'contestants' => $contestants]);
      } catch (ModelNotFoundException $mnt) {
        return back()->with('error', 'Contest not found');
      } catch (\Exception $e) {
        return back()->with('error', $e->getMessage());
      }
    }
  }


  /**
   * find a resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function find_contest_contestant(Request $request, $contest_id)
  {
    $validator = Validator::make($request->all(), [
      'findable' => 'required|alpha|min:3|max:20|',
    ]);
    if ($validator->fails()) {
      return back()->withErrors($validator->errors())->withInput();
    }
    $findable = $request->input('findable');
    try {
      $contest = Contest::where('id', $contest_id)->firstOrFail();
      $contestant_count = $contest->contestants()->where('first_name', 'LIKE', "%{$findable}%")
        ->orwhere('last_name', 'LIKE', "%{$findable}%")
        ->orwhere('middle_name', 'LIKE', "%{$findable}%")->count();
      $contestants = $contest->contestants()->where('first_name', 'LIKE', "%{$findable}%")
        ->orwhere('last_name', 'LIKE', "%{$findable}%")
        ->orwhere('middle_name', 'LIKE', "%{$findable}%")->paginate(10);
      if ($contestant_count == 0) {
        return redirect()->route('list_contest_contestant', ['contest_id' => $contest])->with('info', 'No contestant in this contest matched your search.');
      } else {
        return view('contestant.index', ['contest' => $contest, 'contestants' => $contestants]);
      }
    } catch (ModelNotFoundException $mnt) {
      return back()->with('error', 'Contest not found');
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
    return view('contest.create');
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
      'minimum_vote' => 'nullable|numeric|min:0|max:100000',
      'vote_fee' => 'nullable|numeric|min:0|max:100000',
      'started_at' => 'required|date|',
      'ended_at' => 'required|date|',
      'image' => 'required|image|mimes:jpeg,png,gif,jpg|max:2048',
    ]);
    if ($validator->fails()) {
      return back()->withErrors($validator->errors())->withInput();
    }

    try {
      $data = $request->all();
      $data['image'] = null;
      $new_contest = Contest::create($data);
      $new_contest->refresh();

      //adding images
      $img = $request->file('image');
      $img_ext = $img->getClientOriginalExtension();
      $img_name = sprintf("CONTEST_%s.%s", $new_contest->id, $img_ext);
      $destination_path = public_path(sprintf(sprintf("images/contest/%s", $new_contest->id)));
      $img->move($destination_path, $img_name);
      $data['image'] = $img_name;

      $new_contest->image = $data['image'];
      $new_contest->update();
      return redirect()->route('admin_list_contest')->with('success', 'Contest Added');
    } catch (\Exception $e) {
      return back()->with('error', $e->getMessage());
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
  public function edit($contest_id)
  {
    try {
      $contest = Contest::where('id', $contest_id)->firstOrFail();
      return view('contest.edit', ['contest' => $contest,]);
    } catch (ModelNotFoundException $mnt) {
      return back()->with('error', 'Contest not found');
    } catch (\Exception $e) {
      return back()->with('error', $e->getMessage());
    }
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Contest  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $contest_id)
  {
    $validator = Validator::make($request->all(), [
      'title' => 'required|string|min:5|max:250|',
      'reg_fee' => 'nullable|numeric|min:0|max:100000',
      'minimum_vote' => 'nullable|numeric|min:0|max:100000',
      'started_at' => 'required|date|',
      'ended_at' => 'required|date|',
      'image' => 'file|image|mimes:jpeg,png,gif,jpg|max:2048',
    ]);
    if ($validator->fails()) {
      return back()->withErrors($validator->errors())->withInput();
    }

    try {
      $data = $request->all();
      $updateable_contest = Contest::where('id', $contest_id)->firstOrFail();
      if ($request->hasFile('image')) {
        $data['image'] = null;
        //adding images
        $img = $request->file('image');
        $img_ext = $img->getClientOriginalExtension();
        $img_name = sprintf("CONTEST_%s.%s", bin2hex(random_bytes(15)), $img_ext);
        $destination_path = public_path(sprintf("images/contest/%s", $updateable_contest->id));
        $img->move($destination_path, $img_name);
        $data['image'] = $img_name;
        if ($updateable_contest->image != null) {
          $path = public_path(sprintf("images/contests/%s/%s", $updateable_contest->id, $updateable_contest->image));
          if (File::exists($path)) {
            File::delete($path);
          }
        }
      }
      $updateable_contest->update($data);
      return redirect()->route('admin_list_contest')->with('success', 'Contest Updated');
    } catch (\Exception $e) {
      return back()->with('error', $e->getMessage())->withInput();
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Contest  $contest_id
   * @return \Illuminate\Http\Response
   */
  public function destroy($contest_id)
  {
    try {
      $contest = Contest::where('id', $contest_id)->firstOrFail();
      if ($contest->is_active()) {
        return redirect()->route('admin_list_contest')->with('info', 'Sorry an Active Contest with contestants cannot be Deleted');
      }
      $contest->votes()->delete();
      $contest->contestants()->detach();
      if ($contest->image != null) {
        if (File::isDirectory(sprintf("images/contest/%s/", $contest->id))) {
          File::deleteDirectory(sprintf("images/contest/%s/", $contest->id));
        }
      }
      $contest->delete();
      return redirect()->route('admin_list_contest')->with('success', 'Contest Deleted');
    } catch (ModelNotFoundException $mnt) {
      return back()->with('error', 'Contest not found');
    } catch (\Exception $e) {
      return back()->with('error', $e->getMessage())->withInput();
    }
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
    if ($contest_id == null) {
      return back()->with('error', 'Contest ID cannot be null');
    }
    try {
      $contestant_id = Auth::user()->id;
      $contestant = User::where('id', $contestant_id)->firstOrFail();
      $contest = Contest::where('id', $contest_id)->firstOrFail();
      if ($contest->is_active()) {
        if ($contestant->is_profile_complete()) {
          if (!$contestant->contests()->where('contest_id', $contest_id)->exists()) {
            $contestant->contests()->attach([$contest_id]);
            return redirect()->route('list_contest_contestant', ['contest_id' => $contest_id])->with('success', 'Contest Joined Successfully.');
          } else {
            return redirect()->route('list_contest_contestant', ['contest_id' => $contest_id])->with('info', 'Already Joined Contest Earlier.');
          }
        } else {
          return redirect()->route('list_contest')->with('info', "Please Complete Missing data in your profile");
        }
      } else {
        return redirect()->route('list_contest')->with('info', 'Contest has been closed.');
      }
    } catch (Exception $e) {
      dd($e);
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\User  $contestant_id
   * @param  \App\Contest  $contest_id
   * @return \Illuminate\Http\Response
   */
  public function visit_contest_contestant($contest_id, $contestant_id)
  {
    try {
      $contest = Contest::where('id', $contest_id)->firstOrFail();
    } catch (ModelNotFoundException $mnt) {
      return back()->with('error', 'Contest not found');
    } catch (\Exception $e) {
      return back()->with('error', $e->getMessage());
    }
    try {
      $contestant = User::where('id', $contestant_id)->firstOrFail();
      return view('contestant.vote', ['contestant' => $contestant, 'contest' => $contest]);
    } catch (ModelNotFoundException $mnt) {
      return back()->with('error', 'Contestant not found');
    } catch (\Exception $e) {
      return back()->with('error', $e->getMessage());
    }
  }
}
