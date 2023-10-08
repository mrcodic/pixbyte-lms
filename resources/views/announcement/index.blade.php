@extends('layouts.app')
@section('title', 'Announcement')

@section('css')
    <style>
        .button_add{
            display: flex;
            justify-content: space-between;
            align-items: baseline;
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
                    <h3 class="uk-margin-remove-bottom title-add">Announcement</h3>
                </div>
                <div class="uk-width-auto">
                    <a href="{{ route('announcement.create')}}" class="uk-button uk-button-primary border-radius uk-padding-remove-t-b "><span class="add-icon uk-icon uk-margin-small-right" uk-icon="icon:plus; ratio: .7"></span>Add New Announcement</a>
                </div>
                <div class="line divider"></div>
            </div>
        </div>

    </div>
@include('announcement.modal_show_desc')
    <div class="uk-container uk-container-expand rm-expand uk-margin-medium-top uk-margin-left">
        <div class="uk-overflow-auto uk-margin-medium-top x-scrollbar">
            <table class="uk-table uk-table-hover uk-table-middle uk-table-divider rooms" id="category_table">
                <input type="hidden" name="select_all" id="select_all" value="">
                <input type="hidden" id="table_page" value="">
                <thead>
                    <tr>
                        <th class="uk-table-shrink"><input id="checkbox_all" class="uk-checkbox" type="checkbox"></th>
                        <th class="uk-table-expand">title</th>
                        <th class="uk-table-expand">classroom</th>
                        <th class="uk-width-small">created at</th>
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
            let category_table=''
            category_table  =  $('#category_table').DataTable({
                processing: true,
                serverSide: true,
                lengthMenu: [5, 10, 20, 50],
                pageLength: 0,
                responsive: true,
                dom: 'Blfrtip',
                    ajax: {
                    url: "{{route('get-announcement-data')}}",
                    data: function (d) {

                    },
                    beforeSend: function () {
                        $('#table_page').val($('#category_table').DataTable().page())
                    }
                },
                columns: [
                    {data:'id',sortable:false,render:function (data,type,full){
                        return `
                        <input class="uk-checkbox" id="checkbox" value="${data}" type="checkbox">
                        `;
                        }
                    },
                    {data: 'title', name: 'title'},
                    {data: 'classroom', name: 'classroom'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'id',className:'uk-text-nowrap',render:function (data,type,full){
                    return `
                            <a class="edit" href="/announcement/${data}/edit">Edit</a>
                            <a class="delete" id="delete_row" data-id="${data}">Delete</a>`;
                    }
                    }
                ]
            });
            $(document).on('click','#delete_row',function (e){
                e.preventDefault();
                let rowID=$(this).attr('data-id');
                let url = `/announcement/${rowID}`
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
                    let url = '/delete-all-announcement'
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
                                category_table.ajax.reload();
                                page ? $('#category_table').DataTable().page(parseInt(page)).draw("page") :null

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

            $(document).on('click','#show_modal',function(e){
                e.preventDefault();
                var desc = $(this).attr('data-desc');
                $('#desc_text').html(desc);
                UIkit.modal('#modal-show-desc').show();

            })
            $('#grade_id,#grade_ids,#subcategory_ids,#subcategory_select').select2({
                placeholder:"select....",
                allowClear: true,

            })
        });
    </script>

@endsection
