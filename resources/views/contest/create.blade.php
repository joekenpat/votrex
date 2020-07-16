@extends('layouts.app')

@section('content')
<div class="uk-section uk-section-small uk-section-muted uk-flex uk-flex-center">
  <div class="uk-card uk-card-default uk-card-body uk-width-large">
    <h2 class="uk-card-title">Create Contest</h2>
    <form method="POST" action="{{ route('store_contest_data')  }}" class="uk-form-stacked">
        @csrf
        <div class="uk-margin">
          <label for="name" class="uk-form-label">
            {{ __('Title') }}
          </label>
          <div class="uk-form-control">
            <input class="uk-input @error('title') uk-form-danger @enderror" id="title" name="title"
              type="text" value="{{ old('title') }}" required  autofocus>
            @error('title')
            <span class="uk-text-danger">{{ $message }}</span>
            @enderror
          </div>
        </div>
        <div class="uk-margin">
          <label for="name" class="uk-form-label">
            {{ __('Reg Fee') }}
          </label>
          <div class="uk-form-control">
            <input class="uk-input @error('reg_fee') uk-form-danger @enderror" id="reg_fee" name="reg_fee"
              type="text" value="{{ old('reg_fee') }}" required>
            @error('reg_fee')
            <span class="uk-text-danger">{{ $message }}</span>
            @enderror
          </div>
        </div>
        <div class="uk-margin">
          <label for="name" class="uk-form-label">
            {{ __('Vote Fee') }}
          </label>
          <div class="uk-form-control">
            <input class="uk-input @error('vote_fee') uk-form-danger @enderror" id="vote_fee" name="vote_fee"
              type="text" value="{{ old('vote_fee') }}" >
            @error('vote_fee')
            <span class="uk-text-danger">{{ $message }}</span>
            @enderror
          </div>
        </div>
        <div class="uk-margin">
          <label for="name" class="uk-form-label">
            {{ __('Start Date') }}
          </label>
          <div class="uk-form-control">
            <input class="uk-input @error('started_at') uk-form-danger @enderror" id="started_at" name="started_at"
              type="text" value="{{ old('started_at') }}">
            @error('started_at')
            <span class="uk-text-danger">{{ $message }}</span>
            @enderror
          </div>
        </div>
        <div class="uk-margin">
          <label for="name" class="uk-form-label">
            {{ __('End Date') }}
          </label>
          <div class="uk-form-control">
            <input class="uk-input @error('ended_at') uk-form-danger @enderror" id="ended_at" name="ended_at"
              type="text" value="{{ old('ended_at') }}">
            @error('ended_at')
            <span class="uk-text-danger">{{ $message }}</span>
            @enderror
          </div>
        </div>
        <div class="uk-margin">
          <label for="image" class="uk-form-label">
            {{ __('Image') }}
          </label>
          <div class="uk-form-control">
            <input name="image" placeholder="Select file" class="uk-input @error('image')  uk-form-danger @enderror"
              id="image" accept=".jpg, .png, .jpeg" type="file" />
            @error('image')
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
