<div class="uk-container uk-container-large">
    <a class="icon-side" uk-toggle="target: #offcanvas-overlay" uk-navbar-toggle-icon></a>
    <div class="" id="offcanvas-overlay" uk-offcanvas="overlay: true">
        <div class="uk-offcanvas-bar quiz-side">
            <div class="side-wrapper">
                <div class="header-side" uk-grid>
                    <div class="uk-width-2-3 uk-flex uk-flex-middle">
                        <div class="class-overview">
                            <a class="light-link" href="/classroom/{{auth()->user()?->classroom?->classroom_id}}/classwork" ><i class="fa-solid fa-chevron-left"></i> class overview</a>
                        </div>
                    </div>
                    <div class="uk-width-1-3 profile">
                        <button class="uk-offcanvas-close" type="button" uk-close></button>
                    </div>
                </div>
                <div class="body-side uk-margin-top">
                    <div class="room-content uk-flex uk-flex-middle" uk-grid>
                        <div class="room-icon uk-width-1-3"><img class="" src="/img/xp-lesson.svg" alt="room-icon"></div>
                        <div class="room-title uk-width-expand">
                            <div class="title">{{ $rooms['title'] }}</div>
                            <div class="components">
                                <span class="lessons-number uk-margin-small-right"><i class="fa-solid fa-book"></i> <span>{{ $rooms['lessons_num'] }}</span> lessons</span>
                                <span class="room-duration"><i class="fa-solid fa-clock"></i> {{ $rooms['duration'] }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="divider uk-margin-bottom uk-margin-small-top"></div>
                </div>
                @php
                    $result=0;
                    $room=\App\Models\Room::find($rooms['id']);
                    $quizzz=$room->quizzes;
                    foreach ($quizzz as $item){
                        if($item->result==null){
                            $result=1;
                        }
                    }
                @endphp
                <div class="body-conent">
                    @if(count($quizzes)>0)

                    <div class="lessons-header">
                        <span>Quizzes</span>
                        <span>Examine yourself</span>
                    </div>
                    <ul class="lessons-list">
                        @forelse($quizzes as $index=> $quiz2)
                            <li >
                                <div class="uk-flex uk-flex-middle lesson-body uk-margin-remove-left uk-grid uk-margin-small-top @if($quiz2->id==$quiz->id) active @endif" uk-grid>
                                    @if($quiz2->result)
                                        <div  class="uk-width-1-5">
                                            <div class="check-completed">
                                                <i class="fa-solid fa-circle-check"></i>
                                            </div>
                                        </div>
                                    @else
                                        <div class="uk-width-1-5" >
                                            <div class="lesson-number"><span>0{{$index+1}}</span></div>
                                        </div>
                                    @endif

                                    <div class="uk-width-expand lesson-content  ">
                                        <a href="/quiz/{{$quiz2->id}}">
                                            <div>{{$quiz2->title}}</div>

                                        </a>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li >
                                <div class="uk-flex uk-flex-middle lesson-body uk-margin-remove-left uk-grid uk-margin-small-top uk-grid-stack"  uk-grid>

                                    <div class="uk-width-expand lesson-content">
                                        <a href="#">
                                            <div>Not Found Quiz</div>
                                        </a>
                                    </div>
                                </div>
                            </li>
                        @endforelse
                    </ul>
                    @endif

                    <div class="lessons-header uk-margin-medium-top">
                        <span>Lessons</span>
                        <span>Getting started</span>
                    </div>

                    <ul class="lessons-list">
                        @forelse( $rooms['lessons'] as $lesson)
                            <li >
                                @if(!$result)
                                    <div  class="uk-flex uk-flex-middle lesson-body uk-margin-remove-left uk-grid uk-margin-small-top"  uk-grid>
                                        @if($lesson['completed'])
                                            <div class="uk-width-1-5">
                                                <div class="check-completed">
                                                    <i class="fa-solid fa-circle-check"></i>
                                                </div>
                                            </div>
                                        @else
                                            <div class="uk-width-1-5"  >
                                                <div class="lesson-number"><span>{{$lesson['lesson_order']}}</span></div>
                                            </div>
                                        @endif

                                        <div class="uk-width-expand lesson-content">
                                            <a href="/room/{{$rooms['id']}}?lesson={{$lesson['title']}}">
                                                <div>{{$lesson['title']}}</div>
                                                <div>
                                                    <span class="room-duration"><i class="fa-solid fa-clock"></i>  {{$lesson['duration'] }}</span>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <div   class="uk-flex uk-flex-middle lesson-body uk-margin-remove-left uk-grid uk-margin-small-top"  uk-grid>
                                        <div  class="uk-width-1-5">
                                            <div class="lock-room-icon">
                                                <i class="fa-solid fa-lock"></i>
                                            </div>
                                        </div>


                                        <div class="uk-width-expand lesson-content"  >
                                            <a href="/room/{{$rooms['id']}}?lesson={{$lesson['title']}}"  >
                                                <div>{{$lesson['title']}}</div>
                                                <div>
                                                    <span class="room-duration"><i class="fa-solid fa-clock"></i> {{$lesson['duration'] }}</span>
                                                </div>
                                            </a>
                                        </div>
                                    </div>

                                @endif

                            </li>
                        @empty
                            <li >
                                <div class="uk-flex uk-flex-middle lesson-body uk-margin-remove-left uk-grid uk-margin-small-top uk-grid-stack"  uk-grid>

                                    <div class="uk-width-expand lesson-content">
                                        <a href="#">
                                            <div>Not Found Lesson</div>
                                        </a>
                                    </div>
                                </div>
                            </li>
                        @endforelse

                    </ul>
                    @if(count($assignments)>0)

                    <div class="lessons-header">
                        <span>Assignment</span>
                        <span>Examine yourself</span>
                    </div>
                    <ul class="lessons-list">
                        @forelse($assignments as $index=> $quiz2)
                            <li >
                                <div class="uk-flex uk-flex-middle lesson-body uk-margin-remove-left uk-grid uk-margin-small-top @if($quiz2->id==$quiz->id) active @endif" uk-grid>
                                    @if($quiz2->result)
                                        <div  class="uk-width-1-5">
                                            <div class="check-completed">
                                                <i class="fa-solid fa-circle-check"></i>
                                            </div>
                                        </div>
                                    @else
                                        <div class="uk-width-1-5" >
                                            <div class="lesson-number"><span>0{{$index+1}}</span></div>
                                        </div>
                                    @endif

                                    <div class="uk-width-expand lesson-content  ">
                                        <a href="/assignment/{{$quiz2->id}}">
                                            <div>{{$quiz2->title}}</div>

                                        </a>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li >
                                <div class="uk-flex uk-flex-middle lesson-body uk-margin-remove-left uk-grid uk-margin-small-top uk-grid-stack"  uk-grid>

                                    <div class="uk-width-expand lesson-content">
                                        <a href="#">
                                            <div>Not Found Assignment</div>
                                        </a>
                                    </div>
                                </div>
                            </li>
                        @endforelse
                    </ul>
                    @endif


                </div>
            </div>

        </div>
    </div>
</div>
