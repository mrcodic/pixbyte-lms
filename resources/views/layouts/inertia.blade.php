<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- UIkit CSS -->
    <link rel="stylesheet" href="{{asset('assets/css/uikit.min.css')}}" />
    <link href="{{asset('assets/css/select2.min.css')}}" rel="stylesheet"/>

    <link rel="shortcut icon" type="image/x-icon" href="{{asset('img/tab-icon.png')}}">
    {{-- font-awesome --}}
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" />
    {{-- fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dosis:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" >
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}" />
    @yield('css')
    <style>
        @if(Route::currentRouteName()!=='room.show')
            .wrapper-page-dark{
            background:#dee1e2
        }
        @endif


    </style>

</head>

<body>
@yield('body')
@if(Route::currentRouteName()!=='room.show')
    {{-- Sidebar --}}
    <x-sidebar></x-sidebar>
@endif

<!-- container -->
<div class="wrapper-page-dark f-height uk-padding-remove rooms-lessons">
    {{-- Instructor sidebar --}}

    @if(Route::currentRouteName()!=='room.show')
        <x-instructor-sidebar />
        <!-- container header -->
        <div class="header-wrap page-dark">
            <div class="uk-container uk-container-expand rm-expand">
                <!-- navbar -->
                @include('layouts.navigation')
            </div>
        </div>
    @endif
    @if(Route::currentRouteName()=='room.show')
    <div class="header-wrap page-dark hidden-large">
        <div class="uk-container uk-container-expand rm-expand">
            <!-- navbar -->
            @include('layouts.navigation')
        </div>
        <x-sidebar></x-sidebar>
    </div>
    @endif
{{--    @inertia--}}
    <div id="app" class="h-full" data-page="{{ json_encode($page) }}">

        <div v-cloak>

            <div class="v-cloak--inline"> <!-- Parts that will be visible before compiled your HTML -->
                <div  class="spinner loading dark-font" style=" width: 100px;height: 64px;position: absolute;top:0;bottom: 0;left: 0;right: 0;margin: auto;"  >
                    <div class="circle one"></div>
                    <div class="circle two"></div>
                    <div class="circle three"></div>
                </div>
            </div>

            <div class="v-cloak--hidden"> <!-- Parts that will be visible After compiled your HTML -->
                <!-- Rest of the contents -->
                @yield('content')
            </div>

        </div>
    </div>
</div>

<x-footer></x-footer>
@section('footerScripts')
@show
@yield('script')

{{--<script src="{{ mix('/js/manifest.js') }}" defer></script>--}}
{{--<script src="{{ mix('/js/vendor.js') }}" defer></script>--}}
<script src="{{ mix('/js/app.js') }}" defer></script>
{{--<script src="{{ mix('js/utils.js') }}"></script>--}}
<!-- UIkit JS -->
        <script data-cfasync="false" src="{{asset('assets/js/uikit.min.js')}}"></script>
        <script data-cfasync="false" src="{{asset('assets/js/uikit-icons.min.js')}}"></script>
<!-- Jquery -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script src="https://player.vimeo.com/api/player.js"></script>

<!-- App JS -->
        <script src="{{ asset('assets/js/app.js') }}"></script>

</body>
</html>
