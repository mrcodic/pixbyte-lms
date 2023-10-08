@extends('layouts.app')
@section('title', $data->id === Auth::user()->id ? Auth::user()->first_name.' '. Auth::user()->last_name : $data->first_name.' '.$data->last_name)
@section('css')
<script src="https://cdn.jsdelivr.net/npm/shepherd.js@10.0.1/dist/js/shepherd.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/shepherd.js@10.0.1/dist/css/shepherd.css"/>
@endsection
@section('body')

<!-- container -->
<div class="wrapper-page-light f-height">
    <!-- container header -->

    <div class="header-wrap page-dark">
        <div class="uk-container uk-container-expand">
            <!-- navbar -->
            @include('layouts.navigation')
        </div>
        <!-- Student Data -->
        <div class="uk-container">
            <div class="student-info profile-info uk-margin-medium-top uk-grid-small uk-text-center rm-t-s" uk-grid>
                <div class="uk-width-1-3@m uk-width-1-1@s uk-margin-large-top">
                    <div class="uk-flex-center uk-grid student-basic-info" uk-grid>
                        <div class="uk-padding-remove-left">
                            <div class="profile-img-wrapper">
                                @if ($data->id === Auth::user()->id)
                                    <x-profile-image />
                                @else
                                <img class="showImage" src="{{ (!empty($data->profile_image))? url('uploads/profile_images/'. $data->profile_image) : url('uploads/no-image/third-year.png') }}" alt="avatar">
                                @endif
                            </div>
                            @if ($data->id === Auth::user()->id)
                            <div class="uk-margin-small uk-margin-small-bottom uk-text-center">
                                <a href="{{ route('settings') }}" class="uk-button uk-button-secondary btn-small">Edit</a>
                            </div>
                            @endif
                        </div>
                        <div class="uk-padding-small-left">
                            <div>
                                {{-- <x-full-name /> --}}
                                <div class="stduent-name">
                                    <h3 class="uk-margin-remove uk-text-left">{{ $data->first_name .' '. $data->last_name}}</h3>
                                </div>
                                <div>
                                    <p class="uk-margin-small-bottom uk-text-left">
                                        Member since {{$date}}
                                    </p>
                                    <p class="uk-margin-remove-top uk-text-left">
                                        @foreach ($data->roles as $role)
                                            @if($role->id === 5 || $role->id === 6 || $role->id === 7 || $role->id === 2)
                                                {{ $role->name }}
                                            @endif
                                        @endforeach
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="uk-width-1-1@s uk-width-2-3@m"uk-grid>
                    <div class="uk-grid-small uk-flex uk-flex-center uk-width-1-1" >
                        <div class="uk-width-1-2@s uk-width-1-3@m uk-visible@l">
                        </div>
                        <div class="uk-width-1-2@s uk-width-1-3@m">
                            <div class="stats-profile uk-card uk-card-default xp-info">
                                <div class="uk-header-card">
                                    <div class="target-wrapper">
                                        <img class="uk-width-3-4 target uk-margin-small-bottom" src="{{ asset('img/xp-level.svg') }}">
                                    </div>
                                </div>
                                <div class="car-body">
                                    <strong class="points-card">{{$data->student->exp ?? 0}}</strong>
                                    <p class="uk-margin-small-top uk-margin-remove-bottom uk-margin-auto uk-width-1-2 uk-text-default light-link">Total Points</p>
                                </div>
                            </div>
                        </div>
                        <div class="uk-width-1-2@s uk-width-1-3@m">
                            <div class="stats-profile uk-card uk-card-default completed-info">
                                <div class="uk-header-card padding-less">
                                    <div class="target-wrapper">
                                        <img class="uk-width-3-4 target" src="{{ asset('img/xp-lesson.svg') }}">
                                    </div>
                                </div>
                                <div class="car-body">
                                    <strong class="points-card">{{$completed}}</strong>
                                    <p class="uk-margin-small-top uk-margin-remove-bottom uk-margin-auto uk-width-1-2@m uk-width-1-1@s uk-text-default light-link">Rooms Completed</p>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="uk-width-1-3@m">
                            <div class="stats-profile uk-card uk-card-default">
                                <div class="uk-header-card">
                                    <div class="target-wrapper">
                                        <img class="uk-width-3-4 target" src="{{ asset('img/xp-stars.svg') }}">
                                    </div>
                                </div>
                                <div class="car-body">
                                    <strong class="points-card">3</strong>
                                    <p class="uk-margin-small-top uk-margin-remove-bottom uk-margin-auto uk-width-1-2 uk-text-default">Full Mark Exams</p>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>

            <!-- Progress and Achievments -->
            <div class="progress-container uk-margin-large-top uk-margin-large-bottom">

                <div class="divider uk-margin-medium-top"></div>

            </div>
        </div>
    </div>

    <!-- Tabs and Content -->
    <div class="wrapper-switcher-profile">
        <div class="uk-container">
            <!-- Tabs -->
            @if(request()->name_id==auth()->user()->name_id)
            <div class="uk-margin-medium-top">
                <ul class="uk-flex-center uk-margin-remove-bottom" uk-tab uk-switcher="connect: .switcher-container">
                    <li class="uk-active"><a href="#">My Classrooms</a></li>
                    <li><a href="#">My Activity</a></li>
                </ul>
            </div>
            @endif

            <div class="uk-margin-medium-top">
                <ul class="uk-switcher uk-margin switcher-container">
                    <li class="uk-active myclassrooms">

                            <div class="uk-child-width-expand@s" uk-grid>
                                @forelse($myclassroom as $classroom)
                                <div class="uk-width-1-3@m uk-width-1-3@s">
                                    <div class="uk-card uk-card-default classroom-card">
                                        @foreach ( $classroom->photos as $photo)
                                        <div class="uk-card-header uk-card-media-top classroom-header border-radius" style="background-image:url({{ url('uploads/media/'. $photo->path);}});">
                                        @endforeach
                                            <div class="uk-flex uk-padding-small border-radius">
                                                <div class="uk-width-3-4">
                                                    <a href="/classroom/{{$classroom->id}}/classwork">
                                                        <h3 class="uk-card-title uk-margin-remove-bottom">
                                                        {{$classroom->title}}</h3>
                                                    </a>
                                                    <p class="uk-text-meta uk-margin-small-bottom uk-margin-remove-top"><time datetime="2016-04-01T19:00">{{\Carbon\Carbon::parse($classroom->created_at)->format('j F, Y')}}</time></p>
                                                    <a href="#">
                                                        <p class="uk-margin-remove inline-block light-color">
                                                            {{ $classroom->user->first_name }} {{ $classroom->user->last_name }}
                                                        </p>
                                                    </a>
                                                    <br>
                                                    <a href="#">
                                                        <p class="uk-margin-remove inline-block light-color">
                                                            @if (!empty($classroom->grade))
                                                            {{\App\Models\Grade::findOrFail($classroom->grade->grade_id)->name}}
                                                            @else
                                                            no grade
                                                            @endif
                                                        </p>
                                                    </a>
                                                </div>

                                                <div class="uk-width-1-4">
                                                    {{-- <span class="more-dots uk-border-rounded" uk-icon="icon:more-vertical;"></span> --}}
                                                    <div class="uk-border-rounded" uk-dropdown="mode: click">
                                                        <ul class="uk-nav uk-dropdown-nav">
                                                            <li><a href="#">Unenroll</a></li>
                                                            <li><a href="#">Report</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="free-element instructor-img">
                                                    <img class="uk-border-circle" width="70" height="70" src="{{ url('uploads/profile_images/'. $classroom->user->profile_image) }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="uk-card-body">
                                            <p>{{$classroom->description}}</p>
                                        </div>

                                        <div class="uk-card-footer" data-id="{{$classroom->id}}">
                                            <a href="/classroom/{{$classroom->id}}/classwork" class="uk-button uk-borderless uk-button-text enter-class">Enter the class</a>
                                        </div>

                                    </div>
                                </div>
                                @empty
                                    <div class="nothing-toshow uk-flex uk-flex-center uk-flex-column uk-margin-large-top">
                                        <div class="uk-text-center">
                                            <img class="nothing-toshow-img" src="{{ asset('img/nothing-toshow.webp') }}" alt="notification-body">
                                        </div>
                                        <div class="uk-card uk-card-default classroom-card border-success uk-padding-small uk-text-center">
                                            @if(request()->name_id==auth()->user()->name_id)
                                            {{-- <p class="uk-margin-remove">You haven’t Enrolled in classrooms Yet! checkout classrooms  <a href="{{route('classrooms.index')}}">Here.</a></p> --}}
                                            <p class="uk-margin-remove">You haven’t Enrolled in classrooms Yet!</p>
                                            @else
                                                <p class="uk-margin-remove">They haven’t Enrolled in classrooms Yet! </p>

                                            @endif
                                        </div>
                                    </div>
                                @endforelse
                            </div>

                    </li>
                @php

                // $activite=auth()->user()->quizes;
                @endphp
                    @if($data->pointDetails)
                        <li>
                            <div class="activity-wrapper">
                                <table class="uk-table uk-table-divider uk-margin-remove-top" id="activity_table" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th class="uk-table-expand">Activity</th>
                                            <th class="uk-table-small">Pt</th>
                                            <th class="uk-table-small">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </li>
                    @else
                        <div class="nothing-toshow uk-flex uk-flex-center uk-flex-column uk-margin-large-top">
                            <div class="uk-text-center">
                                <img class="nothing-toshow-img" src="{{ asset('img/nothing-toshow.webp') }}" alt="notification-body">
                            </div>
                            <div class="uk-card uk-card-default classroom-card border-success uk-padding-small uk-text-center">
                                @if(request()->name_id==auth()->user()->name_id)
                                    <p class="uk-margin-remove">You have no activities Yet! </p>
                                @else
                                    <p class="uk-margin-remove">They have’t no activities Yet! </p>

                                @endif
                            </div>
                        </div>
                    @endif


                </ul>
            </div>

        </div>

    </div>
