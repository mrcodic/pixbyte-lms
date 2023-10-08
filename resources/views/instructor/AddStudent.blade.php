<div id="modal-student" class="negative" uk-modal>
    <div class="uk-modal-dialog">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <div class="uk-modal-header">
            <h2 class="uk-modal-title">Add Student</h2>
        </div>
        <div class="uk-modal-body">

                <label class="uk-form-label" for="grade"><span>*</span> Student id</label>
                <input type="hidden" name="classroom" id="classroom_hidden">
                <input type="hidden" name="student" id="student">
            <div class=" divParent" style="display: flex;gap: 20px;">
                <input class="uk-input " style="width: 486px" name="title" id="student_id" type="text" placeholder="Student  Id  goes here....." autofocus>
                <a class="uk-search-toggle" style="margin-top: 9px;" id="searchByStudent"  uk-search-icon></a>
            </div>
            <div class="uk-margin uk-width-1-2 student">

            </div>

        </div>
        <div class="uk-modal-footer uk-text-right">
            <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
            <button class="uk-button uk-button-primary" style="display: none" id="save_change" type="button">Save</button>
        </div>
    </div>
                <div class="spinner loading dark-font" style="display:none;">
                    <div class="circle one"></div>
                    <div class="circle two"></div>
                    <div class="circle three"></div>
                </div>

</div>
