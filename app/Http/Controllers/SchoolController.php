<?php

namespace App\Http\Controllers;

use App\School;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class SchoolController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $sort_type = $request->filled('sort_type') ? $request->sort_type : 'asc';
    $result_count = $request->filled('result_count') ? $request->result_count : 10;

    $school_sort_type = function () use ($sort_type) {
      $school_sort_type_map = ["asc", 'desc'];
      if (in_array($sort_type, $school_sort_type_map)) {
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

    $schools = School::with('users')
      ->orderBy('created_at', $school_sort_type())
      ->paginate($item_result_count());

    return view('school.index', ['schools' => $schools]);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    return view('school.create');
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
      'name' => 'required|string|min:5|max:250|',
      'type' => 'required|string|min:5|max:250|',
      'state' => 'required|string|min:5|max:250|',
    ]);
    if ($validator->fails()) {
      return back()->withErrors($validator->errors())->withInput();
    }

    try {
      $data = $request->all();
      $new_school = School::create($data);
      return redirect()->route('admin_list_school')->with('success', 'School Created');
    } catch (\Exception $e) {
      return back()->with('error', $e->getMessage());
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\School  $school
   * @return \Illuminate\Http\Response
   */
  public function show(School $school)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\School  $school
   * @return \Illuminate\Http\Response
   */
  public function edit($school_id)
  {
    try {
      $school = School::where('id', $school_id)->firstOrFail();
      return view('school.edit', ['school' => $school,]);
    } catch (ModelNotFoundException $mnt) {
      return back()->with('error', 'school not found');
    } catch (\Exception $e) {
      return back()->with('error', $e->getMessage());
    }
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\School  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required|string|min:5|max:250|',
      'type' => 'required|string|min:5|max:250|',
      'state' => 'required|string|min:5|max:250|',
    ]);
    if ($validator->fails()) {
      return back()->withErrors($validator->errors())->withInput();
    }

    try {
      $data = $request->all();
      $updateable_school = School::where('id', $id)->firstOrFail();
      $updateable_school->update($data);
      return redirect()->route('admin_list_school')->with('success', 'School Updated');
    } catch (\Exception $e) {
      return back()->with('error', $e->getMessage());
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\School  $school
   * @return \Illuminate\Http\Response
   */
  public function destroy(School $school)
  {
    //
  }
}
