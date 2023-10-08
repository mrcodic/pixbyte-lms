<!-- This is the modal -->
<div id="modal-generate" class="negative" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <h2 class="uk-modal-title">Generat Dynamic Codes</h2>
        <x-input id="instructor_id" class="uk-width-1-1" type="text" name="instructor_id" :value="Auth::user()->id" hidden/>
        <x-input id="type_desc" class="uk-width-1-1" type="text" name="type_desc" value=""  hidden/>
        <div uk-grid>
            <div class="uk-margin uk-width-1-1">
                <input class="uk-input @error('prefix_code')error-border @enderror" name="prefix_code" type="text" minlength="4" maxlength="4" placeholder="Prefix Four Char goes here....." autofocus>
                <span style="display:none;" class="error_msg prefix_code_error">
                <strong></strong>
                </span>
            </div>
            <div class="uk-margin uk-width-1-2 uk-margin-small-top">
                <label class="uk-form-label" for="absence-rooms"><span>*</span> Coupon Number</label>
                <div class="uk-form-controls">
                    <input id="absence-rooms" class="uk-input @error('num_coupon')error-border @enderror" name="num_coupon" type="number" min="0" placeholder="Coupon Number" value="" autofocus>
                    <span style="display:none;" class="error_msg num_coupon_error" >
                        <strong></strong>
                    </span>
                </div>
            </div>
            <div class="uk-margin uk-width-1-2 uk-margin-small-top">
                <label class="uk-form-label" for="absence-rooms"><span>*</span> Coupon Price</label>
                <div class="uk-form-controls">
                    <input id="absence-rooms" class="uk-input @error('price')error-border @enderror" name="price" type="number" min="0" placeholder="Coupon price" value="" autofocus>
                    <span style="display:none;" class="error_msg price_error">
                        <strong></strong>
                    </span>
                </div>
            </div>
            <div class="uk-width-1-1 uk-margin-small-top">
                <label class="uk-form-label" for="absence-rooms">Restriction</label>
                <div class="uk-form-controls">
                    <input id="num_used" class="uk-input @error('num_used')error-border @enderror" name="num_used" type="number" min="1" placeholder="Restriction here " value="1" autofocus>
                    <span style="display:none;" class="error_msg num_used_error">
                        <strong></strong>
                    </span>
                </div>
            </div>
            <div class="uk-width-1-1 uk-margin-small-top">
                <label class="uk-form-label" for="description"><span>*</span> Choose Type</label>
                <div class="uk-grid-small uk-child-width-auto uk-grid">
                    <label class="dark-font"><input class="uk-radio"  value="2" type="radio" name="type"> Room</label>
                    <label class="dark-font"><input class="uk-radio"  value="3" type="radio" name="type"> Classroom</label>
                    <label class="dark-font"><input class="uk-radio"  value="4" type="radio" name="type"> Grade</label>
                    <label class="dark-font"><input class="uk-radio"  value="5" type="radio" name="type"> Exam</label>
                    <label class="dark-font"><input class="uk-radio"  value="6" type="radio" name="type"> Subscraption</label>

                </div>
                <span style="display:none;" class="error_msg type_error">
                    <strong></strong>
                </span>
            </div>
        </div>


        <div class="uk-margin-small-top uk-width-1-1" id="multiRoom"  style="display: none" >
            <div style="display: grid">

            <label class="uk-form-label" for="description"><span>*</span> Room</label>
            <select multiple class="room_select uk-select @error('room_id')error-border @enderror" id="multi_room_id" name="multi_room_id[]">
                @foreach ( $rooms as $room )
                    <option value=" {{$room->id}} ">{{ $room->title }}</option>
                @endforeach
            </select>
            <span style="display:none;" class="error_msg room_id_error">
                <strong></strong>
            </span>
            </div>

        </div>

        <div class="uk-margin-small-top uk-width-1-1" id="grade"   style="display: none"  >
            <div style="display: grid">
            <label class="uk-form-label" for="description"><span>*</span> Grade</label>
                <select multiple  class="room_select uk-select @error('grade_id')error-border @enderror" id="grade_id" name="grade_id[]">
                    @foreach ( $grades as $grade )
                        <option value=" {{$grade->id}} ">{{ $grade->name }}</option>
                    @endforeach
                </select>
                <span style="display:none;" class="error_msg grade_id_error" >
                                        <strong></strong>
                </span>
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
                        <span style="display:none;" class="error_msg classroom_id_error" >
                            <strong></strong>
    </span>
                 </div>
                 <div style="display:grid;">
                    <label class="uk-form-label" for="description"><span>*</span> From Date</label>
                        <input type="date" class="uk-input @error('date_subscription_from') error-border @enderror" id="date_from" name="date_subscription_from">

                        <span style="display:none;" class="error_msg date_subscription_from_error" >
                            <strong></strong>
    </span>
                 </div>
                 <div style="display:grid;">
                    <label class="uk-form-label" for="description"><span>*</span> From To</label>
                        <input type="date" class="uk-input @error('date_subscription_to') error-border @enderror" id="date_to" name="date_subscription_to">

                        <span style="display:none;" class="error_msg date_subscription_to_error" >
                            <strong></strong>
    </span>
                 </div>
            </div>
        </div>

        <div class="uk-margin-small-top uk-width-1-1" id="classroom"   style="display: none"  >
            <div style="display: grid">

                <label class="uk-form-label" for="description"><span>*</span> Classroom</label>
                <select  class="room_select uk-select @error('classroom_id')error-border @enderror" id="classroom_id" name="classroom_id[]" multiple>
                    <option disabled readonly selected>select</option>
                    @foreach ( $classrooms as $class )
                        <option value=" {{$class->id}} ">{{ $class->title }}</option>
                    @endforeach
                </select>
                <span style="display:none;" class="error_msg classroom_id_error" >
                    <strong></strong>
                </span>
            </div>
        </div>
        <div class="uk-margin-small-top uk-width-1-1" id="quizzes"   style="display: none"  >
            <div style="display: grid">
                <label class="uk-form-label" for="description"><span>*</span> Exam</label>
                <select  class="room_select uk-select @error('quiz_id')error-border @enderror" id="quiz_id" name="quiz_id[]" multiple>
                    @foreach ( $quizzes as $quiz )
                        <option value="{{$quiz->id}}">{{ $quiz->title }}</option>
                    @endforeach
                </select>
                <span style="display:none;" class="error_msg classroom_id_error" >
                    <strong></strong>
                </span>
            </div>
        </div>
        <p class="uk-text-right">
            <button class="uk-button uk-button-default uk-modal-close border-radius" type="button">Cancel</button>
            <button class="uk-button uk-button-primary border-radius" id="save_generat_coupon" type="button">Generate</button>
        </p>
    </div>
</div>

