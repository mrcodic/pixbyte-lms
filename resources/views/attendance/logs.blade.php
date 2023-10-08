@extends('layouts.app')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/rowreorder/1.2.8/css/rowReorder.dataTables.min.css" />
  <style>
    .student{
        background: #3c9bb9;
    }
  </style>
@endsection
@section('title', 'Logs')


@section('body')

    <div class="wrapper-page-light f-height">
        {{-- Instructor sidebar --}}
        <x-instructor-sidebar />
        <!-- container header -->
        <div class="header-wrap page-dark">
            <div class="uk-container uk-container-expand rm-expand">
                <!-- navbar -->
                @include('layouts.navigation')
                <div class="page-header uk-margin-medium-top uk-margin-medium-bottom breadcrumb" uk-grid>
                    <div class="uk-width-expand">
                        <h3 class="uk-margin-remove-bottom title-add">Activity log Attendance</h3>
                    </div>

                    <div class="line divider"></div>
                </div>
            </div>

        </div>
        <div class="uk-container uk-container-expand rm-expand uk-margin-medium-top uk-margin-left" id="attendance" >
                <div class="uk-overflow-auto uk-margin-medium-top x-scrollbar">
                    <input type="hidden" id="table_page" value="">
                    <div>
                    <table class="uk-table uk-table-hover uk-table-middle uk-table-divider rooms small-entries" id="student_attendance_table" style="width: 100%;">
                        <input type="hidden" name="student_id" id="student_id" value="">
                        @include('components.search-datatable',['id'=>'filter_logs'])

                        <thead>
                    <tr>
                        <th class="">Student</th>
                        <th class="">Name id</th>
                        <th class="">Room</th>
                        <th class="">Classroom</th>
                        <th class="">Take by</th>
                        <th class="">Desc</th>
                        <th class="">Created at</th>

                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
{{--    @endif--}}
    </div>
@endsection
    @section('script')


        <script>

            $( document ).ready(function() {
                var student_attendance_table
                student_attendance_table =  $('#student_attendance_table').DataTable({
                    processing: true,
                    serverSide: true,
                    lengthMenu: [5, 10, 20, 50, 100],
                    pageLength: 20,
                    responsive: true,
                    searching:false,
                    dom: 'Blfrtip',
                    order: [[6, 'desc']],
                    ajax: {
                        url: "{{route('get-logs-attendance-data')}}",
                        data: function (d) {
                            d.name_id=$('#filter_logs').val();
                        },
                        beforeSend: function () {
                            $('#table_page').val($('#student_attendance_table').DataTable().page())
                        }
                    },
                    "language": {
                        "processing":
                            `<div class="db-loader">
                        <span class='fa-stack fa-lg'>
                            <i class='fa fa-spinner fa-spin fa-stack-2x fa-fw'></i>
                        </span>Loading ... </div>`,
                    },
                    columns: [
                        {data: 'student', name: 'student', className: ' uk-text-left'  },
                        {data: 'name_id', name: 'name_id', className: ' uk-text-left'  },
                        {data: 'room', name: 'room', className: ' uk-text-left'  },
                        {data: 'classroom', name: 'classroom', className: ' uk-text-left'  },
                        {data: 'take_action', name: 'take_action', className: ' uk-text-left'  },
                        {data: 'desc', name: 'desc', className: ' uk-text-left'  },
                        {data: 'created_at', name: 'created_at', className: ' uk-text-left'  },
                    ],

                });


                $('#filter_logs').on('keyup',function (){
                    student_attendance_table.ajax.reload();
                });
            });
        </script>

    @endsection
