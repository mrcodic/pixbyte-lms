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
                    <h4 class="card-title">Edit lesson</h4>
                </div>
                <div class="card-body">
                    <form class="" action="{{route('admin.lesson.update',$lesson->id)}}" method="POST" enctype="multipart/form-data">
                      @csrf
                        @method('POST')
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
                                        value="{{$lesson->title}}"
                                    />
                                    <small class="text-danger title_error"></small>
                                </div>

                                <div class="mb-1 col-3 " >
                                    <label class="form-label" for="user_id">Instructor</label>
                                    <select id="user_id" class="select2 form-select" name="user_id">
                                        <option readonly disabled selected>select...</option>
                                        @foreach($instructors as $instructor)
                                            <option value="{{$instructor->id}}" {{$lesson->user->id == $instructor->id ? 'selected' :null}}>{{$instructor->name}}</option>
                                        @endforeach
                                    </select>
                                    <small class="text-danger user_id_error"></small>
                                </div>

                            </div>

                            <div class="row">
                                <div class="mb-1 col-6"  >
                                    <label class="form-label" for="url_iframe">Video URL</label>
                                    <input type="text" id="url_iframe" class="form-control " name="url_iframe" value="{{$lesson->url_iframe}}"></select>
                                    <small class="text-danger url_iframe_error"></small>
                                </div>
                                <div class="mb-1 col-6 row"  >
                                    <div class="col-6">
                                        <label class="form-label" for="duration1">Hours</label>
                                        <input id="duration1" class="form-control duration" placeholder="H" min="0" value="{{(int)explode("h", $lesson->duration)[0]}}" type="number" name="duration[]" >
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label" for="duration2">Minutes</label>
                                        <input id="duration2" class="form-control duration" placeholder="M" min="1"  value="{{(int)explode("h", $lesson->duration)[1]}}" type="number" name="duration[]" >
                                    </div>
                                </div>
                            </div>

                            <div class="mb-1" >
                                <label class="form-label" for="description">Description</label>
                                <textarea name="description" class="form-control" id="description" rows="4">{{$lesson->description}}</textarea>
                                <small class="text-danger description_error"></small>
                            </div>

                            <button type="submit" class="btn btn-primary me-1 data-submit" id="save_lesson">Submit</button>
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
