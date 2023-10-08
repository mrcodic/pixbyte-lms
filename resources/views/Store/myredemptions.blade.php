@extends('layouts.app')
@section('title', ' My Fav Store & Gifts')


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
                        <h3 class="uk-margin-remove-bottom title-add">My redemptions Store & Gifts</h3>
                    </div>
                    <div class="line divider"></div>
                </div>
            </div>
        </div>
        <div class="uk-container uk-container-medium uk-margin-medium-top store">
            <input hidden value="{{auth()->id()}}" id="student_id">
            <div class="uk-child-width-expand@s" uk-grid>
                @forelse($gifts as $gift)
                <div class="uk-width-1-4@m uk-width-1-1@s" id="mystore_{{$gift->id}}">
                    <div class="uk-card uk-card-default">
                        <div class="uk-card-header uk-card-media-top" @if($gift->image) style="background-image: url({{ url('uploads/gifts/'. $gift->image) }}); !important;" @endif>
                            <h3 class="total-points">{{$gift->price}} PT</h3>
                            <div class="gift-label">
                                <h3 class="uk-margin-remove">{{$gift->name}}</h3>
                            </div>
                        </div>
                        <div class="uk-card-body"  uk-grid>
                            <div class="acquire uk-width-2-3@s uk-text-center">
                                <a href="#">ACQUIRED</a>
                            </div>
{{--                            <div class="love uk-width-1-3@s" id="index-{{$gift->id}}">--}}
{{--                                <a href="#"  class="add_redemptions" id="add_redemptions-{{$gift->id}}" data-type="0" data-id="{{$gift->id}}">+</a>--}}

{{--                            </div>--}}
                        </div>
                    </div>
                </div>
                    @empty
                        <div class="nothing-toshow uk-flex uk-flex-center uk-flex-column uk-margin-large-top">
                            <div class="uk-text-center">
                                <img class="nothing-toshow-img" src="{{ asset('img/nothing-toshow.webp') }}" alt="notification-body">
                            </div>
                            <div class="uk-card uk-card-default classroom-card border-success uk-padding-small uk-text-center">
                                <p class="uk-margin-remove">You didn't create any Gifts Yet!</p>
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
