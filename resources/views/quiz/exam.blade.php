@extends('layouts.app')
@section('title', 'Test Your Self')
@section('body')

<!-- container -->
<div class="wrapper-page-light uk-padding-remove">
    <div class="header-wrap page-dark">
        <div class="uk-container uk-container-large">
            <!-- navbar -->
            @include('layouts.navigation')
        </div>
    </div>
    {{-- <div class="wrapper-page-light uk-container"> --}}

    @if(request('unlock'))
        <div class="lock-bg page_quiz" >
            <div class="uk-container uk-container-small f-height">
                <div class="uk-margin-xlarge-top uk-margin-xlarge-bottom" uk-grid>
                    <div class="uk-width-1-1@m uk-width-1-1@s">
                        <h3 class="">This Exam Is Locked</h3>
                        <p class="light-color">Please enter your code down there to open the exam.</p>
                        <form action="" method="POST" enctype="multipart/form-data">
                            <div class="uk-flex uk-flex-middle" uk-grid>
                                <div class="uk-width-3-4">
                                    <input class="uk-input locked-coupon" id="code" name="code" type="text" placeholder="Enter Exam Code"  autofocus>
                                </div>
                                <div class="uk-width-1-4 pl-s-10">
                                    <button class="uk-button uk-button-secondary" id="openExam">Open exam</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @else
    <div class="page_quiz" >
        @if($quiz->type==1)

            @include('quiz.sidbar',['quizzes'=>$quizzes,'rooms'=>$rooms])
        @else
        <div class="class-overview exam uk-container uk-container-large">
            <a class="light-link" href="/classroom/ {{auth()->user()->type==1?auth()->user()->classroom->classroom_id:''}}/classwork"><i class="fa-solid fa-chevron-left"></i> class overview</a>
        </div>
        @endif
        <div class="uk-container uk-container-expand f-height" style="">

            <div class="uk-padding-medium-bottom">
                {{-- <img src="quiz_demo/35.jpeg" > --}}
                <div class="uk-margin-auto uk-margin-medium-bottom uk-margin-top" uk-grid>

                    <div class="timerContainer uk-hidden">
                        <div class="timer" >
                            <div class="stm_lms_timer uk-flex uk-flex-center">
                                <div class="stm_lms_timer__circel uk-flex uk-flex-center">
                                    <svg width="35%" height="35%" viewBox="0 0 300 300">
                                        <g fill="none" stroke-width="5">
                                            <circle cx="150" cy="150" r="100" stroke="rgba(204, 204, 204, 0.459)"/>
                                            <circle id="seconds" cx="150" cy="150" r="100" stroke="#fff" transform="rotate(-90 150 150)"/>
                                        </g>
                                    </svg>
                                </div>
                                <div class="stm_lms_timer__icon_arrow" id="timer_arrow">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 21.969 21.968" style="transform-origin: 80% 20%;transform: rotateZ(136deg)">
                                        <path d="M281.486,756.831a4.028,4.028,0,1,1,5.633,5.62L266.78,777.165Z" fill="#fff" transform="translate(-266.781 -755.188)"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="uk-flex uk-flex-center" id="secondTenths">
                                <h3 class="uk-margin-remove values uk-white"></h3>
                                <h3 class="uk-margin-remove values_end uk-white" style="display: none">00:00:00</h3>
                            </div>
                            <div class="uk-flex uk-flex-center">
                                <p class="uk-white">Answer <span id="qAnswered">0</span> /{{count($quiz->questions)}} </p>
                            </div>
                        </div>
                    </div>

                    <div class="uk-width-1-1 uk-text-center">
                        <h1 class="uk-text-secondary uk-margin-remove-top">
                            {{$quiz->title}}
                        </h1>
                    </div>
                    <div class="uk-width-1-1 uk-margin-remove-vertical">
                        <div class="uk-margin-auto-left uk-margin-auto-right uk-width-1-2@m uk-width-1-1@s uk-text-center uk-block uk-margin-top">
                            <p class="dark-font">Time is finished after <em>{{$quiz->timer}}</em>  min</p>
                        </div>
                    </div>

                    <div class="uk-margin-auto uk-width-1-1@m uk-width-1-1@s uk-text-center">
                        <button class="uk-button uk-button-primary btn-quiz uk-width-1-4 startQuiz">
                            {{ __('start') }}
                        </button>
                    </div>

                    <!-- container question -->
                    <form action="{{ route('quiz.answer.store') }}" method="POST" id="target" enctype="multipart/form-data" class="uk-width-1-1 questions-form">
                        @csrf
                        <input type="hidden" name="quiz_id" id="quiz_id" value="{{$quiz->id}}">
                        <input type="hidden" name="check" id="check" value="1">
                        <div class="uk-width-1-1" id="questions" style="display: none"></div>

                        <div class="uk-width-1-1">
                            <div class="spinner loading dark-font" style="display:none;" >
                                <div class="circle one"></div>
                                <div class="circle two"></div>
                                <div class="circle three"></div>
                            </div>
                        </div>
                        <div class="uk-margin-auto uk-width-1-2@m uk-width-1-1@s uk-margin-medium-bottom uk-text-center">
                            <button id="submit" class="uk-button uk-button-primary btn-quiz uk-width-1-4" style="display: none">
                                {{ __('send answers') }}
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@endsection


