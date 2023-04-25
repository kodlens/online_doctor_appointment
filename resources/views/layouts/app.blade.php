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


    {{-- <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin> --}}
    {{-- <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@300&family=Ubuntu:wght@300&display=swap" rel="stylesheet"> --}}

    <style>
        html body{
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif
        }

        .navbar{
            border-bottom: 2px solid rgb(25, 95, 175);
        }
        .navbar .navbar-item, .navbar-link {
            font-weight: bold;
            color: #1464a5;
        }
        
        .navbar-dropdown{
            font-weight: bold;
            color: #1464a5;
        }
    </style>

</head>
<body>
    <div id="app">
        
        <b-navbar>
            <template #brand>
                <b-navbar-item class="has-text-weight-bold">
                    APPOINTMENT SYSTEM
                </b-navbar-item>
            </template>
            <template #start>
                
            </template>
    
            <template #end>
                <b-navbar-item href="/">
                    Home
                </b-navbar-item>
                
                @auth
                    <b-navbar-item href="my-appointment">
                        My Appointments
                    </b-navbar-item>
                    <b-navbar-dropdown label="{{ Auth::user()->lname }}">
                        <b-navbar-item href="#">
                            My Profile
                        </b-navbar-item>
                        <b-navbar-item href="#">
                            Change Password
                        </b-navbar-item>
                    </b-navbar-dropdown>
                @endauth

                <b-navbar-item tag="div">
                    @auth
                    <div class="buttons">
                        <b-button
                            onclick="document.getElementById('logout').submit()"
                            icon-left="logout"
                            class="button is-danger is-outlined">
                        </b-button>
                    </div>
                    @endauth
                </b-navbar-item>
            </template>
        </b-navbar>

        <form action="/logout" id="logout" method="post">@csrf</form>

    <div>
        @yield('content')
    </div>


    </div>
</body>
</html>
