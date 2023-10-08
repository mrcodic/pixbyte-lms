@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Question Banks')

@section('vendor-style')

@endsection
@section('page-style')
  <!-- Page css files -->
@endsection

@section('content')

<section class="bs-validation">
    <div class="row">
        <!-- Bootstrap Validation -->
        <div class="col-md-12 col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Add Question Banks</h4>
                </div>
                <div class="card-body">
                    <form class="" id="quesBankForm" action="{{route('admin.question-bank.update', $questionBank)}}" method="POST">
                        @csrf
                        <x-input id="questions" class="uk-width-1-1" type="text" name="questions"  hidden/>

                        <div class="mb-1">
                            <label class="form-label" for="basic-addon-name"><Title></Title></label>

                            <input
                                type="text"
                                id="basic-addon-name"
                                class="form-control"
                                placeholder="Name"
                                name="name"
                                value="{{$questionBank->name}}"

                            />
                            @error('name')
                               <div class="text-danger">{{$message}}</div>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="mb-1 col-6">
                                <label class="form-label" for="category">Category</label>
                                <select name="cat_id" id="category" class="form-select" required>
                                    @foreach ( $categories as $cat )
                                        <option value="{{$cat->id}}" {{$questionBank->cat_id == $cat->id ? 'selected' :null}}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                                @error('category')
                                <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="mb-1 col-6">
                                <label class="form-label" for="sub_category">Sub Category</label>
                                <select name="sub_cat_id[]" id="sub_category" class="select2 form-select" multiple >
                                    @foreach ($subCategory as $cat)
                                        <option value="{{$cat->id}}" selected>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                                @error('sub_category')
                                <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-1" id="dynamic">
                            <label class="form-check-label" for="absence-rooms"> Choose Dynamic</label>
                            <div class="switcher">
                                <label class="uk-switch " for="default-2">
                                    <input type="checkbox"  id="default-2"  name="type" >
                                    <div class="uk-switch-slider"></div>
                                </label>
                            </div>
                            @error('type')
                            <span class="error-msg">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div id="subcat_input" style="display:none">


                        </div>

                        <div class="mb-1">
                            <h4 class="mt-2 pt-50">Questions</h4>
                            <!-- Permission table -->
                            <div class="table-responsive">
                                <table class="table table-flush-spacing" id="questions_table">
                                    <thead>
                                        <tr>
                                            <th class="col-1">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="hidden" id="selectAll" />
                                                    {{-- <label class="form-check-label" for="selectAll"> Select All </label> --}}
                                                </div>
                                            </th>
                                            <th>title</th>
                                        </tr>
                                    </thead>
                                    <tbody >

                                    </tbody>
                                </table>
                            </div>
                            <!-- Permission table -->
                        </div>




                        <button id="continue" class="btn btn-primary">Submit</button>
                        {{-- <button id="continue" type="submit" class="btn btn-primary">Submit</button> --}}
                    </form>
                </div>
            </div>
        </div>
        <!-- /Bootstrap Validation -->
    </div>
</section>


@endsection

