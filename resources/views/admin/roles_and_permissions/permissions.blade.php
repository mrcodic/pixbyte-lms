@foreach($tablPermissions as $tbl)
    <tr>
    <td class="text-nowrap fw-bolder">{{$tbl}}</td>
    <td>
        <div class="d-flex">
            @foreach(tblPermissions($tbl,$roles,$roles->type) as $pre)
                <div class="form-check me-3 me-lg-5">
                    <input class="form-check-input" name="ids[]" value="{{$pre->name}}" type="checkbox" id="{{$pre->name}}" {{ isset($roles) && $roles->hasPermissionTo($pre->name) ? 'checked' :null}} />
                    <label class="form-check-label" for="{{$pre->name}}"> {{$pre->name}} </label>
                </div>
            @endforeach
        </div>
    </td>
</tr>

@endforeach
