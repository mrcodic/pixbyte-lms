@if(Route::has('login'))
            @auth
                <!-- Profile bar -->
                <div id="offcanvas-flip" uk-offcanvas="flip: true; overlay: true;">
                    <div class="uk-offcanvas-bar notifications-side-bar">

                        <button class="uk-offcanvas-close" type="button" uk-close></button>

                        <div class="uk-child-width-expand@s" uk-grid>
                            <div class="uk-width-1-3 uk-padding-remove">
                                <div class="uk-margin-small-top">
                                    <div class="uk-flex-center uk-grid uk-margin-remove" uk-grid>
                                        <div class="uk-width-1-2 padding-l-s">
                                            <div class="profile-img-wrapper side-bar">
                                                <x-profile-image />
                                            </div>
                                        </div>
                                        <div class="uk-width-1-2@m uk-width-1-1@s uk-padding-smaller side-name padding-l-s">
                                            <div >
                                                <h5 class="uk-margin-remove">{{ Auth::user()->first_name }}</h5>
                                            </div>
                                            <div>
                                                <p class="nameId">{{ __('@') }}{{ Str::upper(Auth::user()->name_id) }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- nav-sidebar -->

                                    <ul class="uk-nav-default uk-margin-top rm-t-s" uk-nav>
                                        <li class="side-bar-menu uk-margin-small-bottom">
                                            <a href="{{ auth()->user()->type == 5 ? route('parent.dashboard') : route('dashboard') }}" class="uk-margin-medium-left uk-padding-remove-bottom ml-s-30"><span>My Profile</span>
                                                <div class="subcontent">
                                                    // everyone can see this page
                                                </div>
                                            </a>
                                        </li>

                                        <li class="side-bar-menu uk-margin-small-bottom">
                                            <a href="/my_fav_store" class="uk-margin-medium-left uk-padding-remove-bottom ml-s-30"><span>Favorite Gift</span>
                                                <div class="subcontent">
                                                    // student only see this page
                                                </div>
                                            </a>
                                        </li>
                                        <li class="side-bar-menu uk-margin-small-bottom">
                                            <a href="/my_redemptions_store" class="uk-margin-medium-left uk-padding-remove-bottom ml-s-30"><span>My Gifts</span>
                                                <div class="subcontent">
                                                    // student only can see this page
                                                </div>
                                            </a>
                                        </li>
                                        <li class="side-bar-menu uk-margin-small-bottom hidden-large">
                                            <a href="#qr-modal" class="uk-margin-medium-left uk-padding-remove-bottom ml-s-30" uk-toggle>
                                                <span>My Qr</span>
                                                <div class="subcontent">
                                                    // view your qr code
                                                </div>
                                            </a>

                                            <x-qr-modal></x-qr-modal>
                                        </li>
                                        <li class="side-bar-menu uk-margin-small-bottom">
                                            <a href="{{ auth()->user()->type == 5 ? route('parent.settings') : route('settings') }}" class="uk-margin-medium-left uk-padding-remove-bottom ml-s-30">
                                                <span>Settings</span>
                                                <div class="subcontent">
                                                    // make a tweak
                                                </div>
                                            </a>
                                        </li>
                                        <li class="uk-nav-divider"></li>
                                        <li class="side-bar-menu">
                                            <a href="{{ auth()->user()->type == 5 ? route('parent.logout') : route('logout') }}" class="uk-margin-medium-left uk-padding-remove-bottom ml-s-30">
                                                <span>Logout</span> <span uk-icon="icon: sign-out"></span>
                                                <div class="subcontent">
                                                    // but why?
                                                </div>
                                            </a>
                                        </li>
                                    </ul>

                                    <div class="qr-wrapper hidden-small">
                                        {{-- <div id="qr" class="da-code"></div> --}}
                                        {!! QrCode::size(300)->generate(auth()->user()->name_id) !!}
                                    </div>

                                </div>
                            </div>

                            <div class="uk-width-2-3 uk-margin-remove-left" uk-grid>
                                <div class="hr-divider uk-padding-remove uk-width-auto"></div>
                                <div class="uk-width-expand uk-padding-small-left notification-area">
                                    <div class="notification-header uk-margin-top" uk-grid>
                                        <div class="uk-width-expand">
                                            <h4>Notifications</h4>
                                        </div>
                                        @if(!auth()->guard('parent')->check())
                                        <div class="uk-width-auto outline-link">
                                            <button id="clear_all" class="uk-button uk-button-default outline uk-button-small border-radius clear-all-s">Clear All</button>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="notification-body">
                                        @php

                                            $notifications =
                                            auth()->user()->type == 5
                                            ? auth()->user()->user->unreadNotifications()->paginate(8)
                                            :auth()->user()->unreadNotifications()->paginate(8);
                                        @endphp
                                        @if(count($notifications)>0)
                                            @foreach($notifications as $notify)
                                            <div class="notifcation-wrapper uk-margin-small-bottom">
                                                <a class="notifciation-link" href="#">
                                                    <div class="uk-card uk-card-default uk-padding-small light-color">
                                                        <p class="uk-margin-remove-top uk-margin-remove-left uk-margin-small-bottom">{!! $notify->data['text'] !!} </p>
                                                        <span  style="font-size: 12px"> {{$notify->created_at->diffForHumans()}}</span>
                                                    </div>
                                                </a>
                                            </div>
                                            @endforeach
                                        @else
                                            <div class="nothing-toshow uk-flex uk-flex-center uk-flex-column uk-margin-large-top">
                                                <div class="nothing-toshow-wrapper uk-margin-medium-left">
                                                    <img class="nothing-toshow-img" src="{{ asset('img/nothing-toshow.webp') }}" alt="notification-body">
                                                </div>
                                                <div class="uk-card uk-card-default uk-text-center nothing-para">
                                                    <p>You don't have any notifications yet! You will receive all the notifications here.</p>
                                                </div>
                                            </div>


                                        @endif

                                        <div class="spinner loader dark-font notifications-spinner" style="display: none"  >
                                            <div class="circle one"></div>
                                            <div class="circle two"></div>
                                            <div class="circle three"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            @endauth
@endif

