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
    // $status = $request->filled('status') ? $request->status : null;
    $sort_type = $request->filled('sort_type') ? $request->sort_type : 'asc';
    $result_count = $request->filled('result_count') ? $request->result_count : 10;

    // $school_status = function () use ($status) {
    //   $school_status_map = [
    //     'active',
    //     'inactive',
    //   ];
    //   if (in_array($status, $school_status_map)) {
    //     return $status;
    //   } else {
    //     return 'active';
    //   }
    // };

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

    try {
      $schools = School::with('users')
        // ->where('status', $school_status())
        ->orderBy('created', $school_sort_type())
        ->paginate($item_result_count());
      $success['data'] = $schools;
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
        'name' => 'required|string|min:5|max:250|',
        'type' => 'required|string|min:5|max:250|',
      ]);
      if ($validator->fails()) {
        return response()->json([
          'error' => $validator->errors()
        ], Response::HTTP_BAD_REQUEST);
      }

      try {
        $data = $request->all();
        $new_school = School::create($data);
        $new_school->refresh();

        $success['data'] = $new_school;
        return response()->json([
          'success' => $success,
        ], Response::HTTP_OK);
      } catch (ModelNotFoundException $mnt) {
        return response()->json([
          'error' => 'School not Found',
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
    public function edit(School $school)
    {
        //
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
      ]);
      if ($validator->fails()) {
        return response()->json([
          'error' => $validator->errors()
        ], Response::HTTP_BAD_REQUEST);
      }

      try {
        $data = $request->all();
        $updateable_school = School::where('id', $id)->firstOrFail();
        $updateable_school->update($data);
        $updateable_school->refresh();

        $success['data'] = $updateable_school;
        return response()->json([
          'success' => $success,
        ], Response::HTTP_OK);
      } catch (ModelNotFoundException $mnt) {
        return response()->json([
          'error' => 'School not Found',
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
     * @param  \App\School  $school
     * @return \Illuminate\Http\Response
     */
    public function destroy(School $school)
    {
        //
    }
}
