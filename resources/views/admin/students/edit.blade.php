@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Students')

@section('vendor-style')

@endsection
@section('page-style')
  <!-- Page css files -->
@endsection

@section('content')




<section class="bs-validation">
    <div class="content-body">
        <section class="vertical-wizard">
            <div class="bs-stepper vertical vertical-wizard-example">
                <div class="bs-stepper-header">
                    <div class="step" data-target="#student-data--vertical" role="tab" id="student-data--vertical-trigger">
                        <button type="button" class="step-trigger">
                            <span class="bs-stepper-box">
                                <i data-feather='user'></i>
                            </span>
                            <span class="bs-stepper-label">
                                <span class="bs-stepper-title">Student Info</span>
                                <span class="bs-stepper-subtitle">Edit student data</span>
                            </span>
                        </button>
                    </div>
                    @if ($student->parent)
                    <div class="step" data-target="#parent-data-vertical" role="tab" id="parent-data-vertical-trigger">
                        <button type="button" class="step-trigger">
                            <span class="bs-stepper-box">
                                <i data-feather='users'></i>
                            </span>
                            <span class="bs-stepper-label">
                                <span class="bs-stepper-title">Parent Date</span>
                                <span class="bs-stepper-subtitle">Edit parent data</span>
                            </span>
                        </button>
                    </div>
                    @endif
                </div>
                <div class="bs-stepper-content">
                    <form class="" action="{{route('students.update',$student->id)}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="page" value="{{$page}}">
                        <div id="student-data--vertical" class="content" role="tabpanel" aria-labelledby="student-data--vertical-trigger">
                            <div class="modal-body flex-grow-1" id="form_data">
                                <input type="hidden" id="id" name="id" value="{{$student->id}}">

                                <div class="mb-1 col-6">
                                    <label class="form-label" for="name_id">Name Id</label>
                                    <input
                                        type="text"
                                        class="form-control dt-full-name"
                                        id="name_id"
                                        placeholder="John Doe"
                                        name="name_id"
                                        value="{{$student->name_id}}"
                                    />

                                    @error('name_id')
                                    <div class="text-danger">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="row">
                                    <div class="mb-1 col-6">
                                        <label class="form-label" for="first_name">First Name</label>
                                        <input
                                            type="text"
                                            class="form-control dt-full-name"
                                            id="first_name"
                                            placeholder="John Doe"
                                            name="first_name"
                                            value="{{$student->first_name}}"
                                        />
                                        @error('first_name')
                                        <div class="text-danger">{{$message}}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-1 col-6">
                                        <label class="form-label" for="last_name">Last Name</label>
                                        <input
                                            type="text"
                                            class="form-control dt-full-name"
                                            id="last_name"
                                            placeholder="John Doe"
                                            name="last_name"
                                            value="{{$student->last_name}}"
                                        />

                                        @error('last_name')
                                        <div class="text-danger">{{$message}}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mb-1 col-6">
                                        <label class="form-label" for="email">Email</label>
                                        <input
                                            type="text"
                                            id="email"
                                            class="form-control dt-email"
                                            placeholder="john.doe@example.com"
                                            name="email"
                                            value="{{$student->email}}"
                                        />
                                        @error('email')
                                        <div class="text-danger">{{$message}}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-1 col-6">
                                        <label class="form-label" for="phone">Phone</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="phone"
                                            name="phone"
                                            value="{{$student->student->phone}}"
                                        />

                                        @error('phone')
                                        <div class="text-danger">{{$message}}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="mb-1 col-6">
                                        <label class="form-label" for="type">Type</label>
                                        <select id="type" class="select2 form-select" name="type">
                                            <option readonly disabled selected>select...</option>
                                            @foreach ($roles as $role)
                                                <option value="{{$role->id}}" {{$student->type == $role->type ? 'selected':null}}>{{$role->name}}</option>
                                            @endforeach
                                            {{-- <option value="4" {{$student->type == 4 ? 'selected':null}}>Student Offline</option> --}}
                                        </select>
                                    </div>
                                    <div class="mb-1 col-6" id="div_grade" >
                                        <label class="form-label" for="grade">Grade</label>
                                        <select id="grade_id" class="select2 form-select" name="grade_id">
                                            <option readonly disabled selected>select...</option>
                                            @foreach($grades as $grade)
                                                <option value="{{$grade->id}}"  @if($student->grade_id==$grade->id) selected @endif>{{$grade->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('grade_id')
                                        <div class="text-danger">{{$message}}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <label class="form-label" for="profile_image"> Image Profile</label>
                                    <input class="form-control" type="file" id="profile_image" value="{{$student->profile_image}}" name="profile_image">
                                    <img width="100" class="mt-2" @if(!empty($student->profile_image)) src="/uploads/profile_images/{{$student->profile_image}}" @else  src="/uploads/no-image/no-image.jpg"  @endif>

                                    <small class="text-danger profile_image_error"></small>

                                </div>
                                <div class="row">
                                    <div class="col-6 mb-2">
                                        <label class="form-label" for="password">Password</label>
                                        <div class="input-group input-group-merge form-password-toggle">
                                            <input
                                                class="form-control form-control-merge"
                                                id="password"
                                                type="password"
                                                name="password"
                                                placeholder="············"
                                                aria-describedby="password"
                                                tabindex="2"
                                            />
                                            <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                                        </div>

                                        @error('password')
                                        <div class="text-danger">{{$message}}</div>
                                        @enderror
                                    </div>
                                    <div class="col-6 mb-2">
                                        <label class="form-label" for="password_confirmation">Confirm Password</label>

                                        <div class="input-group input-group-merge form-password-toggle">
                                            <input
                                                type="password"
                                                id="password_confirmation"
                                                placeholder="**************"
                                                name="password_confirmation"

                                                class="form-control form-control-merge"
                                                placeholder="············"
                                                aria-describedby="password"
                                                tabindex="2"
                                            />
                                            <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                                        </div>

                                        @error('password_confirmation')
                                        <div class="text-danger">{{$message}}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <a class="btn btn-primary btn-prev">
                                        <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                        <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                    </a>
                                    <button type="submit" class="btn btn-success">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>


                    @if ($student->parent)
                        <form
                            class="needs-validation {{ session()->get('message') == 'success media' ? 'was-validated': null}}"
                            action="{{route('parent.update', $student->parent)}}" method="POST"
                        >
                            @csrf
                            @method('PUT')
                            <div id="parent-data-vertical" class="content" role="tabpanel" aria-labelledby="parent-data-vertical-trigger">
                                <div class="content-header">
                                    <h5 class="mb-0">Update Parent Date</h5>
                                    <small>parent id : {{$student->parent->name_id}}.</small>
                                </div>
                                <div class="row">
                                    <div class="mb-1 col-md-6">
                                        <label class="form-label" for="vertical-name">Name</label>
                                        <input type="text" id="vertical-name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{$student->parent->name}}" />
                                        @error('name')
                                        <div class="text-danger">{{$message}}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mb-1 col-md-6">
                                        <label class="form-label" for="vertical-email">Email</label>
                                        <input type="text" id="vertical-email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{$student->parent->email}}" placeholder="john@email.com" />
                                        @error('email')
                                        <div class="text-danger">{{$message}}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-1 col-md-6">
                                        <label class="form-label" for="vertical-phone">Phone</label>
                                        <input type="text" id="vertical-phone" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{$student->parent->phone}}" placeholder="+20012031233"  />
                                        @error('phone')
                                        <div class="text-danger">{{$message}}</div>
                                        @enderror
                                    </div>

                                </div>
                                <div class="row">

                                    <div class="mb-1 col-md-6">
                                        <label class="form-label" for="parent-password">Password</label>

                                        <div class="input-group input-group-merge form-password-toggle">
                                            <input
                                                class="form-control form-control-merge  @error('password') is-invalid @enderror"
                                                id="parent-password"
                                                type="password"
                                                name="password"
                                                placeholder="············"
                                                aria-describedby="password"
                                                tabindex="2"
                                            />
                                            <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                                        </div>

                                        @error('password')
                                        <div class="text-danger">{{$message}}</div>
                                        @enderror
                                    </div>
                                {{-- </div>
                                <div class="row"> --}}
                                    <div class="mb-1 col-md-6">
                                        <label class="form-label" for="parent-password_confirmation">Confirm Password</label>

                                        <div class="input-group input-group-merge form-password-toggle">
                                            <input
                                                class="form-control form-control-merge  @error('password_confirmation') is-invalid @enderror"
                                                id="parent-password_confirmation"
                                                type="password"
                                                name="password_confirmation"
                                                placeholder="············"
                                                aria-describedby="password"
                                                tabindex="2"
                                            />
                                            <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                                        </div>

                                        @error('password_confirmation')
                                        <div class="text-danger">{{$message}}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <a class="btn btn-primary btn-prev">
                                        <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                        <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                    </a>
                                    <button class="btn btn-success">Submit</button>
                                </div>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </section>
    </div>
</section>


@endsection

@section('vendor-script')
  <!-- Vendor js files -->
  <script src="{{ asset('admin/vendors/js/forms/select/select2.full.min.js') }}"></script>
@endsection
@section('page-script')
  <script src="{{ asset('admin/js/helper.js') }}"></script>

  <script>
      $( document ).ready(function() {

      });
  </script>
@endsection
