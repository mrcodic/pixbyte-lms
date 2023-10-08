<div id="modal-generate" class="negative" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <form action="{{ route('lesson.existing') }}" method="POST" class="room-form">
            @csrf

        <div class="uk-margin uk-width-1-1">
            <label class="uk-form-label" for="cover">Lessons List</label>
            <div class=" uk-width-1-1" style="display: grid" >
                    <x-input id="user_id" class="uk-width-1-1" type="text" name="userId" :value="Auth::user()->id" hidden/>
                    <x-input id="class_room_id" class="uk-width-1-1" type="text" name="room_id" :value="request()->room_id" hidden/>

                <select multiple class="uk-select @error('check_lesson')error-border @enderror" id="check_lesson" name="check_lesson[]">
                    @foreach ( $lessons as $lesson )
                        <option value="{{$lesson->id}}" @if(in_array($lesson->id,$lessonsExist)) selected @endif>{{ $lesson->title }}</option>
                    @endforeach
                </select>

            </div>
        </div>
        <div class="uk-modal-footer uk-text-right">
            <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
            <button class="uk-button uk-button-primary" type="submit">Save</button>
        </div>
        </form>

      </div>
    </div>

