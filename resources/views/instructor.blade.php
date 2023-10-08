@extends('layouts.app')
@section('title', $data->id === Auth::user()->id ? Auth::user()->first_name.' '. Auth::user()->last_name : $data->first_name.' '.$data->last_name)

@section('body')

<!-- container -->
<div class="wrapper-page-light">
    @if ($data->id === Auth::user()->id)
        {{-- Instructor sidebar --}}
        <x-instructor-sidebar />
    @endif
    <!-- container header -->
    <div class="header-wrap page-dark">
        @if ($data->id === Auth::user()->id)
        <div class="uk-container uk-container-expand rm-expand">
        @else
        <div class="uk-container uk-container-expand">
        @endif
            <!-- navbar -->
            @include('layouts.navigation')
        </div>
        <!-- Instructor Data -->
        <div class="uk-container uk-container-expand rm-expand uk-margin-medium-top">
            <div class="student-info profile-info uk-margin-small-top uk-grid-small uk-child-width-expand@s uk-text-center" uk-grid>
                <div class="uk-width-1-2@l uk-width-3-4@m uk-margin-small-top">
                    <div class="uk-flex-center uk-grid" uk-grid>
                        <div class="uk-padding-remove-left uk-width-1-6">
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
                            @else
                            <div class="uk-flex uk-flex-center uk-margin-top">
                                <div class="social-links"><a href="#"><span uk-icon="icon:facebook; ratio:.8"></span></a></div>
                                <div class="uk-margin-small-left social-links"><a href="#"><span uk-icon="icon:twitter; ratio:.8"></span></a></div>
                            </div>
                            @endif
                        </div>
                        <div class="uk-padding-small-left uk-width-3-4">
                            <div>
                                {{-- <x-full-name /> --}}
                                <div >
                                    <h3 class="uk-margin-remove uk-text-left">{{ $data->first_name .' '. $data->last_name}}</h3>
                                </div>
                                <div>
                                    <p class="uk-margin-remove-top uk-text-left">
                                        @foreach ($data->roles as $role)
                                            @if($role->id === 5 || $role->id === 6 || $role->id === 7 || $role->id === 2)
                                            {{ $role->name }}
                                            @endif
                                        @endforeach
                                    </p>
                                </div>
                                <div>
                                    <p class="uk-margin-remove-top uk-text-left">
                                        {{ $data->bio }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if(user_can_any('classroom'))
                <div class="uk-width-1-2@l uk-width-1-4@m uk-margin-large-top">
                    <a href="{{ route('classrooms.create') }}" class="uk-button uk-button-primary uk-width-1-1 uk-width-1-2@l border-radius uk-padding-remove"><span class="add-icon" uk-icon="icon:plus; ratio: .7"></span>Add New Classroom</a>
                </div>
                @else
                <div class="uk-width-1-2@l uk-width-1-4@m uk-margin-large-top">
                   {{-- <a href="{{ route('classrooms.create') }}" class="uk-button uk-button-primary uk-width-1-1@m uk-width-1-2@l border-radius uk-padding-remove"><span class="add-icon" uk-icon="icon:commenting; ratio: .9"></span>Send Message</a> --}}
                </div>
                @endif
            </div>

            <!-- Progress and Achievments -->
            <div class="progress-container uk-margin-small-top uk-margin-large-bottom">

                <div class="divider uk-margin-medium-top"></div>

            </div>
        </div>

    </div>

    @if ($data->id != Auth::user()->id)
        <!-- Tabs and Content -->
    <div class="wrapper-switcher-profile f-height">
        <div class="uk-container">
            <!-- Tabs -->
            <div class="uk-margin-medium-top">
                <ul class="uk-flex-center uk-margin-remove-bottom" uk-tab uk-switcher="connect: .switcher-container">
                    <li class="uk-active"><a href="#">Classrooms</a></li>
                </ul>
            </div>

            <div class="uk-margin-medium-top">
                <ul class="uk-switcher uk-margin switcher-container">
                    <li class="uk-active">

                            <div class="uk-child-width-expand@s" uk-grid>
                                @forelse($myclassroom as $classroom)
                                <div class="uk-width-1-3">
                                    <div class="uk-card uk-card-default classroom-card">

                                        <div class="uk-card-header uk-card-media-top classroom-header border-radius">
                                            <div class="uk-flex uk-padding-small border-radius">
                                                <div class="uk-width-3-4">
                                                    <a href="#">
                                                        <h3 class="uk-card-title uk-margin-remove-bottom">
                                                            {{$classroom->title}}</h3>
                                                    </a>
                                                    <p class="uk-text-meta uk-margin-small-bottom uk-margin-remove-top"><time datetime="2016-04-01T19:00">{{\Carbon\Carbon::parse($classroom->created_at)->format('j F, Y')}}</time></p>
                                                    <a href="#">
                                                        <p class="uk-margin-remove inline-block">
                                                            {{ $classroom->user->first_name }} {{ $classroom->user->last_name }}

                                                        </p>
                                                    </a>
                                                    <br>
                                                    <a href="#">
                                                        <p class="uk-margin-remove inline-block">
                                                            {{\App\Models\Grade::findOrFail($classroom->grade->grade_id)->name}}
                                                        </p>
                                                    </a>
                                                </div>
                                                <div class="uk-width-1-4">
                                                    <span class="more-dots uk-border-rounded" uk-icon="icon:more-vertical;"></span>
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

                                        <div class="uk-card-footer">
                                            <a href="/classroom/{{$classroom->id}}/updates" class="uk-button uk-borderless uk-button-text">Enter the class</a>
                                        </div>

                                    </div>
                                </div>
                                @empty

                                    <div class="nothing-toshow uk-flex uk-flex-center uk-flex-column uk-margin-large-top">
                                        <div class="nothing-toshow-wrapper uk-margin-medium-left">
                                            <img class="nothing-toshow-img" src="{{ asset('img/nothing-toshow.webp') }}" alt="notification-body">
                                        </div>
                                        <div class="uk-card uk-card-default uk-text-center nothing-para">
                                            <p>You haven’t Enrolled in classrooms Yet! checkout classrooms  <a href="{{route('classrooms.index')}}">Here.</a></p>
                                        </div>
                                    </div>
                                @endforelse

                            </div>

                    </li>
                </ul>
            </div>

        </div>
    </div>
    @else
    <div class="instructor-stats uk-margin-medium-top f-height">
        <div class="uk-container uk-container-expand rm-expand">
            <div class="uk-child-width-expand@s" uk-grid>
                <div class="uk-width-1-4@m">
                    <div class="uk-card uk-card-instructor">
                        <div class="uk-flex uk-flex-middle" uk-grid>
                            <div class="uk-width-expand">
                                <i class="fa-solid fa-book-open fa-1x"></i>
                                <span class=""> Classrooms</span>
                            </div>
                            <div class="uk-width-auto">
                                <div class="material-number">{{\App\Models\Classroom::where('instructor_id',auth()->id())->count()}}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="uk-width-1-4@m">
                    <div class="uk-card uk-card-instructor">
                        <div class="uk-flex uk-flex-middle" uk-grid>
                            <div class="uk-width-expand">
                                <i class="fa-solid fa-chalkboard fa-1x"></i>
                                <span class=""> Rooms</span>
                            </div>
                            <div class="uk-width-auto">
                                <div class="material-number">{{\App\Models\Room::where('user_id',auth()->id())->count()}}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="uk-width-1-4@m">
                    <div class="uk-card uk-card-instructor">
                        <div class="uk-flex uk-flex-middle" uk-grid>
                            <div class="uk-width-expand">
                                <i class="fa-solid fa-clipboard-check fa-1x"></i>
                                <span class=""> Lessons</span>
                            </div>
                            <div class="uk-width-auto">
                                <div class="material-number">{{\App\Models\Lesson::where('user_id',auth()->id())->count()}}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="uk-width-1-4@m">
                    <div class="uk-card uk-card-instructor">
                        <div class="uk-flex uk-flex-middle" uk-grid>
                            <div class="uk-width-expand">
                                <i class="fa-solid fa-puzzle-piece fa-1x"></i>
                                <span class=""> Quizes</span>
                            </div>
                            <div class="uk-width-auto">
                                <div class="material-number">{{\App\Models\Quiz::where('user_id',auth()->id())->where('type',2)->count()}}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="uk-width-1-4@m">
                    <div class="uk-card uk-card-instructor">
                        <div class="uk-flex uk-flex-middle" uk-grid>
                            <div class="uk-width-expand">
                                <i class="fa-solid fa-spell-check fa-1x"></i>
                                <span class=""> Exams</span>
                            </div>
                            <div class="uk-width-auto">
                                <div class="material-number">{{\App\Models\Quiz::where('user_id',auth()->id())->where('type',1)->count()}}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="uk-width-1-4@m">
                    <div class="uk-card uk-card-instructor">
                        <div class="uk-flex uk-flex-middle" uk-grid>
                            <div class="uk-width-expand">
                                <i class="fa-solid fa-file-circle-exclamation fa-1x"></i>
                                <span class=""> Assignments</span>
                            </div>
                            <div class="uk-width-auto uk-padding-remove">
                                <div class="material-number">{{\App\Models\Quiz::where('user_id',auth()->id())->where('type',3)->count()}}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="uk-width-1-4@m">
                    <div class="uk-card uk-card-instructor">
                        <div class="uk-flex uk-flex-middle" uk-grid>
                            <div class="uk-width-expand">
                                <i class="fa-solid fa-users fa-1x"></i>
                                <span class=""> Total Students</span>
                            </div>
                            <div class="uk-width-auto uk-padding-remove">
                                <div class="material-number">{{\App\Models\User::whereIn('type',[2,3])->whereHas('studentOnlyInstructor')->count()}}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="uk-width-1-4@m">
                    <div class="uk-card uk-card-instructor">
                        <div class="uk-flex uk-flex-middle" uk-grid>
                            <div class="uk-width-expand">
                                <i class="fa-solid fa-users-viewfinder fa-1x"></i>
                                <span class=""> Total Assistants</span>
                            </div>
                            <div class="uk-width-auto uk-padding-remove">
                                <div class="material-number">{{\App\Models\Instructor::where('instructor_id',auth()->id())->count()}}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif


</div>

@endsection
@section('footerScripts')
