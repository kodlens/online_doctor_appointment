<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="/js/app.js" defer></script>

    <!-- Fonts -->
{{--    <link rel="dns-prefetch" href="//fonts.gstatic.com">--}}
{{--    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">--}}

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">
    <link href="/css/admin.css" rel="stylesheet">


    {{-- <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin> --}}
    {{-- <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@300&family=Ubuntu:wght@300&display=swap" rel="stylesheet"> --}}

    <style>

        

    </style>

</head>
<body>
    @php
        $role = Auth::user()->role;
    @endphp
    <div id="app">

        <b-navbar class="is-primary">
            <template #brand>
                <b-navbar-item class="has-text-weight-bold">
                    ADMINISTRATOR
                </b-navbar-item>
            </template>
            <template #start>
                
            </template>
    
            <template #end>
                <b-navbar-item href="/dashboard">
                    Home
                </b-navbar-item>
                <b-navbar-item href="/appointments"
                    class="{{ (request()->is('appointments*')) ? 'active' : '' }}">
                    Appointments
                </b-navbar-item>
                <b-navbar-item href="/walkin"
                    class="{{ (request()->is('walkin*')) ? 'active' : '' }}">
                    Walk-in
                </b-navbar-item>
                @if($role == 'ADMINISTRATOR')
                    <b-navbar-dropdown label="Setting"
                        class="{{ (request()->is('schedules*')) ? 'active' : '' }}">
                        <b-navbar-item href="/schedules">
                            Setup Schedule
                        </b-navbar-item>
                        <b-navbar-item href="/max-patients">
                            Set Max Patient
                        </b-navbar-item>
                        <b-navbar-item href="/archive-appointments">
                            Archive Appointment
                        </b-navbar-item>
                        <b-navbar-item href="/vacations">
                            Vacation
                        </b-navbar-item>
                    </b-navbar-dropdown>
                @endif


                <b-navbar-dropdown label="Reports"
                    class="{{ (request()->is('reports*')) ? 'active' : '' }}">
                    <b-navbar-item href="/report-appointments">
                        Appointments
                    </b-navbar-item>
                    <b-navbar-item href="/report-no-serve-unserve">
                        Patient Served and Unserved
                    </b-navbar-item>
                    <b-navbar-item href="/report-patient-by-location">
                        Patient By Location
                    </b-navbar-item>
                    <b-navbar-item href="/report-appointment-no-show">
                        Appointment No Show
                    </b-navbar-item>
                    <b-navbar-item href="/report-appointment-indian">
                        Missed Appointments
                    </b-navbar-item>
                    <b-navbar-item href="/report-loyal-customers">
                        Regular Patient
                    </b-navbar-item>
                    <b-navbar-item href="/report-statistic-page">
                        Statistic
                    </b-navbar-item>
                </b-navbar-dropdown>
                @if($role == 'ADMINISTRATOR')
                    <b-navbar-item href="/users"
                        class="{{ (request()->is('users*')) ? 'active' : '' }}">
                        Users
                    </b-navbar-item>
                @endif
                
                <b-navbar-item tag="div">
                    <div class="buttons">
                    @auth()
                        <b-button class="button is-danger" 
                            onclick="document.getElementById('logout').submit()">
                            <strong>Log out</strong>
                        </b-button>
                    @else
                        <b-button v-else class="button is-primary">
                            <strong>Log in</strong>
                        </b-button>
                    @endauth
                    </div>
                </b-navbar-item>
            </template>
        </b-navbar>

        <form id="logout" method="post" action="/logout">
            @csrf
        </form>

        <div>
            @yield('content')
        </div>


    </div> <!-- id app -->

</body>
</html>
