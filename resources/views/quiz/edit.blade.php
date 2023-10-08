@extends('layouts.app')
@section('title', 'Edit Quiz')
@section('css')
    <style>
        .success {
            background: #0de37a;
        }

        .question img {
            width: 100px;
            height: 100px;
        }

        #sortable {
            cursor: move;
        }

        .uk-switch {
            position: relative;
            display: inline-block;
            height: 34px;
            width: 60px;
        }

        /* Hide default HTML checkbox */
        .uk-switch input {
            display: none;
        }

        /* Slider */
        .uk-switch-slider {
            background-color: rgba(0, 0, 0, 0.22);
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            border-radius: 500px;
            bottom: 0;
            cursor: pointer;
            transition-property: background-color;
            transition-duration: .2s;
            box-shadow: inset 0 0 2px rgba(0, 0, 0, 0.07);
        }

        /* Switch pointer */
        .uk-switch-slider:before {
            content: '';
            background-color: #fff;
            position: absolute;
            width: 30px;
            height: 30px;
            left: 2px;
            bottom: 2px;
            border-radius: 50%;
            transition-property: transform, box-shadow;
            transition-duration: .2s;
        }

        /* Slider active color */
        input:checked + .uk-switch-slider {
            background-color: #39f !important;
        }

        /* Pointer active animation */
        input:checked + .uk-switch-slider:before {
            transform: translateX(26px);
        }

        .switcher {
            display: flex;
            justify-content: space-between;
            margin-bottom: 18px;
            align-items: baseline;
        }
    </style>

