@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Students List')

@section('vendor-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset('admin/vendors/css/forms/select/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/vendors/css/tables/datatable/responsive.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/vendors/css/tables/datatable/buttons.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/vendors/css/tables/datatable/rowGroup.bootstrap5.min.css') }}">
    <link rel="stylesheet" type="text/css" href="https://printjs-4de6.kxcdn.com/print.min.css">

@endsection

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset('admin/css/base/plugins/forms/form-validation.css') }}">
@endsection

@section('content')
    <!-- users list start -->
    <section class="app-user-list">
        <div class="row">
            <div class="col-lg-3 col-sm-6">
                <div class="card cursor-pointer grade_id_filter_remove">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <h3 class="fw-bolder mb-75">{{$studentsCount}}</h3>
                            <span>Total Students</span>
                        </div>
                        <div class="avatar bg-light-primary p-50">
                            <span class="avatar-content">
                            <i data-feather="user" class="font-medium-4"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <input type="hidden" id="grade_id_filter_top">

            @foreach ( $grades as $grade )
                <div class="col-lg-3 col-sm-6" >
                    <div class="card cursor-pointer grade_id_filter_top_click"
                        data-grade-id="{{$grade->id}}"
                    >
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="fw-bolder mb-75">{{$grade->studentsCount()}}</h3>
                                <span>{{$grade->name}} Users</span>
                            </div>
                            <div class="avatar bg-light-success p-50">
                                <span class="avatar-content">
                                <i data-feather="user-plus" class="font-medium-4"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <!-- list and filter start -->
        <div class="card">
            <div class="card-body border-bottom">
                <h4 class="card-title">Search & Filter</h4>
                <div class="row">
                    {{-- <div class="col-md-4 user_role">
                        <label class="form-label" for="UserRole">Grade</label>
                        <select id="grade_id_filter" class="form-select text-capitalize mb-md-0 mb-2">
                            <option value=""> Select Grade </option>
                            @foreach ( $grades as $grade )
                                <option value=" {{$grade->id}} ">{{ $grade->name }}</option>
                            @endforeach
                        </select>
                    </div> --}}
                    {{-- <div class="col-md-4 user_plan">

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
                    </div> --}}
                </div>
            </div>
            <div class="card-datatable table-responsive pt-0">
                <table class="user-list-table table" id="user_table">
                    <input type="hidden" id="table_page" value="">

                    <input type="hidden" name="select_all" id="select_all" value="">

                    <thead class="table-light">
                    <tr>
                        <th><input id="checkbox_all" class="checkbox" type="checkbox"></th>
                        <th >User ID</th>
                        <th >Name</th>
                        <th >Email</th>
                        <th >Qrcode</th>
                        <th >Block</th>
                        <th >IP</th>
                        <th >Role</th>
                        <th >Last update</th>
                        <th >Classes</th>
                        <th >Phone</th>
                        <th >Parent</th>
                        <th >Parent Phone</th>
                        <th >Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            @include('admin.students._create_edit')
            @include('admin.students.StudentIPmodal')
            @include('admin.students.ParentModal')
            @include('admin.componant.print-qr')
            @include('admin.students.ActivityModal')
            @include('admin.students.GradeBookModal')
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
    <script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>

@endsection

