<div id="modal-classroom" class="uk-flex-top code-modal negative" uk-modal>
    <div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical">

        <button class="uk-modal-close-default" type="button" uk-close></button>
        <h2 class="uk-modal-title">Enter your code for <span class="code-price" >Classroom</span></h2>

        <form action="{{ route('room.unloack.code') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="classroom_id" id="classroom_id" value="{{request()->id}}">
            <input type="hidden" name="type" id="type" value='subscription'>
            <div class="uk-flex uk-flex-middle" uk-grid>
                <div class="uk-width-2-3">
                    <input class="uk-input" name="code" type="text" placeholder="Enter code" required autofocus>
                </div>
                <div class="uk-width-1-3 pl-s-10" style="padding-left: 10px;">
                    <button class="uk-button uk-button-secondary">Open</button>
                </div>
            </div>
        </form>

    </div>
</div>
