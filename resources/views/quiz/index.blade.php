@extends('layouts.app')

@section('css')
 <style>
     #more{
         text-align: center;
     }
    #more img{
        width: 50px;
        height: 50px;
    }
 </style>
@endsection
@section('title', 'Quizzes')

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
                        <h3 class="uk-margin-remove-bottom title-add">All Quizzes, Exams & Assignments</h3>
                    </div>
                    <div class="uk-width-auto">
                        <a href="{{route('quiz.create')}}" class="uk-button uk-button-primary border-radius uk-padding-remove-t-b "><span class="add-icon uk-icon uk-margin-small-right" uk-icon="icon:plus; ratio: .7"></span>Add New Quiz, Exam & Assignment</a>
                    </div>
                    <div class="line divider"></div>
                </div>
            </div>

        </div>

        <div class="uk-container uk-container-expand rm-expand uk-margin-medium-top uk-margin-left">
            <div class="uk-overflow-auto uk-margin-medium-top x-scrollbar">
                <div class="uk-margin-small-right inline-block left uk-width-1-6@m uk-width-1-2@s mb-s-20">
                    <select class="uk-select uk-width-1-1" id="type" name="type">
                        <option selected disabled>Select Type</option>
                        <option value="">all</option>
                            <option value="1">Quiz</option>
                            <option value="2">Exam</option>
                            <option value="3" @if(request()->has('assignment')) selected @endif>Assignment</option>

                    </select>
                </div>
                <table class="uk-table uk-table-hover uk-table-middle uk-table-divider rooms" id="questions_table" style="width:100%;">
                    <input type="hidden" name="select_all" id="select_all" value="">
                    <input type="hidden" id="table_page" value="">
                    @include('components.search-datatable',['id'=>'filter_quiz'])

                    <thead>
                    <tr>
                        <th class="uk-table-shrink"><input id="checkbox_all" class="uk-checkbox" type="checkbox"></th>
                        <th class="uk-table-expand">title</th>
                        <th class="uk-table-expand">type</th>
                        <th class="uk-table-expand">Question Number</th>
                        <th class="uk-table-expand">Created at</th>
                        <th class="uk-table-shrink uk-text-nowrap">Action</th>
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
        <!-- This is the modal -->
        <div id="modal-more-info" class="negative" uk-modal>
            <div class="uk-modal-dialog uk-modal-body" style="width: fit-content">
                <button class="uk-modal-close-default" type="button" uk-close></button>
                <div uk-grid class="test">
                    <h2 class="uk-modal-title">Question </h2>
                    <div class="uk-margin-small-top uk-width-1-1" id="classroom" >
                        <div class="modal-border">
                            <div id="more">


                            </div>
                        </div>

                    </div>

                </div>

                <p class="uk-text-right">
                    <button class="uk-button uk-button-default uk-modal-close border-radius" type="button">Cancel</button>
                    {{--            <button class="uk-button uk-button-primary border-radius" id="save_requset" type="button">Save</button>--}}
                </p>
            </div>
        </div>



    </div>
