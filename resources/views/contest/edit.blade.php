@extends('layouts.app')

@section('content')
<div class="uk-section uk-section-small uk-section-muted uk-flex uk-flex-center">
  <div class="uk-card uk-card-default uk-card-body uk-width-large">
    <h2 class="uk-card-title">Edit Contest</h2>
    <form method="POST" action="{{ route('update_contest_data',['contest_id'=>$contest_id])  }}" class="uk-form-stacked">
      @csrf
      <div class="uk-margin">
        <label for="name" class="uk-form-label">
          {{ __('Title') }}
        </label>
        <div class="uk-form-control">
          <input class="uk-input @error('title') uk-form-danger @enderror" id="title" name="title" type="text"
            value="{{ old('title') != null||''? old('title'): $contest->title }}" required autofocus>
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
          <input class="uk-input @error('reg_fee') uk-form-danger @enderror" min="0" max="9999999" id="reg_fee" name="reg_fee"
            type="text" value="{{ old('reg_fee') != null||''? old('reg_fee'): $contest->reg_fee }}" required>
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
          <input class="uk-input @error('vote_fee') uk-form-danger @enderror" min="0" max="9999999" id="vote_fee" name="vote_fee" type="text"
            value="{{ old('vote_fee') != null||''? old('vote_fee'): $contest->vote_fee }}" required>
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
            type="text" value="{{ old('started_at') != null||''? old('started_at'): $contest->started_at->format('Y-m-d H:i:s') }}" required>
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
          <input class="uk-input @error('ended_at') uk-form-danger @enderror" id="ended_at" name="ended_at" type="text"
            value="{{ old('ended_at') != null||''? old('ended_at'): $contest->ended_at->format('Y-m-d H:i:s') }}" required>
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
            {{ __('Update Contest') }}
          </button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection
