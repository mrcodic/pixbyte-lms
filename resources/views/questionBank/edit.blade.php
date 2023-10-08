@extends('layouts.app')
@section('title', 'Edit Question Bank')
@section('css')
    <style>
        .success {
            background: #0de37a;
        }

        .question img {
            width: 100px;
            height: 100px;
        }

        #sortable {
            cursor: move;
        }

        .uk-switch {
            position: relative;
            display: inline-block;
            height: 34px;
            width: 60px;
        }

        /* Hide default HTML checkbox */
        .uk-switch input {
            display: none;
        }

        /* Slider */
        .uk-switch-slider {
            background-color: rgba(0, 0, 0, 0.22);
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            border-radius: 500px;
            bottom: 0;
            cursor: pointer;
            transition-property: background-color;
            transition-duration: .2s;
            box-shadow: inset 0 0 2px rgba(0, 0, 0, 0.07);
        }

        /* Switch pointer */
        .uk-switch-slider:before {
            content: '';
            background-color: #fff;
            position: absolute;
            width: 30px;
            height: 30px;
            left: 2px;
            bottom: 2px;
            border-radius: 50%;
            transition-property: transform, box-shadow;
            transition-duration: .2s;
        }

        /* Slider active color */
        input:checked + .uk-switch-slider {
            background-color: #39f !important;
        }

        /* Pointer active animation */
        input:checked + .uk-switch-slider:before {
            transform: translateX(26px);
        }

        .switcher {
            display: flex;
            justify-content: space-between;
            margin-bottom: 18px;
            align-items: baseline;
        }
    </style>

