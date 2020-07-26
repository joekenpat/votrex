@extends('layouts.app')
@section('title', 'List of Declined Application')
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
            <th>CONTEST</th>
            <th>SCHOOL</th>
            <th>ACTION</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($applications as $application)
          <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$application->user->get_full_name()}}</td>
            <td>{{$application->user->gender}}</td>
            <td>{{$application->user->age}}</td>
            <td>{{$application->contest->title}}</td>
            <td>{{$application->user->school != null?$application->user->school->name:null}}</td>
            <td>
              <div>
                <a onclick="confirm_action(event, this)"
                  href="{{ route('visit_contestant',["contestant_id"=>$application->user_id]) }}" style="color:blue"
                  uk-tooltip="View Applicant" class="uk-icon-link uk-margin-small-right"
                  uk-icon="icon:user; ratio:1.3"></span></a>
                <a onclick="confirm_action(event, this)"
                  href="{{ route('admin_set_application',["contest_id"=>$application->contest_id,"contestant_id"=>$application->user_id,'status'=>'approved']) }}"
                  style="color:green" uk-tooltip="Approve Contestant" class="uk-icon-link uk-margin-small-right"
                  uk-icon="icon:check; ratio:1.3"></span></a>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <!--=======================-->
    @if ($applications->hasPages())
    <div class="uk-text-center uk-card-footer">
      {!! $applications->links() !!}
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
