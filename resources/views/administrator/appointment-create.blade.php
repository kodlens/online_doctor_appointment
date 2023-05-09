@extends('layouts.admin')

@section('content')

    <appointment-create :prop-id="{{$id}}" 
        prop-appointment='@json($appointment)'
        prop-appointment-string='@json($appointment)'>
    </appointment-create>
    
@endsection
