@extends('layouts.app')
@section('title','Settings')
@section('css')
    <link rel="stylesheet" href="{{ asset('ijaboCropTool/ijaboCropTool.min.css') }}">

@endsection
@section('body')

<!-- container -->
<div class="wrapper-page-light">
    <!-- container header -->
    <div class="header-wrap page-dark">
        <div class="uk-container uk-container-expand">
            <!-- navbar -->
            @include('layouts.navigation')
        </div>

        <div class="page-header uk-text-center uk-margin-small-top uk-padding-xlarge-bottom">
            <h2 class="uk-margin-remove-bottom">Account settings</h2>
            <h5 class="uk-margin-remove">Need to tweak a setting?</h5>
        </div>

    </div>

    <div class="wrapper-switcher-profile uk-padding-medium-bottom">

        <div class="uk-width-1-2@m uk-width-1-1@s uk-margin-auto settings-wrapper uk-margin-bottom-small" uk-grid>
        <!-- Validation Errors -->
            <form method="POST" class="settings-from uk-width-1-1 uk-padding-medium" action="{{ route('update.settings') }}" enctype="multipart/form-data">
                @csrf
                <x-auth-validation-errors class="uk-margin-bottom" :errors="$errors" />

                <x-input id="user_id" class="uk-width-1-1" type="text" name="userId" :value="$userData->id" hidden/>

                <!-- Profile Image -->
                <div class="profile-img-wrapper uk-margin-auto">
                    <label for="uploadImage">
                        <img class="showImage" src="{{ (!empty($userData->profile_image))? url('uploads/profile_images/'. $userData->profile_image) : url('uploads/no-image/third-year.png') }}" alt="avatar">
                    </label>
                </div>
                <div class="uk-margin-small uk-margin-small-bottom uk-text-center">
                    <div uk-form-custom>
                        <input name="profile_image" id="uploadImage" type="file">
                        <button class="uk-button uk-button-secondary btn-small" type="button" tabindex="-1">Upload</button>
                    </div>
                </div>
                <!-- Student Id -->
                <div>
                    <x-label for="first_name" class="light-dark uk-text-small" :value="__('Student Id')" />

                    <x-input id="student_id" class="block uk-width-1-1 disabled" type="text" name="student_id" :value="$userData->name_id" disabled/>
                </div>

                <!-- Name -->
                <div>
                    <x-label for="first_name" class="light-dark" :value="__('First Name')" />

                    <x-input id="first_name" class="block uk-width-1-1" type="text" name="first_name" :value="$userData->first_name" required autofocus />
                </div>

                <div>
                    <x-label for="last_name" class="light-dark" :value="__('Last Name')" />

                    <x-input id="last_name" class="block uk-width-1-1" type="text" name="last_name" :value="$userData->last_name" required autofocus />
                </div>

                <!-- Email Address -->
                <div>
                    <x-label for="email" class="light-dark" :value="__('Email')" />
                    <x-input id="email" class="block uk-width-1-1" type="email" name="email" :value="$userData->email" required />
                </div>
                <!-- Email Address -->
                <!-- phone -->
                @can('Student')
                <div>
                    <x-label for="phone" class="light-dark" :value="__('Phone')" />
                    <x-input id="phone" class="block uk-width-1-1" type="text" name="phone" :value="$userData->student->phone" />
                </div>
                @endcan
                <!-- phone -->

                @can('Instructor')
                    <div>
                        <x-label for="email" class="light-dark" value="Bio" />
                        <textarea id="bio" class="block uk-width-1-1 uk-textarea" name="bio">{{$userData->bio}}</textarea>

                    </div>
                @endCan('Student')
                <!-- Password -->
                <div>
                    <x-label for="password" class="light-dark" :value="__('Password')" />

                    <x-input id="password" class="block uk-width-1-1"
                                    type="password"
                                    name="password"
                                    autocomplete="new-password" placeholder="******"/>
                </div>

                <!-- Confirm Password -->
                <div>
                    <x-label for="password_confirmation" class="light-dark" :value="__('Confirm Password')" />

                    <x-input id="password_confirmation" class="block uk-width-1-1"
                                    type="password"
                                    name="password_confirmation" placeholder="******"/>
                </div>

                <div class="uk-flex uk-width-1-2">
                    <x-button>
                        {{ __('Update Account') }}
                    </x-button>
                </div>
            </form>

        </div>


    </div>
</div>

@endsection

@section('footerScripts')
    @section('script')

        <script src="{{ asset('ijaboCropTool/ijaboCropTool.min.js') }}"></script>
<script type="text/javascript">

    $('#uploadImage').change(function (){
        var formData=new FormData();

        formData.append('profile_image', $('#uploadImage')[0].files[0]);
        formData.append('_token', "{{csrf_token()}}");

        $.ajax({
            url: `/user/checkUpload-profile`,
            contentType: false,
            cache: false,
            processData: false,
            type:"POST",
            data:formData,
            beforeSend: function()
            {
                $('.loading').show();
            },
            success: function(data) {
                  if(data.status){
                  uploadImage()
                  }
            },
            error: function (jqXhr, textStatus, errorMessage) { // error callback
                Swal.fire("Done", "The profile image must not be greater than 2 M.", "warning");
            }
        })
    })

    // uploadImage()
    function uploadImage(){
        $('#uploadImage').ijaboCropTool({
            preview : '.image-previewer',
            setRatio:1,
            allowedExtensions: ['jpg', 'jpeg','png'],
            buttonsText:['CROP','QUIT'],
            buttonsColor:['#30bf7d','#ee5155', -15],
            processUrl:'/user/upload-profile',
            withCSRF:['_token','{{csrf_token()}}'],
            onSuccess:function(message, element, status){
                Swal.fire("Done", "Image upload success", "success");

            },
            onError:function(message, element, status){
                $('.ijabo-cropper-modal').hide();
                Swal.fire({
                    title: " The profile image must not be greater than 2 M.",
                    text: "",
                    icon: "warning",
                    type: "warning",
                    confirmButtonColor: '#DD6B55',
                    confirmButtonText: 'Ok',
                    closeOnConfirm: false,
                    closeOnCancel: false
                }).then(function (result) {
                    if (result.isConfirmed) {
                        window.location.reload()
                    } else {
                        window.location.reload()
                    }
                })
            }
        });


    }
</script>
@endsection