@section('footerScripts')
@parent
<script src="https://fastly.jsdelivr.net/npm/easytimer@1.1.3/dist/easytimer.min.js"></script>
<script src="https://fastly.jsdelivr.net/npm/js-cookie@3.0.1/dist/js.cookie.min.js"></script>
<script>

    var page = 1,
        lastPage = 0,
        start = false,
        questionAnswered = 0;





    var quiz_id = Cookies.get('quiz_id');
    var timer_quiz = Cookies.get('timer_quiz');

       if(quiz_id == {{$quiz->id}}){

           if(timer_quiz){
               var timeHourLimit=timer_quiz.substring(
                   0,
                   2
               );
               var timeMinLimit=timer_quiz.substring(
                   3,
                   5
               );
               var timeSecondLimit=timer_quiz.substring(
                   6,
                   8
               );
               makeCounter((timeHourLimit*60*60)+(timeMinLimit*60)+Number(timeSecondLimit))
           }
       }


        $(window).scroll(function() {
            // console.log($(window).scrollTop() + $(window).height() >= $(document).height() - 100 ,Math.floor($(window).scrollTop() + $(window).height()), $(document).height() - 100);

        if (Math.floor($(window).scrollTop() + $(window).height()) >= $(document).height() - 100) {
            // console.log(Math.round($(window).scrollTop()) >= Math.round($(document).height() - $(window).height() - 900));
            page++;
            console.log(page , lastPage);
            if (page <= lastPage) {
                loadMoreData(page);
            }
        }
    });

    function loadMoreData(page){
        $('#questions').show()
        let quizId={{$quiz->id}}
        page >= lastPage ? $('#submit').show():null
        $.ajax({
            url: `/get-question-quiz/${quizId}?page=${page}`,
            dataType: 'json',
            beforeSend: function()
            {
                $('.loading').show();
            },
            success: function(data) {
                $('.loading').hide();
                lastPage = data.data.last_page
                var questions = data.data.data
                $.each(questions, function (index, question) {
                    var answers = question.answers,
                        title = question.title,
                        type = question.type,
                        key =   question.id;
                    $("#questions").append(`
                        <div class="question-container uk-margin-medium-bottom uk-margin-medium-top">
                            <div class="uk-width-1-2@m uk-width-1-1@s uk-margin-auto ">
                                <hr>
                                <div class="uk-width-1-1  card-question uk-text-secondary uk-margin-bottom ">
                                    Q:
                                    <span class="uk-text-secondary uk-inline question-title">${title}</span>
                                </div>

                                ${ loopAnswers(answers, key, type)}

                            </div>
                        </div>
                    `);
                });
            },
            error: function (jqXhr, textStatus, errorMessage) { // error callback
                alert('Error: ' + errorMessage);
                $('.loading').hide();
            }
        })
    }

    function loopAnswers(answers, qkey, qtype){
        var result = [];

        for(let index = 0 ; index < answers.length; index++) {
            var answer = answers[index];
            let text

        if(answer.status){
            text=answer.valueCk
        }else{
            text=answer.valueInput
        }
            result += `<div class="answer-wrapper">
                            <label class="answar uk-width-1-1 uk-card uk-card-body uk-margin-small-bottom" for="Ans${answer.id}-Q${qkey}">
                                <input class="notAnswered" type="${qtype == 2 ? 'checkbox' : 'radio'}" value="${index}" id="Ans${answer.id}-Q${qkey}" name="questions[${qkey}][]">
                                <span class="checkmark"></span>
                                <span class="uk-margin-medium-left">
                                    ${text}
                                </span>
                            </label>
                        </div>`
        };

        return result;
    }

    // function to answer question
    $('#questions').on('click','.answar', function(){

        var input = $(this).children('input')
        var allInput = $('[name="'+input.attr('name')+'"]')

        input.attr('type') != 'checkbox' ? input.parent('label').removeClass('choosed') : input.parent('label').removeClass('choosed');

        input.hasClass('notAnswered') ? plusQuestionAnswered(true) : null;
        allInput.hasClass('notAnswered') ? allInput.removeClass('notAnswered') :null;



        if(input.attr('type') == 'checkbox'){
            if(input.is(':checked')){
                input.prop('checked', false)
            }else{
                input.prop('checked', true)
                $(this).addClass('choosed')
            }

            if(!allInput.is(':checked')) {
                plusQuestionAnswered(false);
                allInput.addClass('notAnswered');
            }
        }else{
            input.prop('checked', true);
            $(this).addClass('choosed')
        }

    })

    function plusQuestionAnswered(postv){
        postv ? questionAnswered ++ : questionAnswered --;
        $('#qAnswered').html(questionAnswered);
    }

    $(".startQuiz").click(function(){
        // Pass here value from backend

        var timeSecondLimit = {{$quiz->timer*60}};

        // start = true;

     makeCounter(timeSecondLimit)


    });
    function makeCounter(timeSecondLimit){
        start=true
        $('.startQuiz').parent().remove()
        $('.timerContainer').removeClass('uk-hidden')

        // Loading Question
        loadMoreData(page);

        // Timer Text
        timerText(timeSecondLimit);

        // Timer Arrow
        var degUnit   = 360 / timeSecondLimit ;
        var newDegVal = 360
        // Clock Timer
        var  secondCount = timeSecondLimit;

        setCounter("seconds", secondCount, timeSecondLimit);
        var refreshIntervalId = window.setInterval(function() {
            secondCount--;
            newDegVal -= degUnit

            if (secondCount < 0) {
                clearInterval(refreshIntervalId)
                endExamAfterTime()
            };
            setCounter("seconds", secondCount, timeSecondLimit);
            newDegVal >= 0 ? $('#timer_arrow').css({ transform: 'rotate(' + newDegVal + 'deg)' }) :null;

        }, 1000);
    }


    function setCounter(id, value, max)
    {
        var elem = document.getElementById(id);
        var radius = elem.r.baseVal.value;
        var circumference = radius * 2 * Math.PI;
        var barLength = value * circumference / max;

        elem.setAttribute("stroke-dasharray", barLength + " " + circumference);
    }

    function timerText(timeLimit)
    {
        var timer = new Timer();
        timer.start({countdown: true, startValues: {seconds: timeLimit}});

        $('#secondTenths .values').html(timer.getTimeValues().toString());

        timer.addEventListener('secondsUpdated', function (e) {
            $('#secondTenths .values').html(timer.getTimeValues().toString());
            // console.log($('#check').val())
            if($('#check').val()=='1'){
                Cookies.set('timer_quiz', timer.getTimeValues().toString())
                Cookies.set('quiz_id', {{$quiz->id}})
            }
        });

        timer.addEventListener('targetAchieved', function (e) {
            $('#secondTenths .values').html('ENDED!!');
        });
    }

    $('#submit').click(function(e){
        e.preventDefault();
        endquiz()
    });
    function endExamAfterTime(){
        var form = $("#target");
        var url = form.attr('action');
        $.ajax({
            url: url,
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            data: form.serialize(),
            success: function (res) {
                if(res.status){

                    $('#secondTenths .values').hide();
                    $('#secondTenths .values_end').show();
                    @php
                        $point=\App\Models\Result::where(['student_id'=>auth()->id(),'quiz_id'=>$quiz->id])->first();
                        if($point){
                            $point=$point->total_correct_answer;
                        }
                    @endphp
                    let title,imgae,text,cancelButtonText,showCancelButton;
                    if(res.data.result==0){
                        title= " You failed to pass"
                        imgae= '/img/failed.svg'
                        text=`
                            <span style="  font-size: 1.5em;display: inline-block;margin: 0;margin-bottom: 10px;color: inherit;">{{$quiz->title}} </span><br>
                            <span style="  font-size: 1.5em;font-weight: bold;margin: 0;color: #e32526;">${res.data.score} % </span>


`
                    }else{
                        title= " You Passed successfully"
                        imgae= '/img/pass.svg'
                        text=`  <span style="  font-size: 1.5em;display: inline-block;margin-bottom: 10px;margin: 0;margin-bottom: 10px;color: inherit;">{{$quiz->title}} </span><br>
                            <span style="  font-size: 1.5em;font-weight: bold;margin: 0;color: #0ea158;">${res.data.score} % </span>

`
                    }
                    if(res.data.retake){
                        cancelButtonText= "Retake"
                        showCancelButton=true
                    }
                    Swal.fire({
                        title: title,
                        imageUrl: imgae,
                        html:text,
                        imageWidth: 400,
                        imageHeight: 200,
                        allowOutsideClick: false,
                        cancelButtonText:cancelButtonText,
                        showCancelButton:showCancelButton,
                        confirmButtonColor: '#DD6B55',
                        confirmButtonText: 'Continue',
                        @if($quiz->show_answer==1) showDenyButton: true,  denyButtonText: `Show Answer`,denyButtonColor:'#0ea158' @endif
                    }).then(function (result){

                        if (result.isConfirmed) {
                            var timer = new Timer();
                            var timelimit="{{$quiz->timer*60}}"

                            timer.start({countdown: true, startValues: {seconds: {{$quiz->timer*60}}}});
                            var quiz_id = Cookies.set('quiz_id',{{$quiz->id}});
                            var timer_quiz = Cookies.set('timer_quiz',timer.getTimeValues().toString());
                            @if($quiz->type==1)
                                window.location.href=`/room/{{$quiz->room_id}}`
                            @else
                                window.location.href=`/classroom/{{session('classroom')}}/classwork`
                            @endif

                        } else if (result.isDenied) {
                            var timer = new Timer();
                            var timelimit="{{$quiz->timer*60}}"

                            timer.start({countdown: true, startValues: {seconds: {{$quiz->timer*60}}}});
                            var quiz_id = Cookies.set('quiz_id',{{$quiz->id}});
                            var timer_quiz = Cookies.set('timer_quiz',timer.getTimeValues().toString());
                            window.location.href=`/quiz/${$('#quiz_id').val()}/student_answer`

                        }else{
                            var timer = new Timer();
                            var timelimit="{{$quiz->timer*60}}"

                            timer.start({countdown: true, startValues: {seconds: {{$quiz->timer*60}}}});
                            var quiz_id = Cookies.set('quiz_id',{{$quiz->id}});
                            var timer_quiz = Cookies.set('timer_quiz',timer.getTimeValues().toString());
                            $.ajax({
                                url: `/reset-quiz?student_id={{auth()->id()}}&quiz={{$quiz->id}}`,
                                dataType: 'json',
                                beforeSend: function()
                                {
                                    $('.loading').show();
                                },
                                success: function(data) {
                                    if(data.status){
                                        var timer = new Timer();
                                        var timelimit="{{$quiz->timer*60}}"

                                        timer.start({countdown: true, startValues: {seconds: {{$quiz->timer*60}}}});
                                        var quiz_id = Cookies.set('quiz_id',{{$quiz->id}});
                                        var timer_quiz = Cookies.set('timer_quiz',timer.getTimeValues().toString());
                                        window.location.href=`/quiz/{{$quiz->id}}`
                                        window.location.href=`/quiz/{{$quiz->id}}`
                                    }
                                    $('.loading').hide();
                                },
                                error: function (jqXhr, textStatus, errorMessage) { // error callback
                                    alert('Error: ' + errorMessage);
                                    $('.loading').hide();
                                }
                            })

                        }

                    })
                }
                },
            error:function (res) {
                Swal.fire("Close!", "Something is wrong, Please try again.", "error");
            }
        });
    }
    function endquiz()
    {

         Swal.fire({
            title: " Are you sure you want to submit?",
            html:'',
            icon:"warning",
             allowOutsideClick: false,
             type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Send',
             cancelButtonText: "Cancel",
            closeOnConfirm: false,
            closeOnCancel: false
        }).then(function (result){
            if (result.isConfirmed){
                var form = $("#target");
                var url = form.attr('action');
                $('#submit').attr('disabled','disabled');
                $('.loading').show();
                $.ajax({
                    url: url,
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    data: form.serialize(),
                    success: function (res) {

                        $('.loading').hide();
                        if(res.status){
                            $('#check').val('0')
                            var timer = new Timer();
                            var timelimit="{{$quiz->timer*60}}"
                            timer.start({countdown: true, startValues: {seconds: {{$quiz->timer*60}}}});
                            var quiz_id = Cookies.set('quiz_id',{{$quiz->id}});
                            var timer_quiz = Cookies.set('timer_quiz',timer.getTimeValues().toString());
                            $('#secondTenths .values').hide();
                            $('#secondTenths .values_end').show();
                         @php
                            $point=\App\Models\Result::where(['student_id'=>auth()->id(),'quiz_id'=>$quiz->id])->first();
                            if($point){
                                $point=$point->total_correct_answer;
                            }
                         @endphp
                            let title,imgae,text,cancelButtonText,showCancelButton;
                            if(res.data.result==0){
                                title= " You failed to pass"
                                imgae= '/img/failed.svg'
                                text=`  <span style=" font-size: 1.5em;display: inline-block;margin: 0;margin-bottom: 10px;color: inherit;">{{$quiz->title}} </span><br>
                            <span style="  font-size: 1.5em;font-weight: bold;margin: 0;color: #e32526;">${res.data.score} % </span>

`
                            }else{
                                title= " You Passed successfully"
                                imgae= '/img/pass.svg'
                                text=`  <span style="  font-size: 1.5em;display: inline-block;margin: 0;margin-bottom: 10px;color: inherit;">{{$quiz->title}} </span><br>
                            <span style="  font-size: 1.5em;font-weight: bold;margin: 0;color: #0ea158;">${res.data.score} % </span>
`
                            }
                            if(res.data.retake==true){
                                cancelButtonText= "Retake"
                                showCancelButton=true
                            }
                            Swal.fire({
                                title: title,
                                imageUrl: imgae,
                                html:text,
                                imageWidth: 400,
                                imageHeight: 200,
                                allowOutsideClick: false,
                                cancelButtonText:cancelButtonText,
                                showCancelButton:showCancelButton,
                                confirmButtonColor: '#DD6B55',
                                confirmButtonText: 'Continue',
                                @if($quiz->show_answer==1) showDenyButton: true,  denyButtonText: `Show Answer`,denyButtonColor:'#0ea158' @endif
                            }).then(function (result){

                                if (result.isConfirmed) {

                                    @if($quiz->type==1)
                                        window.location.href=`/room/{{$quiz->room_id}}`
                                    @else
                                        window.location.href=`/classroom/{{session('classroom')}}/classwork`
                                    @endif
                                } else if (result.isDenied) {
                                    window.location.href=`/quiz/${$('#quiz_id').val()}/student_answer`
                                }else{
                                    $.ajax({
                                        url: `/reset-quiz?student_id={{auth()->id()}}&quiz={{$quiz->id}}&retake=true`,
                                        dataType: 'json',
                                        beforeSend: function()
                                        {
                                            $('.loading').show();
                                        },
                                        success: function(data) {
                                            if(data.status){
                                                window.location.href=`/quiz/{{$quiz->id}}`
                                            }
                                            $('.loading').hide();
                                        },
                                        error: function (jqXhr, textStatus, errorMessage) { // error callback
                                            alert('Error: ' + errorMessage);
                                            $('.loading').hide();
                                        }
                                    })

                                }

                            })
                        }
                    },
                    error:function (res) {
                        $('.loading').hide();
                        Swal.fire("Close!", "Something is wrong, Please try again.", "error");
                    }
                });
            } else {
                Swal.fire("Cancel", "Cancel Success", "success");
            }
        })
    }
    $(document).on('click','#openExam',function (e){
        e.preventDefault()
        @php
        $classroom_user=\App\Models\ClassroomStudent::where('user_id',auth()->id())->first();
         if(!$classroom_user){

            $classroom_user='';
         }
        @endphp
        @if(!empty($classroom_user))
        let classroom="{{  in_array($classroom_user->classroom_id,explode(',',$quiz->classroom_id)) ?$classroom_user->classroom_id:'' }}"
        @else
        let classroom='';
        @endif
        let grade_id="{{request('grade')}}"
        let quiz_id="{{$quiz->id}}"
        $.ajax({
            url: `/unlock-code?quiz_id=${quiz_id}&classroom_id=${classroom}&grade_id=${grade_id}&code=${$('#code').val()}`,
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            beforeSend: function()
            {
                $('.loading').show();
            },
            success: function(data) {

                    Swal.fire("success!", data.message, "success");

                setTimeout(window.location.reload(), 8000)

            },
            error: function (jqXhr, textStatus, errorMessage) { // error callback

                Swal.fire("warning!", jqXhr.responseJSON.message, "warning");

                $('.loading').hide();
            }
        })
    })


</script>
@endsection
