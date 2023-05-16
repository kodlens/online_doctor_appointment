@extends('layouts.no-navbar')

@section('content')
    <otp-entry prop-user="{{ $user }}"></otp-entry>
@endsection
