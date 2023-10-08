<!-- Modal to add new subcategory starts-->
<div class="modal modal-slide-in new-subcategory-modal fade" id="modal-subcategory">
    <div class="modal-dialog">
        <form class="add-new-subcategory modal-content pt-0">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
            <div class="modal-header mb-1">
                <h5 class="modal-title" id="titleModalLabel">Add subcategory</h5>
                <input type="hidden" id="type">
                <input type="hidden" id="id">
            </div>
            <div class="modal-body flex-grow-1" id="form_data">
                <div class="mb-1">
                    <label class="form-label" for="title">Title</label>
                    <input
                        type="text"
                        class="form-control dt-title"
                        id="title"
                        placeholder=""
                        name="title"
                        value=""
                    />
                    <small class="text-danger title_error"></small>
                </div>

                <div class="mb-1" id="div_role" >
                    <label class="form-label" for="category_id">Category</label>
                    <select id="category_id" class="select2 form-select" name="category_id" >
                        <option readonly disabled>select...</option>
                        @foreach($categories as $category)
                            <option value="{{$category->id}}">{{$category->name}}</option>
                        @endforeach
                    </select>
                    <small class="text-danger grade_id_error"></small>
                </div>

                <button type="submit" class="btn btn-primary me-1 data-submit" id="save_subcategory">Submit</button>
                <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>
<!-- Modal to add new subcategory Ends-->
