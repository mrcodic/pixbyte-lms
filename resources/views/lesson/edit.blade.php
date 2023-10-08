@extends('layouts.app')
@section('title', 'Edit Lesson')

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
                    <h3 class="uk-margin-remove-bottom title-add">Edit Lesson</h3>
                    <div class="breadcrumb-items uk-margin-top uk-width-1-3@m uk-width-1-1@s mb-s-20">
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

            </div>
        </div>

        <div class="uk-container uk-container-expand rm-expand uk-margin-medium-top uk-margin-left">
            {{-- <x-auth-validation-errors /> --}}
            <div class="add-classroom">
                <form action="{{ route('lessons.update',$lesson->id) }}" method="POST" enctype="multipart/form-data" class="room-form">
                    @method('PATCH')
                    @csrf
                    <fieldset class="uk-fieldset add-new" uk-grid>
                        <x-input id="user_id" class="uk-width-1-1" type="text" name="user_id" :value="Auth::user()->id" hidden/>
                        <div class="uk-margin uk-width-1-1">
                            <input class="uk-input title @error('title')error-border @enderror" name="title" value="{{$lesson->title}}" type="text" placeholder="Lesson title goes here....." autofocus>
                            @error('title')
                            <span class="error-msg">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="uk-margin uk-width-3-4@m uk-width-1-1@s">
                            <label class="uk-form-label" for="description"><span>*</span> Description</label>
                            <div class="uk-form-controls">
                                <textarea id="description" class="uk-textarea @error('description')error-border @enderror" rows="5" placeholder="Description" name="description">{{$lesson->description}}</textarea>
                                @error('description')
                                <span class="error-msg">
                                    <strong>{{$message}}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                            <div class="uk-margin uk-width-1-4@m uk-width-1-1@s" id="room" >
                                <label class="uk-form-label" for="description"> Select Room</label>
                                <select multiple class="uk-select @error('room_id')error-border @enderror" id="room_select" name="room_id[]">
                                @foreach ($roooms as $room)
                                            <option value="{{$room->id}}" @foreach($lesson->rooms as $lessons) @if($room->id == $lessons->id) selected @endif  @endforeach>{{ $room->title }}</option>
                                @endforeach
                                </select>
                                @error('room_id')
                                <span class="error-msg">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>


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
                                            <input id="url" class="uk-input" placeholder="Please Enter Url" type="text" name="url_iframe" value="{{$lesson->url_iframe}}">
                                        </div>
                                        <div class="uk-form-controls uk-width-expand">
                                            <label class="uk-form-label block" for="cover"><span>*</span> Duration Video</label>
                                            @php
                                               $duration=$lesson->duration;
                                               $hour=strtok($duration, 'h');
                                               $min=strtok(ltrim(strstr($duration, 'h'), 'h'),'m');
                                            @endphp
                                            <input id="duration" class="uk-width-1-5 uk-input inline-block duration" placeholder="H" min="0" type="number" name="duration[]" value="{{$hour}}" ><span class="duration-text uk-margin-small-right">Hours</span>

                                            <input id="duration" class="uk-width-1-5 uk-input inline-block duration" placeholder="M" min="1" type="number" name="duration[]" value="{{$min}}" ><span class="duration-text">Minutes</span>

                                            {{-- <input id="duration" class="uk-width-1-5 uk-input inline-block duration" placeholder="M" type="number" name="duration" ><span class="duration-text">Minutes</span> --}}
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
{{--                            <button class="uk-button uk-button-third uk-margin-small-left uk-margin-right">Save as draft <i class="fa-solid fa-pen-to-square"></i></button>--}}
                            <button class="uk-button uk-button-secondary">Save <i class="fa-solid fa-arrow-right"></i></button>
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

            $('#room_select').select2({
                closeOnSelect:false,
            });
        </script>
    @endsection
