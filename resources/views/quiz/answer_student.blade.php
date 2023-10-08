@extends('layouts.app')
@section('title', 'Answers')
@section('body')

<!-- container -->

{{-- <div class="wrapper-page-light uk-container"> --}}
<div class="wrapper-page-light uk-padding-remove">


    <div class="header-wrap page-dark">
        <div class="uk-container uk-container-large">
            <!-- navbar -->
            @include('layouts.navigation')
        </div>
    </div>
    <div class="page_quiz answers">
        @if($quiz->type==1 || $quiz->type==3)
                @include('quiz.sidbar',['quizzes'=>$quizzes,'rooms'=>$rooms,'assignments'=>$assignments])
        @else

            <div class="class-overview exam uk-container uk-container-large">
                <a class="light-link" href="/classroom/{{auth()->user()->classroom->classroom_id}}/classwork"><i class="fa-solid fa-chevron-left"></i> class overview</a>
            </div>
        @endif
        <div class="uk-padding-medium-bottom p-lr-20">
            {{-- <img src="quiz_demo/35.jpeg" > --}}
            <div class="uk-margin-auto uk-margin-medium-bottom uk-padding-top" uk-grid>

                <div class="uk-margin-small-top uk-width-1-1" id="classroom" >
                    <div class="info">
                        @php
                        if(request()->has('student_id')){
                                $result=$quiz->users()->where('student_id',request('student_id'))->first();
                        }else{
                            $result=$quiz->result;
                        }
                        @endphp
                        <h2 class="uk-margin-remove-bottom rm-t-s">{{$quiz->title}}</h2>

                        <h3 class="uk-margin-remove-bottom uk-margin-small-top">Your Score : {{ checkScore($result->total_correct_answer,count($quiz->questions))}}% - Pass  : @if((int)checkScore($result->total_correct_answer,count($quiz->questions)) >=(int)$quiz->score) <span style="color:green">Yes</span> @else <span style="color:red">No</span> @endif</h3>
                        @if($quiz->type==2)
                        <h3 class="uk-margin-remove-bottom uk-margin-small-top"> Your answers have been sent please wait <span style="color: #2ed955;font-size: 20px;font-weight: bold">{{unlockafterRoomDetail($quiz->price ,$quiz->lock_after,$quiz->usedCoupon->last()->created_at??'')}} </span> to review the correct answers.</h3>
                        @endif

                    </div>
                    <div class="uk-width-1-1" id="questions">
                        @foreach($quiz->questions as $key=> $que)
                            <div class="question-container uk-margin-medium-bottom uk-margin-medium-top">
                                <div class="uk-width-1-2@m uk-width-1-1@s uk-margin-auto ">
                                    <hr>
                                    <div class="uk-width-1-1  card-question uk-text-secondary uk-margin-bottom ">
                                        Q:
                                        <span class="uk-text-secondary uk-inline question-title">{!! $que->title !!}</span>

                                    </div>
                                    @foreach($que->answers as $k=> $ans)
                                        <div class="answer-wrapper">
                                            <label class="answar uk-width-1-1 uk-card uk-card-body uk-margin-small-bottom ">

                                                <input class="notAnswered" @if($que->type==1) type="radio" @else type="checkbox" @endif disabled readonly  @if(isset($result->points[$que->id])) @if(in_array($k,$result->points[$que->id])) checked @endif @endif>
                                                <span class="checkmark"></span>
                                                <span class="uk-margin-medium-left">
                                                    {!!($ans['status'])? $ans['valueCk']:$ans['valueInput'] !!}
                                                </span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach



                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

@endsection


@section('footerScripts')
@parent
<script src="https://cdn.jsdelivr.net/npm/easytimer@1.1.3/dist/easytimer.min.js"></script>
<script src="
https://cdn.jsdelivr.net/npm/js-cookie@3.0.1/dist/js.cookie.min.js
"></script>
    <script>



        @if($quiz->type==1)
            let title,imgae,text,cancelButtonText,showCancelButton;
            @if((int)checkScore($result->total_correct_answer,count($quiz->questions)) <=(int)$quiz->score)
                title= " You failed to pass"
                imgae= '/img/failed.svg'
                text=`<span style="  font-size: 1.5em;font-weight: bold;margin: 0;color: #620303;">{{(int)checkScore($result->total_correct_answer,count($quiz->questions))}} % </span>`
            @else
                title= " You Passed successfully"
                imgae= '/img/pass.svg'
                text=`<span style="  font-size: 1.5em;font-weight: bold;margin: 0;color: #429f7b;">{{(int)checkScore($result->total_correct_answer,count($quiz->questions))}} % </span>`
            @endif
            @if((int)checkScore($result->total_correct_answer,count($quiz->questions)) <=(int)$quiz->score)

                showCancelButton= true
                cancelButtonText= "Retake"
            @endif


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
                window.location.href=`/quiz/{{$quiz->id}}/show_answer`
            }else{
                $.ajax({
                    url: `/reset-quiz?student_id={{auth()->id()}}&quiz={{$quiz->id}}`,
                    dataType: 'json',
                    beforeSend: function()
                    {
                        $('.loading').show();
                    },
                    success: function(data) {
                        if(data.status){
                            window.location.href=`/quiz/{{$quiz->id}}`
                        }
                        var timer = new Timer();
                        var timelimit="{{$quiz->timer*60}}"

                        timer.start({countdown: true, startValues: {seconds: {{$quiz->timer*60}}}});
                        var quiz_id = Cookies.set('quiz_id',{{$quiz->id}});
                        var timer_quiz = Cookies.set('timer_quiz',timer.getTimeValues().toString());
                        $('.loading').hide();
                    },
                    error: function (jqXhr, textStatus, errorMessage) { // error callback
                        alert('Error: ' + errorMessage);
                        $('.loading').hide();
                    }
                })

            }
        })
        @endif
        $('#reset').on('click',function (e){
            e.preventDefault()
            $.ajax({
                url: `/reset-quiz?student_id={{request('student_id')}}&quiz={{$quiz->id}}`,
                dataType: 'json',
                beforeSend: function()
                {
                    $('.loading').show();
                },
                success: function(data) {
                    if(data.status){
                        window.location.href=`/quiz/{{request('quiz_id')}}`

                    }
                    $('.loading').hide();

                },
                error: function (jqXhr, textStatus, errorMessage) { // error callback
                    alert('Error: ' + errorMessage);
                    $('.loading').hide();
                }
            })
        })
    </script>
@endsection