@endsection
@section('body')
    <!-- container -->
    <div class="wrapper-page-light f-height">
        {{-- Instructor sidebar --}}
        <x-instructor-sidebar />
        <!-- container header -->
        <div class="header-wrap page-dark">
            <div class="uk-container uk-container-expand rm-expand">
                <!-- navbar -->
                @include('layouts.navigation')
                <!-- breadcrumb -->
                <div class="uk-flex uk-flex-middle uk-margin-small-left" uk-grid>

                    <div class="page-header uk-margin-medium-top uk-margin-medium-bottom breadcrumb uk-width-3-4">
                        <h3 class="uk-margin-remove-bottom title-add">Edit Question Bank</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="uk-container uk-container-expand rm-expand uk-margin-medium-top uk-margin-left">
            <div class="add-classroom">
                <form action="{{ route('question-bank.update',$questionBank->id) }}" method="POST" id="target" enctype="multipart/form-data" class="room-form">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="pageTbl" value="{{$pageTbl}}">
                    <fieldset class="uk-fieldset add-new" uk-grid>
                        <x-input id="questions" class="uk-width-1-1" type="text" name="questions"  value="{{implode(',',$questions)}}" hidden/>
                        <div class="uk-margin uk-width-1-1">
                            <input class="uk-input title @error('name')error-border @enderror" name="name" type="text"  value="{{$questionBank->name}}" placeholder="Question bank title goes here....." autofocus>
                            @error('name')
                            <span class="error-msg">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
{{--                            <div class="uk-margin uk-width-1-4">--}}
{{--                                <label class="uk-form-label" for="class_room_id"><span>*</span> Grade</label>--}}
{{--                                <select  class="uk-select @error('grade')error-border @enderror" id="grade" name="grade" >--}}
{{--                                    <option readonly selected disabled>Select........</option>--}}
{{--                                    @foreach ( $grades as $grade )--}}
{{--                                        <option value="{{$grade->id}}" @if($questionBank->grade==$grade->id) selected @endif>{{ $grade->name }}</option>--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}
{{--                                @error('grade')--}}
{{--                                <span class="error-msg">--}}
{{--                                    <strong>{{ $message }}</strong>--}}
{{--                                </span>--}}
{{--                                @enderror--}}
{{--                            </div>--}}
                        <div class="uk-margin uk-width-1-4">
                            <label class="uk-form-label" for="class_room_id"><span>*</span> Category</label>
                            <select  multiple class="uk-select @error('category')error-border @enderror" id="category" name="cat_id[]">
                                @foreach ( $categories as $cat )
                                    <option value="{{$cat->id}}" @if(  in_array($cat->id,explode(',',$questionBank->cat_id))) selected @endif>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category')
                            <span class="error-msg">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="uk-margin uk-width-1-2">
                            <label class="uk-form-label" for="sub_category"><span>*</span> Sub Category</label>
                            <select  multiple class="uk-select @error('sub_category')error-border @enderror" id="sub_category" name="sub_cat_id[]">

                            </select>
                            @error('sub_category')
                            <span class="error-msg">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="uk-margin uk-width-1-5">
                            <label class="uk-form-label" for="absence-rooms"> Choose Dynamic</label>
                            <div class="switcher">
                                <label class="uk-switch " for="default-2">
                                    <input type="checkbox"  id="default-2" @if($questionBank->type==1) checked @endif name="type" >
                                    <div class="uk-switch-slider"></div>
                                </label>
                            </div>
                            @error('type')
                            <span class="error-msg">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        @if($questionBank->type==1)
                        <div id="subcat_input" >
                            @foreach( (array)json_decode($questionBank->question_num) as $key =>$ques)
                            <div class="uk-width-1-3" id="num_question" >
                                <label class="uk-form-label" for="description"><span>*</span>Question number -{{getSubCatName($key)}} </label>

                                <input  type="number" name="question_num[{{$key}}]"  value="{{$ques}}" min="0" class="uk-input" placeholder="Enter Number Here" />

                            </div>
                            @endforeach

                        </div>
                        @else
                            <div id="subcat_input" class="uk-flex" style="display:none">
                            </div>
                        @endif

                        <div class="uk-margin uk-width-1-1 questions" style="display: none">
                            <label class="uk-form-label" for="sub_category"><span>*</span> Questions <span class="uk-badge" id="count_question"></span>  </label>
                            <div class="questions">
                                <div class="uk-overflow-auto uk-margin-medium-top x-scrollbar">
                                    <table class="uk-table uk-table-hover uk-table-middle uk-table-divider rooms" id="questions_table" style="width:100%;">
                                        <input type="hidden" name="select_all" id="select_all" value="">
                                        <thead>
                                        <tr>
                                            <th class="uk-table-shrink"><input id="checkbox_all" class="uk-checkbox" type="checkbox"></th>
                                            <th class="uk-table-expand">title</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="uk-width-1-1">
                            <button class="uk-button uk-button-secondary" id="continue">Continue <i class="fa-solid fa-arrow-right"></i></button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
@include('questionBank.answerDescModal')

    </div>

@endsection
@section('footerScripts')
    @section('script')
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

        <script>
            let selectidss=$('#questions').val()!=''?$('#questions').val().split(','):[];
            $('#count_question').text(selectidss.length)
            $(document).ready(function() {
                $('.uk-select').select2({
                    placeholder:"select Category"
                })
                {{--grades({{$questionBank->grade}});--}}
                cats("{{$questionBank->cat_id}}")
                let subcat="{{$questionBank->sub_cat_id}}"
                questions(subcat.split(","))
                let type="{{$questionBank->type}}"
                if(type=="0"){
                    $(".questions").show()
                }
            })

            var bar = document.getElementById('js-progressbar');


        function grades(grade){
            $.ajax({
                url: `/get_category_grade/${grade}`,
                type: "get",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                success: function (res) {
                    if(res.status){
                        $('#category').empty()
                        $('#category').append('<option readonly selected disabled>Select........</option>')
                        res.data.forEach((item)=>{
                            let items='';
                            if(item.id== {{$questionBank->cat_id}}){
                                items='selected'
                            }
                            $('#category').append(`
                                <option value="${item.id}" ${items} >${item.name}</option>
                                `)
                        });

                    }
                },
                error:function (res) {
                    Swal.fire("Close!", "Something is wrong, Please try again.", "error");
                }
            });
        }
           // $(document).on('change','#grade',function (e){
           //     e.preventDefault();
           //     let grade= $(this).val();
           //     grades(grade);
           // });
            $(document).on('change','#category',function (e){
                e.preventDefault();
                let cat= $(this).val();
                
                cats(cat);
            });
            function cats(cat){
                $.ajax({
                    url: `/get_sub_category_grade/`,
                    data: {category:cat},
                    type: "get",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function (res) {
                        if(res.status){
                            $('#sub_category').empty()
                            res.data.forEach((item)=>{
                                let items='';
                                if(item.id== {{$questionBank->sub_cat_id}}){
                                    items='selected'
                                }
                                $('#sub_category').append(`
                                    <option value="${item.id}" ${items}>${item.name}</option>
                                    `)
                            });

                            if($('#default-2').is(':checked')){
                                $('#subcat_input').css('display','flex')
                                $('.questions').fadeOut()
                            }else {
                                $('#subcat_input').fadeOut()
                                $('.questions').fadeIn()
                            }
                        }
                    },
                    error:function (res) {
                        Swal.fire("Close!", "Something is wrong, Please try again.", "error");
                    }
                });
            }
            $(document).on('change','#sub_category',function (e){
                selectidss=[]
                e.preventDefault();
                $('.questions').show()
                $('#subcat_input').empty()
                let ids= $(this).val();
                if(ids.length>0){
                    var text = $('#sub_category option:selected').toArray().map(item => item.text);
                    if($('#default-2').is(':checked')){
                        $('.questions').fadeOut()
                    }else{
                        $('.questions').fadeIn()
                    }
                    ids.forEach((item,index)=>{
                        $('#subcat_input').append(`
                        <div class="uk-width-1-3 uk-margin-small-right" id="num_question" >
                            <label class="uk-form-label" for="description"><span>*</span>Question number - ${text[index]} </label>

                            <input type="number" name="question_num[${item}]"  min="0" class="uk-input" placeholder="Enter Number Here" />

                    </div>

`)
                    })

                    questions(ids)
                }else{
                    $('.questions').fadeOut()
                }
                $('#count_question').text(selectidss.length)
            });
            $(document).on('change','#checkbox',function (e){
                e.preventDefault()
                if($(this).is(':checked')){
                    if(!selectidss.includes($(this).val())){
                        selectidss.push($(this).val());
                    }
                }else{
                    selectidss = selectidss.filter(item => item !== $(this).val())

                }
                $('#count_question').text(selectidss.length)


            });
            function questions(ids){
                $('#questions_table').DataTable().clear().destroy()
                $(".loading").show();
                var room_student_table
                setTimeout(function () {
                    room_student_table =  $('#questions_table').DataTable({
                        processing: true,
                        serverSide: true,
                        lengthMenu: [5, 10, 20, 50],
                        pageLength: 10,
                        retrieve: true,
                        // paging: false,
                        responsive: true,
                        initComplete: function () {
                            $(".loading").hide();
                        },
                        dom: 'Blfrtip',
                        order: [[0, 'desc']],
                        "language": {
                            "processing":
                                `<div style=" display: flex; margin-top: 150px; margin-left: 120px">
                        <span class='fa-stack fa-lg'>
                            <i class='fa fa-spinner fa-spin fa-stack-2x fa-fw'></i>
                           </span>&emsp;Processing ... </div>`,
                        },
                        ajax: {
                            url: "/get-question-in-bank-data",
                            data: function (d) {
                                d.grade=ids;
                                // d.classroom_id=$('#classroom_id').val();
                                // d.grade_id=$('#grade_id').val();
                            },
                        },
                        columns: [
                            {
                                data:'id',orderable: false, render:function (data,type,full){

                                    let items
                                    // console.log(selectidss,'5548')
                                    if(selectidss.includes(data.toString())){
                                        items='checked';
                                    }

                                    return `<input class="uk-checkbox" id="checkbox" ${items} value="${data}" type="checkbox">`;}
                            },
                            {data: 'question' , className: ' uk-text-left',
                                render:function (data,type,full){
                                    return `<a id="moreInfo" data-id="${full['id']}">${data}</a>`;
                                }
                            },
                        ]
                    });
                }, 500);
            }

            $('#checkbox_all').on('click',function (e){
                // $('input[id=checkbox]').not(this).prop('checked', this.checked);
                if(this.checked){
                    $("input[id=checkbox]").each(function (i, el) {
                        el.setAttribute("checked", "checked");
                        el.checked = true;
                        el.parentElement.className = "checked";
                        if(!selectidss.includes(el.value.toString())){
                            selectidss.push(el.value.toString());
                        }

                    });
                }else{
                    $("input[id=checkbox]").each(function (i, el) {
                        el.removeAttribute("checked");
                        el.parentElement.className = "";
                        selectidss = selectidss.filter(item => item !== el.value)
                        el.checked=false
                    });
                }
                $('#count_question').text(selectidss.length)
                console.log(selectidss,'new')
            })

            $( "#sortable" ).sortable({
                revert: true
            });
            $( "#draggable" ).draggable({
                connectToSortable: "#sortable",
                helper: "clone",
                revert: "invalid"
            });
            $( "ul, li" ).disableSelection();

            $("#default-2").on('change',function (e){
                if($('#default-2').is(':checked')){
                 $('#subcat_input').css('display','flex')
                 $('.questions').fadeOut()

                }else {
                    $('#subcat_input').fadeOut()
                    $('.questions').fadeIn()
                }
            })
            $('#continue').on('click',function (e){
                e.preventDefault()
                $('#questions').val(selectidss.toString())
                // console.log(selectidss);
                $("#target").submit();
            })

            $(document).on('click','#moreInfo',function (e){
                $('.card-question span').empty();
                $('#more .uk-list').empty();
                $('#answer_desc_div').empty();
                UIkit.modal('#modal-more-info').show();
                $('.loader.spinner').show();
                let question= $(this).attr('data-id');

                $.ajax({
                    url: `/get_answer/${question}`,
                    type: "get",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function (res) {
                        if(res.status){
                            $('.card-question span').empty()
                            $('#more .uk-list').empty()
                            $('.card-question span').append(res.data.title)
                            res.data.answers.forEach((item,index)=>{

                                let correct=''
                                if(item.correct){
                                    correct='correct'
                                }
                                let valueInput
                                if(item.status){
                                    valueInput=item.valueCk
                                }else{
                                    valueInput=item.valueInput
                                }
                                $('.uk-list').append(`
                            <div class="answer-wrapper">
                                    <label class="answar uk-width-1-1 uk-card uk-card-body uk-margin-small-bottom ${correct}" for="">
                                        <input class="notAnswered"  type="radio" value="${index}" >
                                        <span class="checkmark"></span>
                                        <span class="uk-margin-medium-left">
                                            ${valueInput}
                                        </span>
                                    </label>
                                </div>
                                    `)
                            });
                            if(res.data.answer_desc){
                                $('#answer_desc').show()
                                $('#answer_desc_div').append(
                                    `
                                <iframe src="${res.data.answer_desc}" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen style="top:0;left:0 ;width: 100%;height: 100%;" title="Final Revision Ch4 -Part2-Last Video"></iframe>

                                    `
                                )
                            }
                            $('.loader.spinner').hide();
                        }
                    },
                    error:function (res) {
                        Swal.fire("Close!", "Something is wrong, Please try again.", "error");
                    }
                });

        })        </script>
    @endsection
