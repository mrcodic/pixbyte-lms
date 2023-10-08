@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Questions')

@section('vendor-style')

@endsection
@section('page-style')
  <!-- Page css files -->

  {{-- <link rel="stylesheet" type="text/css" href="{{asset('admin/vendors/css/editors/quill/katex.min.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('admin/vendors/css/editors/quill/monokai-sublime.min.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('admin/vendors/css/editors/quill/quill.snow.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('admin/vendors/css/editors/quill/quill.bubble.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('admin/css/plugins/forms/form-quill-editor.css')}}"> --}}
@endsection

@section('content')

<section class="bs-validation form-control-repeater">
    <div class="row">
        <!-- Bootstrap Validation -->
        <div class="col-md-12 col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Question</h4>
                </div>
                <div class="card-body">
                    <form class="invoice-repeater" id="questionForm" action="{{route('admin.question.update', $question)}}" method="POST">
                        @csrf
                        <x-input id="type" class="uk-width-1-1" type="text" name="type"  hidden/>

                        <div class="mb-2 row">
                            <div class="col-9">
                                <label class="form-label" for="qname"><h5>question title</h5></label>
                                <input
                                    type="text"
                                    id="qname"
                                    class="form-control"
                                    placeholder="Name"
                                    name="name"
                                    value="{{$question->name}}"
                                />
                                @error('name')
                                   <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-3">
                                <label class="form-label" for="user_id"><h5> Instructor</h5></label>
                                <select name="user_id" id="user_id" class="form-select" required>
                                    <option readonly >select...</option>
                                    @foreach ( $instructors as $instructors )
                                        <option value="{{$instructors->id}}" {{$question->user_id == $instructors->id ? 'selected' :null}}>{{ $instructors->name }}</option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="mb-1 col-6">
                                <label class="form-label" for="category"><h5> Category</h5></label>
                                <select name="category_id" id="category" class="form-select" required>
                                    <option readonly >select...</option>
                                    @foreach ( $categories as $cat )
                                        <option value="{{$cat->id}}" {{$question->category_id == $cat->id ? 'selected' :null}}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="mb-1 col-6">
                                <label class="form-label" for="sub_category"><h5>Sub Category</h5></label>
                                <select name="subcategory_id" id="sub_category" class="select2 form-select" >
                                    <option readonly >select...</option>
                                    <option value="{{$question->subcategory_id}}" selected>{{ $question->sub_category->name }}</option>
                                </select>
                                @error('sub_category')
                                    <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>
                        </div>


                        <div class="mb-3 editor_cont">
                            <input type="hidden" name="question_status" id="question_status">
                            <label class="form-label" for="qtitle"><h5> Quistions</h5></label>
                            <div class="form-check form-check-secondary form-switch inline-flex mb-1">
                                <input type="checkbox" class="form-check-input editor_check" id="qtitle_check" {{$question->question_status == 2 ? "checked" : null}}>
                                <label class="form-label" for="qtitle_check">Used editor</label>
                            </div>
                            <div class="editor_div">
                                <input type="hidden" class="editor_id">
                                <input
                                    type="text"
                                    id="qtitle"
                                    class="form-control editor_input {{$question->question_status == 2 ? "ckeditor" : null}}"
                                    placeholder="title"
                                    name="title"
                                    value="{{$question->title}}"
                                />
                            </div>
                            @error('title')
                               <div class="text-danger">{{$message}}</div>
                            @enderror
                        </div>


                        <div class="mb-3">
                            <div id="answersFinal" class="hidden"></div>

                            <div data-repeater-list="answers">
                                @foreach ($question->answers as $answer)
                                    <div data-repeater-item >
                                        <div class="row d-flex align-items-end">
                                            <div class="row editor_cont">
                                                <label class="form-label" for=""><h5> Answer - <span class='editor_index'>{{$answer['id']}}</span></h5></label>

                                                <div class="form-check form-check-secondary form-switch inline-flex mb-1 mx-1 col-6">
                                                    <input type="checkbox" class="form-check-input editor_check" {{$answer['status'] == "true"? 'checked': null}}>
                                                    <label class="form-label" for="answer_check_2">Used editor</label>
                                                </div>

                                                <div class="form-check form-check-success inline-flex mb-1 mx-1 col-auto ms-auto">
                                                    <input type="checkbox" class="form-check-input editor_currect_answer"{{$answer['correct'] == "true"? 'checked': null}} >
                                                    <label class="form-label" >Currect Answer</label>
                                                </div>

                                                <div class="col-10 editor_div">
                                                    <input type="hidden" class="editor_id">
                                                    <input
                                                        type="text"
                                                        class="form-control editor_input editor_input_answer {{$answer['status'] == "true" ? "ckeditor" : null}}"
                                                        placeholder="Answer"
                                                        value="{{$answer['status'] == "true" ? $answer['valueCk'] : $answer['valueInput']}}"
                                                    />
                                                </div>
                                                <div class="col-auto ms-auto">
                                                    <button class="btn btn-outline-danger text-nowrap float-right" data-repeater-delete type="button">
                                                        <i data-feather="x" class="me-25"></i>
                                                        <span>Delete</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <hr />
                                    </div>
                                @endforeach
                                @error('answers[]')
                                    <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button class="btn btn-icon btn-outline-secondary" type="button" data-repeater-create>
                                        <i data-feather="plus" class="me-25"></i>
                                        <span>Add Answer</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="form-label" for="description"><h5>Answer Description</h5></label>
                            <div>
                                <label class="form-label" for="url"><h5>Video URL</h5></label>
                                <input
                                    type="text"
                                    id="url"
                                    class="form-control"
                                    placeholder="Please Enter Url"
                                    name="answer_desc"
                                    value="{{$question->answer_desc}}"
                                />
                                @error('answer_desc')
                                   <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>
                        </div>


                        <button id="submitForm" class="btn btn-primary">Submit</button>

                    </form>

                    </div>
                </div>
            </div>
        </div>
        <!-- /Bootstrap Validation -->
    </div>
