<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-100">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <!-- UIkit CSS -->
        <link rel="stylesheet" href="{{asset('assets/css/uikit.min.css')}}" />

        <!-- UIkit JS -->
        <script data-cfasync="false" src="{{asset('assets/js/uikit.min.js')}}"></script>
        <script data-cfasync="false" src="{{asset('assets/js/uikit-icons.min.js')}}"></script>
        <link rel="stylesheet" href="{{ asset('assets/css/app.css?v=9') }}" />
        <link rel="shortcut icon" type="image/x-icon" href="{{asset('img/tab-icon.png')}}">

        {{-- fonts --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Dosis:wght@400;600;700&display=swap" rel="stylesheet">

        <title>Mives | @yield('title','Home')</title>
        {!! htmlScriptTagJsApi() !!}
    </head>

    <body class="dark-page">

        <div class="p-s-40-10 uk-container uk-padding-large f-height uk-padding-remove-bottom">
            <div class="uk-text-center">
                <x-application-logo class="w-200 uk-margin-bottom"/>
            </div>
            <div class="uk-width-1-2@l uk-width-1-1@m uk-margin-auto">
                @yield('body')
            </div>
        </div>

        <x-footer></x-footer>
        @section('footerScripts')
        <!-- App JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="{{asset('assets/js/select2.min.js')}}"></script>
        <script src="{{asset('assets/js/sweetalert2.all.min.js')}}"></script>
        <script data-cfasync="false" src="{{ asset('assets/js/app.js') }}"></script>
        <script>
            $(document).on('click', ".togglePassword", function (e) {
                e.preventDefault();
                var type = $(this).parent().find(".password").attr("type");
                if(type == "password"){
                    $(this).parent().find(".password").attr("type","text");
                    $(this).empty();
                    $(this).append(`<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><path fill="none" stroke="#000" d="m7.56,7.56c.62-.62,1.49-1.01,2.44-1.01,1.91,0,3.45,1.54,3.45,3.45,0,.95-.39,1.82-1.01,2.44"></path><path fill="none" stroke="#000" d="m19.5,10c-2.4,3.66-5.26,7-9.5,7h0,0,0c-4.24,0-7.1-3.34-9.49-7C2.89,6.34,5.75,3,9.99,3h0,0,0c4.25,0,7.11,3.34,9.5,7Z"></path><line fill="none" stroke="#000" x1="2.5" y1="2.5" x2="17.5" y2="17.5"></line></svg>`);

                }else if(type == "text"){
                    $(this).parent().find(".password").attr("type","password");
                    $(this).empty();
                    $(this).append(`<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><circle fill="none" stroke="#000" cx="10" cy="10" r="3.45"></circle><path fill="none" stroke="#000" d="m19.5,10c-2.4,3.66-5.26,7-9.5,7h0,0,0c-4.24,0-7.1-3.34-9.49-7C2.89,6.34,5.75,3,9.99,3h0,0,0c4.25,0,7.11,3.34,9.5,7Z"></path></svg>`);
                }
            });
        </script>
        @show
        @yield('script')

    </body>
</html>
