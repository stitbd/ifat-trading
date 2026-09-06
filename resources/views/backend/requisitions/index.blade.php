@extends('layouts.backend')

@section('title')
    Requisition
@endsection

@section('content')
    <div class="app-toolbar py-3 py-lg-6">
        <div class="app-container container-fluid">

            <div class="admin-page-header">

                <div class="admin-page-header-title">

                    <span class="icon-box">
                        <i class="bi bi-clipboard-check"></i>
                    </span>

                    <h1>Requisition</h1>

                </div>

                <a href="{{ route('requisition.create') }}" class="btn-admin-primary">

                    <i class="bi bi-plus-lg"></i>

                    Add Requisition

                </a>

            </div>

        </div>
    </div>


    <div id="kt_app_content" class="app-content flex-column-fluid">

        <div id="kt_app_content_container" class="app-container container-fluid">

            <div class="admin-card">


                {{-- Header --}}

                <div class="admin-card-header">

                    <h5>
                        <i class="bi bi-table" style="color:#4361ee;">
                        </i>

                        Requisition List
                    </h5>

                    <div id="requisitionTableButtons"></div>

                </div>


                {{-- Filters --}}

                <div class="row p-3" style="border-bottom:1px solid #eef0f2;">

                    {{-- Wing --}}
                    <div class="col-md-4 mb-2">
                        <select id="filter_wing" class="form-select">
                            <option value="">All Wings</option>
                            @foreach ($wings as $wing)
                                <option value="{{ $wing->id }}">{{ $wing->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Warehouse --}}
                    <div class="col-md-4 mb-2">
                        <select id="filter_warehouse" class="form-select">
                            <option value="">All Warehouses</option>
                            @foreach ($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Requisition Type --}}
                    <div class="col-md-4 mb-2">
                        <select id="filter_requisition_type" class="form-select">
                            <option value="">All Types</option>
                            <option value="local">Local</option>
                            <option value="import">Import</option>
                        </select>
                    </div>

                    {{-- CHANGE: Workflow Status filter notun --}}
                    <div class="col-md-4 mb-2">
                        <select id="filter_workflow_status" class="form-select">
                            <option value="">All Workflow Status</option>
                            <option value="pending">Pending</option>
                            <option value="forwarded_to_sci">Forwarded to SCI</option>
                            <option value="sci_rejected">Rejected by SCI</option>
                            <option value="forwarded_to_om">Forwarded to OM</option>
                            <option value="om_rejected">Rejected by OM</option>
                            <option value="forwarded_to_md">Forwarded to MD</option>
                            <option value="md_approved">Approved by MD</option>
                            <option value="md_rejected">Rejected by MD</option>
                            <option value="cs_generated">CS Generated</option>
                        </select>
                    </div>

                    {{-- From Date --}}
                    <div class="col-md-2 mb-2">
                        <input type="date" id="filter_date_from" class="form-control" placeholder="From Date">
                    </div>

                    {{-- To Date --}}
                    <div class="col-md-2 mb-2">
                        <input type="date" id="filter_date_to" class="form-control" placeholder="To Date">
                    </div>

                    {{-- Filter Button --}}
                    <div class="col-md-3 mb-2">
                        <button id="filter_apply" class="btn-admin-primary w-100">
                            <i class="bi bi-funnel"></i> Filter
                        </button>
                    </div>

                    {{-- Reset --}}
                    <div class="col-md-1 mb-2">
                        <button id="filter_reset" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-x-circle"></i>
                        </button>
                    </div>

                </div>

                {{-- DataTable --}}
                <div class="table-responsive">
                    <table id="requisitionTable" class="display admin-table" style="width:100%;">

                        <thead>

                            <tr>

                                <th>#</th>

                                <th>Requisition No</th>

                                <th>Wing</th>

                                <th>Warehouse</th>

                                <th>Type</th>

                                <th>Total Quantity</th>

                                <th>Date</th>

                                <th>Place of Supply</th>

                                <th>Status</th>

                                {{-- CHANGE: History column notun --}}
                                {{-- <th>History</th> --}}

                                <th>Action</th>

                            </tr>

                        </thead>

                    </table>
                </div>
            </div>

        </div>

    </div>



    {{-- Requisition View Modal --}}

    <div class="modal fade" id="requisitionViewModal" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-xl modal-dialog-centered">

            <div class="modal-content" id="requisitionViewModalContent"
                style="
                    border-radius:12px;
                    border:none;
                    overflow:hidden;
                ">

            </div>

        </div>

    </div>



    <style>
        /*
                                |--------------------------------------------------------------------------
                                | Products column
                                |--------------------------------------------------------------------------
                                */

        .requisition-products {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .requisition-product-item {
            background: #f8f9fa;
            border-radius: 5px;
            padding: 4px 8px;
            font-size: 13px;
        }

        .requisition-product-name {
            font-weight: 600;
            color: #333;
        }

        .requisition-product-qty {
            color: #777;
            margin-left: 5px;
        }

        /*
                                |--------------------------------------------------------------------------
                                | Serial badge
                                |--------------------------------------------------------------------------
                                */

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

        /* CHANGE: status-rejected pill style notun (controller a 'status-rejected' class use hoyeche) */
        .status-pill.status-rejected {
            background: #fdecea;
            color: #e53e3e;
        }

        /* CHANGE: history column style */
        .requisition-history-cell {
            max-width: 220px;
            font-size: 12px;
            line-height: 1.5;
            white-space: normal;
        }
    </style>



    <script>
        $(document).ready(function() {

            /*
            |--------------------------------------------------------------------------
            | Requisition DataTable
            |--------------------------------------------------------------------------
            */

            var table = $('#requisitionTable').DataTable({

                processing: true,

                serverSide: true,

                ajax: {

                    url: '{{ route('requisition.getdata') }}',

                    data: function(d) {

                        d.wing_id = $('#filter_wing').val();
                        d.warehouse_id = $('#filter_warehouse').val();
                        d.requisition_type = $('#filter_requisition_type').val();
                        // CHANGE: notun workflow_status filter pathano hocche
                        d.workflow_status = $('#filter_workflow_status').val();
                        d.date_from = $('#filter_date_from').val();
                        d.date_to = $('#filter_date_to').val();

                    }

                },

                dom: 'Blfrtip',

                /*
                |--------------------------------------------------------------------------
                | Buttons
                |--------------------------------------------------------------------------
                */

                buttons: [

                    {
                        extend: 'excelHtml5',
                        text: '<i class="bi bi-file-earmark-excel-fill"></i> Excel',
                        title: 'Requisition List',
                        exportOptions: {
                            // CHANGE: history column (index 9) add hoyeche, tai action column ekhon 10
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
                            format: {
                                body: function(data, row, column) {
                                    return $('<div>')
                                        .html(data)
                                        .text()
                                        .trim();
                                }
                            }
                        }
                    },

                    {
                        extend: 'print',
                        text: '<i class="bi bi-printer-fill"></i> Print',
                        title: 'Requisition List',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
                            format: {
                                body: function(data, row, column) {
                                    return $('<div>')
                                        .html(data)
                                        .text()
                                        .trim();
                                }
                            }
                        }
                    }

                ],

                /*
                |--------------------------------------------------------------------------
                | Columns
                |--------------------------------------------------------------------------
                */

                columns: [

                    /* Serial */
                    {
                        data: null,
                        name: 'serial_number',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            let number = meta.row + meta.settings._iDisplayStart + 1;
                            return type === 'display' ?
                                '<span class="serial-badge">' + number + '</span>' :
                                number;
                        }
                    },

                    /* Requisition No */
                    {
                        data: 'requisition_no',
                        name: 'requisition_no'
                    },

                    /* Wing */
                    {
                        data: 'wing_name',
                        name: 'wing.name'
                    },

                    /* Warehouse */
                    {
                        data: 'warehouse_name',
                        name: 'warehouse.name'
                    },

                    /* Type */
                    {
                        data: 'requisition_type_name',
                        name: 'requisition_type'
                    },

                    /* Total Quantity */
                    {
                        data: 'total_quantity',
                        name: 'total_quantity'
                    },

                    /* Date */
                    {
                        data: 'date',
                        name: 'date'
                    },

                    /* Place of Supply */
                    {
                        data: 'place_of_supply',
                        name: 'place_of_supply'
                    },

                    /* Status (ekhon workflow_status label dekhabe) */
                    {
                        data: 'status',
                        name: 'status',
                        render: function(data, type, row) {
                            if (type === 'export') {
                                return $(data).text();
                            }
                            return data;
                        }
                    },

                    /* CHANGE: History column notun */
                    // {
                    //     data: 'workflow_history',
                    //     name: 'workflow_history',
                    //     orderable: false,
                    //     searchable: false,
                    //     render: function(data, type, row) {
                    //         if (type === 'export') {
                    //             return $(data).text();
                    //         }
                    //         return '<div class="requisition-history-cell">' + data + '</div>';
                    //     }
                    // },

                    /* Action */
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }

                ],

                /*
                |--------------------------------------------------------------------------
                | Order
                |--------------------------------------------------------------------------
                */

                order: [
                    [1, 'desc']
                ]

            });


            /*
            |--------------------------------------------------------------------------
            | Move Buttons
            |--------------------------------------------------------------------------
            */

            table.buttons().container().appendTo('#requisitionTableButtons');


            /*
            |--------------------------------------------------------------------------
            | Filters
            |--------------------------------------------------------------------------
            */

            $('#filter_apply').on('click', function() {
                table.ajax.reload();
            });


            /*
            |--------------------------------------------------------------------------
            | Reset Filter
            |--------------------------------------------------------------------------
            */

            $('#filter_reset').on('click', function() {

                $(
                        '#filter_wing, ' +
                        '#filter_warehouse, ' +
                        '#filter_requisition_type, ' +
                        '#filter_workflow_status' // CHANGE: reset a workflow_status o add
                    )
                    .val('');

                $('#filter_date_from, #filter_date_to').val('');

                table.ajax.reload();

            });


            /*
            |--------------------------------------------------------------------------
            | Edit
            |--------------------------------------------------------------------------
            */

            $(document).on('click', '.edit', function() {
                let dataId = $(this).data('id');
                window.location.href = "{{ route('requisition.edit', ':id') }}".replace(':id', dataId);
            });


            /*
            |--------------------------------------------------------------------------
            | Delete
            |--------------------------------------------------------------------------
            */

            $(document).on('click', '.delete', function(event) {

                event.preventDefault();

                let form = $(this).closest('form');

                Swal.fire({
                        title: "Are you sure?",
                        text: "You won't be able to revert this!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "#3085d6",
                        confirmButtonText: "Yes, delete it!"
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });

            });


            /*
            |--------------------------------------------------------------------------
            | View
            |--------------------------------------------------------------------------
            */

            $(document).on('click', '.view', function() {

                let dataId = $(this).data('id');

                $('#requisitionViewModalContent').html(`
                    <div class="modal-body text-center" style="padding:50px;">
                        <div class="spinner-border" style="color:#4361ee;" role="status"></div>
                        <div class="mt-3" style="color:#8a8a9a;">Loading requisition details...</div>
                    </div>
                `);

                $('#requisitionViewModal').modal('show');

                $.ajax({
                    url: "{{ route('requisition.view', ':id') }}".replace(':id', dataId),
                    type: 'GET',
                    success: function(response) {
                        $('#requisitionViewModalContent').html(response);
                    },
                    error: function(xhr) {
                        $('#requisitionViewModal').modal('hide');
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.message ||
                                'Unable to load requisition details!'
                        });
                    }
                });

            });


            /*
            |--------------------------------------------------------------------------
            | CHANGE: Generic helper - workflow action POST call
            | url: route name er JS diye generate kora, id: requisition id
            | remarksRequired: true hole Swal input diye remarks nebe (reject er khetre)
            |--------------------------------------------------------------------------
            */

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


            /*
            |--------------------------------------------------------------------------
            | CHANGE: Forward (GU -> SCI)
            |--------------------------------------------------------------------------
            */

            $(document).on('click', '.forward-btn', function() {
                let dataId = $(this).data('id');
                let url = "{{ url('requisitions') }}/" + dataId + "/forward";
                callWorkflowAction(url, 'Forward this requisition?',
                    'It will be sent to Supply Chain Incharge.', false);
            });


            /*
            |--------------------------------------------------------------------------
            | CHANGE: SCI Approve / Reject
            |--------------------------------------------------------------------------
            */

            $(document).on('click', '.sci-approve-btn', function() {
                let dataId = $(this).data('id');
                let url = "{{ url('requisitions') }}/" + dataId + "/sci-approve";
                callWorkflowAction(url, 'Approve this requisition?',
                    'It will be forwarded to Operation Manager.', false);
            });

            $(document).on('click', '.sci-reject-btn', function() {
                let dataId = $(this).data('id');
                let url = "{{ url('requisitions') }}/" + dataId + "/sci-reject";
                callWorkflowAction(url, 'Reject this requisition?', '', true);
            });


            /*
            |--------------------------------------------------------------------------
            | CHANGE: OM Approve / Reject
            |--------------------------------------------------------------------------
            */

            $(document).on('click', '.om-approve-btn', function() {
                let dataId = $(this).data('id');
                let url = "{{ url('requisitions') }}/" + dataId + "/om-approve";
                callWorkflowAction(url, 'Approve this requisition?', 'It will be forwarded to MD.', false);
            });

            $(document).on('click', '.om-reject-btn', function() {
                let dataId = $(this).data('id');
                let url = "{{ url('requisitions') }}/" + dataId + "/om-reject";
                callWorkflowAction(url, 'Reject this requisition?', '', true);
            });


            /*
            |--------------------------------------------------------------------------
            | CHANGE: MD Approve / Reject
            |--------------------------------------------------------------------------
            */

            $(document).on('click', '.md-approve-btn', function() {
                let dataId = $(this).data('id');
                let url = "{{ url('requisitions') }}/" + dataId + "/md-approve";
                callWorkflowAction(url, 'Approve this requisition?',
                    'General User will be able to generate CS.', false);
            });

            $(document).on('click', '.md-reject-btn', function() {
                let dataId = $(this).data('id');
                let url = "{{ url('requisitions') }}/" + dataId + "/md-reject";
                callWorkflowAction(url, 'Reject this requisition?', '', true);
            });


            /*
            |--------------------------------------------------------------------------
            | CHANGE: Generate CS (GU, MD approve er por)
            |--------------------------------------------------------------------------
            */

            $(document).on('click', '.generate-cs-btn', function() {
                let dataId = $(this).data('id');
                let url = "{{ url('requisitions') }}/" + dataId + "/generate-cs";
                callWorkflowAction(url, 'Generate CS?', 'This will finalize the requisition.', false);
            });

        });


        /*
        |--------------------------------------------------------------------------
        | Success Message
        |--------------------------------------------------------------------------
        */

        @if (request()->has('added-successfully'))

            $(document).ready(function() {

                Swal.fire({
                    icon: "success",
                    title: "{{ request('added-successfully') }}",
                    showConfirmButton: false,
                    timer: 2000
                });

                const url = new URL(window.location.href);
                url.searchParams.delete('added-successfully');
                window.history.replaceState(null, '', url);

            });
        @endif
    </script>
@endsection
