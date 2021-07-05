@extends('base')

@section('body')

<div id="app">
    <Home :user="{{ json_encode(session('authName')) }}"></Home>
</div>

@endsection
