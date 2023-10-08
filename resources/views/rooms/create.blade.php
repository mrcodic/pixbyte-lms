@extends('layouts.app')
@section('title', 'Add new room')

@section('body')
    <!-- container -->
    <div class="wrapper-page-light f-height">
        {{-- Instructor sidebar --}}
        <x-instructor-sidebar />
        <!-- container header -->
        <div class="header-wrap page-dark">
            <div class="uk-container uk-container-expand rm-expand">
                <!-- navbar -->
                @include('layouts.navigation')
                <!-- breadcrumb -->
                <div class="uk-flex uk-flex-middle uk-margin-small-left" uk-grid>

                    <div class="page-header uk-margin-medium-top uk-margin-medium-bottom breadcrumb uk-width-3-4">
                        <h3 class="uk-margin-remove-bottom title-add">Add New Room</h3>
                        <div class="breadcrumb-items uk-margin-top uk-width-1-2@m uk-width-1-1@s mb-s-20">
                            <div class="line divider"></div>
                            <div class="stages-container uk-flex uk-flex-between" uk-grid>
                                <div class="first-stage completed">
                                    <span><i class="fa-solid fa-circle-check fa-2x"></i></span>
                                    <div>Landing</div>
                                </div>
                                <div class="second-stage completed">
                                    <span><i class="fa-solid fa-circle-check fa-2x"></i></span>
                                    <div>Rooms</div>
                                </div>
                                <div class="third-stage stage">
                                    <span>03</span>
                                    <div>Lessons</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(request()->has('classRoomId'))
                        <div class="uk-width-auto uk-margin-medium-top">
                            <a href="#modal-generate" uk-toggle class="uk-button uk-button-primary border-radius uk-padding-remove-t-b "><span class="add-icon uk-icon uk-margin-small-right" uk-icon="icon:plus; ratio: .7"></span>Add Existing Rooms</a>
                        </div>
                    @endif

                </div>
            </div>
        </div>

        @if(request()->has('classRoomId'))
            @include('rooms.existing-room',["roomsExist"=>\App\Models\Classroom::where('id',request('classRoomId'))->first()->rooms->pluck('id')->toArray()])
        @endif


        <div class="uk-container uk-container-expand rm-expand uk-margin-medium-top uk-margin-left">
            {{-- <x-auth-validation-errors /> --}}
            <div class="add-classroom">
                <form action="{{ route('room.store') }}" method="POST" id="target" enctype="multipart/form-data" class="room-form">
                    @csrf
                    <fieldset class="uk-fieldset add-new" uk-grid>
                        <x-input id="user_id" class="uk-width-1-1" type="text" name="userId" :value="Auth::user()->id" hidden/>
                        <x-input id="class_room_id" class="uk-width-1-1" type="text" name="class_room_id" :value="request()->classRoomId" hidden/>
                        <x-input id="action" class="uk-width-1-1" type="text" name="action"  hidden/>
                        <div class="uk-margin uk-width-1-1">
                            <input class="uk-input title @error('title')error-border @enderror" name="title" type="text" placeholder="Room title goes here....." autofocus>
                            @error('title')
                            <span class="error-msg">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="uk-margin uk-width-1-1">
                            <label class="uk-form-label" for="description"><span>*</span> Description</label>
                            <div class="uk-form-controls">
                                <textarea id="description" class="uk-textarea @error('description')error-border @enderror" rows="5" placeholder="Description" name="description"></textarea>
                                @error('description')
                                <span class="error-msg">
                                    <strong>The description field is required</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        @if(!request()->has('classRoomId'))
                            <div class="uk-margin uk-width-1-4@m uk-width-1-1@s">
                                <label class="uk-form-label" for="class_room_id"> Select Classroom</label>
                                <select multiple class="uk-select @error('class_room_id')error-border @enderror" id="class_room_id" name="class_room_id[]">
                                    @foreach ( $classRooms as $classRoom )
                                        <option value=" {{$classRoom->id}} ">{{ $classRoom->title }}</option>
                                    @endforeach
                                </select>
                                @error('class_room_id')
                                <span class="error-msg">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        @endif

