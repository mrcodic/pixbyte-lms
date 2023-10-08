@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Admins')

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
                    <h4 class="card-title">Edit Admin</h4>
                </div>
                <div class="card-body">
                    <form class="" action="{{route('admins.update',$admin->id)}}" method="POST" enctype="multipart/form-data">
                      @csrf
                        @method('PATCH')
                        <div class="modal-body flex-grow-1" id="form_data">
                            <input type="hidden" id="id" name="id" value="{{$admin->id}}">
                            <div class="mb-1">
                                <label class="form-label" for="first_name">Name</label>
                                <input
                                    type="text"
                                    class="form-control dt-full-name"
                                    id="first_name"
                                    placeholder="John Doe"
                                    name="name"
                                    value="{{$admin->name}}"
                                />
                                @error('name')
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
                                    value="{{$admin->email}}"
                                />
                                @error('email')
                                <div class="text-danger">{{$message}}</div>
                                @enderror
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

                                @error('password')
                                <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="mb-1" id="div_role" >
                                <label class="form-label" for="role_id">User Role</label>
                                <select id="role_id" class="select2 form-select" name="role_id">
                                    <option readonly disabled selected>select...</option>
                                    @foreach($roles as $role)
                                        <option value="{{$role->id}}"  @if(@$admin->roles->pluck('id')[0]==$role->id) selected @endif>{{$role->name}}</option>
                                    @endforeach
                                </select>
                                @error('role_id')
                                <div class="text-danger">{{$message}}</div>
                                @enderror
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

      });
  </script>
@endsection
