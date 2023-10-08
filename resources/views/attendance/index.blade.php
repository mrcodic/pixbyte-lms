@extends('layouts.app')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/rowreorder/1.2.8/css/rowReorder.dataTables.min.css" />
  <style>
    .student{
        background: #3c9bb9;
    }
  </style>
@endsection
@section('title', 'Attendance')


@section('body')

    <div class="wrapper-page-light f-height">
        {{-- Instructor sidebar --}}
        <x-instructor-sidebar />
        <!-- container header -->
        <div class="header-wrap page-dark">
            <div class="uk-container uk-container-expand rm-expand">
                <!-- navbar -->
                @include('layouts.navigation')
                <div class="page-header uk-margin-medium-top uk-margin-medium-bottom breadcrumb" uk-grid>
                    <div class="uk-width-expand">
                        <h3 class="uk-margin-remove-bottom title-add">{{request()->attendance_type=='quiz'?'Exam Attendance':'Room Attendance'}}</h3>
                        {{--        @if(!request()->has('classroom') &&!request()->has('room_id'))--}}
                        {{--@endif--}}
                    </div>

                    <div class="uk-overflow-auto x-scrollbar uk-width-2-3@m uk-width-1-1@s select-boxes-container-small">
                        <div class="uk-margin-small-right inline-block left uk-width-1-4 uk-margin-left">
                            <select class="uk-select uk-width-1-1" id="classroom_id" name="classroom_id">
                                <option selected disabled>Select Classroom</option>
                                <option value="">all</option>
                                @foreach ( $classes as $class )
                                    <option value="{{$class->id}}" @if(request()->classroom==$class->id) selected @endif >{{ $class->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="uk-margin-small-right inline-block left uk-width-1-4">
                            <select class="uk-select uk-width-1-1" id="room_id" name="room_id">
                                <option selected disabled>Select {{request()->attendance_type=='quiz'?'Exam':request()->attendance_type}}</option>
                                <option value="">all</option>
                            </select>
                        </div>
                        <div class="uk-margin-small-right inline-block left uk-width-auto">
                            @if(request()->attendance_type=='room')
                            <input type="checkbox" class="uk-checkbox" @if(request()->type==1) checked @endif title="2 day room" name="2dayRoom"  id="2dayRoom">
                            @endif
                            <a href="#" id="save"  onclick="return false;" class="uk-button uk-button-primary border-radius uk-padding-remove-t-b ">Submit</a>

                            <a href="#" id="reset" class="uk-button border-radius uk-padding-remove-t-b ">Rest</a>
                            @if(request()->classroom)
                            <a href="#" id="use_scan"  onclick="return false;" class="qr-icon-scann hidden-large"><img src="{{ asset('img/scan.png') }}" alt="scanner"/></a>
                            @endif
                        </div>
                    </div>

                    <div class="line divider"></div>
                </div>


            </div>

        </div>



{{--        @if(request()->has('classroom') && request()->has('room_id'))--}}

        <div class="uk-container uk-container-expand rm-expand uk-margin-medium-top uk-margin-left" id="attendance" >
            <div class="uk-width-expand uk-margin-top">
                {{-- @if(request()->type==1) --}}
                <div class="uk-form-controls">
                    <a class="delete uk-margin-small-right" id="collect_absence" >Collect Absences</a>
                </div>
                {{-- @endif --}}
            </div>
            @if(request()->classroom)
            <div class="uk-width-expand uk-margin-top">
                <div class="uk-form-controls">
                    <a class="dark-font uk-button uk-button-default uk-button-small border-radius draft light-borders stats-attendance" id="totalFilter"><img class="hidden-large" src="{{asset('img/classmates.png')}}" alt="total-students"/><span class="hidden-small">Total students:</span> <span class="uk-badge"  id="totalAttendance">{{count($totalAttendance)}}</span></a>
                    <a class="dark-font uk-button uk-button-default uk-button-small border-radius draft light-borders stats-attendance" id="presentFilter"><img class="hidden-large" src="{{asset('img/present-user.png')}}" alt="total-students"/><span class="hidden-small">Present: </span><span class="uk-badge"  id="totalPresent">{{count($totalPresent)}}</span></a>
                    <a class="dark-font uk-button uk-button-default uk-button-small border-radius draft light-borders stats-attendance" id="absenteesFilter"><img class="hidden-large" src="{{asset('img/missing.png')}}" alt="total-students"/><span class="hidden-small">Absentees: </span><span class="uk-badge" id="totalabsent" >{{count($totalabsent)}}</span></a>
                    <a class="dark-font uk-button uk-button-default uk-button-small border-radius draft light-borders stats-attendance" id="newstudentFilter"><img class="hidden-large" src="{{asset('img/new-user.png')}}" alt="total-students"/><span class="hidden-small">New student: </span><span class="uk-badge" id="totalnewstudent" >{{count($totalnewstudent)}}</span></a>
                </div>
            </div>
            @endif
                <div class="uk-overflow-auto uk-margin-medium-top x-scrollbar mt-s-20">
                    <input type="hidden" id="table_page" value="">
                    <div>
                    @if(request()->type!=1)
                        <div class="uk-margin-small-right inline-block left uk-width-1-6 hidden-small">
                            <select class="uk-select uk-width-1-1" id="bulk_status" name="bulk_status">
                                <option selected disabled>Select Status........</option>
                                <option value="1">Present</option>
                                <option value="0">Absent</option>
                            </select>
                        </div>
                        <div class="uk-margin-small-right inline-block left uk-width-1-6 hidden-small">
                            <a class="edit apply" id="bulk_status_apply" >Apply</a>
                        </div>
                    @endif
                    <table class="uk-table uk-table-hover uk-table-middle uk-table-divider rooms small-entries" id="student_attendance_table" style="width: 100%;">
                        <input type="hidden" name="student_id" id="student_id" value="">
                        <input type="hidden" name="status" id="status" value="">
                        <input type="hidden" name="room" id="room" value="{{request()->room_id}}">
                        <input type="hidden" name="classroom" id="classroom" value="{{request()->classroom}}">
                        <input type="hidden" name="select_all" id="select_all" value="">
                        <input type="hidden" name="filter" id="filters" value="">
                        @include('components.search-datatable',['id'=>'filter_attendance'])

                        <thead>
                    <tr>
                        <th class="uk-table-shrink"><input id="checkbox_all" class="uk-checkbox" type="checkbox"></th>
                        <th class="">ID</th>
                        <th class="">Name</th>
                        <th class="uk-table-expand">Status</th>
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
            @include('attendance.ChangeStatusModal')
        </div>
        <div id="scan" style="display: none ;display: flex;justify-content: center;">

            <video id="preview"></video>
            <br>
            <span style="display: none; cursor: pointer" id="reset_scan" uk-icon="icon:  history; ratio: 2"></span>


        </div>
        {{--    @endif--}}
    </div>
    @include('components.scanner-qrcode')
    @include('components.scanner-qrcode-data')

@endsection
    @section('script')
        {{-- <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script> --}}
        <script src="{{asset('assets/js/html5-qrcode.min.js')}}" type="text/javascript"></script>
        {{-- <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script> --}}
        <script>
            $( document ).ready(function() {
                {{-- @if(request()->has('classroom') && request()->has('room_id')) --}}
                var student_attendance_table
                student_attendance_table =  $('#student_attendance_table').DataTable({
                    processing: true,
                    serverSide: true,
                    lengthMenu: [5, 10, 20, 50, 100],
                    pageLength: 20,
                    responsive: true,
                    searching:false,
                    dom: 'Blfrtip',
                    order: [[1, 'desc']],
                    ajax: {
                        url: "{{route('get-student-attendance-data')}}",
                        data: function (d) {
                            d.classroom={{request()->classroom??0}};
                            d.room={{request()->room_id??0}};
                            d.attendance_type="{{request()->attendance_type??'room'}}";
                            d.status=$('#filters').val();
                            d.name=$('#filter_attendance').val();
                        },
                        beforeSend: function () {
                            $('#table_page').val($('#student_attendance_table').DataTable().page())
                        }
                    },
                    "language": {
                        "processing":
                            `<div class="db-loader">
                        <span class='fa-stack fa-lg'>
                            <i class='fa fa-spinner fa-spin fa-stack-2x fa-fw'></i>
                        </span>Loading ... </div>`,
                    },
                    columnDefs: [
                        { "width": "2%", "targets": 0 },
                        { "width": "12%", "targets": 1 },
                        { "width": "15%", "targets": 2 },
                        { "width": "65%", "targets": 3 },
                        @if(request()->type && request()->type==1)
                        { "width": "1%", "targets": 4 },
                        { "width": "1%", "targets": 5 },
                        { "width": "15%", "targets": 6 },
                        @else
                        { "width": "15%", "targets": 4 },
                        @endif


                    ],
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
                                    present='success';
                                }
                                else if(full['status']=='Absent'){
                                    absent='success';
                                }
                                else if(full['status']=='Absent with excuse'){
                                    absent_with='success'
                                }
                                else if(full['status']=='left/leave early with justification'){
                                    left_with_excuse='success'
                                }
                                else if(full['status']=='New student'){
                                    present_but_absent='student'
                                }

                                if('{{request()->type==1}}'){

                                    return `<div class='status'>
                                        <span class="uk-badge table-btns ${absent}" disabled  data-value="0" data-id="${full['id']}">Absent</span>
                                        <span class="uk-badge table-btns ${present}"  disabled data-value="1" data-id="${full['id']}">Present</span>
                                            <span class="uk-badge table-btns hidden-small ${absent_with}"  disabled data-value="2" data-id="${full['id']}">Absent With excuse</span>
                                            <span class="uk-badge table-btns hidden-small ${left_with_excuse}" disabled  data-value="3" data-id="${full['id']}">left early with justification</span>
                                            <span class="uk-badge table-btns hidden-small ${present_but_absent}"  disabled data-value="4" data-id="${full['id']}">New student</span>
                                        </div>
                                    `
                                }else{
                                    return `<div class='status'>
                                        <span class="uk-badge table-btns ${absent}"  id="changeStatus" data-value="0" data-id="${full['id']}">Absent</span>
                                        <span class="uk-badge table-btns ${present}"  id="changeStatus" data-value="1" data-id="${full['id']}">Present</span>
                                            <span class="uk-badge table-btns hidden-small ${absent_with}" id="changeStatus" data-value="2" data-id="${full['id']}">Absent With excuse</span>
                                            <span class="uk-badge table-btns hidden-small ${left_with_excuse}" id="changeStatus"  data-value="3" data-id="${full['id']}">left early with justification</span>
                                            <span class="uk-badge table-btns hidden-small ${present_but_absent}" id="changeStatus" data-value="4" data-id="${full['id']}">New student</span>
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

                    $("#student_id").val( JSON.stringify(checkboxStudents))
                    console.log(checkboxStudents);

                    acceptChangeStatus(null, true)
                })
                $(document).on('click','#save_change_status',function (e){
                    e.preventDefault()
                    ChangeRequest();
                })
                {{--@endif--}}


                $('#checkbox_all').on('click',function (e){
                    $('input:checkbox').not(this).prop('checked', this.checked);
                })




                $(document).on('change','#classroom_id',function (e){
                    e.preventDefault();
                    let classroom= $(this).val();
                    let attendance_type="{{request('attendance_type')}}"
                    getRooms(classroom,attendance_type);
                });
                function getRooms(classroom,attendance_type){
                    let url;
                if(attendance_type =='room'){
                     url='get_room_with_class';
                }else{
                    url='get_quiz_with_class';
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
                @if(request()->has('classroom'))
                getRooms({{request()->classroom}},"{{request('attendance_type')}}");
                $(document).on('change','#first_present',function (e){
                    let id=$(this).attr('data-student');
                    let checked = $(this).is(':checked') ;
                    // if(e.target.checked){
                        $.ajax({
                            url: `/make_first_attendance/?classroom={{request()->classroom}}&room={{request()->room_id}}&student=${id}&status=${checked}`,
                            type: "get",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            },
                            success: function (res) {
                                if(res.status){
                                    let page = $('#table_page').val()
                                    student_attendance_table.ajax.reload()
                                    page ? $('#student_attendance_table').DataTable().page(parseInt(page)).draw("page") :null
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
                            url: `/make_second_attendance/?classroom={{request()->classroom}}&room={{request()->room_id}}&&student=${id}&status=${checked}`,
                            type: "get",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            },
                            success: function (res) {
                                if(res.status){
                                    let page = $('#table_page').val()
                                    student_attendance_table.ajax.reload()
                                    page ? $('#student_attendance_table').DataTable().page(parseInt(page)).draw("page") :null

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
                                url: `/collect_absence`,
                                type: "get",
                                data:{room_id: {{request()->room_id}},classroom_id:{{request()->classroom}}, type: {{request()->type}}},
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
                                        student_attendance_table.ajax.reload();
                                        page ? $('#student_attendance_table').DataTable().page(parseInt(page)).draw("page") :null
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
                @else
                $(document).on('click','#save',function (e){
                    e.preventDefault()
                    let type=0
                    let class_room =$('#classroom_id').val()
                    let room_id=$("#room_id").val();
                    let attendance_type="{{request()->attendance_type}}";
                    @if(request()->attendance_type=='room')
                        type=document.getElementById('2dayRoom').checked?1:0;
                    @endif
                    $.ajax({
                        url: `/make_room_check`,
                        type: "get",
                        data:{"classroom":class_room,"room_id":room_id,"type":type},
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        },
                        success: function (res) {
                            if(res.status){
                                if(res.type==1){
                                    window.location.href=`/attendance?classroom=${class_room}&room_id=${room_id}&type=1&attendance_type=${attendance_type}`;
                                }else{
                                    window.location.href=`/attendance?classroom=${class_room}&room_id=${room_id}&type=0&attendance_type=${attendance_type}`;

                                }

                            }
                        },
                        error:function (res) {
                            toastr.warning(res.responseJSON.message);

                        }
                    });
                })
                @endif



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
                         url: `/changeStatus`,
                         type: "post",
                         data:{student_id:$("#student_id").val(),room_id:$("#room").val(),type:type,attendance_type:attendance_type,classroom_id:$("#classroom").val(),comment:$("#comments").val(),status:$("#status").val(),select_all:select_all,name_id:name_id},
                         headers: {
                             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                         },
                         success: function (res) {
                             if(res.status){
                                 if(status!=='1'){
                                    toastr.success("change Success");
                                }
                                UIkit.modal('#modal-changeStatus').hide();
                                 let page = $('#table_page').val()
                                 student_attendance_table.ajax.reload();
                                 page ? $('#student_attendance_table').DataTable().page(parseInt(page)).draw("page") :null
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

                $('.uk-select').select2({
                    placeholder:'Select '
                })

                $(document).on('click','#totalFilter',function (e){
                     $('#filters').val('')
                    //  let page = $('#table_page').val()
                     student_attendance_table.ajax.reload()
                    //  page ? $('#student_attendance_table').DataTable().page(parseInt(page)).draw("page") :null
                });
                $(document).on('click','#presentFilter',function (e){
                    $('#filters').val(1)
                    // let page = $('#table_page').val()
                    student_attendance_table.ajax.reload()
                    // page ? $('#student_attendance_table').DataTable().page(parseInt(page)).draw("page") :null
                });
                $(document).on('click','#absenteesFilter',function (e){
                    $('#filters').val(0)
                    // let page = $('#table_page').val()
                    student_attendance_table.ajax.reload()
                    // page ? $('#student_attendance_table').DataTable().page(parseInt(page)).draw("page") :null
                });
                $(document).on('click','#newstudentFilter',function (e){
                    $('#filters').val(4)
                    // let page = $('#table_page').val()
                    student_attendance_table.ajax.reload()
                    // page ? $('#student_attendance_table').DataTable().page(parseInt(page)).draw("page") :null
                });
                $(document).on('click','#reset',function (e){
                    var attendance_type="{{request('attendance_type')??'0'}}";

                    window.location.href=`/attendance/?attendance_type=${attendance_type}`;

                });

                $('#filter_attendance').on('keyup',function (){
                    student_attendance_table.ajax.reload();
                });
                $('#reset_scan').on('click',function (){
                    location.reload();
                    $('#reset_scan').hide()
                });



                var html5QrCode = new Html5Qrcode(/* element id */ "reader", false);


                // var html5QrcodeScanner = new Html5QrcodeScanner(
                //     "reader",
                //     { fps: 10, qrbox: {width: 250, height: 250} },
                //     /* verbose= */ false
                // );

                $(document).on('click','#use_scan',function(e){
                    // console.log('kfjskdjf');
                    if($("#room").val()){
                        let accetp_swal = Swal.fire({
                            title: " Are You Sure to use scanner ?",
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
                                $('#scan').fadeIn()
                                $('#attendance').fadeOut()
                                $('#reset_scan').fadeIn()

                                UIkit.modal('#modal-scanner-qrcode').show();
                                // html5QrcodeScanner.render(onScanSuccess, onScanFailure);

                                Html5Qrcode.getCameras().then(devices => {
                                    /**
                                     * devices would be an array of objects of type:
                                     * { id: "id", label: "label" }
                                     */
                                    if (devices && devices.length) {
                                        var cameraId = devices[0].id;
                                        // .. use this to start scanning.
                                        $('#scanner-qrcode-cams').empty()
                                        $.each(devices, function(key,val) {
                                            // console.log(val);
                                            $('#scanner-qrcode-cams').append(`<option value="${val.id}">${val.label}</option>`)
                                        });
                                    }

                                    startScanCam(cameraId)


                                }).catch(err => {
                                    console.log('err');
                                });

                            } else {
                                Swal.fire("Close", "Close Success", "error");
                            }
                        })
                    }else{
                        Swal.fire("Info", "Please choose room", "Info");
                    }
                })



                function startScanCam(cameraId) {

                    let qrboxFunction = function(viewfinderWidth, viewfinderHeight) {
                        let minEdgePercentage = 0.7; // 70%
                        let minEdgeSize = Math.min(viewfinderWidth, viewfinderHeight);
                        let qrboxSize = Math.floor(minEdgeSize * minEdgePercentage);
                        return {
                            width: qrboxSize,
                            height: qrboxSize
                        };
                    }

                    let config = {fps: 2, qrbox: qrboxFunction };
                    // let config = { qrbox: { width: 250, height: 250 } };

                    html5QrCode.start(
                        // {deviceId: { exact: cameraId} },
                        { facingMode: "environment" },
                        config,
                        (decodedText, decodedResult) => {

                            // html5QrCode.clear()
                            // html5QrCode.pause()
                            $('#student_id').val(decodedText)
                            $("#status").val('1')

                            // UIkit.modal('#modal-scanner-qrcode').hide();

                            acceptChangeStatus(null, false,true)
                            html5QrCode.pause()

                            // get_student_use_id
                            $.ajax({
                                url: "{{route('get_student_scan')}}",
                                type: "get",
                                data:{ student_id: decodedText},
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                },
                                success: function (res) {
                                    // res.data
                                    $('#modal-scanner-qrcode-data #scan_id').text(res.data.name_id)
                                    $('#modal-scanner-qrcode-data #scan_name').text(res.data.name)
                                    $('#modal-scanner-qrcode-data #scan_missed_room').text(res.data.missed_room)
                                    $('#modal-scanner-qrcode-data #scan_missed_room_name').text(res.data.missed_room_name)
                                    $('#modal-scanner-qrcode-data #scan_classrooms').text(res.data.classrooms)
                                    $('#modal-scanner-qrcode-data #scan_phone').text(res.data.phone)
                                    $('#modal-scanner-qrcode-data #scan_parent_phone').text(res.data.ParentPhone)
                                    $('#modal-scanner-qrcode-data #scan_suspension').text(res.data.block)
                                    UIkit.modal('#modal-scanner-qrcode-data').show();
                                },
                                error:function (res) {
                                    html5QrCode.resume()
                                    toastr.warning(res.responseJSON.message);
                                }
                            });

                            // $('#scan').fadeOut()
                            // $('#attendance').fadeIn()
                            // $('#reset_scan').fadeOut()
                            // html5QrCode.clear();
                            // startScanCam()
                            // html5QrCode.stop();

                        },
                        (errorMessage) => {}
                    )
                    .catch((err) => {
                        // Start failed, handle it.
                        // toastr.warning(res.responseJSON.message);
                        toastr.warning(res.errorMessage);
                    });
                }

                $('#scanner-qrcode-cams').on('change', function() {
                    html5QrCode.stop();
                    html5QrCode = new Html5Qrcode("reader");
                    startScanCam( $(this).val() );
                });

                $('#modal-scanner-qrcode').on('click', '.uk-modal-close-default', function() {
                    html5QrCode.stop();
                    $('#scan').fadeOut()
                    $('#attendance').fadeIn()
                    $('#reset_scan').fadeOut()
                });

                $('#modal-scanner-qrcode-data').on('click', '.uk-modal-close-default', function() {
                    html5QrCode.resume();
                });

            });
        </script>

    @endsection

