<!-- Modal to add new lesson starts-->
<div class="modal modal-slide-in new-lesson-modal fade" id="modal-lesson">
    <div class="modal-dialog">
        <form class="add-new-lesson modal-content pt-0" id="form">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
            <div class="modal-header mb-1">
                <h5 class="modal-title" id="exampleModalLabel">Add lesson</h5>
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
                    />
                    <small class="text-danger title_error"></small>
                </div>
                @if (!$room)
                    <div class="row">
                        <div class="mb-1 col-6"  >
                            <label class="form-label" for="user_id">select instractor</label>
                            <select id="user_id" class="select2 form-select" name="user_id">
                                <option selected>select...</option>
                                @foreach($instractors as $instractor)
                                    <option value="{{$instractor->id}}" >{{$instractor->name}}</option>
                                @endforeach
                            </select>
                            <small class="text-danger user_id_error"></small>
                        </div>
                        <div class="mb-1 col-6"  >
                            <label class="form-label" for="room_ids">select room</label>
                            <select id="room_ids" class="select2 form-select" multiple="multiple" name="room_id[]"></select>
                            <small class="text-danger room_id_error"></small>
                        </div>
                    </div>
                @endif


                <div class="row">
                    <div class="mb-1 col-6"  >
                        <label class="form-label" for="url_iframe">Video URL</label>
                        <input type="text" id="url_iframe" class="form-control " name="url_iframe"></select>
                        <small class="text-danger url_iframe_error"></small>
                    </div>
                    <div class="mb-1 col-6 row"  >
                        <div class="col-6">
                            <label class="form-label" for="duration1">Hours</label>
                            <input id="duration1" class="form-control duration" placeholder="H" min="0" value="0" type="number" name="duration[]" >
                        </div>
                        <div class="col-6">
                            <label class="form-label" for="duration2">Minutes</label>
                            <input id="duration2" class="form-control duration" placeholder="M" min="1"  value="0" type="number" name="duration[]" >
                        </div>
                    </div>
                </div>

                <div class="mb-1" >
                    <label class="form-label" for="description">Description</label>
                    <textarea name="description" class="form-control" id="description" rows="4"></textarea>
                    <small class="text-danger description_error"></small>
                </div>

                <span type="submit" class="btn btn-primary me-1 data-submit" id="save_lesson">Submit</span>
                <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>
<!-- Modal to add new lesson Ends-->
