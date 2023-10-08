@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Instructor List')

@section('vendor-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset('admin/vendors/css/forms/select/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/vendors/css/tables/datatable/responsive.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/vendors/css/tables/datatable/buttons.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/vendors/css/tables/datatable/rowGroup.bootstrap5.min.css') }}">
@endsection

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset('admin/css/base/plugins/forms/form-validation.css') }}">
@endsection

@section('content')
    <!-- users list start -->
    <section class="app-user-list">
        <!-- list and filter start -->
        <div class="card">
            <div class="card-body border-bottom">
                <h4 class="card-title">Search & Filter</h4>
                <div class="row">
                    <div class="col-md-4 user_role">
                        <label class="form-label" for="UserRole">Grade</label>
                        <select id="grade_id_filter" class="form-select text-capitalize mb-md-0 mb-2">
                            <option value=""> Select Grade </option>
                            @foreach ( $grades as $grade )
                                <option value=" {{$grade->id}} ">{{ $grade->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 user_plan">

                        <label class="form-label" for="UserRole">ClassRoom</label>
                        <select id="classroom_id" class="form-select text-capitalize mb-md-0 mb-2">
                            <option value=""> Select ClassRoom </option>
                            <option value="">All</option>
                            @foreach ( $classrooms as $class )
                                <option value=" {{$class->id}} ">{{ $class->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 user_status"><label class="form-label" for="UserRole">Role</label>
                        <select id="UserRole" class="form-select text-capitalize mb-md-0 mb-2">
                            <option value=""> Select Role </option>
                            @foreach($roles as $role)
                              <option value="{{$role->id}}">{{$role->name}} </option>
                            @endforeach

                        </select>
                    </div>
                </div>
            </div>
            <div class="card-datatable table-responsive pt-0">
                <table class="user-list-table table" id="user_table">
                    <input type="hidden" name="select_all" id="select_all" value="">

                    <thead class="table-light">
                    <tr>
                        <th><input id="checkbox_all" class="uk-checkbox" type="checkbox"></th>
                        <th >User ID</th>
                        <th >Name</th>
                        <th >Email</th>
                        <th >Role</th>
                        <th >Last update</th>
                        <th >Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            @include('admin.users._create_edit')
        </div>
        <!-- list and filter end -->
    </section>
    <!-- users list ends -->
@endsection

@section('vendor-script')
    {{-- Vendor js files --}}
    <script src="{{ asset('admin/vendors/js/forms/select/select2.full.min.js') }}"></script>
    <script src="{{ asset('admin/vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin/vendors/js/tables/datatable/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('admin/vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('admin/vendors/js/tables/datatable/responsive.bootstrap5.js') }}"></script>
    <script src="{{ asset('admin/vendors/js/tables/datatable/datatables.buttons.min.js') }}"></script>
    <script src="{{ asset('admin/vendors/js/tables/datatable/jszip.min.js') }}"></script>
    <script src="{{ asset('admin/vendors/js/tables/datatable/pdfmake.min.js') }}"></script>
    <script src="{{ asset('admin/vendors/js/tables/datatable/vfs_fonts.js') }}"></script>
    <script src="{{ asset('admin/vendors/js/tables/datatable/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('admin/vendors/js/tables/datatable/buttons.print.min.js') }}"></script>
    <script src="{{ asset('admin/vendors/js/tables/datatable/dataTables.rowGroup.min.js') }}"></script>
    <script src="{{ asset('admin/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('admin/vendors/js/forms/cleave/cleave.min.js') }}"></script>
    <script src="{{ asset('admin/vendors/js/forms/cleave/addons/cleave-phone.us.js') }}"></script>
@endsection

@section('page-script')
    {{-- Page js files --}}
    <script src="{{ asset('admin/js/helper.js') }}"></script>
    <script>
        $( document ).ready(function() {
            var user_table
            user_table =  $('#user_table').DataTable({
                processing: true,
                serverSide: true,
                lengthMenu: [5, 10, 20, 50],
                pageLength: 10,
                responsive: true,
                ordering:false,


                dom:
                    '<"d-flex justify-content-between align-items-center header-actions mx-2 row mt-75"' +
                    '<"col-sm-12 col-lg-4 d-flex justify-content-center justify-content-lg-start" l>' +
                    '<"col-sm-12 col-lg-8 ps-xl-75 ps-0"<"dt-action-buttons d-flex align-items-center justify-content-center justify-content-lg-end flex-lg-nowrap flex-wrap"<"me-1"f>B>>' +
                    '>t' +
                    '<"d-flex justify-content-between mx-2 row mb-1"' +
                    '<"col-sm-12 col-md-6"i>' +
                    '<"col-sm-12 col-md-6"p>' +
                    '>',
                language: {
                    sLengthMenu: 'Show _MENU_',
                    search: 'Search',
                    searchPlaceholder: 'Search..'
                },
                order: [[0, 'desc']],
                ajax: {
                    url: "{{route('get_users')}}",
                    data: function (d) {
                        d.grade_id=$('#grade_id_filter').val();
                        d.classroom_id=$('#classroom_id').val();
                        d.user_role=$('#UserRole').val();
                    },
                },
                columns: [

                    {
                        data:'id',orderable: false, render:function (data,type,full){
                            return `<input class="uk-checkbox usersCheckbox" id="checkbox" value="${data}" type="checkbox">`;}
                    },
                    {data: 'name_id', name: 'name_id', className: ' uk-text-left'  },

                    {data: 'name' , className: ' uk-text-left' ,
                        render:function (data,type,full){
                            return `<a class="link" href="/u/${full['name_id']}">${data}</a> `} },
                    {data: 'email', name: 'email', className: ' uk-text-left'  },
                    {data: 'role', name: 'enroll_class', className: ' uk-text-left'  },
                    {data: 'created_at', name: 'created_at', className: ' uk-text-left' },
                    {data: 'id',className:'uk-text-nowrap',render:function (data,type,full){
                            return (
                                '<div class="btn-group">' +
                                '<a class="btn btn-sm dropdown-toggle hide-arrow" data-bs-toggle="dropdown">' +
                                feather.icons['more-vertical'].toSvg({ class: 'font-small-4' }) +
                                '</a>' +
                                '<div class="dropdown-menu dropdown-menu-end">' +
                                '<a href="/admin/users/'+data+'/edit"  id="edit_row" class="dropdown-item">' +
                                feather.icons['file-text'].toSvg({ class: 'font-small-4 me-50' }) +
                                'Edit</a>' +
                                '<a href="javascript:;" data-id="'+data+'" class="dropdown-item forceLogout">' +
                                feather.icons['log-out'].toSvg({ class: 'font-small-4 me-50' }) +
                                'forceLogout</a>' +
                                '<a href="javascript:;" id="delete_row" data-id="'+data+'" class="dropdown-item delete-record">' +
                                feather.icons['trash-2'].toSvg({ class: 'font-small-4 me-50' }) +
                                'Delete</a></div>' +
                                '</div>' +
                                '</div>'
                            );
                        }
                    }
                ],
                // Buttons with Dropdown
                buttons: [
                    {
                        extend: 'collection',
                        className: 'btn btn-outline-secondary dropdown-toggle me-2',
                        text: feather.icons['external-link'].toSvg({ class: 'font-small-4 me-50' }) + 'Export',
                        buttons: [
                            {
                                extend: 'excel',
                                text: feather.icons['file'].toSvg({ class: 'font-small-4 me-50' }) + 'Excel',
                                className: 'dropdown-item',
                                exportOptions: { columns: [1, 2, 3, 4, 5] }
                            },
                        ],
                        init: function (api, node, config) {
                            $(node).removeClass('btn-secondary');
                            $(node).parent().removeClass('btn-group');
                            setTimeout(function () {
                                $(node).closest('.dt-buttons').removeClass('btn-group').addClass('d-inline-flex mt-50');
                            }, 50);
                        }
                    },
                    {
                        text: 'Add New User',
                        className: 'add-new btn btn-primary',
                        attr: {
                            'data-bs-toggle': 'modal',
                            'data-bs-target': '#modal-user'
                        },
                        init: function (api, node, config) {
                            $(node).removeClass('btn-secondary');
                        }
                    },
                    {
                        text: 'Force Logout',
                        className: 'btn btn-danger me-2 force_selected',
                    }
                ],

            });


            $('#checkbox_all').on('click',function (e){
                $('input:checkbox').not(this).prop('checked', this.checked);
            })

            $('#role_id').on('change',function (e){

               if($("option:selected", this).text()!='superInstructor'){
                    $('#div_users').show()
               }else{
                   $('#div_users').hide()
                   $('#instructor_id').val('')
               }
            })
            function fetch_role(id){
                $.ajax({
                    url: `/admin/getRole/${id}`,
                    type: "get",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function (res) {
                        if(res.status){
                            $('#role_id').empty();
                            var html=''
                            res.data.map((item)=>{
                                html+=`<option value="${item.id}">${item.name}</option>`
                            })
                            $('#role_id').append(html);
                        }
                    },
                    error:function (res) {
                        Swal.fire("Close!", "Something is wrong, Please try again.", "error");
                    }
                });
            }

            $(document).on('click','#save_student',function (e){
                e.preventDefault()
                var formdata= {
                    first_name: $('#first_name').val(),
                    last_name: $('#last_name').val(),
                    email:$('#email').val(),
                    password:$('#password').val(),
                    grade_id:$('#grade_id').val(),
                    role_id:$('#role_id').val(),
                    instructor_id:$('#instructor_id').val(),
                    type:$('#type').val(),
                    profile_image:$('#profile_image')[0].files[0],
            }
                create_modal(formdata,'/admin/users','POST','modal-user',user_table,true);
            });


            $(document).on('click','#delete_row',function (e){
                e.preventDefault();
                var rowID=$(this).attr('data-id');
                let url = `/admin/users/${rowID}`
                deleteRow(rowID,url)

            })

            $('#delete_all').on('click',function (e){
                e.preventDefault();
                var selectids = [];
                $.each($("input:checkbox:checked"), function(){
                    if($(this).val() !='on'){
                        selectids.push($(this).val());
                    }
                });
                if(selectids.length>0){
                    var url='/delete-all-student'
                    deleteRow(selectids,url)
                }else{
                    Swal.fire("warning!", "Please Select Room First.", "warning");
                }

            });
            $(document).on('click','.force_selected',function (e){
                e.preventDefault();
                var selectids = [];
                $('.usersCheckbox').each(function (loop) {
                    this.checked ? selectids.push($(this).val()) :null;
                });
                if(selectids.length>0){
                    $.ajax({
                    url: `/admin/forceLogout_selected`,
                    type: "post",
                    data:{ids:selectids},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    beforeSend: function() {
                        $('.loader').show();
                    },
                    success: function (res) {

                            Swal.fire("success!", "Log out Success.", "success");

                    },
                    error:function (res) {
                        Swal.fire("Close!", "Something is wrong, Please try again.", "error");
                    }
                });
                }else{
                    Swal.fire("warning!", "Please Select Room First.", "warning");
                }

            });
            $(document).on('click','.forceLogout',function (e){
                e.preventDefault()
                var select = $(this).attr('data-id');
                $.ajax({
                    url: `/admin/forceLogout/${select}`,
                    type: "get",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    beforeSend: function() {
                        $('.loader').show();
                    },
                    success: function (res) {

                            Swal.fire("success!", "Log out Success.", "success");

                    },
                    error:function (res) {
                        Swal.fire("Close!", "Something is wrong, Please try again.", "error");
                    }
                });
            })
            function deleteRow(id,url){
                let accetp_swal = Swal.fire({
                    title: " Are You Sure To Delete ?",
                    text: "",
                    icon:"warning",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#DD6B55',
                    confirmButtonText: 'Yes',
                    cancelButtonText: "No",
                    closeOnConfirm: false,
                    closeOnCancel: false
                }).then(function (result){
                    if (result.isConfirmed){
                        $.ajax({
                            url: url,
                            type: "POST",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            },
                            data: {idds: id,'_method':"DELETE"},
                            success: function (res) {
                                if(res.status){
                                    Swal.fire("Done", "Record delete successful.", "success");

                                }
                                user_table.ajax.reload();
                            },
                            error:function (res) {
                                Swal.fire("Close!", "Something is wrong, Please try again.", "error");
                            }
                        });
                    } else {
                        Swal.fire("Close", "Close Success", "error");
                    }
                })


            }


            $('#grade_id_filter').on('change',function (){
                user_table.ajax.reload();
            });
            $('#classroom_id').on('change',function (){
                user_table.ajax.reload();
            });
            $('#UserRole').on('change',function (){
                user_table.ajax.reload();
            });

        });
    </script>
@endsection
