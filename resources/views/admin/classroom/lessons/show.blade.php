@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Show lesson')

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
                    <h4 class="card-title">{{$lesson->title}}</h4>
                </div>
                <div class="card-body">
                    <section id="media-player-wrapper">
                        <div class="row">
                            <!-- VIDEO -->
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Video</h4>
                                        <div class="video-player" id="plyr-video-player">
                                            <iframe height="500" width="800" src="{{$lesson->url_iframe}}" allowfullscreen allow="autoplay"></iframe>
                                        </div>
                                    </div>
                                    <div class="modal-body flex-grow-1">

                                        <div class="mb-1" >
                                            <div class="row">
                                                <div class="col-6">
                                                    <label class="form-label card-title d-inline    " for="description">Instructor</label>
                                                    <div>{{$lesson->user->name}}</div>
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label card-title d-inline    " for="description">duration</label>
                                                    <div class="">{{$lesson->duration}}</div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!--/ VIDEO -->
                        </div>
                    </section>
                        <input type="hidden" name="page_tbl" value="{{$page_tbl}}">
                        <div class="modal-body flex-grow-1" id="form_data">

                            <div class="mb-1" >
                                <label class="form-label card-title" for="description">Description</label>
                                <div>{{$lesson->description}}</div>
                                <small class="text-danger description_error"></small>
                            </div>

                        </div>
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
