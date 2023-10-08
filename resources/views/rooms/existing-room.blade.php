<div id="modal-generate" class="negative" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <form action="{{ route('room.existing') }}" method="POST" class="room-form">
            @csrf

        <div class="uk-margin uk-width-1-1">
            <label class="uk-form-label" for="cover">Rooms List</label>
            <div class=" uk-width-1-1" style="display: grid" uk-form-custom>
                    <x-input id="user_id" class="uk-width-1-1" type="text" name="userId" :value="Auth::user()->id" hidden/>
                    <x-input id="class_room_id" class="uk-width-1-1" type="text" name="class_room_id" :value="request()->classRoomId" hidden/>

                <select multiple class="uk-select @error('check_room')error-border @enderror" id="check_room" name="check_room[]">
                    @foreach ( $rooms as $room )
                        <option value=" {{$room->id}}" @if(in_array($room->id,$roomsExist)) selected @endif>{{ $room->title }}</option>
                    @endforeach
                </select>

            </div>
        </div>
        <div class="uk-modal-footer uk-text-right">
            <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
            <button class="uk-button uk-button-primary" type="submit">Save</button>
        </div>
        </form>

      </div>
    </div>

