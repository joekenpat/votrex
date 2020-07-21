@extends('layouts.app')
@section('title', 'List of Schools')
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
                <a onclick="confirm_action(event, this)"  href="{{route('admin_edit_school',['school_id'=>$school->id])}}" style="color:blue" uk-tooltip="Edit School" class="uk-icon-link uk-margin-small-right" uk-icon="icon:file-edit; ratio:1.3"></span></a>
                <a onclick="confirm_action(event, this)"  href="{{route('admin_delete_school',['school_id'=>$school->id])}}" style="color:red" uk-tooltip="Delete School" class="uk-icon-link" uk-icon="icon:trash; ratio:1.3"></span></a>
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
