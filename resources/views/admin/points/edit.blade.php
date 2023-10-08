@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Point settings')

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
                    <h4 class="card-title">Edit Point Settings</h4>
                </div>
                <div class="card-body">
                    <form class=""  action="{{route('point.settings.edit')}}" method="POST">
                        @csrf
                        @method('POST')

                        <div class="row">
                            @foreach ($settings as $setting)
                                @if ($setting->type == 'points')
                                    <div class="mb-1 col-6">
                                        <label class="form-label" for="basic-addon-name">{{$setting->main_name}}</label>

                                        <input
                                            type="number"
                                            class="form-control"
                                            name="{{$setting->name}}"
                                            value="{{$setting->value}}"
                                            min="0"
                                        />
                                        @error('name')
                                        <div class="text-danger">{{$message}}</div>
                                        @enderror
                                    </div>
                                @endif
                            @endforeach
                        </div>




                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>


@endsection

