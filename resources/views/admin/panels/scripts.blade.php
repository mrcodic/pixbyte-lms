<!-- BEGIN: Vendor JS-->
<script src="{{ asset('admin/vendors/js/vendors.min.js') }}"></script>
<!-- BEGIN Vendor JS-->

<script src="{{ asset('admin/vendors/js/file-uploaders/dropzone.min.js')}}"></script>
<script src="{{ asset('admin/js/scripts/forms/form-file-uploader.js')}}"></script>
<!-- BEGIN: Page Vendor JS-->
<script src="{{asset('admin/vendors/js/ui/jquery.sticky.js')}}"></script>

@yield('vendor-script')
<!-- END: Page Vendor JS-->
<!-- BEGIN: Theme JS-->
<script src="{{ asset('admin/js/core/app-menu.js') }}"></script>
<script src="{{ asset('admin/js/core/app.js') }}"></script>

<!-- custome scripts file for user -->
<script src="{{ asset('admin/js/core/scripts.js') }}"></script>
<script src="{{ asset('admin/js/scripts.js') }}"></script>

<script src="{{ asset('admin/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('admin/vendors/js/extensions/polyfill.min.js') }}"></script>
<script src="{{ asset('admin/js/scripts/extensions/ext-component-sweet-alerts.js') }}"></script>
<script src="{{ asset('admin/vendors/js/extensions/toastr.min.js')}}"></script>
<script src="{{ asset('admin/vendors/js/forms/wizard/bs-stepper.min.js')}}"></script>
<script src="{{ asset('admin/js/scripts/forms/form-wizard.js')}}"></script>

{{-- <script src="{{ asset('admin/vendors/js/pickers/pickadate/picker.js')}}"></script>
<script src="{{ asset('admin/vendors/js/pickers/pickadate/picker.date.js')}}"></script>
<script src="{{ asset('admin/vendors/js/pickers/pickadate/picker.time.js')}}"></script>
<script src="{{ asset('admin/vendors/js/pickers/pickadate/legacy.js')}}"></script> --}}
{{-- <script src="{{ asset('admin/js/scripts/forms/form-repeater.min.js')}}"></script> --}}
<script src="{{ asset('admin/vendors/js/forms/select/select2.full.min.js')}}"></script>

<script src="{{ asset('admin/vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
<script src="{{ asset('admin/js/scripts/forms/pickers/form-pickers.js')}}"></script>

@if($configData['blankPage'] === false)
<script src="{{ asset('admin/js/scripts/customizer.js') }}"></script>
@endif

@if(Session::has('message'))
    <script>
        var type = "{{ Session::get('alert-type','info') }}"
        Swal.fire({
            text: '{{ Session::get("message") }}',
            icon: type,
            toast: true,
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: false,
            position: 'top-right'
        })
    </script>
@endif
<!-- END: Theme JS-->
<!-- BEGIN: Page JS-->
@yield('page-script')
<!-- END: Page JS-->