<div class="help-icon" uk-tooltip="Start Mives Assistant Student Guide📚"><img src="{{asset('img/help.png')}}" alt="help"/></div>
</div>

@endsection
@section('footerScripts')
    @parent
<script type="text/javascript">
$(document).ready(function() {

    let activity_table=''
    activity_table  =  $('#activity_table').DataTable({
        processing: true,
        ordering: false,
        serverSide: true,
        lengthMenu: [5, 10, 20, 50],
        pageLength: 10,
        bLengthChange: false,
        "bFilter": false,
        "bInfo" : false,
        responsive: true,
        dom: 'Blfrtip',
        ajax: {
            url: "{{route('get-activity-data')}}",
            data: function (d) {
                d.student_id="{{$data->id}}"
            }
        },
        "language": {
            "processing":
                `<div style=" display: flex; margin-top: 150px; margin-left: 120px">
            <span class='fa-stack fa-lg'>
                <i class='fa fa-spinner fa-spin fa-stack-2x fa-fw'></i>
            </span>&emsp;Processing ... </div>`,
        },
        columns: [
            {data: 'title', name: 'title', render:function (data,type,full){
                let icon = '<img class="icon-activity" src="{{ asset("img/xp-lesson.svg") }}" >';
                let name = full['activity'] ? '<a>'+full['activity']+'</a>' :'';
                return `${icon} <span> ${data} </span> ${name}`}
            },
            {data: 'value', name: 'value'},
            {data: 'date', name: 'date'},
        ],
    });


    function addStep(step) {
        step.when = {
            show: onStepShow,
            hide: onStepHide
        };

        tour.addStep(step);
    }

    function onStepShow() {
        const { options, target, tour } = this;

        if (target && options.clickTargetAction) {
            target.addEventListener("click", tour[options.clickTargetAction]);
        }
    }

    function onStepHide() {
        const { options, target, tour } = this;

        if (target && options.clickTargetAction) {
            target.removeEventListener("click", tour[options.clickTargetAction]);
        }
    }

    function onTourEnd() {
    const currentStep = tour.getCurrentStep();
    onStepHide.call(currentStep);
    }
    let classId         = $('.uk-card-footer').attr('data-id');
    let demoClass       = "/classroom/"+classId+"/classwork?shtc=t";
    let activeClass     = "/classroom/"+classId+"/classwork";
    // Init tour
    const tour = new Shepherd.Tour({useModalOverlay: true,
        defaultStepOptions: {
            exitOnEsc: true,
        }
    });

    tour.on("cancel", onTourEnd);
    tour.on("complete", onTourEnd);

    addStep({
        id: 'step-0',
        text: '<img src="{{asset("img/welcome_image.jpeg")}}" alt="welcome"/><h2 class="uk-text-center">Welcome to Mives, Our Hero👋!</h2><p class="uk-text-center">You are now one step closer to a better online learning experience🏄.<br/>Let us guide you to show aroud💪!</p>',
        attachTo: {
            element: '',
            on: ''
        },
        classes: 'step-extra-class welcome',
        buttons: [
            {
            text: 'Start The Tour',
            action: tour.next
            },
            {
                action() {
                        // Dismiss the tour when the finish button is clicked
                        dismissTour();
                        return this.hide();
                    },
                    text: "Cancel"
            }

        ]
    });

    addStep({
        id: 'step-1',
        text: 'You can find your profile picture👤, fullname and membership period.',
        title: 'First of All',
        attachTo: {
            element: '.student-basic-info',
            on: 'right'
        },
        classes: 'step-extra-class left-m',
        buttons: [
            {
            text: 'Next',
            action: tour.next
            }
        ]
    });
    addStep({
        id: 'step-2',
        text: 'You will earn points 🎖 after passing exams and quizzes, collect many points and XP so you can earn prizes from MIVES store🎁.',
        title: 'Points🥇 and Rewards🎁',
        attachTo: {
            element: '.xp-info',
            on: 'left'
        },
        classes: 'step-extra-class step-2 right-m',
        buttons: [
            {
            text: 'Next',
            action: tour.next
            }
        ]
    });
    addStep({
        id: 'step-3',
        text: 'When you complete rooms 👏 you get more points 🎖 and win more prizes🎁.',
        title: 'Points🥇 and Rewards🎁',
        attachTo: {
            element: '.completed-info',
            on: 'left'
        },
        classes: 'step-extra-class step-3 right-m',
        buttons: [
            {
            text: 'Next',
            action: tour.next
            }
        ]
    });
    addStep({
        clickTargetAction: "next", // <- custom option: tour action to fire when target is clicked
        id: 'step-4',
        text: 'Click here to view all your notifications🔔, change settings⚙️ and view your Qr code🤓.',
        title: 'Settings⚙️ and Notifications',
        classes: 'step-extra-class right-m',
        attachTo: { element: ".profile-img-wrapper-x", on: "left" },
        scrollTo: {
            behavior: 'smooth',
            block: 'center'
        },
        buttons: [{
            action () {
                UIkit.offcanvas('#offcanvas-flip').show();
                return this.next();
            },
            text: 'Next'
        }]

    });

    addStep({
        id: 'step-5',
        text: 'Here you can find your personal data, Do you want to continue the tour?',
        title: 'Personal Data👤',
        attachTo: {
            element: '',
            on: ''
        },
        classes: 'step-extra-class',
        buttons: [
            { action: tour.next, text: "Continue" },
            { action: tour.cancel, text: "End" }
        ]
    });

    addStep({
        id: 'step-6',
        text: 'Your user Id💁‍♂️ you will use it in many major things like your attendance or when contacting our support to give you help🤗.',
        title: 'Name ID👤',
        attachTo: {
            element: '.side-name',
            on: 'left'
        },
        classes: 'step-extra-class right-m',
        buttons: [
            {
            text: 'Next',
            action: tour.next
            }
        ]
    });
    addStep({
        id: 'step-7',
        text: 'All of your notifications go here, You will be notified🔔 when a room is opened or if you miss any room and much more😃.',
        title: 'Notifications 🔔',
        attachTo: {
            element: '.notification-area',
            on: 'left'
        },
        classes: 'step-extra-class right-m',
        buttons: [
            {
            text: 'Next',
            action: tour.next
            }
        ]
    });
    addStep({
        clickTargetAction: "next", // <- custom option: tour action to fire when target is clicked
        id: 'step-8',
        text: 'Click here to clear your notifications🙄.',
        title: 'Notifications 🔔',
        classes: 'step-extra-class right-m',
        attachTo: {element: '#clear_all', on: "left" },
        buttons: [{
            action () {
                const selector = $('#clear_all');
                selector.click();
                tour.next;
            },
            text: 'Next'
        }]
    });
    if($('.myclassrooms').has('.nothing-toshow').length > 0) {
        addStep({
            id: 'step-9',
            text: 'Okay you are amazing🤩 you can take a break untill you enter classroom🥳️!',
            title: 'Wooow 🎊',
            attachTo: {
                element: '',
                on: ''
            },
            classes: 'step-extra-class',
            buttons: [
                {
                    action() {
                        // Dismiss the tour when the finish button is clicked
                        dismissTour();
                        return this.hide();
                    },
                    text: "End the tour"
                }
            ],
        });
    }else{
        addStep({
            id: 'step-9',
            text: 'Okay you are amazing🤩 we can move to the classroom part🥳️!',
            title: 'Wooow 🎊',
            attachTo: {
                element: '',
                on: ''
            },
            classes: 'step-extra-class',
            buttons: [
                {
                    action () {
                        UIkit.offcanvas('#offcanvas-flip').hide();
                        return this.next();
                    },
                    text: "Lets's Go💪!"
                },
                {
                    action() {
                        // Dismiss the tour when the finish button is clicked
                        dismissTour();
                        return this.hide();
                    },
                    text: "End the tour"
                }
            ],
        });

    }
    addStep({
        id: 'step-10',
        text: 'This is the classrooms area let\'s take this one as an example💁‍♂️.',
        title: 'Classroom Area👨‍🏫',
        attachTo: {
            element: '.classroom-card',
            on: 'right'
        },
        scrollTo: {
            behavior: 'smooth',
            block: 'center'
        },
        classes: 'step-extra-class left-m',
        buttons: [
            { action: tour.next, text: "Next" }
        ],
    });
    addStep({
        id: 'step-11',
        text: 'Enter the classroom now👆.',
        title: 'Classroom Area👨‍🏫',
        attachTo: {
            element: '.uk-card-footer',
            on: 'bottom'
        },
        scrollTo: {
            behavior: 'smooth',
            block: 'center'
        },
        classes: 'step-extra-class bottom-m',
        buttons: [
            {
                action () {
                    window.location.href = demoClass;
                },
                text: 'Next'
            }
        ],
    });

    // Initiate the tour first visit
    if (!localStorage.getItem('shepherd-tour')) {
        tour.start();
        $(".enter-class").attr("href", demoClass);
        localStorage.setItem('shepherd-tour', 'yes');
    }
    // Dismiss the toure on cancel
    function dismissTour() {
        if (!localStorage.getItem('shepherd-tour')) {
            localStorage.setItem('shepherd-tour', 'yes');
        }
        $(".enter-class").attr("href", activeClass);
    }
    // Dismiss the tour when the cancel icon is clicked. Do not show the tour on next page reload
    tour.on('cancel', dismissTour);
    // Start Tour on click
    $(".help-icon").click(function (){
        localStorage.removeItem('shepherd-tour');
        tour.start();
        localStorage.setItem('shepherd-tour', 'yes');
        $(".enter-class").attr("href", demoClass);
    });

    $(document).on('click','.shepherd-button',function (e){
        e.preventDefault();
        if($('.step-extra-class').hasClass('step-2') && $('.step-extra-class.step-2').css('display') != 'none') {
            $(".xp-info").addClass('blend');
            $(".completed-info").removeClass('blend');
        }else if($('.step-extra-class').hasClass('step-3') && $('.step-extra-class.step-3').css('display') != 'none'){
            $(".completed-info").addClass('blend')
            $(".xp-info").removeClass('blend');
        }
        else {
            $(".xp-info").removeClass('blend');
            $(".completed-info").removeClass('blend');
        }
    });
});
</script>
@endsection
