@extends('layouts.app')
@section('title', 'Add new school')
@section('content')
<div class="uk-container">
  <div class="content-user">
    <div class="uk-card uk-card-default my-card">
        <form method="POST" action="{{ route('admin_save_school') }}" class="uk-form-stacked">
          <div class="uk-card-header">
            <div class="uk-width-expand">
              <h3 class="uk-card-title uk-margin-remove-bottom"><b style="color: white">Add School</b></h3>
            </div>
          </div>
          <div class="uk-card-body">
            @csrf
            <div class="uk-grid-small" uk-grid>
            <div class="uk-width-1-1 uk-width-1-1@s">
              <label for="name" class="uk-form-label form-label">
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
            <div class="uk-width-1-1 uk-width-1-2@s">
              <label for="name" class="uk-form-label form-label">
                {{ __('Type') }}
              </label>
              <div class="uk-form-control">
                <select class="uk-input @error('state') uk-form-danger @enderror" id="type" name="type">
                  <option value="">-- Select School Type</option>
                  @foreach (['university', 'polytechnic', 'college'] as $type)
                    <option value="{{strtolower($type)}}">{{$type}}</option>
                  @endforeach
                </select>
                @error('type')
                <span class="uk-text-danger">{{ $message }}</span>
                @enderror
              </div>
            </div>
            <div class="uk-width-1-1 uk-width-1-2@s">
              <label for="name" class="uk-form-label form-label">
                {{ __('State') }}
              </label>
              <div class="uk-form-control">
                <select class="uk-input @error('state') uk-form-danger @enderror" id="state" name="state">
                  <option value="">-- Select School State</option>
                  @foreach (['Rivers'] as $state)
                  <option value="{{$state}}">{{$state}}</option>
                  @endforeach
                </select>
                @error('state')
                <span class="uk-text-danger form-label">{{ $message }}</span>
                @enderror
              </div>
            </div>
            </div>
          </div>
          <div class="uk-card-footer uk-margin-bottom">
            <div class="uk-animation-toggle" tabindex="0">
              <button type="submit" class="uk-button uk-width-1-1" style="background-color:#3D9FB9;">
                <b>Add School</b></button>
            </div>
          </div>
        </form>
      </div>
  </div>
</div>
@endsection
