<!-- Modal to add new room starts-->
<div class="modal modal-slide-in new-room-modal fade" id="modal-room">
    <div class="modal-dialog">
        <form class="add-new-room modal-content pt-0">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
            <div class="modal-header mb-1">
                <h5 class="modal-title" id="exampleModalLabel">Add room</h5>
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
                @if (!$class)
                    <div class="row">
                        <div class="mb-1 col-6">
                            <label class="form-label" for="user_id">select instructor</label>
                            <select id="user_id" class="select2 form-select" name="user_id">
                                <option value="" >select...</option>
                                @foreach($instructors as $instructor)
                                    <option value="{{$instructor->id}}" >{{$instructor->name}}</option>
                                @endforeach
                            </select>
                            <small class="text-danger title_error"></small>
                        </div>
                        <div class="mb-1 col-6">
                            <label class="form-label" for="class_room_ids">select classroom</label>
                            <select id="class_room_ids" class="select2 form-select" name="class_room_ids" multiple="multiple"></select>
                            <small class="text-danger title_error"></small>
                        </div>
                    </div>
                @endif

                <div class="row">
                    <div class="mb-1 col-6"  >
                        <label class="form-label" for="ulock_after">Lock After (days)</label>
                        <input type="number" id="ulock_after" class="form-control " name="ulock_after"></select>
                        <small class="text-danger ulock_after_error"></small>
                    </div>
                    <div class="mb-1 col-6"  >
                        <label class="form-label" for="price">Room Price</label>
                        <input type="number" id="price" class="form-control " name="price"></select>
                        <small class="text-danger price_error"></small>
                    </div>
                </div>

                <div class="mb-1" >
                    <label class="form-label" for="description">Description</label>
                    <textarea name="description" class="form-control" id="description" rows="4"></textarea>
                    <small class="text-danger description_error"></small>
                </div>

                </form>
                <div class="mb-2">

                    <input class="form-control" type="hidden" id="materialIds" name="materialIds[]">

                    <label class="form-label" for="material"> Room Metrial</label>
                    <form action="{{route('admin.room.upload_material')}}" class="dropzone dropzone-area"  maxFilesize="1024" id="dpz-upload-material">
                        @csrf
                        <div class="dz-message">Drop files here or click to upload.</div>
                    </form>
                </div>

                <button type="submit" class="btn btn-primary me-1 data-submit" id="save_room">Submit</button>
                <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        {{-- </form> --}}
    </div>
</div>
<!-- Modal to add new room Ends-->
