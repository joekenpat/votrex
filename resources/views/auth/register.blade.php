@extends('layouts.app')

@section('content')
<div class="uk-section uk-section-small uk-section-muted uk-flex uk-flex-center">
  <div class="uk-card uk-card-default uk-card-body uk-width-large">
    <h2 class="uk-card-title">{{ Request::route()->getName() == 'register'? 'Contestant': 'Admin'  }} Registration</h2>
    <form method="POST" action="{{ Request::route()->getName() == 'register'? route('process_register'): route('process_admin_register')  }}" class="uk-form-stacked">
        @csrf
        <div class="uk-margin">
          <label for="name" class="uk-form-label">
            {{ __('Last Name') }}
          </label>
          <div class="uk-form-control">
            <input class="uk-input @error('last_name') uk-form-danger @enderror" id="last_name" name="last_name"
              type="text" value="{{ old('last_name') }}" required autocomplete="family-name" autofocus>
            @error('last_name')
            <span class="uk-text-danger">{{ $message }}</span>
            @enderror
          </div>
        </div>
        <div class="uk-margin">
          <label for="name" class="uk-form-label">
            {{ __('First Name') }}
          </label>
          <div class="uk-form-control">
            <input class="uk-input @error('first_name') uk-form-danger @enderror" id="first_name" name="first_name"
              type="text" value="{{ old('first_name') }}" required autocomplete="given-name">
            @error('first_name')
            <span class="uk-text-danger">{{ $message }}</span>
            @enderror
          </div>
        </div>
        @if(Request::route()->getName() == 'register')
        <div class="uk-margin">
          <label for="name" class="uk-form-label">
            {{ __('Middle Name') }}
          </label>
          <div class="uk-form-control">
            <input class="uk-input @error('middle_name') uk-form-danger @enderror" id="middle_name" name="middle_name"
              type="text" value="{{ old('middle_name') }}" autocomplete="additional-name">
            @error('middle_name')
            <span class="uk-text-danger">{{ $message }}</span>
            @enderror
          </div>
        </div>
        <div class="uk-margin">
          <label for="name" class="uk-form-label">
            {{ __('Phone') }}
          </label>
          <div class="uk-form-control">
            <input class="uk-input @error('phone') uk-form-danger @enderror" id="phone" name="phone" type="text"
              value="{{ old('phone') }}" required autocomplete="tel">
            @error('phone')
            <span class="uk-text-danger">{{ $message }}</span>
            @enderror
          </div>
        </div>
        <div class="uk-margin">
          <label for="name" class="uk-form-label">
            {{ __('Gender') }}
          </label>
          <div class="uk-form-control">
            <select class="uk-input @error('gender') uk-form-danger @enderror" id="gender" name="gender" type="text"
              value="{{ old('gender') }}">
              <option value="">-- Select Gender --</option>
              @foreach (['Male','Female'] as $gender)
              <option value="{{strtolower($gender)}}">{{$gender}}</option>
              @endforeach
            </select>
            @error('gender')
            <span class="uk-text-danger">{{ $message }}</span>
            @enderror
          </div>
        </div>
        @endif
        <div class="uk-margin">
          <label for="email" class="uk-form-label">
            {{ __('E-Mail Address') }}
          </label>
          <div class="uk-form-control">
            <input class="uk-input @error('email') uk-form-danger @enderror" id="email" name="email" type="email"
              value="{{ old('email') }}" required autocomplete="email" autofocus>
            @error('email')
            <span class="uk-text-danger">{{ $message }}</span>
            @enderror
          </div>
        </div>
        <div class="uk-margin">
          <label for="password" class="uk-form-label">
            {{ __('Password') }}
          </label>
          <div class="uk-form-control">
            <input id="password" type="password" class="uk-input @error('password') uk-form-danger @enderror"
              name="password" required autocomplete="new-password">
            @error('password')
            <span class="uk-text-danger">{{ $message }}</span>
            @enderror
          </div>
        </div>
        <div class="uk-margin">
          <div class="uk-form-control">
            <button type="submit" class="uk-button uk-button-primary">
              {{ __('Register') }}
            </button>
          </div>
        </div>
      </form>
  </div>
</div>
@endsection
