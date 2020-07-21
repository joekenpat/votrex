@extends('layouts.app')
@section('title', 'Login now')
@section('content')
<div class="uk-container uk-padding-small content1">
  <div uk-grid>
    <div class="uk-width-1-1 ">
      <div class="uk-card uk-card-default uk-card-body my-card">
        <h2 class="uk-card-title" style="color:white;">Login</h2>
        <form method="POST" action="{{ route('login')  }}" class="uk-form-stacked">
          @csrf
          <div class="uk-margin">
            <label for="email" class="uk-form-label form-label">
              {{ __('E-Mail Address') }}
            </label>
            <div class="uk-form-control">
              <div class="uk-inline uk-width-1-1">
                <span class="uk-form-icon" uk-icon="icon: mail" style="color: white"></span>
                <input class="uk-input @error('email') uk-form-danger @enderror" name="email"
                  id="email" type="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
              </div>
              @error('email')
              <span class="uk-text-danger">{{ $message }}</span>
              @enderror
            </div>
          </div>
          <div class="uk-margin">
            <label for="password" class="uk-form-label form-label">
              {{ __('Password') }}
            </label>
            <div class="uk-form-control">
              <div class="uk-inline uk-width-1-1">
                <span class="uk-form-icon" uk-icon="icon: lock" style="color: white"></span>
                <input id="password" type="password"
                  class="uk-input @error('password') uk-form-danger @enderror" name="password" required
                  autocomplete="current-password">
              </div>
              @error('password')
              <span class="uk-text-danger">{{ $message }}</span>
              @enderror
            </div>
          </div>
          <div class="uk-margin">
            <div class="uk-form-control">
              <input class="uk-checkbox" type="checkbox" name="remember" id="remember"
                {{ old('remember') ? 'checked' : '' }}>
              <label for="remember" class="form-label">
                {{ __('Remember Me') }}
              </label>
            </div>
          </div>
          <div class="uk-margin">
            <div class="uk-form-control uk-text-center">
              <button type="submit" style="background-color:#3D9FB9;" class="uk-button uk-button-primary uk-width-1-1">
                {{ __('Log In') }}
              </button>

              @if (Route::has('password.request'))
              <a class="uk-button uk-button-link uk-margin-left" href="{{ route('password.request') }}">
                {{ __('Forgot Your Password?') }}
              </a>
              @endif
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