@section('vendor-script')
  <!-- Vendor js files -->
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
    <script src="{{ asset('admin/js/helper.js') }}"></script>

    <script>
        var selectids = [];
        $('#count_question').text(selectids.length)

        $('#checkbox_all').on('click',function (e){
            if(this.checked){
                $("input[id=checkbox]").each(function (i, el) {
                    el.setAttribute("checked", "checked");
                    el.checked = true;
                    el.parentElement.className = "checked";
                    if(!selectids.includes(el.value.toString())){
                        selectids.push(el.value.toString());
                    }

                });
            }else{
                $("input[id=checkbox]").each(function (i, el) {
                    el.removeAttribute("checked");
                    el.parentElement.className = "";
                    selectids = selectids.filter(item => item !== el.value)
                    el.checked=false

                });
            }
            $('#count_question').text(selectids.length)

            console.log(selectids,'new')
        })

        $(document).on('change','#checkbox',function (e){

            e.preventDefault()

            if($(this).is(':checked')){
                if(!selectids.includes($(this).val())){
                    selectids.push($(this).val());
                }
            }else{
                selectids = selectids.filter(item => item != $(this).val())
            }
            $('#count_question').text(selectids.length)


        });


        $(document).on('change','#category',function (e){
            e.preventDefault();
            let grade= $(this).val();

            $.ajax({
                url: '{{route("admin.get_sub_category_grade", "")}}/'+grade,
                type: "get",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                success: function (res) {
                    if(res.status){
                        $('#sub_category').empty()
                        // $('#sub_category').append('<option readonly selected disabled>Select........</option>')
                        res.data.forEach((item)=>{
                            $('#sub_category').append(`
                                <option value="${item.id}">${item.name}</option>
                                `)
                        });

                    }
                },
                error:function (res) {
                    Swal.fire("Close!", "Something is wrong, Please try again.", "error");
                }
            });


        });

        $(document).on('change','#sub_category',function (e){
            selectids=[]
            e.preventDefault();
            let ids= $(this).val();
            var text = $('#sub_category option:selected').toArray().map(item => item.text);

            $('#subcat_input').empty()


            if(ids.length > 0){
                if($('#default-2').is(':checked')){
                    $('.questions').fadeOut()
                }else{
                    $('.questions').fadeIn()
                }
                ids.forEach((item,index)=>{
                    $('#subcat_input').append(`
                    <div class="mr-2" id="num_question" >
                        <label class="form-check-label" for="description"><span>*</span>Question number - ${text[index]} </label>
                        <input  type="number" name="question_num[${item}]"  min="0" class="form-check-input" placeholder="Enter Number Here" />
                    </div>`)
                })

                $('#questions_table').DataTable().clear().destroy()
                // $(".loading").show();
                var room_student_table
                // setTimeout(function () {
                    room_student_table =  $('#questions_table').DataTable({
                        processing: true,
                        serverSide: true,
                        lengthMenu: [5, 10, 20, 50],
                        pageLength: 10,
                        retrieve: true,
                        responsive: true,
                        dom:
                            '<"d-flex justify-content-between align-items-center header-actions mx-2 row mt-75"' +
                            '<"col-sm-12 col-lg-3 d-flex justify-content-center justify-content-lg-start" l>' +
                            '<"col-sm-12 col-lg-8 ps-xl-75 ps-0"<"dt-action-buttons d-flex align-items-center justify-content-center justify-content-lg-end flex-lg-nowrap flex-wrap"<"me-1 d-block"fb>>>' +
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
                        order: [[0, 'desc']],
                        ajax: {
                            url: "{{route('admin.get_question_in_bank_data')}}",
                            data: function (d) {
                                d.grade=ids;
                                // d.classroom_id=$('#classroom_id').val();
                                // d.grade_id=$('#grade_id').val();
                            },
                        },
                        columns: [
                            {
                                data:'id',orderable: false,className: ' col-3', render:function (data,type,full){
                                    let items
                                    // console.log(selectidss,'5548')
                                    if(selectids.includes(data.toString())){
                                        items='checked';
                                    }
                                    return `<input class="form-check-input" id="checkbox" ${items} value="${data}" type="checkbox">`;}
                            },
                            {
                                data: 'question' , className: ' uk-text-left',
                                render:function (data,type,full){
                                    return `<a id="moreInfo" data-id="${full['id']}">${data}</a>`;
                                }
                            },
                        ],
                    });
                // }, 500);
            }else{
                if($('#default-2').is(':checked')){
                    $('.questions').fadeOut()
                }else{
                    $('.questions').fadeIn()

                }
            }
            $('#count_question').text(selectids.length)

        });


        $(document).on('click','#moreInfo',function (e){
            $('.card-question span').empty();
            $('#more .uk-list').empty();
            $('#answer_desc_div').empty();
            UIkit.modal('#modal-more-info').show();
            $('.loader.spinner').show();
            let question= $(this).attr('data-id');

            $.ajax({
                url: `/get_answer/${question}`,
                type: "get",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                success: function (res) {
                    if(res.status){
                        $('.card-question span').empty()
                        $('#more .uk-list').empty()
                        $('.card-question span').append(res.data.title)
                        res.data.answers.forEach((item,index)=>{

                            let correct=''
                                if(item.correct){
                                correct='success'
                            }
                                let valueInput
                                if(item.status){
                                    valueInput=item.valueCk
                                }else{
                                    valueInput=item.valueInput
                                }
                            $('.uk-list').append(`
                                <div class="answer-wrapper">
                                    <label class="answar uk-width-1-1 uk-card uk-card-body uk-margin-small-bottom ${correct}" for="">
                                        <input class="notAnswered"  type="radio" value="${index}" >
                                        <span class="checkmark"></span>
                                        <span class="uk-margin-medium-left">
                                            ${valueInput}
                                        </span>
                                    </label>
                                </div>
                                `)
                        });
                        if(res.data.answer_desc){
                            $('#answer_desc').show()
                            $('#answer_desc_div').append(

                                `
                            <iframe src="${res.data.answer_desc}" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen style="top:0;left:0 ;width: 100%;height: 100%;"></iframe>

                                `
                            )
                        }
                        $('.loader.spinner').hide();
                    }
                },
                error:function (res) {
                    Swal.fire("Close!", "Something is wrong, Please try again.", "error");
                }
            });

        })


        $("#default-2").on('change',function (e){
            if($('#default-2').is(':checked')){

            $('#subcat_input').css('display','flex')
            $('.questions').fadeOut()

            }else {
                $('#subcat_input').fadeOut()
                $('.questions').fadeIn()
            }
        })

        $('#continue').on('click',function (e){
            e.preventDefault()

            $('#questions').val(selectids.toString())
            // console.log($('#questions').val());
            $("#quesBankForm").submit();
        })

        getQuestions();

        function getQuestions() {
            selectids = @json($questions);
            let ids   = $('#sub_category option:selected').toArray().map(item => item.value);
            var text  = $('#sub_category option:selected').toArray().map(item => item.text);

            $('#subcat_input').empty()


            if(ids.length > 0){
                if($('#default-2').is(':checked')){
                    $('.questions').fadeOut()
                }else{
                    $('.questions').fadeIn()
                }
                ids.forEach((item,index)=>{
                    $('#subcat_input').append(`
                    <div class="mr-2" id="num_question" >
                        <label class="form-check-label" for="description"><span>*</span>Question number - ${text[index]} </label>
                        <input  type="number" name="question_num[${item}]"  min="0" class="form-check-input" placeholder="Enter Number Here" />
                    </div>`)
                })

                $('#questions_table').DataTable().clear().destroy()
                // $(".loading").show();
                var room_student_table
                // setTimeout(function () {
                    room_student_table =  $('#questions_table').DataTable({
                        processing: true,
                        serverSide: true,
                        lengthMenu: [5, 10, 20, 50],
                        pageLength: 10,
                        retrieve: true,
                        responsive: true,
                        dom:
                            '<"d-flex justify-content-between align-items-center header-actions mx-2 row mt-75"' +
                            '<"col-sm-12 col-lg-3 d-flex justify-content-center justify-content-lg-start" l>' +
                            '<"col-sm-12 col-lg-8 ps-xl-75 ps-0"<"dt-action-buttons d-flex align-items-center justify-content-center justify-content-lg-end flex-lg-nowrap flex-wrap"<"me-1 d-block"fb>>>' +
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
                        order: [[0, 'desc']],
                        ajax: {
                            url: "{{route('admin.get_question_in_bank_data')}}",
                            data: function (d) {
                                d.grade=ids;
                            },
                        },
                        columns: [
                            {
                                data:'id',orderable: false,className: ' col-3', render:function (data,type,full){
                                    let items
                                    if(selectids.includes(data)){
                                        items='checked';
                                    }
                                    return `<input class="form-check-input" id="checkbox" ${items} value="${data}" type="checkbox">`;}
                            },
                            {
                                data: 'question' , className: ' uk-text-left',
                                render:function (data,type,full){
                                    return `<a id="moreInfo" data-id="${full['id']}">${data}</a>`;
                                }
                            },
                        ],
                    });
                // }, 500);
            }else{
                if($('#default-2').is(':checked')){
                    $('.questions').fadeOut()
                }else{
                    $('.questions').fadeIn()

                }
            }
            $('#count_question').text(selectids.length)
        }


    </script>
@endsection
