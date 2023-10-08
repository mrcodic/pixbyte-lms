@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Quiz')

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
                    <h4 class="card-title">Add Quiz</h4>
                </div>
                <div class="card-body">
                    <form id="quizForm" action="{{route('admin.quiz.store')}}" method="POST">
                        @csrf
                        <input type="hidden" name="checkbox_all_question" id="checkbox_all_question">
                        <x-input id="questions" class="uk-width-1-1" type="text" name="questions"  hidden/>

                        <div class="mb-1 row">
                            <div class=" col-9">
                                <label class="form-label" for="basic-addon-name">Title</label>

                                <input
                                    type="text"
                                    id="basic-addon-name"
                                    class="form-control"
                                    placeholder="title"
                                    name="title"
                                    value=""
                                    required
                                />
                                @error('title')
                                   <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>

                            <div class="col-3">
                                <label class="form-label" for="user_id"> Instructor</label>
                                <select name="user_id" id="user_id" class="form-select" required>
                                    <option readonly disabled>select...</option>
                                    @foreach ( $instructors as $instructor )
                                        <option value="{{$instructor->id}}">{{ $instructor->name }}</option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>

                        </div>

                        <div class="row">
                            <div class="mb-1 col-3">
                                <label class="form-label" for="type">Type</label>
                                <select name="type" id="type" class="form-select" required>
                                    <option readonly selected disabled>Select........</option>
                                    <option value="1" >quiz in room</option>
                                    <option value="2" >Exam in classroom</option>
                                    <option value="3" >Assignment in room</option>
                                </select>
                                @error('type')
                                    <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="mb-1 col-3">
                                <label class="form-label" for="grade">Grade</label>
                                <select name="grade" id="grade" class="form-select" required>
                                    <option readonly selected disabled>Select........</option>
                                    @foreach ( $grades as $grade )
                                        <option value="{{$grade->id}}">{{ $grade->name }}</option>
                                    @endforeach
                                </select>
                                @error('grade')
                                    <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="mb-1 col-3 classroom">
                                <label class="form-label" for="classroom_id">Classroom</label>
                                <select name="classroom_id" id="classroom_id" class="form-select" >
                                    {{-- @foreach ( $categories as $cat )
                                        <option value="{{$cat->id}}">{{ $cat->name }}</option>
                                    @endforeach --}}
                                </select>
                                @error('classroom_id')
                                    <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="mb-1 col-3 room">
                                <label class="form-label" for="room_id">Room</label>
                                <select name="room_id" id="room_id" class="select2 form-select">

                                </select>
                                @error('room_id')
                                    <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="mb-1 col-3">
                                <label class="form-label" for="question_bank">Question Bank</label>
                                <select name="question_bank_id[]" multiple id="question_bank" class="select2 form-select">

                                </select>
                                @error('question_bank_id')
                                    <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>

                        </div>


                        <div id="subcat_input" style="display:none"></div>

                        <div class="mb-1 questions" style="display: none">
                            <h4 class="mt-2 pt-50">Questions
                                <a id="quesion_colapse">
                                    <i data-feather='chevron-down'></i>
                                </a>
                                <span class="badge bg-danger rounded-pill" id="count_question"></span>
                            </h4>
                            <!-- questions table -->
                            <div class="table-responsive questions" id="ques_col">
                                <label class="mb-1 " for="checkbox_all_que">
                                    <input type="checkbox" id="checkbox_all_que" name="checkbox_all_que" class="form-check-input">
                                    <span >Check all Question</span>
                                </label>
                                <table class="table table-flush-spacing" id="questions_table">
                                    <input type="hidden" name="totalRecord" id="totalRecord" value="">
                                    <thead>
                                        <tr>
                                            <th class="col-1">
                                                <div class="form-check  text-center">
                                                    <input class="form-check-input" type="hidden" id="selectAll" />
                                                    <label class="form-check-label" for="selectAll"></label>
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

                        <hr>
                        <div class="row mb-1">
                            <div class="mb-1 col-3 price">
                                <label class="form-label" for="price">Price</label>
                                <input name="price" id="price" type="number" min="1" class="form-control">
                                @error('price')
                                    <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="mb-1 col-3 timer_div">
                                <label class="form-label" for="timer">Timer</label>
                                <input name="timer" id="timer" type="number" class="form-control">
                                @error('timer')
                                    <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="mb-1 col-3 Lock">
                                <label class="form-label" for="lock_after">Lock after</label>
                                <input name="lock_after" id="lock_after" type="number" class="form-control">
                                @error('lock_after')
                                    <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="mb-1 col-3 score_div">
                                <label class="form-label" for="score">Score to pass a quiz (%)</label>
                                <input name="score" id="score" type="number" class="form-control">
                                @error('score')
                                    <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-1">

                            <div class="mb-1 col-3">
                                <div class="form-check form-check-secondary form-switch inline-flex mb-1 col-6">
                                    <input type="checkbox" class="form-check-input" id="randomize_answer" name="randomize_answer">
                                    <label class="form-label" for="randomize_answer">Randomize Answer</label>
                                </div>
                                @error('randomize_answer')
                                    <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="mb-1 col-3">
                                <div class="form-check form-check-secondary form-switch inline-flex mb-1  col-6">
                                    <input type="checkbox" class="form-check-input" id="randomize_question" name="randomize_question">
                                    <label class="form-label" for="randomize_question">Randomize Question</label>
                                </div>
                                @error('randomize_question')
                                    <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="mb-1 col-3">
                                <div class="form-check form-check-secondary form-switch inline-flex mb-1  col-6">
                                    <input type="checkbox" class="form-check-input" id="show_answer" name="show_answer">
                                    <label class="form-label" for="show_answer">Show Answer</label>
                                </div>
                                @error('show_answer')
                                    <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="mb-1 col-3">
                                <div class="form-check form-check-secondary form-switch inline-flex mb-1  col-6">
                                    <input type="checkbox" class="form-check-input" id="retake" name="retake">
                                    <label class="form-label" for="retake">Retake</label>
                                </div>
                                @error('retake')
                                    <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>
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
        let selectids=[]
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
        })

        $(document).on('change','#checkbox',function (e){
            console.log(selectids,'before')

            e.preventDefault()

            if($(this).is(':checked')){
                if(!selectids.includes($(this).val())){
                    selectids.push($(this).val());
                }
            }else{
                selectids = selectids.filter(item => item !== $(this).val())

            }
            $('#count_question').text(selectids.length)


        });


        $(document).on('change','#type',function (e){
            let type=$(this).val();
            if(type=="2"){
                $(".room").fadeOut()
                $(".classroom").fadeIn()
                $(".Lock").fadeIn()
                $(".price").fadeIn()
                $(".timer_div").fadeIn()
                $(".score_div").fadeIn()
            }else if(type=="3"){
                $(".room").fadeIn()
                $(".classroom").fadeOut()
                $(".timer_div").fadeOut()
                $(".score_div").fadeOut()

            }

            else{
                $(".room").fadeIn()
                $(".classroom").fadeOut()
                $(".price").fadeOut()
                $(".timer_div").fadeIn()
                $(".score_div").fadeIn()
                $('#retake').prop( "checked", true )
                $('#show_answer').prop( "checked", true )
            }
        })


        $(document).on('click','#quesion_colapse',function (e){
            $('#ques_col').slideToggle();
        })

        $(document).on('change','#grade',function (e){
            e.preventDefault();
            let grade= $(this).val();
            let type= $('#type').val();
            let id ,url;
            if(type=="2"){
                url='{{route("admin.get_classroom.quiz", "")}}';
                id='classroom_id'
                $("#classroom_id").fadeIn()
                $("#room_id").fadeOut()
            }else{
                url='{{route("admin.get_room.quiz", "")}}';
                id='room_id';
                $("#classroom_id").fadeOut()
                $("#room_id").fadeIn()
            }
            getDataRoomOrClass(url,grade,id)
            geQuestionBank(grade)
        });

        $(document).on('change','#checkbox',function (e){
            e.preventDefault()
            if($(this).is(':checked')){

                if(!selectids.includes($(this).val())){
                    selectids.push($(this).val());
                }
            }else{

                selectids = selectids.filter(item => item !== $(this).val())

            }
            $('#count_question').text(selectids.length)
        });

        function getDataRoomOrClass(url,grade,id){
            $.ajax({
                url: `${url}/${grade}`,
                type: "get",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                success: function (res) {
                    if(res.status){
                        $(`#${id}`).empty()
                        $(`#${id}`).append('<option readonly selected disabled>Select........</option>')
                        res.data.forEach((item)=>{
                            $(`#${id}`).append(`
                                <option value="${item.id}">${item.title}</option>
                                `)
                        });

                    }
                },
                error:function (res) {
                    Swal.fire("Close!", "Something is wrong, Please try again.", "error");
                }
            });
        }

        function geQuestionBank(){
            let id = 'question_bank';
            $.ajax({
                url: `{{route("admin.get_questionBank")}}`,
                type: "get",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                success: function (res) {
                    if(res.status){
                        $(`#${id}`).empty()
                        res.data.forEach((item)=>{
                            $(`#${id}`).append(`
                                <option value="${item.id}">${item.name}</option>
                                `)
                        });

                    }
                },
                error:function (res) {
                    Swal.fire("Close!", "Something is wrong, Please try again.", "error");
                }
            });
        }


        $(document).on('change','#question_bank',function (e){
            selectids=[]
            e.preventDefault();
            $('.questions').fadeIn()
            let ids= $(this).val();
            if($(this).val().length>0){

                $('#questions_table').DataTable().clear().destroy()
                $(".loading").show();
                var room_student_table
                setTimeout(function () {
                    room_student_table =  $('#questions_table').DataTable({
                        processing: true,
                        serverSide: true,
                        lengthMenu: [5, 10, 20, 50],
                        pageLength: 10,
                        responsive: true,

                        initComplete: function () {
                            checkBox()
                        },
                        "drawCallback": function( settings, start, end, max, total, pre ) {
                            $('#totalRecord').val(this.fnSettings().fnRecordsTotal())
                        },
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
                            url: "{{route('admin.get_questionBank_quiz')}}",
                            data: function (d) {
                                d.ids = ids;
                                setTimeout(function () {
                                    checkBox()
                                },1500)
                            },
                        },
                        columns: [
                            {
                                data:'id',orderable: false, render:function (data,type,full){
                                    let items
                                    if(selectids.includes(data.toString())){
                                        items='checked';
                                    }
                                    return `<input class="form-check-input ms-1" ${items}  id="checkbox" value="${data}" type="checkbox">`;
                                }
                            },
                            {
                                data: 'question' , className: 'text-left',
                                render:function (data,type,full){
                                    return `<a id="moreInfo" data-id="${full['id']}">${data}</a>`;
                                }
                            },
                        ]
                    });
                }, 500);
                setTimeout(function () {
                    checkBox()
                },1500)

            }else{
                $('.questions').fadeOut()
            }


        });

        $(document).on('click','#moreInfo',function (e){
            UIkit.modal('#modal-more-info').show();
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
                            $('#answer_desc_div').append(res.data.answer_desc)
                        }

                    }
                },
                error:function (res) {
                    Swal.fire("Close!", "Something is wrong, Please try again.", "error");
                }
            });

        })

        function checkBox(){
            $("input[id=checkbox]").each(function (i, el) {
                el.setAttribute("checked", "checked");
                el.checked = true;
                el.parentElement.className = "checked";
                if(!selectids.includes(el.value.toString())){
                    selectids.push(el.value.toString());
                }

            });
            $('#count_question').text(selectids.length)
        }

        $('#continue').on('click',function (e){
            e.preventDefault()

            $('#questions').val(selectids.toString())
            // console.log(selectids);
            $("#quizForm").submit();
        })
    </script>
@endsection


