<!-- This is the modal -->
<div id="modal-create-edit-category" class="negative" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <h2 class="uk-modal-title header">Create Category</h2>
        <div uk-grid>
            <div class="uk-margin- inline-block left uk-width-4-1" style="display: grid">
                <input type="hidden" id="type" value="">
                <input type="hidden" id="category_id" value="">
                <input type="hidden" id="user_id" value="{{auth()->id()}}">
                <label class="uk-form-label" for="description"> Grade <span>*</span></label>
                <select multiple class=" " id="grade_ids" name="gradeIds[]">
                    @foreach ( $grades as $grade )
                        <option value="{{$grade->id}}">{{ $grade->name }}</option>
                    @endforeach
                </select>
{{--                <label class="uk-form-label" for="description"> SubCategory <span>*</span></label>--}}
{{--                <div class="button_add">--}}

{{--                <select multiple class="" id="subcategory_ids" name="subcategory_ids[]">--}}
{{--                    @foreach ( $subcategories as $subcategory )--}}
{{--                        <option value="{{$subcategory->id}}">{{ $subcategory->name }}</option>--}}
{{--                    @endforeach--}}
{{--                </select>--}}
{{--                    <button id="modal_create_subcategory">add</button>--}}
{{--                </div>--}}

            </div>

            <div class="uk-margin-small-right inline-block left uk-width-4-4">
                <label class="uk-form-label" for="description">Name <span>*</span> </label>

                <input class="uk-input" type="text" name="name" id="category_name">
            </div>

            <div class="uk-text-right uk-width-1-1 uk-margin-top">
                <button class="uk-button uk-button-default uk-modal-close border-radius" type="button">Cancel</button>
                <button class="uk-button uk-button-primary border-radius" id="save_create_edit_modal" type="button">Add</button>
            </div>
        </div>

    </div>
    @include('category.modal_add_subcat')
</div>
