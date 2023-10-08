@extends('layouts.app')
@section('title', 'Student Rank')
@section('css')
<script src="https://cdn.jsdelivr.net/npm/shepherd.js@10.0.1/dist/js/shepherd.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/shepherd.js@10.0.1/dist/css/shepherd.css"/>
<style>
    .me{
        background: #f5a6a8;
    }
</style>
@endsection
@section('body')

<!-- container -->
<div class="wrapper-page-light f-height">
    <style>
        .confetti {
            display: flex;
            justify-content: center;
            align-items: center;
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;

        }
        .confetti-piece {
            position: absolute;
            width: 10px;
            height: 30px;
            background: #ffd300;
            top: 0;
            opacity: 0;
        }
        .confetti-piece:nth-child(1) {
            left: 7%;
            -webkit-transform: rotate(-40deg);
            -webkit-animation: makeItRain 1000ms infinite ease-out;
            -webkit-animation-delay: 182ms;
            -webkit-animation-duration: 1116ms;
        }
        .confetti-piece:nth-child(2) {
            left: 14%;
            -webkit-transform: rotate(4deg);
            -webkit-animation: makeItRain 1000ms infinite ease-out;
            -webkit-animation-delay: 161ms;
            -webkit-animation-duration: 1076ms;
        }
        .confetti-piece:nth-child(3) {
            left: 21%;
            -webkit-transform: rotate(-51deg);
            -webkit-animation: makeItRain 1000ms infinite ease-out;
            -webkit-animation-delay: 481ms;
            -webkit-animation-duration: 1103ms;
        }
        .confetti-piece:nth-child(4) {
            left: 28%;
            -webkit-transform: rotate(61deg);
            -webkit-animation: makeItRain 1000ms infinite ease-out;
            -webkit-animation-delay: 334ms;
            -webkit-animation-duration: 708ms;
        }
        .confetti-piece:nth-child(5) {
            left: 35%;
            -webkit-transform: rotate(-52deg);
            -webkit-animation: makeItRain 1000ms infinite ease-out;
            -webkit-animation-delay: 302ms;
            -webkit-animation-duration: 776ms;
        }
        .confetti-piece:nth-child(6) {
            left: 42%;
            -webkit-transform: rotate(38deg);
            -webkit-animation: makeItRain 1000ms infinite ease-out;
            -webkit-animation-delay: 180ms;
            -webkit-animation-duration: 1168ms;
        }
        .confetti-piece:nth-child(7) {
            left: 49%;
            -webkit-transform: rotate(11deg);
            -webkit-animation: makeItRain 1000ms infinite ease-out;
            -webkit-animation-delay: 395ms;
            -webkit-animation-duration: 1200ms;
        }
        .confetti-piece:nth-child(8) {
            left: 56%;
            -webkit-transform: rotate(49deg);
            -webkit-animation: makeItRain 1000ms infinite ease-out;
            -webkit-animation-delay: 14ms;
            -webkit-animation-duration: 887ms;
        }
        .confetti-piece:nth-child(9) {
            left: 63%;
            -webkit-transform: rotate(-72deg);
            -webkit-animation: makeItRain 1000ms infinite ease-out;
            -webkit-animation-delay: 149ms;
            -webkit-animation-duration: 805ms;
        }
        .confetti-piece:nth-child(10) {
            left: 70%;
            -webkit-transform: rotate(10deg);
            -webkit-animation: makeItRain 1000ms infinite ease-out;
            -webkit-animation-delay: 351ms;
            -webkit-animation-duration: 1059ms;
        }
        .confetti-piece:nth-child(11) {
            left: 77%;
            -webkit-transform: rotate(4deg);
            -webkit-animation: makeItRain 1000ms infinite ease-out;
            -webkit-animation-delay: 307ms;
            -webkit-animation-duration: 1132ms;
        }
        .confetti-piece:nth-child(12) {
            left: 84%;
            -webkit-transform: rotate(42deg);
            -webkit-animation: makeItRain 1000ms infinite ease-out;
            -webkit-animation-delay: 464ms;
            -webkit-animation-duration: 776ms;
        }
        .confetti-piece:nth-child(13) {
            left: 91%;
            -webkit-transform: rotate(-72deg);
            -webkit-animation: makeItRain 1000ms infinite ease-out;
            -webkit-animation-delay: 429ms;
            -webkit-animation-duration: 818ms;
        }
        .confetti-piece:nth-child(odd) {
            background: #7431e8;
        }
        .confetti-piece:nth-child(even) {
            z-index: 1;
        }
        .confetti-piece:nth-child(4n) {
            width: 5px;
            height: 12px;
            -webkit-animation-duration: 2000ms;
        }
        .confetti-piece:nth-child(3n) {
            width: 3px;
            height: 10px;
            -webkit-animation-duration: 2500ms;
            -webkit-animation-delay: 1000ms;
        }
        .confetti-piece:nth-child(4n-7) {
            background: red;
        }
        @-webkit-keyframes makeItRain {
            from {opacity: 0;}
            50% {opacity: 1;}
            to {-webkit-transform: translateY(350px);}
        }
    </style>
    <div class="confetti">
        <div class="confetti-piece"></div>
        <div class="confetti-piece"></div>
        <div class="confetti-piece"></div>
        <div class="confetti-piece"></div>
        <div class="confetti-piece"></div>
        <div class="confetti-piece"></div>
        <div class="confetti-piece"></div>
        <div class="confetti-piece"></div>
        <div class="confetti-piece"></div>
        <div class="confetti-piece"></div>
        <div class="confetti-piece"></div>
        <div class="confetti-piece"></div>
        <div class="confetti-piece"></div>
    </div>

    @can('Instructor')
        {{-- Instructor sidebar --}}
    <x-instructor-sidebar />
    @endcan
    <!-- container header -->
    <div class="header-wrap page-dark">
        <div class="uk-container uk-container-expand @auth rm-expand @endauth">
            <!-- navbar -->
            @include('layouts.navigation')
            <div class="page-header uk-margin-medium-top uk-margin-medium-bottom breadcrumb" uk-grid>
                <div class="uk-width-expand">
                    <h3 class="uk-margin-remove-bottom title-add">Students Leaderboard</h3>
                    <h5 class="uk-margin-remove-bottom title-add uk-margin-remove">All top students ranks.</h5>
                </div>
                <div class="line divider"></div>
            </div>
        </div>

    </div>
    <div class="uk-container uk-container-large">
        <div class="section-wrapper rooms uk-margin-medium-bottom " style=" padding: 38px;">
            <div class="uk-card uk-card-default classroom-card uk-margin-bottom classrank-card">
                <div class="activity-wrapper classrank-wrapper">
                    <table class="uk-table uk-table-middle uk-table-divider myclassrank_table"  id="myclassrank_table" style="width:100%;">
                        <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody class="myclassrank_table_body">
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="uk-width-expand uk-margin-top">
                <div class="uk-form-controls">

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('footerScripts')
    @parent
