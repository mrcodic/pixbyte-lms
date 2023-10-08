@extends('layouts.app')
@section('title', 'Classrooms')

@section('body')

<!-- container -->
@foreach (Auth::user()->roles as $role)
<div class="wrapper-page-light">
        {{-- @if ($role->id === 2 ) --}}
        @if(Auth::user()->type == 2)
        {{-- Instructor sidebar --}}
        <x-instructor-sidebar />
        @endif
    @endforeach

    <!-- container header -->
    <div class="header-wrap page-dark">
        <div class="uk-container uk-container-expand rm-expand">
            <!-- navbar -->
            @include('layouts.navigation')
            <!-- breadcrumb -->
            <div class="page-header uk-margin-medium-top uk-margin-medium-bottom breadcrumb" uk-grid>
                <div class="uk-width-expand">
                    <h3 class="uk-margin-remove-bottom title-add">My Classrooms</h3>
                </div>
                <div class="uk-width-auto">
                    <form class="uk-search uk-search-default">
                        <a  id="save_search" uk-search-icon></a>
                        <input class="uk-search-input" style="background: #dee1e2;color: #000000" type="search" id="search" placeholder="Search" aria-label="Search">
                    </form>
                </div>
                <div class="uk-width-auto">
                    <a href="{{ route('classrooms.create') }}" class="uk-button uk-button-primary border-radius uk-padding-remove-t-b">
                        <span class="add-icon uk-icon" uk-icon="icon:plus; ratio: .7"></span>Add New Classroom</a>
                </div>
                <div class="line divider"></div>
            </div>

        </div>
    </div>

    <!-- body -->
    <div class="instructor-stats uk-margin-medium-top f-height">

        <div class="uk-container uk-container-expand rm-expand">
            <div class="uk-child-width-expand@s" id="posts" uk-grid>
                @forelse ( $classes as $class )
                <div class="uk-width-1-4@l uk-width-1-3@s">
                    <div class="uk-card uk-card-default classroom-card">
                        @forelse ( $class->photos as $photo)
                        <div class="uk-card-header uk-card-media-top classroom-header border-radius" style="background-image:url({{ url('uploads/media/'. $photo->path) }});">
                            @empty
                            <div class="uk-card-header uk-card-media-top classroom-header border-radius" style="background-image:url({{ url('uploads/no-image/no-image.jpg') }});">
                            @endforelse
                            <div class="uk-flex uk-padding-small border-radius">
                                <div class="uk-width-3-4">
                                    <a href="/classroom/{{$class->id}}/classwork">
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
                                        @if($class->grade)
                                            @foreach ( $grades as $grade )
                                                @if ($grade->id === $class->grade->grade_id)
                                                {{ $grade->name}}
                                                @endif
                                            @endforeach
                                        @else
                                        no grade
                                            @endif
                                        </p>
                                    </a>
                                </div>
                                <div class="uk-width-1-4">
                                    <span class="more-dots uk-border-rounded uk-icon" uk-icon="icon:more-vertical;" tabindex="0" aria-haspopup="true" aria-expanded="false"></span>
                                    <div class="uk-border-rounded uk-dropdown" uk-dropdown="mode: click">
                                        <ul class="uk-nav uk-dropdown-nav">


                                            <li><a class="modal-student"  data-id="{{$class->id}}" href="#modal-student" uk-toggle>Add student</a></li>
                                            <li><a href="/student?classroom={{$class->id}}">Students</a></li>
                                            @if($class->is_draft) <li><a href="#" data-id="{{$class->id}}" id="published">Publish</a></li> @endif
                                            <li><a href="{{ route('classrooms.edit',['classroom'=>$class->id]) }}">Edit</a></li>
                                            <li>
                                                    <a class="uk-button uk-button-text delete_class " data-id="{{$class->id}}" >Delete</a>
                                            </li>
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

                        <div class="uk-card-footer uk-flex uk-flex-between"  @if($class->is_draft) title="is Draft" style="background: #fbe3b2;    border-radius: 10px;" @endif >
                            <a href="/room?class_room_id={{$class->id}}" class="uk-button uk-borderless uk-button-text">Edit rooms</a>
                            <a href="/classroom/{{$class->id}}/classwork" class="uk-button uk-borderless uk-button-text">Enter the class</a>
                        </div>

                    </div>
                </div>
                @empty
                    <div class="nothing-toshow uk-flex uk-flex-center uk-flex-column uk-margin-large-top">
                        <div class="uk-text-center">
                            <img class="nothing-toshow-img" src="{{ asset('img/nothing-toshow.webp') }}" alt="notification-body">
                        </div>
                        <div class="uk-card uk-card-default classroom-card border-success uk-padding-small uk-text-center">
                            <p class="uk-margin-remove">You didn't create any classrooms Yet!</p>
                        </div>
                    </div>
                @endforelse

            </div>
            <div class="spinner loading dark-font" style="display:none;">
                <div class="circle one"></div>
                <div class="circle two"></div>
                <div class="circle three"></div>
            </div>
            @if($classesCount>8)
                <div class="uk-text-center uk-margin-top">
                    <button class="edit uk-margin-small-right" id="loadmore">More classrooms</button>
                </div>
            @endif

        </div>

    </div>
</div>
@include('instructor.AddStudent')

@endsection

