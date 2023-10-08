@extends('layouts.app')

@section('css')
<style>
    .success {
        background: #0de37a;
    }
</style>
@endsection
@section('title', 'Questions')

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
                        <h3 class="uk-margin-remove-bottom title-add">My Questions</h3>
                    </div>
                    <div class="uk-width-auto">
                        <a href="{{route('question.create')}}" class="uk-button uk-button-primary border-radius uk-padding-remove-t-b "><span class="add-icon uk-icon uk-margin-small-right" uk-icon="icon:plus; ratio: .7"></span>Add New Question</a>
                    </div>
                    <div class="line divider"></div>
                </div>
            </div>

        </div>

        <div class="uk-container uk-container-expand rm-expand uk-margin-medium-top uk-margin-left">
            <div class="uk-overflow-auto uk-margin-medium-top x-scrollbar">
                <table class="uk-table uk-table-hover uk-table-middle uk-table-divider rooms" id="questions_table" style="width:100%;">
                    <input type="hidden" name="select_all" id="select_all" value="">
                    <input type="hidden" id="table_page" value="">
                    @include('components.search-datatable',['id'=>'filter_question'])

                    <thead>
                    <tr>
                        <th class="uk-table-shrink"><input id="checkbox_all" class="uk-checkbox" type="checkbox"></th>
                        <th class="uk-table-expand">title</th>
                        <th class="uk-table-expand">Category</th>
                        <th class="uk-table-expand">Question Bank</th>
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
    </div>
    @include('questions.answerDescModal')

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
                        url: "{{route('get-question-data')}}",
                        data: function (d) {
                            d.class_room_id=$('#class_room_id').val();
                            d.name=$('#filter_question').val();
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
                            data: 'question', className: ' uk-text-left',
                            render:function (data,type,full){
                                return `<a id="moreInfo" data-id="${full['id']}">${data}</a>`;
                            }
                        },
                        {
                            data: 'category', className: ' uk-text-left', render:function (data,type,full){
                                return `<span>${data}</span> - <span>${full['sub_category']}</span>`;}
                        },
                        {
                            data: 'questionBank', className: ' uk-text-left'
                        },
                        {data: 'created_at', name: 'created_at', className: ' uk-text-left'  },
                        {data: 'id',className:'uk-text-nowrap',render:function (data,type,full){
                                return `
                            <a class="edit" href="/question/${data}/edit?pageTbl=${$('#table_page').val()}">Edit</a>
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


                if ('{{request("pageTbl")}}') {
                    setTimeout(() => {
                        $('#questions_table').DataTable().page(parseInt('{{request("pageTbl")}}')).draw("page");
                    }, 200);
                }

                $('#checkbox_all').on('click',function (e){
                    $('input:checkbox').not(this).prop('checked', this.checked);
                });
                $(document).on('click','#delete_row',function (e){
                    e.preventDefault();
                    var rowID=$(this).attr('data-id');
                    let url = `/question/${rowID}`
                    deleteRow(rowID,url)

                });
                $('#delete_all').on('click',function (e){
                    e.preventDefault();
                    var selectids = [];
                    $.each($("input:checkbox:checked"), function(){
                        if($(this).val() !='on'){
                            selectids.push($(this).val());
                        }
                    });
                    if(selectids.length>0){
                        var url='/delete-all-questions'
                        deleteRow(selectids,url)
                    }else{
                        Swal.fire("warning!", "Please Select Room First.", "warning");
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
                $(document).on('click','#moreInfo',function (e){
                    $('.card-question span').empty();
                    $('#more .uk-list').empty();
                    $('#answer_desc_div').empty();
                    UIkit.modal('#modal-more-info').show();
                    $('.loader.spinner').show();
                    let question= $(this).attr('data-id');
                    $.ajax({
                        url: `/get_answer/${question}`,
                        type: "get",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        },
                        success: function (res) {
                            if(res.status){
                                $('.card-question span').append(res.data.title)
                                res.data.answers.forEach((item,index)=>{
                                    let correct=''
                                    if(item.correct){
                                        correct='success'
                                    }
                                    let valueInput
                                    if(item.status){
                                        valueInput=item.valueCk
                                    }else{
                                        valueInput=item.valueInput
                                    }
                                    $('.uk-list').append(`
                                    <div class="answer-wrapper">
                                        <label class="answar uk-width-1-1 uk-card uk-card-body uk-margin-small-bottom ${correct}" for="">
                                            <input class="notAnswered"  type="radio" value="${index}" >
                                            <span class="checkmark"></span>
                                            <span class="uk-margin-medium-left">
                                                ${valueInput}
                                            </span>
                                        </label>
                                    </div>
                                    `)
                                });
                                if(res.data.answer_desc){
                                    $('#answer_desc').show();
                                    $('#answer_desc_div').append(
                                    `
                                    <iframe src="${res.data.answer_desc}" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen style="top:0;left:0 ;width: 100%;height: 100%;"></iframe>

                                    `

                                    )
                                }
                                $('.loader.spinner').hide();
                            }

                        },
                        error:function (res) {
                            Swal.fire("Close!", "Something is wrong, Please try again.", "error");
                        }
                    });

                })
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
                $('#filter_question').on('keyup',function (){
                    room_table.ajax.reload();
                });
            });
        </script>
    @endsection
