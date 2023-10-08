<!-- This is the modal -->
<div id="modal-activity" class="modal fade" >
    <div class="modal-dialog modal-lg modal-dialog-centered" style="width: 90%; padding: 10px 30px;">
        <div class="modal-content">

            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="spinner loading dark-font"  >
            <div class="circle one"></div>
            <div class="circle two"></div>
            <div class="circle three"></div>
        </div>
{{--style="display: none"--}}
            <div class="modal-body px-5 pb-5">

             <div  class="test"  >
                 <div class="text-center mb-4">
                     <h1 class="role-title">Student's Activity</h1>
                 </div>0
            <div  id="classroom" >
                <div style="display: grid">
                    <label class="uk-form-label" for="description"><span>*</span> Rooms </label>
                </div>
                <div>
                    <table style="width:100%;background:#fff;" class="table" cellspacing="0" id="room_student_table">
                        @include('admin.componant.search-datatable',['id'=>'filter_rooms'])
                        <thead>
                        <tr>
                            <th class="">Order</th>
                            <th>Name</th>
                            <th>Join</th>
                            <th>End date</th>
                            <th>Coupon Used</th>
                            <th>completed</th>
                            <th>Present</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>


                </div>
            </div>

            <div class="uk-margin-small-top uk-width-1-1" id="classroom" >
                <div style="display: grid">

                    <label class="uk-form-label" for="description"><span>*</span> Exams & Quizzes </label>
                </div>
                <div>

                    <table style="width:100%;background:#fff;"  class="table quiz_student_table" cellspacing="0" id="quiz_student_table">
                        @include('admin.componant.search-datatable',['id'=>'filter_rooms'])
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Passed</th>
                            <th>Retake Num</th>
                            <th>Total Score</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>


                </div>
            </div>

        </div>
            </div>

        </div>
    </div>
</div>


