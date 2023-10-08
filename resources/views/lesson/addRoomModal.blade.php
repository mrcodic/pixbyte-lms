<!-- This is the modal -->
<div id="modal-classes" class="negative" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <h2 class="uk-modal-title">Select room</h2>
        <x-input id="lessonIds" class="uk-width-1-1" type="text" name="lessonIds"  hidden/>
        <div uk-grid>
            <div class="uk-margin-small-right inline-block left uk-width-4-4">
                <select multiple class="uk-select  select2" id="room_ids" name="room_ids[]">
                    <option value="">all</option>
                    @foreach ( $rooms as $room )
                        <option value=" {{$room->id}} ">{{ $room->title }}</option>
                    @endforeach
                </select>
            </div>

            <div class="uk-text-right uk-width-1-1 uk-margin-top">
                <button class="uk-button uk-button-default uk-modal-close border-radius" type="button">Cancel</button>
                <button class="uk-button uk-button-primary border-radius" id="save_move_room_to_classes" type="button">Add lessons</button>
            </div>
        </div>

    </div>
</div>
