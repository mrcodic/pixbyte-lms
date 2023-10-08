<!-- This is the modal -->
<div id="modal-add-subcategory" class="negative" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <h2 class="uk-modal-title header">Create Category</h2>
        <div uk-grid>
            <div class="uk-margin- inline-block left uk-width-4-1" style="display: grid">
                <input type="hidden" id="user_id" value="{{auth()->id()}}">
                <input type="hidden" id="category_id" value="">
            </div>

            <div class="uk-margin-small-right inline-block left uk-width-4-4">
                <label class="uk-form-label" for="description"><span>*</span> Name</label>

                <input class="uk-input" type="text" name="name" id="subcategory_name_add">
            </div>

            <div class="uk-text-right uk-width-1-1 uk-margin-top">
                <button class="uk-button uk-button-default uk-modal-close border-radius" type="button">Cancel</button>
                <button class="uk-button uk-button-primary border-radius" id="save_add_subcat_modal" type="button">Add</button>
            </div>
        </div>

    </div>
</div>
