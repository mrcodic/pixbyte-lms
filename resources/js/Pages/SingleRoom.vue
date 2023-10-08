<template>
    <Head>
        <title>Mives - {{rooms.data.title}}</title>
        <meta name="description" content="Your page description">
    </Head>

    <div class="lesson-room f-height uk-padding-remove" uk-grid>
        <div class="aside uk-width-1-5@xl uk-width-1-4@m uk-width-1-1@s"  ref="aside">
            <div class="side-wrapper uk-width-1-1">
                <div class="header-side" uk-grid>
                    <div class="uk-width-2-3 uk-flex uk-flex-middle">
                        <div class="class-overview">
                            <a class="light-link" :href="classroomroute"><i class="fa-solid fa-chevron-left"></i> class overview</a>
                        </div>
                    </div>
                    <div class="uk-width-1-3 profile hidden-small">
                        <a :href="'/u/'+rooms.data.name_id">
                            <img class="showImage" :src="rooms.data.profile_image" alt="avatar">
                        </a>
                    </div>
                    <div class="uk-width-1-3 close-lessons hidden-large" @click="closeToggle">
                        <span uk-icon="icon: close; ratio:1.7"></span>
                    </div>
                </div>
                <div class="body-side uk-margin-top">
                    <div class="room-content uk-flex uk-flex-middle" uk-grid>
                        <div class="room-icon uk-width-1-3"><img class="" src="/img/xp-lesson.svg" alt="room-icon"></div>
                        <div class="room-title uk-width-expand">
                            <div class="title">{{ rooms.data.title }}</div>
                            <div class="components">
                                <span class="lessons-number uk-margin-small-right"><i class="fa-solid fa-book"></i> <span>{{ rooms.data.lessons_num }}</span> lessons</span>
                                <span class="room-duration"><i class="fa-solid fa-clock"></i> {{ rooms.data.duration }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="divider uk-margin-bottom uk-margin-small-top"></div>
                </div>
                <div class="body-conent">


                    <div class="lessons-header" v-if="quizzes.length>0">
                        <span>Quizes</span>
                        <span>Examine yourself</span>
                    </div>
                    <ul class="lessons-list" v-if="quizzes.length>0">
                        <li v-if="quizzes.length>0" v-for="(quiz,index) in quizzes">
                            <div v-if="!request.unlock || request.quiz " class="uk-flex uk-flex-middle lesson-body uk-margin-remove-left uk-grid uk-margin-small-top " :class="quiz.id==this.selectedRoom.id?'active':''" uk-grid>
                                <div v-if="(quiz.result)" class="uk-width-1-5">
                                    <div class="check-completed">
                                        <i class="fa-solid fa-circle-check"></i>
                                    </div>
                                </div>
                                <div class="uk-width-1-5" v-else >
                                    <div class="lesson-number"><span>0{{index+1}}</span></div>
                                </div>
                                <div class="uk-width-expand lesson-content">
                                    <a :href="'/quiz/'+quiz.id">
                                        <div>{{ quiz.title }}</div>
<!--                                        <div>-->
<!--                                            <span class="room-duration"><i class="fa-solid fa-clock"></i> {{quizzesCount}}</span>-->
<!--                                        </div>-->
                                    </a>
                                </div>
                            </div>
                            <div  v-else class="uk-flex uk-flex-middle lesson-body uk-margin-remove-left uk-grid uk-margin-small-top " :class="quiz.id==this.selectedRoom.id?'active':''" uk-grid>
                                <div  class="uk-width-1-5">
                                    <div class="lock-room-icon">
                                        <i class="fa-solid fa-lock"></i>
                                    </div>
                                </div>
                                <div class="uk-width-expand lesson-content">
                                    <a :href="request.quiz?'/quiz/'+quiz.id:''">
                                        <div>{{ quiz.title }}</div>
                                        <!--                                        <div>-->
                                        <!--                                            <span class="room-duration"><i class="fa-solid fa-clock"></i> {{quizzesCount}}</span>-->
                                        <!--                                        </div>-->
                                    </a>
                                </div>
                            </div>

                        </li>
                        <li v-else>
                            <div class="uk-flex uk-flex-middle lesson-body uk-margin-remove-left uk-grid uk-margin-small-top uk-grid-stack"  uk-grid>

                                <div class="uk-width-expand lesson-content">
                                    <a href="#">
                                        <div>Not Found Quiz</div>
                                    </a>
                                </div>
                            </div>
                        </li>
                    </ul>

                    <div class="lessons-header uk-margin-medium-top">
                        <span>Lessons</span>
                        <span>Getting started</span>
                    </div>
                    <ul class="lessons-list">
                        <li v-if="rooms.data.lessons.length > 0" v-for="lesson in rooms.data.lessons">
                            <div v-if="!request.unlock" class="uk-flex uk-flex-middle lesson-body uk-margin-remove-left uk-grid uk-margin-small-top" :class="lesson.id==this.selectedRoom.id?'active':''" @click="renderVideo(lesson.id)" uk-grid>
                                <div v-if="(lesson.completed)" class="uk-width-1-5">
                                    <div class="check-completed">
                                        <i class="fa-solid fa-circle-check"></i>
                                    </div>
                                </div>
                                <div class="uk-width-1-5" v-else >
                                    <div class="lesson-number"><span>{{lesson.lesson_order}}</span></div>
                                </div>

                                <div class="uk-width-expand lesson-content">
                                    <a href="#">
                                        <div>{{lesson.title}}</div>
                                        <div>
                                            <span class="room-duration"><i class="fa-solid fa-clock"></i> {{lesson.duration }}</span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div  @click="renderVideo(lesson.id)" v-else class="uk-flex uk-flex-middle lesson-body uk-margin-remove-left uk-grid uk-margin-small-top"  uk-grid>
                                <div  class="uk-width-1-5">
                                    <div class="lock-room-icon">
                                        <i class="fa-solid fa-lock"></i>
                                    </div>
                                </div>


                                <div class="uk-width-expand lesson-content">
                                    <a>
                                        <div>{{lesson.title}}</div>
                                        <div>
                                            <span class="room-duration"><i class="fa-solid fa-clock"></i> {{lesson.duration }}</span>
                                        </div>
                                    </a>
                                </div>
                            </div>


                        </li>
                        <li v-else>
                            <div class="uk-flex uk-flex-middle lesson-body uk-margin-remove-left uk-grid uk-margin-small-top uk-grid-stack"  uk-grid>

                                <div class="uk-width-expand lesson-content">
                                    <a href="#">
                                        <div>Not Found Lesson</div>
                                    </a>
                                </div>
                            </div>
                        </li>

                    </ul>

                    <div class="lessons-header" v-if="assignments.length>0">
                        <span>Assignment</span>
                        <span>Examine yourself</span>
                    </div>
                    <ul class="lessons-list" v-if="assignments.length>0">
                        <li v-if="assignments.length>0" v-for="(assignment,index) in assignments">
                            <div v-if="!request.unlock || request.assignments "
                                 class="uk-flex uk-flex-middle lesson-body uk-margin-remove-left uk-grid uk-margin-small-top " :class="assignment.id==this.selectedRoom.id?'active':''" uk-grid>
                                <div v-if="(assignment.result)" class="uk-width-1-5">
                                    <div class="check-completed">
                                        <i class="fa-solid fa-circle-check"></i>
                                    </div>
                                </div>
                                <div class="uk-width-1-5" v-else >
                                    <div class="lesson-number"><span>0{{index+1}}</span></div>
                                </div>
                                <div class="uk-width-expand lesson-content">
                                    <a :href="'/assignment/'+assignment.id">
                                        <div>{{ assignment.title }}</div>
                                        <!--                                        <div>-->
                                        <!--                                            <span class="room-duration"><i class="fa-solid fa-clock"></i> {{quizzesCount}}</span>-->
                                        <!--                                        </div>-->
                                    </a>
                                </div>
                            </div>
                            <div  v-else class="uk-flex uk-flex-middle lesson-body uk-margin-remove-left uk-grid uk-margin-small-top " :class="assignment.id==this.selectedRoom.id?'active':''" uk-grid>
                                <div  class="uk-width-1-5">
                                    <div class="lock-room-icon">
                                        <i class="fa-solid fa-lock"></i>
                                    </div>
                                </div>
                                <div class="uk-width-expand lesson-content">
                                    <a :href="request.assignment?'/assignment/'+assignment.id:''">
                                        <div>{{ assignment.title }}</div>
                                        <!--                                        <div>-->
                                        <!--                                            <span class="room-duration"><i class="fa-solid fa-clock"></i> {{quizzesCount}}</span>-->
                                        <!--                                        </div>-->
                                    </a>
                                </div>
                            </div>

                        </li>
                        <li v-else>
                            <div class="uk-flex uk-flex-middle lesson-body uk-margin-remove-left uk-grid uk-margin-small-top uk-grid-stack"  uk-grid>

                                <div class="uk-width-expand lesson-content">
                                    <a href="#">
                                        <div>Not Found Assignment</div>
                                    </a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="main uk-width-expand uk-margin-large-bottom">
            <section v-if="!request.unlock">
                <div class="video-holder" v-if="this.selectedRoom.url_iframe">
                    <iframe :src="this.selectedRoom.url_iframe" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen style="position:absolute;top:0;left:0;width:100%;height:100%;" title="Final Revision Ch4 -Part2-Last Video"></iframe>
                </div>
                <div class="video-holder" v-else>
                    <video width="320" height="240" controls>
                        <source :src="this.selectedRoom.video" type="video/mp4">
                        <source :src="this.selectedRoom.video" type="video/ogg">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </section>
            <div class="lock-bg" v-else-if="request.block">
                <div class="uk-container uk-container">
                    <div class="uk-margin-xlarge-top uk-margin-xlarge-bottom" uk-grid>
                        <div class="uk-width-2-3@m uk-width-1-1@s">
                            <h3>This Room Is Locked</h3>
                            <p class="light-color" style="font-size: 18px;">You can't join any room because your account has been suspended</p>

                        </div>
                        <div class="uk-width-expand">
                            <img class="nothing-toshow-img" src="/img/gang.svg" alt="locked-room">
                        </div>
                    </div>
                </div>
            </div>
            <div class="lock-bg" v-else>
                <div class="uk-container uk-container">
                    <div class="uk-margin-xlarge-top uk-margin-xlarge-bottom" v-if="request.quiz" uk-grid>
                        <div class="uk-width-2-3@m uk-width-1-1@s">
                            <h3>This Lesson Is Locked</h3>
                            <p class="light-color" style="font-size: 18px;">You have to pass the quiz to open any lesson.</p>
                        </div>
                        <div class="uk-width-expand">
                            <img class="nothing-toshow-img" src="/img/quiz_bg.png" alt="locked-room">
                        </div>
                    </div>
                    <div class="uk-margin-xlarge-top uk-margin-xlarge-bottom" v-else uk-grid>

                        <div class="uk-width-2-3@m uk-width-1-1@s" v-if="this.request.type=='subscription'">

                            <h3>This Room Is Locked</h3>
                            <p class="light-color">Please Subscription of Classroom to continue</p>
                        </div>
                        <div class="uk-width-2-3@m uk-width-1-1@s" v-else>
                            <h3>This Room Is Locked</h3>
                            <p class="light-color">Please enter your code down there to open the room.</p>
                            <form action="" method="POST" enctype="multipart/form-data">
                                <div class="uk-flex uk-flex-middle" uk-grid>
                                    <div class="uk-width-2-3">
                                        <input class="uk-input locked-coupon" name="code" type="text" placeholder="Enter room code" v-model="code" autofocus>
                                    </div>
                                    <div class="uk-width-1-3 pl-s-10">
                                        <button class="uk-button uk-button-secondary" @click.prevent="openRoom">Open room</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="uk-width-expand">
                            <img class="nothing-toshow-img" src="/img/gang.svg" alt="locked-room">
                        </div>
                    </div>

                </div>
            </div>

            <div class="uk-container uk-margin-large-top mt-s-20 ml-l-30">
                <div class="toggle-btn uk-margin-bottom toggle-lessons hidden-large" @click="toggleButton">
                    <img src="/img/toggle-episodes-icon.svg" alt="toggle-icon" uk-svg/>
                    <div>
                        Toggle Lessons List
                    </div>
                </div>
                <div class="lesson-items uk-flex uk-flex-middle" uk-grid>
                    <div class="uk-width-auto uk-text-left pr-40 nav-arrow hidden-small">
                        <a href="#" v-if="!request.unlock" @click="RoomLeft(this.selectedRoom.id)">
                            <i class="fa-solid fa-chevron-left fa-1x"></i>
                        </a>
                        <a href="#" v-else style="cursor: not-allowed;"  disabled>
                            <i class="fa-solid fa-chevron-left fa-1x"></i>
                        </a>
                    </div>
                    <div class="uk-width-expand uk-flex uk-flex-middle" uk-grid>
                        <div class="uk-width-expand uk-width-1-1@s">
                            <div class="lesson-title">
                                {{ this.selectedRoom.title }}
                            </div>
                            <div class="lesson-content-box">

                                <span>
                                    <div>Run Time</div>
                                    <div>{{ this.selectedRoom.duration }}</div>
                                </span>
                                <span>
                                    <div>Room Progress</div>
                                    <div>{{ this.rooms.data.progress }}% </div>
                                </span>
                                <span class="closes-room-time">
                                    <div>Room Closes after</div>
                                    <vue-countdown v-if="!request.unlock" :time="rooms.data.unlock_after" v-slot="{ days, hours, minutes, seconds }">
                                        ({{ days }}d) {{ hours }}h {{ minutes }}m {{ seconds }}s.
                                    </vue-countdown>
                                    <div v-else > (0)</div>
                                </span>
                            </div>
                        </div>
                        <div class="uk-width-auto@l uk-width-1-1@s">
                            <div>
                                <div v-if="!request.unlock" >
                                    <button v-if="!selectedRoom.completed"  class="room uk-button uk-button-secondary light" @click="makeCompleted(this.selectedRoom.id)"><i class="fa-solid fa-check"></i> Complete</button>
                                    <button style="cursor: not-allowed;"  v-else class="room uk-button uk-button-secondary light completed" ><i class="fa-solid fa-check"></i> Completed</button>
                                </div>
                                <div v-else>
                                    <button  class="room uk-button uk-button-secondary light " style="cursor: not-allowed;" ><i class="fa-solid fa-check"></i> Complete</button>

                                </div>

                            </div>


                            <div v-if="this.selectedRoom.attachment">
                                <a v-if="!request.unlock" @click="downloadMaterial(this.selectedRoom.id)" download=""  class="room uk-button uk-button-secondary light uk-margin-top" ><i class="fa-solid fa-download" ></i> Room Material</a>
                                <a v-else disabled="" style="cursor: not-allowed;"  download=""  class="room uk-button uk-button-secondary light uk-margin-top" ><i class="fa-solid fa-download" ></i> Room Material</a>
                            </div>
                        </div>
                    </div>
                    <div class="uk-width-auto uk-text-right pr-40 nav-arrow hidden-small">
                        <a href="#" v-if="!request.unlock" @click="roomNext(this.selectedRoom.id)" >
                            <i class="fa-solid fa-chevron-right fa-1x"></i>
                        </a>
                        <a href="#" v-else disabled="" style="cursor: not-allowed;"  >
                            <i class="fa-solid fa-chevron-right fa-1x"></i>
                        </a>
                    </div>
                </div>
                <div class="uk-flex uk-flex-around uk-margin-top hidden-large" uk-grid>
                    <div class="uk-width-1-3 uk-text-left nav-arrow pr-40 inline-block">
                        <a href="#" v-if="!request.unlock" @click="RoomLeft(this.selectedRoom.id)">
                            <i class="fa-solid fa-chevron-left fa-1x"></i>
                        </a>
                        <a href="#" v-else style="cursor: not-allowed;"  disabled="">
                            <i class="fa-solid fa-chevron-left fa-1x"></i>
                        </a>
                    </div>
                    <div class="uk-width-1-3 uk-text-right nav-arrow inline-block">
                        <a href="#" v-if="!request.unlock" @click="roomNext(this.selectedRoom.id)" >
                            <i class="fa-solid fa-chevron-right fa-1x"></i>
                        </a>
                        <a href="#" v-else disabled="" style="cursor: not-allowed;"  >
                            <i class="fa-solid fa-chevron-right fa-1x"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="uk-container uk-container-small uk-margin-medium-top">
                <div class="teacher-card room-card">
                    <div class="teacher-info uk-flex uk-flex-middle" uk-grid>
                        <div class="uk-width-expand uk-text-left">
                            <span>Your Teacher</span><span> {{ rooms.data.user_name }}</span>
                        </div>
                        <!-- <div class="uk-width-auto uk-flex uk-flex-middle">
                            <i class="fa-brands fa-facebook"></i>
                        </div> -->
                    </div>
                    <div class="teacher uk-flex uk-flex-middle uk-margin-top">
                        <div class="uk-width-1-4 uk-width-1-6@m uk-text-left">
                            <img :src="rooms.data.instructor_image" alt="teacher">
                        </div>
                        <div class="uk-width-expand uk-flex uk-flex-middle bio">
                            <p>{{rooms.data.bio}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="loader" class="spinner loading dark-font" style=" width: 100px;height: 64px;position: absolute;top:0;bottom: 0;left: 0;right: 0;margin: auto;"  >
                <div class="circle one"></div>
                <div class="circle two"></div>
                <div class="circle three"></div>
            </div>
            <!--<div class="uk-container uk-container-small uk-margin-medium-top reply">
                <h4 class="uk-text-center uk-text-bold">Discuss This Lesson</h4>
                <div class="write-reply uk-flex uk-flex-middle room-card">
                    <div class="uk-width-auto avatar">
                        <img src="{{ asset('img/chris.jpg') }}" alt="avatar">
                    </div>
                    <div class="uk-width-expand">
                        Write a reply
                    </div>
                </div>
            </div>
            <div class="uk-container uk-container-small uk-margin-small-top reply">
                <div class=replies-with-responses>
                    <div class="replies room-card uk-margin-small-top">
                        <div class=""uk-grid>
                            <div class="uk-width-auto">
                                <div class="avatar">
                                    <img src="{{ asset('img/chris.jpg') }}" alt="avatar">
                                    <div>
                                        Level 6
                                    </div>
                                </div>
                            </div>

                            <div class="uk-width-expand uk-padding-remove">
                                <div class="user-name">
                                    <div class="uk-width-expand">
                                        Mohame Assem
                                    </div>
                                    <div class="posted-date">
                                        Posted 3 months ago
                                    </div>
                                    <div class="reply-body uk-margin-top">
                                        First-person to comment in this series. Love your work.
                                        And these are my suggestions:
                                        Please use composition API along with typescript support.
                                        Please try to build it as a separate frontend app
                                        Include proper auth workflow along with Laravel sanctum
                                        How to build basic apis and connect with vue3
                                        Thank you for your effort.
                                    </div>
                                    <div>
                                        <button class="reply uk-button uk-button-secondary light uk-margin-top">Reply</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="responses">
                        <div class="replies room-card uk-margin-small-top">
                            <div class=""uk-grid>
                                <div class="uk-width-auto">
                                    <div class="avatar">
                                        <img src="{{ asset('img/chris.jpg') }}" alt="avatar">
                                        <div>
                                            Level 6
                                        </div>
                                    </div>
                                </div>

                                <div class="uk-width-expand uk-padding-remove">
                                    <div class="user-name">
                                        <div class="uk-width-expand">
                                            Mohame Assem
                                        </div>
                                        <div class="posted-date">
                                            Posted 3 months ago
                                        </div>
                                        <div class="reply-body uk-margin-top">
                                            First-person to comment in this series. Love your work.
                                            And these are my suggestions:
                                            Please use composition API along with typescript support.
                                            Please try to build it as a separate frontend app
                                            Include proper auth workflow along with Laravel sanctum
                                            How to build basic apis and connect with vue3
                                            Thank you for your effort.
                                        </div>
                                        <div>
                                            <button class="reply uk-button uk-button-secondary light uk-margin-top">Reply</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="replies room-card uk-margin-small-top">
                            <div class=""uk-grid>
                                <div class="uk-width-auto">
                                    <div class="avatar">
                                        <img src="{{ asset('img/chris.jpg') }}" alt="avatar">
                                        <div>
                                            Level 6
                                        </div>
                                    </div>
                                </div>

                                <div class="uk-width-expand uk-padding-remove">
                                    <div class="user-name">
                                        <div class="uk-width-expand">
                                            Mohame Assem
                                        </div>
                                        <div class="posted-date">
                                            Posted 3 months ago
                                        </div>
                                        <div class="reply-body uk-margin-top">
                                            First-person to comment in this series. Love your work.
                                            And these are my suggestions:
                                            Please use composition API along with typescript support.
                                            Please try to build it as a separate frontend app
                                            Include proper auth workflow along with Laravel sanctum
                                            How to build basic apis and connect with vue3
                                            Thank you for your effort.
                                        </div>
                                        <div>
                                            <button class="reply uk-button uk-button-secondary light uk-margin-top">Reply</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="replies room-card uk-margin-small-top">
                            <div class=""uk-grid>
                                <div class="uk-width-auto">
                                    <div class="avatar">
                                        <img src="{{ asset('img/chris.jpg') }}" alt="avatar">
                                        <div>
                                            Level 6
                                        </div>
                                    </div>
                                </div>

                                <div class="uk-width-expand uk-padding-remove">
                                    <div class="user-name">
                                        <div class="uk-width-expand">
                                            Mohame Assem
                                        </div>
                                        <div class="posted-date">
                                            Posted 3 months ago
                                        </div>
                                        <div class="reply-body uk-margin-top">
                                            First-person to comment in this series. Love your work.
                                            And these are my suggestions:
                                            Please use composition API along with typescript support.
                                            Please try to build it as a separate frontend app
                                            Include proper auth workflow along with Laravel sanctum
                                            How to build basic apis and connect with vue3
                                            Thank you for your effort.
                                        </div>
                                        <div>
                                            <button class="reply uk-button uk-button-secondary light uk-margin-top">Reply</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class=replies-with-responses>
                    <div class="replies room-card uk-margin-small-top">
                        <div class=""uk-grid>
                            <div class="uk-width-auto">
                                <div class="avatar">
                                    <img src="{{ asset('img/chris.jpg') }}" alt="avatar">
                                    <div>
                                        Level 6
                                    </div>
                                </div>
                            </div>

                            <div class="uk-width-expand uk-padding-remove">
                                <div class="user-name">
                                    <div class="uk-width-expand">
                                        Mohame Assem
                                    </div>
                                    <div class="posted-date">
                                        Posted 3 months ago
                                    </div>
                                    <div class="reply-body uk-margin-top">
                                        First-person to comment in this series. Love your work.
                                        And these are my suggestions:
                                        Please use composition API along with typescript support.
                                        Please try to build it as a separate frontend app
                                        Include proper auth workflow along with Laravel sanctum
                                        How to build basic apis and connect with vue3
                                        Thank you for your effort.
                                    </div>
                                    <div>
                                        <button class="reply uk-button uk-button-secondary light uk-margin-top">Reply</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div> -->

        </div>
    </div>
</template>

<script>
import { Head } from '@inertiajs/inertia-vue3'
import VueCountdown from '@chenfengyuan/vue-countdown';


export default {
    name: "SingleRoom.vue",
    props: {
        rooms: Array,
        request: Array,
        quizzes:Array,
        assignments:Array,
    },
    components:{
        VueCountdown
    },
    data(){
        return {

            selectedRoom:this.rooms.data.lessons[0]??[],
            classroomroute:'',
            classroom:'',
            code:'',
            loader:false,

        };
    },
    mounted() {

        this.classroomroute=`/classroom/${this.rooms.data.classroom}/classwork`
        this.classroom=this.rooms.data.classroom
        const queryString = window.location.search;
        if(queryString){
            const urlParams = new URLSearchParams(queryString);
            const page_type = urlParams.get('lesson')
            let instance=this;
            this.rooms.data.lessons.find(function(a) {
                if(a.title == page_type){
                    instance.selectedRoom=a;
                }
            });
        }

    },

    methods:{
        toggleButton(){
            this.$refs['aside'].classList.add('open')
        },
        closeToggle(){
            this.$refs['aside'].classList.remove('open')
        },
        openRoom(){
            this.loader=true
            axios
                .post(`/unlock-code?room_id=${this.rooms.data.id}&classroom_id=${this.classroom}&grade_id=${this.request.grade}&code=${this.code}&type=${this.request.type}`)
                .then(({ data }) => {
                    this.loader=true
                    if(data.status){
                        this.request.unlock=false
                        this.$swal('success',`${data.message}`,'sucess');

                    }else{
                        this.$swal(`warning`,`${data.message}`,'warning');
                    }
                    let instance =this
                    setTimeout(function () { instance.loader = false } , 1050)

                })
                .catch((err) => {
                    if(err.response.status == 422 || err.response.status == 400 ) {
                        let instance=this;
                        setTimeout(function () { instance.loader = false } , 1050)
                        this.$swal(`warning`,`${err.response.data.message??'Please Enter Code'}`,'warning');
                    }
                });

        },
        makeCompleted(id){
            this.loader=true
            this.selectedRoom.completed=true;
            axios
                .get(`/make-complete/${this.rooms.data.id}/${id}?classroom=${this.classroom}`)
                .then(({ data }) => {
                    if(data.status){
                        this.rooms.data.lessons.find(function(a) {
                            if(a.id == id){
                                a.completed=true;
                            }
                        });
                        this.selectedRoom.completed=true;
                        this.rooms.data.progress=data.progress;
                        let instance =this
                        setTimeout(function () { instance.loader = false } , 1050)
                        this.$swal('Complete Sucess ','sucess');
                        localStorage.setItem("lessons", JSON.stringify(this.rooms.data.lessons));

                    }
                })
                .catch((data) => {
                    setTimeout(function () { instance.loader = false } , 1050)
                    this.selectedRoom.completed=false;
                });

        },
        renderVideo(id){
            let instance=this
            let lessons = JSON.parse(localStorage.getItem("lessons"))

            this.rooms.data.lessons.find(function(a) {
                if(a.id == id){
                    instance.selectedRoom=a;
                }
            });

            lessons ? lessons.find(function(l) {
                if(l.id == id){ l.completed ? instance.selectedRoom.completed=true :null }
            }) :null;

            this.$inertia.get(`/room/${this.rooms.data.id}`,{lesson:instance.selectedRoom.title},{
                preserveState:true
            })
            this.loader=true
            setTimeout(function () { instance.loader = false } , 1050)

        },
        roomNext(id){
            let instance=this
            this.rooms.data.lessons.find(function(a,key) {
            if(a.id==id){
                instance.selectedRoom=instance.rooms.data.lessons[key+1]
            }
            });
            this.$inertia.get(`/room/${this.rooms.data.id}`,{lesson:instance.selectedRoom.title},{
                preserveState:true
            })
            instance.loader=true
            setTimeout(function () { instance.loader = false } , 1050)
        },
        downloadMaterial(roomID){
            this.loader=true
            axios
                .get(`/getMatrial/${this.rooms.data.id}`,{
                    responseType: 'blob'
                })
                .then(({ data }) => {
                    this.loader=false
                    const blob = new Blob([data], { type: "application/zip" });
                    const link = document.createElement("a");
                    link.href = URL.createObjectURL(blob);
                    link.download = 'matrial.zip';
                    link.click();
                })
                .catch((data) => {

                });
        },
        RoomLeft(id){
            let instance=this
            this.rooms.data.lessons.find(function(a,key) {
                if(a.id==id){
                    instance.selectedRoom=instance.rooms.data.lessons[key-1]
                }
            });

            this.$inertia.get(`/room/${this.rooms.data.id}`,{lesson:instance.selectedRoom.title},{
                preserveState:true
            })
            this.loader=true
            setTimeout(function () { instance.loader = false } , 1050)


        }
    }
}
</script>

<style scoped>
[v-cloak] {
    display: none;
}

</style>
