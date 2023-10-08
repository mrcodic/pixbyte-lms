@extends('layouts.app')
@section('title', 'Classrooms')

@section('body')

<!-- container -->

<div class="wrapper-page-light f-height @if(Auth::user()->type==3 || Auth::user()->type==4) @if(Auth::user()->grade_id==1) first-bg @elseif(Auth::user()->grade_id==3) third-bg @else second-bg  @endif @else third-bg @endif bg-classrooms">
        @if (Auth::user()->type ===2)
        {{-- Instructor sidebar --}}
        <x-instructor-sidebar />
        @endif


    <!-- container header -->
    <div class="classroom-navbar">
        <div class="uk-container uk-container-large">
            <!-- navbar -->
            @include('layouts.navigation')
        </div>
    </div>

    <!-- body -->
    <div class="body-wrapper uk-margin-large-top">

        <div class="uk-container uk-container-expand rm-expand">
            <div class="classroom-title uk-margin-large-bottom">

                <h1 class="classGrade-title uk-margin-remove-bottom">{{auth()->user()->grade->name??''}} Classrooms</h1>
                <h5 class="uk-margin-remove-top uk-margin-small-left uk-text-muted">Note: You can enroll in one classroom only</h5>
            </div>
            <div class="uk-child-width-expand@s" uk-grid>
                @foreach ( $classes as $class )
                <div class="uk-width-1-4@m uk-width-1-1@s">
                    <div class="uk-card uk-card-default classroom-card">

                        @foreach ( $class->photos as $photo)

                        <div class="uk-card-header uk-card-media-top classroom-header border-radius" style="background-image:url({{ url('uploads/media/'. $photo->path) }});">
                        @endforeach
                            <div class="uk-flex uk-padding-small border-radius">
                                <div class="uk-width-3-4">
                                    <a href="/classroom/{{$class->id}}/updates">
                                        <h3 class="uk-card-title uk-margin-remove-bottom">
                                        {{$class->title}}</h3>
                                    </a>
                                    <p class="uk-text-meta uk-margin-small-bottom uk-margin-remove-top"><time datetime="2016-04-01">{{\Carbon\Carbon::parse($class->created_at)->format('j F, Y')}}</time></p>
                                    <a href="/u/{{$class->user->name_id}}/">
                                        <p class="uk-margin-remove inline-block light-color">
                                            {{ $class->user->first_name }} {{ $class->user->last_name }}
                                        </p>
                                    </a>
                                    <br>
                                    <a href="#">
                                        <p class="uk-margin-remove inline-block light-color">
                                            @foreach ( $grades as $grade )
                                                @if ($grade->id === $class->grade->grade_id)
                                                {{ $grade->name}}
                                                @endif
                                            @endforeach
                                        </p>
                                    </a>
                                    <br>
                                    <a href="#">
                                        <p class="uk-margin-remove inline-block light-color">
                                        {{$class->subject->name}}
                                        </p>
                                    </a>
                                </div>
                                <div class="uk-width-1-4">
                                    {{-- <span class="more-dots uk-border-rounded uk-icon" uk-icon="icon:more-vertical;" tabindex="0" aria-haspopup="true" aria-expanded="false"><svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><circle cx="10" cy="3" r="2"></circle><circle cx="10" cy="10" r="2"></circle><circle cx="10" cy="17" r="2"></circle></svg></span> --}}
                                    <div class="uk-border-rounded uk-dropdown" uk-dropdown="mode: click">
                                        <ul class="uk-nav uk-dropdown-nav">
                                            <li><a href="#">Unenroll</a></li>
                                            <li><a href="#">Report</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="free-element instructor-img">
                                    <img class="uk-border-circle" src="{{ url('uploads/profile_images/'. $class->user->profile_image) }}" width="70" height="70">
                                </div>
                            </div>
                        </div>

                        <div class="uk-card-body">
                            <p>{{ $class->description }}</p>
                        </div>
                        @can('Student')
                        <div class="uk-card-footer "  >
                            @if(in_array($class->id,array_values($myclasses)))
                                <a href="/classroom/{{$class->id}}/updates" class="uk-button uk-borderless uk-button-text">view</a>
                            @else
                                 @if(in_array($class->subject->id,array_keys($myclasses)))
                                     @if(count($class->requests()->where('status',0)->get()) ==0)

                                         <a href="" id="request_change" data-id="{{$class->id}}" class="uk-button uk-borderless uk-button-text">Request to Change </a>
                                      @else
                                         <a href="" id="request_change"  class="uk-button uk-borderless uk-button-text">Request Under Review </a>
                                      @endif
                                    @else
                                    <a href="/classroom/{{$class->id}}/classwork" data-id="{{$class->id}}" id="asked" class="uk-button uk-borderless uk-button-text">Enter Classroom </a>
                                @endif

                            @endif
                        </div>
                        @endcan
                        @can('Instructor')
                        <div class="uk-card-footer">
                            <a href="/classroom/{{$class->id}}/classwork" class="uk-button uk-borderless uk-button-text">view</a>
                        </div>
                        @endcan

                    </div>
                </div>
                @endforeach
            </div>


        </div>
    </div>

</div>

@endsection

@section('footerScripts')
    @section('script')
        <script>
            $(document).ready(function () {

                $(document).on('click', '#asked', function (e) {
                    let href = $(this).attr('href')
                    let id= $(this).attr('data-id')
                    e.preventDefault();
                    let accetp_swal = Swal.fire({
                        title: " Note: You can enroll in one classroom only for the same subject",
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

                            // ajax here
                            $.ajax({
                                url: `enter-class-room/${id}`,
                                type: "get",
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                },
                                data: {id: id},
                                success: function (res) {
                                    if(res.status){
                                        Swal.fire("Done",res.message, "success");
                                        window.location.href = href
                                    }
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

                $(document).on('click', '#request_change', function (e) {
                    e.preventDefault();
                    let id=$(this).attr('data-id')
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
                                url: 'get_classroom_student',
                                type: "get",
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                },
                                data: {id: id},
                                success: function (res) {
                                    if(res.status){
                                        Swal.fire("Done",res.message, "success");
                                    }
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
        </script>
    @endsection
