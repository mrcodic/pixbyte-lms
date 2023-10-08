<section id="rooms" class="section-wrapper rooms uk-margin-medium-bottom" >
    <h2>Rooms</h2>
    <div class="divider uk-margin-bottom light "></div>
    <div id="post">
        @forelse($class->rooms as $key => $room)
            @php
                if($room->usedCoupon->last()){
                    $day=$room->unlock_after;
                    $created_at=($room->usedCoupon?$room->usedCoupon->last()->created_at:null);
                    $endDate = \Carbon\Carbon::parse($created_at)->addDays($day);
                    $date1 = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $endDate);
                    $date2 = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', now());
                    $result = $date1->gt($date2);
                }else{
                    $result=false;
                }
                if(auth()->user()->type!==2){
                // coronJopAttendence($room,auth()->id(),$result,$class);
                coronJopAbsence(auth()->id(),$class);
                }
            @endphp

            <div class="uk-card  {{auth()->user()->type!==2 && (\App\Models\Setting::demoRoom() == $class->id ?false : \App\Models\ClassroomStudent::where('user_id',auth()->id())->where('classroom_id',$class->id)->first()->block)?"block_div":''}} uk-card-default classroom-card up-hover uk-margin-bottom {{ checkMissed($room->id,auth()->id()) ?'hover_missed':'' }}" >
                <div class="uk-padding-small border-radius">
                    <div class="uk-flex {{ auth()->user()->type!==2 && (\App\Models\Setting::demoRoom() == $class->id ?false : \App\Models\ClassroomStudent::where('user_id',auth()->id())->where('classroom_id',$class->id)->first()->block)?'':"cursor-pointer"}}  ">
                        <div class="uk-width-expand collapse-header">
                            <div class="up-room">
                                <img class="icon-activity" src="{{ asset('img/xp-lesson.svg') }}" alt="room-icon">

                                @php
                                    $lockQuiz=false;
                                        if((!$result && !empty($room->price))){
                                            if(! $room->usedCoupon || ( $room->usedCoupon && !$result)){
                                                $lockQuiz=true;
                                            }
                                        }
                                           $resultQuiz=checkTakeQuiz($room->quizzes);
                                           if( $resultQuiz && !$lockQuiz){
                                              if($resultQuiz['type']==3)
                                              $url=route('get_assignment',$resultQuiz['id']);
                                               else
                                                 $url=route('quiz.show',$resultQuiz['id']);
                                           }else{
                                               $url=route('room.show',$room->id);
                                           }
                                @endphp
                                <a data-id="{{$room->id}}" @can('Student') @if(auth()->user()->type!==2 && (\App\Models\Setting::demoRoom() == $class->id ?false :\App\Models\ClassroomStudent::where('user_id',auth()->id())->where('classroom_id',$class->id)->first()->block)==0) href="{{$url}}" @endif @endcan @can('Instructor') href="{{route('room.show',$room->id)}}"  @endcan class="room-link">
                                    <h5 class="uk-width-3-4 room-text-data dark-font uk-margin-remove-bottom uk-margin-small-top inline-block uk-margin-small-left uk-text-bold rm-t-s">Room <small> {{sprintf('%02d', (count(\App\Models\Classroom::where('id',$class->id)->first()->rooms()->whereHas('lessons')->get()) -$key))}} </small><span class="unit-title uk-text-bold"> {{$room->title}}</span>
                                    </h5>
                                </a>
                                @php
                                    $roomCreated= DB::table('class_rooms')->where('classroom_id',$class->id)->where('room_id',$room->id)->first()->created_at;
                                @endphp
                                <p class="date-padding light-dark uk-margin-small-bottom uk-margin-remove-top uk-margin-medium-left"><time datetime="2016-04-01T19:00"> {{\Carbon\Carbon::parse($roomCreated)->format('M d, Y')}}</time></p>
                            </div>
                        </div>
                        <div class="uk-width-auto uk-margin-small-top rm-t-s">
                            @if (checkMissed($room->id,auth()->id()))
                                <img class="lock-time" src="{{ asset('img/missisng-small.png') }}" alt='missing'/>
                            @else
                                <span class="lock-time">
                            {{unlockafterRoomDetail($room->price ,$room->unlock_after,$room->usedCoupon->last()->created_at??'')}}
                            </span>
                            @endif
                            <span class="price">{{$room->price??0}} L.E</span>
                            @if(checkMissed($room->id,auth()->id()))
                                <span class="danger-text" uk-tooltip="Room is missed"  uk-icon="icon: warning" ></span>
                                <span uk-tooltip="Room is locked" @if((!$result && $room->price!=0 ||$room->price!=null)) uk-icon="icon: lock" @endif></span>

                            @elseif((!$room->usedCoupon) || ( $room->usedCoupon && !$result))
                                @if(!$room->price || (!$result && $room->price=="0"))
                                    <span uk-tooltip="Room is unlocked"  uk-icon="icon: unlock" ></span>
                                @else
                                    <span uk-tooltip="Room is locked"  uk-icon="icon: lock" ></span>

                                @endif
                            @else
                                <span uk-tooltip="Room is unLocked"  uk-icon="icon: unlock" ></span>
                            @endif
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="display: none;">
                                <symbol viewBox="0 0 128 128" id="tick"><path d="M112.639844,0 L35.84,88.575 L17.9203125,69.82 L0,69.82 L35.84,128 L128,0 L112.639844,0 Z"></path></symbol>
                            </svg>
                            @php
                                $lesson=App\Models\CompleteLesson::where(['user_id'=>auth()->id(),'room_id'=>$room->id,'classroom_id'=>$class->id])->get();
                            @endphp
                            @if(getProgress(count($lesson),count($room->lessons))==100)
                                <svg uk-tooltip="Completed" class="check-icon rooms-icon viewed">✔<use xlink:href="#tick">
                                </svg>
                            @else
                                <svg uk-tooltip="UnCompleted" class="check-icon rooms-icon">✔<use xlink:href="#tick">
                                </svg>
                            @endif
                        </div>
                        @if(auth()->user()->type!==2 && (\App\Models\Setting::demoRoom() == $class->id ?false : \App\Models\ClassroomStudent::where('user_id',auth()->id())->where('classroom_id',$class->id)->first()->block)==0)
                            <div class="uk-width-auto">
                                <span class="more-dots light uk-border-circle" uk-icon="icon:more-vertical;"></span>
                                <div class="uk-border-rounded" uk-dropdown="mode: click">
                                    <ul class="uk-nav uk-dropdown-nav">
                                        <li><a href="#" id="copyLink" data-link="{{route('room.show',$room->id)}}">Copy</a></li>
                                        @if(($room->price!=="0" && !$result) || $room->price)
                                            @if(!$room->usedCoupon || ($room->usedCoupon && !$result))
                                                <li><a href="#modal-center" data-roomId="{{$room->id}}" data-roomPrice="{{$room->price}}" data-gradeId="{{getGradeId(request()->id)}}" id="unlock_code" uk-toggle>Unlock</a></li>
                                            @endif
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        @elseif(auth()->user()->type==2)
                            <div class="uk-width-auto">
                                <span class="more-dots light uk-border-circle" uk-icon="icon:more-vertical;"></span>
                                <div class="uk-border-rounded" uk-dropdown="mode: click">
                                    <ul class="uk-nav uk-dropdown-nav">
                                        <li><a href="#" id="copyLink" data-link="{{route('room.show',$room->id)}}">Copy</a></li>
                                        @if(( $room->price!=0 ))
                                            @if(! $room->usedCoupon || ( $room->usedCoupon && !$result))
                                                <li><a href="#modal-center" data-roomId="{{$room->id}}" data-roomPrice="{{$room->price}}" data-gradeId="{{getGradeId(request()->id)}}" id="unlock_code" uk-toggle>Unlock</a></li>
                                            @endif
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="uk-flex collapse-content">
                        @foreach($room->quizzes()->where('type',1)->get() as $key=> $quiz)
                            <div class="collapse-video">
                                <img class="collapse-icon" src="{{ asset('img/quiz.svg') }}"  alt="video-icon" uk-svg>
                                <a class="dark-font uk-margin-remove-bottom uk-margin-small-top inline-block uk-margin-small-left uk-text-bold">
                                    Quiz  {{sprintf('%02d',$key+1)}} {{$quiz->title}}.
                                </a>
                            </div>
                        @endforeach
                        @foreach($room->lessons as $key=> $lesson)

                            <div class="collapse-video">
                                <img class="collapse-icon" src="{{ asset('img/video-camera.svg') }}" alt="video-icon" uk-svg>
                                <a class="dark-font uk-margin-remove-bottom uk-margin-small-top inline-block uk-margin-small-left uk-text-bold">
                                    Part {{sprintf('%02d',$key+1)}} {{$lesson->title}}.
                                </a>
                            </div>
                        @endforeach
                        @foreach($room->quizzes()->where('type','3')->get() as $key=> $quiz)
                        <div class="collapse-video">
                            <img class="collapse-icon"  src="{{ asset('img/assignment.svg') }}" alt="video-icon" uk-svg>
                            <a class="dark-font uk-margin-remove-bottom uk-margin-small-top inline-block uk-margin-small-left uk-text-bold">
                               Assignment  {{sprintf('%02d',$key+1)}} {{$quiz->title}}.
                            </a>
                        </div>
                    @endforeach
                    </div>
                </div>
                <x-code-modal />
            </div>
        @empty
            <div class="uk-card uk-card-default classroom-card up-hover uk-margin-bottom">
                <div class="uk-padding-small border-radius">
                    <div class="uk-flex collapse-header cursor-pointer">
                        <div class="uk-width-expand">
                            <div class="up-room">
                                <img class="icon-activity" src="{{ asset('img/xp-lesson.svg') }}" alt="room-icon">
                                <h5 class="dark-font uk-margin-remove-bottom uk-margin-small-top inline-block uk-margin-small-left uk-text-bold"> <a href="#" class=""><span class="unit-title uk-text-bold"> No Found Rooms</span></a>
                                </h5>

                            </div>
                        </div>

                    </div>
                </div>
            </div>

        @endforelse
    </div>

    <div>
        <div class="spinner loading dark-font" style="display:none;">
            <div class="circle one"></div>
            <div class="circle two"></div>
            <div class="circle three"></div>
        </div>
        @if($roomsCount>5)
            <div class="uk-text-center uk-margin-top">
                <button class="edit uk-margin-small-right" data-type="room" id="loadmore">More rooms</button>
            </div>
        @endif
    </div>

 @include('classroom.codeClassroom-modal')
</section>

