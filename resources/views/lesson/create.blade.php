@extends('layouts.app')
@section('title', 'Add new lesson')

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
                        <h3 class="uk-margin-remove-bottom title-add">Add New Lesson</h3>
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
                                    <span><i class="fa-solid fa-circle-check fa-2x"></i></span>
                                    <div>Lessons</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(request()->has('room_id'))
                        <div class="uk-width-auto uk-margin-medium-top">
                            <a href="#modal-generate" uk-toggle class="uk-button uk-button-primary border-radius uk-padding-remove-t-b "><span class="add-icon uk-icon uk-margin-small-right" uk-icon="icon:plus; ratio: .7"></span>Add Existing Lessons</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @if(request()->has('room_id'))
                @include('lesson.existing-lessons',["lessonsExist"=>\App\Models\Room::where('id',request('room_id'))->first()->lessons->pluck('id')->toArray()])
        @endif

        <div class="uk-container uk-container-expand rm-expand uk-margin-medium-top uk-margin-left">
            {{-- <x-auth-validation-errors /> --}}
            <div class="add-classroom">
                <form action="{{ route('lessons.store') }}" id="target" method="POST" enctype="multipart/form-data" class="room-form">
                    @csrf
                    <fieldset class="uk-fieldset add-new" uk-grid>
                        <x-input id="user_id" class="uk-width-1-1" type="text" name="user_id" :value="Auth::user()->id" hidden/>
                        <x-input id="room_id" class="uk-width-1-1" type="text" name="room_id" :value="request('room_id')" hidden/>
                        <x-input id="action" class="uk-width-1-1" type="text" name="action"  hidden/>

                        <div class="uk-margin uk-width-1-1">
                            <input class="uk-input title @error('title')error-border @enderror" name="title" type="text" placeholder="Lesson title goes here....." autofocus>
                            <span class="error_msg title_error">
                                <strong></strong>
                            </span>

                        </div>

                        <div class="uk-margin uk-width-3-4@m uk-width-1-1@s">
                            <label class="uk-form-label" for="description"><span>*</span> Description</label>
                            <div class="uk-form-controls">
                                <textarea id="description" class="uk-textarea @error('description')error-border @enderror" rows="5" placeholder="Description" name="description"></textarea>
                                <span class="error_msg description_error">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>

                        @if(!request()->has('room_id'))
                            <div class="uk-margin uk-width-1-4@m uk-width-1-1@s" id="room_id">
                                <label class="uk-form-label" for="description">Select Room</label>
                                <select multiple class="room_select uk-select @error('room_id')error-border @enderror" id="room_id" name="room_id[]">
                                    @foreach ( $rooms as $room )
                                        <option value=" {{$room->id}} ">{{ $room->title }}</option>
                                    @endforeach
                                </select>
                                <span class="error_msg room_id_error">
                                    <strong></strong>
                                </span>

                            </div>
                        @endif


                        <div class="uk-margin uk-width-1-1">
                            <label class="uk-form-label" for="cover"><span>*</span> Lesson Video</label>
                            <ul class="lesson-tabs" uk-tab>
                                <li><a href="#">Add vimeo URL</a></li>
                                <li><a href="#">Upload Video <span class="uk-label uk-label-danger">Coming Soon</span></a></li>
                            </ul>

                            <ul class="uk-switcher uk-margin">
                                <li>
                                    <div uk-grid>
                                        <div class="uk-form-controls uk-width-3-4@m uk-width-1-1@s">
                                            <label class="uk-form-label" for="cover"><span>*</span> Video URL</label>
                                            <input id="url" class="uk-input" placeholder="Please Enter Url" type="text" name="url_iframe" value="">
                                        </div>

                                        <div class="uk-form-controls uk-width-expand">
                                            <label class="uk-form-label block" for="cover"><span>*</span> Duration Video</label>

                                            <input id="duration" class="uk-width-1-5 uk-input inline-block duration" placeholder="H" min="0" value="0" type="number" name="duration[]" ><span class="duration-text uk-margin-small-right">Hours</span>

                                            <input id="duration" class="uk-width-1-5 uk-input inline-block duration" placeholder="M" min="1"  value="0" type="number" name="duration[]" ><span class="duration-text">Minutes</span>

                                        </div>
                                    </div>
                                </li>
                                <li class="soon-container">
                                    <ul class="edit-room-material" id="parent" style="display: none">

                                    </ul>
                                    <div class="soon">
                                    </div>
                                    <div class="js-upload uk-placeholder uk-text-center">
                                        <span class="dark-font" uk-icon="icon: cloud-upload"></span>
                                        <span class="uk-text-middle dark-font">Drop mp4 file here</span>
                                        <div uk-form-custom>
                                            <input type="file" multiple>
                                            <span class="uk-link">selecting one</span>
                                        </div>
                                    </div>
                                    <progress id="js-progressbar" class="uk-progress" value="0" max="100" hidden></progress>
                                </li>
                            </ul>
                        </div>
                            <div class="uk-width-1">
                                <button class="uk-button uk-button-third uk-margin-small-left uk-margin-right" id="save">Save as Draft <i class="fa-solid fa-pen-to-square"></i></button>
                                <button class="uk-button uk-button-secondary" id="submit">Publish</button>
                            </div>

                    </fieldset>
                </form>
            </div>
        </div>

    </div>

@endsection
@section('footerScripts')

    @section('script')
{{--        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js" integrity="sha384-qlmct0AOBiA2VPZkMY3+2WqkHtIQ9lSdAsAn5RUJD/3vA5MKDgSGcdmIv4ycVxyn" crossorigin="anonymous"></script>--}}

        <script>



                $('.uk-select').select2({
                    placeholder:"select Lessons"
                })

                    $('#save').click(function (e){

                        $('#action').val('save_draft');

                        $("#target").submit();
                    });
                    $(document).on('click','#submit',function (e){

                        $('#action').val('save');
                        $("#target").submit();
                    });

        </script>
    @endsection
