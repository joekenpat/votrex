@extends('layouts.app')

@section('content')
<div class="uk-container">
  <div class="content-user">
    <div class="uk-card uk-card-default my-card">
      <form method="POST" enctype="multipart/form-data"
        action="{{ route('admin_update_contest',['contest_id'=>$contest->id])  }}" class="uk-form-stacked">
        <div class="uk-card-header">
          <div class="uk-width-expand">
            <h3 class="uk-card-title uk-margin-remove-bottom"><b style="color: white">Edit Contest</b></h3>
          </div>
        </div>
        <div class="uk-card-body">
          @csrf
          <div class="uk-grid-small" uk-grid>
            <div class="uk-width-1-1">
              <img class="uk-border-rounded uk-align-center" width="250" height="250" style=" border: 4px solid white"
                src="{{$contest->image != null?asset(sprintf("images/contest/%s/%s",$contest->id,$contest->image)):asset("images/misc/default_avatar.png") }}">
            </div>
            <div class="uk-width-1-1 uk-width-1-1">
              <label for="title" class="uk-form-label form-label">
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
            <div class="uk-width-1-1 uk-width-1-2@s">
              <label for="reg_fee" class="uk-form-label form-label">
                {{ __('Reg Fee') }}
              </label>
              <div class="uk-form-control">
                <input class="uk-input @error('reg_fee') uk-form-danger @enderror" min="0" id="reg_fee"
                  name="reg_fee" type="text" value="{{ old('reg_fee') != null||''? old('reg_fee'): $contest->reg_fee }}"
                  required>
                @error('reg_fee')
                <span class="uk-text-danger">{{ $message }}</span>
                @enderror
              </div>
            </div>
            <div class="uk-width-1-1 uk-width-1-2@s">
              <label for="vote_fee" class="uk-form-label form-label">
                {{ __('Vote Fee') }}
              </label>
              <div class="uk-form-control">
                <input class="uk-input @error('vote_fee') uk-form-danger @enderror" min="0" id="vote_fee"
                  name="vote_fee" type="text"
                  value="{{ old('vote_fee') != null||''? old('vote_fee'): $contest->vote_fee }}" required>
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
                <input class="uk-input @error('started_at') uk-form-danger @enderror" id="started_at" name="started_at"
                  type="text"
                  value="{{ old('started_at') != null||''? old('started_at'): $contest->started_at->format('Y-m-d') }}"
                  required>
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
                  type="text"
                  value="{{ old('ended_at') != null||''? old('ended_at'): $contest->ended_at->format('Y-m-d') }}"
                  required>
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
              <b>Update Contest</b></button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
