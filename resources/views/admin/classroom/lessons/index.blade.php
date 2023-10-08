@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Lessons List')

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
    <!-- lessons list start -->
    <section class="app-lesson-list">
        <!-- list and filter start -->
        <div class="card">
            <div class="card-datatable table-responsive pt-0">
                <table class="lesson-list-table table" id="lesson_table">
                    <input type="hidden" name="select_all" id="select_all" value="">
                    <input type="hidden" id="table_page" value="">

                    <thead class="table-light">
                    <tr>
                        <th><input id="checkbox_all" class="uk-checkbox" type="checkbox"></th>
                        <th >Title</th>
                        <th >Instructor</th>
                        <th >Video</th>
                        <th >Duration</th>
                        <th >Description</th>
                        <th >Status</th>
                        <th >Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            @include('admin.classroom.lessons._create')
        </div>
        <!-- list and filter end -->
    </section>
    <!-- lessons list ends -->
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
    {{-- <script src="{{ asset('admin/vendors/js/forms/cleave/cleave.min.js') }}"></script>
    <script src="{{ asset('admin/vendors/js/forms/cleave/addons/cleave-phone.us.js') }}"></script> --}}
@endsection

@section('page-script')
    {{-- Page js files --}}
    <script src="{{ asset('admin/js/helper.js') }}"></script>
    <script>
        $( document ).ready(function() {

            var lesson_table

            lesson_table =  $('#lesson_table').DataTable({
                processing: true,
                serverSide: true,
                lengthMenu: [5, 10, 20, 50],
                pageLength: 10,
                responsive: true,
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
                    url: "{{route('admin.get_lessons',$room??'')}}",
                    // data: function (d) {
                    //     // d.grade_id=$('#grade_id_filter').val();
                    // },
                    beforeSend: function () {
                        $('#table_page').val($('#lesson_table').DataTable().page())
                    },
                },
                columns: [

                    {
                        data:'id',orderable: false, render:function (data,type,full){
                            return `<input class="uk-checkbox" id="checkbox" value="${data}" type="checkbox">`;}
                    },
                    {data: 'title' , className: ' uk-text-left'
                        // render:function (data,type,full){
                        //     return `<a class="link" href="{route('admin.lesson.lessons','')}}/${full['id']}">${data}</a> `
                        // }
                    },
                    {data: 'instructor' , className: ' uk-text-left'},
                    {data: 'video' , className: ' uk-text-left',
                        render:function (data,type,full){
                            return `<a class="link" class="d-inline-block text-truncate" style="max-width: 150px;" href="${data}">${data}</a> `
                        }
                    },
                    {data: 'duration' , className: ' uk-text-left'},
                    {data: 'description' , className: ' uk-text-left' },
                    {data: 'status' , className: ' uk-text-left' },
                    {data: 'id',className:'uk-text-nowrap',render:function (data,type,full){
                            return (
                                '<div class="btn-group">' +
                                '<a class="btn btn-sm dropdown-toggle hide-arrow" data-bs-toggle="dropdown">' +
                                feather.icons['more-vertical'].toSvg({ class: 'font-small-4' }) +
                                '</a>' +
                                '<div class="dropdown-menu dropdown-menu-end">' +
                                `<a href="{{route('admin.lesson.show','')}}/${data}?page_tbl=${$('#table_page').val()}"  id="show_row" class="dropdown-item">` +
                                feather.icons['eye'].toSvg({ class: 'font-small-4 me-50' }) +
                                'Show</a>' +
                                `<a href="{{route('admin.lesson.edit','')}}/${data}?page_tbl=${$('#table_page').val()}"  id="edit_row" class="dropdown-item">` +
                                feather.icons['file-text'].toSvg({ class: 'font-small-4 me-50' }) +
                                'Edit</a>' +
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
                        text: 'Add New lesson',
                        className: 'add-new btn btn-primary',
                        attr: {
                            'data-bs-toggle': 'modal',
                            'data-bs-target': '#modal-lesson'
                        },
                        init: function (api, node, config) {
                            $(node).removeClass('btn-secondary');
                        }
                    }
                ],

            });

            if ('{{session()->get("page_tbl")}}') {
                setTimeout(() => {
                    $('#lesson_table').DataTable().page(parseInt('{{session()->get("page_tbl")}}')).draw("page");
                }, 200);
            }

            $('#checkbox_all').on('click',function (e){
                $('input:checkbox').not(this).prop('checked', this.checked);
            })

            ///////////////////////////////////////////////////
            // Create functionality
            //////////////////////////////////////////////////

            $(document).on('click','#save_lesson',function (e){
                e.preventDefault()

                // console.log(JSON.stringify($('.duration').serialize()));
                // console.log($("input[name='duration[]']").map(function(){return $(this).val()}).get());

                let duration = $('.duration').map(
                    function(){
                        return $(this).val()
                    }).get()

                var formdata = {
                    room_ids:    JSON.stringify($('#room_id').val() )?? '[{{$room ? $room->id :null}}]',
                    user_id:     $('#user_id').val() ?? '{{$room ? $room->user->id :null}}',
                    title:       $('#title').val(),
                    description: $('#description').val(),
                    url_iframe:  $('#url_iframe').val(),
                    duration:    JSON.stringify(duration)
                }

                create_modal(formdata ,'{{route("admin.lesson.create")}}','POST','modal-lesson',lesson_table, false);
            });

            ///////////////////////////////////////////////////
            ///////////////////////////////////////////////////


            $(document).on('click','#delete_row',function (e){
                e.preventDefault();
                var rowID = $(this).attr('data-id');
                let url = '{{route("admin.lesson.delete","")}}/'+rowID
                deleteRow(rowID,url)

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
                                let page = $('#table_page').val()
                                lesson_table.ajax.reload();
                                page ? $('#lesson_table').DataTable().page(parseInt(page)).draw("page") :null


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


            $('#modal-lesson').find('#room_ids').select2({
                allowClear: true,
                dropdownParent: $("#modal-lesson"),
                ajax: {
                    url: '{{route("admin.get_room_instructors")}}',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        var instructor = $('#modal-lesson').find('#user_id option:selected').val()
                        var query = {
                            search: params.term,
                            instructor: instructor,
                            page: params.page || 1
                        }
                        return query;
                    },
                    cache: true
                }
            });

        });
    </script>
@endsection
