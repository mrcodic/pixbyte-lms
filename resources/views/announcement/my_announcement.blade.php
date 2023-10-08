@extends('layouts.app')
@section('title', 'My Announcement')
@section('css')
    <style>
        .uk-card-announcement-default {
            background: #d8d8d8;
            border-radius: 10px;
            padding: 5px;
        }
        .announcement .uk-card-announcement-default {
            padding: 10px 20px;
        }
    </style>
@endsection

@section('body')

    <div class="wrapper-page-light f-height">
        {{-- Instructor sidebar --}}
{{--        <x-instructor-sidebar />--}}
        <!-- container header -->
        <div class="header-wrap page-dark">
            <div class="uk-container uk-container-expand rm-expand">
                <!-- navbar -->
                @include('layouts.navigation')
                <div class="page-header uk-margin-medium-top uk-margin-medium-bottom breadcrumb" uk-grid>
                    <div class="uk-width-expand">
                        <h3 class="uk-margin-remove-bottom title-add">My Announcement</h3>
                    </div>

                    <div class="line divider"></div>
                </div>

            </div>

        </div>
        <div class="uk-container uk-container-medium uk-margin-medium-top announcement">
{{--            <div class="uk-width-auto outline-link uk-margin-bottom uk-text-right">--}}
{{--                <button id="clear_all_announcement" class="uk-button uk-button-default outline uk-button-small border-radius clear-all-s">Clear All</button>--}}
{{--            </div>--}}
            <div class="uk-child-width-expand@s" uk-grid>
                <div class="notifcation-wrapper uk-margin-small-bottom">
                    <ul uk-accordion="multiple: true">
                        @forelse($announcements as $announcement)
                        <li class="uk-open uk-card uk-card-announcement-default">
                            <a class="uk-accordion-title" href="#">{{$announcement->announcement->name}}</a>
                            <div class="uk-accordion-content">
                                <div style="color: #000000;padding: 8px;">
                                    {!! $announcement->announcement->desc !!}
                                </div>
                                <div>
                                    <a class="announce-download" href="{{$announcement->announcement->getMedia('file')->first() ?str_replace('//storage','/storage',$announcement->announcement->getMedia('file')->first()->getUrl()):''}}" download>
                                        Download Material <span uk-icon="cloud-download"></span>
                                    </a>
                                </div>
                            </div>
                            <br>
                            <div class="uk-text-right">
                                <span  style="color: #644040">{{$announcement->announcement->created_at->diffForHumans()}}</span>

                            </div>

                        </li>
                        @empty
                            <li class=" uk-card-announcement-default">
                                <a class="uk-accordion-title" href="#">No Found data</a>
                            </li>
                        @endif

                    </ul>

                </div>

            </div>

        </div>
    </div>
@endsection
