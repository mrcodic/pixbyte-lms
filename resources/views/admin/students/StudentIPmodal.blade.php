<!-- Modal to add new user starts-->
<div class="modal  new-user-modal fade" id="modal-student-ip">
    <div class="modal-dialog modal-lg">
        <form class="add-new-user modal-content pt-0">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
            <div class="modal-body flex-grow-1" id="form_data">
                <div class="mb-1" >
                    <div class="modal-header mb-1">
                        <h5 class="modal-title" id="exampleModalLabel">User Ips</h5>
                    </div>
                    <ul id="ips">

                    </ul>

                    <div class="modal-header mb-1">
                        <h5 class="modal-title" >Last logins</h5>
                    </div>
                   <table class="user-list-table table" id="authLogsTable">
                        <thead class="table-light">
                        <tr>
                            <th >IP address</th>
                            <th >Browser</th>
                            <th >OS</th>
                            <th >Login</th>
                            <th >Logout</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Modal to add new user Ends-->
