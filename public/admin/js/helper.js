function create_modal(form_data,url,method,modalID,tableId,needRefresh){
    var data;
    var rtn ;
    // console.log(form_data)
    data = new FormData();
    $.each( form_data, function( key, value ) {
        if(value!=undefined||value!=null){
            data.append(`${key}`, value);
        }
    })
    $.ajax({
        url: url,
        type: method,
        data: data,
        dataType:'json',
        contentType: false,
        processData: false,
        async: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
        success: function (res) {
            rtn = res.data;

            $('.modal').modal('hide')
            if(res.status){
                if(tableId!=null)
                    tableId.ajax.reload();

                if(modalID!=null){
                    $(`#${modalID}`).hide();
                    Swal.fire({
                        toast: true,
                        icon: 'success',
                        title: res.message,
                        animation: false,
                        position: 'top-right',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                      });
                }
                if(needRefresh){
                    window.location.reload()
                }
            }
        },
        error:function (reject) {
            rtn = false;
                var errors = $.parseJSON(reject.responseText);
                $.each(errors.errors, function (key, val) {
                    $("." + key + "_error").text(val[0]);
                });
        }
    });

    return rtn;

}
function deleteRow(id,url,tableId,needRefresh){
    let accetp_swal = Swal.fire({
        title: " Are You Sure To Delete ?",
        text: "",
        icon:"warning",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: '#DD6B55',
        confirmButtonText: 'Yes',
        cancelButtonText: "No",
        closeOnConfirm: false,
        closeOnCancel: false
    }).then(function (result){
        if (result.isConfirmed){
            $.ajax({
                url: url,
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                data: {idds: id,'_method':"DELETE"},
                success: function (res) {
                    if(res.status){
                        Swal.fire("Done", "Record delete successful.", "success");

                    }
                    if(tableId!=null)
                       tableId.ajax.reload();

                    if(needRefresh)
                        window.location.reload()
                },
                error:function (res) {
                    Swal.fire("Close!", "Something is wrong, Please try again.", "error");
                }
            });
        } else {
            Swal.fire("Close", "Close Success", "error");
        }
    })


}
function fetch_data(url,idToAppend,method="GET"){
    let action={}
    $.ajax({
        url: url,
        type: method,
        contentType: false,
        processData: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },

        success: function (res) {
            if(res.status){
                $('#appendPermisions').empty();

                var parent
                parent+=`  <tr>
                          <td class="text-nowrap fw-bolder">
                              Administrator Access
                              <span data-bs-toggle="tooltip" data-bs-placement="top" title="Allows a full access to the system">
                        <i data-feather="info"></i>
                      </span>
                          </td>
                          <td>
                              <div class="form-check">
                                  <input class="form-check-input" type="checkbox" id="selectAll" />
                                  <label class="form-check-label" for="selectAll"> Select All </label>
                              </div>
                          </td>
                      </tr>`
                var html
                Object.entries(res.data).forEach(([k, e])=>{
                    html=''

                    e.forEach((item)=>{
                        html+=`
                        <div class="form-check me-3 me-lg-5">
                          <input class="form-check-input" name="ids[]" value="${item}" type="checkbox" id="${item}" />
                          <label class="form-check-label" for="${item}"> ${item} </label>
                        </div>
                        `
                    })
                    parent+=`
                     <tr>
                          <td class="text-nowrap fw-bolder">${k}</td>
                           <td>
                              <div class="d-flex">
                              ${html}
                              </div>
                           </td>
                      </tr>

`
                })
                $('#appendPermisions').append(parent);

            }

            },

        error:function (reject) {
          alert('error')
        }
    });
    return action;
}
