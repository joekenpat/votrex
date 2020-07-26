@extends('layouts.app')
@section('title', 'Contests')
@section('content')
@if(Auth::check() && Auth::user()->is_admin())
<div class="uk-container">
  <div class="uk-card uk-card-default">
    <!--=======================-->
    <div class="uk-container ">
      <table class="uk-table uk-table-responsive uk-table-divider">
        <thead>
          <tr>
            <th>#</th>
            <th>TITLE</th>
            <th>MIN VOTE</th>
            <th>VOTE FEE</th>
            <th>STARTS</th>
            <th>ENDS</th>
            <th>CONTESTANTS</th>
            <th>ACTION</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($contests as $contest)
          <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$contest->title}}</td>
            <td>{{floor($contest->minimum_vote)}}</td>
            <td>{{$contest->vote_fee != 0?'N'.floor($contest->vote_fee):"Free"}}</td>
            <td>{{$contest->started_at->format('Y-m-d')}}</td>
            <td>
              @if($contest->is_active())
              {{$contest->ended_at->format('Y-m-d')}}
              @else
              <span class="uk-label uk-label-warning">CLOSED</span>
              @endif
            </td>
            <td>{{$contest->contestants->count()}}</td>
            <td>
              <div>
                <a onclick="confirm_action(event, this)"
                  href="{{route('admin_edit_contest',['contest_id'=>$contest->id])}}" style="color:blue"
                  uk-tooltip="Edit Contest" class="uk-icon-link uk-margin-small-right"
                  uk-icon="icon:file-edit; ratio:1.3"></span></a>
                <a onclick="confirm_action(event, this)"
                  href="{{route('admin_delete_contest',['contest_id'=>$contest->id])}}" style="color:red"
                  uk-tooltip="Delete Contest" class="uk-icon-link" uk-icon="icon:trash; ratio:1.3"></span></a>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <!--=======================-->
    @if ($contests->hasPages())
    <div class="uk-text-center uk-card-footer">
      {!! $contests->links() !!}
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
@else
<div class="uk-container uk-margin uk-margin-top">
  <div class="uk-grid-small" uk-grid>
    @foreach ($contests as $contest)
    <div class="uk-width-1-2@m uk-width-1-1">
      <div class="uk-card uk-card-default uk-grid-collapse uk-child-width-1-2@s uk-margin my-card" uk-grid>
        <div class="uk-card-media-left uk-cover-container">
          <img
            src="{{$contest->image != null?asset(sprintf("images/contest/%s/%s",$contest->id,$contest->image)):asset("images/misc/default_avatar.png") }}"
            alt="" class="contest_image" uk-cover>
          <canvas width="600" height="400"></canvas>
        </div>
        <div>
          <div class="uk-card-body uk-text-center ">
            <h3 class="uk-card-title contest" style="color: white">{{$contest->title}}</h3>
            <p><label style="font-size: 0.7em; color: white"> From:
                {{$contest->started_at->isoFormat('MMMM Do YYYY, h:mm a')}}<br>To:
                {{$contest->ended_at->isoFormat('MMMM Do YYYY, h:mm a')}} </label>
            </p>
            <a href="{{ route('list_contest_contestant',['contest_id'=>$contest->id]) }}"
              class="uk-button uk-button-small uk-width-1-1 uk-margin-small" style="background-color:#3D9FB9; border-radius: 5px; box-shadow: 0px 0px 3px 0px black;
                                 color:white;">
              <b>View Contestants</b>
            </a>
            @if($contest->is_active())
            @auth
            @if (Auth::user()->contests()->where('contest_id', $contest->id)->exists())
            @if (Auth::user()->contests()->where('contest_id', $contest->id)->first()->pivot->status == 'pending')
            <span class="uk-label uk-label-warning">PENDING</span>
            @elseif(Auth::user()->contests()->where('contest_id', $contest->id)->first()->pivot->status == 'declined')
            <span class="uk-label uk-label-danger">REJECTED</span>
            @else
            <span class="uk-label uk-label-success">JOINED</span>
            @endif
            @else
            <a href="{{ route('join_contest',['contest_id'=>$contest->id]) }}"
              class="uk-button uk-button-small uk-width-1-1 uk-margin-small" style="background-color:#3D9FB9; border-radius: 5px; box-shadow: 0px 0px 3px 0px black;
                                    color:white;">
              <b>Join this Contest</b>
              @endif
              @else
              <a href="{{ route('join_contest',['contest_id'=>$contest->id]) }}"
                class="uk-button uk-button-small uk-width-1-1 uk-margin-small" style="background-color:#3D9FB9; border-radius: 5px; box-shadow: 0px 0px 3px 0px black;
                                  color:white;">
                <b>Join this Contest</b>
                @endif
                @else
                <span class="uk-label uk-label-warning">CLOSED</span>
                @endif
              </a>
          </div>
        </div>
      </div>
    </div>
    @endforeach
    <div class="uk-width1-1">
      <!--=======================-->
      @if ($contests->hasPages())
      <div class="uk-text-center">
        {!! $contests->links() !!}
      </div>
      @endif
    </div>
  </div>
</div>
@endif
@endsection
