@extends('admin.layouts.contentLayoutMaster')

@section('title', ' Edit Profile')

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
                        <h4 class="card-title">Edit Profile</h4>
                    </div>
                    <div class="card-body">
                        <form class="" action="{{route('profile.update')}}" method="POST">
                            @csrf
                            @method('POST')

                            <div class="row">
                                <div class="mb-1 col-6">
                                    <label class="form-label" for="basic-addon-name">Name</label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        name="name"
                                        value="{{auth('admin')->user()->name}}"
                                        min="0"
                                    />
                                    @error('name')
                                    <div class="text-danger">{{$message}}</div>
                                    @enderror
                                </div>

                                <div class="mb-1 col-6">
                                    <label class="form-label" for="basic-addon-name">Phone</label>

                                    <input
                                        type="number"
                                        class="form-control"
                                        name="phone"
                                        value="{{auth('admin')->user()->phone}}"
                                        min="0"
                                    />
                                    @error('phone')
                                    <div class="text-danger">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-6">
                                    <label class="form-label" for="basic-addon-name">Password</label>

                                    <input
                                        type="password"
                                        class="form-control"
                                        name="password"
                                    />
                                    @error('name')
                                    <div class="text-danger">{{$message}}</div>
                                    @enderror
                                </div>
                            </div>


                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

