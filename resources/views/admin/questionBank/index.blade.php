@extends('admin/layouts/contentLayoutMaster')

@section('title', 'question-bank')

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
    <!-- question-banks list start -->
    <section class="app-question-bank-list">
        <!-- list and filter start -->
        <div class="card">
            <div class="card-datatable table-responsive pt-0">
                <table class="question-bank-list-table table" id="questions_table">
                    <input type="hidden" name="select_all" id="select_all" value="">
                    <input type="hidden" id="table_page" value="">

                    <thead class="table-light">
                    <tr>
                        <th><input id="checkbox_all" class="uk-checkbox" type="checkbox"></th>
                        <th >Title</th>
                        <th >type</th>
                        <th >Question Number</th>
                        {{-- <th >Instructor</th> --}}
                        <th >Created at</th>
                        <th >Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- list and filter end -->
    </section>
    <!-- question-banks list ends -->
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
            var editor;

            $( document ).ready(function() {
                var question_bank_table
                question_bank_table =  $('#questions_table').DataTable({
                    processing: true,
                    serverSide: true,
                    lengthMenu: [5, 10, 20, 50],
                    pageLength: 10,
                    responsive: true,
                    dom:
                        '<"d-flex justify-content-between align-items-center header-actions mx-2 row mt-75"' +
                        '<"col-sm-6 col-lg-2 d-flex justify-content-center justify-content-lg-start" l>' +
                        // '<"col-sm-6 col-lg-2 d-flex justify-content-center justify-content-lg-start" <"demoroom">>' +
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
                        url: "{{route('admin.get_question_bank_data')}}",
                        data: function (d) {
                            d.class_room_id=$('#class_room_id').val();
                        },
                        beforeSend: function () {
                            $('#table_page').val($('#questions_table').DataTable().page())
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
                        {   data: 'created_at', name: 'created_at', className: ' uk-text-left'  },
                        {   data: 'id',className:'uk-text-nowrap',render:function (data,type,full){
                            return (
                                    '<div class="btn-group">' +
                                    '<a class="btn btn-sm dropdown-toggle hide-arrow" data-bs-toggle="dropdown">' +
                                    feather.icons['more-vertical'].toSvg({ class: 'font-small-4' }) +
                                    '</a>' +
                                    '<div class="dropdown-menu dropdown-menu-end">' +
                                    `<a href="{{route('admin.question-bank.edit','')}}/${data}?page_tbl=${$('#table_page').val()}"  id="edit_row" class="dropdown-item">` +
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
                            text: 'Add New Question Bank',
                            className: 'add-new btn btn-primary',
                            // attr: {
                            //     'href': "{{route('admin.question_bank.create')}}"
                            // },
                            init: function (api, node, config) {
                                $(node).removeClass('btn-secondary');
                            }
                        }
                    ],
                });


                if ('{{session()->get("pageTbl")}}') {
                    setTimeout(() => {
                        $('#questions_table').DataTable().page(parseInt('{{session()->get("pageTbl")}}')).draw("page");
                    }, 200);
                }

                $('#checkbox_all').on('click',function (e){
                    $('input:checkbox').not(this).prop('checked', this.checked);
                })

                $(document).on('click','.add-new',function (e){
                    window.location.href = '{{route("admin.question_bank.create")}}'
                })

                $(document).on('click','#delete_row',function (e){
                    e.preventDefault();
                    var rowID=$(this).attr('data-id');
                    let url = `{{route("admin.question-bank.delete","")}}/${rowID}`
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
                        var url='{{route("admin.question-bank.delete","")}}'
                        deleteRow(selectids,url)
                    }else{
                        Swal.fire("warning!", "Please Select question-bank First.", "warning");
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
                                    question_bank_table.ajax.reload();
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

                $('#class_question-bank_id').on('change',function (){
                    question_bank_table.ajax.reload();
                });

                $('#class_question-bank_ids ,#class_question-bank_id').select2({
                    placeholder:'Select '
                })

            });
        </script>
    @endsection
