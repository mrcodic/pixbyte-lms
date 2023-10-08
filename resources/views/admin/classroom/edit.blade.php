@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Class Room')

@section('vendor-style')

@endsection
@section('page-style')
  <!-- Page css files -->
@endsection

@section('content')

<section class="bs-validation">
    <div class="row">
        <!-- Bootstrap Validation -->
        <div class="col-md-12 col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit ClassRoom</h4>
                </div>
                <div class="card-body">
                    <form class="" action="{{route('admin.classrooms.update',$class->id)}}" method="POST" enctype="multipart/form-data">
                      @csrf
                        {{-- @method('PATCH') --}}
                        <input type="hidden" name="page_tbl" value="{{$page_tbl}}">
                        <div class="modal-body flex-grow-1" id="form_data">

                            <div class="row">
                                <div class="mb-1 col-9">
                                    <label class="form-label" for="title">Title</label>
                                    <input
                                        type="text"
                                        class="form-control dt-title"
                                        id="title"
                                        placeholder=""
                                        name="title"
                                        value="{{$class->title}}"
                                    />
                                    <small class="text-danger title_error"></small>
                                </div>

                                <div class="mb-1 col-3 " >
                                    <label class="form-label" for="user_id">Instructor</label>
                                    <select id="user_id" class="select2 form-select" name="user_id">
                                        <option readonly disabled selected>select...</option>
                                        @foreach($instructors as $instructor)
                                            <option value="{{$instructor->id}}" {{$class->user->id == $instructor->id ? 'selected' :null}}>{{$instructor->name}}</option>
                                        @endforeach
                                    </select>
                                    <small class="text-danger user_id_error"></small>
                                </div>
                            </div>

                            {{-- <div class="mb-1" id="div_classrooms" >
                                <label class="form-label" for="userId">Instructor</label>
                                <select id="userId" class="select2 form-select" name="userId">
                                            <option readonly disabled selected>select...</option>
                                    @foreach($instructors as $instructor)
                                            <option value="{{$instructor->id}}">{{$instructor->name}}</option>
                                    @endforeach
                                </select>
                                <small class="text-danger userId_error"></small>
                            </div> --}}
                            <div class="row">
                                <div class="mb-1 col-3 col-md-6" id="div_role"  >
                                    <label class="form-label" for="grade_id">Grade</label>
                                    <select id="grade_id" class="select2 form-select" name="grade_id">
                                        <option readonly disabled selected>select...</option>
                                        @foreach($grades as $grade)
                                            <option value="{{$grade->id}}" {{$class->grade->grade->id == $grade->id ? 'selected' :null}}>{{$grade->name}}</option>
                                        @endforeach
                                    </select>
                                    <small class="text-danger grade_id_error"></small>
                                </div>
                                <div class="mb-1 col-3 col-md-6" id="div_role"  >
                                    <label class="form-label" for="subject_id">Subject</label>
                                    <select id="subject_id" class="select2 form-select" name="subject_id">
                                        <option readonly disabled selected>select...</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{$subject->id}}" {{$class->subject_id == $subject->id ? 'selected' :null}}>{{$subject->name}}</option>
                                        @endforeach
                                    </select>
                                    <small class="text-danger subject_id_error"></small>
                                </div>
                                <div class="mb-1 col-3 col-md-6" id="div_role"  >
                                    <label class="form-label" for="room_scheduel">Room Schedule</label>
                                    <select id="room_scheduel" class="select2 form-select" name="room_scheduel">
                                        <option readonly disabled selected>select...</option>
                                        @foreach($schedules as $schedule)
                                            <option value="{{$schedule->id}}" {{$class->room_scheduel == $schedule->id ? 'selected' :null}}>{{$schedule->name}}</option>
                                        @endforeach
                                    </select>
                                    <small class="text-danger room_scheduel_error"></small>
                                </div>

                                <div class="mb-1 col-3 col-md-6" id="div_role"  >
                                    <label class="form-label" for="subject_id">Absence rooms</label>
                                    <input type="number" id="absence_times" class="form-control " value="{{$class->absence_times}}" name="absence_times"></select>
                                    <small class="text-danger absence_times_error"></small>
                                </div>
                            </div>
                            <div class="mb-1" id="div_role"  >
                                <label class="form-label" for="description">description</label>
                                <textarea name="description" class="form-control" id="description" rows="4">{{$class->description}}</textarea>
                                <small class="text-danger description_error"></small>
                            </div>

                            <div class="mb-2">
                                <label class="form-label" for="cover"> Cover</label>
                                <input class="form-control" type="file" id="cover" name="cover">
                                <small class="text-danger cover_error"></small>
                            <img  width="100"class="mt-2" @if($class->cover) src="/uploads/profile_images/{{$class->cover}}" @else  src="/uploads/no-image/no-image.jpg"  @endif>
                            </div>

                            <button type="submit" class="btn btn-primary me-1 data-submit" id="save_classroom">Submit</button>
                            {{-- <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button> --}}
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</section>


@endsection

@section('vendor-script')
  <!-- Vendor js files -->
  <script src="{{ asset('admin/vendors/js/forms/select/select2.full.min.js') }}"></script>
@endsection
@section('page-script')
  <script src="{{ asset('admin/js/helper.js') }}"></script>

@endsection
