@extends('layouts.app')
@section('title', 'Add new announcement')

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
                        <h3 class="uk-margin-remove-bottom title-add">Add New announcement</h3>
                    </div>

                </div>
            </div>
        </div>

        <div class="uk-container uk-container-expand rm-expand uk-margin-medium-top uk-margin-left">
            {{-- <x-auth-validation-errors /> --}}
            <div class="add-announcement">
                <form action="{{ route('announcement.store') }}" id="target" method="POST" enctype="multipart/form-data" class="announcement-form">
                    @csrf
                    <fieldset class="uk-fieldset add-new" uk-grid>
                        <div class="uk-margin uk-width-1-1">
                            <input class="uk-input title @error('title')error-border @enderror" name="title" type="text" placeholder="announcement title goes here....." autofocus>
                            @error('title')
                            <span class="error-msg">
                                    <strong>{{$message}}</strong>
                                </span>
                            @enderror

                        </div>

                        <div class="uk-margin uk-width-3-4@m uk-width-1-1@s">
                            <label class="uk-form-label" for="description"><span>*</span> Description</label>
                            <textarea name="desc" id="desc" class="uk-textarea"></textarea>



                            @error('desc')
                            <span class="error-msg">
                                    <strong>{{$message}}</strong>
                                </span>
                            @enderror
                        </div>

                            <div class="uk-margin uk-width-1-4@m uk-width-1-1@s" id="classroom_id">
                                <label class="uk-form-label" for="description">Select Classroom</label>
                                <select multiple class="room_select uk-select @error('classroom_id')error-border @enderror" id="classroom_id" name="classroom_id[]">
                                    @foreach ( $classrooms as $classroom )
                                        <option value=" {{$classroom->id}} ">{{ $classroom->title }}</option>
                                    @endforeach
                                </select>
                                @error('classroom_id')
                                <span class="error-msg">
                                    <strong>{{$message}}</strong>
                                </span>
                                @enderror

                            </div>
                        <div class="uk-margin uk-width-1-1">
                            <label class="uk-form-label" for="cover">Announcement Metrial</label>
                            <ul class="edit-room-material" id="parent" style="display: none">

                            </ul>
                            <div class="js-upload uk-placeholder uk-text-center">
                                <span class="dark-font" uk-icon="icon: cloud-upload"></span>
                                <span class="uk-text-middle dark-font">Drop PDF files here</span>
                                <div uk-form-custom>
                                    <input type="file" multiple>
                                    <span class="uk-link">selecting one</span>
                                </div>
                            </div>
                            <progress id="js-progressbar" class="uk-progress" value="0" max="100" hidden></progress>
                        </div>

                            <div class="uk-width-1">
                                <button class="uk-button uk-button-secondary" id="submit">Publish</button>
                            </div>

                    </fieldset>
                </form>
            </div>
        </div>

    </div>

@endsection
@section('footerScripts')

    @section('script')
{{-- <script src="https://cdn.jsdelivr.xyz/npm/ckeditor5-mathtype@1.0.4/build/ckeditor.min.js "></script> --}}

        <script>

            // ClassicEditor.create( document.querySelector( '#description_editor' ) ,{
            //     toolbar: [ 'MathType','ChemType', 'imageUpload' ],
            //     ckfinder: {
            //         uploadUrl: `{{route('admin.question.uploadImage')}}?_token=${document.head.querySelector('meta[name="csrf-token"]').content}`,
            //         withCredentials: true,
            //     },
            // }).then( editor => {
            //     editor.model.document.on('change:data', (evt, data) => {
            //     $('#desc').val(editor.getData()) ;
            //     });
            // } )

                $('.uk-select').select2({
                    placeholder:"select Lessons"
                })

                    $('#save').click(function (e){

                        $('#action').val('save_draft');

                        $("#target").submit();
                    });
                    $(document).on('click','#submit',function (e){
                        $("#target").submit();
                    });


            var bar = document.getElementById('js-progressbar');

            UIkit.upload('.js-upload', {

                url: '/uploadMaterial?_token={{csrf_token()}}',
                multiple: true,
                name:'files[]',

                beforeSend: function () {
                    console.log('beforeSend', arguments);
                },
                beforeAll: function () {
                    console.log('beforeAll', arguments);
                },
                load: function () {
                    console.log('load', arguments);
                },
                error: function () {
                    console.log('error', arguments);
                },
                complete: function () {
                    console.log('complete', arguments);
                    $("#parent").show()
                    let response=JSON.parse(arguments[0].response)
                    console.log(response)
                    $("#parent").append(`
                    <li data-id="${response.id}">  <a download="custom-filename.jpg" href="${response.url}"> ${response.file_name} </a>  <span uk-icon="icon: close; ratio: 1"  data-id ="${response.id}" class="deleteImage"> </span></li>
                    `)
                },

                loadStart: function (e) {
                    console.log('loadStart', arguments);

                    bar.removeAttribute('hidden');
                    bar.max = e.total;
                    bar.value = e.loaded;
                },

                progress: function (e) {
                    console.log('progress', arguments);

                    bar.max = e.total;
                    bar.value = e.loaded;
                },

                loadEnd: function (e) {
                    console.log('loadEnd', arguments);

                    bar.max = e.total;
                    bar.value = e.loaded;
                },

                completeAll: function () {
                    console.log('completeAll', arguments);

                    setTimeout(function () {
                        bar.setAttribute('hidden', 'hidden');
                    }, 1000);

                    Swal.fire("Done", "Files Uploaded Successful.", "success");
                }
            });
            $(document).on('click','.deleteImage',function (){
                var id = $(this).attr('data-id');
                $.ajax({
                    url: `/deleteImageAnnouncement/${id}`,
                    type: "POST",
                    data: {id: 0 },

                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (res) {
                        if (res.status) {
                            Swal.fire("Done", "File delete successful.", "success");
                        }
                        $("#parent li").each(function(){
                            if($(this).attr('data-id') == id){
                                $(this).remove();
                            }
                        });
                    },
                    error: function (res) {
                        Swal.fire("Close!", "Something is wrong, Please try again.", "error");
                    }
                });
            })
        </script>
    @endsection
