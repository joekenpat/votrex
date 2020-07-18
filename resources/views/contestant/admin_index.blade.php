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
            <th>GENDER</th>
            <th>AGE</th>
            <th>SCHOOL</th>
            <th>LEVEL</th>
            <th>NUMBER OF CONTEST</th>
            <th>NUMBER OF VOTE</th>
            <th>ACTION</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($contestants as $contestant)
          <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$contestant->get_full_name()}}</td>
            <td>{{$contestant->gender}}</td>
            <td>{{$contestant->age}}</td>
            <td>{{$contestant->school != null?$contestant->school->name:null}}</td>
            <td>{{$contestant->sch_level}}</td>
            <td>{{$contestant->contests->count()}}</td>
            <td>{{$contestant->votes->count()}}</td>
            <td>
              <div>
                <a href="{{ route('visit_contestant',["contestant_id"=>$contestant->id]) }}" class="uk-button uk-button-small"
                  uk-tooltip="View Contestant">
                  View
                </a>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <!--=======================-->
    @if ($contestants->hasPages())
    <div class="uk-text-center uk-card-footer">
      {!! $contestants->links() !!}
    </div>
    @endif

  </div>
</div>
@endif
@endsection
