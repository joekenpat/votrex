@extends('layouts.app')
@section('title', 'List Applications')
@section('content')
<div class="uk-container">
  <div class="uk-card uk-card-default">
    <!--=======================-->
    <div class="uk-container ">
      <table class="uk-table uk-table-responsive uk-table-divider">
        <thead>
          <tr>
            <th>#</th>
            <th>CONTEST</th>
            <th>STATUS</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($applications as $application)
          <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$application->contest->title}}</td>
            @if ($application->status =='declined')
            <td><span class="uk-label uk-label-danger">REJECTED</span></td>
            @elseif($application->status =='pending')
            <td><span class="uk-label uk-label-warning">PENDING</span></td>
            @else
            <td><span class="uk-label uk-label-success">JOINED</span></td>
            @endif
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
@endsection
