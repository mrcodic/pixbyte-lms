@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Gifts List')

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
    <!-- users list start -->
    <section class="app-user-list">
        <!-- list and filter start -->
        <div class="card">

            <div class="card-datatable table-responsive pt-0">
                <table class="user-list-table table" id="main_table">
                    <input type="hidden" id="table_page" value="">

                    <input type="hidden" name="select_all" id="select_all" value="">

                    <thead class="table-light">
                    <tr>
                        <th><input id="checkbox_all" class="uk-checkbox" type="checkbox"></th>
                        <th >Name</th>
                        <th >Price (points)</th>
                        <th >count items</th>
                        <th >status</th>
                        <th >redemptions</th>
                        <th >Created Date</th>
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
    <!-- users list ends -->

    @include('admin.gift._create_edit')
    @include('admin.gift._settings')
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


            var main_table
            main_table =  $('#main_table').DataTable({
                processing: true,
                ordering:false,
                serverSide: true,
                lengthMenu: [5, 10, 20, 50, 100],
                pageLength: 10,
                responsive: true,
                // searching:false,


                dom:
                    '<"d-flex justify-content-between align-items-center header-actions mx-2 row mt-75"' +
                    '<"col-sm-6 col-lg-2 d-flex justify-content-center justify-content-lg-start" l>' +
                    '<"col-sm-6 col-lg-1 d-flex justify-content-center justify-content-lg-start" <"Setting me-1">>' +
                    '<"col-sm-12 col-lg-9 ps-xl-75 ps-0"<"dt-action-buttons d-flex align-items-center justify-content-center justify-content-lg-end flex-lg-nowrap flex-wrap"<"me-1 d-block"fb>B>>' +
                    '>t' +
                    '<"d-flex justify-content-between mx-2 row mb-1"' +
                    '<"col-sm-12 col-md-6"i>' +
                    '<"col-sm-12 col-md-6"p>' +
                    '>',
                language: {
                    sLengthMenu: 'Show _MENU_',
                    search: '',
                    searchPlaceholder: 'Search..'
                },
                // order: [[0, 'desc']],
                ajax: {
                    url: "{{route('admin.get_gifts')}}",
                    beforeSend: function () {
                        $('#table_page').val($('#main_table').DataTable().page())
                    },
                },
                columns: [

                    {
                        data:'id',orderable: false, render:function (data,type,full){
                            return `<input class="uk-checkbox checkboxgift" value="${data}" type="checkbox">`;}
                    },
                    {data: 'name', name: 'name', className: ' uk-text-left'  },
                    {data: 'price', name: 'price', className: ' uk-text-left'  },
                    {data: 'count', name: 'count', className: ' uk-text-left'  },
                    {data: 'status', name: 'status', className: ' uk-text-left'  },
                    {data: 'redemptions', name: 'redemptions', className: ' uk-text-left'  },
                    {data: 'created_at', name: 'created_at', className: ' uk-text-left'  },
                    {data: 'id',className:'uk-text-nowrap',render:function (data,type,full){
                            return (
                                '<div class="btn-group">' +
                                    '<a class="btn btn-sm dropdown-toggle hide-arrow" data-bs-toggle="dropdown">' +
                                    feather.icons['more-vertical'].toSvg({ class: 'font-small-4' }) +
                                    '</a>' +
                                    '<div class="dropdown-menu dropdown-menu-end">' +
                                        '<a  class="dropdown-item edit_row" data-id="'+data+'" data-name="'+full["name"]+'" data-price="'+full["price"]+'" data-status="'+full["status"]+'"  data-count="'+full["count"]+'"  data-bs-toggle="modal" data-bs-target="#modal-gift">' +
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
                        text: 'Add New gift',
                        className: 'add-new btn btn-primary px-1 fs-6',
                        attr: {
                            'data-bs-toggle': 'modal',
                            'data-bs-target': '#modal-gift'
                        },
                        init: function (api, node, config) {
                            giftId();
                            $(node).removeClass('btn-secondary');
                        }
                    }
                ],

            });



            if ('{{session()->get("page")}}') {
                setTimeout(() => {
                    $('#main_table').DataTable().page(parseInt('{{session()->get("page")}}')).draw("page");
                }, 200);
            }

            $('#checkbox_all').on('click',function (e){
                $('input:checkbox').not(this).prop('checked', this.checked);
            })



            $('div.Setting').html(`<button class="btn btn-outline-secondary px-1" data-bs-toggle='modal' data-bs-target='#redemptionsSettingModal'>Setting</button>`)


            $(document).on('click','.add-new',function (e){
                $('#titleModalLabel').text("add gift")
                $('#modal-gift input').val(null)
                $('#modal-gift select').val(null).trigger('change')
                $('#modal-gift #type').val("add")
            })

            $(document).on('click','.edit_row',function (e){
                $('#titleModalLabel').text("edit gift")

                $('#modal-gift input').val(null)
                $('#modal-category select').val(null).trigger('change')

                $('#modal-gift #type').val("edit")
                $('#modal-gift #id').val($(this).data('id'))
                $('#modal-gift #name').val($(this).data('name'))
                $('#modal-gift #price').val($(this).data('price'))
                $('#modal-gift #count').val($(this).data('count'))

                let status = $(this).data('status') == 'published' ? 1: 0
                $('#modal-gift #status').val(status).trigger('change');

                $('#modal-gift').show()
            })


            $(document).on('click','#save_gift',function (e){
                e.preventDefault()
                var formdata= {
                    name:   $('#name').val(),
                    price:  $('#price').val(),
                    count:  $('#count').val(),
                    status: $('#status').val(),
                    image:  $('#image')[0].files[0],
                }
                let page = $('#table_page').val()

                if($('#type').val() == 'add'){
                    create_modal(formdata,'{{route("admin.gift.store")}}','POST','modal-user',main_table)
                }
                else if($('#type').val() == 'edit'){
                    create_modal(formdata,'{{route("admin.gift.edit","")}}/'+$('#id').val(),'POST','modal-user',main_table)
                }

                page ? $('#main_table').DataTable().page(parseInt(page)).draw("page") :null
            });


            $(document).on('click','#save_setting',function (e){
                e.preventDefault()
                var formdata= {
                    date: $('#redemptionsSettingDate').val(),
                }
                create_modal(formdata,'{{route("admin.gift.setting")}}','POST','redemptionsSettingModal');
            });

            $(document).on('click','#delete_row',function (e){
                e.preventDefault();
                var rowID=$(this).attr('data-id');
                let url = "{{route('admin.gift.delete','')}}/"+rowID
                deleteRow(rowID,url)

            })

            $(document).on('click','.SelectorToDelete',function (e){
                e.preventDefault();
                var selectids = [];
                $('.checkboxgift').each(function (loop) {
                    this.checked ? selectids.push($(this).val()) :null;
                });
                if(selectids.length>0){
                    var url='/admin/delete-all-gift'
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
                                main_table.ajax.reload();
                                page ? $('#main_table').DataTable().page(parseInt(page)).draw("page") :null
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


            $('.grade_id_filter_remove').on('click',function (){
                $('.grade_id_filter_top_click').css({backgroundColor: ''});
                $('#grade_id_filter').val(null)
                $('#grade_id_filter_top').val(null)
                main_table.ajax.reload();
            });


            $('.grade_id_filter_top_click').on('click',function (){

                $('.grade_id_filter_top_click').css({backgroundColor: ''})
                $(this).css({backgroundColor: 'rgba(202, 37, 43, 0.16)'});

                $('#grade_id_filter_top').val($(this).data('grade-id'))

                main_table.ajax.reload();
            });


            $('#grade_id_filter').on('change',function (){
                $('#grade_id_filter_top').val($(this).val())
                main_table.ajax.reload();
            });


            $('#classroom_id').on('change',function (){
                main_table.ajax.reload();
            });
            $('#UserRole').on('change',function (){
                main_table.ajax.reload();
            });
            $(document).on('click','#delete_device',function (){
                let index = $(this).attr('data-id');
                let gift_id = $(this).attr('data-gift');
                $.ajax({
                    url: '/admin/delete-device',
                    type: "GET",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    data: {index: index,gift_id:gift_id},
                    success: function (res) {
                        if(res.status){
                            main_table.ajax.reload();
                            Swal.fire("Done", "Record delete successful.", "success");

                        }
                        let page = $('#table_page').val()
                        main_table.ajax.reload();
                        page ? $('#main_table').DataTable().page(parseInt(page)).draw("page") :null
                        $('#modal-gift-ip').modal('hide')

                    },
                    error:function (res) {
                        Swal.fire("Close!", "Something is wrong, Please try again.", "error");
                    }
                });
            });

        });

        $('#setIdgift').click(function(){
            giftId();
        })

        function giftId(){
            if($('#setIdgift').is(':checked')){
                $('#name_id').removeClass('hidden');
            }else{
                $('#name_id').addClass('hidden');
            }
        }

    </script>
@endsection
