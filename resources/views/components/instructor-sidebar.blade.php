<div class="btn-hmbrgr mobile-toggle hidden-large">
    <button id="toggle-hmbrgr-mobile" class="reign-toggler small" type="button">
        <span class="icon-bar bar1"></span>
        <span class="icon-bar bar2"></span>
        <span class="icon-bar bar3"></span>
    </button>
</div>
<div id="sidebar-inst-mobile" class="sidebar-container opened hidden">
    <div class="instructor-sidebar uk-margin-remove" uk-grid>
        <div class="uk-width-1-1 uk-padding-remove">
            <div class="btn-hmbrgr">
                <button id="toggle-hmbrgr" class="reign-toggler opened mobile" type="button">
                    <span class="icon-bar bar1"></span>
                    <span class="icon-bar bar2"></span>
                    <span class="icon-bar bar3"></span>
                </button>
            </div>
        </div>
        <div class="uk-width-1-1 uk-padding-remove uk-margin-medium-top">
            <ul class="uk-nav-default uk-nav inst-nav">
                @if(auth()->user()->roles->first()->name =='Instructor')
                <li class="{{ Request::is('u*') ? 'active': ''}} uk-margin-small-bottom">
                        <a class="side-bar-link dash" href="{{ Route('dashboard.instructor') }}" uk-tooltip="title:Dashboard; pos: right">
                            <i class="fa fa-graduation-cap" aria-hidden="true"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                @endif

                @if(user_can_any('students'))

                <li class="uk-margin-small-bottom collapse-header {{ Request::is('student*') || Request::is('student/*') || Request::is('attendance/*') || Request::is('attendance*')? 'active': ''}}">
                    <a>
                        <i class="fa-solid fa-users"></i>
                        <span>Students<span class="icon-no-rotate hide-close" uk-icon="icon: chevron-down; ratio: 1"></span></span>
                    </a>
                    <ul class="sub-side uk-nav-default {{ Request::is('student*') || Request::is('student/*') || Request::is('attendance/*') || Request::is('attendance*')? '': 'none'}}">
                        <li class="uk-margin-small-bottom {{ Request::is('student*') || Request::is('student/*')? 'active': ''}}" disabled="">
                            <a href="{{ route('student.index')}}">
                                <span>My Students</span>
                            </a>
                        </li>
                        @if(user_can_any('attendance'))
                        <li class="uk-margin-small-bottom {{ Request::is('attendance/*') || Request::is('attendance*')? 'active': ''}}" disabled="">
                            <a href="/attendance?attendance_type=room">
                                <span>Room Attendance</span>
                            </a>
                        </li>
                            <li class="uk-margin-small-bottom {{ Request::is('attendance/*') || Request::is('attendance*')? 'active': ''}}" disabled="">
                                <a href="{{route('activity.log.attendance')}}">
                                    <span>Logs</span>
                                </a>
                            </li>
                        @endif

                        {{-- @if(user_can_any('attendance'))
                            <li class="uk-margin-small-bottom {{ Request::is('attendance/*') || Request::is('attendance*')? 'active': ''}}" disabled="">
                                <a href="/attendance?attendance_type=quiz">
                                    <span>Exam Attendance</span>
                                </a>
                            </li>
                        @endif --}}
                    </ul>
                </li>
                @endif

                @if(user_can_any('classroom'))
                <li class="{{ Request::is('myclassrooms*') || Request::is('classrooms/*')? 'active': ''}} uk-margin-small-bottom">
                    <a class="side-bar-link class" href="{{ route('myclassrooms.index')}}" uk-tooltip="title:Classrooms; pos: right">
                        <i class="fa-solid fa-book-open"></i>
                        <span>Classrooms</span>
                    </a>
                </li>
                @endif
                @if(user_can_any('rooms'))
                <li class="uk-margin-small-bottom {{ Request::is('room*') ? 'active': ''}}">
                    <a href="{{ route('room.index') }}" class="side-bar-link room" uk-tooltip="title:Rooms; pos: right">
                        <i class="fa fa-chalkboard" aria-hidden="true"></i>
                        <span>Rooms</span>
                    </a>
                </li>
                @endif
                @if(user_can_any('lessons'))
                <li class="uk-margin-small-bottom {{ Request::is('lesson*') ? 'active' : ''}}">
                    <a href="{{ route('lessons.index') }}" class="side-bar-link lessons" uk-tooltip="title:Lessons; pos: right">
                        <i class="fa fa-clipboard-check" aria-hidden="true"></i>
                        <span>Lessons</span>
                    </a>
                </li>
                @endif

                @if(user_can_any('quizes'))
                <li class="uk-margin-small-bottom collapse-header {{ Request::is('quiz*') || Request::is('quiz/*') || Request::is('question-bank/*') || Request::is('question-bank*') || Request::is('question/*') || Request::is('question*') || Request::is('category/*') || Request::is('category*') || Request::is('subcategory/*') || Request::is('subcategory*')? 'active': ''}}" disabled="">
                    <a>
                        <i class="fa fa-spell-check" aria-hidden="true"></i>
                        <span>Quizzes
                            <span class="icon-no-rotate hide-close" uk-icon="icon: chevron-down; ratio: 1"></span>
                        </span>
                    {{--<label class="soon-text">Soon</label>--}}
                    </a>

                    <ul class="sub-side uk-nav-default {{ Request::is('quiz*') || Request::is('quiz/*') || Request::is('question-bank/*') || Request::is('question-bank*') || Request::is('question/*') || Request::is('question*') || Request::is('category/*') || Request::is('category*') || Request::is('subcategory/*') || Request::is('subcategory*') ? '': 'none'}}">
                        <li class="uk-margin-small-bottom" disabled="">
                            <a href="{{route('quiz.index')}}">
                                <span>All Quizzes</span>
                            </a>
                        </li>
                        <li class="uk-margin-small-bottom" disabled="">
                            <a href="{{route('question-bank.index')}}">
                                <span>Question Banks</span>
                            </a>
                        </li>
                        <li class="uk-margin-small-bottom" disabled="">
                            <a href="{{route('question.index')}}">
                                <span>Questions</span>
                            </a>
                        </li>
                        <li class="uk-margin-small-bottom">
                            <a href="{{route('subcategory.index')}}">
                                <span>Sub Category</span>
                            </a>
                        </li>
                        <li class="uk-margin-small-bottom">
                            <a href="{{route('category.index')}}">
                                <span>Category</span>
                            </a>
                        </li>

                    </ul>
                </li>
                @endif

                {{-- @if(user_can_any('assignment'))
                    <li class="uk-margin-small-bottom">
                        <a href="/quiz?assignment">
                            <i class="fa-solid fa-file-pen"></i>
                            <span>Assignments</span><label class="soon-text">Soon</label>
                        </a>
                    </li>
                @endif --}}

                @if (auth()->user()->roles->first() ? auth()->user()->roles->first()->name =='Instructor' :false)
                <li class="uk-margin-small-bottom">
                    <a href="/announcement">
                        <i class="fa-solid fa-bullhorn"></i>
                        <span>Announcements</span>
                    </a>
                </li>
                @endif

                @if(user_can_any('coupon'))

                    <li class="uk-margin-small-bottom {{ Request::is('coupon*') ? 'active' : ''}}">
                        <a href="{{route('coupon.index')}}" class="side-bar-link code" uk-tooltip="title:Code Generator; pos: right">
                            <i class="fa-solid fa-receipt"></i>
                            <span>Code Generator</span>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->roles->first() ? auth()->user()->roles->first()->name =='Instructor' :false)
                    <li class="uk-margin-small-bottom {{ Request::is('myassistant*') ? 'active' : ''}}">
                        <a href=" {{route('myassistant')}} " class="side-bar-link ass" uk-tooltip="title:My Assistants; pos: right">
                            <i class="fa-solid fa-users-viewfinder"></i>
                            <span>My Assistants</span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>
