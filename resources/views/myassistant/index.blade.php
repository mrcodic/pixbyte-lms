@extends('layouts.app')
@section('title', 'Assistants')

@section('body')

<div class="wrapper-page-light f-height">
    {{-- Instructor sidebar --}}
    <x-instructor-sidebar />
    <!-- container header -->
    <div class="header-wrap page-dark">
        <div class="uk-container uk-container-expand rm-expand">
            <!-- navbar -->
            @include('layouts.navigation')
            <div class="page-header uk-margin-medium-top uk-margin-medium-bottom breadcrumb" uk-grid>
                <div class="uk-width-expand">
                    <h3 class="uk-margin-remove-bottom title-add">My Assistant</h3>
                </div>
{{--                <div class="uk-width-auto">--}}
{{--                    <a href="{{ route('coupon.create')}}" class="uk-button uk-button-primary border-radius uk-padding-remove-t-b"><span class="add-icon uk-icon uk-margin-small-right" uk-icon="icon:plus; ratio: .7"></span>Add New Code</a>--}}
{{--                    <button uk-toggle="target: #modal-generate" class="new-add uk-margin-small-left" id="modal_generate" type="button" ><i class="fa-solid fa-layer-group uk-margin-small-right"></i>Generate Codes</button>--}}
{{--                </div>--}}
                <div class="line divider"></div>
            </div>
        </div>

    </div>

    <div class="uk-container uk-container-expand rm-expand uk-margin-medium-top uk-margin-left">
        <div uk-grid>
            @foreach($assistants as $ass)
            <div class="uk-width-1-3">
                <div class="uk-card uk-card-default classroom-card border-success">
                    <div class="uk-flex uk-padding-small uk-padding-remove-bottom border-radius">
                        <div class="uk-width-1-6">
                            <img class="showImage border-radius" @if(!$ass->profile_image) src="{{ url('uploads/no-image/no-profile-picture.png') }}" @else src="{{ url('uploads/profile_images/'.$ass->profile_image) }}" @endif  alt="avatar">
                        </div>
                        <div class="uk-width-expand uk-margin-left">
                            <div>
                                <h4 class="uk-margin-remove-bottom dark-font inline-block">
                                    Name:</h4>
                                    <h6 class="uk-margin-remove-bottom uk-margin-remove-top dark-font inline-block">
                                        {{$ass->name }}</h6>
                            </div>
                            <div>
                                <h4 class="uk-margin-remove-bottom dark-font inline-block">
                                    Role:</h4>
                                    <h6 class="uk-margin-remove-bottom uk-margin-remove-top dark-font inline-block">
                                    {{@$ass->roles[0]->name}}</h6>
                            </div>
                            <div class="uk-margin-bottom uk-margin-small-top">
                                @isset($ass->roles[0])
                                    @foreach(getpermission($ass->roles[0]->permissions) as $pre)
                                        <span class="dark-font permissions-outline">
                                        {{$pre}}</span>
                                    @endforeach
                                @endisset
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

        </div>
    </div>

{{--    <div class="uk-container uk-container-expand rm-expand uk-margin-medium-top uk-margin-left">--}}
{{--        <div class="uk-child-width-expand@s uk-text-center" uk-grid>--}}
{{--            @foreach($assistants as $ass)--}}
{{--            <div>--}}
{{--                <div class="uk-card uk-card-default uk-card-body">{{$ass->name}}</div>--}}
{{--            </div>--}}
{{--            @endforeach--}}
{{--        </div>--}}
{{--    </div>--}}
</div>
@endsection
@section('script')
    <script>
        $( document ).ready(function() {




        });

    </script>

@endsection
