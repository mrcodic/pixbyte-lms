@extends('layouts.app')
@section('css')

<style>
.dt-buttons{
    display: flex;
    justify-content: end!important;
    margin-top: 12px!important;
    float:none !important;
    margin-right:32px;
}
.dt-buttons button{
    background: #fafbfb !important;
    border-radius: 19px !important;
}
.requests{
    background: #ffe0c473;
}
.blocks{
    background: #ffc4c473;
}
</style>
@endsection
@section('title', 'Students')


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
                        <h3 class="uk-margin-remove-bottom title-add">Students</h3>
                    </div>
                    <div class="line divider"></div>
                </div>
            </div>

        </div>

        <div class="uk-container uk-container-expand rm-expand uk-margin-medium-top uk-margin-left">
                <div class="uk-overflow-auto uk-margin-medium-top x-scrollbar mt-s-20">
                <input type="hidden" id="table_page" value="">
                <table class="uk-table uk-table-hover uk-table-middle uk-table-divider rooms" id="student_table" style="width:100%">
                    <div class="uk-margin-small-right inline-block left uk-width-1-6 hidden-small">
                        <select class="uk-select uk-width-1-1" id="grade_id" name="grade">
                            <option selected disabled>Select Grades</option>
                            <option value="">all</option>
                            @foreach ( $grades as $grade )
                                <option value=" {{$grade->id}} ">{{ $grade->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="uk-margin-small-right inline-block left uk-width-1-6 hidden-small">
                        <select class="uk-select uk-width-1-1" id="classroom_id" name="classroom_id">
                            <option selected disabled>Select Classroom</option>
                            <option value="">all</option>
                            @foreach ( $classrooms as $class )
                                <option value=" {{$class->id}}" @if(request('classroom') && ((int)request('classroom')==$class->id)) selected @endif>{{ $class->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    @include('components.search-datatable',['id'=>'filter_datatable'])
                    <input type="hidden" name="select_all" id="select_all" value="">
                    <thead>
                    <tr>

                        <th class="uk-table-shrink"><input id="checkbox_all" class="uk-checkbox" type="checkbox"></th>
                        <th class="">ID</th>
                        <th class="">Name</th>
                        <th class="uk-table-expand">Classroom</th>
                        <th class="uk-table-expand">Email</th>
                        <th class="uk-table-expand">Grade</th>
                        <th class="uk-text-nowrap">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="uk-width-expand uk-margin-top">
                <div class="uk-form-controls">
                    <a class="delete uk-margin-small-right" id="delete_all" >Delete</a>
                    <span class="dark-font">selected entries</span>

                </div>
            </div>
        </div>
        @include('students.RequestsModal')
        @include('students.RequestChangeModal')
        @include('students.ActivityModal')
        @include('students.StudentDetailModal')
    </div>
@endsection
    @section('script')


        <script>

            $( document ).ready(function() {
                var student_table
                student_table =  $('#student_table').DataTable({
                    columnDefs: [
                        { "width": "10%", "targets": 4 },
                        { "width": "10%", "targets": 5 },
                    ],
                    processing: true,
                    ordering:false,
                    serverSide: true,
                    lengthMenu: [5, 10, 20, 50, 100],
                    pageLength: 20,
                    responsive: true,
                    searching:false,
                    dom: 'Blfrtip',
                    order: [[0, 'desc']],
                    ajax: {
                        url: "{{route('get-student-data')}}",
                        data: function (d) {
                            d.grade_id=$('#grade_id').val();
                            d.classroom_id=$('#classroom_id').val();
                            d.name=$('#filter_datatable').val();
                        },
                        beforeSend: function () {
                            $('#table_page').val($('#student_table').DataTable().page())
                        }
                    },
                    "language": {
                        "processing":
                            `<div class="db-loader">
                        <span class='fa-stack fa-lg'>
                            <i class='fa fa-spinner fa-spin fa-stack-2x fa-fw'></i>
                           </span>Loading ... </div>`,
                    },

                    columns: [

                        {
                            data:'id',orderable: false, render:function (data,type,full){
                                return `<input class="uk-checkbox" id="checkbox" value="${data}" type="checkbox">`;}
                        },
                        {data: 'name_id', name: 'name_id', className: ' uk-text-left'  },

                        {data: 'name' , className: ' uk-text-left' ,
                            render:function (data,type,full){
                                return `<a class="link" href="/u/${full['name_id']}">${data}</a> `}
                        },
                        {data: 'enroll_class', name: 'enroll_class', className: ' uk-text-left'  },
                        {data: 'email', name: 'email', className: ' uk-text-left'  },
                        {data: 'grade', name: 'grade', className: ' uk-text-left'  },
                        {data: 'id',className:'uk-text-nowrap',render:function (data,type,full){
                                let iconBlock
                            if(full['block']){
                                iconBlock=`<a class="" id="changeBlock" data-id="${data}"
                                                 uk-tooltip="Remove Suspension"><span uk-icon="refresh"></span></a>`;
                            }else{
                                iconBlock=''
                            }
                                return `
                                <a class="" id="requests" data-id="${data}" uk-tooltip="Requests" ><span uk-icon="forward"></span></a>
                                <a class="" id="see_more" data-id="${data}"
                                data-enroll-class="${full['enroll_class']}"
                                data-total-room="${full['total_room']}"
                                data-complete-room="${full['completed_room']}"
                                data-missed-room="${full['missed_room']}"
                                data-room="${full['room']}"
                                data-ip="${full['ip']}"
                                data-created_at="${full['created_at']}"
                                uk-tooltip="More Info"
                                ><span uk-icon="info"></span></a>
                                <a class="" id="actitvity" data-id="${data}" uk-tooltip="Actitvity"><span uk-icon="bolt"></span></a>
                                    ${iconBlock}

                                        `;

                        }
                        }
                    ],
                    rowCallback: function (row, data) {
                        if ( data.request ) {
                            $(row).addClass('requests');
                        }
                        if(data.block){
                            $(row).addClass('blocks');
                        }
                    }
                });


                $('#checkbox_all').on('click',function (e){
                    $('input:checkbox').not(this).prop('checked', this.checked);
                })


                    $(document).on('click','#requests',function (e){
                    $('.test').hide()
                    UIkit.modal('#modal-requests').show();

                    let id = $(this).attr('data-id');
                    $.ajax({
                        url: `/get-request-student/${id}`,
                        type: "get",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        },
                        beforeSend: function() {
                            $('.loading').show();

                        },
                        success: function (res) {
                            if(res.status){
                                $('.loading').hide();
                                $('.test').show()
                                $('#rquest_div').empty();
                                $('#rquest_div').append(`
                                    <input type="hidden" value="${id}" id="student_id_row" name="student_id">
                                `);
                                let status=''
                                res.data.forEach((item)=>{
                                    if(item.status==1){
                                        status= `<span> Accepted </span>`

                                    }else if(item.status==2){
                                        status= `<span> Rejected </span>`

                                    }else{
                                        status= `<span uk-icon="close" data-id="${item.id}"  data-student-id="${id}" id="reject"></span>
                                    <span uk-icon="check" id="accept" data-id="${item.id}" data-student-id="${id}"></span>`

                                    }
                                $('#rquest_div').append(`
                                <input type="hidden" value="${id}" id="student_id_row" name="student_id">
                                                    <div class="uk-card uk-card-default classroom-card up-hover uk-margin-bottom">
                        <div class="uk-padding-small border-radius">
                            <div class="uk-flex cursor-pointer">
                                <div class="uk-width-expand">
                                    <div class="up-room">
                                        <img class="icon-activity" src="{{ asset('img/exam.svg') }}" alt="room-icon">
                                        <h5 class="dark-font uk-margin-remove-bottom uk-margin-small-top inline-block uk-margin-small-left uk-text-bold"> Current : ${item.current_class.title}  <a href="#" class=""><span class="unit-title uk-text-bold"> Change To  ${item.another_class.title}.</span></a>
                                        </h5>
                                        <p class="date-padding light-dark uk-margin-small-bottom uk-margin-remove-top uk-margin-medium-left"><time datetime="2016-04-01T19:00"> ${item.created_at}</time></p>
                                    </div>
                                </div>
                                <div class="uk-width-auto uk-margin-small-top">

                                    ${status}

                                </div>

                            </div>

                        </div>
                    </div>

                                `)
                                });

                            }
                        },
                        error:function (res) {
                            Swal.fire("Close!", "Something is wrong, Please try again.", "error");
                        }
                    });

                })
                $(document).on('click','#delete_row',function (e){
                    e.preventDefault();
                    var rowID=$(this).attr('data-id');
                    let url = `/student/${rowID}`
                    deleteRow(rowID,url)

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
                                url: '/UnBlock',
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
                                    student_table.ajax.reload();
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
                $(document).on('change','#current_classroom_id',function (e){
                    e.preventDefault();
                    let current_class= $(this).val();
                    let student_id=$('#student_id_row').val();

                    $.ajax({
                        url: `/get_another_classes_student/${current_class}/${student_id}`,
                        type: "get",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        },
                        success: function (res) {
                            if(res.status){
                                $('#another_classroom_id').empty()
                                $('#another_classroom_id').append('<option readonly selected disabled>Select........</option>')
                                res.data.forEach((item)=>{
                                    $('#another_classroom_id').append(`
                                    <option value="${item.id}">${item.title}</option>
                                    `)
                                });
                                UIkit.modal('#modal-request').show();

                            }
                        },
                        error:function (res) {
                            Swal.fire("Close!", "Something is wrong, Please try again.", "error");
                        }
                    });


                });

                $(document).on('click', '#save_user_requset', function (e) {
                    e.preventDefault();
                    let current_classroom_id=$('#current_classroom_id').val();
                    let another_classroom_id=$('#another_classroom_id').val();
                        console.log(current_classroom_id,another_classroom_id)
                    if((!current_classroom_id && !another_classroom_id )|| (!another_classroom_id) || (!current_classroom_id)){
                        Swal.fire("Close!", "please select Classroom.", "error");
                    }else{
                        let student_id=$('#student_id_row').val();
                        let accetp_swal = Swal.fire({
                            title: " Note: You are about To Make Request Change !",
                            text: "",
                            icon: "warning",
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonColor: '#DD6B55',
                            confirmButtonText: 'Continue',
                            cancelButtonText: "Close",
                            closeOnConfirm: false,
                            closeOnCancel: false
                        }).then(function (result) {
                            if (result.isConfirmed) {
                                $.ajax({
                                    url: '/save_instuctor_request',
                                    type: "post",
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                    },
                                    data: {student_id:student_id,current_classroom_id: current_classroom_id,another_classroom_id:another_classroom_id},
                                    success: function (res) {
                                        if(res.status){
                                            Swal.fire("Done",res.message, "success");
                                        }
                                        UIkit.modal('#modal-request').hide();
                                        let page = $('#table_page').val()
                                        student_table.ajax.reload();
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
                    }



                })
                $(document).on('click','#makeRequest',function (e){
                    e.preventDefault();
                    let student_id=$('#student_id_row').val();
                    $.ajax({
                        url: `/get_classes_student/${student_id}`,
                        type: "get",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        },
                        success: function (res) {
                            if(res.status){
                                $('#current_classroom_id').empty()
                                $('#current_classroom_id').append('<option readonly selected disabled >Select........</option>')
                                res.data.forEach((item)=>{
                                    $('#current_classroom_id').append(`
                                    <option value="${item.id}">${item.title}</option>
                                    `)
                                });
                                UIkit.modal('#modal-request').show();


                            }
                        },
                        error:function (res) {
                            Swal.fire("Close!", "Something is wrong, Please try again.", "error");
                        }
                    });
                    // UIkit.modal('#modal-request').show();

                })
                $(document).on('click','#accept',function (e){
                    e.preventDefault();
                    var id=$(this).attr('data-id');
                    var student_id=$(this).attr('data-student-id');
                    let type='accept'
                    replyRequest(id,type,student_id)

                })
                $(document).on('click','#see_more',function (e){
                    UIkit.modal('#modal-more-info').show();
                    $("#created_at").text($(this).attr('data-created_at'))
                    $("#ip").text($(this).attr('data-ip'))
                    $("#missed_room").text($(this).attr('data-missed-room'))
                    $("#completed_room").text($(this).attr('data-complete-room'))
                    $("#total_room").text($(this).attr('data-total-room'))
                    $("#enroll_class").text($(this).attr('data-enroll-class'))
                    $("#room").text($(this).attr('data-room'))

                })

                $(document).on('click','#reset_coupon',function (e){
                    let couponId=$(this).attr('data-coupon-id')
                    let html=`
                     <div>
                               <label>Day: </label>
                                <input type="number" id="day" class="uk-input" style="width: 264px;color:black" name="day" placeholder="Enter Number Of Days">

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
                                url: `/resetCoupon`,
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
                $(document).on('click','#reject',function (e){
                    e.preventDefault();
                    var id=$(this).attr('data-id');
                    var student_id=$(this).attr('data-student-id');
                    let type='reject'
                    replyRequest(id,type,student_id)


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
                                    student_table.ajax.reload();
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


                }
                function replyRequest(id,type,student_id){
                    let accetp_swal = Swal.fire({
                        title: " Are You Sure ?",
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
                                url: '/replay_request',
                                type: "POST",
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                },
                                data: {id: id,type:type,student_id:student_id},
                                success: function (res) {
                                    if(res.status){
                                        Swal.fire("Done", "successful", "success");
                                        UIkit.modal('#modal-requests').hide();

                                        let page = $('#table_page').val()
                                        student_table.ajax.reload();
                                        page ? $('#student_table').DataTable().page(parseInt(page)).draw("page") :null
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


                }


                $('#grade_id').on('change',function (){
                    student_table.ajax.reload();
                });
                $('#classroom_id').on('change',function (){
                    student_table.ajax.reload();
                });
                $('#filter_datatable').on('keyup',function (){
                    student_table.ajax.reload();
                });
                $('#filter_rooms').on('keyup',function (){
                    room_student_table.ajax.reload();
                });

                $('#filter_exam').on('keyup',function (){
                    quiz_student_table.ajax.reload();
                });
                $('#grade_id').select2({
                    placeholder:'Select '
                })
                $('#classroom_id,#another_classroom_id,#current_classroom_id').select2({
                    placeholder:'Select '
                })


                var room_student_table,quiz_student_table
                $(document).on('click','#actitvity',function (e) {
                    let student_id=$(this).attr('data-id')
                    UIkit.modal('#modal-activity').show();
                    $('#room_student_table').DataTable().clear().destroy()
                    $(".loading").show();

                    setTimeout(function () {
                        room_student_table =  $('#room_student_table').DataTable({
                            processing: true,
                            serverSide: true,
                            lengthMenu: [5, 10, 20, 50],
                            pageLength: 5,
                            retrieve: true,
                            searching:false,
                            sorting:false,
                            // paging: false,
                            responsive: true,
                            initComplete: function () {
                                console.log('@@@ init complete @@@');
                                $(".loading").hide();
                            },
                            dom: 'Blfrtip',
                            order: [[0, 'desc']],
                            ajax: {
                                url: "{{route('get-room_student-data')}}",
                                data: function (d) {
                                    d.student_id=student_id;
                                    d.classroom_id=$('#classroom_id').val();
                                    d.grade_id=$('#grade_id').val();
                                    d.name=$('#filter_rooms').val();

                                },
                            },
                            columns: [
                                {data: 'readingOrder', className: 'reorder' ,visible: false, searchable: false},

                                {data: 'name' , className: ' uk-text-left' ,
                                    render:function (data,type,full){
                                        return `<a class="link" href="/u/${data}">${data}</a> `} },
                                {data: 'join', name: 'coupon_used', className: ' uk-text-left'  },
                                {data: 'end_date', name: 'end_date', className: ' uk-text-left'  },
                                {data: 'coupon', name: 'coupon', className: ' uk-text-left'  },
                                {data: 'completed', name: 'completed', className: ' uk-text-left'  },
                                {data: 'status_val', name: 'status_val', className: ' uk-text-left'  },
                                // {data: 'missed', name: 'missed', className: ' uk-text-left'  },
                                {data: 'button_coupon' , className: ' uk-text-left' ,
                                    render:function (data,type,full){
                                       if(data){
                                           return `
                                        <a class="" id="reset_coupon" data-id="${data}"
                                           data-coupon-id="${data}"
                                           uk-tooltip="Open more days"
                                        ><span uk-icon="unlock"></span></a>
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
                            serverSide: true,
                            lengthMenu: [5, 10, 20, 50],
                            pageLength: 5,
                            retrieve: true,
                            searching:false,
                            sorting:false,
                            // paging: false,
                            responsive: true,
                            initComplete: function () {
                                console.log('@@@ init complete @@@');
                                $(".loading").hide();
                            },
                            dom: 'Blfrtip',
                            order: [[0, 'desc']],
                            ajax: {
                                url: "{{route('get-quiz_student-data')}}",
                                data: function (d) {
                                    d.student_id=student_id;
                                    d.name=$('#filter_exam').val();

                                },
                            },
                            columns: [

                                {data: 'name' , className: ' uk-text-left' ,
                                    render:function (data,type,full) {
                                        if (full['enter']) {
                                            return `<a class="link" href="/quiz/${full['id']}/show_answer?student_id=${student_id}">${data}</a> `
                                        }else{
                                            return `<a class="link" href="#">${data}</a> `

                                        }
                                    }
                                    },

                                {data: 'type', name: 'type', className: ' uk-text-left'  },
                                {data: 'passed', name: 'passed', className: ' uk-text-left'  },
                                {data: 'num_retake', name: 'num_retake', className: ' uk-text-left'  },
                                {data: 'score', name: 'score', className: ' uk-text-left'  },
                                {data: 'id' , className: ' uk-text-left' ,
                                    render:function (data,type,full) {
                                        if (full['enter']) {
                                            return `<a target="_blank" class="link uk-margin-small-right" href="/quiz/${full['id']}/show_answer?student_id=${student_id}" uk-tooltip="title: View Answers"> <span uk-icon="eye"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><circle fill="none" stroke="#000" cx="10" cy="10" r="3.45"></circle><path fill="none" stroke="#000" d="m19.5,10c-2.4,3.66-5.26,7-9.5,7h0,0,0c-4.24,0-7.1-3.34-9.49-7C2.89,6.34,5.75,3,9.99,3h0,0,0c4.25,0,7.11,3.34,9.5,7Z"></path></svg></span></a>
                                                    <a class="link" id="reset" data-id="${data}" data-student="${student_id}" uk-tooltip="title: Reset Progress"> <span uk-icon="refresh"></span></a>`

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

                $(document).on('click','#reset',function (){
                    let student_id=$(this).attr('data-student')
                    let quiz=$(this).attr('data-id')
                    let accetp_swal = Swal.fire({
                        title: " Reset this progress ?",
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
                                url: '/reset-quiz',
                                type: "get",
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                },
                                data: {quiz:quiz,student_id:student_id},
                                success: function (res) {
                                    if(res.status){
                                        Swal.fire("Done", "successful", "success");
                                        let page = $('#table_page').val()
                                        quiz_student_table.ajax.reload();
                                        page ? $('.quiz_student_table').DataTable().page(parseInt(page)).draw("page") :null
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
                })




            });
        </script>


    @endsection
