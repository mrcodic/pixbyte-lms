<div class="modal fade" id="redemptionsSettingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-add-new-role">
      <div class="modal-content">
        <div class="modal-header bg-transparent">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body px-5 pb-5" id="flatpickr">
          <div class="text-center mb-4">
            <h1 class="role-title">Date can student redemptions his point </h1>
          </div>
          <!-- Add role form -->
          <form id="redemptionsSettingForm" class="row" onsubmit="return false">
            <div class="col-12">
                <label class="form-label" for="redemptionsSettingDate">Date Range</label>
                <input type="text" id="redemptionsSettingDate" class="form-control flatpickr-range" placeholder="YYYY-MM-DD to YYYY-MM-DD" value=""/>
                <small class="text-danger classroom_error"></small>
            </div>
            <div class="col-12 text-center mt-2">
              <button type="submit" class="btn btn-primary me-1" id="save_setting">Submit</button>
              <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">
                cancel
              </button>
            </div>
          </form>
          <!--/ Add role form -->
        </div>
      </div>
    </div>
</div>
