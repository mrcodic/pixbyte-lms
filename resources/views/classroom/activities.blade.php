<div id="post">
@forelse($activities as $activity)

    <div class="uk-card uk-card-default classroom-card up-hover uk-margin-bottom">
        <div class="uk-padding-small border-radius">
            <div class="uk-flex border-radius">
                <div class="uk-width-expand">
                    <a href="#" class="rmv-underline">
                        <div class="up-room">
                            <img class="icon-activity" src="{{ asset('img/xp-lesson.svg') }}" alt="room-icon">
                            <h5 class="rm-t-s dark-font uk-margin-remove-bottom uk-margin-small-top inline-block uk-margin-small-left uk-text-bold contect-activity uk-width-5-6">
                                {{$activity['properties']['attributes']['user.name']}}
                                {{$activity->description}}
                                <a href="{{route('room.show',$activity['properties']['attributes']['id'])}}">
                                    <span class="unit-title uk-text-bold">
                                        {{$activity['properties']['attributes']['title']}}
                                    </span>
                                </a>
                            </h5>

                            <p class="date-padding light-dark uk-margin-small-bottom uk-margin-remove-top uk-margin-medium-left">
                                <time datetime="2016-04-01T19:00"> {{$activity->created_at}}</time>
                            </p>
                        </div>
                    </a>
                </div>

                @if(@$activity->description==='added room')
                        <div class="uk-width-auto">
                            <span class="more-dots light uk-border-circle" uk-icon="icon:more-vertical;"></span>
                            <div class="uk-border-rounded" uk-dropdown="mode: click">
                                <ul class="uk-nav uk-dropdown-nav">
                                    <li><a href="{{route('room.show',$activity['properties']['attributes']['id'])}}">Enter room</a></li>
                                    <li><a href="#" id="copyLink" data-link="{{route('room.show',$activity['properties']['attributes']['id'])}}" >Copy</a></li>
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
                        <img class="icon-activity" src="{{ asset('img/xp-lesson.svg') }}" alt="room-icon">
                        <h5 class="dark-font uk-margin-remove-bottom uk-margin-small-top inline-block uk-margin-small-left uk-text-bold"> <a href="#" class=""><span class="unit-title uk-text-bold dark-font"> You have No activities yet!</span></a>
                        </h5>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endforelse

    <div class="spinner loading dark-font" style="display:none;">
        <div class="circle one"></div>
        <div class="circle two"></div>
        <div class="circle three"></div>
    </div>

</div>
@section('script')
    <script>
        $(document).on('click', '#save_requset', function (e) {
            e.preventDefault();
            let id = $('#classroom_id_reguest').val();
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
                            if (res.status) {
                                Swal.fire("Done", res.message, "success");
                            }
                            UIkit.modal('#modal-request').hide();

                        },
                        error: function (res) {
                            Swal.fire("Close!", "Something is wrong, Please try again.", "error");
                        }
                    });
                } else {
                    Swal.fire("Close", "Close Success", "error");
                }
            })


        })

        var paginate = 2;
            loadMoreData(paginate);
            $(window).scroll(function() {
                if($(window).scrollTop() + $(window).height() >= $(document).height()) {
                    paginate++;
                    loadMoreData(paginate);
                }
            });
            $(document).on('click','#classwork',function (){
                window.location.href=$(this).attr('href')
            })
            $(document).on('click','#updates',function (){
                window.location.href=$(this).attr('href')
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
                        if(data.length == 0) {
                            $('#loadmore').hide();
                            $('.loading').html('No more Updates.');
                            return;
                        } else {
                            $('.loading').hide();
                            $('#post').prepend(data);
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

    </script>
@endsection
