@extends('layouts.app')
@section('title','Home')

    @section('body')

        <!-- particles.js container -->
        <x-particles></x-particles>
        @can('Instructor')
            {{-- Instructor sidebar --}}
            <x-instructor-sidebar />
        @endcan
        <!-- container -->
        <x-mobile-app />
        <div class="uk-container uk-container-expand f-height">
            <!-- navbar -->
            @include('layouts.navigation')

            <!-- Hero section -->
            <div class="uk-child-width-expand@s uk-flex uk-flex-middle uk-text-center" uk-grid>
                <div class="wrapper-hero-img">
                    <img class="hero-img" src="{{ asset('img/iron-man-hp.png') }}" alt="hero">
                </div>
                <div class="uk-margin-right wrapper-hero-content border-radius">
                    <h3>We offer an integrated service from which the student can obtain knowledge of the latest theoretical methods and modern technology, which will raise the student's capabilities in line with modern assessment methods.</h3>
                </div>
            </div>
            <div class="intro-video-container">
                <h1 class="uk-text-center intro-title">Who are we?</h1>
                <div class="intro divider"></div>
                <div class="uk-text-center intro-video-mives">
                    <iframe src="https://player.vimeo.com/video/854094640?h=7d4f483a0c&amp;badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" style="width:100%;height:100%;" title="mives-intro"></iframe>
                </div>
            </div>
        </div>

    @endsection

    @section('footerScripts')
        @parent
        <!-- parcticles.js lib -->
        <script src="{{asset('assets/js/particles.min.js')}}"></script>
        <script src="{{ asset('assets/js/app-home.js') }}"></script>


        <script>
            $(document).ready(function (e){

                if (!("Notification" in window)) {
                    // Check if the browser supports notifications
                    alert("This browser does not support desktop notification");
                } else if (Notification.permission === "granted") {
                    // Check whether notification permissions have already been granted;
                    // if so, create a notification
                    //const notification = new Notification("Hi there!");
                    // …
                } else if (Notification.permission !== "denied") {
                    // We need to ask the user for permission
                    Notification.requestPermission().then((permission) => {
                        // If the user accepts, let's create a notification
                        if (permission === "granted") {
                            let notifications = new Notification('Mives.org', {
                                body: "Welcome in mives"  ,
                                icon: "/img/LOGO.png"

                            });
                        }
                    });
                }else{
                    const notification = new Notification("Hi there!");
                    Notification.requestPermission().then((permission) => {
                        // If the user accepts, let's create a notification
                        if (permission === "granted") {
                            const notification = new Notification("Hi there!");
                            // …
                        }
                    });
                }


            })
        </script>

    @endsection

