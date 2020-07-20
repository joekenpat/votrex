@extends('layouts.app')
@section('title', $contestant->get_full_name())
@section('content')
<div class="uk-container" style="padding-top: 5% ;padding-bottom:10px ;">
  <div class="uk-card uk-card-default my-card">
    <div class="uk-card-header">
      <div class="uk-width-expand">
        <h3 class="uk-card-title uk-margin-remove-bottom"><b style="color: white">{{Auth()->user()->first_name}} Details</b></h3>
      </div>
    </div>
    <div class="uk-card-body">
      <div uk-grid>
        <div class="uk-width-1-1 uk-width-1-3@m ">
          <img class="uk-border-circle uk-align-center contestant_avatar uk-width-1-1"
            src="{{$contestant->avatar != null?asset(sprintf("images/users/%s/%s",$contestant->id,$contestant->avatar)):asset("images/misc/default_avatar.png") }}">
        </div>

        <div class="uk-width-1-1 uk-width-2-3@m">
          <h2 class="my-card-name uk-padding-remove-horizontal uk-text-center"><b
              style="color:#EF7D11">{{$contestant->get_full_name()}}</b>
          </h2>
          <div class="uk-grid-divider uk-grid-collapse" uk-grid>
            <div class="uk-width-1-1 uk-width-1-2@m">
              <ul class="contestant-details uk-padding-remove-left">
                <li><b>SEX: </b>{{$contestant->gender}}</li>
                <li><b>AGE: </b>{{$contestant->age}}</li>
                <li><b>SCHOOL: </b> {{$contestant->school != null?$contestant->school->name:null}}</li>
                <li><b>FACULTY: </b>{{$contestant->sch_faculty}}</li>
                <li><b>EMAIL: </b>{{$contestant->email}}</li>
                <li><b>PHONE: </b>{{$contestant->phone}}</li>
              </ul>
            </div>
            <div class="uk-width-1-1 uk-width-1-2@m">
              <ul class="contestant-details uk-padding-remove-left">
                <li><b>BIO: </b>{{$contestant->bio}}</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  @if(count($contestant->media)> 0)

  <div class="uk-card uk-card-default my-card uk-margin-top uk-margin-top">
    <div class="uk-card-header">
      <div class="uk-width-expand">
      <h3 class="uk-card-title uk-margin-remove-bottom"><b style="color: white">{{$contestant->first_name}} Media Gallery</b></h3>
      </div>
    </div>
    <div class="uk-card-body">
      <div class="uk-child-width-1-1@m uk-child-width-1-2@s uk-child-width-1-3@m uk-child-width-1-5@l uk-grid-collapse"
        uk-grid uk-lightbox="animation: scale">
        @foreach ($contestant->media as $img)
        <div>
          <a class="uk-inline ph" href="{{asset(sprintf("images/users/%s/%s",$contestant->id, $img))}}"
            data-caption="Caption 1">
            <img class="uk-width-1-1" src="{{asset(sprintf("images/users/%s/%s",$contestant->id, $img))}}"
              style="max-height:200px; object-fit:cover;" alt="">
          </a>
        </div>
        @endforeach
      </div>
    </div>
  </div>
  @endif

</div>

@endsection
