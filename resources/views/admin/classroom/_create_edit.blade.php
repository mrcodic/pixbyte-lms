<!-- Modal to add new classroom starts-->
<div class="modal modal-slide-in new-classroom-modal fade" id="modal-classroom">
    <div class="modal-dialog">
        <form class="add-new-classroom modal-content pt-0">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
            <div class="modal-header mb-1">
                <h5 class="modal-title" id="exampleModalLabel">Add classroom</h5>
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

                <div class="mb-1" id="div_classrooms" >
                    <label class="form-label" for="userId">Instructor</label>
                    <select id="userId" class="select2 form-select" name="userId">
                                <option readonly disabled selected>select...</option>
                        @foreach($instructors as $instructor)
                                <option value="{{$instructor->id}}">{{$instructor->name}}</option>
                        @endforeach
                    </select>
                    <small class="text-danger userId_error"></small>
                </div>
                <div class="mb-1" id="div_role"  >
                    <label class="form-label" for="grade_id">Grade</label>
                    <select id="grade_id" class="select2 form-select" name="grade_id">
                        <option readonly disabled selected>select...</option>
                        @foreach($grades as $grade)
                            <option value="{{$grade->id}}">{{$grade->name}}</option>
                        @endforeach
                    </select>
                    <small class="text-danger grade_id_error"></small>
                </div>
                <div class="mb-1" id="div_role"  >
                    <label class="form-label" for="subject_id">Subject</label>
                    <select id="subject_id" class="select2 form-select" name="subject_id">
                        <option readonly disabled selected>select...</option>
                        @foreach($subjects as $subject)
                            <option value="{{$subject->id}}">{{$subject->name}}</option>
                        @endforeach
                    </select>
                    <small class="text-danger subject_id_error"></small>
                </div>
                <div class="mb-1" id="div_role"  >
                    <label class="form-label" for="room_scheduel">Room Schedule</label>
                    <select id="room_scheduel" class="select2 form-select" name="room_scheduel">
                        <option readonly disabled selected>select...</option>
                        @foreach($schedules as $schedule)
                            <option value="{{$schedule->id}}">{{$schedule->name}}</option>
                        @endforeach
                    </select>
                    <small class="text-danger room_scheduel_error"></small>
                </div>

                <div class="mb-1" id="div_role"  >
                    <label class="form-label" for="subject_id">Absence rooms</label>
                    <input type="number" id="absence_times" class="form-control " name="absence_times"></select>
                    <small class="text-danger absence_times_error"></small>
                </div>
                <div class="mb-1" id="div_role"  >
                    <label class="form-label" for="description">description</label>
                    <textarea name="description" class="form-control" id="description" rows="4"></textarea>
                    <small class="text-danger description_error"></small>
                </div>

                <div class="mb-2">
                    <label class="form-label" for="cover"> Cover</label>
                    <input class="form-control" type="file" id="cover" name="cover">
                    <small class="text-danger cover_error"></small>
                </div>
                <button type="submit" class="btn btn-primary me-1 data-submit" id="save_classroom">Submit</button>
                <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>
<!-- Modal to add new classroom Ends-->
