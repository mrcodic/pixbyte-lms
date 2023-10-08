<!-- Modal to add new user starts-->
<div class="modal modal-slide-in new-user-modal fade" id="modal-user">
    <div class="modal-dialog">
        <form class="add-new-user modal-content pt-0">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
            <div class="modal-header mb-1">
                <h5 class="modal-title" id="exampleModalLabel">Add Admin</h5>
            </div>
            <div class="modal-body flex-grow-1" id="form_data">
                <input type="hidden" id="id" name="id">
                <div class="mb-1">
                    <label class="form-label" for="name">Name</label>
                    <input
                        type="text"
                        class="form-control dt-full-name"
                        id="name"
                        placeholder="John Doe"
                        name="name"
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
                    <input
                        type="password"
                        id="password"
                        class="form-control dt-contact"
                        placeholder="**************"
                        name="password"
                    />
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
                <button type="submit" class="btn btn-primary me-1 data-submit" id="save_student">Submit</button>
                <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>
<!-- Modal to add new user Ends-->
