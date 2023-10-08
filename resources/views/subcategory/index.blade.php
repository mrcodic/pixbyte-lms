@extends('layouts.app')
@section('title', 'SubCategory')

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
                    <h3 class="uk-margin-remove-bottom title-add">My SubCategory</h3>
                </div>
                <div class="uk-width-auto">
                    <button uk-toggle="target: #modal-create-edit-subcategory" class="new-add uk-margin-small-left" id="modal_create" type="button" >Create</button>
                </div>
                <div class="line divider"></div>
            </div>
        </div>

    </div>
    @include('subcategory.create_edit_modal')

    <div class="uk-container uk-container-expand rm-expand uk-margin-medium-top uk-margin-left">
        <div class="uk-overflow-auto uk-margin-medium-top x-scrollbar">
            <table class="uk-table uk-table-hover uk-table-middle uk-table-divider rooms" id="subcategory_table">
                <div class="uk-margin-small-right inline-block left">
                    <select class="uk-select " id="category_id_select" name="category_id">
                        <option disabled readonly="">Select Category .....</option>
                        <option value="">all</option>
                        @foreach ( $categories as $category )
                            <option value=" {{$category->id}} ">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <input type="hidden" name="select_all" id="select_all" value="">
                <input type="hidden" id="table_page" value="">
                <thead>
                    <tr>
                        <th class="uk-table-shrink"><input id="checkbox_all" class="uk-checkbox" type="checkbox"></th>
                        <th class="uk-table-expand">Sub Category title</th>
                        <th class="uk-table-expand">Category</th>
                        <th class="uk-width-small">Last update</th>
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
        $( document ).ready(function() {
            let subcategory_table=''
            subcategory_table  =  $('#subcategory_table').DataTable({
                processing: true,
                serverSide: true,
                lengthMenu: [5, 10, 20, 50, 100, 200, 500],
                pageLength: 10,
                responsive: true,
                dom: 'Blfrtip',
                    ajax: {
                    url: "{{route('get-subcategory-data')}}",
                    data: function (d) {
                        d.category_id=$('#category_id_select').val()
                    },
                    beforeSend: function () {
                        $('#table_page').val($('#subcategory_table').DataTable().page())
                    }
                },
                columns: [
                    {data:'id',sortable:false,render:function (data,type,full){
                        return `
                        <input class="uk-checkbox" id="checkbox" value="${data}" type="checkbox">
                        `;
                        }
                    },
                    {data: 'name', name: 'name'},
                    {data: 'category', name: 'category'},
                    {data: 'updated_at', name: 'updated_at'},
                    {data: 'id',className:'uk-text-nowrap',render:function (data,type,full){
                    return `
                        <a class="edit"  id="edit_modal" data-id=${data} data-name=${full['name']} data-category_id=${full['category_id']}>Edit</a>
                            <a class="delete" id="delete_row" data-id="${data}">Delete</a>`;
                    }
                    }
                ]
            });
            $(document).on('click','#delete_row',function (e){
                e.preventDefault();
                let rowID=$(this).attr('data-id');
                let url = `/subcategory/${rowID}`
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
                    let url = '/delete-all-subcategory'
                    deletedRow(selectids,url)
                }else{
                    Swal.fire("warning!", "Please Select Room First.", "warning");

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
                                let page = $('#table_page').val()
                                subcategory_table.ajax.reload();
                                page ? $('#subcategory_table').DataTable().page(parseInt(page)).draw("page") :null
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

            function create_edit_save(type,url){
                var data;
                if(type=='edit'){
                     data={id:$('#subcategory_id').val(),user_id:$('#user_id').val(),name:$('#subcategory_name').val(),category_id:$('#category_id').val(),"_method":"PATCH"}
                }else{
                     data={name:$('#subcategory_name').val(),user_id:$('#user_id').val(),category_id:$('#category_id').val()}
                }
                        $.ajax({
                            url: url,
                            type: "POST",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            },
                            data: data,
                            success: function (res) {
                                if(res.status){
                                    Swal.fire("Done", "successful.", "success");

                                }
                                UIkit.modal('#modal-create-edit-subcategory').hide();
                                let page = $('#table_page').val()
                                subcategory_table.ajax.reload();
                                page ? $('#subcategory_table').DataTable().page(parseInt(page)).draw("page") :null
                            },
                            error:function (res) {
                                Swal.fire("Close!", "Something is wrong, Please try again.", "error");
                            }
                        });
            }
            $(document).on('click','#save_create_edit_modal',function(e){
                e.preventDefault();
                var type = $('#type').val();
                var id = $('#subcategory_id').val()
                let url;
                if(type=='edit'){
                    url=`subcategory/${id}`
                }else{
                    url=`subcategory`;

                }
                create_edit_save(type,url)
            })
            $(document).on('click','#edit_modal',function(e){
                e.preventDefault();
                var name = $(this).attr('data-name');
                var id = $(this).attr('data-id')
                var category_id = $(this).attr('data-category_id')
                var type = 'edit'
                $('#subcategory_id').val(id)
                $('#subcategory_name').val(name)
                $('#category_id').val(category_id);
                $('#category_id').trigger('change');
                $('.header').text ('Edit Category');
                $('#save_create_edit_modal').text ('Edit');
                $('#type').val(type)
                UIkit.modal('#modal-create-edit-subcategory').show();

            })
            $(document).on('click','#modal_create',function(e){
                e.preventDefault();
                var type = 'create'
                $('#subcategory_id').val('')
                $('#category_name').val('')
                $('#category_id').val(null);
                $('#category_id').trigger('change');
                $('.header').text ('Create Category');
                $('#save_create_edit_modal').text ('Add');
                $('#type').val(type)
                UIkit.modal('#modal-create-edit-subcategory').show();

            })

            $('#category_id_select').on('change',function (){
                subcategory_table.ajax.reload();
            })
            $('#category_id,#category_id_select').select2({
                placeholder:"select....",
                allowClear: true,
            })
        });
    </script>

@endsection
