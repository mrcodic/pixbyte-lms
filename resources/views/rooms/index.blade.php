@extends('layouts.app')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/rowreorder/1.2.8/css/rowReorder.dataTables.min.css"/>
    <style>
        .draft {
            background: #fbe3b2;
        }
    </style>

@endsection
@section('title', 'Rooms')


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
                        <h3 class="uk-margin-remove-bottom title-add">My Rooms</h3>
                    </div>
                    <div class="uk-width-auto">
                        <a href="{{ route('room.create')}}" class="uk-button uk-button-primary border-radius uk-padding-remove-t-b "><span class="add-icon uk-icon uk-margin-small-right" uk-icon="icon:plus; ratio: .7"></span>Add New Room</a>
                    </div>
                    <div class="line divider"></div>
                </div>
            </div>

        </div>
         {{-- <div class="uk-width-expand uk-margin-top">
                <div class="uk-form-controls">
                    <button class="edit uk-margin-small-right" id="modal_classes" type="button" >Add to Classroom</button>
                    <a class="delete uk-margin-small-right" id="delete_all" >Delete</a>
                </div>
            </div> --}}

        <div class="uk-container uk-container-expand rm-expand uk-margin-medium-top mt-s-20 uk-margin-left">
            <div class="uk-form-controls">
                @if(!request()->has('class_room_id'))
                    <button class="edit uk-margin-small-right" id="modal_classes" type="button" >Add to Classroom</button>
                @endif

                @if(!request()->has('class_room_id'))
                <a class="delete uk-margin-small-right" data-type="delete" id="delete_all" >Delete</a>
                @else
                <a class="delete uk-margin-small-right" data-type="remove" id="delete_all" >Remove from class</a>
                @endif
            </div>
                <div class="uk-overflow-auto uk-margin-medium-top x-scrollbar rm-s">

                    @if(!request()->has('class_room_id'))
                    <div class="uk-margin-small-right inline-block left uk-width-1-6@m uk-width-1-2@s mb-s-20">
                        <select class="uk-select uk-width-1-1" id="class_room_id" name="class_room_id">
                            <option selected disabled>Select Classroom</option>
                            <option value="">all</option>
                            @foreach ( $classRooms as $classRoom )
                                <option value=" {{$classRoom->id}} ">{{ $classRoom->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                <table class="uk-table uk-table-hover uk-table-middle uk-table-divider rooms" id="room_table" style="width:100%;">
                    <input type="hidden" name="select_all" id="select_all" value="">
                    <input type="hidden" id="table_page" value="">
                    @include('components.search-datatable',['id'=>'filter_room'])
                    <thead>
                    <tr>
                        @if(request()->has('class_room_id'))
                            <th class="uk-table-expand">Order</th>
                        @endif
                        <th class="uk-table-shrink"><input id="checkbox_all" class="uk-checkbox" type="checkbox"></th>
                        <th class="uk-table-expand">Room title</th>
                        <th class="uk-table-expand">Classroom</th>
                        <th class="uk-width-small">Last update</th>
                        <th class="uk-table-expand">Price</th>
                        <th class="uk-table-shrink uk-text-nowrap">Action</th>
                    </tr>
                    </thead>
                    <tbody id="tabletelscope">
                    </tbody>
                </table>
            </div>
            <div class="uk-width-expand uk-margin-top">
                <div class="uk-form-controls">
                    @if(!request()->has('class_room_id'))
                    <button class="edit uk-margin-small-right" id="modal_classes" type="button" >Add to Classroom</button>
                    @endif

                    @if(!request()->has('class_room_id'))
                    <a class="delete uk-margin-small-right" data-type="delete" id="delete_all" >Delete</a>
                    @else
                    <a class="delete uk-margin-small-right" data-type="remove" id="delete_all" >Remove from class</a>
                    @endif
{{--                    uk-toggle="target: #modal-classes"--}}
                    <span class="dark-font">selected entries</span>

                </div>
            </div>
        </div>
        @include('rooms.addClassRoomModal')
    </div>
@endsection
    @section('script')
        @if(request()->has('class_room_id'))
{{--            <script src="https://cdn.datatables.net/rowreorder/1.2.8/js/dataTables.rowReorder.min.js"></script>--}}
{{--            <script src="https://editor.datatables.net/extensions/Editor/js/dataTables.editor.min.js"></script>--}}
            <script src="https://cdn.datatables.net/select/1.4.0/js/dataTables.select.min.js"></script>
            <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
            <script type="text/javascript" src="//code.jquery.com/ui/1.12.1/jquery-ui.js" ></script>

        @endif
        <script>
            var editor;

            $( document ).ready(function() {


                var room_table
                room_table =  $('#room_table').DataTable({

                    processing: true,
                    serverSide: true,
                    lengthMenu: [5, 10, 20, 50,100,200],
                    @if(request()->has('class_room_id'))
                    pageLength: 200,
                    @else
                    pageLength: 10,
                    @endif
                    responsive: true,
                    searching:false,
                    // sorting:false,

                    dom: 'Blfrtip',
                    @if(request()->has('class_room_id'))
                    pageLength: 200,
                    rowReorder: {
                        selector: 'tr td:not(:first-of-type,:last-of-type)',
                        editor:  editor,
                    },
                    order: [[0, 'asc']],
                    select: true,
                    @endif
                    "language": {
                        "processing":
                            `<div class="db-loader">
                        <span class='fa-stack fa-lg'>
                            <i class='fa fa-spinner fa-spin fa-stack-2x fa-fw'></i>
                           </span>Loading ... </div>`,
                    },
                    ajax: {
                        url: "{{route('get-room-data')}}",
                        data: function (d) {
                            d.class_room_id=$('#class_room_id').val();
                                @if(request()->has('class_room_id'))
                                d.class_room_id={{request('class_room_id')}}
                                @endif
                                    d.name=$('#filter_room').val();

                        },
                        beforeSend: function () {
                            $('#table_page').val($('#room_table').DataTable().page())
                        }
                        // <input type="hidden" id="table_page" value="">

                        // ?pageTbl='+$('#table_page').val()+'

                        // let page = $('#table_page').val()
                        // page ? $('#room_table').DataTable().page(parseInt(page)).draw("page") :null
                    },

                    columns: [
                            @if(request()->has('class_room_id'))
                        {data: 'readingOrder', className: 'reorder' ,visible: false, searchable: false},
                            @endif
                        {
                            data:'id',orderable: false, render:function (data,type,full){
                                return `<input class="uk-checkbox" data-classroom_id="${full['class_room_ids']}" id="checkbox" value="${data}" type="checkbox">`;}
                        },
                        {data: 'title' ,@if(request()->has('class_room_id')) className: 'reorder uk-text-left' @else className: ' uk-text-left' @endif ,
                                render:function (data,type,full){
                                return `<a class="link" href="/lessons?room_id=${full['id']}">${data}</a> `} },
                        {data: 'class_room', name: 'class_room',@if(request()->has('class_room_id')) className: 'reorder uk-text-left' @else className: ' uk-text-left' @endif },
                        {data: 'created_at', name: 'created_at',@if(request()->has('class_room_id')) className: 'reorder uk-text-left' @else className: ' uk-text-left' @endif },
                        {data: 'price', name: 'price', className: ' uk-text-left' },
                        {data: 'id',className:'uk-text-nowrap',render:function (data,type,full){
                            let draft
                            if(full['is_draft']){
                                draft= `<a class="uk-button uk-button-default uk-button-small border-radius draft"  data-id="${data}" id="draft" href="#"> Publish </a>`
                            }else{
                                draft= `<a class="uk-button uk-button-default uk-button-small border-radius draft" id="draft" data-id="${data}" href="#">Draft</a>`

                            }
                                return `
                            <a class="edit" href="/room/${data}/edit?pageTbl=${$('#table_page').val()}">Edit</a>
                            @if(!request()->has('class_room_id'))
                            <a class="delete" id="delete_row" data-type="delete" data-id="${data}">Delete</a>

                            @else
                            <a class="delete" id="delete_row" data-type="remove" data-id="${data}">remove from class</a>

                            @endif

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

                if ('{{session()->get("pageTbl")}}') {
                    setTimeout(() => {
                        $('#room_table').DataTable().page(parseInt('{{session()->get("pageTbl")}}')).draw("page");
                    }, 200);
                }


                $('#checkbox_all').on('click',function (e){
                    $('input:checkbox').not(this).prop('checked', this.checked);
                })
                $(document).on('click','#delete_row',function (e){
                    e.preventDefault();
                    var rowID=$(this).attr('data-id');
                    let url = `/room/${rowID}`
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
                    var ids ;
                    $.each($("input:checkbox:checked"), function(){
                        if($(this).val() !='on'){
                            selectids.push($(this).val());
                             ids=$(this).attr('data-classroom_id')
                        }
                    });
                    if(selectids.length>0){
                        $('#roomIds').val(selectids);
                        UIkit.modal('#modal-classes').show();
                       if(selectids.length==1){

                           let arr=ids.split(',')

                           $('#class_room_ids').val(arr).trigger('change');
                       }
                    }else {
                        Swal.fire("warning!", "Please Select Room First.", "warning");

                    }

                });
                $('#save_move_room_to_classes').on('click',function () {
                    var room_ids = $('#roomIds').val();
                    var class_room_ids = $('#class_room_ids').val();
                    $.ajax({
                        url: "/move-room-to-classes",
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {roomIds: room_ids, class_room_ids: class_room_ids},
                        success: function (res) {
                            if (res.status) {
                                Swal.fire("Done", "Record delete successful.", "success");
                            }
                            let page = $('#table_page').val()
                            room_table.ajax.reload();
                            page ? $('#room_table').DataTable().page(parseInt(page)).draw("page") :null
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
                                data: {idds: id,'_method':"DELETE",'classroom_id':"{{request('class_room_id')}}"},
                                success: function (res) {
                                    if(res.status){
                                        Swal.fire("Done", "Record delete successful.", "success");

                                    }
                                    let page = $('#table_page').val()
                                    room_table.ajax.reload();
                                    page ? $('#room_table').DataTable().page(parseInt(page)).draw("page") :null
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
                @if(request()->has('class_room_id'))
                {{--room_table.on('row-reorder', function (e, details) {--}}
                {{--    if(details.length) {--}}
                {{--        let rows = [];--}}
                {{--        details.forEach(element => {--}}
                {{--            rows.push({--}}
                {{--                id: room_table.row(element.node).data().id,--}}
                {{--                position:element.newPosition,--}}
                {{--            });--}}
                {{--        });--}}
                {{--        var class_room={{request('class_room_id')}}--}}
                {{--        $.ajax({--}}
                {{--            headers: {'x-csrf-token': $('meta[name="csrf-token"]').attr('content')},--}}
                {{--            method: 'POST',--}}
                {{--            url: "/order-room",--}}
                {{--            data: { rows,class_id:class_room }--}}
                {{--        }).done(function () { room_table.ajax.reload() });--}}
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
                    var class_room={{request('class_room_id')}}
                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: "/order-room",
                        data: {
                            order:order,
                            _token: '{{csrf_token()}}',
                            class_id:class_room
                        },
                        success: function(response) {
                            let page = $('#table_page').val()
                            room_table.ajax.reload()
                            page ? $('#room_table').DataTable().page(parseInt(page)).draw("page") :null

                        }
                    });

                }
                @endif

                $('#room_ids ,#class_room_id,#class_room_ids').select2({
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
                                data: {id: id,classroom_id:"{{request('class_room_id')}}"},
                                success: function (res) {
                                    if(res.status){
                                        Swal.fire("Done",res.message, "success");
                                        // window.location.reload()
                                        let page = $('#table_page').val()
                                        room_table.ajax.reload()
                                        page ? $('#room_table').DataTable().page(parseInt(page)).draw("page") :null

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
                $('#filter_room').on('keyup',function (){
                    room_table.ajax.reload();
                });
            });
        </script>

    @endsection
