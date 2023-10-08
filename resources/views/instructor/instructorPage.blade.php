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
                    <h3 class="uk-margin-remove-bottom title-add">Instructors</h3>
                </div>
                <div class="uk-width-auto">
                    <form class="uk-search uk-search-default">
                        <a  id="save_search" uk-search-icon></a>
                        <input class="uk-search-input" style="background: #dee1e2;color: #000000" type="search" id="search" placeholder="Search" aria-label="Search">
                    </form>
                </div>
                <div class="line divider"></div>
            </div>

        </div>
    </div>

    <!-- body -->
    <div class="instructor-stats uk-margin-medium-top f-height">

        <div class="uk-container uk-container-expand rm-expand">
            <div class="uk-child-width-expand@s" id="posts" uk-grid>
                @foreach( $instructors as $instructor )
                <div class="uk-card uk-card-default uk-width-1-3@m" style="background: #FFFFFF;margin-left:16px">
                    <div class="uk-card-header">
                        <div class="uk-grid-small uk-flex-middle" uk-grid>
                            <div class="uk-width-auto">
                                <img class="uk-border-circle" width="40" height="40" src="{{ (!empty($instructor->profile_image))? url('uploads/profile_images/'. $instructor->profile_image) : url('uploads/no-image/no-profile-picture.png'); }}" alt="Avatar">
                            </div>
                            <div class="uk-width-expand">
                                <h3 class="uk-card-title uk-margin-remove-bottom" style="color: #000000">{{$instructor->name}}</h3>
                                <p class="uk-text-meta uk-margin-remove-top" style="color: #000000"><time datetime="2016-04-01T19:00">{{$instructor->created_at->diffForHumans()}}</time></p>
                            </div>
                        </div>
                    </div>
                    <div class="uk-card-body" style="color: #000000">
                        <p>{{$instructor->bio??'---'}}</p>
                    </div>

                </div>
                @endforeach
            </div>
            <div class="spinner loading dark-font" style="display:none;">
                <div class="circle one"></div>
                <div class="circle two"></div>
                <div class="circle three"></div>
            </div>
            @if($instructorsCount>8)
                <div class="uk-text-center uk-margin-top">
                    <button class="edit uk-margin-small-right" id="loadmore">More Instructor</button>
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
