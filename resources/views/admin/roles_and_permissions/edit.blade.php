@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Roles')

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
                    <h4 class="card-title">Edit Role</h4>
                </div>
                <div class="card-body">
                    <form class=""  action="{{route('roles.update',$roles->id)}}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mb-1">
                            <label class="form-label" for="basic-addon-name">Name</label>

                            <input
                                type="text"
                                id="basic-addon-name"
                                class="form-control"
                                placeholder="Name"
                                name="name"
                                value="{{$roles->name}}"

                            />
                            @error('name')
                               <div class="text-danger">{{$message}}</div>
                            @enderror
                        </div>
                        <div class="mb-1">
                            <label class="form-label" for="select-country1">Type</label>
                            <select name="type" id="role_type" class="form-select" required>
                                <option value="1" @if($roles->type==1) selected @endif>Admin</option>
                                <option value="2" @if($roles->type==2) selected @endif>Instructor</option>
                                <option value="3" @if($roles->type==3) selected @endif>Student</option>
                            </select>
                            @error('name')
                            <div class="text-danger">{{$message}}</div>
                            @enderror
                        </div>
                        <div class="mb-1">
                            <h4 class="mt-2 pt-50">Role Permissions</h4>
                            <!-- Permission table -->
                            <div class="table-responsive">
                                <table class="table table-flush-spacing">
                                    <tbody id="appendPermisions">
                                    <tr>
                                        <td class="text-nowrap fw-bolder">
                                            Administrator Access
                                            <span data-bs-toggle="tooltip" data-bs-placement="top" title="Allows a full access to the system">
                                                <i data-feather="info"></i>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="selectAll" />
                                                <label class="form-check-label" for="selectAll"> Select All </label>
                                            </div>
                                        </td>
                                    </tr>
                                    @if($roles->type==1)
                                        @include('admin.roles_and_permissions.permissions',['tablPermissions'=>adminDbTablesPermissions()])
                                    @elseif($roles->type==3)
                                        @include('admin.roles_and_permissions.permissions',['tablPermissions'=>studentOnlineDbTablesPermissions()])
                                    @elseif($roles->type==4)
                                        @include('admin.roles_and_permissions.permissions',['tablPermissions'=>studentOfflineDbTablesPermissions()])
                                    @else
                                        @include('admin.roles_and_permissions.permissions',['tablPermissions'=>instructorDbTablesPermissions()])
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                            <!-- Permission table -->
                        </div>




                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- /Bootstrap Validation -->
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

          let value=$('#role_type').val();

          $(document).on('click','#delete_row',function (e){
              e.preventDefault();
              var rowID=$(this).attr('data-id');
              let url = `/admin/roles/${rowID}`
              deleteRow(rowID,url,null,true)

          })
          $(document).on('change','#role_type',function (e){
             let value=$(this).val();
             fetch_data(`/admin/getPermessions/${value}`,'#appendPermisions')

          });

          $('#save_role').on('click',function (e){
              e.preventDefault()
              var selectids = [];
              $.each($("input:checkbox:checked"), function(){
                  if($(this).val() !='on'){
                      selectids.push($(this).val());
                  }
              });
              console.log(selectids)
              if(selectids.length>0){
                  var formdata= {
                      name: $('#name').val(),
                      type: $('#role_type').val(),
                      ids:selectids,
                  }
                  create_modal(formdata,'/admin/roles','POST','addRoleModal',null,true);
              }else{
                  Swal.fire("warning!", "Please Select Room First.", "warning");
              }

          });



          $(document).on('click','#selectAll',function (e){
              $('input:checkbox').not(this).prop('checked', this.checked);
          })



      });
  </script>
@endsection
