<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'FORSI') }} - @yield('title')</title>

  <!-- Styles -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>
  <div id="app">
    <div id="side_nav" uk-offcanvas="mode: push; overlay: true">
      <div class="uk-offcanvas-bar white">
        <ul class="uk-nav uk-nav-default">
          <li class="uk-active uk-text-center">
            <a class=" uk-margin-small-top uk-margin-small-bottom" href="index.html">
              <img src="/image/misc/mfi-logo.png" style="height: 100px"></a></li>
          <li>
            <a class="uk-button uk-border-pill uk-background-primary white-text uk-text-emphasis uk-width-1-1 uk-margin-small-bottom"
              href="/">{{ __('Contests') }}</a>
          </li>
          @guest
          @if (Route::has('login'))
          <li>
            <a class="uk-button uk-border-pill uk-background-primary white-text uk-width-1-1 uk-margin-small-bottom"
              href="{{ route('login') }}">{{ __('Log In') }}</a>
          </li>
          @endif
          @if (Route::has('register'))
          <li>
            <a class="uk-button uk-border-pill uk-background-primary white-text uk-text-emphasis uk-width-1-1 uk-margin-small-bottom"
              href="{{ route('register') }}">{{ __('Register') }}</a>
          </li>
          @endif
          @else
          @endguest
          @auth
          <li><a
              class="uk-button uk-border-pill uk-background-primary white-text uk-text-emphasis uk-width-1-1 uk-margin-small-bottom"
              href="
            {{ route('contestant_view_profile') }}
            ">{{ __('Home') }}</a>
          </li>
          <li><a
              class="uk-button uk-border-pill uk-background-primary white-text uk-text-emphasis uk-width-1-1 uk-margin-small-bottom"
              href="
          {{ route('contestant_edit_profile') }}
          ">{{ __('Edit Profile') }}</a>
          </li>
          @if (!Auth::User()->role=='admin')
          <li><a
              class="uk-button uk-border-pill uk-background-primary white-text uk-text-emphasis uk-width-1-1 uk-margin-small-bottom"
              href="
        {{ route('contestant_votes') }}
        ">{{ __('Your Votes') }}</a>
          </li>
          @endif
          @if (Auth::User()->role=='admin')
          <li><a
              class="uk-button uk-border-pill uk-background-primary white-text uk-text-emphasis uk-width-1-1 uk-margin-small-bottom"
              href="
              {{ route('admin_list_contest') }}
              ">{{ __('Contest List ') }}</a></li>
          <li><a
              class="uk-button uk-border-pill uk-background-primary white-text uk-text-emphasis uk-width-1-1 uk-margin-small-bottom"
              href="
                {{ route('admin_create_contest') }}
                ">{{ __('Create Contest') }}</a></li>
          <li><a
              class="uk-button uk-border-pill uk-background-primary white-text uk-text-emphasis uk-width-1-1 uk-margin-small-bottom"
              href="
                {{ route('admin_list_contestant') }}
                ">{{ __('Contestant List ') }}</a></li>
          <li><a
              class="uk-button uk-border-pill uk-background-primary white-text uk-text-emphasis uk-width-1-1 uk-margin-small-bottom"
              href="
                {{ route('admin_list_school') }}
                ">{{ __('School List ') }}</a></li>
          <li><a
              class="uk-button uk-border-pill uk-background-primary white-text uk-text-emphasis uk-width-1-1 uk-margin-small-bottom"
              href="
                  {{ route('admin_create_school') }}
                  ">{{ __('Create School ') }}</a></li>
          @endif
          <li><a
              class="uk-button uk-border-pill uk-background-primary white-text uk-text-emphasis uk-width-1-1 uk-margin-small-bottom"
              href="
                  {{ route('list_vote') }}
                  ">{{ __('Vote List') }}</a></li>
          <li>
            <a title="logout" class="uk-button uk-border-pill white-text" href="{{ route('logout') }}"
              onclick="event.preventDefault(); document.getElementById('sidebar-logout-form').submit();"
              style="background-color:#f44336;">
              Logout <span uk-icon="icon: lock; ratio:1.2;"></span>
            </a>
            <form id="sidebar-logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
            </form>
          </li>
          @endauth
        </ul>
      </div>
    </div>
    <div class="uk-background-primary uk-light">
      <nav class="uk-navbar-container uk-navbar-transparent">
        <div class="uk-container">
          <div class="uk-navbar" data-uk-navbar>
            <div class="uk-navbar-left">
              <a class="uk-navbar-item uk-logo" href="/">{{ config('app.name', 'FORSI') }}</a>

            </div>
            <div class="uk-navbar-right">
              <ul class="uk-navbar-nav">
                <!-- Authentication Links -->
                @guest
                @if (Route::has('login'))
                <li>
                  <div class="uk-animation-toggle" tabindex="0">
                    <div class="uk-padding-small">
                      <a href="/login"> <button class=" uk-animation-shake uk-button uk-button-large"
                          style="background-color:#EF7D11; border-radius: 30px; box-shadow: 0px 0px 3px 1px black;
                                      color:white;">
                          <b>LOG IN</b></button></a>
                    </div>

                </li>
                @endif
                @if (Route::has('register'))
                <li>
                  <div class="uk-animation-toggle" tabindex="0">
                    <div class="uk-padding-small">
                      <a href="/register"> <button class=" uk-animation-shake uk-button uk-button-large"
                          style="background-color:#3D9FB9; border-radius: 30px; box-shadow: 0px 0px 3px 1px black;
                                      color:white;">
                          <b>Register</b></button></a>
                    </div>

                </li>
                @endif
                @else
                <li>
                  <a href="{{ url('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    {{ __('Logout') }} <span uk-icon="icon: lock; ratio:1.2;"></span>
                  </a>
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                  </form>

                </li>
                @endguest
                <li>
                  <button class="uk-navbar-toggle uk-button uk-button-text white-text" type="button"
                    uk-toggle="target: #side_nav"><i uk-icon="icon: menu; ratio:2;"></i></button>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </nav>
    </div>

    <main>
      @if ($message = Session::get('success'))
      <div class="uk-alert-success" uk-alert>
        <a class="uk-alert-close" uk-close></a>
        <h4>Success</h4>
        <p>{{$message}}</p>
      </div>
      @endif
      @if($message = Session::get('error'))
      <div class="uk-alert-danger" uk-alert>
        <a class="uk-alert-close" uk-close></a>
        <h4>Alert</h4>
        <p>{{$message}}</p>
      </div>
      @endif
      @if($message = Session::get('info'))
      <div class="uk-alert-warning" uk-alert>
        <a class="uk-alert-close" uk-close></a>
        <h4>Notice</h4>
        <p>{{$message}}</p>
      </div>
      @endif
      @yield('content')
    </main>
  </div>
  <script src="{{ asset('js/app.js') }}" defer></script>
  @stack('bottom_scripts')
</body>

</html>
