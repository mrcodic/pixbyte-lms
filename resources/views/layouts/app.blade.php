<!DOCTYPE html>
{{--<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">--}}
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <link rel="shortcut icon" type="image/x-icon" href="{{asset('img/tab-icon.png')}}">
        <!-- UIkit CSS -->
        <link rel="stylesheet" href="{{asset('assets/css/uikit.min.css')}}" />
        <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.uikit.min.css" />
        <!-- UIkit JS -->
        <script data-cfasync="false" src="{{asset('assets/js/uikit.min.js')}}"></script>
        <script data-cfasync="false" src="{{asset('assets/js/uikit-icons.min.js')}}"></script>


        <link href="{{asset('assets/css/select2.min.css')}}" rel="stylesheet"/>

        {{-- font-awesome --}}
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" />

        {{-- fonts --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Dosis:wght@400;600;700&display=swap" rel="stylesheet">
        @yield('css')
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" >
        <link rel="stylesheet" href="{{ asset('assets/css/app.css?v=9') }}" />

        <title>Mives | @yield('title','Home')</title>
        <!-- Include script -->
        {!! htmlScriptTagJsApi() !!}
    </head>

    <body>

        @yield('body')

{{--        @can('Instructor')--}}
        {{-- Sidebar --}}
        <x-sidebar></x-sidebar>
{{--        @endcan--}}

        <x-footer></x-footer>
        @section('footerScripts')
        <!-- Jquery -->
        <script data-cfasync="false" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.12.1/js/dataTables.uikit.min.js"></script>
        <!-- Toster -->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <script src="{{asset('assets/js/select2.min.js')}}"></script>
        <script src="{{asset('assets/js/sweetalert2.all.min.js')}}"></script>
        <!-- App JS -->
{{--        <script src="{{ asset('/js/app-sockets.min.js') }}"></script>--}}
<script src="https://player.vimeo.com/api/player.js"></script>

        <script data-cfasync="false" src="{{ asset('assets/js/app.js') }}"></script>
        {{-- <script src="{{ asset('js/websocket.js') }}"  ></script> --}}

        <script >

            @if(auth()->user())
            let userId="{{auth()->user()->id}}"
          // event announcement
            Echo.private(`App.Models.User.${userId.toString()}`).listen(('AnnouncementEvent'),(data)=>{

                $('#countAnnouncement').text(data.data.count)
            })
            // event notification
            Echo.private(`App.Models.User.${userId.toString()}`)
                .notification((notification) => {
                    console.log(notification);
                    var currentDateWithFormat = new Date().toJSON().slice(0,10).replace(/-/g,'/');
                    notification['date']=currentDateWithFormat + ' ' +new Date().toLocaleTimeString();

                    @php
                        $unreadNotifications =
                        auth()->user()->type == 5
                        ? auth()->user()->user->unreadNotifications
                        :auth()->user()->unreadNotifications;
                    @endphp
                    @if(count($unreadNotifications)==0)
                    $('.notification-body').empty();
                    $('.nothing-toshow').hide();
                    @endif
                    $('.nothing-toshow').hide();
                    $('.profile-icon').prepend(
                        `<span class="bell-notification">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 19 19">
                            <g class="too-big-actually">
                                <g class="bell-whole">
                                    <path class="bell-part bell-part--ringer" d="M9.5,17.5a2,2,0,0,0,2-2h-4A2,2,0,0,0,9.5,17.5Z"></path>
                                    <path class="bell-part bell-part--main" d="M16.23,12.82c-.6-.65-1.73-1.62-1.73-4.82a4.93,4.93,0,0,0-4-4.85V2.5a1,1,0,0,0-2,0v.65A4.94,4.94,0,0,0,4.5,8c0,3.2-1.13,4.17-1.73,4.82a1,1,0,0,0-.27.68,1,1,0,0,0,1,1h12a1,1,0,0,0,1-1A1,1,0,0,0,16.23,12.82Z"></path>
                                </g>
                            </g>
                        </svg>
                    </span>`
                    );
                    $('.notification-body').prepend(`
                    <div class="notifcation-wrapper uk-margin-small-bottom">
                        <a class="notifciation-link" href="#">
                            <div class="uk-card uk-card-default uk-padding-small light-color">
                                <p class="uk-margin-remove-top uk-margin-remove-left uk-margin-small-bottom">${notification.data['text']} </p>
                                <span style="font-size: 12px">1 minute ago</span>
                            </div>
                        </a>
                    </div>
                    <div class="loader spinner dark-font notifications-loader" style="display: none"  >
                        <div class="circle one"></div>
                        <div class="circle two"></div>
                        <div class="circle three"></div>
                    </div>
                    `);
                    if (! ('Notification' in window)) {
                        alert('Web Notification is not supported');
                        return;
                    }
                    if (Notification.permission === 'denied') {
                        alert('The user has blocked notifications.');
                        return;
                    }
                    Notification.requestPermission( permission => {
                        var my_div = document.createElement('div');
                        my_div.innerHTML = notification.data['text'];
                        let div=my_div;
                        let notifications = new Notification('Mives.org', {
                            body:  div.innerText  ,
                            icon: "/img/LOGO.png"

                        });

                    })

                });

            $('#clear_all').on('click',function (e){

                $.ajax({
                    url: '/clear-notification',
                    type: "get",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    beforeSend: function() {
                        $('.loader').show();

                    },
                    success: function (res) {
                        if(res.status){
                            $('.loader').hide();
                            $('.bell-notification').hide();
                            $('.notification-body').empty();
                            $('.notification-body').prepend(`
                        <div class="nothing-toshow uk-flex uk-flex-center uk-flex-column uk-margin-large-top">
                                                <div class="nothing-toshow-wrapper uk-margin-medium-left">
                                                    <img class="nothing-toshow-img" src="{{ asset('img/nothing-toshow.webp') }}" alt="notification-body">
                                                </div>
                                                <div class="uk-card uk-card-default uk-text-center nothing-para">
                                                    <p>You don't have any notifications yet! You will receive all the notifications here.</p>
                                                </div>
                                            </div>
                                        <div class="loader  spinner dark-font notifications-loader" style="display: none"  >
                                            <div class="circle one"></div>
                                            <div class="circle two"></div>
                                            <div class="circle three"></div>
                                        </div>
                    `);
                        }
                    },
                    error:function (res) {
                        Swal.fire("Close!", "Something is wrong, Please try again.", "error");
                    }
                });
            });
            $('#deleteAnnouncement').on('click',function (e){

                $.ajax({
                    url: '/clear-announcement',
                    type: "get",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    beforeSend: function() {
                        $('.loader').show();

                    },
                    success: function (res) {
                        if(res.status){
                           window.location.href='my-announcement';
                        }
                    },
                    error:function (res) {
                        Swal.fire("Close!", "Something is wrong, Please try again.", "error");
                    }
                });
            });

            var page = 1; //track user scroll as page number, right now page number is 1

            $('.notification-area').on('scroll',function() { //detect page scroll
                if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) { //if user scrolled from top to bottom of the page

                    page++; //page number increment
                    load_more(page); //load content
                }
            });

            function  load_more(page){
                $.ajax({
                    url: `/loadmore-notification?page=${page}`,
                    type: "get",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    beforeSend: function() {
                        $('.loader').show();
                    },
                    success: function (res) {
                        if(res.status){
                            $('.loader').hide();
                            res.data.forEach((e)=>{

                                $('.notification-body').append(`
                                    <div class="notifcation-wrapper uk-margin-small-bottom">
                                        <a class="notifciation-link" href="#">
                                            <div class="uk-card uk-card-default uk-padding-small light-color">
                                                <p class="uk-margin-remove-top uk-margin-remove-left uk-margin-small-bottom">${e.text} </p>
                                                <span  style="font-size: 12px">${e.date}</span>
                                            </div>
                                        </a>
                                    </div>
                                `);
                            });

                        }
                    },
                    error:function (res) {
                        Swal.fire("Close!", "Something is wrong, Please try again.", "error");
                    }
                });
            }
            @endif



            @if(Session::has('message'))
            var type = "{{ Session::get('alert-type','info') }}"
            switch(type){
                case 'info':
                toastr.info(" {{ Session::get('message') }} ");
                break;
                case 'success':
                toastr.success(" {{ Session::get('message') }} ");
                break;
                case 'warning':
                toastr.warning(" {{ Session::get('message') }} ");
                break;
                case 'error':
                toastr.error(" {{ Session::get('message') }} ");
                break;
            }
            @endif

            // data-beer-label="before"

        </script>
        @show
    @yield('script')

    </body>
</html>