@section('page-script')
    {{-- Page js files --}}
    <script src="{{ asset('admin/js/helper.js') }}"></script>
    <script>
        $( document ).ready(function() {


            var user_table
            user_table =  $('#user_table').DataTable({
                processing: true,
                ordering:false,
                serverSide: true,
                lengthMenu: [5, 10, 20, 50, 100],
                pageLength: 50,
                responsive: true,
                // searching:false,


                dom:
                    '<"d-flex justify-content-between align-items-center header-actions mx-2 row mt-75"' +
                    '<"col-sm-12 col-lg-3 d-flex justify-content-center justify-content-lg-start" l>' +
                    '<"col-sm-12 col-lg-4 d-flex justify-content-center justify-content-lg-start" <"ClassRoom me-1"><"UserRole me-1">>' +
                    '<"col-sm-12 col-lg-5 ps-xl-75 ps-0"<"dt-action-buttons d-flex align-items-center justify-content-center justify-content-lg-end flex-lg-nowrap flex-wrap"<"me-1 d-block"fb>B>>' +
                    '>t' +
                    '<"d-flex justify-content-between mx-2 row mb-1"' +
                    '<"col-sm-12 col-md-6"i>' +
                    '<"col-sm-12 col-md-6"p>' +
                    '>',
                language: {
                    sLengthMenu: 'Show _MENU_',
                    search: '',
                    searchPlaceholder: 'Search..'
                },
                // order: [[0, 'desc']],
                ajax: {
                    url: "{{route('get_students_users')}}",
                    data: function (d) {
                        d.grade_id = $('#grade_id_filter_top').val() ;
                        d.classroom_id = $('#classroom_id').val();
                        d.user_role = $('#UserRole').val();
                    },
                    beforeSend: function () {
                        $('#table_page').val($('#user_table').DataTable().page())
                    },
                },
                columnDefs: [
                        { "width": "5%", "targets": 3 },
                ],
                columns: [

                    {
                        data:'id',orderable: false, render:function (data,type,full){
                            return `<input class="checkbox checkboxStudent" data-name-id="${full['name_id']}" data-name="${full['name']}" data-qr-code="${full['qrcode']}" value="${data}" type="checkbox">`;}
                    },
                    {data: 'name_id', name: 'name_id', className: ' text-left',
                        render:function (data,type,full){
                            return `
                                 ${data}   `

                        }
                        },

                    {data: 'name' , className: ' text-left' ,
                        render:function (data,type,full){
                            return `
                                 <a class="link" href="/u/${full['name_id']}">${data}</a> `

                    }

                    },
                    {data: 'email', name: 'email', className: ' text-left'  },
                    {
                        data: 'qrcode', className: ' text-left',
                        render: function (data, type, full) {
                            return `
                                 <a class="link" data-name-id="${full['name_id']}" data-name="${full['name']}" data-qrcode="${full['qrcode']}" id="printer" href="#"> <img src="/img/scan.png" style="width: 18px"></a>`

                        }
                    },
                    {data: 'block', name: 'block', className: ' text-left'  ,
                        render:function (data,type,full){
                        let block
                        if(data){
                            block='checked';
                        }else{
                            block='';
                        }
                            return `<div class="form-check form-check-success form-switch">
                                                <input type="checkbox" student-id="${full['id']}" id="_changeBlock" ${block} class="form-check-input" id="customSwitch4">
                                            </div> `}
                    },

                    {data: 'ip', name: 'ip', className: ' text-left' ,
                        render:function (data,type,full){
                            return `<a class="modal_ip" data-student="${full['id']}" data-ip=${data}  href="#">${feather.icons['eye'].toSvg({ class: 'me-50' })}</a> `} },
                    {data: 'role', name: 'enroll_class', className: ' text-left'  },
                    {data: 'created_at', name: 'created_at', className: ' text-left' },
                    {data: 'classrooms', name: 'classrooms', className: ' text-left',
                        render:function (data,type,full){
                            return data.length > 0 ? `<span class="text-dark fw-bold">${data}<span>` : 'Not Set'
                        }
                    },
                    {data: 'phone', name: 'phone', className: ' text-left' },
                    {data: 'parent', name: 'parent', className: ' text-left' ,
                        render:function (data,type,full){
                            // console.log((full['parent']).name);
                            return full['parent'] ? `<a class="modal_parent"
                                data-name="${full['parent'].name}"
                                data-nameid="${full['parent'].name_id}"
                                href="#">${full['parent'].name }</a>`
                                :'Not Set'
                                // data-pass="${full['parent']['password']}"
                        }
                    },
                    {data: 'parent_phone', name: 'parent_phone', className: ' text-left'  },

                    {data: 'id',className:'text-nowrap',render:function (data,type,full){
                            let iconBlock
                            if(full['block']){
                                iconBlock=`<a class="dropdown-item delete-record" id="changeBlock" data-id="${data}"
                                                 title="Remove Suspension">${feather.icons['refresh-cw'].toSvg({ class: 'font-small-4 me-50' })} Remove Suspension</a>`;
                            }else{
                                iconBlock=''
                            }
                            return (
                                '<div class="btn-group">' +
                                '<a class="btn btn-sm dropdown-toggle hide-arrow" data-bs-toggle="dropdown">' +
                                feather.icons['more-vertical'].toSvg({ class: 'font-small-4' }) +
                                '</a>' +
                                '<div class="dropdown-menu dropdown-menu-end">' +
                                '<a href="/admin/students/'+data+'/edit?page='+$('#table_page').val()+'"  id="edit_row" class="dropdown-item">' +
                                feather.icons['file-text'].toSvg({ class: 'font-small-4 me-50' }) +
                                'Edit</a>' +
                                '<a href="javascript:;" data-id="'+data+'" class="dropdown-item tableAddToClassroom">' +
                                feather.icons['book-open'].toSvg({ class: 'font-small-4 me-50' }) +
                                'Add to Classroom</a>' +
                                '<a href="javascript:;" data-id="'+data+'" class="dropdown-item forceLogout">' +
                                feather.icons['log-out'].toSvg({ class: 'font-small-4 me-50' }) +
                                'forceLogout</a>' +
                                '<a href="javascript:;" id="delete_row" data-id="'+data+'" class="dropdown-item delete-record">' +
                                feather.icons['trash-2'].toSvg({ class: 'font-small-4 me-50' }) +
                                'Delete</a>' +
                                iconBlock+
                                '<a href="javascript:;" id="actitvity" data-id="'+data+'" title="Actitvity" id="delete_row" class="dropdown-item delete-record">' +
                                feather.icons['activity'].toSvg({ class: 'font-small-4 me-50' }) +
                                'Activity</a>' +
                                '<a href="javascript:;" id="grade_book" data-id="'+data+'" title="Grade Book" id="delete_row" class="dropdown-item delete-record">' +
                                feather.icons['book'].toSvg({ class: 'font-small-4 me-50' }) +
                                'Grade book</a>' +
                                '</div>' +
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
                        className: 'btn btn-outline-secondary dropdown-toggle px-1 me-1',
                        text: 'Edit',
                        buttons: [
                            {
                                text: feather.icons['book-open'].toSvg({ class: 'font-small-4 me-50' }) + 'Add to Classroom',
                                className: 'SelectorToAddClassroom dropdown-item',
                            },
                            {
                                text: feather.icons['trash-2'].toSvg({ class: 'font-small-4 me-50' }) + 'Delete Users',
                                className: 'SelectorToDelete dropdown-item',
                            },
                            {
                                extend: 'excel',
                                text: feather.icons['external-link'].toSvg({ class: 'font-small-4 me-50' }) + 'Export Excel',
                                className: 'dropdown-item',
                                exportOptions: { columns: [1, 2, 10,3,9,12] }
                            },
                            {
                                text: feather.icons['log-out'].toSvg({ class: 'font-small-4 me-50' }) +'Force Logout',
                                className: 'dropdown-item force_selected',

                             },
                            {

                                text: `<img src="/img/scan.png" style="width: 18px"> ` + 'Export Qr',
                                className: 'dropdown-item exportScanner',
                                exportOptions: { columns: [1, 2, 3, 4, 5] }
                            }

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
                        className: 'add-new btn btn-primary px-1 fs-6',
                        attr: {
                            'data-bs-toggle': 'modal',
                            'data-bs-target': '#modal-user'
                        },
                        init: function (api, node, config) {
                            studentId();
                            $(node).removeClass('btn-secondary');
                        }
                    }
                ],

            });



            if ('{{session()->get("page")}}') {
                setTimeout(() => {
                    $('#user_table').DataTable().page(parseInt('{{session()->get("page")}}')).draw("page");
                }, 200);
            }


            $('div.ClassRoom').html(`
                <div class="d-inline col-md- user_plan ">
                    <label class="form-label" for="classroom_id">ClassRoom</label>
                    <select id="classroom_id" class="form-select text-capitalize mb-md-0 mb-2">
                        <option value=""> Select ClassRoom </option>
                        <option value="">All</option>
                        @foreach ( $classrooms as $class )
                            <option value=" {{$class->id}} ">{{ $class->title }}</option>
                        @endforeach
                    </select>
                </div>
                `
            )
            $('div.UserRole').html(`
                <div class="d-inline col-md- user_status">
                    <label class="form-label" for="UserRole">Role</label>
                    <select id="UserRole" class="form-select text-capitalize mb-md-0 mb-2">
                        <option value=""> Select Role </option>
                        @foreach($roles as $role)
                            <option value="{{$role->id}}">{{$role->name}} </option>
                        @endforeach
                    </select>
                </div>
                `
            )
            $(document).on('change','#_changeBlock',function (e) {
                let block
                if ($(this).is(":checked")) {
                     block=true

                }else{
                     block=false

                }
        let student_id=$(this).attr('student-id')
                $.ajax({
                    url: '/admin/changeBlock',
                    type: "POST",
                    data:{'block':block,'student_id':student_id},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function (res) {
                        if(res.status){
                            Swal.fire("Done", "Record delete successful.", "success");

                        }
                        let page = $('#table_page').val()
                        user_table.ajax.reload();
                    },
                    error:function (res) {
                        Swal.fire("Close!", "Something is wrong, Please try again.", "error");
                    }
                });
            });

            $('#checkbox_all').on('click',function (e){
                $('.checkboxStudent').not(this).prop('checked', this.checked);
            })


            var room_student_table,quiz_student_table
            $(document).on('click','#actitvity',function (e) {
                let student_id=$(this).attr('data-id')

                $('#modal-activity').modal('show');
                $('#room_student_table').DataTable().clear().destroy()
                $(".loading").show();

                setTimeout(function () {
                    room_student_table =  $('#room_student_table').DataTable({
                        processing: true,
                        ordering:false,
                        serverSide: true,
                        lengthMenu: [5, 10, 20, 50, 100],
                        pageLength: 10,
                        responsive: true,
                        searching:false,
                        initComplete: function () {
                            console.log('@@@ init complete @@@');
                            $(".loading").hide();
                        },
                        order: [[0, 'desc']],
                        language: {
                            sLengthMenu: 'Show _MENU_',
                        },
                        ajax: {
                            url: "{{route('get-room_student-data-admin')}}",
                            data: function (d) {
                                d.student_id=student_id;
                                d.classroom_id=$('#classroom_id').val();
                                d.grade_id=$('#grade_id').val();
                                d.name=$('#filter_rooms').val();

                            },
                        },
                        columns: [
                            {data: 'readingOrder', className: 'reorder' ,visible: false, searchable: false},

                            {data: 'name' , className: ' text-left' ,
                                render:function (data,type,full){
                                    return `<a class="link" href="#">${data}</a> `} },
                            {data: 'join', name: 'coupon_used', className: ' text-left'  },
                            {data: 'end_date', name: 'end_date', className: ' text-left'  },
                            {data: 'coupon', name: 'coupon', className: ' text-left'  },
                            {data: 'completed', name: 'completed', className: ' text-left'  },
                            {data: 'attendance_status', name: 'attendance_status',
                                render:function (data,type,full){
                                    let status
                                    if(data==1){
                                        status='checked';
                                    }else if(data==0){
                                        status='';
                                    }
                                    return `<div class="form-check form-check-success form-switch">
                                                <input type="checkbox" data-attendance="${full['attendance_id']}" data-room-id="${full['id']}"  data-student-id="${student_id}" id="changeAttendanceStatus" ${status} class="form-check-input" id="customSwitch4">
                                            </div> `}

                            },
                            {data: 'status_val', name: 'status_val', className: ' text-left'  },
                            {data: 'button_coupon' , className: ' text-left' ,
                                render:function (data,type,full){
                                    if(data){
                                        return `
                                        <a class="" id="reset_coupon" data-id="${data}"
                                           data-coupon-id="${data}"
                                           title="Open more days"
                                        >${feather.icons['unlock'].toSvg({ class: 'font-small-4 me-50' })}</a>
                                        `}else{
                                        return ''
                                    }
                                }

                            },
                        ]
                    });
                }, 500);


                $('.quiz_student_table').DataTable().clear().destroy()
                $(".loading").show();
                setTimeout(function () {
                    quiz_student_table =  $('.quiz_student_table').DataTable({
                        processing: true,
                        ordering:false,
                        serverSide: true,
                        lengthMenu: [5, 10, 20, 50, 100],
                        pageLength: 10,
                        responsive: true,
                        searching:false,
                        initComplete: function () {
                            console.log('@@@ init complete @@@');
                            $(".loading").hide();
                        },
                        order: [[0, 'desc']],
                        ajax: {
                            url: "{{route('get-quiz_student-data-admin')}}",
                            data: function (d) {
                                d.student_id=student_id;
                                d.name=$('#filter_exam').val();

                            },
                        },
                        columns: [

                            {data: 'name' , className: ' text-left' ,
                                render:function (data,type,full) {
                                    if (full['enter']) {
                                        return `<a class="link" href="/quiz/${full['id']}/show_answer?student_id=${student_id}">${data}</a> `
                                    }else{
                                        return `<a class="link" href="#">${data}</a> `

                                    }
                                }
                            },

                            {data: 'type', name: 'type', className: ' text-left'  },
                            {data: 'passed', name: 'passed', className: ' text-left'  },
                            {data: 'num_retake', name: 'num_retake', className: ' text-left'  },
                            {data: 'score', name: 'score', className: ' text-left'  },
                            {data: 'id' , className: ' text-left' ,
                                render:function (data,type,full) {
                                    if (full['enter']) {
                                        return `<a target="_blank" class="link margin-small-right" href="/quiz/${full['id']}/show_answer?student_id=${student_id}" tooltip="title: View Answers"> <span icon="eye"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><circle fill="none" stroke="#000" cx="10" cy="10" r="3.45"></circle><path fill="none" stroke="#000" d="m19.5,10c-2.4,3.66-5.26,7-9.5,7h0,0,0c-4.24,0-7.1-3.34-9.49-7C2.89,6.34,5.75,3,9.99,3h0,0,0c4.25,0,7.11,3.34,9.5,7Z"></path></svg></span></a>
                                                    <a class="link" id="reset" data-id="${data}" data-student="${student_id}" tooltip="title: Reset Progress"> <span icon="refresh"></span></a>`

                                    }else{
                                        return ``

                                    }
                                }
                            },
                        ],
                        rowCallback: function (row, data) {
                            if (data.enter) {
                                $(row).prop('title','Entered this exam');
                            }else{
                                $(row).prop('title','Hasn\'t Entered Exam yet!');

                            }
                        }

                    });
                }, 500);
            })

            $(document).on('click','.add-new',function (e){

                $('#modal-user input').val(null)
                $('#modal-user select').val(null).trigger('change')
            })
            $(document).on('click','#changeAttendanceStatus',function (e){
                let room_id=$(this).attr('data-room-id')
                let status=e.target.checked
                let student_id=$(this).attr('data-student-id')
                let attendance_id=$(this).attr('data-attendance')
                $.ajax({
                    url: `/admin/changeAttendanceStatus`,
                    type: "GET",

                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    data: {status: status,room_id:room_id,student_id:student_id,attendance_id:attendance_id},
                    success: function (res) {
                        if(res.status){
                            Swal.fire("Done", "Record Updated successful.", "success");

                        }
                        let page = $('#table_page').val()
                        room_student_table.ajax.reload();
                        page ? $('#student_table').DataTable().page(parseInt(page)).draw("page") :null
                    },
                    error:function (res) {
                        Swal.fire("Close!", "Something is wrong, Please try again.", "error");
                    }
                });
            })

            $(document).on('click','#changeBlock',function (e){
                e.preventDefault();
                var rowID=$(this).attr('data-id');
                let accetp_swal = Swal.fire({
                    title: "Remove suspension ?",
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
                            url: '/admin/UnBlock',
                            type: "GET",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            },
                            data: {id: rowID},
                            success: function (res) {
                                if(res.status){
                                    Swal.fire("Done", "Record delete successful.", "success");

                                }
                                let page = $('#table_page').val()
                                user_table.ajax.reload();
                                page ? $('#student_table').DataTable().page(parseInt(page)).draw("page") :null
                            },
                            error:function (res) {
                                Swal.fire("Close!", "Something is wrong, Please try again.", "error");
                            }
                        });
                    } else {
                        Swal.fire("Close", "Close Success", "error");
                    }
                })

            })

            $(document).on('click','#save_student',function (e){
                e.preventDefault()
                var formdata= {
                    name_id: $('#name_id').val(),
                    first_name: $('#first_name').val(),
                    last_name: $('#last_name').val(),
                    email:$('#email').val(),
                    password:$('#password').val(),
                    grade_id:$('#grade_id').val(),
                    role_id:$('#role_id').val(),
                    instructor_id:$('#instructor_id').val(),
                    type:$('#type').val(),
                    phone:$('#phone').val(),
                    profile_image:$('#profile_image')[0].files[0],
                    parent__name:$('#parent__name').val(),
                    parent__email:$('#parent__email').val(),
                    parent__phone:$('#parent__phone').val(),
                    parent__pass:$('#parent__pass').val(),
                }
                let page = $('#table_page').val()

                $res = create_modal(formdata,'/admin/students','POST','modal-user',user_table)
                addToClassRoom('['+$res+']')
                page ? $('#user_table').DataTable().page(parseInt(page)).draw("page") :null
            });

            function addToClassRoom(res){
                if (res) {
                    $('#studentToAddClassRoom').val(res)
                    $('#classRoomSelectorToAdd').val(null).trigger("change")
                    $('#addToClassRoomModal').modal('show')
                }
            }

            $(document).on('click','.tableAddToClassroom',function (e){
                e.preventDefault()
                var select = $(this).attr('data-id');
                var res = '[' +select+ ']'

                addToClassRoom(res)
            })
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




            function  load_more(page,id){
                $.ajax({
                    url: `/admin/getPoint/${id}?page=${page}`,
                    type: "get",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    beforeSend: function() {
                        $('.loader').show();
                    },
                    success: function (res) {
                        if(res.status){
                            if(res.data.length>0){
                                // $('.list-group').empty();
                                let html='';
                                res.data.map((item)=>{
                                    html+=`<li class="list-group-item d-flex align-items-center">
                                 <span> ${item.name} ... <small style="color: darkred;font-weight: bold">${item.point??''}</small></span>
                                 <span class="badge bg-primary rounded-pill ms-auto">${item.value}</span>
                             </li>
                           `;
                                })
                                $('.list-group').append(html);
                                $('.total').text(res.sum);
                                $('#modal-grade-book').modal('show')
                            }else {
                                Swal.fire("warning!", "No Grade book yet.", "warning");
                            }



                        }
                    },
                    error:function (res) {
                        Swal.fire("Close!", "Something is wrong, Please try again.", "error");
                    }
                });
            }

            $(document).on('click','#grade_book',function (e){
                var page = 1;
                e.preventDefault()
                let id =$(this).attr('data-id')
                load_more(page,id);
               //track user scroll as page number, right now page number is 1
                $('.grade-area').on('scroll',function() { //detect page scroll
                    if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) { //if user scrolled from top to bottom of the page

                        page++; //page number increment
                        load_more(page,id); //load content
                    }
                });

            })

            $(document).on('click','.SelectorToAddClassroom',function (e){
                e.preventDefault()

                var select = '';

                $('.checkboxStudent').each(function (loop) {
                    this.checked ?
                        select +=  (select ? ',' :'') + $(this).val()
                    :null;
                });

                var res = '[' +select+ ']'
                addToClassRoom(res)

            })

            $(document).on('click','#save_class_student',function (e){
                e.preventDefault()
                var formdata= {
                    user_ids:    $('#studentToAddClassRoom').val(),
                    classroom:   $('#classRoomSelectorToAdd').val(),
                }

                let page = $('#table_page').val()

                create_modal(formdata,'{{route("admin.add_classroom_to_student")}}','POST','addToClassRoomModal',user_table)

                page ? $('#user_table').DataTable().page(parseInt(page)).draw("page") :null
            });

            $(document).on('click','#delete_row',function (e){
                e.preventDefault();
                var rowID=$(this).attr('data-id');
                let url = `/admin/students/${rowID}`
                deleteRow(rowID,url)

            })

            $(document).on('click','.SelectorToDelete',function (e){
                e.preventDefault();
                var selectids = [];
                $('.checkboxStudent').each(function (loop) {
                    this.checked ? selectids.push($(this).val()) :null;
                });
                if(selectids.length>0){
                    var url='/admin/delete-all-student'
                    deleteRow(selectids,url)
                }else{
                    Swal.fire("warning!", "Please Select Room First.", "warning");
                }

            });

            $(document).on('click','.force_selected',function (e){
                e.preventDefault();
                var selectids = [];
                $('.checkboxStudent').each(function (loop) {
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
            $(document).on('click','.exportScanner',function (e){
                e.preventDefault();
                var selectids = [];
                $('.checkboxStudent').each(function (loop) {
                    this.checked ? selectids.push({qrcode:$(this).attr('data-qr-code'),name:$(this).attr('data-name'),nameId:$(this).attr('data-name-id')}) :null;
                });
                if(selectids.length>0){
                    $('#scanner').empty()
                }else{
                    $('.checkboxStudent').each(function (loop) {
                        !this.checked ? selectids.push({qrcode:$(this).attr('data-qr-code'),name:$(this).attr('data-name'),nameId:$(this).attr('data-name-id')}) :null;
                    });
                }
                selectids.map(function (item){
                    $('#scanner').append(
                        `
                         <div class="row">
                            <div class="col-3" style="margin-bottom: 60px">
                            <h4>${item.name} - ${item.nameId} </h4>
                                    ${item.qrcode}
                            </div>
                         </div>

`)
                    printJS({ printable: 'scanner', type: 'html',documentTitle:'Qr Code',repeatTableHeader:true,maxWidth:'700' })

                })
                $('#scanner').empty()

            });

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
                                let page = $('#table_page').val()
                                user_table.ajax.reload();
                                page ? $('#user_table').DataTable().page(parseInt(page)).draw("page") :null
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


            $('.grade_id_filter_remove').on('click',function (){
                $('.grade_id_filter_top_click').css({backgroundColor: ''});
                $('#grade_id_filter').val(null)
                $('#grade_id_filter_top').val(null)
                user_table.ajax.reload();
            });


            $('.grade_id_filter_top_click').on('click',function (){

                $('.grade_id_filter_top_click').css({backgroundColor: ''})
                $(this).css({backgroundColor: 'rgba(202, 37, 43, 0.16)'});

                $('#grade_id_filter_top').val($(this).data('grade-id'))

                user_table.ajax.reload();
            });
            $('#filter_rooms').on('keyup',function (){
                room_student_table.ajax.reload();
            });

            $('#filter_exam').on('keyup',function (){
                quiz_student_table.ajax.reload();
            });
            $(document).on('click','#reset_coupon',function (e){
                let couponId=$(this).attr('data-coupon-id')
                let html=`
                     <div>
                               <label>Day: </label>
                                <input type="number" value="" id="day" class="form-control"  name="day" placeholder="Enter Number Of Days">
                            </div>

                    `;
                let accetp_swal = Swal.fire({
                    title: " Open this room for more days ?",
                    text: "",
                    html:html,
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
                            url: `/admin/resetCoupon`,
                            type: "post",
                            data:{couponId:couponId,day:$("#day").val()},
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            },
                            success: function (res) {
                                Swal.fire("success!", "change success", "success");

                            },
                            error:function (res) {
                                Swal.fire("Close!", res.responseJSON.message, "warning");
                            }
                        });
                    } else {
                        Swal.fire("Close", "Close Success", "error");
                    }
                })
            })


            $('#grade_id_filter').on('change',function (){
                $('#grade_id_filter_top').val($(this).val())
                user_table.ajax.reload();
            });


            $('#classroom_id').on('change',function (){
                user_table.ajax.reload();
            });
            $('#UserRole').on('change',function (){
                user_table.ajax.reload();
            });
            $(document).on('click','#delete_device',function (){
                let index = $(this).attr('data-id');
                let student_id = $(this).attr('data-student');
                $.ajax({
                    url: '/admin/delete-device',
                    type: "GET",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    data: {index: index,student_id:student_id},
                    success: function (res) {
                        if(res.status){
                            user_table.ajax.reload();
                            Swal.fire("Done", "Record delete successful.", "success");

                        }
                        let page = $('#table_page').val()
                        user_table.ajax.reload();
                        page ? $('#user_table').DataTable().page(parseInt(page)).draw("page") :null
                        $('#modal-student-ip').modal('hide')

                    },
                    error:function (res) {
                        Swal.fire("Close!", "Something is wrong, Please try again.", "error");
                    }
                });
            });
            $(document).on('click','.modal_ip',function (){

                let ips= JSON.parse($(this).attr('data-ip'));
                let student= JSON.parse($(this).attr('data-student'));
                if(ips.length==0){
                    $('#ips').empty();
                    $('#ips').append(`
                        <p> Not Found Device<p>
                    `);
                }else{
                    let arr=[]
                    ips.map((i,key)=>{
                        $('#ips').empty();
                        arr+=`<li>
                           <div>
                            <span>${i}</span>
                           <a style="float: right" data-student="${student}" data-id="${key}" id="delete_device">${feather.icons['delete'].toSvg({ class: 'me-50' })}</i></a>
                           </div>
                        </li>`


                    })
                    $('#ips').append(arr);
                }

                $('#authLogsTable').DataTable().destroy();
                $('#authLogsTable').DataTable({
                    ordering:false,
                    searching:false,
                    paging: false,

                    ajax: {
                        url: "{{route('get_students_auth_logs')}}?user_id="+student,
                    },
                    columns: [
                        {data: 'ip_address', name: 'ip_address', className: ' text-left'  },
                        {data: 'browser', name: 'browser', className: ' text-left'  },
                        {data: 'os_platform', name: 'os_platform', className: ' text-left'  },
                        {data: 'login', name: 'login', className: ' text-left'  },
                        {data: 'logout', name: 'logout', className: ' text-left'  },
                    ],

                });

                $('#modal-student-ip').modal('show')

            });
            $(document).on('click','.modal_parent',function (){

                let name    = $(this).attr('data-name');
                let name_id = $(this).attr('data-nameid');
                // let id    = $(this).attr('data-pass');

                $('#parentData').empty();
                arr= `<li>
                        <div class="my-2">
                            Name :      ${name}
                        </div>
                    </li>
                    <hr>
                    <li>
                        <div class="my-2">
                            Name_id :   ${name_id}
                        </div>
                    </li>
                    <hr>
                    <li>
                        <div class="my-2">
                            Pass :      <span id="passParent">******</span>
                            <a class="ml-5" id="showPassPerant">${feather.icons['eye'].toSvg({ class: 'me' })}</a>
                        </div>
                    </li>`
                $('#parentData').append(arr);

                $('#modal-student-parent').modal('show')

                $('#showPassPerant').click(function(){
                    $('#passParent').empty();
                    $.ajax({
                        url: '{{route("parent.pass")}}',
                        type: "Post",
                        data: {name_id: name_id},
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        },
                        success: function (res) {
                            $('#passParent').append(res.data);
                            $('#showPassPerant').hide();
                        },
                        error:function (res) {
                            Swal.fire("Close!", "Something is wrong, Please try again.", "error");
                        }
                    });
                })

            });

        });

        $('#setIdStudent').click(function(){
            studentId();
        })
         $(document).on('click','#printer',function (e){
             $('#scanner').html($(this).attr('data-qrcode'))
             printJS({ printable: 'scanner', type: 'html', header: `${$(this).attr('data-name')} - ${$(this).attr('data-name-id')}` })

         })
        function studentId(){
            if($('#setIdStudent').is(':checked')){
                $('#name_id').removeClass('hidden');
            }else{
                $('#name_id').addClass('hidden');
            }
        }

        $('#classRoomSelectorInstructor').on('change', function(){
            $('#classRoomSelectorToAdd').empty()
            $.ajax({
                url: '{{route("admin.get_classroom_from_instructors",'')}}/'+$(this).val(),
                type: "get",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                success: function (res) {
                    res.data.forEach(data => {
                        $('#classRoomSelectorToAdd').append(
                            `<option value="${data.id}">${data.title}</option>`
                        );
                    });
                },
                error:function (res) {
                    Swal.fire("Close!", "Something is wrong, Please try again.", "error");
                }
            });
        })

    </script>
@endsection
