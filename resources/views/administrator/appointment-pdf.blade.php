@extends('layouts.no-navbar')

@section('extracss')
    <style>
        .table tr {
            padding: 15px;
            border-botton: 1px solid gray;
        }
        .table tr td{
            margin: 30px;
        }
    </style>
@endsection


@section('content')

    

    <table class="table">
        <tr>
            <th>Appointment</th>
            <th>Name</th>
            <th>Time</th>
            <th>Arrived</th>
            <th>Served</th>
        </tr>
        @foreach($appointment as $item)
            <tr style="padding: 20px 30px; border-bottom: 1px solid gray; margin: 20px;">
                <td>{{ $item->appointment_date }}</td>
                <td>{{ $item->user->lname }}, {{ $item->user->fname }} {{ $item->user->mname }}</td>
                <td>{{ date('h:i A', strtotime($item->schedule->time_from)) }} - {{ date('h:i A', strtotime($item->schedule->time_end)) }}</td>
                <td>
                    @if($item->is_arrived == 1)
                        <span>YES</span>
                    @else
                        <span>NO</span>
                    @endif
                </td>
                <td>
                    @if($item->is_served == 1)
                        <span>YES</span>
                    @else
                        <span>NO</span>
                    @endif
                </td>

            </tr>
        @endforeach
    </table>
   
    
@endsection
