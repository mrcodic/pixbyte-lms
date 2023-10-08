<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <!-- UIkit JS -->
        <script data-cfasync="false" src="{{asset('assets/js/uikit.min.js')}}"></script>
        <script data-cfasync="false" src="{{asset('assets/js/uikit-icons.min.js')}}"></script>
        {{-- font-awesome --}}
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" />
        {{-- fonts --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Dosis:wght@400;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" >
        <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}" />

        <title>Mives | @yield('title','Home')</title>

    </head>

    <body>

        @yield('body')

        <x-footer></x-footer>
        @section('footerScripts')
        <!-- UIkit JS -->
        <script data-cfasync="false" src="{{asset('assets/js/uikit.min.js')}}"></script>
        <script data-cfasync="false" src="{{asset('assets/js/uikit-icons.min.js')}}"></script>
        <!-- Jquery -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <!-- App JS -->
        <script src="{{ asset('assets/js/app.js') }}"></script>
        @show
    </body>
</html>

