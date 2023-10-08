@extends('admin/layouts/contentLayoutMaster')

@section('title', 'categories List')

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
        <div class="row">
            <input type="hidden" id="grade_id_filter_top">

        </div>
        <!-- list and filter start -->
        <div class="card">

            <div class="card-datatable table-responsive pt-0">
                <table class="user-list-table table" id="main_table">
                    <input type="hidden" id="table_page" value="">

                    <input type="hidden" name="select_all" id="select_all" value="">

                    <thead class="table-light">
                    <tr>
                        <th><input id="checkbox_all" class="uk-checkbox" type="checkbox"></th>
                        <th >subcategory title</th>
                        <th >Category</th>
                        <th >Instructor</th>
                        <th >Last update</th>
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

    @include('admin.subcategory._create_edit')
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
                    '<"col-sm-12 col-lg-3 d-flex justify-content-center justify-content-lg-start" l>' +
                    '<"col-sm-12 col-lg-8 ps-xl-75 ps-0"<"dt-action-buttons d-flex align-items-center justify-content-center justify-content-lg-end flex-lg-nowrap flex-wrap"<"me-1 d-block"fb>B>>' +
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
                    url: "{{route('admin.get_subcategories')}}",
                    data: function (d) {
                        d.grade_id = $('#grade_id_filter_top').val() ;
                        d.classroom_id = $('#classroom_id').val();
                        d.user_role = $('#UserRole').val();
                    },
                    beforeSend: function () {
                        $('#table_page').val($('#main_table').DataTable().page())
                    },
                },
                columns: [

                    {
                        data:'id',orderable: false, render:function (data,type,full){
                            return `<input class="uk-checkbox checkboxsubcategory" value="${data}" type="checkbox">`;}
                    },
                    {data: 'name', name: 'name', className: ' uk-text-left'  },
                    {data: 'category', name: 'category', className: ' uk-text-left'  },
                    {data: 'instructor', name: 'instructor', className: ' uk-text-left'  },
                    {data: 'updated_at', name: 'updated_at', className: ' uk-text-left'  },
                    {data: 'id',className:'uk-text-nowrap',render:function (data,type,full){
                            return (
                                '<div class="btn-group">' +
                                '<a class="btn btn-sm dropdown-toggle hide-arrow" data-bs-toggle="dropdown">' +
                                feather.icons['more-vertical'].toSvg({ class: 'font-small-4' }) +
                                '</a>' +
                                '<div class="dropdown-menu dropdown-menu-end">' +
                                '<a  class="dropdown-item edit_row" data-id="'+data+'" data-name="'+full["name"]+'" data-categoryid="'+full["category_id"]+'" data-bs-toggle="modal" data-bs-target="#modal-subcategory">' +
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
                        text: 'Add New subcategory',
                        className: 'add-new btn btn-primary px-1 fs-6',
                        attr: {
                            'data-bs-toggle': 'modal',
                            'data-bs-target': '#modal-subcategory'
                        },
                        init: function (api, node, config) {
                            subcategoryId();
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




            $(document).on('click','.add-new',function (e){
                $('#titleModalLabel').text("add subcategory")
                $('#modal-subcategory input').val(null)
                $('#modal-subcategory select').val(null).trigger('change')
                $('#modal-subcategory #type').val("add")
            })

            $(document).on('click','.edit_row',function (e){
                $('#titleModalLabel').text("edit subcategory")

                $('#modal-subcategory input').val(null)
                $('#modal-subcategory select').val(null).trigger('change')

                $('#modal-subcategory #type').val("edit")
                $('#modal-subcategory #id').val($(this).data('id'))
                $('#modal-subcategory #title').val($(this).data('name'))
                $('#modal-subcategory #category_id').val($(this).data('categoryid')).change()

                $('#modal-subcategory').show()
            })


            $(document).on('click','#save_subcategory',function (e){
                e.preventDefault()
                var formdata= {
                    name: $('#title').val(),
                    category_id: $('#category_id').val()
                }
                let page = $('#table_page').val()

                if($('#type').val() == 'add'){
                    create_modal(formdata,'/admin/subcategory/create','POST','modal-user',main_table)
                }
                else if($('#type').val() == 'edit'){
                    // formdata.id= $('#id').val(),
                    create_modal(formdata,'/admin/subcategory/edit/'+$('#id').val(),'POST','modal-user',main_table)
                }

                page ? $('#main_table').DataTable().page(parseInt(page)).draw("page") :null
            });


            $(document).on('click','#delete_row',function (e){
                e.preventDefault();
                var rowID=$(this).attr('data-id');
                let url = `/admin/subcategory/${rowID}`
                deleteRow(rowID,url)

            })

            $(document).on('click','.SelectorToDelete',function (e){
                e.preventDefault();
                var selectids = [];
                $('.checkboxsubcategory').each(function (loop) {
                    this.checked ? selectids.push($(this).val()) :null;
                });
                if(selectids.length>0){
                    var url='/admin/delete-all-subcategory'
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
                let subcategory_id = $(this).attr('data-subcategory');
                $.ajax({
                    url: '/admin/delete-device',
                    type: "GET",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    data: {index: index,subcategory_id:subcategory_id},
                    success: function (res) {
                        if(res.status){
                            main_table.ajax.reload();
                            Swal.fire("Done", "Record delete successful.", "success");

                        }
                        let page = $('#table_page').val()
                        main_table.ajax.reload();
                        page ? $('#main_table').DataTable().page(parseInt(page)).draw("page") :null
                        $('#modal-subcategory-ip').modal('hide')

                    },
                    error:function (res) {
                        Swal.fire("Close!", "Something is wrong, Please try again.", "error");
                    }
                });
            });

        });

        $('#setIdsubcategory').click(function(){
            subcategoryId();
        })

        function subcategoryId(){
            if($('#setIdsubcategory').is(':checked')){
                $('#name_id').removeClass('hidden');
            }else{
                $('#name_id').addClass('hidden');
            }
        }

    </script>
@endsection
