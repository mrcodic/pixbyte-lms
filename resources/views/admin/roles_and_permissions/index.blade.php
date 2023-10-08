@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Roles')

@section('vendor-style')
  <!-- Vendor css files -->
  <link rel="stylesheet" href="{{ asset('admin/vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}">
  <link rel="stylesheet" href="{{ asset('admin/vendors/css/tables/datatable/responsive.bootstrap5.min.css') }}">
  <link rel="stylesheet" href="{{ asset('admin/vendors/css/tables/datatable/buttons.bootstrap5.min.css') }}">
@endsection
@section('page-style')
  <!-- Page css files -->
  <link rel="stylesheet" href="{{ asset('admin/css/base/plugins/forms/form-validation.css') }}">
@endsection

@section('content')
<h3>Roles List</h3>
<p class="mb-2">
  A role provided access to predefined menus and features so that depending <br />
  on assigned role an administrator can have access to what he need
</p>

<!-- Role cards -->
<div class="row">
    <div class="col-xl-4 col-lg-6 col-md-6">
        <div class="card">
            <div class="row">
                <div class="col-sm-5">
                    <div class="d-flex align-items-end justify-content-center h-100">
                        <img
                            src="{{asset('admin/images/illustration/faq-illustrations.svg')}}"
                            class="img-fluid mt-2"
                            alt="Image"
                            width="85"
                        />
                    </div>
                </div>
                <div class="col-sm-7">
                    <div class="card-body text-sm-end text-center ps-sm-0">
                        <a
                            href="javascript:void(0)"
                            data-bs-target="#addRoleModal"
                            data-bs-toggle="modal"
                            class="stretched-link text-nowrap add-new-role"
                        >
                            <span class="btn btn-primary mb-1">Add New Role</span>
                        </a>
                        <p class="mb-0">Add role, if it does not exist</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @foreach($roles as $role)
    <div class="col-xl-4 col-lg-6 col-md-6">
        <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between">
            <span>Total 7 users</span>
            <ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
                <li
                data-bs-toggle="tooltip"
                data-popup="tooltip-custom"
                data-bs-placement="top"
                title="{{$role->type==1?'Admin':"Instructor"}}"
                class=""
                >
                    {{$role->type==1?'Admin':null}}
                    {{$role->type==2?'Instructor':null}}
                    {{$role->type==3?'Student':null}}
                    {{$role->type==4?'Student':null}}
                </li>

            </ul>
            </div>
            <div class="d-flex justify-content-between align-items-end mt-1 pt-25">
            <div class="role-heading">
                <h4 class="fw-bolder">{{$role->name}}</h4>
                <a href="{{route('roles.edit',$role->id)}}"  class="role-edit-modal" >
                <small class="fw-bolder">Edit Role</small>
                </a>
            </div>
            <a href="javascript:void(0);" data-id="{{$role->id}}" id="delete_row"  class="text-body"><i data-feather="trash" class="font-medium-5"></i></a>
            </div>
        </div>
        </div>
    </div>
    @endforeach
</div>
<!--/ Role cards -->

@include('admin.roles_and_permissions.modal-add-role')

@endsection

@section('vendor-script')
  <!-- Vendor js files -->
  <script src="{{ asset('admin/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
@endsection
@section('page-script')
  <!-- Page js files -->
  <script src="{{ asset('admin/js/scripts/pages/modal-add-role.js') }}"></script>
  <script src="{{ asset('admin/js/scripts/pages/app-access-roles.js') }}"></script>

  <script src="{{ asset('admin/js/helper.js') }}"></script>

  <script>
      $( document ).ready(function() {
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
