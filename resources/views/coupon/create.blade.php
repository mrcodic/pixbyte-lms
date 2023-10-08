@extends('layouts.app')
@section('title', 'Add new course')

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
                    <h3 class="uk-margin-remove-bottom title-add">Add New Coupon</h3>
                </div>

            </div>
        </div>

        <div class="uk-container uk-container-expand rm-expand uk-margin-medium-top uk-margin-left">
            {{-- <x-auth-validation-errors /> --}}
            <div class="add-classroom">
                <form action="{{ route('coupon.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <fieldset class="uk-fieldset add-new" uk-grid>
                        <x-input id="instructor_id" class="uk-width-1-1" type="text" name="instructor_id" :value="Auth::user()->id" hidden/>
                        <div class="uk-margin uk-width-1-1">
                            <input class="uk-input title @error('code')error-border @enderror" name="code" type="text" placeholder="Code goes here....." autofocus>
                            @error('code')
                            <span class="error-msg">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="uk-margin uk-width-1-4">
                            <label class="uk-form-label" for="absence-rooms">Coupon Price</label>
                            <div class="uk-form-controls">
                                <input id="absence-rooms" class="uk-input @error('price')error-border @enderror" name="price" type="number" min="0" placeholder="Coupon price" value="" autofocus>
                                @error('price')
                                <span class="error-msg">
                                    <strong>{{$message}}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="uk-margin uk-width-1-4">
                            <label class="uk-form-label" for="absence-rooms">Restriction</label>
                            <div class="uk-form-controls">
                                <input id="num_used" class="uk-input @error('num_used')error-border @enderror" name="num_used" type="number" min="1" placeholder="Restriction here " value="1" autofocus>
                                @error('num_used')
                                <span class="error-msg">
                                    <strong>{{$message}}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                            <div class="uk-margin uk-width-1-1">
                                <label class="uk-form-label" for="description"><span>*</span> Choose Type</label>
                                    <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">
                                        <label><input class="uk-radio" {{ old('type') == '2' ? 'checked' : '' }}  value="2" type="radio" name="type"> Room</label>
                                        <label><input class="uk-radio" {{ old('type') == '3' ? 'checked' : '' }}  value="3" type="radio" name="type"> Classroom</label>
                                        <label><input class="uk-radio" {{ old('type') == '4' ? 'checked' : '' }}  value="4" type="radio" name="type"> Grade</label>
                                        <label><input class="uk-radio" {{ old('type') == '5' ? 'checked' : '' }}  value="5" type="radio" name="type"> Quiz</label>
                                        <label><input class="uk-radio" {{ old('type') == '6' ? 'checked' : '' }}  value="6" type="radio" name="type"> Subscription</label>

                                    </div>
                                @error('type')
                                <span class="error-msg">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>



                        <div class="uk-margin uk-width-1-1" id="multiRoom" @if( old('type') == '2')  style="display: block" @else  style="display: none" @endif >
                            <div style="display:grid;">
                            <label class="uk-form-label" for="description"><span>*</span> Room</label>
                            <select multiple class="room_select uk-select @error('room_id')error-border @enderror" id="multi_room_id" name="room_id[]">

                            @foreach ( $rooms as $room )
                                    <option value=" {{$room->id}} ">{{ $room->title }}</option>
                                @endforeach
                            </select>
                            @error('room_id')
                            <span class="error-msg">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            </div>
                        </div>


                        <div class="uk-margin uk-width-1-1" id="subscription" @if( old('type') == '6')  style="display: block" @else  style="display: none" @endif >
                            <div >
                                 <div style="display:grid;">
                                    <label class="uk-form-label" for="description"><span>*</span> Classroom</label>
                                        <select multiple class="classroom_select uk-select @error('classroom_id')error-border @enderror" id="classroom_id" name="classroom_id[]">

                                        @foreach ( $classrooms as $class )
                                                <option value=" {{$class->id}} ">{{ $class->title }}</option>
                                            @endforeach
                                        </select>
                                        @error('classroom_id')
                                        <span class="error-msg">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                 </div>
                                 <div style="display:grid;">
                                    <label class="uk-form-label" for="description"><span>*</span> From Date</label>
                                        <input type="date" class="uk-input @error('date_subscription_from') error-border @enderror" id="date_from" name="date_subscription_from">

                                        @error('date_subscription_from')
                                        <span class="error-msg">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                 </div>
                                 <div style="display:grid;">
                                    <label class="uk-form-label" for="description"><span>*</span> From To</label>
                                        <input type="date" class="uk-input @error('date_subscription_to') error-border @enderror" id="date_to" name="date_subscription_to">

                                        @error('date_subscription_to')
                                        <span class="error-msg">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                 </div>
                            </div>
                        </div>


                        <div class="uk-margin uk-width-1-1" id="grade" @if( old('type') == '4')  style="display: block" @else  style="display: none" @endif >
                            <div style="display:grid;">
                            <label class="uk-form-label" for="description"><span>*</span> Grade</label>
                            <select  class="room_select uk-select @error('grade_id')error-border @enderror" id="grade_id" name="grade_id[]" multiple>

                            @foreach ( $grades as $grade )
                                    <option value=" {{$grade->id}} ">{{ $grade->name }}</option>
                                @endforeach
                            </select>
                            @error('grade_id')
                            <span class="error-msg">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        </div>
                        <div class="uk-margin uk-width-1-1" id="classroom" @if( old('type') == '3')  style="display: block" @else  style="display: none" @endif >
                            <div style="display:grid;">
                            <label class="uk-form-label" for="description"><span>*</span> Classroom</label>
                            <select  class="room_select uk-select @error('classroom_id')error-border @enderror" id="classroom_id" name="classroom_id[]" multiple>
                                @foreach ( $classrooms as $class )
                                    <option value=" {{$class->id}} ">{{ $class->title }}</option>
                                @endforeach
                            </select>
                            @error('classroom_id')
                            <span class="error-msg">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        </div>

                        <div class="uk-margin uk-width-1-1" id="quizzes" @if( old('type') == '5')  style="display: block" @else  style="display: none" @endif >
                            <div style="display:grid;">
                                <label class="uk-form-label" for="description"><span>*</span> Exam</label>
                                <select  class="room_select uk-select @error('quiz_id')error-border @enderror" id="quiz_id" name="quiz_id[]" multiple>
                                    @foreach ( $quizzes as $quiz )
                                        <option value="{{$quiz->id}}">{{ $quiz->title }}</option>
                                    @endforeach
                                </select>
                                @error('quiz_id')
                                <span class="error-msg">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="uk-width-1">
                            <button class="uk-button uk-button-third uk-margin-small-left uk-margin-right">Save as draft <i class="fa-solid fa-pen-to-square"></i></button>
                            <button class="uk-button uk-button-secondary">Continue <i class="fa-solid fa-arrow-right"></i></button>
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
            $(document).ready(function (){
                $('.uk-select, #room_id').select2({
                    placeholder:"select......."
                })
                $('.classroom_select').select2({
                    placeholder:"select......."
                })
                $('[name="type"]').on('click',function (){
                    let val= $(this).val()
                    switch (val){
                        case "2":
                            $("#multiRoom").show();
                            $("#quizzes").hide();
                            $("#classroom").hide();
                            $("#grade").hide();
                            $("#subscription").hide();

                            $("#room_id").val('').trigger('change');
                            $("#classroom_id").val('').trigger('change');
                            $("#grade_id").val('').trigger('change');
                            $("#date_to").val('')
                            $("#date_from").val('')
                            $("#quiz_id").val('').trigger('change');

                            break;
                        case "3":
                            $("#classroom").css('display','inline-grid');
                            $("#quizzes").hide();
                            $("#multiRoom").hide();
                            $("#grade").hide();
                            $("#subscription").hide();
                            $("#date_to").val('')
                            $("#date_from").val('')
                            $("#multi_room_id").val('').trigger('change');
                            $("#room_id").val('').trigger('change');
                            $("#grade_id").val('').trigger('change');
                            $("#quiz_id").val('').trigger('change');

                            break;
                        case "4":
                            $("#grade").css('display','inline-grid');
                            $("#quizzes").hide();
                            $("#multiRoom").hide();
                            $("#classroom").hide();
                            $("#subscription").hide();
                            $("#date_to").val('')
                            $("#date_from").val('')
                            $("#multi_room_id").val('').trigger('change');
                            $("#classroom_id").val('').trigger('change');
                            $("#room_id").val('').trigger('change');
                            $("#quiz_id").val('').trigger('change');
                            break;

                        case "5":
                            $("#quizzes").css('display','inline-grid');
                            $("#grade").hide();
                            $("#multiRoom").hide();
                            $("#classroom").hide();
                            $("#subscription").hide();
                            $("#date_to").val('')
                            $("#date_from").val('')
                            $("#multi_room_id").val('').trigger('change');
                            $("#classroom_id").val('').trigger('change');
                            $("#room_id").val('').trigger('change');
                            $("#grade_id").val('').trigger('change');
                            break;
                        case "6":
                            $("#subscription").css('display','inline-grid');
                            $("#quizzes").hide();
                            $("#grade").hide();
                            $("#multiRoom").hide();
                            $("#classroom").hide();

                            $("#multi_room_id").val('').trigger('change');
                            $("#classroom_id").val('').trigger('change');
                            $("#room_id").val('').trigger('change');
                            $("#grade_id").val('').trigger('change');
                            $("#quiz_id").val('').trigger('change');
                            break;


                    }
                })

            })

        </script>
    @endsection
