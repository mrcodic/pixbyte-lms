@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Instructors')

@section('vendor-style')

@endsection
@section('page-style')
  <!-- Page css files -->
@endsection

@section('content')

<section class="bs-validation">
    <div class="row">
        <!-- Bootstrap Validation -->
        <div class="col-md-12 col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Instructor</h4>
                </div>
                <div class="card-body">
                    <form class="" action="{{route('users.update',$instructor->id)}}" method="POST" enctype="multipart/form-data">
                      @csrf
                        @method('PATCH')
{{--                        @dd($errors)--}}
                        <div class="modal-body flex-grow-1" id="form_data">
                            <input type="hidden" id="id" name="id" value="{{$instructor->id}}">
                            <div class="mb-1">
                                <label class="form-label" for="first_name">First Name</label>
                                <input
                                    type="text"
                                    class="form-control dt-full-name"
                                    id="first_name"
                                    placeholder="John Doe"
                                    name="first_name"
                                    value="{{$instructor->first_name}}"
                                />
                                @error('first_name')
                                <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="mb-1">
                                <label class="form-label" for="last_name">Last Name</label>
                                <input
                                    type="text"
                                    class="form-control dt-full-name"
                                    id="last_name"
                                    placeholder="John Doe"
                                    name="last_name"
                                    value="{{$instructor->last_name}}"
                                />

                                @error('last_name')
                                <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="mb-1">
                                <label class="form-label" for="email">Email</label>
                                <input
                                    type="text"
                                    id="email"
                                    class="form-control dt-email"
                                    placeholder="john.doe@example.com"
                                    name="email"
                                    value="{{$instructor->email}}"
                                />
                                @error('email')
                                <div class="text-danger">{{$message}}</div>
                                @enderror
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
                                        {{-- required --}}
                                    />
                                    <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                                </div>

                                @error('password')
                                <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="mb-1" id="div_role" >
                                <label class="form-label" for="role_id">User Role</label>
                                <select id="role_id" class="select2 form-select" name="role_id">
                                    <option readonly disabled selected>select...</option>
                                    @foreach($roles as $role)
                                        <option value="{{$role->id}}"  {{ in_array( $role->id, $instructor->roles->pluck('id')->toArray()) ? 'selected' :null }}>{{$role->name}}</option>
                                    @endforeach
                                </select>
                                @error('role_id')
                                    <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="mb-1" id="div_users"   style="display: none">
                                <label class="form-label" for="role_id">Instructor</label>
                                <select id="instructor_id" class="select2 form-select" name="instructor_id">
                                    <option readonly disabled selected>select...</option>
                                    @foreach($instructors as $user)
                                        <option value="{{$user->id}}"  @if(@$instructor->instructor->instructor_id==$user->id) selected @endif>{{$user->name}}</option>
                                    @endforeach
                                </select>
                                @error('instructor_id')
                                <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>

                            </div>
                            <div class="mb-2">
                                <label class="form-label" for="profile_image"> Image Profile</label>
                                <input class="form-control" type="file" id="profile_image" value="{{$instructor->profile_image}}" name="profile_image">
                                <small class="text-danger profile_image_error"></small>
                                <img  width="100"class="mt-2" @if($instructor->profile_image) src="/uploads/profile_images/{{$instructor->profile_image}}" @else  src="/uploads/no-image/no-image.jpg"  @endif>
                            </div>

                            <button type="submit" class="btn btn-primary me-1 data-submit" >Submit</button>
                            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>


@endsection

@section('vendor-script')
  <!-- Vendor js files -->
  <script src="{{ asset('admin/vendors/js/forms/select/select2.full.min.js') }}"></script>
@endsection
@section('page-script')
  <script src="{{ asset('admin/js/helper.js') }}"></script>

  <script>
      $( document ).ready(function() {
           @if(@$instructor->roles->pluck('name')[0]!='superInstructor')
             $('#div_users').show()
          @else
           $('#div_users').hide()
          @endif
          $('#role_id').on('change',function (e){
              if($("option:selected", this).text()!='superInstructor'){
                  $('#div_users').show()
              }else{
                  $('#instructor_id').val('')
                  $('#div_users').hide()
              }
          })







      });
  </script>
@endsection