@endsection
@section('body')
    <!-- container -->
    <div class="wrapper-page-light f-height">
        {{-- Instructor sidebar --}}
        <x-instructor-sidebar />
        <!-- container header -->
        <div class="header-wrap page-dark">
            <div class="uk-container uk-container-expand rm-expand">
                <!-- navbar -->
                @include('layouts.navigation')
                <!-- breadcrumb -->
                <div class="uk-flex uk-flex-middle uk-margin-small-left" uk-grid>

                    <div class="page-header uk-margin-medium-top uk-margin-medium-bottom breadcrumb uk-width-3-4">
                        <h3 class="uk-margin-remove-bottom title-add">Add New Quiz</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="uk-container uk-container-expand rm-expand uk-margin-medium-top uk-margin-left">
            <div class="add-classroom">
                <form action="{{ route('quiz.update',$quiz->id) }}" method="POST" id="target" enctype="multipart/form-data" class="room-form">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="pageTbl" value="{{$pageTbl}}">
                    <fieldset class="uk-fieldset add-new" uk-grid>
                        <x-input id="questions" class="uk-width-1-1" type="text" name="questions"  value="{{implode(',',$questions)}}" hidden/>

                        <div class="uk-margin uk-width-1-1">
                            <input class="uk-input title @error('title')error-border @enderror" name="title" type="text" value="{{$quiz->title}}" placeholder="Quiz title goes here....." autofocus>
                            @error('title')
                            <span class="error-msg">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="uk-margin uk-width-1-4">
                            <label class="uk-form-label" for="class_room_id"><span>*</span> Type</label>
                            <select  class="uk-select @error('type')error-border @enderror" id="type" name="type">
                                <option readonly selected disabled>Select........</option>
                                <option value="1" @if($quiz->type=='1') selected @endif>quiz in room</option>
                                <option value="2" @if($quiz->type=='2') selected @endif>Exam in classroom</option>
                                <option value="3" @if($quiz->type=='3') selected @endif>Assignment in room</option>
                            </select>
                            @error('type')
                            <span class="error-msg">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="uk-margin uk-width-1-4">

                            <label class="uk-form-label" for="class_room_id"><span>*</span> Grade</label>
                            <select  class="uk-select @error('grade')error-border @enderror" id="grade" name="grade">
                                <option readonly selected disabled>Select........</option>
                                @foreach ( $grades as $grade )
                                    <option value="{{$grade->id}}" @if($grade->id==$quiz->grade) selected @endif>{{ $grade->name }}</option>
                                @endforeach
                            </select>
                            @error('grade')
                            <span class="error-msg">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="uk-margin uk-width-1-4 room" >
                            <label class="uk-form-label" for="room_id"><span>*</span> Room</label>
                            <select  class="uk-select @error('room_id')error-border @enderror" id="room_id" name="room_id">
                                <option readonly selected disabled>Select........</option>
                            </select>
                            @error('room_id')
                            <span class="error-msg">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="uk-margin uk-width-1-4 classroom" >
                            <label class="uk-form-label" for="classroom_id"><span>*</span> Classroom</label>
                            <select  multiple class="uk-select @error('classroom_id')error-border @enderror" id="classroom_id" name="classroom_id[]">
                                <option readonly selected disabled>Select........</option>
                            </select>
                            @error('classroom_id')
                            <span class="error-msg">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="uk-margin uk-width-1-4">
                            <label class="uk-form-label" for="class_room_id"><span>*</span> Question Bank</label>
                            <select multiple  class="uk-select @error('question_bank_id')error-border @enderror" id="question_bank" name="question_bank_id[]">

                            </select>
                            @error('question_bank_id')
                            <span class="error-msg">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="uk-margin uk-width-1-1 questions"  >
                            <label class="uk-form-label" for="sub_category"><span>*</span> Questions  <a id="quesion_colapse"><span uk-icon="icon: triangle-down"></span> </a> <span class="uk-badge" id="count_question"></span> </label>
                            <div class="questions" id="ques_col">
                                <div class="uk-overflow-auto uk-margin-medium-top x-scrollbar">
                                    <table class="uk-table uk-table-hover uk-table-middle uk-table-divider rooms" id="questions_table" style="width:100%;">
                                        <input type="hidden" name="select_all" id="select_all" value="">
                                        <thead>
                                        <tr>
                                            <th class="uk-table-shrink"><input id="checkbox_all" class="uk-checkbox" type="checkbox"></th>
                                            <th class="uk-table-expand">title</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                                @error('questions')
                                <span class="error-msg">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="uk-margin uk-width-1-4 " @if($quiz->type!==2) style="display: none" @endif>
                            <label class="uk-form-label" for="price"><span>*</span> Price</label>
                            <input class="uk-input @error('price')error-border @enderror" name="price" value="{{$quiz->price}}" type="number"  min="1" placeholder="Price here....." autofocus>
                            @error('price')
                            <span class="error-msg">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="uk-margin uk-width-1-4 " @if($quiz->type==3) style="display: none" @endif >
                            <label class="uk-form-label" for="classroom_id"><span>*</span> Timer (in minutes)</label>
                            <input class="uk-input @error('timer')error-border @enderror" name="timer" value="{{$quiz->timer}}" type="number" placeholder="Timer here....." autofocus>

                            @error('timer')
                            <span class="error-msg">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="uk-margin uk-width-1-4 lock " style="display: none" >
                            <label class="uk-form-label" for="lock_after"><span>*</span> Lock after</label>
                            <input class="uk-input @error('timer')error-border @enderror"  id="lock_after" name="lock_after" value="{{$quiz->lock_after}}" type="number" placeholder="lock after here....." autofocus>

                            @error('lock_after')
                            <span class="error-msg">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="uk-margin uk-width-1-4  score" >
                            <label class="uk-form-label" for="classroom_id"><span>*</span> Score to pass a quiz (%)</label>
                            <input class="uk-input @error('score')error-border @enderror" name="score" type="number" value="{{$quiz->score}}" placeholder="Score here....." autofocus>

                            @error('score')
                            <span class="error-msg">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="uk-margin uk-width-1-2">
                            <label class="uk-form-label" for="absence-rooms"> Randomize Answer</label>
                            <div class="switcher">
                                <label class="uk-switch " for="randomize_answer">
                                    <input type="checkbox"  id="randomize_answer" @if($quiz->randomize_answer==1) checked @endif  name="randomize_answer" >
                                    <div class="uk-switch-slider"></div>
                                </label>
                            </div>
                            @error('type')
                            <span class="error-msg">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="uk-margin uk-width-1-2">
                            <label class="uk-form-label" for="randomize_question"> Randomize Question</label>
                            <div class="switcher">
                                <label class="uk-switch " for="randomize_question">
                                    <input type="checkbox"  id="randomize_question" @if($quiz->randomize_question==1) checked @endif name="randomize_question" >
                                    <div class="uk-switch-slider"></div>
                                </label>
                            </div>
                            @error('type')
                            <span class="error-msg">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="uk-margin uk-width-1-2">
                            <label class="uk-form-label" for="absence-rooms"> Show Answer</label>
                            <div class="switcher">
                                <label class="uk-switch " for="show_answer">
                                    <input type="checkbox"  id="show_answer" @if($quiz->show_answer==1) checked @endif  name="show_answer" >
                                    <div class="uk-switch-slider"></div>
                                </label>
                            </div>
                            @error('show_answer')
                            <span class="error-msg">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="uk-margin uk-width-1-2">
                            <label class="uk-form-label" for="absence-rooms"> Retake</label>
                            <div class="switcher">
                                <label class="uk-switch " for="retake">
                                    <input type="checkbox"  id="retake"  name="retake"  @if($quiz->retake==1) checked @endif>
                                    <div class="uk-switch-slider"></div>
                                </label>
                            </div>
                            @error('retake')
                            <span class="error-msg">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="uk-width-1-1">
                            <button class="uk-button uk-button-secondary" id="continue">Continue <i class="fa-solid fa-arrow-right"></i></button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
        @include('questionBank.answerDescModal')
    </div>

