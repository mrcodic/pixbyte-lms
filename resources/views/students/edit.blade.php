@extends('layouts.app')
@section('title', 'Edit Student')

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
                        <h3 class="uk-margin-remove-bottom title-add">Edit Student</h3>
                    </div>


                </div>
            </div>
        </div>


        <div class="uk-container uk-container-expand rm-expand uk-margin-medium-top uk-margin-left">
            {{-- <x-auth-validation-errors /> --}}
            <div class="add-classroom">
                <form action="{{ route('student.update',$student->id) }}" method="POST" id="target" enctype="multipart/form-data" class="room-form">
                    @csrf
                    @method('patch')
                    <fieldset class="uk-fieldset add-new" uk-grid>
                   <x-input id="user_id" class="uk-width-1-1" type="text" name="id" :value="$student->id" hidden/>


                        <div class="uk-margin uk-width-1-2">
                            <label class="uk-form-label" for="absence-rooms"><span>*</span>First Name</label>
                            <div class="uk-form-controls">
                                <input id="absence-rooms" class="uk-input @error('first_name')error-border @enderror" name="first_name" type="text" placeholder="First Name" value="{{$student->first_name}}" autofocus>
                                @error('first_name')
                                <span class="error-msg">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="uk-margin uk-width-1-2">
                            <label class="uk-form-label" for="absence-rooms"><span>*</span>Last Name</label>
                            <div class="uk-form-controls">
                                <input id="absence-rooms" class="uk-input @error('last_name')error-border @enderror" name="last_name" type="text" placeholder="Last Name" value="{{$student->last_name}}" autofocus>
                                @error('last_name')
                                <span class="error-msg">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>



                        <div class="uk-margin uk-width-1-2">
                            <label class="uk-form-label" for="absence-rooms"><span>*</span>Email</label>
                            <div class="uk-form-controls">
                                <input id="email" class="uk-input @error('email')error-border @enderror" name="email" type="email" placeholder="Email" value="{{$student->email}}" autofocus>
                                @error('email')
                                <span class="error-msg">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="uk-margin uk-width-1-2">
                            <label class="uk-form-label" for="lock-after"><span>*</span> Phone</label>
                            <div class="uk-form-controls">
                                <input id="lock-after" class="uk-input" min="0" type="number" placeholder="Enter Phone" id="phone" value="{{@$student->student->phone}}" name="phone" />
                                @error('phone')
                                <span class="error-msg">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="uk-margin uk-width-1-2">
                            <label class="uk-form-label" for="absence-rooms"><span>*</span> Password</label>
                            <div class="uk-form-controls">
                                <input id="absence-rooms" class="uk-input @error('password')error-border @enderror" name="password" type="password" placeholder="password" value="" autofocus>
                                @error('password')
                                <span class="error-msg">
                                    <strong>{{$message}}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="uk-margin uk-width-1-2">
                            <label class="uk-form-label" for="absence-rooms"><span>*</span> Password Confirmation</label>
                            <div class="uk-form-controls">
                                <input id="absence-rooms" class="uk-input @error('password_confirmation')error-border @enderror" name="password_confirmation" type="password" placeholder="password" value="" autofocus>
                                @error('password_confirmation')
                                <span class="error-msg">
                                    <strong>{{$message}}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="uk-margin uk-width-1-1">
                            <label class="uk-form-label" for="absence-rooms"><span>*</span> Grade</label>
                            <div class="uk-form-controls">
                                <select  class="uk-select @error('grade_id')error-border @enderror" id="grade_id" name="grade_id">
                                    @foreach ( $grades as $grade )
                                        <option value="{{$grade->id}}"  @if($grade->id==$student->grade_id) selected @endif>{{ $grade->name }}</option>
                                    @endforeach
                                </select>
                                @error('grade_id')
                                <span class="error-msg">
                                        <strong>{{$message}}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="uk-margin uk-width-1-1">
                            <label class="uk-form-label" for="cover">Image</label>

                            <div class="js-upload uk-placeholder uk-text-center">
                                <span class="dark-font" uk-icon="icon: cloud-upload"></span>
                                <span class="uk-text-middle dark-font">Drop Image here</span>
                                <div uk-form-custom>
                                    <input type="file" name="profile_image" >
                                    <span class="uk-link">selecting one</span>
                                </div>
                            </div>
                            {{--                            <progress id="js-progressbar" class="uk-progress" value="0" max="100" hidden></progress>--}}
                        </div>

                        <div class="uk-width-1-1">
                            <button class="uk-button uk-button-third uk-margin-small-left uk-margin-right" id="save">Publish <i class="fa-solid fa-pen-to-square"></i></button>
                            <button class="uk-button uk-button-secondary" id="continue">Continue <i class="fa-solid fa-arrow-right"></i></button>
                        </div>

                    </fieldset>
                </form>
            </div>
        </div>

    </div>

@endsection
@section('footerScripts')
    @section('script')
        <script>
            $(document).ready(function() {
                $('.uk-select').select2({
                    placeholder:"select Grade"
                })
            })



            $(document).on('click','#continue',function (e){
                e.preventDefault();
                $('#action').val('continue');
                $( "#target" ).submit();
            });
            $(document).on('click','#save',function (e){
                e.preventDefault();
                $('#action').val('save');
                $( "#target" ).submit();
            });

        </script>
    @endsection
