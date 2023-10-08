@extends('layouts.app')
@section('title', $data->id === Auth::user()->id ? Auth::user()->first_name.' '. Auth::user()->last_name : $data->first_name.' '.$data->last_name)

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
                    <div class="uk-flex-center uk-grid" uk-grid>
                        <div class="uk-padding-remove-left">
                            <div class="profile-img-wrapper" id="profile-img-wrapper">
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
                                <div >
                                    <h3 class="uk-margin-remove uk-text-left">{{ $data->first_name }}'s Parent</h3>
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
                            <div class="stats-profile uk-card uk-card-default">
                                <div class="uk-header-card">
                                    <div class="target-wrapper">
                                        <img class="uk-width-3-4 target" src="{{ asset('img/xp-level.svg') }}">
                                    </div>
                                </div>
                                <div class="car-body">
                                    <strong class="points-card">100</strong>
                                    <p class="uk-margin-small-top uk-margin-remove-bottom uk-margin-auto uk-width-1-2 uk-text-default light-link">Total Experience</p>
                                </div>
                            </div>
                        </div>
                        <div class="uk-width-1-2@s uk-width-1-3@m">
                            <div class="stats-profile uk-card uk-card-default">
                                <div class="uk-header-card padding-less">
                                    <div class="target-wrapper">
                                        <img class="uk-width-3-4 target" src="{{ asset('img/xp-lesson.svg') }}">
                                    </div>
                                </div>
                                <div class="car-body">
                                    <strong class="points-card">{{$completed}}</strong>
                                    <p class="uk-margin-small-top uk-margin-remove-bottom uk-margin-auto uk-width-1-2 uk-text-default light-link">Rooms Completed</p>
                                </div>
                            </div>
                        </div>
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
                        <li class="uk-active"><a href="#">My Activity</a></li>
                        <li ><a href="#">My Classrooms</a></li>
                    </ul>
                </div>
            @endif

            <div class="uk-margin-medium-top">
                <ul class="uk-switcher uk-margin switcher-container">


                        <li class="uk-active">
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
                                            <th class="uk-width-small">Value</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>

                            </section>
                        </li>
                    <li >

                            <div class="uk-child-width-expand@s" uk-grid>
                                @forelse($myclassroom as $classroom)
                                <div class="uk-width-1-3@m uk-width-1-3@s">
                                    <div class="uk-card uk-card-default classroom-card">
                                        @foreach ( $classroom->photos as $photo)
                                        <div class="uk-card-header uk-card-media-top classroom-header border-radius" style="background-image:url({{ url('uploads/media/'. $photo->path);}});">
                                        @endforeach
                                            <div class="uk-flex uk-padding-small border-radius">
                                                <div class="uk-width-3-4">
                                                    <a href="">
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
                                                            {{\App\Models\Grade::findOrFail($classroom->grade->grade_id)->name}}
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

                                        <div class="uk-card-footer">
                                            <a href="" class="uk-button uk-borderless uk-button-text">Enter the class</a>
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
                                            <p class="uk-margin-remove">You haven’t Enrolled in classrooms Yet! checkout classrooms  <a href="{{route('classrooms.index')}}">Here.</a></p>
                                            @else
                                                <p class="uk-margin-remove">They haven’t Enrolled in classrooms Yet! </p>

                                            @endif
                                        </div>
                                    </div>
                                @endforelse


                            </div>

                    </li>

                </ul>
            </div>

        </div>
    </div>

</div>

@endsection
@section('footerScripts')
    @section('script')
        <script>
            $(document).ready(function () {
                let myWork_table = ''
                myWork_table = $('#myWork_table').DataTable({
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
                        url: "{{route('get-mywork-parent-data')}}",
                        data: function (d) {
                            d.classroom_id = "{{request()->id}}",
                            d.type='parent'
                        },
                    },
                    "language": {
                        "processing":
                            `<div style=" display: flex; margin-top: 150px; margin-left: 120px">
                        <span class='fa-stack fa-lg'>
                            <i class='fa fa-spinner fa-spin fa-stack-2x fa-fw'></i>
                        </span>&emsp;Processing ... </div>`,
                    },
                    columns: [
                        {
                            data: 'title', name: 'title', render: function (data, type, full) {
                                let icon = ''
                                if (full['type'] == 'Quiz') {
                                    icon = '<img class="icon-activity" src="{{ asset('img/exam.svg') }}" alt="room-icon">'

                                } else  {
                                    icon = '<img class="icon-activity" src="{{ asset('img/xp-lesson.svg') }}" alt="room-icon">'

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
            })

        </script>
    @endsection
