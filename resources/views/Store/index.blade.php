@extends('layouts.app')
@section('title', 'Store & Gifts')


@section('body')

    <div class="wrapper-page-light f-height">
        {{-- Instructor sidebar --}}
        @can('Instructor')
            {{-- Instructor sidebar --}}
            <x-instructor-sidebar />
        @endcan
        <!-- container header -->
        <div class="header-wrap page-dark">
            <div class="uk-container uk-container-expand rm-expand">
                <!-- navbar -->
                @include('layouts.navigation')
                <div class="page-header uk-margin-medium-top uk-margin-medium-bottom breadcrumb" uk-grid>
                    <div class="uk-width-expand">
                        <h3 class="uk-margin-remove-bottom title-add">Store & Gifts</h3>
                    </div>
                    <div class="line divider"></div>
                </div>
            </div>
        </div>
        <div class="uk-container uk-container-medium uk-margin-medium-top store">
            <input hidden value="{{auth()->id()}}" id="student_id">
            <div class="uk-child-width-expand@s posts" id="posts" uk-grid>
                 @forelse($gifts as $gift)
                   <div class="uk-width-1-4@m uk-width-1-1@s">
                    <div class="uk-card uk-card-default">
                        <div class="uk-card-header uk-card-media-top" @if($gift->image) style="background-image: url({{ url('uploads/gifts/'. $gift->image) }}); !important;" @endif>
                            <h3 class="total-points">{{$gift->price}} PT</h3>
                            <div class="gift-label">
                                <h3 class="uk-margin-remove">{{$gift->name}}</h3>
                            </div>
                        </div>
                        <div class="uk-card-body"  uk-grid>
                            <div uk-tooltip="title: Redeem tour points" class="acquire uk-width-2-3@s uk-text-center">
                                <a href="#"  class="add_store" id="add_store-{{$gift->id}}" data-id="{{$gift->id}}" >@if(count($gift->users)>0) ACQUIRED @else  ACQUIRE @endif</a>
                            </div>
                            <div uk-tooltip="title: Add to your favourites" class="love uk-width-1-3@s" id="index-{{$gift->id}}">
                               @if(count($gift->favorites)>0 && $gift->favorites()->where('user_id',auth()->id())->exists())
                                <a href="#"  class="add_redemptions" id="add_redemptions-{{$gift->id}}" data-type="0" data-id="{{$gift->id}}">-</a>
                               @else
                                    <a href="#" id="add_redemptions-{{$gift->id}}" class="add_redemptions" data-type="1" data-id="{{$gift->id}}">+</a>
                              @endif
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                    <div class="nothing-toshow uk-flex uk-flex-center uk-flex-column uk-margin-large-top">
                        <div class="uk-text-center">
                            <img class="nothing-toshow-img" src="{{ asset('img/nothing-toshow.webp') }}" alt="notification-body">
                        </div>
                        <div class="uk-card uk-card-default classroom-card border-success uk-padding-small uk-text-center">
                            <p class="uk-margin-remove">No Gifts in the Store Yet!</p>
                        </div>
                    </div>
                @endforelse

            </div>
            <div class="spinner loading dark-font" style="display:none;">
                <div class="circle one"></div>
                <div class="circle two"></div>
                <div class="circle three"></div>
            </div>
            @if($giftCount>8)
                <div class="uk-text-center uk-margin-top">
                    <button class="edit uk-margin-small-right" id="loadmore">More Gifts</button>
                </div>
            @endif

        </div>
    </div>
@endsection
@section('script')
    <script>
        $( document ).ready(function() {
            var paginate=1
            $(document).on('click','#loadmore',function (e) {

                e.preventDefault();
                paginate++
                loadMoreData(paginate)
            })
            $('.add_store').on('click',function (e){
                e.preventDefault();
                let gift_id=$(this).attr('data-id');
                let accetp_swal = Swal.fire({
                    title: " Are you sure make this action ?",
                    text: "",
                    icon:"warning",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#DD6B55',
                    confirmButtonText: 'Yes',
                    cancelButtonText: "No",
                    closeOnConfirm: false,
                    closeOnCancel: false
                }).then(function (result){
                    if (result.isConfirmed){
                        let student_id=$('#student_id').val();
                        $.ajax({
                            url: '/add-store',
                            type: "POST",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            },
                            data: {student_id: student_id,gift_id:gift_id},
                            success: function (res) {
                                if(res.status){
                                        Swal.fire("Done", "Record stored.", "success");
                                        $(`#add_store-${res.data.id}`).text('ACQUIRED')
                                        $(`#add_store-${res.data.id}`).attr('disabled',true)
                                }else{
                                    Swal.fire("warning", res.message, "warning");

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


            });

            $('.add_redemptions').on('click',function (e){
                e.preventDefault();
                let gift_id=$(this).attr('data-id');
                let type=$(this).attr('data-type');
                let accetp_swal = Swal.fire({
                    title: " Are you sure make this action ?",
                    text: "",
                    icon:"warning",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#DD6B55',
                    confirmButtonText: 'Yes',
                    cancelButtonText: "No",
                    closeOnConfirm: false,
                    closeOnCancel: false
                }).then(function (result){
                    if (result.isConfirmed){
                        let student_id=$('#student_id').val();
                        $.ajax({
                            url: '/add-redemptions',
                            type: "POST",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            },
                            data: {student_id: student_id,gift_id:gift_id,type:type},
                            success: function (res) {
                                if(res.status){
                                    if(res.data.type=='0'){
                                        Swal.fire("Done", "Record UnFavorite.", "success");
                                        $(`#add_redemptions-${res.data.id}`).text('+')
                                        $(`#add_redemptions-${res.data.id}`).attr('data-type','1')
                                    }else{
                                        Swal.fire("Done", "Record favorite.", "success");
                                        $(`#add_redemptions-${res.data.id}`).text('-')
                                        $(`#add_redemptions-${res.data.id}`).attr('data-type','0')


                                    }

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


            });
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
