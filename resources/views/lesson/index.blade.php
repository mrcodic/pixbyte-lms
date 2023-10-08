@extends('layouts.app')
@section('title', 'Lessons')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/rowreorder/1.2.8/css/rowReorder.dataTables.min.css" />
    <style>
        .draft {
            background: #fbe3b2;
        }
    </style>
@endsection
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
                    <h3 class="uk-margin-remove-bottom title-add">My Lessons</h3>
                </div>
                <div class="uk-width-auto">
                    <a href="{{ route('lessons.create')}}" class="uk-button uk-button-primary border-radius uk-padding-remove-t-b"><span class="add-icon uk-icon uk-margin-small-right" uk-icon="icon:plus; ratio: .7"></span>Add New Lesson</a>
                </div>
                <div class="line divider"></div>
            </div>
        </div>

    </div>

    <div class="uk-container uk-container-expand rm-expand uk-margin-medium-top uk-margin-left">
        <div class="uk-overflow-auto uk-margin-medium-top x-scrollbar rm-s">
            <table class="uk-table uk-table-hover uk-table-middle uk-table-divider rooms" id="lesson_table" style="width:100%;">
                @if(!request()->has('room_id'))

                <div class="uk-margin-small-right inline-block left uk-width-1-6@m uk-width-1-2@s mb-s-20">
                    <select class="uk-select " id="room_id" name="room_id">
                        <option selected disabled>Select Room</option>
                        <option value="">all</option>
                        @foreach ( $rooms as $room )
                            <option value=" {{$room->id}} ">{{ $room->title }}</option>
                        @endforeach
                    </select>
                    @endif
                </div>
                <input type="hidden" name="select_all" id="select_all" value="">
                    <input type="hidden" id="table_page" value="">
                    @include('components.search-datatable',['id'=>'filter_lesson'])

                    <thead>
                    <tr>
                        @if(request()->has('room_id'))
                            <th class="uk-table-expand">Order</th>
                        @endif
                        <th class="uk-table-shrink"><input id="checkbox_all" class="uk-checkbox" type="checkbox"></th>
                        <th class="uk-table-expand">Lesson title</th>
                        <th class="uk-table-expand">Room</th>
                        <th class="uk-width-small">Last update</th>
                        <th class="uk-table-shrink uk-text-nowrap">Action</th>
                    </tr>
                </thead>
                    <tbody id="tabletelscope">
                    </tbody>
            </table>
        </div>
        <div class="uk-width-expand uk-margin-top">
            <div class="uk-form-controls">
                <button class="edit uk-margin-small-right" id="modal_classes" type="button" >Add to Room</button>
                <a class="delete uk-margin-small-right" id="delete_all" >Delete</a>
                <span class="dark-font">selected entries</span>

            </div>
        </div>
    </div>
    @include('lesson.addRoomModal')

