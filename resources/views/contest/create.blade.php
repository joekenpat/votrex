@extends('layouts.app')
@section('title', 'Add Contest')
@section('content')
<div class="uk-container">
  <div class="content-user">
    <div class="uk-card uk-card-default my-card">
        <form method="POST" action="{{ route('admin_save_contest')  }}" enctype="multipart/form-data" class="uk-form-stacked">
          <div class="uk-card-header">
            <div class="uk-width-expand">
              <h3 class="uk-card-title uk-margin-remove-bottom"><b style="color: white">Create Contest</b></h3>
            </div>
          </div>
          <div class="uk-card-body">
            @csrf
            <div class="uk-grid-small" uk-grid>
              <div class="uk-width-1-1">
                <label for="title" class="uk-form-label form-label">
                  {{ __('Title') }}
                </label>
                <div class="uk-form-control">
                  <input class="uk-input @error('title') uk-form-danger @enderror" id="title" name="title" type="text"
                    value="{{ old('title') }}" required autofocus>
                  @error('title')
                  <span class="uk-text-danger">{{ $message }}</span>
                  @enderror
                </div>
              </div>
              <div class="uk-width-1-1 uk-width-1-2@s">
                <label for="minimum_vote" class="uk-form-label form-label">
                  {{ __('Minimum Vote') }}
                </label>
                <div class="uk-form-control">
                  <input class="uk-input @error('minimum_vote') uk-form-danger @enderror" id="minimum_vote" name="minimum_vote"
                    type="number" min="0" value="{{ old('minimum_vote')?:0 }}" required>
                  @error('minimum_vote')
                  <span class="uk-text-danger">{{ $message }}</span>
                  @enderror
                </div>
              </div>
              <div class="uk-width-1-1 uk-width-1-2@s">
                <label for="vote_fee" class="uk-form-label form-label">
                  {{ __('Vote Fee') }}
                </label>
                <div class="uk-form-control">
                  <input class="uk-input @error('vote_fee') uk-form-danger @enderror" id="vote_fee" name="vote_fee"
                    type="number" min="0" value="{{ old('vote_fee')?:0 }}">
                  @error('vote_fee')
                  <span class="uk-text-danger">{{ $message }}</span>
                  @enderror
                </div>
              </div>
              <div class="uk-width-1-1 uk-width-1-2@s">
                <label for="started_at" class="uk-form-label form-label">
                  {{ __('Start Date') }}
                </label>
                <div class="uk-form-control">
                  <input class="uk-input @error('started_at') uk-form-danger @enderror" id="started_at"
                    name="started_at" type="date" value="{{ old('started_at') }}">
                  @error('started_at')
                  <span class="uk-text-danger">{{ $message }}</span>
                  @enderror
                </div>
              </div>
              <div class="uk-width-1-1 uk-width-1-2@s">
                <label for="ended_at" class="uk-form-label form-label">
                  {{ __('End Date') }}
                </label>
                <div class="uk-form-control">
                  <input class="uk-input @error('ended_at') uk-form-danger @enderror" id="ended_at" name="ended_at"
                    type="date" value="{{ old('ended_at') }}">
                  @error('ended_at')
                  <span class="uk-text-danger">{{ $message }}</span>
                  @enderror
                </div>
              </div>
              <div class="uk-width-1-1">
                <div class="uk-width-1-1" uk-form-custom="target: true">
                  <label class="uk-form-label form-label" for="Contest">Contest Image</label>
                  <input type="file" accept=".jpeg,.gif,.jpg,.png" id="image" name="image">
                  <input class="uk-input @error('image') uk-form-danger @enderror uk-width-1-1"
                    placeholder="Select Contest image" disabled>
                </div>
                @error('image')
                <span class="uk-text-danger">{{ $message }}</span>
                @enderror
              </div>
            </div>
          </div>
          <div class="uk-card-footer uk-margin-bottom">
            <div class="uk-animation-toggle" tabindex="0">
              <button type="submit" class="uk-button uk-width-1-1" style="background-color:#3D9FB9;">
                <b>Create Contest</b></button>
            </div>
          </div>
        </form>
      </div>
    </div>
</div>
@endsection
