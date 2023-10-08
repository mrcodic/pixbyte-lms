@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Attendance List')

@section('vendor-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset('admin/vendors/css/forms/select/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/vendors/css/tables/datatable/responsive.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/vendors/css/tables/datatable/buttons.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/vendors/css/tables/datatable/rowGroup.bootstrap5.min.css') }}">
    <style>
        .student{
            background: #3c9bb9 !important;
        }
        .success{
            background: #388d6c !important;

        }
        .badge{
            cursor: pointer;
        }
    </style>
@endsection

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset('admin/css/base/plugins/forms/form-validation.css') }}">
@endsection

@section('content')
    <!-- users list start -->
    <section class="app-user-list">
        @if(request('room_id'))
        <div class="row">
            <div class="col-lg-3 col-sm-6">
                <div class="card cursor-pointer grade_id_filter_remove" id="totalFilter">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <h3 class="fw-bolder mb-75" id="totalAttendance">{{count($totalAttendance)}}</h3>
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
            <div class="col-lg-3 col-sm-6">
                <div class="card cursor-pointer grade_id_filter_remove" id="presentFilter">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <h3 id="totalPresent" class="fw-bolder mb-75">{{count($totalPresent)}}</h3>
                            <span>Present</span>
                        </div>
                        <div class="avatar bg-light-primary p-50">
                            <span class="avatar-content">
                            <i data-feather="check" class="font-medium-4"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="card cursor-pointer grade_id_filter_remove" id="absenteesFilter">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <h3 class="fw-bolder mb-75" id="totalabsent">{{count($totalabsent)}}</h3>
                            <span>Absent</span>
                        </div>
                        <div class="avatar bg-light-primary p-50">
                            <span class="avatar-content">
                            <i data-feather="x-circle"  class="font-medium-4"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="card cursor-pointer grade_id_filter_remove" id="newstudentFilter">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <h3 class="fw-bolder mb-75" id="totalnewstudent">{{count($totalnewstudent)}}</h3>
                            <span>New student</span>
                        </div>
                        <div class="avatar bg-light-primary p-50">
                            <span class="avatar-content">
                            <i data-feather="user-plus" class="font-medium-4"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <!-- list and filter start -->
        <div class="card">
            <div class="card-body border-bottom">
                <div class="row">
                    <div class="col-lg-3 col-sm-3">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <select class="form-select text-capitalize mb-md-0 mb-2" id="instructor_id" name="instructor">
                                    <option selected disabled>Select Instructor</option>
                                    @foreach ( $instructors as $instructor )
                                        <option value="{{$instructor->id}}" @if(request()->instructor==$instructor->id) selected @endif >{{ $instructor->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                    </div>
                    <div class="col-lg-3 col-sm-3">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <select class="form-select text-capitalize mb-md-0 mb-2" id="classroom_id" name="classroom">
                                    <option selected disabled>Select Classroom</option>
                                    <option value="">all</option>
                                </select>
                            </div>
                    </div>
                    <div class="col-lg-3 col-sm-3">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <select class="form-select text-capitalize mb-md-0 mb-2" id="room_id" name="room">
                                    <option selected disabled>Select room</option>
                                    <option value="">all</option>
                                </select>
                            </div>
                    </div>
                    <div class="col-lg-3 col-sm-3">
                            <div class="card-body d-flex align-items-center justify-content-between">

                                @if(request()->attendance_type=='room')
                                    <input type="checkbox" class="uk-checkbox" @if(request()->type==1) checked @endif title="2 day room" name="2dayRoom"  id="2dayRoom">
                                @endif
                                <a href="#" id="save"  onclick="return false;" class="dt-button add-new btn btn-success">Submit</a>
                                @if(request()->classroom)
                                    {{--                            <a href="#" id="use_scan"  onclick="return false;" class="dt-button add-new btn btn-primary">Use Scan</a>--}}
                                @endif
                                <a href="#" id="reset" class="dt-button add-new btn btn-danger">Rest</a>
                            </div>
                    </div>

                </div>

                <h4 class="card-title">Search & Filter</h4>
                <div class="row">
                    @if(request()->type!=1)
                        <div class="col-3 ">
                            <select class="form-select" id="bulk_status" name="bulk_status">
                                <option selected disabled>Select Status........</option>
                                <option value="1">Present</option>
                                <option value="0">Absent</option>
                            </select>
                        </div>
                        <div class="col-3">
                            <a class="edit btn btn-primary" id="bulk_status_apply" >Apply</a>
                        </div>
                    @endif
                    <div class="col-3">
                        <a class="delete  btn btn-outline-danger waves-effect" id="collect_absence" >Collect Absences</a>
                    </div>
{{--                    <div class="col-md-4 user_role">--}}
{{--                        <label class="form-label" for="UserRole">Grade</label>--}}
{{--                        <select id="grade_id_filter" class="form-select text-capitalize mb-md-0 mb-2">--}}
{{--                            <option value=""> Select Grade </option>--}}
{{--                            @foreach ( $grades as $grade )--}}
{{--                                <option value=" {{$grade->id}} ">{{ $grade->name }}</option>--}}
{{--                            @endforeach--}}
{{--                        </select>--}}
{{--                    </div>--}}
{{--                    <div class="col-md-4 user_plan">--}}

{{--                        <label class="form-label" for="UserRole">ClassRoom</label>--}}
{{--                        <select id="classroom_id" class="form-select text-capitalize mb-md-0 mb-2">--}}
{{--                            <option value=""> Select ClassRoom </option>--}}
{{--                            <option value="">All</option>--}}
{{--                            @foreach ( $classrooms as $class )--}}
{{--                                <option value=" {{$class->id}} ">{{ $class->title }}</option>--}}
{{--                            @endforeach--}}
{{--                        </select>--}}
{{--                    </div>--}}
{{--                    <div class="col-md-4 user_status"><label class="form-label" for="UserRole">Role</label>--}}
{{--                        <select id="UserRole" class="form-select text-capitalize mb-md-0 mb-2">--}}
{{--                            <option value=""> Select Role </option>--}}
{{--                            @foreach($roles as $role)--}}
{{--                              <option value="{{$role->id}}">{{$role->name}} </option>--}}
{{--                            @endforeach--}}

{{--                        </select>--}}
{{--                    </div>--}}
                </div>
            </div>
            <div class="card-datatable table-responsive pt-0">
                <table class="user-list-table table" id="user_table">
                    <input type="hidden" name="student_id" id="student_id" value="">
                    <input type="hidden" name="status" id="status" value="">
                    <input type="hidden" name="room" id="room" value="{{request()->room_id}}">
                    <input type="hidden" name="classroom" id="classroom" value="{{request()->classroom}}">
                    <input type="hidden" name="instructor" id="instructor" value="{{request()->instructor}}">
                    <input type="hidden" name="select_all" id="select_all" value="">
                    <input type="hidden" name="filter" id="filters" value="">
{{--                    @include('components.search-datatable',['id'=>'filter_attendance'])--}}
                    <thead class="table-light">
                    <tr>
                        <th class="uk-table-shrink"><input id="checkbox_all" class="uk-checkbox" type="checkbox"></th>
                        <th class="">User ID</th>
                        <th class="">Name</th>
                        <th class="">Status</th>
                        @if(request()->type && request()->type==1)
                            <th class="">1s Time</th>
                            <th class="">2nd Time</th>
                        @endif
                        <th class="">Reason</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
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
                    url: "{{route('admin.get-student-attendance-data')}}",
                    data: function (d) {
                        d.classroom={{request()->classroom??0}};
                        d.room={{request()->room_id??0}};
                        d.attendance_type="{{request()->attendance_type??'room'}}";
                        d.status=$('#filters').val();
                        d.name=$('#filter_attendance').val();
                        d.instructor={{request()->instructor??0}};
                    },
                },
                columns: [

                    {
                        data:'id',orderable: false, render:function (data,type,full){
                            return `<input class="uk-checkbox checkboxStudents" id="checkbox" value="${data}" type="checkbox">`;}
                    },
                    {data: 'name_id', name: 'name_id', className: ' uk-text-left'  },

                    {data: 'name' , className: ' uk-text-left' ,
                        render:function (data,type,full){
                            return `<a class="link" href="/u/${full['name_id']}">${data}</a> `} },
                    {
                        data: 'status', name: 'status', className: ' uk-text-left',render:function (data,type,full){
                            var present,absent,left_with_excuse,present_but_absent,absent_with;
                            if(full['status']=='Present'){
                                present='badge bg-success ';
                            }
                            else if(full['status']=='Absent'){
                                absent='badge bg-success';
                            }
                            else if(full['status']=='Absent with excuse'){
                                absent_with='badge bg-success'
                            }
                            else if(full['status']=='left/leave early with justification'){
                                left_with_excuse='badge bg-success'
                            }
                            else if(full['status']=='New student'){
                                present_but_absent='badge bg-info'
                            }
                            console.log(present,absent,absent_with)
                            if('{{request()->type==1}}'){

                                return `<div class='status'>
                                        <span class="badge bg-secondary ${absent}" disabled  data-value="0" data-id="${full['id']}">Absent</span>
                                        <span class="badge bg-secondary ${present}"  disabled data-value="1" data-id="${full['id']}">Present</span>
                                            <span class="badge bg-secondary ${absent_with}"  disabled data-value="2" data-id="${full['id']}">Absent With excuse</span>
                                            <span class="badge bg-secondary ${left_with_excuse}" disabled  data-value="3" data-id="${full['id']}">left early with justification</span>
                                            <span class="badge bg-secondary ${present_but_absent}"  disabled data-value="4" data-id="${full['id']}">New student</span>
                                        </div>
                                    `
                            }else{
                                return `<div class='status'>
                                        <span class="badge bg-secondary ${absent}"  id="changeStatus" data-value="0" data-id="${full['id']}">Absent</span>
                                        <span class="badge bg-secondary ${present}"  id="changeStatus" data-value="1" data-id="${full['id']}">Present</span>
                                            <span class="badge bg-secondary ${absent_with}" id="changeStatus" data-value="2" data-id="${full['id']}">Absent With excuse</span>
                                            <span class="badge bg-secondary ${left_with_excuse}" id="changeStatus"  data-value="3" data-id="${full['id']}">left early with justification</span>
                                            <span class="badge bg-secondary ${present_but_absent}" id="changeStatus" data-value="4" data-id="${full['id']}">New student</span>
                                        </div>
                                    `
                            }


                        }
                    },
                        @if(request()->type && request()->type==1)
                    {data:'first_attendance',name:'first_attendance',render:function (data,type,full){
                            if(data==1 ){
                                let check
                                check='checked';

                                return `<input class="uk-checkbox" ${check} id="first_present" data-student="${full['id']}" value="${data}" type="checkbox">`
                            }else{

                                if(full['status']==null)
                                    return `<input class="uk-checkbox" id="first_present" data-student="${full['id']}" value="${data}" type="checkbox">`
                                else
                                    return `<input class="uk-checkbox" id="first_present" data-student="${full['id']}" value="${data}" type="checkbox">`


                            }

                        }
                    },
                    {data:'second_attendance',name:'second_attendance',render:function (data,type,full){
                            if(data==1 ){
                                let check
                                check='checked';

                                return `<input class="uk-checkbox" ${check}  id="second_present" data-student="${full['id']}" value="${data}" type="checkbox">`
                            }else{
                                if(full['status']==null)
                                    return `<input class="uk-checkbox" id="second_present" data-student="${full['id']}" value="${data}" type="checkbox">`
                                else
                                    return `<input class="uk-checkbox" id="second_present" data-student="${full['id']}" value="${data}" type="checkbox">`
                            }

                        },
                    },
                        @endif
                    {data: 'reason', name: 'reason', className: ' uk-text-left'}
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
                ],

            });


            $(document).on('change','#classroom_id',function (e){
                e.preventDefault();
                let classroom= $(this).val();
                let attendance_type="{{request('attendance_type')}}"
                getRooms(classroom,attendance_type);
            });
            @if(request()->has('classroom'))
            getRooms({{request()->classroom}},"{{request('attendance_type')}}");
            getInstructor({{request()->instructor}});

            $(document).on('change','#first_present',function (e){
                let id=$(this).attr('data-student');
                let checked = $(this).is(':checked') ;
                // if(e.target.checked){
                $.ajax({
                    url: `/admin/make_first_attendance/?classroom={{request()->classroom}}&room={{request()->room_id}}&student=${id}&status=${checked}`,
                    type: "get",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function (res) {
                        if(res.status){
                            let page = $('#table_page').val()
                            user_table.ajax.reload()
                            page ? $('#user_table').DataTable().page(parseInt(page)).draw("page") :null
                        }
                    },
                    error:function (res) {
                        Swal.fire("Close!", "Something is wrong, Please try again.", "error");
                    }
                });
                // }
            })
            $(document).on('change','#second_present',function (e){
                let id=$(this).attr('data-student');
                let checked = $(this).is(':checked') ;
                // if(e.target.checked){
                $.ajax({
                    url: `/admin/make_second_attendance/?classroom={{request()->classroom}}&room={{request()->room_id}}&&student=${id}&status=${checked}`,
                    type: "get",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function (res) {
                        if(res.status){
                            let page = $('#table_page').val()
                            user_table.ajax.reload()
                            page ? $('#user_table').DataTable().page(parseInt(page)).draw("page") :null

                        }
                    },
                    error:function (res) {
                        Swal.fire("Close!", "Something is wrong, Please try again.", "error");
                    }
                });
                // }
            })
            $('#collect_absence').on('click',function (e){
                e.preventDefault();
                let accetp_swal = Swal.fire({
                    title: " Are You Sure To Change ?",
                    text: "",
                    html:`
                           <div class="uk-width-expand uk-margin-top">
                            <div class="uk-form-controls">
                                <span class="dark-font">Total students:<span class="uk-badge"  >${$('#totalAttendance').text()}</span></span>
                                <span class="dark-font">Present:<span class="uk-badge"  >${$('#totalPresent').text()}</span></span>
                            </div>
                        </div>
                        `,
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
                            url: `/admin/collect_absence`,
                            type: "get",
                            data:{instructor:{{request('instructor')}},room_id: {{request()->room_id}},classroom_id:{{request()->classroom}}, type: {{request()->type}}},
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            },
                            success: function (res) {
                                if(res.status){
                                    Swal.fire("Done","change Success", "success");
                                    $('#totalAttendance').text(res.totalAttendance)
                                    $('#totalPresent').text(res.totalPresent)
                                    $('#totalabsent').text(res.totalabsent)
                                    $('#totalnewstudent').text(res.totalnewstudent)
                                    let page = $('#table_page').val()
                                    user_table.ajax.reload();
                                    page ? $('#user_table').DataTable().page(parseInt(page)).draw("page") :null
                                }

                            },
                            error:function (res) {
                                Swal.fire("Close!", "Something is wrong, Please try again.", "error");
                            }
                        });
                    } else {
                        Swal.fire("Close", "Close Success", "error");
                    }
                })

            });
            $(document).on('click','#reset',function (e){
                var attendance_type="{{request('attendance_type')??'0'}}";

                window.location.href=`/admin/attendance/?attendance_type=${attendance_type}`;

            });
            @else
            $(document).on('click','#save',function (e){
                e.preventDefault()
                let type=0
                let instructor =$('#instructor_id').val()
                let class_room =$('#classroom_id').val()
                let room_id=$("#room_id").val();
                let attendance_type="{{request()->attendance_type}}";
                @if(request()->attendance_type=='room')
                    type=document.getElementById('2dayRoom').checked?1:0;
                @endif
                $.ajax({
                    url: `/admin/make_room_check`,
                    type: "get",
                    data:{"instructor_id":instructor,"classroom":class_room,"room_id":room_id,"type":type},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function (res) {
                        if(res.status){
                            if(res.type==1){
                                window.location.href=`/admin/attendance?instructor=${instructor}&classroom=${class_room}&room_id=${room_id}&type=1&attendance_type=${attendance_type}`;
                            }else{
                                window.location.href=`/admin/attendance?instructor=${instructor}&classroom=${class_room}&room_id=${room_id}&type=0&attendance_type=${attendance_type}`;

                            }

                        }
                    },
                    error:function (res) {
                        Swal.fire("Close!", "Something is wrong, Please try again.", "error");
                    }
                });
            })
            @endif

            $('#checkbox_all').on('click',function (e){
                $('input:checkbox').not(this).prop('checked', this.checked);
            })


            $(document).on('change','#classroom_id',function (e){
                e.preventDefault();
                let classroom= $(this).val();
                let attendance_type="{{request('attendance_type')}}"
                getRooms(classroom,attendance_type);
            });
            $(document).on('click','#changeStatus',function (e) {
                e.preventDefault();
                var rowID = $(this).attr('data-id');
                var changeStatus = $(this).attr('data-value');
                $('#student_id').val(rowID);
                $('#status').val(changeStatus);
                // UIkit.modal('#modal-changeStatus').show();
                if (changeStatus == "2" || changeStatus == "3") {

                    ChangeRequest(null, changeStatus);

                }
                else if(changeStatus=='1'){
                    acceptChangeStatus(changeStatus)
                }else{
                    ChangeRequest();
                }
            })
            $(document).on('click','#bulk_status_apply',function (e) {

                e.preventDefault();
                $("#status").val( $('#bulk_status').val())
                var checkboxStudents = $(".checkboxStudents:checked").map(function(){
                    return $(this).val();
                }).get();
                if(checkboxStudents.length>0){
                    $("#student_id").val( JSON.stringify(checkboxStudents))

                    acceptChangeStatus(null, true)
                }else{
                    Swal.fire("warning!", "Please select student", "warning");
                }


            })
            function ChangeRequest(id,status){

                let html;
                if(status=='2' || status=='3'){
                    html=`
                            <div>
                               <label>Comment: </label>
                                <input id="comments" class="uk-input" style="width: 264px;color:black" name="comment" placeholder="enter excuse">

                            </div>
                        `
                }
                let accetp_swal = Swal.fire({
                    title: " Are You Sure To Change ?",
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
                        acceptChangeStatus()
                    } else {
                        Swal.fire("Close", "Close Success", "error");
                    }
                })


            }

            function  acceptChangeStatus(status=null, select_all=false,name_id=false){
                let type={{request('type')??'0'}};
                var attendance_type="{{request('attendance_type')??'0'}}";
                $.ajax({
                    url: `/admin/changeStatus`,
                    type: "post",
                    data:{instructor:$('#instructor').val(),student_id:$("#student_id").val(),room_id:$("#room").val(),type:type,attendance_type:attendance_type,classroom_id:$("#classroom").val(),comment:$("#comments").val(),status:$("#status").val(),select_all:select_all,name_id:name_id},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function (res) {
                        if(res.status){
                            if(status!=='1'){
                                toastr.success("change Success");
                            }
                            // UIkit.modal('#modal-changeStatus').hide();
                            let page = $('#table_page').val()
                            user_table.ajax.reload();
                            page ? $('#user_table').DataTable().page(parseInt(page)).draw("page") :null
                            $('#totalAttendance').text(res.totalAttendance)
                            $('#totalPresent').text(res.totalPresent)
                            $('#totalabsent').text(res.totalabsent)
                            $('#totalnewstudent').text(res.totalnewstudent)
                        }
                    },
                    error:function (res) {
                        toastr.warning(res.responseJSON.message);
                        //  Swal.fire("Close!", "Something is wrong, Please try again.", "error");
                        //  alert(res.errorMessage);
                    }
                });
            }
            $(document).on('change','#instructor_id',function (e){

                e.preventDefault();
                let instructor= $(this).val();
                getInstructor(instructor);
            });
            function getRooms(classroom,attendance_type){
                let url;
                if(attendance_type =='room'){
                    url='admin/get_room_with_class';
                }else{
                    url='admin/get_quiz_with_class';
                }
                $.ajax({
                    url: `/${url}/${classroom}`,
                    type: "get",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function (res) {
                        if(res.status){
                            $('#room_id').empty()
                            $('#room_id').append('<option readonly selected disabled>Select........</option>')
                            res.data.forEach((item)=>{
                                let items='';
                                if(item.id == {{request()->room_id??'0'}}){
                                    items='selected'
                                }
                                $('#room_id').append(`
                                    <option value="${item.id}" ${items}>${item.title}</option>
                                    `)
                            });

                        }
                    },
                    error:function (res) {
                        Swal.fire("Close!", "Something is wrong, Please try again.", "error");
                    }
                });
            }

            function getInstructor(instructor){


                $.ajax({
                    url: `/admin/get_instructor_with_class/${instructor}`,
                    type: "get",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function (res) {
                        if(res.status){
                            $('#classroom_id').empty()
                            $('#classroom_id').append('<option readonly selected disabled>Select........</option>')
                            res.data.forEach((item)=>{
                                let items='';
                                if(item.id == {{request()->classroom??'0'}}){
                                    items='selected'
                                }
                                $('#classroom_id').append(`
                                    <option value="${item.id}" ${items}>${item.title}</option>
                                    `)
                            });

                        }
                    },
                    error:function (res) {
                        Swal.fire("Close!", "Something is wrong, Please try again.", "error");
                    }
                });
            }



            $(document).on('click','#totalFilter',function (e){
                $('#filters').val('')
                //  let page = $('#table_page').val()
                user_table.ajax.reload()
                //  page ? $('#user_table').DataTable().page(parseInt(page)).draw("page") :null
            });
            $(document).on('click','#presentFilter',function (e){
                $('#filters').val(1)
                // let page = $('#table_page').val()
                user_table.ajax.reload()
                // page ? $('#user_table').DataTable().page(parseInt(page)).draw("page") :null
            });
            $(document).on('click','#absenteesFilter',function (e){
                $('#filters').val(0)
                // let page = $('#table_page').val()
                user_table.ajax.reload()
                // page ? $('#user_table').DataTable().page(parseInt(page)).draw("page") :null
            });
            $(document).on('click','#newstudentFilter',function (e){
                $('#filters').val(4)
                // let page = $('#table_page').val()
                user_table.ajax.reload()
                // page ? $('#user_table').DataTable().page(parseInt(page)).draw("page") :null
            });


        });
    </script>
@endsection