@endsection
@section('footerScripts')
    @section('script')
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

        <script>
            let selectids=$('#questions').val()!=''?$('#questions').val().split(','):[]

            $('#count_question').text(selectids.length)
            $(document).ready(function() {
                $('.uk-select').select2({
                    placeholder:"select Rooms"
                })
            })

            var bar = document.getElementById('js-progressbar');
            let url ,id
            if({{$quiz->type}}!==2){
                 url='get_room'
                 id='room_id'
                $(".classroom").fadeOut()
            }else{
                url='get_classroom'
                id='classroom_id'
                $(".room").fadeOut()
                $(".lock").fadeIn()



            }
            getDataRoomOrClass(url,{{$quiz->grade}},id)
            geQuestionBank({{$quiz->grade}})
            let questions="{{$quiz->question_bank_id}}"
            getQuestions(questions.split(','))



            $(document).on('change','#type',function (e){
                let type=$(this).val();
                if(type=="2"){
                    $(".room").fadeOut()
                    $(".classroom").fadeIn()
                    $(".Lock").fadeIn()
                    $(".price").fadeIn()
                    $(".score").fadeIn()
                    $(".Timer").fadeIn()


                }else if(type=="3"){
                    $(".room").fadeIn()
                    $(".classroom").fadeOut()
                    $(".price").fadeOut()
                    $(".Lock").fadeOut()
                    $(".Timer").fadeOut()
                    $(".score").fadeOut()

                    $('#retake').prop( "checked", true )
                    $('#show_answer').prop( "checked", true )
                }
                else{
                    $(".room").fadeIn()
                    $(".classroom").fadeOut()
                    $(".price").fadeOut()
                    $(".score").fadeIn()
                    $(".Timer").fadeIn()

                    $('#retake').prop( "checked", true )
                    $('#show_answer').prop( "checked", true )
                }
            })
            $(document).on('click','#quesion_colapse',function (e){

                $('#ques_col').toggle()
            })

            $(document).on('change','#grade',function (e){
                e.preventDefault();
                let grade= $(this).val();
                let type= $('#type').val();
                let id ,url;
                if(type=="2"){
                    url='get_classroom';
                    id='classroom_id'
                    $("#classroom_id").fadeIn()
                    $("#room_id").fadeOut()
                    $(".lock").fadeIn()
                }else{
                    url='get_room';
                    id='room_id';
                    $("#classroom_id").fadeOut()
                    $("#room_id").fadeIn()
                }
                getDataRoomOrClass(url,grade,id)
                geQuestionBank(grade)



            });
            function getDataRoomOrClass(url,grade,id){
                $.ajax({
                    url: `/${url}/${grade}`,
                    type: "get",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function (res) {
                        if(res.status){
                            $(`#${id}`).empty()
                            if(url!=='get_classroom'){
                                $(`#${id}`).append('<option readonly selected disabled>Select........</option>')
                            }
                            res.data.forEach((item)=>{
                                let items='';
                                if({{(string)$quiz->type}}=='1' || {{(string)$quiz->type}}=='3'  ) {
                                    if (item.id == {{$quiz->room_id??'null'}}) {
                                        items = 'selected'
                                    }
                                }
                                else{
                                    let arr="{{($quiz->classroom_id)??'null'}}";
                                    let ss=arr.split(',');

                                    if (ss.includes(item.id.toString()) ) {
                                        items = 'selected'
                                    }
                                }

                                $(`#${id}`).append(`
                                    <option value="${item.id}" ${items}>${item.title}</option>
                                    `)
                            });

                        }
                    },
                    error:function (res) {
                        Swal.fire("Close!", "Something is wrong, Please try again.", "error");
                    }
                });
            }
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
            function geQuestionBank(grade){
                let id = 'question_bank';
                $.ajax({
                    url: `/getQuestionBank/`,
                    type: "get",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function (res) {
                        if(res.status){
                            $(`#${id}`).empty()
                            res.data.forEach((item)=>{
                                let items='';

                                    if(item.id== {{$quiz->question_bank_id}}){
                                        items='selected'
                                    }

                                $(`#${id}`).append(`
                                    <option value="${item.id}" ${items}>${item.name}</option>
                                    `)
                            });

                        }
                    },
                    error:function (res) {
                        Swal.fire("Close!", "Something is wrong, Please try again.", "error");
                    }
                });
            }
