@extends('layouts.app')

@section('content')
<div class="uk-text-center">
  <form>
    <div class="uk-margin-top ">
      <div class="uk-inline">
        <button style="background-color:#213176;  border-radius:30px ; border: none; "
          class="uk-form-icon uk-form-icon-flip remove-highlight" type="button"><i class="mdi  mdi-account-search"
            style="color:white; font-size: 30px;"></i></button>
        <input class="uk-input" type="text" style="border-radius:18px ;">
      </div>
    </div>
  </form>
</div>

<div class="uk-container contestants-card">
  <div uk-grid>
    @foreach ($contestants as $contestant)
    <div class="uk-width-1-2@s uk-width-1-4@m">
      <div class="uk-card  uk-card-default my-card ">
        <div class="uk-width-auto uk-text-center ">
          <img class="uk-border-circle" width="100" height="100" style="margin-top:-20px; border: 4px 4px 4px black"
            src="{{$contestant->avatar != null?asset(sprintf("images/users/%s/%s",$contestant->id,$contestant->avatar)):asset("images/misc/default_avatar.png") }}">
        </div>
        <div class="uk-card-body uk-text-center uk-padding-small">
          <h5 class="my-card-name"><b style="color:white">{{$contestant->get_full_name()}}</b></h5>
          <div class="uk-flex uk-flex-center">
            <p class="my-card-text  uk-margin-left"><b style="color:#EF7D11"><label>Votes:</label>
                {{$contestant->votes()->where('contest_id', $contest->id)->where('status', 'valid')->sum('quantity')}}</b></p>
            <p class="my-card-text  uk-margin-left"><b style="color:#EF7D11"><label>Contestant No:</label>
                {{($loop->iteration * $contestants->currentPage())}}</b></p>
          </div>
          <a href="{{route('visit_contest_contestant',['contest_id'=>$contest->id,'contestant_id'=>$contestant->id])}}"
            class="uk-button uk-button-small" style="background-color:#3D9FB9; border-radius: 10px; box-shadow: 0px 0px 10px 0px black;
                    color:white;">
            <b>Vote</b></a>
        </div>
      </div>
    </div>
    @endforeach
    @if ($contestants->hasPages())
    <div class="uk-text-center uk-width-1-1">
      {!! $contestants->links() !!}
    </div>
    @endif
  </div>
</div>

@endsection
