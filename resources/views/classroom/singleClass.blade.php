@extends('layouts.app')
@section('title', $class->title)
@section('css')
<script src="https://fastly.jsdelivr.net/npm/shepherd.js@10.0.1/dist/js/shepherd.min.js"></script>
<link rel="stylesheet" href="https://fastly.jsdelivr.net/npm/shepherd.js@10.0.1/dist/css/shepherd.css"/>
@endsection
@section('body')
<!-- container -->
<div class="wrapper-page-light f-height">
    @if(request()->is('classroom/'.request()->id.'/classRank'))
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
    @endif

    @can('Instructor')
    {{-- Instructor sidebar --}}
    <x-instructor-sidebar />
    @endcan
    <!-- container header -->
    <div class="header-wrap page-dark">
        <div class="uk-container uk-container-expand">
            <!-- navbar -->
            @include('layouts.navigation')
        </div>

        <!-- Classroom Data -->
        <div class="uk-container uk-padding-large-bottom classroom-header-s">
            <div class="divider uk-margin-small-bottom"></div>
        </div>
    </div>

    <!-- Tabs and Content -->
    <div class="wrapper-switcher-profile">
        <div class="uk-container uk-container-large">
            <!-- Tabs -->
            <div class="uk-margin-medium-top classrooms-tabs">
                <ul class="uk-flex-center uk-margin-remove-bottom uk-tab">
                    <li class="{{ Request::is('classroom/'.request()->id.'/classwork') ? 'uk-active' : '' }}"><a id="classwork" href="/classroom/{{request()->id}}/classwork">Classwork</a></li>
                    <li class="{{ Request::is('classroom/'.request()->id.'/myWork') ? 'uk-active' : '' }}"><a id="myWork" href="/classroom/{{request()->id}}/myWork">My work</a></li>
                    <li class="{{ Request::is('classroom/'.request()->id.'/classRank') ? 'uk-active' : '' }}"><a id="classRank" href="/classroom/{{request()->id}}/classRank">Classrank</a></li>
                    <li class="{{ Request::is('classroom/'.request()->id.'/updates') ? 'uk-active' : '' }}"><a id="updates" href="/classroom/{{request()->id}}/updates">Classinfo</a></li>
                </ul>
            </div>

            @php
                $attendance=\App\Models\Attendance::where(['classroom_id'=>request()->id,'student_id'=>auth()->id(),'instructor_id'=>$class->user->id,'attendance_type'=>'room'])->whereIn('status',[0,2,3])->pluck('attendance_id')->toArray();

                $rooms=\App\Models\Room::whereIn('id',$attendance)->pluck('title')->toArray();
            @endphp

            @if(count($attendance)>0 && auth()->user()->type!==2 && count($attendance) !=$class->absence_times && \App\Models\ClassroomStudent::where('user_id',auth()->id())->where('classroom_id',$class->id)->first()->block !== 1)
                <div class="uk-width-expand">
                    <div class="uk-card uk-card-default classroom-card border-danger uk-padding-small suspended-card uk-margin-medium-top">
                        <span uk-icon="icon: info; ratio: 1"></span>
                        You missed these rooms ({{implode(',',$rooms)}}) Please note that you will be suspended when you miss ({{(int)$class->absence_times - (int)count($attendance)}}) rooms again!
                    </div>
                </div>
            @endif
            {{-- Suspended --}}
            @if(auth()->user()->type!==2 && (\App\Models\Setting::demoRoom() == $class->id ?false : \App\Models\ClassroomStudent::where('user_id',auth()->id())->where('classroom_id',$class->id)->first()->block))
                <div class="uk-width-expand">
                <div class="uk-card uk-card-default classroom-card border-danger uk-padding-small suspended-card uk-margin-medium-top">
                    <span uk-icon="icon: info; ratio: 1"></span>
                    You have been suspended due to the absence limitation being exceeded! Please get in touch with your Instructor Immediately to avoid Permanent Removal.
                </div>
            </div>
            @endif
            <div class="uk-margin-medium-top classwork-tab">
                <ul class="uk-margin switcher-container">
                    <li class="uk-active">
                        @if(\request()->is('classroom/'.request()->id.'/updates'))
                        <div class="uk-child-width-expand@s" uk-grid>
                            <div class="uk-width-1-5@m uk-width-1-1@s">
                                <div class="uk-card uk-card-default classroom-card border-success">
                                    <div class="uk-flex uk-padding-small uk-padding-remove-bottom border-radius">
                                        <div class="uk-width-expand">
                                            <h3 class="uk-margin-remove-bottom dark-font">
                                            Upcoming</h3>
                                        </div>
                                    </div>
                                    <div class="uk-padding-small">
                                        <p class="light-dark">Woohoo! you miss nothing Keep earning points!</p>
                                        {{-- <p class="light-dark">
                                            An exam <a href="#"><span class="unit-title">Organic</span></a> is running try to be a terminator.
                                        </p> --}}
                                    </div>
                                </div>
                            </div>
                            <div class="uk-width-expand">
                                @include('classroom.activities')
                            </div>

                            <div class="uk-width-1-4 hidden-small">
                                <div class="classroom-card uk-padding-small uk-margin-small-bottom">
                                    <div class="uk-width-1-1">
                                        <div class="uk-card uk-card-default last-room-wrapper uk-text-center">
                                            <div class="room-numbers-wrapper">
                                                <div class="room-numbers">
                                                    {{sprintf('%02d',count(\App\Models\Classroom::where('id',$class->id)->first()->rooms()->whereHas('lessons')->get()))}}
                                                </div>
                                            </div>

                                            <p class="latest-room-meta light-color uk-margin-small-top uk-margin-small-bottom">
                                                Latest room in this class
                                                <span>added {{($class->rooms()->whereHas('lessons')->latest()->first())? $class->rooms()->whereHas('lessons')->latest()->first()->created_at->format('M d , Y'):'--'}}</span>
                                            </p>

                                            <h5 class="latest-room-title uk-margin-remove">
                                                {{$class->rooms()->whereHas('lessons')->latest()->first()->title??'--'}}
                                            </h5>

                                            <p class="latest-room-desc uk-margin-small-top light-color">
                                                {{$class->rooms()->whereHas('lessons')->latest()->first()->description??'--'}}                                    </p>
                                            @if(count($class->rooms)>0 )
                                                    <a href="{{route('room.show',$class->rooms()->whereHas('lessons')->latest()->first()->id??'-')}}" >
                                                        <div class="start-rooming">
                                                            <img src="{{ asset('img/video-icon.svg') }}" alt="video-icon" uk-svg>
                                                            <span>
                                                        Watch</span>
                                                        </div>
                                                    </a>
                                                @endif

                                        </div>
                                    </div>
                                </div>
                                <div class="classroom-card uk-padding-small uk-margin-small-top">
                                    <div class="uk-width-1-1">
                                        <div class="uk-card uk-card-default uk-padding-small">
                                            <div class="class-wrapper">
                                                <h3 class="class-title uk-margin-small-bottom uk-text-bold">
                                                    {{ $class->title }}
                                                </h3>
                                                <p class="class-desc uk-margin-remove-top light-color uk-margin-small-bottom">
                                                    {{ $class->description }}
                                                </p>
                                                <div class="class-metas uk-margin-small-bottom" uk-grid>
                                                    <div class="rooms-number uk-width-auto">
                                                        <img src="{{ asset('img/books.svg') }}" alt="book-icon" uk-svg>
                                                        <span><span>{{count(\App\Models\Classroom::where('id',$class->id)->first()->rooms()->whereHas('lessons')->get())}}</span> rooms</span>
                                                    </div>
                                                    <div class="rooms-schedule uk-width-1-2">
                                                        <img src="{{ asset('img/clock.svg') }}" alt="clock-icon" uk-svg>
                                                        <span>{{$schedule}}</span>
                                                    </div>
                                                </div>
                                                <div class="class-metas uk-margin-remove-top" uk-grid>
                                                    <div class="rooms-number uk-width-auto uk-margin-remove-top">
                                                        <img class="uk-border-circle" src="{{ url('uploads/profile_images/'.$class->user->profile_image ) }}" alt="avatar">
                                                        <span>{{ $class->user->first_name }} {{ $class->user->last_name }}</span>
                                                    </div>
                                                </div>
                                                <div class="uk-flex uk-margin-remove-top" uk-grid>
                                                    <a href="#">
                                                        <div class="request-changing">
                                                            <span class="req-icon" uk-icon="icon:git-branch"></span>
                                                            @if(count($requests)==0)
                                                            <span uk-toggle="target: #modal-request" >Request to change</span>
                                                            @else
                                                                <span>Request Under Review</span>
                                                            @endif
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        @endif
                    </li>

                    <li>
                        @if(request()->is('classroom/'.request()->id.'/classwork'))
                        <div class="uk-child-width-expand@s classwork" uk-grid>
                            <div class="uk-width-1-6@l uk-wdith-1-1@s">
                                <div id="classwork-nav" class="uk-card uk-card-default classroom-card border-success">
                                    <ul class="uk-nav uk-nav-default uk-padding-small nav classwork-nav">
                                        <li class="uk-active"><a href="#rooms">Rooms</a></li>
                                        <li><a href="#exams">Exams</a></li>
                                        {{-- <li><a href="#assignments">Assignments</a></li> --}}
                                    </ul>
                                </div>
                            </div>
                            <div class="uk-width-expand">

                                @include('classroom.classwork')

                                <section id="exams" class="section-wrapper exams uk-margin-medium-bottom">
                                    <h2>Exams</h2>
                                    <div class="divider uk-margin-bottom light"></div>
                                    <div id="exam">
                                        @forelse($exams as $key=> $exam)
                                            @php
                                                $result=false;
                                                    if(auth()->user()->type!==2){
                                                        if($exam->usedCoupon->last()){
                                                            $day=$exam->lock_after;
                                                            $created_at=($exam->usedCoupon?$exam->usedCoupon->last()->created_at:Null);
                                                            $endDate = \Carbon\Carbon::parse($created_at)->addDays($day);
                                                            $date1 = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $endDate);
                                                            $date2 = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', now());

                                                            $result = $date1->gt($date2);

                                                            }else{
                                                                $result=false;
                                                            }

                                                        //coronJopAttendenceExam($exam,auth()->id());
                                                    }
                                            @endphp
                                            <div class="uk-card  {{auth()->user()->type!==2 && \App\Models\ClassroomStudent::where('user_id',auth()->id())->where('classroom_id',$class->id)->first()->block?"block_div":''}} uk-card-default classroom-card up-hover uk-margin-bottom {{ checkMissedExam($exam,auth()->id()) ?'hover_missed':'' }}">
                                                <div class="uk-padding-small border-radius">
                                                    <div class="uk-flex cursor-pointer">
                                                        <div class="uk-width-expand">
                                                            <div class="up-room">
                                                                <img class="icon-activity" src="{{ asset('img/exam.svg') }}" alt="room-icon">
                                                                <a  @if(auth()->user()->type!==2 &&\App\Models\ClassroomStudent::where('user_id',auth()->id())->where('classroom_id',$class->id)->first()->block==0) href="{{route('quiz.show',$exam->id)}}" @elseif(auth()->user()->type==2) href="/quiz/{{$exam->id}}/show_answer" @endif  class="room-link">
                                                                    <h5 class="room-text-data dark-font uk-margin-remove-bottom uk-margin-small-top inline-block uk-margin-small-left uk-text-bold rm-t-s">Exam {{sprintf('%02d', (count(\App\Models\Quiz::where('classroom_id','like',"%{$class->id}%")->where('type',2)->get()) -$key))}} <span class="unit-title uk-text-bold"> {{$exam->title}}.</span>
                                                                    </h5>
                                                                </a>
                                                                <p class="date-padding light-dark uk-margin-small-bottom uk-margin-remove-top uk-margin-medium-left"><time datetime="2016-04-01T19:00"> {{\Carbon\Carbon::parse($exam->created_at)->format('M d, Y')}}</time></p>
                                                            </div>
                                                        </div>
                                                        <div class="uk-width-auto uk-margin-small-top rm-t-s">

                                                            @if(!checkMissedExam($exam,auth()->id()))
                                                                <span class="lock-time">
                                                    {{unlockafterRoomDetail($exam->price ,$exam->lock_after,$exam->usedCoupon->last()->created_at??'')}}
                                                    </span>
                                                            @endif
                                                            <span class="price">{{$exam->price??0}} L.E</span>
                                                            @if(checkMissedExam($exam,auth()->id()))
                                                                <span class="danger-text" uk-tooltip="Exam is missed"  uk-icon="icon: warning" ></span>
                                                                <span uk-tooltip="Exam is locked" @if((!$result && $exam->price!=0 ||$exam->price!=null)) uk-icon="icon: lock" @endif></span>

                                                            @elseif((!$exam->usedCoupon) || ( $exam->usedCoupon && !$result))
                                                                    @if(!$exam->price || (!$result && $exam->price=="0"))
                                                                        <span uk-tooltip="Room is unlocked"  uk-icon="icon: unlock" ></span>
                                                                    @else
                                                                        <span uk-tooltip="Room is locked"  uk-icon="icon: lock" ></span>

                                                                    @endif
                                                                @else
                                                                <span uk-tooltip="Exam is unLocked"  uk-icon="icon: unlock" ></span>
                                                            @endif
                                                            @if(checkCompleteExam($exam->id,auth()->id()))
                                                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="display: none;">
                                                                    <symbol viewBox="0 0 128 128" id="tick"><path d="M112.639844,0 L35.84,88.575 L17.9203125,69.82 L0,69.82 L35.84,128 L128,0 L112.639844,0 Z"></path></symbol>
                                                                </svg>
                                                                <svg class="check-icon rooms-icon viewed">✔<use xlink:href="#tick">
                                                                </svg>
                                                            @else

                                                                <svg uk-tooltip="UnCompleted" class="check-icon rooms-icon">✔<use xlink:href="#tick">
                                                                </svg>
                                                            @endif
                                                        </div>
                                                        @if(auth()->user()->type!==2 && \App\Models\ClassroomStudent::where('user_id',auth()->id())->where('classroom_id',$class->id)->first()->block==0)
                                                            <div class="uk-width-auto">
                                                                <span class="more-dots light uk-border-circle" uk-icon="icon:more-vertical;"></span>
                                                                <div class="uk-border-rounded" uk-dropdown="mode: click">
                                                                    <ul class="uk-nav uk-dropdown-nav">
                                                                        <li><a href="#">Copy</a></li>

                                                                        @if(($exam->price!=="0" && !$result) || $exam->price)

                                                                            @if(!$exam->usedCoupon || ( $exam->usedCoupon && !$result))
                                                                                <li><a href="#modal-center" data-QuizId="{{$exam->id}}" data-roomPrice="{{$exam->price}}" data-gradeId="{{getGradeId(request()->id)}}" id="unlock_code" uk-toggle>Unlock</a></li>
                                                                            @endif
                                                                        @endif
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        @elseif(auth()->user()->type==2)
                                                            <div class="uk-width-auto">
                                                                <span class="more-dots light uk-border-circle" uk-icon="icon:more-vertical;"></span>
                                                                <div class="uk-border-rounded" uk-dropdown="mode: click">
                                                                    <ul class="uk-nav uk-dropdown-nav">
                                                                        <li><a href="#" id="copyLink" data-link="{{route('quiz.show',$exam->id)}}">Copy</a></li>
                                                                        @if(( $exam->price!=0 ))
                                                                            @if(! $exam->usedCoupon || ( $exam->usedCoupon && !$result))
                                                                                <li><a href="#modal-center" data-roomId="{{$exam->id}}" data-roomPrice="{{$exam->price}}" data-gradeId="{{getGradeId(request()->id)}}" id="unlock_code" uk-toggle>Unlock</a></li>
                                                                            @endif
                                                                        @endif
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>

                                                </div>
                                            </div>
                                        @empty
                                            <div class="uk-card uk-card-default classroom-card up-hover uk-margin-bottom">
                                                <div class="uk-padding-small border-radius">
                                                    <div class="uk-flex collapse-header cursor-pointer">
                                                        <div class="uk-width-expand">
                                                            <div class="up-room">
                                                                <img class="icon-activity" src="{{ asset('img/exam.svg') }}"  alt="room-icon">
                                                                <h5 class="dark-font uk-margin-remove-bottom uk-margin-small-top inline-block uk-margin-small-left uk-text-bold"> <a href="#" class=""><span class="unit-title uk-text-bold"> No Found Exam</span></a>
                                                                </h5>

                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        @endforelse
                                    </div>


                                    <div>
                                        <div class="spinner loading-exam dark-font" style="display:none;">
                                            <div class="circle one"></div>
                                            <div class="circle two"></div>
                                            <div class="circle three"></div>
                                        </div>
                                        @if($examCount>5)
                                            <div class="uk-text-center uk-margin-top">
                                                <button class="edit uk-margin-small-right loading_exam" data-type="exam" id="loadmore">More exams</button>
                                            </div>
                                        @endif
                                    </div>
                                </section>

                                {{-- <section id="assignments" class="section-wrapper assignments uk-margin-medium-bottom">
                                    <h2>Assignments</h2>
                                    <div class="divider uk-margin-bottom light"></div>
                                    <div class="uk-card uk-card-default classroom-card up-hover uk-margin-bottom">
                                        <div class="uk-padding-small border-radius">
                                            <div class="uk-flex cursor-pointer">
                                                <div class="uk-width-expand">
                                                    <div class="up-room">
                                                        <img class="icon-activity" src="{{ asset('img/assignment.svg') }}" alt="room-icon">
                                                        <h5 class="room-text-data dark-font uk-margin-remove-bottom uk-margin-small-top inline-block uk-margin-small-left uk-text-bold rm-t-s">Assignment 01 <a href="#" class=""><span class="unit-title uk-text-bold"> Organic chemistry Ch1-2.</span></a>
                                                        </h5>
                                                        <p class="date-padding light-dark uk-margin-small-bottom uk-margin-remove-top uk-margin-medium-left"><time datetime="2016-04-01T19:00"> Jun 10, 2022</time></p>
                                                    </div>
                                                </div>
                                                <div class="uk-width-auto uk-margin-small-top rm-t-s">
                                                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="display: none;">
                                                        <symbol viewBox="0 0 128 128" id="tick"><path d="M112.639844,0 L35.84,88.575 L17.9203125,69.82 L0,69.82 L35.84,128 L128,0 L112.639844,0 Z"></path></symbol>
                                                        </svg>
                                                        <svg class="check-icon rooms-icon viewed">✔<use xlink:href="#tick">
                                                    </svg>
                                                </div>
                                                <div class="uk-width-auto">
                                                    <span class="more-dots light uk-border-circle" uk-icon="icon:more-vertical;"></span>
                                                    <div class="uk-border-rounded" uk-dropdown="mode: click">
                                                        <ul class="uk-nav uk-dropdown-nav">
                                                            <li><a href="#">Copy</a></li>
                                                            <li><a href="#">Report</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </section> --}}

                            </div>
                            <div class="uk-width-1-6 hidden-small">
                                {{-- Suspended --}}
                                @if(auth()->user()->type!==2 &&(\App\Models\Setting::demoRoom() == $class->id ?false : \App\Models\ClassroomStudent::where('user_id',auth()->id())->where('classroom_id',$class->id)->first()->block))
                                    <div class="uk-card uk-card-default classroom-card border-danger uk-padding-small suspended-card">
                                        Note while your account is suspended you can’t enter any room, and this may affect your rank.
                                    </div>
                                @else

                                @if(auth()->user()->grade_id==1)
                                    <div class="spider-wrapper">
                                        <img src="{{ asset('img/spider (1).png')}}" alt="first-grade">
                                    </div>
                                    @elseif(auth()->user()->grade_id==2)
                                    <div class="blackpanther-wrapper">
                                        <img src="{{ asset('img/panther.jpg')}}" alt="second-grade">
                                    </div>
                                    @else
                                    <div class="ironman-wrapper">
                                        <img src="{{ asset('img/iron-face.png')}}" alt="third-grade">
                                    </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                        @endif
                    </li>


                    <li>
                        @if(request()->is('classroom/'.request()->id.'/myWork'))
                            <section class="">
                                <h2 style="color: #000000">My Work</h2>
                                <div class="divider uk-margin-bottom light "></div>

                                <div class="activity-wrapper">
                                    <table class="uk-table uk-table-divider uk-margin-remove-bottom" id="myWork_table">
                                        <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th class="uk-width-small">Type</th>
                                            <th class="uk-width-small">Date</th>
                                            <th class="uk-width-small">Passed</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>

                            </section>

                        @endif
                    </li>

                    <li>
                        @if(request()->is('classroom/'.request()->id.'/classRank'))
                        <div class="section-wrapper rooms uk-margin-medium-bottom ">
                            <div uk-grid>
                                <div class="uk-width-expand"><h2>Classmates</h2></div>
                                <div class="uk-width-auto"><h2 class="dark-font uk-padding-small">{{count($students)}}</h2></div>
                            </div>
                            <div class="divider uk-margin-bottom light"></div>

                            <div class="uk-card uk-card-default classroom-card uk-margin-bottom classrank-card">

                            <div class="activity-wrapper classrank-wrapper">
                            <table class="uk-table uk-table-divider uk-margin-remove-bottom myclassrank_table uk-table-middle" id="myclassrank_table" style="width:100%;">
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
                        @endif
                    </li>

                </ul>
            </div>
        {{-- <div class="spinner loading dark-font" style="display:none;">--}}
            {{-- <div class="circle one"></div>--}}
{{--                <div class="circle two"></div>--}}
{{--                <div class="circle three"></div>--}}
{{--            </div> --}}
        </div>
    </div>
        @include('classroom.RequestChangeModal')

