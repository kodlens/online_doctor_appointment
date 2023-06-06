@extends('layouts.admin')

@section('content')
    <vacation-create-edit :prop-id="{{$id}}" :prop-data='@json($data)'></vacation-create-edit>
@endsection

 