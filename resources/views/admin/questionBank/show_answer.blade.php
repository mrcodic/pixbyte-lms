@extends('layouts.app')
@section('title', 'Answers')

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
                        <h3 class="uk-margin-remove-bottom title-add">My Question Bank</h3>
                    </div>
                    <div class="line divider"></div>
                </div>
            </div>

        </div>

        <div class="uk-container uk-container-expand rm-expand uk-margin-medium-top uk-margin-left">
            <div class="uk-overflow-auto uk-margin-medium-top x-scrollbar">
                <table class="uk-table uk-table-hover uk-table-middle uk-table-divider rooms" id="questions_table" style="width:100%;">
                    <input type="hidden" name="select_all" id="select_all" value="">
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

                    dom: 'Blfrtip',
                    "language": {
                        "processing":
                            `<div style=" display: flex; margin-top: 150px; margin-left: 120px">
                        <span class='fa-stack fa-lg'>
                            <i class='fa fa-spinner fa-spin fa-stack-2x fa-fw'></i>
                           </span>&emsp;Processing ... </div>`,
                    },
                    ajax: {
                        url: "{{route('get-question-bank-data')}}",
                        data: function (d) {
                            d.class_room_id=$('#class_room_id').val();
                        },
                    },

                    columns: [

                        {
                            data:'id',orderable: false, render:function (data,type,full){
                                return `<input class="uk-checkbox" id="checkbox" value="${data}" type="checkbox">`;}
                        },
                        {
                            data:'name',orderable: false,className: ' uk-text-left'
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
                            <a class="edit" href="/question-bank/${data}/edit">Edit</a>
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
                    let url = `/question-bank/${rowID}`
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
                        var url='/delete-all-room'
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
                            room_table.ajax.reload();
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
                                    room_table.ajax.reload();
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
            });
        </script>
    @endsection