</div>
@endsection

@section('footerScripts')
    @section('script')
        <script>
            @if(request()->is('classroom/'.request()->id.'/classwork'))
                window.onscroll = function() {myFunction()};
                var navbar = document.getElementById("classwork-nav");
                var sticky = navbar.offsetTop;
                function myFunction() {
                    if (window.pageYOffset >= sticky) {
                        navbar.classList.add("fixed-sidCard")
                    } else {
                        navbar.classList.remove("fixed-sidCard");
                    }
                };
            @endif

            $(document).ready(function (){
                let myWork_table=''
                myWork_table  =  $('#myWork_table').DataTable({
                    processing: true,
                    ordering: false,
                    serverSide: true,
                    lengthMenu: [5, 10, 20, 50],
                    pageLength: 10,
                    bLengthChange: false,
                    "bFilter": false,
                    responsive: true,
                    dom: 'Blfrtip',
                    ajax: {
                        url: "{{route('get-mywork-data')}}",
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
                        {data: 'title', name: 'title',render:function (data,type,full){
                            let icon=''
                            if(full['type']=='Room'){


                                icon='<img class="icon-activity" src="{{ asset('img/xp-lesson.svg') }}" alt="room-icon">'
                            }else{
                                icon='<img class="icon-activity" src="{{ asset('img/exam.svg') }}" alt="room-icon">'

                            }
                            return `
                            ${icon}
                            <h5 class="uk-width-3-4 room-text-data dark-font uk-margin-remove-bottom uk-margin-small-top inline-block uk-margin-small-left uk-text-bold rm-t-s">
                                    ${data}
                            </h5>
                            `
                            }
                            },
                        {data: 'type', name: 'type'},
                        {data: 'created_at', name: 'created_at'},
                        {data: 'passed', name: 'passed'},
                    ],
                });

                let classRenktable=''
                classRenktable  =  $('#myclassrank_table').DataTable({
                    columnDefs: [
                        { "width": "0%", "targets": 0 },
                    ],
                    order: [[2, 'DESC']],
                    processing: true,
                    pageLength: 20,
                    serverSide: true,
                    // ordering: false,
                    bLengthChange: false,
                    "bFilter": false,
                    "bInfo" : false,
                    responsive: true,
                    dom: 'Blfrtip',
                    ajax: {
                        url: "{{route('get-classrank-data')}}",
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

                    ]
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

                        $(document).on('click','#classwork',function (){
                            window.location.href=$(this).attr('href')
                        })
                        $(document).on('click','#updates',function (){
                            window.location.href=$(this).attr('href')
                        })
                        $(document).on('click','#myWork',function (){
                            window.location.href=$(this).attr('href')
                        })
                        $(document).on('click','#classRank',function (){
                            window.location.href=$(this).attr('href')
                        })

                        $(document).on('click','#unlock_code',function (e) {
                            e.preventDefault();
                            var room_id =$(this).attr('data-roomId')
                            var grade_id =$(this).attr('data-gradeId')
                            var quiz_id =$(this).attr('data-QuizId')
                            var room_price =$(this).attr('data-roomPrice')

                            $('#modal-center #room_id_modal').val(room_id)
                            $('#modal-center #grade_id_modal').val(grade_id)
                            $('#modal-center #quiz_id_modal').val(quiz_id)
                            $('#modal-center h2 span').text(room_price +'L.E');
                        })
                        var paginate=1
                        var classroom_id ={{request()->id}}


                        $(document).on('click','#loadmore',function (e) {
                            let type=$(this).attr('data-type')

                            e.preventDefault();
                            paginate++
                            loadMoreData(classroom_id,paginate,type)
                        })
                        $(document).on('click', '#save_requset', function (e) {
                            e.preventDefault();
                            let id=$('#classroom_id_reguest').val();
                            let accetp_swal = Swal.fire({
                                title: " Note: You are about To Make Request Change !",
                                text: "",
                                icon: "warning",
                                type: "warning",
                                showCancelButton: true,
                                confirmButtonColor: '#DD6B55',
                                confirmButtonText: 'Continue',
                                cancelButtonText: "Close",
                                closeOnConfirm: false,
                                closeOnCancel: false
                            }).then(function (result) {
                                if (result.isConfirmed) {
                                    $.ajax({
                                        url: '/get_classroom_student',
                                        type: "get",
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                        },
                                        data: {id: id},
                                        success: function (res) {
                                            if(res.status){
                                                Swal.fire("Done",res.message, "success");
                                            }
                                        UIkit.modal('#modal-request').hide();

                                        },
                                        error:function (res) {
                                            Swal.fire("Close!", "Something is wrong, Please try again.", "error");
                                        }
                                    });
                                } else {
                                    Swal.fire("Close", "Close Success", "error");
                                }
                            })


                        })
                    })
                    function loadMoreData(classroom,paginate,type) {
                        $.ajax({
                            url: `?page=${paginate}&type=${type}`,
                            type: 'get',
                            datatype: 'html',
                            beforeSend: function() {
                                if(type=='exam'){
                                    $('.loading-exam').show();
                                }else{
                                    $('.loading').show();
                                }


                            }
                        })
                            .done(function(data) {
                                if(data.length == 0 || data.length==1) {
                                    if(type=='exam'){
                                        $('.loading_exam').hide();
                                        $('.loading-exam').html('No more exams.');
                                    }else{
                                        $('#loadmore').hide();
                                        $('.loading').html('No more Rooms.');
                                    }
                                    return;
                                } else {
                                    if(type=='exam'){
                                        $('#exam').append(data);
                                        $('.loading-exam').hide();
                                    }else{
                                    $('#post').append(data);
                                        $('.loading').hide();

                                    }
                                    $('#post').find('.up-room h5 small').each(function(key ,value){
                                    });
                                }
                            })
                            .fail(function(jqXHR, ajaxOptions, thrownError) {
                                alert('Something went wrong.');
                            });
                    }

                    $(document).on('click','#copyLink',function (e){
                        e.preventDefault();
                        var text = $(this).attr("data-link");

                        document.addEventListener('copy', function(e) {
                            e.clipboardData.setData('text/plain', text);
                            e.preventDefault();
                        }, true);
                        document.execCommand('copy');
                        Swal.fire("success!", "You copy Link.", "success");

                    })

                    $('.uk-select').select2({});


                    $(document).ready(function() {
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
                        let classId = {{request()->id}};
                        let roomId  = $(".room-link").attr("data-id");
                        // Init tour
                        const tour = new Shepherd.Tour({useModalOverlay: true});

                        tour.on("cancel", onTourEnd);
                        tour.on("complete", onTourEnd);

                        addStep({
                            id: 'step-0',
                            text: '<div style="text-align:center;"><img src="{{asset("img/teaching.jpg")}}" alt="welcome" style="width: 370px;"/><h2 class="uk-text-center">Welcome in your classroom👋!</h2><p class="uk-text-center">In your classwork Tab you can find all the Rooms and Exams each room contains lessons and Quizzes.!</p></div>',
                            attachTo: {
                                element: '',
                                on: ''
                            },
                            classes: 'step-extra-class welcome',
                            buttons: [
                                {
                                text: 'Let\'s Start💪',
                                action: tour.next
                                },
                                {
                                    action () {
                                        window.location.href = "/classroom/"+classId+"/classwork";
                                    },
                                    text: 'Cancel'
                                }
                            ]
                        });

                        addStep({
                            id: 'step-1',
                            text: 'You will find all rooms here each room contains lessons📖 and quizzes❓.',
                            title: 'Rooms Section 📚📖',
                            attachTo: {
                                element: '#rooms',
                                on: 'bottom'
                            },
                            classes: 'step-extra-class bottom-m',
                            buttons: [
                                {
                                text: 'Next',
                                action: tour.next
                                }
                            ]
                        });
                        addStep({
                            id: 'step-2',
                            text: 'This demo room is free but usually you will find a timer⏳ and after finishing the room will be closed🔐.',
                            title: 'Rooms Section 📚📖',
                            attachTo: {
                                element: '.lock-time',
                                on: 'bottom'
                            },
                            classes: 'step-extra-class bottom-m',
                            buttons: [
                                {
                                text: 'Next',
                                action: tour.next
                                }
                            ]
                        });
                        addStep({
                            id: 'step-3',
                            text: 'Click on the room title to open it👆.',
                            title: 'Rooms Section 📚📖',
                            attachTo: {
                                element: '.room-link',
                                on: 'bottom'
                            },
                            classes: 'step-extra-class bottom-m',
                            buttons: [
                                {
                                text: 'Next',
                                action () {
                                        window.location.href = "/room/"+roomId;
                                    },
                                }
                            ]
                        });


                        const queryString = window.location.search;
                        const urlParams = new URLSearchParams(queryString);
                        const shouldTourContinue = urlParams.get('shtc');
                        if (shouldTourContinue == 't') {
                            tour.start();
                        }
                });
                @php
    $noSubscription=false;
         $studentOffline=auth()->user()->type==4;

         if($studentOffline){
            if(!$coupon=auth()->user()->subscription){
                $noSubscription=true;
            }
         }
    @endphp

        @if ($noSubscription)
            UIkit.modal('#modal-classroom').show();
        @endif
    </script>
@endsection
