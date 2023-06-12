@extends('layouts.app')

@section('content')
    <my-appointment-reschedule prop-data='@json($data)'></my-appointment-reschedule>
@endsection