@section('footerScripts')
    @section('script')
        <script>

            $(document).ready(function () {
                $(document).on('click', '#published', function (e) {
                    let id= $(this).attr('data-id')
                    e.preventDefault();
                    let accetp_swal = Swal.fire({
                        title: " Are You Sure To Published ?",
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
                                url: `publish-class-room/${id}`,
                                type: "get",
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                },
                                data: {id: id},
                                success: function (res) {
                                    if(res.status){
                                        Swal.fire("Done",res.message, "success");
                                        window.location.reload()
                                    }
                                },
                                error:function (res) {
                                    Swal.fire("Close!", "Something is wrong, Please try again.", "error");
                                }
                            });


                        } else {
                            Swal.fire("Close", "Close Success", "success");
                        }
                    })
                });
               $('.modal-student').on('click',function (){
                   $('#classroom_hidden').val($(this).attr('data-id'));
               })
                $('#searchByStudent').on('click',function (){
                    let classroom_id= $('#classroom_hidden').val()
                    let student_id= $('#student_id').val()
                    $.ajax({
                        url: `get_student_use_id`,
                        type: "get",
                        data:{classroom_id:classroom_id,student_id:student_id},
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        },
                        beforeSend: function() {
                                $('.loading').show();
                        },
                        success: function (res) {
                            if(res.status){
                                $('.loading').hide();
                                $('.student').empty();
                                $('#save_change').fadeIn();

                                let image;
                                if(res.data.profile_image==null){
                                    if(res.data.grade==1){

                                        image='/uploads/no-image/first-year.png'
                                    }else if(res.data.grade==2){
                                        image='/uploads/no-image/second-year.png'

                                    }else{
                                        image='/uploads/no-image/third-year.png'

                                    }
                                }else{
                                    image=`/uploads/profile_images/${res.data.profile_image}`
                                }
                         $('.student').append(`
                          <hr/>
                          <img class="avatar-resize uk-border-circle" src="${image}" alt="avatar">
                            <h4 class="dark-font uk-margin-remove-bottom uk-margin-small-top inline-block uk-margin-small-left uk-text-bold"><a href="#" class=""><span class="dark-font uk-text-bold"> ${res.data.name}</span></a>
                            </h4>
                         `)
                                $('#student').val(res.data.id)
                            }else{
                                Swal.fire("warning",'Student Not Founded', "warning");
                                $('.loading').hide();

                            }
                        },
                        error:function (res) {
                            $('.loading').hide();
                            Swal.fire("Close!", "Something is wrong, Please try again.", "error");
                        }
                    });
                })

                $(document).on('click','#save_change',function (e){
                    let id= $('#student').val()
                    let classroom_id= $('#classroom_hidden').val()
                    e.preventDefault();
                    let accetp_swal = Swal.fire({
                        title: " Are You Sure To Add This Student ?",
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
                                url: `add-student-in-classroom`,
                                type: "get",
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                },
                                data: {classroom_id: classroom_id,id:id},
                                success: function (res) {
                                    if(res.status){
                                        Swal.fire("Done",res.message, "success");
                                        window.location.reload()
                                    }
                                },
                                error:function (res) {
                                    Swal.fire("Close!", "Something is wrong, Please try again.", "error");
                                }
                            });


                        } else {
                            Swal.fire("Close", "Close Success", "success");
                        }
                    })
                })

                $(document).on('click','.delete_class',function (e){
                    e.preventDefault();
                    let id = $(this).attr('data-id')
                    let accetp_swal = Swal.fire({
                        title: " Are You Sure To Delete ?",
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
                                url: `classrooms/${id}`,
                                type: "delete",
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                },
                                success: function (res) {
                                    if(res.status){
                                        Swal.fire("Done",res.message, "success");
                                        window.location.reload()
                                    }
                                },
                                error:function (res) {
                                    Swal.fire("Close!", "Something is wrong, Please try again.", "error");
                                }
                            });
                        } else {
                            Swal.fire("Close", "Close Success", "success");
                        }
                    })
                })
                var paginate=1

                $(document).on('click','#loadmore',function (e) {

                    e.preventDefault();
                    paginate++
                    loadMoreData(paginate)
                })
                $(document).on('keyup','#search',function (e) {
                    let search=$('#search').val();
                    $.ajax({
                        url: `?search=${search}`,
                        type: 'get',
                        datatype: 'html',
                        beforeSend: function() {
                            $('.loading').show();
                        }
                    })
                        .done(function(data) {
                            if(data.length == 0 || data.length==1) {
                                $('#loadmore').hide();
                                $('.loading').html('No classrooms.');
                                return;
                            }
                            else{
                                $('#posts').empty();
                                $('#posts').append(data);
                                $('#loadmore').hide();

                                $('.loading').hide();
                            }
                        })
                        .fail(function(jqXHR, ajaxOptions, thrownError) {
                            alert('Something went wrong.');
                        });
                })

                function loadMoreData(paginate) {
                    $.ajax({
                        url: `?page=${paginate}`,
                        type: 'get',
                        datatype: 'html',
                        beforeSend: function() {
                                $('.loading').show();
                        }
                    })
                        .done(function(data) {
                            if(data.length == 0 || data.length==1) {
                                $('#loadmore').hide();
                                $('.loading').html('No more classrooms.');
                                return;
                            }
                            else{
                                    $('#posts').append(data);
                                    $('.loading').hide();
                            }
                        })
                        .fail(function(jqXHR, ajaxOptions, thrownError) {
                            alert('Something went wrong.');
                        });
                }

            });
        </script>
    @endsection
