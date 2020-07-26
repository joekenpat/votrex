@extends('layouts.app')
@section('title', 'Your Dashboard')
@section('content')
@if (Auth::user()->is_admin())
<div class="uk-container uk-padding-small">
  <div class="uk-child-width-1-3@l uk-child-width-1-2@m uk-child-width-1-1@s uk-text-center" uk-grid>
    <div class="">
      <a href="{{route('admin_list_contest')}}" style="text-decoration:none;">
        <div class="uk-card uk-card-default uk-card-body  uk-text-center">
          <h3 class="uk-card-title">CONTEST</h3>
          <div class="uk-flex uk-flex-center">

            <p class="uk-width-1-3 uk-border-circle  uk-padding-medium count">{{$contest_count}}</p>
          </div>
        </div>
      </a>
    </div>
    <div class="">
      <a href="{{route('admin_list_contestant')}}" style="text-decoration:none;">
        <div class="uk-card uk-card-default uk-card-body  uk-text-center">
          <h3 class="uk-card-title">CONTESTANTS</h3>
          <div class="uk-flex uk-flex-center">

            <p class="uk-width-1-3 uk-border-circle  uk-padding-medium count">{{$contestant_count}}</p>
          </div>
        </div>
      </a>
    </div>
    <div class="">
      <a href="{{route('list_vote')}}" style="text-decoration:none;">
        <div class="uk-card uk-card-default uk-card-body  uk-text-center">
          <h3 class="uk-card-title">VOTES</h3>
          <div class="uk-flex uk-flex-center">

            <p class="uk-width-1-3 uk-border-circle  uk-padding-medium count">{{$vote_count}}</p>
          </div>
        </div>
      </a>
    </div>
    <div class="">
      <a href="{{route('admin_list_school')}}" style="text-decoration:none;">
        <div class="uk-card uk-card-default uk-card-body  uk-text-center">
          <h3 class="uk-card-title">SCHOOL</h3>
          <div class="uk-flex uk-flex-center">

            <p class="uk-width-1-3 uk-border-circle  uk-padding-medium count">{{$school_count}}</p>
          </div>
        </div>
      </a>
    </div>
    <div class="">
      <a href="{{route('admin_get_application',['status'=>'pending'])}}" style="text-decoration:none;">
        <div class="uk-card uk-card-default uk-card-body  uk-text-center">
          <h3 class="uk-card-title">APPLICATIONS</h3>
          <div class="uk-flex uk-flex-center">

            <p class="uk-width-1-3 uk-border-circle  uk-padding-medium count">{{$pending_application_count}}</p>
          </div>
        </div>
      </a>
    </div>
  </div>
</div>
@else
<div class="uk-container" style="padding-top: 5% ;padding-bottom:10px ;">
  <div class="uk-card uk-card-default my-card uk-margin-bottom">
    <div class="uk-card-header">
      <div class="uk-width-expand">
        <h3 class="uk-card-title uk-margin-remove-bottom"><b style="color: white">Your Details</b></h3>
      </div>
    </div>
    <div class="uk-card-body">
      <div uk-grid>
        <div class="uk-width-1-1 uk-width-1-3@m ">
          <img class="uk-border-circle uk-align-center contestant_avatar uk-width-1-1"
            src="{{Auth()->user()->avatar != null?asset(sprintf("images/users/%s/%s",Auth()->user()->id,Auth()->user()->avatar)):asset("images/misc/default_avatar.png") }}">
        </div>

        <div class="uk-width-1-1 uk-width-2-3@m">
          <h2 class="my-card-name uk-padding-remove-horizontal uk-text-center"><b
              style="color:#EF7D11">{{Auth()->user()->get_full_name()}}</b>
          </h2>
          <div class="uk-grid-divider uk-grid-collapse" uk-grid>
            <div class="uk-width-1-1">
              <ul class="contestant-details uk-padding-remove-left">
                <li><b>SEX: </b>{{Auth()->user()->gender}}</li>
                <li><b>AGE: </b>{{Auth()->user()->age}}</li>
                <li><b>SCHOOL: </b> {{Auth()->user()->school != null?Auth()->user()->school->name:null}}</li>
                <li><b>FACULTY: </b>{{Auth()->user()->sch_faculty}}</li>
                <li><b>EMAIL: </b>{{Auth()->user()->email}}</li>
                <li><b>PHONE: </b>{{Auth()->user()->phone}}</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  @if(Auth()->user()->bio != null || Auth()->user()->bio != "")

  <div class="uk-card uk-card-default my-card uk-margin-top uk-margin-top">
    <div class="uk-card-header">
      <div class="uk-width-expand">
        <h3 class="uk-card-title uk-margin-remove-bottom"><b style="color: white">Your
            Bio</b></h3>
      </div>
    </div>
    <div class="uk-card-body">
      {{Auth()->user()->bio}}
    </div>
  </div>
  @endif

  @if(count(Auth()->user()->media)> 0)

  <div class="uk-card uk-card-default my-card uk-margin-top">
    <div class="uk-card-header">
      <div class="uk-width-expand">
        <h3 class="uk-card-title uk-margin-remove-bottom"><b style="color: white">Your Media Gallery</b></h3>
      </div>
    </div>
    <div class="uk-card-body">
      <div class="uk-child-width-1-5@m k-child-width-1-1@m uk-grid-collapse  " uk-grid uk-lightbox="animation: scale">
        @foreach (Auth()->user()->media as $img)
        <div>
          <a class="uk-inline ph" href="{{asset(sprintf("images/users/%s/%s",Auth()->user()->id, $img))}}"
            data-caption="Caption 1">
            <img class="uk-width-1-1" src="{{asset(sprintf("images/users/%s/%s",Auth()->user()->id, $img))}}"
              style="max-height:200px; object-fit:cover;" alt="">
          </a>
        </div>
        @endforeach
      </div>
    </div>
  </div>
  @endif

</div>
@endif
@endsection
