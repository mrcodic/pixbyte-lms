<!-- This is the modal -->
<div id="modal-activity" class="negative" uk-modal>
    <div class="uk-modal-dialog uk-modal-body" style="width: 90%; padding: 10px 30px;">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <div class="spinner loading dark-font"  >
            <div class="circle one"></div>
            <div class="circle two"></div>
            <div class="circle three"></div>
        </div>
{{--style="display: none"--}}
        <div uk-grid class="test"  >
            <h2 class="uk-modal-title">Student's Activity</h2>
            <div class="uk-margin-small-top uk-width-1-1" id="classroom" >
                <div style="display: grid">

                    <label class="uk-form-label" for="description"><span>*</span> Rooms </label>
                </div>
                <div>

                    <table style="width:100%;background:#fff;" class="uk-table uk-table-hover uk-table-middle uk-table-divider table-bordered uk-margin-remove" cellspacing="0" id="room_student_table">
                        @include('components.search-datatable',['id'=>'filter_rooms'])
                        <thead>
                        <tr>
                            <th class="uk-table-expand">Order</th>
                            <th class="uk-table-expand">Name</th>
                            <th class="uk-table-expand">Join</th>
                            <th class="uk-table-expand">End date</th>
                            <th class="uk-table-expand">Coupon Used</th>
                            <th class="uk-table-expand">completed</th>
                            <th class="uk-table-expand">Status</th>
                            <th class="uk-table-shrink uk-text-nowrap">Action</th>
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

                    <table style="width:100%;background:#fff;" class="uk-table uk-table-hover uk-table-middle uk-table-divider table-bordered uk-margin-remove quiz_student_table" cellspacing="0" id="quiz_student_table">
                        @include('components.search-datatable',['id'=>'filter_exam'])
                        <thead>
                        <tr>
                            <th class="uk-table-expand">Name</th>
                            <th class="uk-table-expand">Type</th>
                            <th class="uk-table-expand">Passed</th>
                            <th class="uk-table-expand">Retake Num</th>
                            <th class="uk-table-expand">Total Score</th>
                            <th class="uk-table-expand">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>


                </div>
            </div>

        </div>

{{--        <p class="uk-text-right">--}}
{{--            <button class="uk-button uk-button-default uk-modal-close border-radius" type="button">Cancel</button>--}}
{{--            <button class="uk-button uk-button-primary border-radius" id="save_requset" type="button">Save</button>--}}
{{--        </p>--}}
    </div>
</div>


