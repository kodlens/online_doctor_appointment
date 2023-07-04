@extends('layouts.app')

@section('content')
    
    @auth
        <welcome-page :is-login="true"></welcome-page>
    @else
        <welcome-page :is-login="false"></welcome-page>
    @endauth


    <footer-page></footer-page>

    
@endsection
