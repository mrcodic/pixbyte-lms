<!-- This is the modal -->
<div id="modal-request" class="negative" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <h2 class="uk-modal-title">Request Change Classroom</h2>
        <x-input id="instructor_id" class="uk-width-1-1" type="text" name="instructor_id" :value="Auth::user()->id" hidden/>
        <div uk-grid>
            <div class="uk-margin-small-top uk-width-1-1" id="classroom" >
                <div style="display: grid">
                    <label class="uk-form-label" for="description"><span>*</span> Classroom</label>
                    <select  class="room_select uk-select @error('classroom_id')error-border @enderror" id="classroom_id_reguest" name="classroom_id">
                        <option disabled readonly selected>select</option>
                        @foreach ( $classessRequset as $class )
                            <option value="{{$class->id}}">{{ $class->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

        </div>

        <p class="uk-text-right">
            <button class="uk-button uk-button-default uk-modal-close border-radius" type="button">Cancel</button>
            <button class="uk-button uk-button-primary border-radius" id="save_requset" type="button">Save</button>
        </p>
    </div>
</div>


