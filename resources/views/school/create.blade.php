@extends('layouts.app')

@section('content')
<div class="uk-section uk-section-small uk-section-muted uk-flex uk-flex-center">
  <div class="uk-card uk-card-default uk-card-body uk-width-large">
    <h2 class="uk-card-title">Add School</h2>
    <form method="POST" action="{{ route('store_school_data')  }}" class="uk-form-stacked">
      @csrf
      <div class="uk-margin">
        <label for="name" class="uk-form-label">
          {{ __('Name') }}
        </label>
        <div class="uk-form-control">
          <input class="uk-input @error('name') uk-form-danger @enderror" id="name" name="name" type="text"
            value="{{ old('name') }}" required autofocus>
          @error('name')
          <span class="uk-text-danger">{{ $message }}</span>
          @enderror
        </div>
      </div>
      <div class="uk-margin">
        <label for="name" class="uk-form-label">
          {{ __('Type') }}
        </label>
        <div class="uk-form-control">
          <input class="uk-input @error('type') uk-form-danger @enderror" id="type" name="type" type="text"
            value="{{ old('type') }}" required>
          @error('type')
          <span class="uk-text-danger">{{ $message }}</span>
          @enderror
        </div>
      </div>
      <div class="uk-margin">
        <label for="name" class="uk-form-label">
          {{ __('State') }}
        </label>
        <div class="uk-form-control">
          <select class="uk-input @error('state') uk-form-danger @enderror" id="state" name="state" type="text"
            value="{{ old('state') }}">
            <option value="">-- Select School State</option>
            @foreach (['Rivers'] as $state)
            <option value="{{strtolower($state)}}">{{$state}}</option>
            @endforeach
          </select>
          @error('state')
          <span class="uk-text-danger">{{ $message }}</span>
          @enderror
        </div>
      </div>
      <div class="uk-margin">
        <div class="uk-form-control">
          <button type="submit" class="uk-button uk-button-primary">
            {{ __('Create Contest') }}
          </button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection
