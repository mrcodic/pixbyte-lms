<!-- Modal to add new category starts-->
<div class="modal modal-slide-in new-category-modal fade" id="modal-category">
    <div class="modal-dialog">
        <form class="add-new-category modal-content pt-0">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
            <div class="modal-header mb-1">
                <h5 class="modal-title" id="titleModalLabel">Add category</h5>
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

                <div class="mb-1" id="div_role"  >
                    <label class="form-label" for="grade_id">Grade</label>
                    <select id="grade_id" class="select2 form-select" name="grade_ids[]" multiple>
                        <option readonly disabled>select...</option>
                        @foreach($grades as $grade)
                            <option value="{{$grade->id}}">{{$grade->name}}</option>
                        @endforeach
                    </select>
                    <small class="text-danger grade_id_error"></small>
                </div>

                <button type="submit" class="btn btn-primary me-1 data-submit" id="save_category">Submit</button>
                <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>
<!-- Modal to add new category Ends-->