<script type="text/javascript">
$(document).ready(function() {

    let classRenktable=''
    classRenktable  =  $('#myclassrank_table').DataTable({
        columnDefs: [
            { "width": "0%", "targets": 0 },
        ],
        order: [[2, 'DESC']],
        processing: true,
        pageLength: 5,
        serverSide: true,
        // ordering: false,
        bLengthChange: false,
        "bFilter": false,
        "bInfo" : false,
        responsive: true,
        dom: 'Blfrtip',
        ajax: {
            url: "{{route('get-classrank-classes-data')}}",
            data: function (d) {
                d.classroom_id="{{request()->id}}"
            },
        },
        "language": {
            "processing":
                `<div class="db-loader">
                        <span class='fa-stack fa-lg'>
                            <i class='fa fa-spinner fa-spin fa-stack-2x fa-fw'></i>
                           </span>Loading ... </div>`,
        },
        columns: [
            {data: 'id', name: 'id'},// fun in line number 594
            {data: 'name', name: 'name',render:function (data,type,full){
                    let image;
                    if(full['profile_image']==null){
                        if(full['grade']==1){

                            image='/uploads/no-image/first-year.png'
                        }else if(full['grade']==2){
                            image='/uploads/no-image/second-year.png'

                        }else{
                            image='/uploads/no-image/third-year.png'

                        }
                    }else{
                        image=`/uploads/profile_images/${full['profile_image']}`
                    }
                    return `
                        <img class="avatar-resize uk-border-circle rank-img" src="${image}" alt="avatar">
                        <h4 class="uk-margin-remove-bottom uk-margin-small-top inline-block uk-margin-small-left uk-text-bold"><a href="#" class=""><span class="light-color uk-text-bold"> ${data}</span></a>
                        </h4>
                                `;
                }
            },
            {data: 'points',orderable:true,name: 'points',render:function (data,type,full){
                    return `
                                <div class="coins-container inline-block uk-margin-left">
                                    <img src="/img/coin.png" class="coin"><span class="light-color">${data}</span>
                                </div>`
                }
            },

        ],
        rowCallback: function (row, data) {
            if ( data.id == "{{auth()->id()}}" ) {
                $(row).addClass('me');
            }
        }
    });
    classRenktable.on( 'draw.dt', function () {
        var PageInfo = $('#myclassrank_table').DataTable().page.info();
        classRenktable.column(0, { page: 'current' }).nodes().each( function (cell, i) {
            cell.innerHTML = `<div class="class_rank_point"> ${i + 1 + PageInfo.start} </div>`;
        } );
        let keyOne = $('table tr:first-child .class_rank_point').text();
        let keyTwo = $('table tr:nth-child(2) .class_rank_point').text();
        let keyThree = $('table tr:nth-child(3) .class_rank_point').text();
        if( keyOne == 1){
            $("table tr:first-child td:nth-child(3)").prepend('<div class="first-icon inline-block"><img src="/img/first-svg-icon.svg"></div>');
            $("table tr:first-child td:nth-child(2)").append('<img src="/img/first-place.png" class="first-place">');
            $("table tr:first-child").addClass("first-rank");
        }
        if (keyTwo == 2){
            $("table tr:nth-child(2) td:nth-child(2)").append('<img src="/img/second-place.png" class="first-place">');
            $("table tr:nth-child(2)").addClass("second-rank");
        }
        if (keyThree == 3) {
            $("table tr:nth-child(3) td:nth-child(2)").append('<img src="/img/third-place.png" class="first-place">');
            $("table tr:nth-child(3)").addClass("third-rank");
        }
    } );



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
