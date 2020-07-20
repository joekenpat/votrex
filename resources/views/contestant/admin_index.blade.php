@extends('layouts.app')
@section('title', 'List of Users')
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
            <th>EMAIL</th>
            <th>PHONE</th>
            <th>ROLE</th>
            <th>NUMBER OF CONTEST</th>
            <th>NUMBER OF VOTE</th>
            <th>ACTION</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($contestants as $contestant)
          @if ($contestant->is_admin())
          <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$contestant->get_full_name()}}</td>
            <td>N/A</td>
            <td>N/A</td>
            <td>{{$contestant->email}}</td>
            <td>{{$contestant->phone}}</td>
            <td><span class="uk-label">ADMIN</span></td>
            <td>N/A</td>
            <td>N/A</td>
            <td>N/A</td>
          </tr>
          @else
          <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$contestant->get_full_name()}}</td>
            <td>{{$contestant->gender}}</td>
            <td><span class="uk-label uk-label-warning">USER</span></td>
            <td>{{$contestant->age}}</td>
            <td>{{$contestant->school != null?$contestant->school->name:null}}</td>
            <td>{{$contestant->sch_level}}</td>
            <td>{{$contestant->contests->count()}}</td>
            <td>{{$contestant->votes->count()}}</td>
            <td>
              <div>
                <a onclick="confirm_action(event, this)" href="{{ route('visit_contestant',["contestant_id"=>$contestant->id]) }}" style="color:blue" uk-tooltip="View User" class="uk-icon-link uk-margin-small-right" uk-icon="icon:user; ratio:1.3"></span></a>
              </div>
            </td>
          </tr>
          @endif
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
@push('bottom_scripts')
<script>
  function confirm_action(e,t){
  e.preventDefault();
  e.target.blur();
  var self_link = t.getAttribute('href')
  var self_action = t.getAttribute('uk-tooltip')
  UIkit.modal.confirm(`Do you want to ${self_action}!`).then(function () {
      e.isDefaultPrevented = function(){ return false; }
    // retrigger with the exactly same event data
    location.href = self_link
  }, function () {
  });
  }
</script>
@endpush
@endif
@endsection
