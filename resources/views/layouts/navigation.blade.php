<nav class="uk-navbar-container uk-navbar-transparent uk-margin-small-top" uk-navbar>
    <div class="uk-navbar-left hidden-small">
        <x-application-logo></x-application-logo>
        <div class="hidden-small">
            <x-main-menu></x-main-menu>
        </div>
    </div>
    <div class="uk-navbar-right hidden-small">
        <ul class="uk-navbar-nav">
            @if (Route::has('login'))
                @auth
                    @if(auth()->user()->type !== 2 && auth()->user()->type !== '5')
                        @php
                            $announcement=auth()->user()->announcementes()->whereNull('read_at')->get();
                        @endphp

                            <a href="{{route('my-announcement')}}" style="margin: auto;cursor: pointer;">
                                <li  class="announcement-notifi">
                                    <span uk-icon="comments">
                                        <span class="uk-badge countAnnouncement" id="countAnnouncement">{{$announcement->count()}}</span>
                                    </span>
                                </li>
                            </a>
                    @endif

                <li class="profile-icon profile-img-wrapper-x" uk-toggle="target: #offcanvas-flip">
                    @php

                    if(auth()->guard('parent')->check()){
                        $user=auth()->guard('parent')->user()->user;

                    }else{
                        $user=auth()->user();
                    }
                        $unreadNotifications = $user->type == 5 ? auth()->user()->user->unreadNotifications :$user->unreadNotifications;
                    @endphp
                    @if(count($unreadNotifications)>0)
                        <span class="bell-notification">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 19 19">
                            <g class="too-big-actually">
                                <g class="bell-whole">
                                    <path class="bell-part bell-part--ringer" d="M9.5,17.5a2,2,0,0,0,2-2h-4A2,2,0,0,0,9.5,17.5Z"/>
                                    <path class="bell-part bell-part--main" d="M16.23,12.82c-.6-.65-1.73-1.62-1.73-4.82a4.93,4.93,0,0,0-4-4.85V2.5a1,1,0,0,0-2,0v.65A4.94,4.94,0,0,0,4.5,8c0,3.2-1.13,4.17-1.73,4.82a1,1,0,0,0-.27.68,1,1,0,0,0,1,1h12a1,1,0,0,0,1-1A1,1,0,0,0,16.23,12.82Z"/>
                                </g>
                            </g>
                        </svg>
                    </span>
                    @endif
                    <x-profile-image />
                </li>

            @else
                <li>
                    <a href="#login-model" class="border-all-radius height-50" uk-toggle>Student Portal</a>
                </li>
                <li>
                    <a href="{{route('getParentLogin')}}" class="border-all-radius height-50">Parent Portal</a>
                </li>
                    @if (Route::has('register'))
                        <li class="">
                            <a href="{{ route('register') }}" class="">Sign Up</a>
                        </li>
                    @endif
                @endauth
            @endif
        </ul>
    </div>

    @auth
    <div class="navbar-center hidden-large uk-flex uk-flex-between uk-width-1-1 uk-margin-small-top">
        <div class="navbar-center-left">
            <div class="btn-hmbrgr reverse">
                <button class="reign-toggler" type="button" uk-toggle="target: #offcanvas-flip-1">
                    <span class="icon-bar bar1 @auth white @endauth"></span>
                    <span class="icon-bar bar2 @auth white @endauth"></span>
                    <span class="icon-bar bar3 @auth white @endauth"></span>
                </button>
            </div>
        </div>
        <div class="navbar-item-center">
            <x-application-logo></x-application-logo>
        </div>
        <div class="navbar-center-right">
            <li class="profile-icon profile-img-wrapper-x" uk-toggle="target: #offcanvas-flip">
                    @if(count($unreadNotifications)>0)
                        <span class="bell-notification">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 19 19">
                                <g class="too-big-actually">
                                    <g class="bell-whole">
                                        <path class="bell-part bell-part--ringer" d="M9.5,17.5a2,2,0,0,0,2-2h-4A2,2,0,0,0,9.5,17.5Z"/>
                                        <path class="bell-part bell-part--main" d="M16.23,12.82c-.6-.65-1.73-1.62-1.73-4.82a4.93,4.93,0,0,0-4-4.85V2.5a1,1,0,0,0-2,0v.65A4.94,4.94,0,0,0,4.5,8c0,3.2-1.13,4.17-1.73,4.82a1,1,0,0,0-.27.68,1,1,0,0,0,1,1h12a1,1,0,0,0,1-1A1,1,0,0,0,16.23,12.82Z"/>
                                    </g>
                                </g>
                            </svg>
                        </span>
                    @endif
                <x-profile-image />
            </li>
        </div>
    </div>
    <div class="hidden-large main-side-canvas" id="offcanvas-flip-1" uk-offcanvas="overlay: true">
        <div class="uk-offcanvas-bar">
            <button class="uk-offcanvas-close" type="button" uk-close></button>
            <x-main-menu></x-main-menu>
        </div>
    </div>
    @endauth

    @if (!Auth::user())
    <div class="uk-navbar-left hidden-large">
        <x-application-logo></x-application-logo>
    </div>
    <div class="uk-navbar-right hidden-large">
        <div class="btn-hmbrgr">
            <button class="reign-toggler" type="button" uk-toggle="target: #offcanvas-flip-1">
                <span class="icon-bar bar1 @auth white @endauth"></span>
                <span class="icon-bar bar2 @auth white @endauth"></span>
                <span class="icon-bar bar3 @auth white @endauth"></span>
            </button>
        </div>

        <div class="hidden-large main-side-canvas" id="offcanvas-flip-1" uk-offcanvas="overlay: true">
            <div class="uk-offcanvas-bar">

                <button class="uk-offcanvas-close" type="button" uk-close></button>

                @if (!Auth::user())
                <ul class="uk-navbar-nav right-btn uk-margin-medium-top uk-flex-center block">
                    @if (Route::has('login'))
                        <li>
                            <a href="#login-model" class="border-all-radius height-50" uk-toggle>Student Portal</a>
                        </li>
                        <li>
                            <a href="{{route('getParentLogin')}}" class="border-all-radius height-50">Parent Portal</a>
                        </li>
                        @if (Route::has('register'))
                        <li class="">
                            <a href="{{ route('register') }}" class="">Sign Up</a>
                        </li>
                        @endif
                    @endif
                </ul>
                @endif

                <x-main-menu></x-main-menu>

            </div>
        </div>
    </div>

    <x-login></x-login>
    @endif
</nav>