</div>
@endsection
@section('script')
    @if(request()->has('room_id'))
{{--        <script src="https://cdn.datatables.net/rowreorder/1.2.8/js/dataTables.rowReorder.min.js"></script>--}}
{{--        <script src="https://editor.datatables.net/extensions/Editor/js/dataTables.editor.min.js"></script>--}}
            <script src="https://cdn.datatables.net/select/1.4.0/js/dataTables.select.min.js"></script>
            <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
            <script type="text/javascript" src="//code.jquery.com/ui/1.12.1/jquery-ui.js" ></script>
    @endif
    <script>
        $( document ).ready(function() {
            var editor;
            let lesson_table=''
            lesson_table  =  $('#lesson_table').DataTable({
                columnDefs: [
                        { "width": "30%", "targets": 2 },
                        { "width": "20%", "targets": 3 },
                        { "width": "20%", "targets": 4 },
                    ],
                processing: true,
                serverSide: true,
                lengthMenu: [5, 10, 20, 50],
                pageLength: 10,
                responsive: true,
                searching:false,
                dom: 'Blfrtip',
                @if(request()->has('room_id'))
                rowReorder: {
                    selector: 'tr td:not(:first-of-type,:last-of-type)',
                    order: [[0, 'asc']],
                    select: true,
                },
                select: true,
                @endif
                    ajax: {
                    url: "{{route('get-lesson-data')}}",
                    data: function (d) {
                        d.room_id=$('#room_id').val()
                           @if(request()->has('room_id'))
                            d.room_id={{request('room_id')}}
                            @endif
                            d.name=$('#filter_lesson').val();
                    },


                },
                "language": {
                    "processing":
                        `<div class="db-loader">
                        <span class='fa-stack fa-lg'>
                            <i class='fa fa-spinner fa-spin fa-stack-2x fa-fw'></i>
                           </span>Loading ... </div>`,
                },
                columns: [
                        @if(request()->has('room_id'))
                    {data: 'readingOrder', className: 'reorder' ,visible: false, searchable: false},
                        @endif
                    {data:'id',sortable:false,render:function (data,type,full){
                        return `
                        <input class="uk-checkbox" id="checkbox" value="${data}" type="checkbox">
                        `;
                        }
                    },
                    {data: 'title', name: 'title',@if(request()->has('room_id')) className: 'reorder uk-text-left' @endif},
                    {data: 'room', name: 'room',@if(request()->has('room_id')) className: 'reorder uk-text-left' @endif},
                    {data: 'updated_at', name: 'updated_at',@if(request()->has('room_id')) className: 'reorder uk-text-left' @endif},
                    {data: 'id',className:'uk-text-nowrap',render:function (data,type,full){
                            let draft
                            if(full['is_draft']){
                                draft= `<a class="uk-button uk-button-default uk-button-small border-radius draft"  data-id="${data}" id="draft" href="#"> Publish </a>`
                            }else{
                                draft= `<a class="uk-button uk-button-default uk-button-small border-radius draft" id="draft" data-id="${data}" href="#">Draft</span></a>`

                            }
                        return `
                        <a class="edit" href="/lessons/${data}/edit">Edit</a>
                            <a class="delete" id="delete_row" data-id="${data}">Delete</a>
                     ${draft}
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
            $(document).on('click','#delete_row',function (e){
                e.preventDefault();
                let rowID=$(this).attr('data-id');
                let url = `/lessons/${rowID}`
                deletedRow(rowID,url)

            })

            $('#checkbox_all').on('click',function (e){
                $('input:checkbox').not(this).prop('checked', this.checked);
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
                    let url = '/delete-all-lesson'
                    deletedRow(selectids,url)
                }else{
                    Swal.fire("warning!", "Please Select Lesson First.", "warning");

                }


        });

            function deletedRow(id,url){
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
                                lesson_table.ajax.reload();
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

            $('#room_id').on('change',function (){
                lesson_table.ajax.reload();
            })
            @if(request()->has('room_id'))
            {{--lesson_table.on('row-reorder', function (e, details) {--}}
            {{--    if(details.length) {--}}
            {{--        let rows = [];--}}
            {{--        details.forEach(element => {--}}
            {{--            console.log(element);--}}
            {{--            rows.push({--}}
            {{--                id: lesson_table.row(element.node).data().id,--}}
            {{--                position:element.newPosition,--}}
            {{--            });--}}
            {{--        });--}}
            {{--        var room={{request('room_id')}}--}}
            {{--        $.ajax({--}}
            {{--            headers: {'x-csrf-token': $('meta[name="csrf-token"]').attr('content')},--}}
            {{--            method: 'POST',--}}
            {{--            url: "/order-lesson",--}}
            {{--            data: { rows,room_id:room }--}}
            {{--        }).done(function () { lesson_table.ajax.reload() });--}}
            {{--    }--}}

            {{--});--}}

            $( "#tabletelscope" ).sortable({
                items: "tr",
                cursor: 'move',
                opacity: 0.6,
                update: function() {
                    sendOrderToServer();
                }
            });


            function sendOrderToServer() {

                var order = [];
                $('tr').each(function(index,element) {
                    if($(this).find('input')[0].value!=="on"){
                        order.push({
                            id: $(this).find('input')[0].value,
                            position: index
                        });
                    }
                });
                var room={{request('room_id')}}
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "/order-lesson",
                    data: {
                        order:order,
                        _token: '{{csrf_token()}}',
                        room_id:room
                    },
                    success: function(response) {
                        let page = $('#table_page').val()
                        lesson_table.ajax.reload()
                        page ? $('#lesson_table').DataTable().page(parseInt(page)).draw("page") :null

                    }
                });

            }
            @endif

            $('#room_id').select2({})
            $('.select2').select2({
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
                            url: `publish-lesson/${id}`,
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


            $('#modal_classes').on('click',function (e){
                e.preventDefault();
                var selectids = [];
                $.each($("input:checkbox:checked"), function(){
                    if($(this).val() !='on'){
                        selectids.push($(this).val());
                    }
                });
                if(selectids.length>0){
                    $('#lessonIds').val(selectids);
                    UIkit.modal('#modal-classes').show();

                }else{
                    Swal.fire("warning!", "Please Select Lesson First.", "warning");

                }

            });

            $('#save_move_room_to_classes').on('click',function () {
                var roomIds = $('#room_ids').val();
                var lessonIds = $('#lessonIds').val();
                $.ajax({
                    url: "/move-lesson-to-rooms",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {roomIds: roomIds, lessonIds: lessonIds},
                    success: function (res) {
                        if (res.status) {
                            Swal.fire("Done", "Record delete successful.", "success");
                        }
                        lesson_table.ajax.reload();
                        UIkit.modal('#modal-classes').hide();

                    },
                    error: function (res) {
                        Swal.fire("Close!", "Something is wrong, Please try again.", "error");
                    }
                });
            });
            $('#filter_lesson').on('keyup',function (){
                lesson_table.ajax.reload();
            });
        });
    </script>

@endsection