{{--                        <div class="uk-margin uk-width-1-4">--}}
{{--                            <label class="uk-form-label" for="absence-rooms">Room order</label>--}}
{{--                            <div class="uk-form-controls">--}}
{{--                                <input id="absence-rooms" class="uk-input @error('absence_times')error-border @enderror" name="room_order" type="number" placeholder="Rooms number" value="" autofocus>--}}
{{--                                @error('room_order')--}}
{{--                                @enderror--}}
{{--                            </div>--}}
{{--                        </div>--}}

                        <div class="uk-margin uk-width-1-4@m uk-width-1-1@s">
                            <label class="uk-form-label" for="absence-rooms"><span>*</span> Room Price</label>
                            <div class="uk-form-controls">
                                <input id="price-rooms" class="uk-input @error('price')error-border @enderror" name="price" type="number" placeholder="Rooms price" min="0" value="0" autofocus>
                                @error('price')
                                <span class="error-msg">
                                    <strong>{{$message}}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="uk-margin uk-width-1-4@m uk-width-1-1@s lock_after" style="display: none">
                            <label class="uk-form-label" for="lock-after"><span>*</span> Lock After (in days)</label>
                            <div class="uk-form-controls">
                                <input id="lock-after" class="uk-input" min="0" type="number" placeholder="Select Lock After Days" id="unlock_after" name="unlock_after" />
                                @error('unlock_after')
                                <span class="error-msg">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            </div>
                        </div>



                        <div class="uk-margin uk-width-1-4@m uk-width-1-1@s">
                            <label class="uk-form-label" for="pass_quiz"> Must pass quiz to view lessons </label>
                            <div class="switcher">
                                <label class="uk-switch " for="pass_quiz">
                                    <input type="checkbox"  id="pass_quiz"  name="pass_quiz" >
                                    <div class="uk-switch-slider"></div>
                                </label>
                            </div>
                            @error('pass_quiz')
                            <span class="error-msg">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="uk-margin uk-width-1-1">
                            <label class="uk-form-label" for="cover">Room Metrial</label>
                            <ul class="edit-room-material" id="parent" style="display: none">

                            </ul>
                            <div class="js-upload uk-placeholder uk-text-center">
                                <span class="dark-font" uk-icon="icon: cloud-upload"></span>
                                <span class="uk-text-middle dark-font">Drop PDF files here</span>
                                <div uk-form-custom>
                                    <input type="file" multiple>
                                    <span class="uk-link">selecting one</span>
                                </div>
                            </div>
                            <progress id="js-progressbar" class="uk-progress" value="0" max="100" hidden></progress>
                        </div>
                        <div class="uk-width-1-1">
                            <button class="uk-button uk-button-third uk-margin-small-left uk-margin-right" id="save">Save as Draft <i class="fa-solid fa-pen-to-square"></i></button>
                            <button class="uk-button uk-button-secondary" id="continue">Continue <i class="fa-solid fa-arrow-right"></i></button>
                        </div>

                    </fieldset>
                </form>
            </div>
        </div>

    </div>

@endsection
@section('footerScripts')
    @section('script')
        <script>
            $(document).ready(function() {
                $('.uk-select').select2({
                    placeholder:"select Rooms"
                })
            })

            var bar = document.getElementById('js-progressbar');

            UIkit.upload('.js-upload', {

                url: '/uploadMaterial?_token={{csrf_token()}}',
                multiple: true,
                name:'files[]',

                beforeSend: function () {
                    console.log('beforeSend', arguments);
                },
                beforeAll: function () {
                    console.log('beforeAll', arguments);
                },
                load: function () {
                    console.log('load', arguments);
                },
                error: function () {
                    console.log('error', arguments);
                },
                complete: function () {
                    console.log('complete', arguments);
                    $("#parent").show()
                    let response=JSON.parse(arguments[0].response)
                    console.log(response)
                    $("#parent").append(`
                    <li data-id="${response.id}">  <a download="custom-filename.jpg" href="${response.url}"> ${response.file_name} </a>  <span uk-icon="icon: close; ratio: 1"  data-id ="${response.id}" class="deleteImage"> </span></li>
                    `)
                },

                loadStart: function (e) {
                    console.log('loadStart', arguments);

                    bar.removeAttribute('hidden');
                    bar.max = e.total;
                    bar.value = e.loaded;
                },

                progress: function (e) {
                    console.log('progress', arguments);

                    bar.max = e.total;
                    bar.value = e.loaded;
                },

                loadEnd: function (e) {
                    console.log('loadEnd', arguments);

                    bar.max = e.total;
                    bar.value = e.loaded;
                },

                completeAll: function () {
                    console.log('completeAll', arguments);

                    setTimeout(function () {
                        bar.setAttribute('hidden', 'hidden');
                    }, 1000);

                    Swal.fire("Done", "Files Uploaded Successful.", "success");
                }
            });

            $(document).on('click','#continue',function (e){
                e.preventDefault();
                $('#action').val('continue');
                $( "#target" ).submit();
            });
            $(document).on('click','#save',function (e){
                e.preventDefault();
                $('#action').val('save');
                $( "#target" ).submit();
            });


            $(document).on('click','.deleteImage',function (){
                var id = $(this).attr('data-id');
                $.ajax({
                    url: `/deleteImage/${id}`,
                    type: "POST",
                    data: {roomId: 0 },

                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (res) {
                        if (res.status) {
                            Swal.fire("Done", "File delete successful.", "success");
                        }
                        $("#parent li").each(function(){
                            if($(this).attr('data-id') == id){
                                $(this).remove();
                            }
                        });
                    },
                    error: function (res) {
                        Swal.fire("Close!", "Something is wrong, Please try again.", "error");
                    }
                });
            })

            $(document).on('input','#price-rooms',function (){
                if($(this).val()==0){
                    $('.lock_after').fadeOut()
                    $('#lock-after').val(0)
                }else{
                    $('.lock_after').fadeIn()
                }
            })
        </script>
    @endsection
