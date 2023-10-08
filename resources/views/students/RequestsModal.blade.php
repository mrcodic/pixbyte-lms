<!-- This is the modal -->
<div id="modal-requests" class="negative" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <div class="spinner loading dark-font" style="display:none;" >
            <div class="circle one"></div>
            <div class="circle two"></div>
            <div class="circle three"></div>
        </div>

        <div uk-grid class="test" style="display: none">
            <h2 class="uk-modal-title">Requests Student</h2>
            <div class="uk-margin-small-top uk-width-1-1" id="classroom" >
                <div style="display: grid">

                    <label class="uk-form-label" for="description"><span>*</span> Requests </label>
                    <a id="makeRequest" class="uk-button uk-button-primary border-radius uk-padding-remove-t-b "><span class="add-icon uk-icon uk-margin-small-right" uk-icon="icon:plus; ratio: .7"></span>Make Request</a>
                </div>
                <div style="display: grid">

                    <div id="rquest_div" class=" uk-margin-top">

                    </div>

                </div>
            </div>

        </div>

{{--        <p class="uk-text-right">--}}
{{--            <button class="uk-button uk-button-default uk-modal-close border-radius" type="button">Cancel</button>--}}
{{--            <button class="uk-button uk-button-primary border-radius" id="save_requset" type="button">Save</button>--}}
{{--        </p>--}}
    </div>
</div>


