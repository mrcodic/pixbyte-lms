<!-- This is the modal -->
<div id="modal-request" class="negative" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <h2 class="uk-modal-title">Request Change Classroom</h2>
        <div uk-grid>
            <div class="uk-margin-small-top uk-width-1-1" id="classroom" >
                <div style="display: grid">
                    <label class="uk-form-label" for="description"><span>*</span> Current Classroom</label>
                    <select  class="room_select uk-select @error('classroom_id')error-border @enderror" id="current_classroom_id" name="current_classroom_id">
                        <option disabled readonly selected>select</option>
                    </select>
                </div>
            </div>
            <div class="uk-margin-small-top uk-width-1-1" id="classroom" >
                <div style="display: grid">
                    <label class="uk-form-label" for="description"><span>*</span> Another Classroom</label>
                    <select  class="room_select uk-select " id="another_classroom_id" name="another_classroom_id">
                        <option disabled readonly selected>select</option>
                    </select>
                </div>
            </div>

        </div>

        <p class="uk-text-right">
            <button class="uk-button uk-button-default uk-modal-close border-radius" type="button">Cancel</button>
            <button class="uk-button uk-button-primary border-radius" id="save_user_requset" type="button">Save</button>
        </p>
    </div>
</div>


