@extends('layouts.app')
@section('title', 'Codes')

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
                    <h3 class="uk-margin-remove-bottom title-add">My Codes</h3>
                </div>
                <div class="uk-width-auto">
                    <a href="{{ route('coupon.create')}}" class="uk-button uk-button-primary border-radius uk-padding-remove-t-b"><span class="add-icon uk-icon uk-margin-small-right" uk-icon="icon:plus; ratio: .7"></span>Add New Code</a>
                    <button uk-toggle="target: #modal-generate" class="new-add uk-margin-small-left" id="modal_generate" type="button" ><i class="fa-solid fa-layer-group uk-margin-small-right"></i>Generate Codes</button>
                </div>
                <div class="line divider"></div>
            </div>
        </div>

    </div>

    <div class="uk-container uk-container-expand rm-expand uk-margin-medium-top uk-margin-left">
        <div class="uk-overflow-auto uk-margin-medium-top x-scrollbar mt-s-20">
            <table class="uk-table uk-table-hover uk-table-middle uk-table-divider rooms" id="coupon_table" style="width:100%;">
                <div class="uk-margin-small-right inline-block left uk-width-1-6 hidden-small">
                    <select class="uk-select uk-width-1-1" id="room_id_filter" name="room_id_filter">
                        <option selected disabled>Select Room</option>
                        <option value="">all</option>
                        @foreach ( $rooms as $room )
                            <option value=" {{$room->id}} ">{{ $room->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="uk-margin-small-right inline-block left uk-width-1-6 hidden-small">
                    <select class="uk-select uk-width-1-1" id="grade_id_filter" name="grade_id_filter">
                        <option selected disabled>Select Grade</option>
                        <option value="">all</option>
                        @foreach ( $grades as $grade )
                            <option value=" {{$grade->id}} ">{{ $grade->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="uk-margin-small-right inline-block left uk-width-1-6 hidden-small">
                    <select class="uk-select uk-width-1-1" id="class_room_id_filter" name="class_room_id_filter">
                        <option selected disabled>Select Class</option>
                        <option value="">all</option>
                        @foreach ( $classrooms as $classroom )
                            <option value=" {{$classroom->id}} ">{{ $classroom->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="uk-margin-small-right inline-block left uk-width-1-6@m uk-width-1-1@s">
                    <input type="text" class="uk-input dark-font"  placeholder="search" id="filter_datatable">
                </div>
                <input type="hidden" name="select_all" id="select_all" value="">
                <input type="hidden" id="table_page" value="">
                <thead>
                    <tr>
                        <th class="uk-table-shrink"><input id="checkbox_all" class="uk-checkbox" type="checkbox"></th>

                        <th class="uk-table-expand">Code</th>
                        <th class="">Type</th>
                        <th class="uk-table-small">Value</th>
                        <th class="">Price</th>
                        <th class="uk-table-small">Created At</th>
                        <th class="">Restriction</th>
                        <th class="">Student ID</th>
                        <th class="">Usage Name</th>
                        <th class="uk-width-small">Usage Date</th>
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
                <a class="delete uk-margin-small-right" id="exportCouponExcel" onclick="exportCouponExcel(event)"  >Export</a>
                <span class="dark-font">selected entries</span>
            </div>
        </div>
    </div>
    @include('coupon.GenerateCouponModal')
    @include('coupon.MoreModal')
</div>
@endsection
@section('script')
    <script>
        $( document ).ready(function() {
         $(document).on('click','#modal_generate',function (e){
             $('#modal-generate').on('hidden.bs.modal', function () {
                 $(this).find('form').trigger('reset');
             })
            })


            let coupon_table=''
            coupon_table  =  $('#coupon_table').DataTable({
                processing: true,
                serverSide: true,
                lengthMenu: [5, 10, 20, 50,100],
                pageLength: 50,
                ordering:false,
                searching:false,
                responsive: true,
                dom: 'Blfrtip',
                    ajax: {
                    url: "{{route('get-coupon-data')}}",
                    data: function (d) {
                        d.room_id_filter=$('#room_id_filter').val()
                        d.class_room_id_filter=$('#class_room_id_filter').val()
                        d.grade_id_filter=$('#grade_id_filter').val()
                        d.name=$('#filter_datatable').val();
                    },
                    beforeSend: function () {
                        $('#table_page').val($('#coupon_table').DataTable().page())
                    }
                },
                "language": {
                    "processing":
                        `<div class="db-loader">
                        <span class='fa-stack fa-lg'>
                            <i class='fa fa-spinner fa-spin fa-stack-2x fa-fw'></i>
                           </span>Loading ... </div>`,
                },
                columns: [
                    {data:'id',sortable:false,render:function (data,type,full){
                        return `
                        <input class="uk-checkbox" id="checkbox" value="${data}" type="checkbox">
                        `;
                        }
                    },
                    {data: 'code', name: 'code'},
                    {data: 'type', name: 'type'},
                    {data: 'value', name:"value",render:function (data,type,full){
                            return `

                    <button data-name="${data}" class="new-add " id="modal_more" type="button" ><i class="fa-solid fa-eye"></i></button>

                        `
                        }
                        },
                    {data: 'price', name: 'price'},
                    {data: 'updated_at', name: 'updated_at'},
                    {data: 'expired', name: 'expired'},
                    {data: 'user', name: 'user'},
                    {data: 'usage', name: 'usage',render:function (data,type,full){
                            return `
                            <span title="${full['usage_type']}"> ${data}</span>
                        `
                    }
                    },
                    {data: 'checkedIn', name: 'checkedIn'},
                    {data: 'id',className:'uk-text-nowrap',render:function (data,type,full){
                    return `
                        <a class="edit" href="/coupon/${data}/edit?pageTbl=${$('#table_page').val()}">Edit</a>
                            <a class="delete" id="delete_row" data-id="${data}">Delete</a>`;
                    }
                    }
                ],
                columnDefs: [
                        { "width": "5%", "targets": 2 },
                ],
            });


            if ('{{session()->get("pageTbl")}}') {
                setTimeout(() => {
                    $('#coupon_table').DataTable().page(parseInt('{{session()->get("pageTbl")}}')).draw("page");
                }, 200);
            }

            $(document).on('click','#delete_row',function (e){
                e.preventDefault();
                let rowID=$(this).attr('data-id');
                let url = `/coupon/${rowID}`
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
                    let url = '/delete-all-coupon'
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
                    confirmButtonColor: '#ee8e8e',
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
                                coupon_table.ajax.reload();
                                page ? $('#coupon_table').DataTable().page(parseInt(page)).draw("page") :null
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

            $('#room_id_filter , #class_room_id_filter ,#grade_id_filter').on('change',function (){
                coupon_table.ajax.reload();
            })
            $('#filter_datatable').on('keyup',function (){
                coupon_table.ajax.reload();
            });
            $('#class_room_id_filter,#grade_id_filter,#room_id_filter,.uk-select').select2({})


            $('[name="type"]').on('click',function (){
                let val= $(this).val()
                $('#type_desc').val(val);


                switch (val){
                    case "2":
                        $("#multiRoom").show();
                        $("#classroom").hide();
                        $("#quizzes").hide();
                        $("#grade").hide();
                        $("#room_id").val('').trigger('change');
                        $("#classroom_id").val('').trigger('change');
                        $("#grade_id").val('').trigger('change');
                        $("#quiz_id").val('').trigger('change');
                        $("#subscription").hide();
                        $("#date_to").val('')
                        $("#date_from").val('')
                        break;
                    case "3":
                        $("#classroom").show();
                        $("#multiRoom").hide();
                        $("#quizzes").hide();
                        $("#grade").hide();
                        $("#subscription").hide();
                        $("#date_to").val('')
                        $("#date_from").val('')
                        $("#multi_room_id").val('').trigger('change');
                        $("#room_id").val('').trigger('change');
                        $("#grade_id").val('').trigger('change');
                        $("#quiz_id").val('').trigger('change');
                        break;
                    case "4":
                        $("#grade").show();
                        $("#multiRoom").hide();
                        $("#classroom").hide();
                        $("#quizzes").hide();
                        $("#multi_room_id").val('').trigger('change');
                        $("#classroom_id").val('').trigger('change');
                        $("#room_id").val('').trigger('change');
                        $("#quiz_id").val('').trigger('change');
                        break;
                        $("#subscription").hide();
                        $("#date_to").val('')
                        $("#date_from").val('')
                    case "5":

                        $("#quizzes").show();
                        $("#grade").hide();
                        $("#multiRoom").hide();
                        $("#classroom").hide();
                        $("#subscription").hide();
                        $("#date_to").val('')
                        $("#date_from").val('')
                        $("#quiz").hide();
                        $("#multi_room_id").val('').trigger('change');
                        $("#classroom_id").val('').trigger('change');
                        $("#room_id").val('').trigger('change');
                        $("#grade_id").val('').trigger('change');
                        break;
                        case "6":
                        $("#subscription").css('display','inline-grid');
                            $("#quizzes").hide();
                            $("#grade").hide();
                            $("#multiRoom").hide();
                            $("#classroom").hide();

                            $("#multi_room_id").val('').trigger('change');
                            // $("#classroom_id").val('').trigger('change');
                            $("#room_id").val('').trigger('change');
                            $("#grade_id").val('').trigger('change');
                            $("#quiz_id").val('').trigger('change');
                        break;


                }
            })
            $('#save_generat_coupon').on('click',function () {
                var type = $('#type_desc').val();
                console.log(type)
                var room_id = $('[name="multi_room_id[]"]').val();
                var instructor_id = $('[name="instructor_id"]').val();
                var prefix_code = $('[name="prefix_code"]').val();
                var price = $('[name="price"]').val();
                var grade_id = $('[name="grade_id[]"]').val();
                var classroom_id = $('[name="classroom_id[]"]').val();
                var quiz_id = $('[name="quiz_id[]"]').val();
                var num_coupon = $('[name="num_coupon"]').val();
                var num_used = $('[name="num_used"]').val();
                var date_subscription_from = $('[name="date_subscription_from"]').val();
                var date_subscription_to = $('[name="date_subscription_to"]').val();
                var exports=false
                Swal.fire({
                    title: " Generate code ",
                    text: "",
                    icon:"warning",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#ee8e8e',
                    confirmButtonText: 'Generate and export',
                    cancelButtonText: "Generate only",
                    closeOnConfirm: false,
                    closeOnCancel: false
                }).then(function (result){
                    if (result.isConfirmed){
                        exports=true;
                    } else {
                       exports=false;
                     }
                    $.ajax({
                        url: "/generate_coupons",
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': "{{@csrf_token()}}"
                        },
                        data: {exports:exports,quiz_id:quiz_id,room_id: room_id,instructor_id:instructor_id,type:type,prefix_code:prefix_code,price:price,grade_id:grade_id,
                            classroom_id:classroom_id,num_coupon:num_coupon
                        ,date_subscription_to:date_subscription_to,date_subscription_from:date_subscription_from,num_used:num_used
                        },
                        success: function (res) {
                            if (res.status) {
                                if(exports){
                                    let selected_ids=res.data;
                                    window.open(`/coupon-export?selected_ids=${selected_ids}`);
                                    UIkit.modal('#modal-generate').hide();
                                    let page = $('#table_page').val()
                                    coupon_table.ajax.reload();
                                    page ? $('#coupon_table').DataTable().page(parseInt(page)).draw("page") :null
                                }else{
                                    UIkit.modal('#modal-generate').hide();
                                    let page = $('#table_page').val()
                                    coupon_table.ajax.reload();
                                    page ? $('#coupon_table').DataTable().page(parseInt(page)).draw("page") :null
                                }

                            }
                            toastr.success("Done! Code Generate successful.", 'Success!');


                        },
                        error: function (res) {
                            var response = JSON.parse(res.responseText);
                            $('.error_msg').show();
                            $.each( response.errors, function( key, value) {
                                $("." + key + "_error strong").text(value[0]);
                            });

                            toastr.error("Opps! Something is wrong, Please try again.", 'Error!', {timeOut: 5000});
                        }
                    });
                })


            });


         $(document).on('click','#modal_more',function (){
             let data=$(this).attr('data-name')

             $('#data_more').empty();
             $('#data_more').text(`${data}`)
             UIkit.modal('#modal-more').show();

         })
        });
        function exportCouponExcel(event){
            event.preventDefault()
            let selected_ids = [];

            $.each($("input:checkbox:checked"), function(){
                if($(this).val() !='on'){
                    selected_ids.push($(this).val());
                }
            });


            window.open(`/coupon-export?selected_ids=${selected_ids}`);
        }
    </script>

@endsection
