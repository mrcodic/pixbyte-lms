<!-- Modal to add new user starts-->
<div class="modal modal-slide-in new-user-modal fade" id="modal-user" >
    <div class="modal-dialog">
        <form class="add-new-user modal-content pt-0" enctype="multipart/form-data">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
            <div class="modal-header mb-1">
                <h5 class="modal-title" id="exampleModalLabel">Add User</h5>
            </div>
            <div class="modal-body flex-grow-1" id="form_data">
                <input type="hidden" id="id" name="id">

                <div class="row mb-1">
                    <label class="form-label" for="setIdStudent">Set User Id</label>
                    <div class="form-switch mx-1">
                        <input type="checkbox" class="form-check-input" id="setIdStudent">
                    </div>
                </div>
                <div class="row">
                    <div class="mb-1">
                        <input
                            type="text"
                            class="form-control dt-id hidden"
                            id="name_id"
                            placeholder="mvs-0001"
                            name="name_id"
                        />
                        <small class="text-danger name_id_error"></small>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-1 col-6">
                        <label class="form-label" for="first_name">First Name</label>
                        <input
                            type="text"
                            class="form-control dt-full-name"
                            id="first_name"
                            placeholder="John "
                            name="first_name"
                        />
                        <small class="text-danger first_name_error"></small>
                    </div>
                    <div class="mb-1 col-6">
                        <label class="form-label" for="last_name">Last Name</label>
                        <input
                            type="text"
                            class="form-control dt-full-name"
                            id="last_name"
                            placeholder=" Doe"
                            name="last_name"
                        />
                        <small class="text-danger first_name_error"></small>
                    </div>
                </div>
                <div class="mb-1">
                    <label class="form-label" for="phone">Phone</label>
                    <input
                        type="text"
                        class="form-control"
                        id="phone"
                        placeholder="01234567890"
                        name="phone"
                    />
                    <small class="text-danger phone_error"></small>
                </div>
                <div class="mb-1">
                    <label class="form-label" for="email">Email</label>
                    <input
                        type="text"
                        id="email"
                        class="form-control dt-email"
                        placeholder="john.doe@example.com"
                        name="email"
                    />
                    <small class="text-danger email_error"></small>
                </div>
                <div class="row">
                    <div class="mb-1 col-12">
                        <label class="form-label" for="password">Password</label>

                        <div class="input-group input-group-merge form-password-toggle">
                            <input
                                class="form-control form-control-merge"
                                id="password"
                                type="password"
                                name="password"
                                placeholder="············"
                                aria-describedby="password"
                                tabindex="2"
                                required
                            />
                            <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                        </div>

                        <small class="text-danger password_error"></small>
                    </div>

                </div>
                <div class="row">
                    <div class="mb-1 col-6" id="div_users" >
                        <label class="form-label" for="type">type</label>
                        <select id="type" class="select2 form-select" name="type">
                            <option readonly disabled selected>select...</option>
                            {{-- <option value="3">Student Online</option>
                            <option value="4">Student Offline</option> --}}
                            @foreach ($roles as $role)
                                <option value="{{$role->id}}">{{$role->name}}</option>
                            @endforeach
                        </select>
                        <small class="text-danger role_id_error"></small>
                    </div>
                    <div class="mb-2 col-6" id="div_grade" >
                        <label class="form-label" for="grade">Grade</label>
                        <select id="grade_id" class="select2 form-select" name="grade_id">
                            <option readonly disabled selected>select...</option>
                            @foreach($grades as $grade)
                            <option value="{{$grade->id}}">{{$grade->name}}</option>
                            @endforeach
                        </select>
                        <small class="text-danger grade_id_error"></small>
                    </div>
                </div>
                <div class="mb-2">
                    <label class="form-label" for="profile_image"> Image Profile</label>
                    <input class="form-control" type="file" id="profile_image" name="profile_image">
                    <small class="text-danger profile_image_error"></small>
                </div>
                <hr>
                <div class="row">
                    <div class="mb-1 col-6">
                        <label class="form-label" for="parent__name">Parent Name</label>
                        <input
                            type="text"
                            class="form-control"
                            id="parent__name"
                            placeholder="John's parent"
                            name="parent__name"
                        />
                        <small class="text-danger parent__name_error"></small>
                    </div>
                    <div class="mb-1 col-6">
                        <label class="form-label" for="parent__email">Parent Email</label>
                        <input
                            type="text"
                            class="form-control"
                            id="parent__email"
                            placeholder="JohnParent@email.com"
                            name="parent__email"
                        />
                        <small class="text-danger parent__email_error"></small>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-1 col-6">
                        <label class="form-label" for="parent__phone">Parent Phone</label>
                        <input
                            type="text"
                            class="form-control"
                            id="parent__phone"
                            placeholder="+123456789"
                            name="parent__phone"
                        />
                        <small class="text-danger parent__phone_error"></small>
                    </div>
                    <div class="mb-1 col-6">
                        <label class="form-label" for="parent__pass">Parent Password</label>
                        <input
                            type="password"
                            class="form-control"
                            id="parent__pass"
                            placeholder="********"
                            name="parent__pass"
                        />
                        <small class="text-danger parent__pass_error"></small>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary me-1 data-submit" id="save_student">Submit</button>
                <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>
<!-- Modal to add new user Ends-->


<!-- Add Role Modal -->
<div class="modal fade" id="addToClassRoomModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-add-new-role">
      <div class="modal-content">
        <div class="modal-header bg-transparent">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body px-5 pb-5">
          <div class="text-center mb-4">
            <h1 class="role-title">Add Student to classroom</h1>
          </div>
          <!-- Add role form -->
          <form id="addToClassRoomForm" class="row" onsubmit="return false">
            <div class="col-12">
                <input type="text" hidden name="user_ids[]" id="studentToAddClassRoom">
                <label class="form-label" for="classRoomSelectorInstructor">Instructors</label>
                <select id="classRoomSelectorInstructor" class="select2 form-select" name="classRoomSelectorInstructor">
                    <option readonly disabled selected>select...</option>
                    @foreach ($instructors as $user)
                    <option value="{{$user->id}}">{{$user->name}}</option>
                    @endforeach
                </select>

                <label class="form-label mt-2" for="classRoomSelectorToAdd">ClassRooms</label>
                <select id="classRoomSelectorToAdd" class="select2 form-select" name="classRoomSelectorToAdd">
                    <option readonly disabled selected>select...</option>
                    {{-- @foreach ($classrooms as $class)
                        <option value="{{$class->id}}">{{$class->title}}</option>
                    @endforeach --}}
                </select>
                <small class="text-danger classroom_error"></small>
            </div>
            <div class="col-12 text-center mt-2">
              <button type="submit" class="btn btn-primary me-1" id="save_class_student">Submit</button>
              <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">
                cancel
              </button>
            </div>
          </form>
          <!--/ Add role form -->
        </div>
      </div>
    </div>
</div>
<!--/ Add Role Modal -->