</section>


@endsection

@section('vendor-script')
  <!-- Vendor js files -->
    <script src="{{ asset('admin/vendors/js/forms/select/select2.full.min.js') }}"></script>
    <script src="{{ asset('admin/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('admin/vendors/js/forms/cleave/cleave.min.js') }}"></script>
    <script src="{{ asset('admin/vendors/js/forms/cleave/addons/cleave-phone.us.js') }}"></script>
    <script src="{{ asset('admin/vendors/js/forms/repeater/jquery.repeater.min.js') }}"></script>
    {{-- <script src="{{ asset('admin/js/scripts/forms/form-repeater.js') }}"></script> --}}

@endsection
@section('page-script')
    <script src="{{ asset('admin/js/helper.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/ckeditor5-mathtype@1.0.4/build/ckeditor.min.js "></script>

    <script>
        let selectids=[]
        $('#count_question').text(selectids.length)

        $('#checkbox_all').on('click',function (e){
            if(this.checked){
                $("input[id=checkbox]").each(function (i, el) {
                    el.setAttribute("checked", "checked");
                    el.checked = true;
                    el.parentElement.className = "checked";
                    if(!selectids.includes(el.value.toString())){
                        selectids.push(el.value.toString());
                    }

                });
            }else{
                $("input[id=checkbox]").each(function (i, el) {
                    el.removeAttribute("checked");
                    el.parentElement.className = "";
                    selectids = selectids.filter(item => item !== el.value)
                    el.checked=false

                });
            }
            $('#count_question').text(selectids.length)

            console.log(selectids,'new')
        })

        $(document).on('change','#checkbox',function (e){
            console.log(selectids,'before')

            e.preventDefault()

            if($(this).is(':checked')){
                if(!selectids.includes($(this).val())){
                    selectids.push($(this).val());
                }
            }else{
                selectids = selectids.filter(item => item !== $(this).val())

            }
            $('#count_question').text(selectids.length)


        });


        $(document).on('change','#category',function (e){
            e.preventDefault();
            let grade= $(this).val();

            $.ajax({
                url: '{{route("admin.get_sub_category_grade", "")}}/'+grade,
                type: "get",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                success: function (res) {
                    if(res.status){
                        $('#sub_category').empty()
                        // $('#sub_category').append('<option readonly selected disabled>Select........</option>')
                        res.data.forEach((item)=>{
                            $('#sub_category').append(`
                                <option value="${item.id}">${item.name}</option>
                                `)
                        });

                    }
                },
                error:function (res) {
                    Swal.fire("Close!", "Something is wrong, Please try again.", "error");
                }
            });


        });



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

            $('#questions').val(selectids.toString())
            // console.log(selectids);
            $("#quesBankForm").submit();
        })



        var editors = []

        $(document).ready(function(){
            var ckeditor = document.querySelectorAll(".ckeditor");

            ckeditor.forEach((inputEditor) => {
                let editorDiv = inputEditor.closest('.editor_div').querySelector('.editor_id');
                ClassicEditor.create( inputEditor ,{
                    toolbar: [ 'MathType','ChemType', 'imageUpload' ],
                    ckfinder: {
                        uploadUrl: `{{route('admin.question.uploadImage')}}?_token=${document.head.querySelector('meta[name="csrf-token"]').content}`,
                        withCredentials: true,
                    },
                }).then( editor => {
                    editorDiv.value = editor.id;
                    editor.setData( inputEditor.value);
                    editors.push(editor);
                } )
            });

        })


        $(document).on('click','.editor_check', function(){ changeInputEditor($(this))})

        function changeInputEditor(thisCheck){

            let input    = thisCheck.closest('.editor_cont').find('.editor_input')[0];
            let divInput = thisCheck.closest('.editor_cont').find('.editor_div');

            if(thisCheck.is(":checked")){
                input.classList.add('ckeditor')

                ClassicEditor.create( input ,{
                    toolbar: [ 'MathType','ChemType', 'imageUpload' ],
                    ckfinder: {
                        uploadUrl: `{{route('admin.question.uploadImage')}}?_token=${document.head.querySelector('meta[name="csrf-token"]').content}`,
                        withCredentials: true,
                    },
                }).then( editor => {
                    divInput.find('.editor_id').val(editor.id);
                    editors.push(editor);
                } )
            }
            else{
                divInput.children('.ckeditor').show()
                divInput.children('.ck-editor').remove()
                input.classList.remove('ckeditor')
            }
        }



        $('.invoice-repeater, .repeater-default').repeater({
            show: function () {
                $(this).slideDown();
                $(this).find('.editor_index').html($('.editor_input_answer').length)
                // Feather Icons
                if (feather) {
                    feather.replace({ width: 14, height: 14 });
                }
            },
            hide: function (deleteElement) {
                $(this).slideUp(deleteElement);
                setTimeout(function(){
                    $('.editor_index').each(function(index) {
                        $(this).html(index+1)
                    });
                }, 500);
            }
        });


        $('#submitForm').click(function (e){
            e.preventDefault()


            let point = 0;
            $('[data-repeater-item]').each(function(index) {
                let id         = index+1;
                let valueInput = $(this).find('.editor_input').val();
                let editorVal  = editors.find(x => x.id === $(this).find('.editor_id').val());
                let valueCk    = editorVal? editorVal.getData() :null;
                let correct    = $(this).find('.editor_currect_answer').is(":checked") ? true : false;
                let status     = editorVal ? true :false;

                $('#answersFinal').append('<input name="answers['+id+'][id]" value="'+id+'">')
                $('#answersFinal').append('<input name="answers['+id+'][valueInput]" value="'+valueInput+'">')
                $('#answersFinal').append('<textarea name="answers['+id+'][valueCk]">'+valueCk+'</textarea>')
                $('#answersFinal').append('<input name="answers['+id+'][correct]" value="'+correct+'">')
                $('#answersFinal').append('<input name="answers['+id+'][status]" value="'+status+'">')

                correct ? point++ :null;

            });

           titleEditorSend();

            if(point<1){
                Swal.fire('warning','please check at least one answer','warning');
                return false
            }else if(point > 1) {
                $("#type").val(2);
            }else{
                $("#type").val(1);
            }

            $("#questionForm").submit();
        })


        function titleEditorSend() {
            let qtitle = $('#qtitle').closest('.editor_cont')
            let editorVal = editors.find(x => x.id === qtitle.find('.editor_id').val())
            $('#qtitle').val(editorVal ? editorVal.getData() : $('#qtitle').val())
            editorVal ? $('#question_status').val(2) : $('#question_status').val(1)
        }

    </script>
@endsection


