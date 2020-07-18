@extends('layouts.app')

@section('content')
@if(Auth::user()->is_admin())
<div class="uk-container">
  <div class="uk-card uk-card-default">
    <!--=======================-->
    <div class="uk-container ">
      <table class="uk-table uk-table-responsive uk-table-divider">
        <thead>
          <tr>
            <th>#</th>
            <th>NAME</th>
            <th>TYPE</th>
            <th>STATE</th>
            <th>ACTION</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($schools as $school)
         <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$school->name}}</td>
            <td>{{$school->type}}</td>
            <td>{{$school->state}}</td>
            <td>
              <div>
                <a href="{{ route('admin_edit_school',["school_id"=>$school->id]) }}" class="uk-button uk-button-small"
                  uk-tooltip="Edit School">
                  Edit
                </a>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <!--=======================-->
    @if ($schools->hasPages())
    <div class="uk-text-center uk-card-footer">
      {!! $schools->links() !!}
    </div>
    @endif

  </div>
</div>
@endif
@endsection