@endsection
    @section('script')

        <script>
            var editor;

            $( document ).ready(function() {
                var room_table
                room_table =  $('#questions_table').DataTable({
                    processing: true,
                    serverSide: true,
                    lengthMenu: [5, 10, 20, 50],
                    pageLength: 10,
                    responsive: true,
                    searching:false,
                    dom: 'Blfrtip',
                    "language": {
                        "processing":
                            `<div class="db-loader">
                        <span class='fa-stack fa-lg'>
                            <i class='fa fa-spinner fa-spin fa-stack-2x fa-fw'></i>
                           </span>Loading ... </div>`,
                    },
                    ajax: {
                        url: "{{route('get-quiz-data')}}",
                        data: function (d) {
                            d.type=$('#type').val();
                            d.name=$('#filter_quiz').val();
                        },

                        beforeSend: function () {
                            $('#table_page').val($('#questions_table').DataTable().page())
                        }
                    },

                    columns: [

                        {
                            data:'id',orderable: false, render:function (data,type,full){
                                return `<input class="uk-checkbox" id="checkbox" value="${data}" type="checkbox">`;}
                        },
                        {
                            data:'name',orderable: false,className: ' uk-text-left',
                            render:function (data,type,full){
                                return `<a href="/quiz/${full['id']}/show_answer" >${data}</a>`;
                            }
                        },
                        {
                            data:'type',orderable: false,className: ' uk-text-left'
                        },
                        {
                            data:'question_count',orderable: false,className: ' uk-text-left'
                        },
                        {data: 'created_at', name: 'created_at', className: ' uk-text-left'  },
                        {data: 'id',className:'uk-text-nowrap',render:function (data,type,full){
                                return `
                            <a class="edit" href="/quiz/${data}/edit?pageTbl=${$('#table_page').val()}">Edit</a>
                            <a class="delete" id="delete_row" data-id="${data}">Delete</a>

                                `;
                            }
                        }
                    ],
                    rowCallback: function (row, data) {
                        if ( data.is_draft ) {
                            $(row).addClass('draft');
                        }

                    }
                });



                if ('{{session()->get("pageTbl")}}') {
                    setTimeout(() => {
                        $('#questions_table').DataTable().page(parseInt('{{session()->get("pageTbl")}}')).draw("page");
                    }, 200);
                }

                $('#checkbox_all').on('click',function (e){
                    $('input:checkbox').not(this).prop('checked', this.checked);
                })
                $(document).on('click','#showQues',function (e){
                   let data= $(this).attr('data-question');
                    $('#more').empty()
                    $('#more').append(data)
                    UIkit.modal('#modal-more-info').show();

                })
                $(document).on('click','#delete_row',function (e){
                    e.preventDefault();
                    var rowID=$(this).attr('data-id');
                    let url = `/quiz/${rowID}`
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
                        var url='/delete-all-quiz'
                        deleteRow(selectids,url)
                    }else{
                        Swal.fire("warning!", "Please Select Quiz First.", "warning");
                    }

                });
                $('#modal_classes').on('click',function (e){
                    e.preventDefault();
                    var selectids = [];
                    $.each($("input:checkbox:checked"), function(){
                        if($(this).val() !='on'){
                            selectids.push($(this).val());
                        }
                    });
                    if(selectids.length>0){
                        $('#roomIds').val(selectids);
                        UIkit.modal('#modal-classes').show();

                    }else{
                        Swal.fire("warning!", "Please Select Room First.", "warning");

                    }

                });
                $('#save_move_room_to_classes').on('click',function () {
                    var classRoomIds = $('#class_room_ids').val();
                    var roomIds = $('#roomIds').val();
                    $.ajax({
                        url: "/move-room-to-classes",
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {roomIds: roomIds, classroomids: classRoomIds},
                        success: function (res) {
                            if (res.status) {
                                Swal.fire("Done", "Record delete successful.", "success");
                            }
                            let page = $('#table_page').val()
                            room_table.ajax.reload();
                            page ? $('#questions_table').DataTable().page(parseInt(page)).draw("page") :null
                            UIkit.modal('#modal-classes').hide();

                        },
                        error: function (res) {
                            Swal.fire("Close!", "Something is wrong, Please try again.", "error");
                        }
                    });
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
                                    room_table.ajax.reload();
                                    page ? $('#questions_table').DataTable().page(parseInt(page)).draw("page") :null
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

                $('#class_room_id').on('change',function (){
                    room_table.ajax.reload();
                });

                $('#class_room_ids ,#class_room_id').select2({
                    placeholder:'Select '
                })

                $(document).on('click', '#draft', function (e) {
                    let id= $(this).attr('data-id')
                    e.preventDefault();
                    let accetp_swal = Swal.fire({
                        title: " Are You Sure To Change ?",
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

                            // ajax here
                            $.ajax({
                                url: `publish-room/${id}`,
                                type: "get",
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                },
                                data: {id: id},
                                success: function (res) {
                                    if(res.status){
                                        Swal.fire("Done",res.message, "success");
                                        window.location.reload()
                                    }
                                },
                                error:function (res) {
                                    Swal.fire("Close!", "Something is wrong, Please try again.", "error");
                                }
                            });


                        } else {
                            Swal.fire("Close", "Close Success", "success");
                        }
                    })
                });
                $('#filter_quiz').on('keyup',function (){
                    room_table.ajax.reload();
                });
                $('#type').on('change',function (){
                    room_table.ajax.reload();
                });
            });
        </script>
    @endsection
