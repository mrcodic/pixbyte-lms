<div id="modal-center" class="uk-flex-top code-modal negative" uk-modal>
    <div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical">

        <button class="uk-modal-close-default" type="button" uk-close></button>
        <h2 class="uk-modal-title">Enter your code for <span class="code-price" >100 L.E</span></h2>

        <form action="{{ route('room.unloack.code') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="quiz_id" id="quiz_id_modal" >
            <input type="hidden" name="room_id" id="room_id_modal" >
            <input type="hidden" name="classroom_id" id="classroom_id" value="{{request()->id}}">
            <input type="hidden" name="grade_id" id="grade_id_modal">
            <div class="uk-flex uk-flex-middle" uk-grid>
                <div class="uk-width-2-3">
                    <input class="uk-input" name="code" type="text" placeholder="Enter room code" required autofocus>
                </div>
                <div class="uk-width-1-3 pl-s-10" style="padding-left: 10px;">
                    <button class="uk-button uk-button-secondary">Open</button>
                </div>
            </div>
        </form>

    </div>
</div>
