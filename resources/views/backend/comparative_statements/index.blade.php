@extends('layouts.backend')

@section('title')
    Comparative Statements
@endsection

@section('content')
    <div class="app-toolbar py-3 py-lg-6">
        <div class="app-container container-fluid">
            <div class="admin-page-header">
                <div class="admin-page-header-title">
                    <span class="icon-box"><i class="bi bi-file-earmark-bar-graph"></i></span>
                    <h1>Comparative Statements</h1>
                </div>
            </div>

        </div>
    </div>

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            <div class="admin-card">

                <div class="admin-card-header">
                    <h5><i class="bi bi-table" style="color:#4361ee;"></i> CS List</h5>
                    <div id="csTableButtons"></div>
                </div>




                {{-- Filters --}}
                <div class="row p-3" style="border-bottom:1px solid #eef0f2;">
                    <div class="col-md-4 mb-2">
                        <select id="filter_requisition" class="form-select">
                            <option value="">All Requisitions</option>
                            @foreach ($requisitions as $requisition)
                                <option value="{{ $requisition->id }}">{{ $requisition->requisition_no }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <input type="date" id="filter_date_from" class="form-control" placeholder="From Date">
                    </div>
                    <div class="col-md-3 mb-2">
                        <input type="date" id="filter_date_to" class="form-control" placeholder="To Date">
                    </div>
                    <div class="col-md-1 mb-2">
                        <button id="filter_apply" class="btn-admin-primary w-100">
                            <i class="bi bi-funnel"></i>
                        </button>
                    </div>
                    <div class="col-md-1 mb-2">
                        <button id="filter_reset" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-x-circle"></i>
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="csTable" class="display admin-table" style="width:100%;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Wing</th>
                                <th>Requisition No</th>
                                <th>Requisition Date</th>
                                <th>CS No</th>
                                <th>CS Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- CS View Modal --}}
    <div class="modal fade" id="csViewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content" id="csViewModalContent" style="border-radius:12px;border:none;overflow:hidden;">
            </div>
        </div>
    </div>

    <style>
        .serial-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 28px;
            height: 28px;
            background: #eef2ff;
            color: #4361ee;
            border-radius: 6px;
            font-weight: 600;
        }
    </style>

    <script>
        $(document).ready(function() {

            var table = $('#csTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('comparative-statement.getdata') }}',
                    data: function(d) {
                        d.requisition_id = $('#filter_requisition').val();
                        d.date_from = $('#filter_date_from').val();
                        d.date_to = $('#filter_date_to').val();
                    }
                },
                dom: 'Blfrtip',
                buttons: [{
                        extend: 'excelHtml5',
                        text: '<i class="bi bi-file-earmark-excel-fill"></i> Excel',
                        title: 'Comparative Statement List',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6],
                            format: {
                                body: function(data) {
                                    return $('<div>').html(data).text().trim();
                                }
                            }
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="bi bi-printer-fill"></i> Print',
                        title: 'Comparative Statement List',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6],
                            format: {
                                body: function(data) {
                                    return $('<div>').html(data).text().trim();
                                }
                            }
                        }
                    }
                ],
                columns: [{
                        data: null,
                        name: 'serial_number',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            let number = meta.row + meta.settings._iDisplayStart + 1;
                            return type === 'display' ?
                                '<span class="serial-badge">' + number + '</span>' : number;
                        }
                    },
                    {
                        data: 'wing',
                        name: 'wing'
                    },
                    {
                        data: 'requisition_no',
                        name: 'requisition.requisition_no'
                    },
                    {
                        data: 'requisition_date',
                        name: 'requisition_date'
                    },
                    {
                        data: 'cs_no',
                        name: 'cs_no'
                    },

                    {
                        data: 'cs_date_formatted',
                        name: 'cs_date'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [1, 'desc']
                ]
            });

            table.buttons().container().appendTo('#csTableButtons');

            function callWorkflowAction(url, confirmTitle, confirmText, remarksRequired) {

                if (remarksRequired) {

                    Swal.fire({
                        title: confirmTitle,
                        input: 'textarea',
                        inputLabel: 'Remarks (optional)',
                        inputPlaceholder: 'Reason / note...',
                        showCancelButton: true,
                        confirmButtonText: 'Submit',
                        confirmButtonColor: '#d33',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            sendWorkflowRequest(url, result.value);
                        }
                    });

                } else {

                    Swal.fire({
                        title: confirmTitle,
                        text: confirmText,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Yes',
                        confirmButtonColor: '#4361ee',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            sendWorkflowRequest(url, null);
                        }
                    });

                }
            }

            function sendWorkflowRequest(url, remarks) {

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        remarks: remarks
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.success || 'Action completed successfully!',
                            showConfirmButton: false,
                            timer: 1800
                        });
                        table.ajax.reload(null, false);
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.message || 'Something went wrong!'
                        });
                    }
                });
            }

            $(document).on('click', '.cs-create-po-btn', function() {
                Swal.fire('Coming Soon', 'PO creation feature is under development.', 'info');
            });

            $(document).on('click', '.cs-create-pi-btn', function() {
                Swal.fire('Coming Soon', 'PI creation feature is under development.', 'info');
            });


            /*
            |--------------------------------------------------------------------------
            | CHANGE: Forward (GU -> SCI)
            |--------------------------------------------------------------------------
            */

            $(document).on('click', '.cs-forward-btn', function() {
                let dataId = $(this).data('id');
                let url = "{{ url('comparative-statements') }}/" + dataId + "/forward";
                callWorkflowAction(url, 'Forward this Comparative Statement?',
                    'It will be sent to Supply Chain Incharge.', false);
            });


            /*
            |--------------------------------------------------------------------------
            | CHANGE: SCI Approve / Reject
            |--------------------------------------------------------------------------
            */

            $(document).on('click', '.cs-sci-approve-btn', function() {
                let dataId = $(this).data('id');
                let url = "{{ url('comparative-statements') }}/" + dataId + "/sci-approve";
                callWorkflowAction(url, 'Approve this Comparative Statement?',
                    'It will be forwarded to Operation Manager.', false);
            });

            $(document).on('click', '.cs-sci-reject-btn', function() {
                let dataId = $(this).data('id');
                let url = "{{ url('comparative-statements') }}/" + dataId + "/sci-reject";
                callWorkflowAction(url, 'Reject this Comparative Statement?', '', true);
            });


            /*
            |--------------------------------------------------------------------------
            | CHANGE: OM Approve / Reject
            |--------------------------------------------------------------------------
            */

            $(document).on('click', '.cs-om-approve-btn', function() {
                let dataId = $(this).data('id');
                let url = "{{ url('comparative-statements') }}/" + dataId + "/om-approve";
                callWorkflowAction(url, 'Approve this Comparative Statement?',
                    'It will be forwarded to MD.', false);
            });

            $(document).on('click', '.cs-om-reject-btn', function() {
                let dataId = $(this).data('id');
                let url = "{{ url('comparative-statements') }}/" + dataId + "/om-reject";
                callWorkflowAction(url, 'Reject this Comparative Statement?', '', true);
            });


            /*
            |--------------------------------------------------------------------------
            | CHANGE: MD Approve / Reject
            |--------------------------------------------------------------------------
            */

            $(document).on('click', '.cs-md-approve-btn', function() {
                let dataId = $(this).data('id');
                let url = "{{ url('comparative-statements') }}/" + dataId + "/md-approve";
                callWorkflowAction(url, 'Approve this Comparative Statement?',
                    'General User will be able to generate CS.', false);
            });

            $(document).on('click', '.cs-md-reject-btn', function() {
                let dataId = $(this).data('id');
                let url = "{{ url('comparative-statements') }}/" + dataId + "/md-reject";
                callWorkflowAction(url, 'Reject this Comparative Statement?', '', true);
            });

            $('#filter_apply').on('click', function() {
                table.ajax.reload();
            });

            $('#filter_reset').on('click', function() {
                $('#filter_requisition').val('');
                $('#filter_date_from, #filter_date_to').val('');
                table.ajax.reload();
            });

            $(document).on('click', '.view', function() {
                let dataId = $(this).data('id');

                $('#csViewModalContent').html(`
                    <div class="modal-body text-center" style="padding:50px;">
                        <div class="spinner-border" style="color:#4361ee;" role="status"></div>
                        <div class="mt-3" style="color:#8a8a9a;">Loading CS details...</div>
                    </div>
                `);

                $('#csViewModal').modal('show');

                $.ajax({
                    url: "{{ route('comparative-statement.view', ':id') }}".replace(':id', dataId),
                    type: 'GET',
                    success: function(response) {
                        $('#csViewModalContent').html(response);
                    },
                    error: function(xhr) {
                        $('#csViewModal').modal('hide');
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.message ||
                                'Unable to load CS details!'
                        });
                    }
                });
            });

            $(document).on('submit', '.delete-form', function(event) {
                event.preventDefault();
                let form = $(this);

                Swal.fire({
                    title: "Are you sure?",
                    text: "This Comparative Statement will be permanently deleted!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: form.attr('action'),
                            type: 'POST',
                            data: form.serialize(),
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted',
                                    text: response.message,
                                    showConfirmButton: false,
                                    timer: 1800
                                });
                                table.ajax.reload(null, false);
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: xhr.responseJSON?.message ||
                                        'Something went wrong!'
                                });
                            }
                        });
                    }
                });
            });

        });
    </script>
@endsection