function getQuestions(ids){
    $('#questions_table').DataTable().clear().destroy()
    $(".loading").show();
    var room_student_table
    setTimeout(function () {
        room_student_table =  $('#questions_table').DataTable({
            processing: true,
            serverSide: true,
            lengthMenu: [5, 10, 20, 50],
            pageLength: 10,
            retrieve: true,
            // paging: false,
            responsive: true,
            initComplete: function () {
                console.log('@@@ init complete @@@');
                $(".loading").hide();
            },
            dom: 'Blfrtip',
            order: [[0, 'desc']],
            ajax: {
                url: "/get-question-in-bank-data-quiz",
                data: function (d) {
                    d.ids=ids;
                    // d.classroom_id=$('#classroom_id').val();
                    // d.grade_id=$('#grade_id').val();
                },
            },
            columns: [
                {
                    data:'id',orderable: false, render:function (data,type,full){
                        let items
                        // console.log(selectidss,'5548')
                        if(selectids.includes(data.toString())){
                            items='checked';
                        }

                        return `<input class="uk-checkbox" id="checkbox" ${items} value="${data}" type="checkbox">`;}
                },
                {data: 'question' , className: ' uk-text-left',
                    render:function (data,type,full){
                        return `<a id="moreInfo" data-id="${full['id']}">${data}</a>`;
                    }
                },
            ]
        });
    }, 500);
}
            $(document).on('change','#question_bank',function (e){
                selectids=[]
                e.preventDefault();
                $('.questions').fadeIn()
                let ids= $(this).val();
                if($(this).val().length>0){
                    getQuestions(ids)
                }else{
                    $('.questions').fadeOut()

                }
                $('#count_question').text(selectids.length)
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
                                    correct='correct'
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



            $('#continue').on('click',function (e){
                e.preventDefault()

                $('#questions').val(selectids.toString())
                $("#target").submit();
            })
        </script>
    @endsection
