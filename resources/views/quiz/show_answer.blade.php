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
        @if(auth()->user()->type!==2)
            @if($quiz->type==1 ||$quiz->type==3)

                @include('quiz.sidbar',['quizzes'=>$quizzes,'rooms'=>$rooms,'assignments'=>$assignments])
            @else
                <div class="class-overview exam uk-container uk-container-large">
                    <a class="light-link" href="/classroom/{{auth()->user()->classroom->classroom_id}}/classwork"><i class="fa-solid fa-chevron-left"></i> class overview</a>
                </div>
            @endif
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
                      @if($quiz->type!==3)
                        @if(auth()->user()->type!==2 )

                        <h3 class="uk-margin-remove-bottom uk-margin-small-top">Your Score : {{ checkScore($result->total_correct_answer,count($quiz->questions))}}% - Pass  : @if((int)checkScore($result->total_correct_answer,count($quiz->questions)) >=(int)$quiz->score) <span style="color:green">Yes</span> @else <span style="color:red">No</span> @endif</h3>
                        @endif
                      @endif
                        @if(auth()->user()->type==2 && request('student_id'))
                            <button class="uk-button uk-button-secondary btn-small uk-margin-small-top" id="reset">Reset Progress</button>
                        @endif
                    </div>
                    <div class="uk-width-1-1" id="questions">
                        @foreach($quiz->questions as $key=> $que)
                            <div class="question-container uk-margin-medium-bottom uk-margin-medium-top">
                                <div class="uk-width-1-2@m uk-width-1-1@s uk-margin-auto ">
                                    <hr>
                                    <div class="uk-width-1-1  card-question uk-text-secondary uk-margin-bottom uk-relative">
                                        Q:
                                        <span class="uk-text-secondary uk-inline question-title">{!! $que->title !!}</span>
                                        @if($que->answer_desc)
                                        <span  uk-tooltip="title: Answer Description; delay: 200; pos:right" id="open_answerDesc" class="pulsating-circle open_answerDesc" uk-icon="icon: video-camera
                                        " title="Answer description" data-desc="{{$que->answer_desc}}" href="#modal-center-{{$que->id}}" ></span>
                                        @endif
                                    </div>
                                    @foreach($que->answers as $k=> $ans)
                                        <div class="answer-wrapper">
                                            <label class="answar uk-width-1-1 uk-card uk-card-body uk-margin-small-bottom @if($ans['correct']) correct @endif">

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
<div  id="modal-center-desc" class="negative" uk-modal>
    <div style="height: 400px;" class="uk-modal-dialog uk-modal-body">

        <button class="uk-modal-close-default" type="button" uk-close></button>
        <div class="spinner loading dark-font" style="display:none;z-index: 9999;">
            <div class="circle one"></div>
            <div class="circle two"></div>
            <div class="circle three"></div>
        </div>
        <iframe src="" id="desc" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen style="position:absolute;top:0;left:0;width:100%;height:100%;" title="Final Revision Ch4 -Part2-Last Video"></iframe>

    </div>
</div>

@endsection


@section('footerScripts')
@parent
<script src="https://cdn.fastly.net/npm/easytimer@1.1.3/dist/easytimer.min.js"></script>

    <script>
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
                        window.location.href=`/student`

                    }
                    $('.loading').hide();

                },
                error: function (jqXhr, textStatus, errorMessage) { // error callback
                    alert('Error: ' + errorMessage);
                    $('.loading').hide();
                }
            })
        })

        $('.open_answerDesc').on('click',function(){
            $('.loading').show();
        //  console.log($(this).attr('data-desc'))
            $('#modal-center-desc #desc').attr("src",$(this).attr('data-desc'))
           UIkit.modal('#modal-center-desc').show();
           setTimeout(function() {
            $('.loading').hide();
                }, 1500);
        })
    </script>
@endsection
