@extends('layouts.app')
@section('title', 'Add new course')
@section('css')
    <style>
        .success {
            background: #0de37a;
        }
        .question img{
            width: 100px;
            height: 100px;
        }
        #sortable{
            cursor: move;
        }
        .uk-switch {
            position: relative;
            display: inline-block;
            height: 34px;
            width: 60px;
        }

        /* Hide default HTML checkbox */
        .uk-switch input {
            display:none;
        }
        /* Slider */
        .uk-switch-slider {
            background-color: rgba(0,0,0,0.22);
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            border-radius: 500px;
            bottom: 0;
            cursor: pointer;
            transition-property: background-color;
            transition-duration: .2s;
            box-shadow: inset 0 0 2px rgba(0,0,0,0.07);
        }
        /* Switch pointer */
        .uk-switch-slider:before {
            content: '';
            background-color: #fff;
            position: absolute;
            width: 30px;
            height: 30px;
            left: 2px;
            bottom: 2px;
            border-radius: 50%;
            transition-property: transform, box-shadow;
            transition-duration: .2s;
        }
        /* Slider active color */
        input:checked + .uk-switch-slider {
            background-color: #39f !important;
        }
        /* Pointer active animation */
        input:checked + .uk-switch-slider:before {
            transform: translateX(26px);
        }
        .switcher{
            display: flex;
            justify-content: space-between;
            margin-bottom: 18px;
            align-items: baseline;
        }
    </style>
