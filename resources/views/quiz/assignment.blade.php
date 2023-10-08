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

    <div class="page_quiz" >

            @include('quiz.sidbar',['quizzes'=>$assignments,'rooms'=>$rooms,'quiz'=>$assignment,'assignments'=>$quizes])

        <div class="uk-container uk-container-expand f-height" style="">

            <div class="uk-padding-medium-bottom p-lr-20">
                {{-- <img src="quiz_demo/35.jpeg" > --}}
                <div class="uk-margin-auto uk-margin-medium-bottom uk-margin-top" uk-grid>


                    <div class="uk-width-1-1 uk-text-center">

                        <h3 class="uk-text-secondary ">
                            assignment  <b>For</b>   {{$assignment->room->title}}
                        </h3>
                        <h1 class="uk-text-secondary uk-margin-remove-top">
                            {{$assignment->title}}
                        </h1>
                    </div>

                    <div class="uk-margin-auto uk-width-1-1@m uk-width-1-1@s uk-text-center">
                        <button class="uk-button uk-button-primary btn-quiz uk-width-1-4 startQuiz">
                            {{ __('start') }}
                        </button>
                    </div>

                    <!-- container question -->
                    <form action="{{ route('quiz.answer.store') }}" method="POST" id="target" enctype="multipart/form-data" class="uk-width-1-1 questions-form">
                        @csrf
                        <input type="hidden" name="quiz_id" id="quiz_id" value="{{$assignment->id}}">
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
</div>

@endsection


@section('footerScripts')
@parent
<script src="https://cdn.jsdelivr.xyz/npm/easytimer@1.1.3/dist/easytimer.min.js"></script>
<script src="
https://cdn.jsdelivr.xyz/npm/js-cookie@3.0.1/dist/js.cookie.min.js
"></script>
<script>

    var page = 1,
        lastPage = 0,
        start = false,
        questionAnswered = 0;
        Cookies.set('quiz_id', {{$assignment->id}})

    var quiz_id = Cookies.get('quiz_id');



        $(window).scroll(function() {
        if ($(window).scrollTop() >= ($(document).height() - $(window).height() - 200)) {
            if($('#questions').css('display') == 'block'){
                page++;
                if (page <= lastPage) {
                    loadMoreData(page);
                }
            }
        }
    });

    function loadMoreData(page){
        $('#questions').show()
        let quizId={{$assignment->id}}
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

        var timeSecondLimit = {{$assignment->timer*60}};

        // start = true;

     makeCounter(timeSecondLimit)


    });
    function makeCounter(timeSecondLimit){
        start=true
        $('.startQuiz').parent().remove()
        $('.timerContainer').removeClass('uk-hidden')

        // Loading Question
        loadMoreData(page);
    }
    $('#submit').click(function(e){
        e.preventDefault();
        endquiz()
    });

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
                            $('#secondTenths .values').hide();
                            $('#secondTenths .values_end').show();
                         @php
                            $point=\App\Models\Result::where(['student_id'=>auth()->id(),'quiz_id'=>$assignment->id])->first();
                            if($point){
                                $point=$point->total_correct_answer;
                            }
                         @endphp
                            let title,imgae,text,cancelButtonText,showCancelButton;
                            if(res.data.result==0){
                                title= " You failed to pass"
                                imgae= '/img/failed.svg'
                                text=`<span style="  font-size: 1.5em;font-weight: bold;margin: 0;color: #620303;">${res.data.score} % </span>`
                            }else{
                                title= " You Passed successfully"
                                imgae= '/img/pass.svg'
                                text=`<span style="  font-size: 1.5em;font-weight: bold;margin: 0;color: #429f7b;">${res.data.score} % </span>`
                            }
                            if(res.data.retake==true){
                                cancelButtonText= "Retake"
                            }
                            window.location.href=`/quiz/${$('#quiz_id').val()}/show_answer`

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


</script>
@endsection
