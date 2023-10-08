@extends('layouts.app')

@section('title', 'Lesson-locked')
@section('body')
    <div class="lock-bg">
        <div class="uk-container uk-container">
            <div class="uk-margin-xlarge-top uk-margin-xlarge-bottom" uk-grid>
                <div class="uk-width-2-3">
                    <h3>This Room Is Locked</h3>
                    <p class="light-color">Please enter your code down there to open the room.</p>
                    <form action="{{ route('room.unloack.code') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="room_id" id="room_id_modal" >
                        <input type="hidden" name="classroom_id" id="classroom_id" value="{{request()->id}}">
                        <input type="hidden" name="grade_id" id="grade_id_modal">
                        <div class="uk-flex uk-flex-middle" uk-grid>
                            <div class="uk-width-2-3">
                                <input class="uk-input" name="code" type="text" placeholder="Enter room code" autofocus>
                            </div>
                            <div class="uk-width-1-3">
                                <button class="uk-button uk-button-secondary">Open room</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="uk-width-expand">
                    <img class="nothing-toshow-img" src="{{asset('img/gang.svg')}}" alt="locked-room">
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footerScripts')
@endsection
