<!-- Modal to add new user starts-->
<div class="modal modal-slide-in new-user-modal fade" id="modal-user">
    <div class="modal-dialog">
        <form class="add-new-user modal-content pt-0">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
            <div class="modal-header mb-1">
                <h5 class="modal-title" id="exampleModalLabel">Add User</h5>
            </div>
            <div class="modal-body flex-grow-1" id="form_data">
                <input type="hidden" id="id" name="id">
                <div class="mb-1">
                    <label class="form-label" for="first_name">First Name</label>
                    <input
                        type="text"
                        class="form-control dt-full-name"
                        id="first_name"
                        placeholder="John Doe"
                        name="first_name"
                    />
                    <small class="text-danger first_name_error"></small>
                </div>
                <div class="mb-1">
                    <label class="form-label" for="last_name">Last Name</label>
                    <input
                        type="text"
                        class="form-control dt-full-name"
                        id="last_name"
                        placeholder="John Doe"
                        name="last_name"
                    />
                    <small class="text-danger first_name_error"></small>
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
                <div class="mb-1">
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
                <div class="mb-1" id="div_role"  >
                    <label class="form-label" for="role_id">User Role</label>
                    <select id="role_id" class="select2 form-select" name="role_id">
                        <option readonly disabled selected>select...</option>
                        @foreach($roles as $role)
                            <option value="{{$role->id}}">{{$role->name}}</option>
                        @endforeach
                    </select>
                    <small class="text-danger role_id_error"></small>
                </div>
                <div class="mb-1" id="div_users"  style="display: none">
                    <label class="form-label" for="role_id">Instructor</label>
                    <select id="instructor_id" class="select2 form-select" name="instructor_id">
                                <option readonly disabled selected>select...</option>
                        @foreach($users as $user)
                                <option value="{{$user->id}}">{{$user->name}}</option>
                        @endforeach
                    </select>
                    <small class="text-danger role_id_error"></small>
                </div>

                <div class="mb-2">
                    <label class="form-label" for="profile_image"> Image Profile</label>
                    <input class="form-control" type="file" id="profile_image" name="profile_image">
                    <small class="text-danger profile_image_error"></small>
                </div>
                <button type="submit" class="btn btn-primary me-1 data-submit" id="save_student">Submit</button>
                <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>
<!-- Modal to add new user Ends-->
