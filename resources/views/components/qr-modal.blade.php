<div id="qr-modal" class="uk-flex-top qr-modal negative" uk-modal>
    <div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <div class="qr-wrapper">
            {!! QrCode::size(300)->generate(auth()->user()->name_id) !!}

        </div>
    </div>
</div>
