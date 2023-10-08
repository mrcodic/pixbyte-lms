<!-- This is the modal -->
<div id="modal-classes" class="negative" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <h2 class="uk-modal-title">Select classroom</h2>
        <x-input id="roomIds" class="uk-width-1-1" type="text" name="roomIds"  hidden/>
        <div uk-grid>
            <div class="uk-margin-small-right inline-block left uk-width-4-4">
                <select multiple class="uk-select " id="class_room_ids" name="class_room_ids[]">
                    <option value="">all</option>
                    @foreach ( $classRooms as $classRoom )
                        <option value=" {{$classRoom->id}} ">{{ $classRoom->title }}</option>
                    @endforeach
                </select>
            </div>

            <div class="uk-text-right uk-width-1-1 uk-margin-top">
                <button class="uk-button uk-button-default uk-modal-close border-radius" type="button">Cancel</button>
                <button class="uk-button uk-button-primary border-radius" id="save_move_room_to_classes" type="button">Add rooms</button>
            </div>
        </div>

    </div>
</div>
