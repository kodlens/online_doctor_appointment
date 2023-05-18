<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
{{--    <link rel="dns-prefetch" href="//fonts.gstatic.com">--}}
{{--    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">--}}

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">


    {{-- <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin> --}}
    {{-- <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@300&family=Ubuntu:wght@300&display=swap" rel="stylesheet"> --}}

    <style>

        @font-face {
            
            font-family: 'Signika', sans-serif;
            src: url('/fonts/vendor/Signika/Signika-VariableFont_wght.ttf') format('truetype'); /* Font file path and format */
            /* You can add additional src declarations for different font file formats if needed */
        }

        html body{
            font-family: 'Signika', sans-serif;
        }

    </style>

</head>
<body>
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
                <b-navbar-item href="#">
                    Home
                </b-navbar-item>
                <b-navbar-item href="/appointments"
                    class="{{ (request()->is('appointments*')) ? 'active' : '' }}">
                    Appointments
                </b-navbar-item>
                <b-navbar-dropdown label="Setting"
                    class="{{ (request()->is('schedules*')) ? 'active' : '' }}">
                    <b-navbar-item href="/schedules">
                        Setup Schedule
                    </b-navbar-item>
                    <b-navbar-item href="/max-patients">
                        Set Max Patient
                    </b-navbar-item>
                    {{-- <b-navbar-item href="#">
                        Contact
                    </b-navbar-item> --}}
                </b-navbar-dropdown>
                <b-navbar-dropdown label="Reports"
                    class="{{ (request()->is('reports*')) ? 'active' : '' }}">
                    <b-navbar-item href="/reports-appointments">
                        Appointments
                    </b-navbar-item>
                    <b-navbar-item href="/reports-appointments">
                        Patient Appointment By Date
                    </b-navbar-item>
                    {{-- <b-navbar-item href="#">
                        Contact
                    </b-navbar-item> --}}
                </b-navbar-dropdown>
                <b-navbar-item href="/users"
                    class="{{ (request()->is('users*')) ? 'active' : '' }}">
                    Users
                </b-navbar-item>

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
