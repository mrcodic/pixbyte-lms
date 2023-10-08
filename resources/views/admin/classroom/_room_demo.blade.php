<!-- Modal to add new room starts-->
<div class="modal modal-slide-in new-room-modal fade" id="modal-setRoomDemo">
    <div class="modal-dialog">
        <form class="add-new-room modal-content pt-0">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
            <div class="modal-header mb-1">
                <h5 class="modal-title" id="exampleModalLabel">Add room</h5>
            </div>
            <div class="modal-body flex-grow-1" id="form_data">

                <div class="mb-1">
                    <label class="form-label" for="demo_class_room_id">select room</label>
                    <select id="demo_class_room_id" class="select2 form-select " name="demo_class_room_id">
                        <option selected>select...</option>
                        @foreach($classrooms as $room)
                            <option value="{{$room->id}}" {{$classRoomDemoID == $room->id ? 'selected':null}}>{{$room->title}}</option>
                        @endforeach
                    </select>
                    <small class="text-danger title_error"></small>
                </div>


                <button type="submit" class="btn btn-primary me-1 data-submit" id="save_demo_class_room">Submit</button>
                <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        {{-- </form> --}}
    </div>
</div>
<!-- Modal to add new room Ends-->
