@extends('base')

@section('body')

<div id="app">
    <Home :user="{{ json_encode(session('authName')) }}" :provider-id="{{ json_encode(session('authId')) }}"></Home>
</div>

@endsection
