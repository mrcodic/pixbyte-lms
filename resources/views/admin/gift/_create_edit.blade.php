<!-- Modal to add new gift starts-->
<div class="modal modal-slide-in new-gift-modal fade" id="modal-gift">
    <div class="modal-dialog">
        <form class="add-new-gift modal-content pt-0">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
            <div class="modal-header mb-1">
                <h5 class="modal-title" id="titleModalLabel">Add gift</h5>
                <input type="hidden" id="type">
                <input type="hidden" id="id">
            </div>
            <div class="modal-body flex-grow-1" id="form_data">
                <div class="mb-1">
                    <label class="form-label" for="name">Name</label>
                    <input
                        type="text"
                        class="form-control dt-name"
                        id="name"
                        placeholder=" set name"
                        name="name"
                        value=""
                    />
                    <small class="text-danger title_error"></small>
                </div>

                <div class="mb-1">
                    <label class="form-label" for="price">Price (points)</label>
                    <input
                        type="number"
                        class="form-control dt-price"
                        id="price"
                        placeholder="set price"
                        name="price"
                        min="1"
                        value=""
                    />
                    <small class="text-danger price_error"></small>
                </div>

                <div class="mb-1">
                    <label class="form-label" for="count">Count</label>
                    <input
                        type="number"
                        class="form-control dt-count"
                        id="count"
                        placeholder="set count"
                        name="count"
                        min="1"
                        value=""
                    />
                    <small class="text-danger count_error"></small>
                </div>

                <div class="mb-1">
                    <label class="form-label" for="status">Status</label>
                    <select id="status" class="select2 form-select" name="status">
                        <option value="1">Publish</option>
                        <option value="0">Draft</option>
                    </select>
                    <small class="text-danger status_error"></small>
                </div>

                <div class="mb-2">
                    <label class="form-label" for="image"> Image </label>
                    <input class="form-control" type="file" id="image" name="image">
                    <small class="text-danger image_error"></small>
                </div>

                <button type="submit" class="btn btn-primary me-1 data-submit" id="save_gift">Submit</button>
                <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>
<!-- Modal to add new gift Ends-->
