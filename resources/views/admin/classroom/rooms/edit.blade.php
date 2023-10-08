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
                    <h4 class="card-title">Edit room</h4>
                </div>
                <div class="card-body">
                    <form class="" action="{{route('admin.room.update',$room)}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        {{-- @method('PATCH') --}}
                        <input type="hidden" name="page_tbl" value="{{$page_tbl}}">
                        <div class="mb-1">
                            <label class="form-label" for="title">Title</label>
                            <input
                                type="text"
                                class="form-control dt-title"
                                id="title"
                                placeholder=""
                                name="title"
                                value="{{$room->title}}"
                            />
                            <small class="text-danger title_error"></small>
                        </div>

                        <div class="row">
                            <div class="mb-1 col-6">
                                <label class="form-label" for="class_room_id">Classrooms</label>
                                <select id="class_room_id" class="select2 form-select" name="class_room_id[]" multiple>
                                    <option readonly>select...</option>
                                    @php
                                        $classromsIds = $room->classroom->pluck('id')->toArray();
                                    @endphp
                                    @foreach($classRooms as $classRoom)
                                        <option value="{{$classRoom->id}}" {{in_array($classRoom->id, $classromsIds) ?'selected':null}}>{{$classRoom->title}}</option>
                                    @endforeach
                                </select>
                                <small class="text-danger title_error"></small>
                            </div>
                            <div class="mb-1 col-6"  >
                                <label class="form-label" for="unlock_after">Instructor</label>
                                <select id="instructor_id" class="select2 form-select" name="user_id" >
                                    <option readonly>select...</option>
                                    @foreach($instructors as $instructor)
                                        <option value="{{$instructor->id}}" {{$room->user_id == $instructor->id ?'selected' :null}}>{{$instructor->name}}</option>
                                    @endforeach
                                </select>
                                <small class="text-danger unlock_after_error"></small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-1 col-6"  >
                                <label class="form-label" for="unlock_after">Lock After (days)</label>
                                <input type="number" id="unlock_after" class="form-control " value="{{$room->unlock_after}}" name="unlock_after"></select>
                                <small class="text-danger unlock_after_error"></small>
                            </div>
                            <div class="mb-1 col-6"  >
                                <label class="form-label" for="price">Room Price</label>
                                <input type="number" id="price" class="form-control " value="{{$room->price}}"  name="price"></select>
                                <small class="text-danger price_error"></small>
                            </div>
                        </div>

                        <div class="mb-1" >
                            <label class="form-label" for="description">Description</label>
                            <textarea name="description" class="form-control" id="description" rows="4">{{$room->description}}</textarea>
                            <small class="text-danger description_error"></small>
                        </div>

                        {{-- <div class="mb-2">

                            <input class="form-control" type="hidden" id="materialIds" name="materialIds[]">

                            <label class="form-label" for="material"> Room Metrial</label>
                            <form action="{{route('admin.room.upload_material')}}" class="dropzone dropzone-area"  maxFilesize="1024" id="dpz-upload-material">
                                @csrf
                                <div class="dz-message">Drop files here or click to upload.</div>
                            </form>
                        </div> --}}

                        <button type="submit" class="btn btn-primary me-1 data-submit" id="save_room">Submit</button>

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
