@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Classrank Student')

@section('vendor-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset('admin/vendors/css/forms/select/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/vendors/css/tables/datatable/responsive.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/vendors/css/tables/datatable/buttons.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/vendors/css/tables/datatable/rowGroup.bootstrap5.min.css') }}">
    <link rel="stylesheet" type="text/css" href="https://printjs-4de6.kxcdn.com/print.min.css">

@endsection

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset('admin/css/base/plugins/forms/form-validation.css') }}">
@endsection

@section('content')
    <!-- users list start -->
    <section class="app-user-list">
        <div class="row">
            <div class="col-lg-3 col-sm-6">
                <div class="card cursor-pointer grade_id_filter_remove">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <h3 class="fw-bolder mb-75">{{$totalStudent}}</h3>
                            <span>Total Students</span>
                        </div>
                        <div class="avatar bg-light-primary p-50">
                            <span class="avatar-content">
                            <i data-feather="user" class="font-medium-4"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- list and filter start -->
        <div class="card">
            <div class="card-body border-bottom">
                <h4 class="card-title">Search & Filter</h4>
                <div class="row">
                     <div class="col-md-4 user_role">
                        <label class="form-label" for="UserRole">Instructor</label>
                        <select id="user_id_filter" class="form-select text-capitalize mb-md-0 mb-2">
                            <option value=""> Select Instructor </option>
                            @foreach ( $instructors as $instructor )
                                <option value=" {{$instructor->id}} ">{{ $instructor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                     <div class="col-md-4 user_plan">

                        <label class="form-label" for="UserRole">ClassRoom</label>
                        <select id="classroom_id" class="form-select text-capitalize mb-md-0 mb-2">
                            <option value=""> Select ClassRoom </option>
                            <option value="">All</option>
                            @foreach ( $classrooms as $class )
                                <option value=" {{$class->id}} ">{{ $class->title }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>
            </div>
            <div class="card-datatable table-responsive pt-0">
                <table class="user-list-table table" id="user_table">
                    <input type="hidden" id="table_page" value="">

                    <input type="hidden" name="select_all" id="select_all" value="">

                    <thead class="table-light">
                    <tr>
                        <th><input id="checkbox_all" class="checkbox" type="checkbox"></th>
                        <th >User ID</th>
                        <th >Name</th>
                        <th >Point</th>
{{--                        <th >Action</th>--}}
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            @include('admin.students.GradeBookModal')

        </div>
        <!-- list and filter end -->





    </section>
    <!-- users list ends -->
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
    <script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>

@endsection

@section('page-script')
    {{-- Page js files --}}
    <script src="{{ asset('admin/js/helper.js') }}"></script>
    <script>
        $( document ).ready(function() {


            var user_table
            user_table =  $('#user_table').DataTable({
                processing: true,
                ordering:true,
                serverSide: true,
                lengthMenu: [5, 10, 20, 50, 100],
                pageLength: 50,
                responsive: true,
                // searching:false,


                dom:
                    '<"d-flex justify-content-between align-items-center header-actions mx-2 row mt-75"' +
                    '<"col-sm-12 col-lg-3 d-flex justify-content-center justify-content-lg-start" l>' +
                    '<"col-sm-12 col-lg-4 d-flex justify-content-center justify-content-lg-start" <"ClassRoom me-1"><"UserRole me-1">>' +
                    '<"col-sm-12 col-lg-5 ps-xl-75 ps-0"<"dt-action-buttons d-flex align-items-center justify-content-center justify-content-lg-end flex-lg-nowrap flex-wrap"<"me-1 d-block"fb>B>>' +
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
                order: [[3, 'desc']],
                ajax: {
                    url: "{{route('get-student-classrank-data')}}",
                    data: function (d) {
                        d.user_id = $('#user_id_filter').val() ;
                        d.classroom_id = $('#classroom_id').val();
                    },
                    beforeSend: function () {
                        $('#table_page').val($('#user_table').DataTable().page())
                    },
                },
                columns: [

                    {
                        data:'id',orderable: false, render:function (data,type,full){
                            return `<input class="checkbox checkboxStudent" data-name-id="${full['name_id']}" data-name="${full['name']}" data-qr-code="${full['qrcode']}" value="${data}" type="checkbox">`;}
                    },
                    {data: 'user_id', name: 'user_id', className: ' text-left'
                        , render:function (data,type,full){
                        return `<a href="javascript:;" id="grade_book" data-id="${full['id']}" title="Grade Book" id="grade_book" class="">

                                ${data}</a>`
                      }
                    },
                    {data: 'name', name: 'name', className: ' text-left'  },
                    {data: 'points', name: 'points', className: ' text-left'  },

                    // {data: 'id',className:'text-nowrap',render:function (data,type,full){
                    //         return (
                    //             '<div class="btn-group">' +
                    //             '<a class="btn btn-sm dropdown-toggle hide-arrow" data-bs-toggle="dropdown">' +
                    //             feather.icons['more-vertical'].toSvg({ class: 'font-small-4' }) +
                    //             '</a>' +
                    //             '<div class="dropdown-menu dropdown-menu-end">' +
                    //             '<a href="/admin/students/'+data+'/edit?page='+$('#table_page').val()+'"  id="edit_row" class="dropdown-item">' +
                    //             feather.icons['file-text'].toSvg({ class: 'font-small-4 me-50' }) +
                    //             'Edit</a>'+'</div>' +
                    //             '</div>'
                    //         );
                    //     }
                    //}
                ],
                // Buttons with Dropdown
                buttons: [
                    {
                        text: 'Add New User',
                        className: 'add-new btn btn-primary px-1 fs-6 hidden',
                    }
                ],

            });



            if ('{{session()->get("page")}}') {
                setTimeout(() => {
                    $('#user_table').DataTable().page(parseInt('{{session()->get("page")}}')).draw("page");
                }, 200);
            }
            $(document).on('click','#grade_book',function (e){
                var page = 1;
                e.preventDefault()
                let id =$(this).attr('data-id')
                load_more(page,id);
                //track user scroll as page number, right now page number is 1
                $('.grade-area').on('scroll',function() { //detect page scroll
                    if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) { //if user scrolled from top to bottom of the page

                        page++; //page number increment
                        load_more(page,id); //load content
                    }
                });

            })
            function  load_more(page,id){
                $.ajax({
                    url: `/admin/getPoint/${id}?page=${page}`,
                    type: "get",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    beforeSend: function() {
                        $('.loader').show();
                    },
                    success: function (res) {
                        if(res.status){
                            if(res.data.length>0){
                                // $('.list-group').empty();
                                 if(page==1){
                                     $('.list-group').empty();
                                 }
                                let html='';
                                res.data.map((item)=>{
                                    html+=`<li class="list-group-item d-flex align-items-center">
                                 <span> ${item.name} ... <small style="color: darkred;font-weight: bold">${item.point??''}</small></span>
                                 <span class="badge bg-primary rounded-pill ms-auto">${item.value}</span>
                             </li>
                           `;
                                })
                                $('.list-group').append(html);
                                $('.total').text(res.sum);
                                $('#modal-grade-book').modal('show')
                            }else {
                                Swal.fire("warning!", "No Grade book yet.", "warning");
                            }



                        }
                    },
                    error:function (res) {
                        Swal.fire("Close!", "Something is wrong, Please try again.", "error");
                    }
                });
            }




            $('#checkbox_all').on('click',function (e){
                $('input:checkbox').not(this).prop('checked', this.checked);
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
                                user_table.ajax.reload();
                                page ? $('#user_table').DataTable().page(parseInt(page)).draw("page") :null
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


            // $('.grade_id_filter_remove').on('click',function (){
            //     $('.grade_id_filter_top_click').css({backgroundColor: ''});
            //     $('#grade_id_filter').val(null)
            //     $('#grade_id_filter_top').val(null)
            //     user_table.ajax.reload();
            // });


            $('#filter_rooms').on('keyup',function (){
                room_student_table.ajax.reload();
            });

            $('#filter_exam').on('keyup',function (){
                quiz_student_table.ajax.reload();
            });


            $('#user_id_filter').on('change',function (){
                user_table.ajax.reload();
            });

            $('#classroom_id').on('change',function (){
                user_table.ajax.reload();
            });


        });




    </script>
@endsection
