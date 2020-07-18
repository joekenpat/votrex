@extends('layouts.app')

@section('content')
<div class="uk-container uk-margin-bottom" style="padding-top: 5% ;padding-bottom:10px ;">
  <div class="uk-card uk-card-default my-card uk-margin-bottom">
    <div class="uk-card-header">
      <div class="uk-width-expand">
        <h3 class="uk-card-title uk-margin-remove-bottom"><b style="color: white">Contestant Details</b></h3>
      </div>
    </div>
    <div class="uk-card-body">
      <div uk-grid>
        <div class="uk-width-1-1 uk-width-1-3@m ">
          <img class="uk-border-circle uk-align-center" width="300" height="300" style=" border: 4px solid white"
            src="{{$contestant->avatar != null?URL::to(sprintf("images/users/%s/%s",$contestant->id,$contestant->avatar)):asset("images/misc/default_avatar.png") }}">
        </div>

        <div class="uk-width-1-1 uk-width-2-3@m">
          <h2 class="my-card-name uk-padding-remove-horizontal uk-text-center"><b
              style="color:#EF7D11">{{$contestant->get_full_name()}}</b>
          </h2>
          <div class=" uk-grid-collapse" uk-grid>
            <div class="uk-width-1-1 uk-width-1-2@m">
              <ul class="contestant-details uk-padding-remove-left">
                <li><b>SEX: </b>{{$contestant->sex}}</li>
                <li><b>AGE: </b>{{$contestant->sex}}</li>
                <li><b>SCHOOL: </b> {{$contestant->school->name}}</li>
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
        <h3 class="uk-card-title uk-margin-remove-bottom"><b style="color: white">Contestant Media Gallery</b></h3>
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

  <div class="uk-card uk-card-default my-card uk-margin-top">
    <div class="uk-card-header">
      <div class="uk-width-expand">
        <h3 class="uk-card-title uk-margin-remove-bottom"><b style="color: white">Vote For
            {{$contestant->first_name}}</b></h3>
      </div>
    </div>
    <div class="uk-card-body">
      <form class="uk-grid-small content" method="POST"
        action="{{route('pay')}}" uk-grid>
        @csrf
        <div class="uk-width-1-1 uk-width-1-2@s">
          <label class="uk-form-label form-label" for="first_name">Your First Name</label>
          <input class="uk-input" type="text" id="first_name" name="first_name" required
            autocomplete="given-name">
        </div>
        <div class="uk-width-1-1 uk-width-1-2@s">
          <label class="uk-form-label form-label" for="last_name">Your Last Name</label>
          <input class="uk-input" type="text" id="first_name" name="last_name" required autocomplete="family-name">
        </div>
        <div class="uk-width-1-1 uk-width-1-2@s">
          <label class="uk-form-label form-label" for="email">Your Email</label>
          <input class="uk-input" type="email" id="email" name="email" required autocomplete="email">
        </div>
        <div class="uk-width-1-1 uk-width-1-2@s">
          <label class="uk-form-label form-label" for="quantity">Number of Vote</label>
          <input class="uk-input" type="number" id="xquantity" value="1" name="xquantity" min="1"
            onkeyup="calc_vote_amt()" onchange="calc_vote_amt()">
        </div>
        <input type="hidden" name="amount" id="amount" value="{{$contest->vote_fee}}">
        <input type="hidden" name="quantity" value="1">
        <input type="hidden" name="contest_id" value="{{$contest->id}}">
        <input type="hidden" name="contestant_id" value="{{$contestant->id}}">
        <input type="hidden" name="currency" value="NGN">
        <input type="hidden" name="metadata"
          value="{{ json_encode(['contestant_id' => $contestant->id,'contest_id' => $contest->id,]) }}">
        <input type="hidden" name="reference" value="{{ Paystack::genTranxRef() }}">
        <div class="uk-width-2-3 uk-align-center uk-animation-toggle" tabindex="0">
          <button type="submit" class="uk-button uk-width-1-1" style="background-color:#3D9FB9; color:white;">
            <b>Vote</b> N<span id="vote_amount">{{$contest->vote_fee}}</span></button>
        </div>

      </form>

    </div>
  </div>

</div>
</div>
@endsection
@push('bottom_scripts')

<script>
  function calc_vote_amt(){
    document.getElementById('vote_amount').innerHTML = document.getElementById('xquantity').value * {{ $contest->vote_fee }};
    document.getElementById('amount').value = (document.getElementById('xquantity').value * {{ $contest->vote_fee}}) * 100;
  }
</script>

@endpush
