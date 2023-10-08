<!-- BEGIN: Vendor CSS-->
@if ($configData['direction'] === 'rtl' && isset($configData['direction']))
  <link rel="stylesheet" href="{{ asset('admin/vendors/css/vendors-rtl.min.css') }}" />
@else
  <link rel="stylesheet" href="{{ asset('admin/vendors/css/vendors.min.css') }}" />
@endif

@yield('vendor-style')
<!-- END: Vendor CSS-->

<!-- BEGIN: Theme CSS-->
<link rel="stylesheet" href="{{asset('admin/vendors/css/forms/select/select2.min.css')}}">
<link rel="stylesheet" href="{{ asset('admin/css/core.css') }}" />
<link rel="stylesheet" href="{{ asset('admin/css/base/themes/dark-layout.css') }}" />
<link rel="stylesheet" href="{{ asset('admin/css/base/themes/bordered-layout.css') }}" />
<link rel="stylesheet" href="{{ asset('admin/css/base/themes/semi-dark-layout.css') }}" />
<link rel="stylesheet" href="{{ asset('admin/vendors/css/animate/animate.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin/vendors/css/extensions/sweetalert2.min.css') }}">
<link rel="stylesheet" href="{{asset('admin/css/base/plugins/extensions/ext-component-sweet-alerts.css')}}">
<link rel="stylesheet" href="{{asset('admin/css/plugins/extensions/ext-component-toastr.css')}}">
{{-- <link rel="stylesheet" href="{{asset('admin/css/plugins/extensions/toastr.min.css')}}"> --}}

<link rel="stylesheet" href="{{asset('admin/vendors/css/pickers/flatpickr/flatpickr.min.css')}}">
<link rel="stylesheet" href="{{asset('admin/css/plugins/forms/pickers/form-pickadate.css')}}">

<link rel="stylesheet" href="{{asset('admin/vendors/css/forms/wizard/bs-stepper.min.css')}}">
<link rel="stylesheet" href="{{asset('admin/css/plugins/forms/form-wizard.css')}}">

<link rel="stylesheet" href="{{asset('admin/vendors/css/file-uploaders/dropzone.min.css')}}">
<link rel="stylesheet" href="{{asset('admin/css/plugins/forms/form-file-uploader.css')}}">


@php $configData = Helper::applClasses(); @endphp

<!-- BEGIN: Page CSS-->
@if ($configData['mainLayoutType'] === 'horizontal')
  <link rel="stylesheet" href="{{ asset('admin/css/base/core/menu/menu-types/horizontal-menu.css') }}" />
@else
  <link rel="stylesheet" href="{{ asset('admin/css/base/core/menu/menu-types/vertical-menu.css') }}" />
@endif

{{-- Page Styles --}}
@yield('page-style')

<!-- laravel style -->
<link rel="stylesheet" href="{{ asset('admin/css/overrides.css') }}" />

<!-- BEGIN: Custom CSS-->

@if ($configData['direction'] === 'rtl' && isset($configData['direction']))
  <link rel="stylesheet" href="{{ asset('admin/css-rtl/custom-rtl.css') }}" />
  <link rel="stylesheet" href="{{ asset('admin/css-rtl/style-rtl.css') }}" />

@else
  {{-- user custom styles --}}
  <link rel="stylesheet" href="{{ asset('admin/css/style.css') }}" />
@endif
