@extends('layouts.admin')

@section('content')

    @if($max > 0)
        <max-patients
            :prop-max="{{ $max }}"> 
        </max-patients>

    @else
        <max-patients
            :prop-max="0"> 
        </max-patients>
    @endif
    
    
@endsection
