<!-- This is the modal -->
<div id="modal-changeStatus" class="negative" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <div class="spinner loading dark-font" style="display:none;" >
            <div class="circle one"></div>
            <div class="circle two"></div>
            <div class="circle three"></div>
        </div>

        <div uk-grid class="test" >
            <h2 class="uk-modal-title">Requests Student</h2>
            <input type="hidden" name="student_id" id="student_id" value="">
            <input type="hidden" name="room" id="room" value="{{request()->room_id}}">
            <input type="hidden" name="classroom" id="classroom" value="{{request()->classroom}}">
            <div class="uk-margin-small-top uk-width-1-1" id="classroom" >

                <div style="display: grid">
                    <label class="uk-form-label" for="description"><span>*</span> Select Status </label>

                    <div class="uk-margin-small-right inline-block left uk-width-1-1">
                        <select class="uk-select uk-width-1-1" id="change_status" name="change_status">
                            <option selected disabled>Select Status</option>
                            <option value="1">Present</option>
                            <option value="2">Absent with excuse</option>
                            <option value="3">left/leave early with justification</option>
                            <option value="4">Present but Absent online</option>

                        </select>
                    </div>
                    <br>
                    <div class="uk-margin-small-right inline-block left uk-width-1-1" id="comment_div" style="display: none">
                        <textarea id="comment"class="uk-textarea" name="comment" placeholder="enter excuse"></textarea>
                    </div>

                </div>
            </div>

        </div>

        <p class="uk-text-right">
            <button class="uk-button uk-button-default uk-modal-close border-radius" type="button">Cancel</button>
            <button class="uk-button uk-button-primary border-radius" id="save_change_status" type="button">Save</button>
        </p>
    </div>
</div>