<div id="sidebar-inst" class="sidebar-container closed hidden-small">
    <div class="instructor-sidebar uk-margin-remove" uk-grid>
        <div class="uk-width-1-1 uk-padding-remove">
            <div class="btn-hmbrgr">
                <button id="toggle-hmbrgr" class="reign-toggler large" type="button">
                    <span class="icon-bar bar1"></span>
                    <span class="icon-bar bar2"></span>
                    <span class="icon-bar bar3"></span>
                </button>
            </div>
        </div>
        <div class="uk-width-1-1 uk-padding-remove uk-margin-medium-top">
            <ul class="uk-nav-default uk-nav inst-nav">
                @if(auth()->user()->roles->first()->name =='Instructor')
                    <li class="{{ Request::is('u*') ? 'active': ''}} uk-margin-small-bottom">
                        <a class="side-bar-link dash" href="{{ Route('dashboard.instructor') }}" uk-tooltip="title:Dashboard; pos: right">
                            <i class="fa fa-graduation-cap" aria-hidden="true"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                @endif
                @if(user_can_any('students'))

                <li class="uk-margin-small-bottom collapse-header {{ Request::is('student*') || Request::is('student/*') || Request::is('attendance/*') || Request::is('attendance*')? 'active': ''}}">
                    <a>
                        <i class="fa-solid fa-users"></i>
                        <span>Students<span class="icon-no-rotate hide-close" uk-icon="icon: chevron-down; ratio: 1"></span></span>
                    </a>
                    <ul class="sub-side uk-nav-default {{ Request::is('student*') || Request::is('student/*') || Request::is('attendance/*') || Request::is('attendance*')? '': 'none'}}">
                        <li class="uk-margin-small-bottom {{ Request::is('student*') || Request::is('student/*')? 'active': ''}}" disabled="">
                            <a href="{{ route('student.index')}}">
                                <span>My Students</span>
                            </a>
                        </li>
                        @if(user_can_any('attendance'))
                        <li class="uk-margin-small-bottom {{ Request::is('attendance/*') || Request::is('attendance*')? 'active': ''}}" disabled="">
                            <a href="/attendance?attendance_type=room">
                                <span>Room Attendance</span>
                            </a>
                        </li>
                            <li class="uk-margin-small-bottom {{ Request::is('attendance/*') || Request::is('attendance*')? 'active': ''}}" disabled="">
                                <a href="{{route('activity.log.attendance')}}">
                                    <span>Logs</span>
                                </a>
                            </li>
                        @endif

                        {{-- @if(user_can_any('attendance'))
                            <li class="uk-margin-small-bottom {{ Request::is('attendance/*') || Request::is('attendance*')? 'active': ''}}" disabled="">
                                <a href="/attendance?attendance_type=quiz">
                                    <span>Exam Attendance</span>
                                </a>
                            </li>
                        @endif --}}
                    </ul>
                </li>
                @endif

                @if(user_can_any('classroom'))
                <li class="{{ Request::is('myclassrooms*') || Request::is('classrooms/*')? 'active': ''}} uk-margin-small-bottom">
                    <a class="side-bar-link class" href="{{ route('myclassrooms.index')}}" uk-tooltip="title:Classrooms; pos: right">
                        <i class="fa-solid fa-book-open"></i>
                        <span>Classrooms</span>
                    </a>
                </li>
                @endif
                @if(user_can_any('rooms'))
                <li class="uk-margin-small-bottom {{ Request::is('room*') ? 'active': ''}}">
                    <a href="{{ route('room.index') }}" class="side-bar-link room" uk-tooltip="title:Rooms; pos: right">
                        <i class="fa fa-chalkboard" aria-hidden="true"></i>
                        <span>Rooms</span>
                    </a>
                </li>
                @endif
                @if(user_can_any('lessons'))
                <li class="uk-margin-small-bottom {{ Request::is('lesson*') ? 'active' : ''}}">
                    <a href="{{ route('lessons.index') }}" class="side-bar-link lessons" uk-tooltip="title:Lessons; pos: right">
                        <i class="fa fa-clipboard-check" aria-hidden="true"></i>
                        <span>Lessons</span>
                    </a>
                </li>
                @endif

                @if(user_can_any('quizes'))
                <li class="uk-margin-small-bottom collapse-header {{ Request::is('quiz*') || Request::is('quiz/*') || Request::is('question-bank/*') || Request::is('question-bank*') || Request::is('question/*') || Request::is('question*') || Request::is('category/*') || Request::is('category*') || Request::is('subcategory/*') || Request::is('subcategory*')? 'active': ''}}" disabled="">
                    <a>
                        <i class="fa fa-spell-check" aria-hidden="true"></i>
                        <span>Quizzes
                            <span class="icon-no-rotate hide-close" uk-icon="icon: chevron-down; ratio: 1"></span>
                        </span>
                    {{--<label class="soon-text">Soon</label>--}}
                    </a>

                    <ul class="sub-side uk-nav-default {{ Request::is('quiz*') || Request::is('quiz/*') || Request::is('question-bank/*') || Request::is('question-bank*') || Request::is('question/*') || Request::is('question*') || Request::is('category/*') || Request::is('category*') || Request::is('subcategory/*') || Request::is('subcategory*') ? '': 'none'}}">
                        <li class="uk-margin-small-bottom" disabled="">
                            <a href="{{route('quiz.index')}}">
                                <span>All Quizzes</span>
                            </a>
                        </li>
                        <li class="uk-margin-small-bottom" disabled="">
                            <a href="{{route('question-bank.index')}}">
                                <span>Question Banks</span>
                            </a>
                        </li>
                        <li class="uk-margin-small-bottom" disabled="">
                            <a href="{{route('question.index')}}">
                                <span>Questions</span>
                            </a>
                        </li>
                        <li class="uk-margin-small-bottom">
                            <a href="{{route('subcategory.index')}}">
                                <span>Sub Category</span>
                            </a>
                        </li>
                        <li class="uk-margin-small-bottom">
                            <a href="{{route('category.index')}}">
                                <span>Category</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                {{-- @if(user_can_any('assignment'))
                    <li class="uk-margin-small-bottom">
                        <a href="/quiz?assignment">
                            <i class="fa-solid fa-file-pen"></i>
                            <span>Assignments</span>
                        </a>
                    </li>
                @endif --}}

                @if (auth()->user()->roles->first() ? auth()->user()->roles->first()->name =='Instructor' :false)
                <li class="uk-margin-small-bottom">
                    <a href="/announcement">
                        <i class="fa-solid fa-bullhorn"></i>
                        <span>Announcements</span>
                    </a>
                </li>
                @endif

                @if(user_can_any('coupon'))

                    <li class="uk-margin-small-bottom {{ Request::is('coupon*') ? 'active' : ''}}">
                        <a href="{{route('coupon.index')}}" class="side-bar-link code" uk-tooltip="title:Code Generator; pos: right">
                            <i class="fa-solid fa-receipt"></i>
                            <span>Code Generator</span>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->roles->first() ? auth()->user()->roles->first()->name =='Instructor' :false)
                    <li class="uk-margin-small-bottom {{ Request::is('myassistant*') ? 'active' : ''}}">
                        <a href=" {{route('myassistant')}} " class="side-bar-link ass" uk-tooltip="title:My Assistants; pos: right">
                            <i class="fa-solid fa-users-viewfinder"></i>
                            <span>My Assistants</span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>
