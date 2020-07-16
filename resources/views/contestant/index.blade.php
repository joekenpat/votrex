@extends('layouts.app')

@section('content')
<div class="uk-section uk-section-small uk-section-muted">
    <div class="uk-container">
        @isset($contestants)
        {{dd($contestants)}}
        @endisset
    </div>
</div>
@endsection