@endsection
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
            <div class="page-header uk-margin-medium-top uk-margin-medium-bottom breadcrumb">
                <h3 class="uk-margin-remove-bottom title-add">Add New Classroom</h3>
                <div class="breadcrumb-items uk-margin-top uk-width-1-3@m uk-width-1-1@s mb-s-20">
                    <div class="line divider"></div>
                    <div class="stages-container uk-flex uk-flex-between" uk-grid>
                        <div class="first-stage completed">
                            <span><i class="fa-solid fa-circle-check fa-2x"></i></span>
                            <div>Landing</div>
                        </div>
                        <div class="second-stage stage">
                            <span>02</span>
                            <div>Rooms</div>
                        </div>
                        <div class="third-stage stage">
                            <span>03</span>
                            <div>Lessons</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="uk-container uk-container-expand rm-expand uk-margin-medium-top uk-margin-left">
        {{-- <x-auth-validation-errors /> --}}
        <div class="add-classroom">
            <form action="{{ route('classrooms.store') }}" method="POST" id="target" enctype="multipart/form-data">
                @csrf
                <x-input id="action" class="uk-width-1-1" type="text" name="action"  hidden/>

                <fieldset class="uk-fieldset add-new" uk-grid>
                    <x-input id="user_id" class="uk-width-1-1" type="text" name="userId" :value="Auth::user()->id" hidden/>
                    <div class="uk-margin uk-width-1-1">
                        <input class="uk-input title @error('title')error-border @enderror" name="title" type="text" placeholder="Classroom title goes here....." autofocus>
                        @error('title')
                            <span class="error-msg">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="uk-margin uk-width-1-4@m uk-width-1-1@s">
                        <label class="uk-form-label" for="grade"><span>*</span> Grade</label>
                        <div class="uk-form-controls">
                            <select class="uk-select @error('grade_id')error-border @enderror" id="grade" name="grade_id">
                                <option selected disabled>Select Grade</option>
                                @foreach ( $grades as $grade )
                                    <option value=" {{$grade->id}} ">{{ $grade->name }}</option>
                                @endforeach
                            </select>
                            @error('grade_id')
                                <span class="error-msg">
                                    <strong>The grade field is required</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="uk-margin uk-width-1-4@m uk-width-1-1@s">
                        <label class="uk-form-label" for="subject"><span>*</span> Subject</label>
                        <div class="uk-form-controls">
                            <select class="uk-select @error('subject_id')error-border @enderror" id="subject" name="subject_id">
                                <option selected disabled>Select Subject</option>
                                @foreach ( $subjects as $subject )
                                    <option value=" {{$subject->id}} ">{{ $subject->name }}</option>
                                @endforeach
                            </select>
                            @error('subject_id')
                                <span class="error-msg">
                                    <strong>The subject field is required</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="uk-margin uk-width-1-4@m uk-width-1-1@s">
                        <label class="uk-form-label" for="room-Schedule"><span>*</span> Room Schedule</label>
                        <div class="uk-form-controls">
                            <select class="uk-select @error('room_scheduel')error-border @enderror" id="room-Schedule" name="room_scheduel">
                                <option selected disabled>Select Schedule</option>
                                @foreach ( $schedules as $schedule )
                                    <option value=" {{$schedule->id}} ">{{ $schedule->name }}</option>
                                @endforeach
                            </select>
                            @error('room_scheduel')
                                <span class="error-msg">
                                    <strong>The room schedule field is required</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="uk-margin uk-width-1-4@m uk-width-1-1@s">
                        <label class="uk-form-label" for="absence-rooms"><span>*</span> Absence rooms</label>
                        <div class="uk-form-controls">
                            <input id="absence-rooms" class="uk-input @error('absence_times')error-border @enderror" name="absence_times" type="number" placeholder="Rooms number" value="" autofocus>
                            @error('absence_times')
                                <span class="error-msg">
                                    <strong>The number of absence rooms field is required</strong>
                                </span>
                            @enderror
                        </div>
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


                    <div class="uk-margin uk-width-1-1">
                            <label class="uk-form-label" for="cover">Classroom cover</label>
                            <ul class="edit-room-material" id="parent" style="display: none">

                            </ul>
                            <div class="js-upload uk-placeholder uk-text-center">
                                <span class="dark-font" uk-icon="icon: cloud-upload"></span>
                                <span class="uk-text-middle dark-font">Drop PDF files here</span>
                                <div uk-form-custom>
                                    <input type="file" id='file_cover'>
                                    <span class="uk-link" id="fileUpload" >selecting one</span>
                                </div>
                            </div>
                            <progress id="js-progressbar" class="uk-progress" value="0" max="100" hidden></progress>
                            @error('cover')
                            <span class="error-msg">
                                <strong>The Classroom cover is required</strong>
                            </span>
                        @enderror
                        </div>
                    <div class="uk-margin uk-width-1-4@m uk-width-1-2@s">
                        <label class="uk-form-label" for="absence-rooms"> Setting missed</label>
                        <div class="switcher">
                            <label class="uk-switch " for="setting_missed">
                                <input type="checkbox"  id="setting_missed"  name="setting_missed" >
                                <div class="uk-switch-slider"></div>
                            </label>
                        </div>
                        @error('setting_missed')
                        <span class="error-msg">
                                    <strong>{{ $message }}</strong>
                                </span>
                        @enderror
                    </div>

                    <div class="uk-width-1">
                        <button class="uk-button uk-button-third uk-margin-small-left uk-margin-right" id="save">save as Draft<i class="fa-solid fa-pen-to-square"></i></button>
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


            var bar = document.getElementById('js-progressbar');

UIkit.upload('.js-upload', {

    url: '/uploadCover?_token={{csrf_token()}}',
    name:'files',

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
        $("#parent").append(`
        <li data-id="${response.id}">  <a download="custom-filename.jpg" href="${response.url}"> ${response.file_name} </a>  <span uk-icon="icon: close; ratio: 1"  data-id ="${response.id}" class="deleteImage"> </span></li>
        `);
        $('#fileUpload').hide()

    },

    loadStart: function (e) {
        bar.removeAttribute('hidden');
        bar.max = e.total;
        bar.value = e.loaded;
    },

    progress: function (e) {
        bar.max = e.total;
        bar.value = e.loaded;
    },

    loadEnd: function (e) {
        bar.max = e.total;
        bar.value = e.loaded;
    },

    completeAll: function () {
        setTimeout(function () {
            bar.setAttribute('hidden', 'hidden');
        }, 1000);

        Swal.fire("Done", "Files Uploaded Successful.", "success");
        $('#fileUpload').hide()
    }
});
$(document).on('click','.deleteImage',function (){
                var id = $(this).attr('data-id');
                $.ajax({
                    url: `/deleteCover/${id}`,
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
                        $('#fileUpload').show()

                    },
                    error: function (res) {
                        Swal.fire("Close!", "Something is wrong, Please try again.", "error");
                    }
                });
            })
        </script>

    @endsection
