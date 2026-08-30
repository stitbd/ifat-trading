@extends('layouts.backend')
@section('content')
@section('title')
    Roles List
@endsection

<div class="app-toolbar py-3 py-lg-6">
    <div class="app-container container-fluid">
        <div class="admin-page-header">
            <div class="admin-page-header-title">
                <span class="icon-box"><i class="bi bi-shield-lock"></i></span>
                <h1>Roles List</h1>
            </div>
            <a href="{{ route('role.create') }}" class="btn-admin-primary">
                <i class="bi bi-plus-lg"></i> Create Role
            </a>
        </div>
    </div>
</div>

<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-fluid">
        <div class="admin-card">
            <div class="admin-card-header">
                <h5><i class="bi bi-table" style="color:#4361ee;"></i> Role List</h5>
                <div id="roleTableButtons"></div>
            </div>

            <table id="roleTable" class="display admin-table" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Permission</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var table = $('#roleTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('role.getdata') }}',
            dom: 'Blfrtip', // B = buttons
            buttons: [{
                    extend: 'excelHtml5',
                    text: '<i class="bi bi-file-earmark-excel-fill"></i> Excel',
                    title: 'Role List',
                    exportOptions: {
                        columns: [0, 1, 3] // exclude Permission & Action column
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="bi bi-file-earmark-pdf-fill"></i> PDF',
                    title: 'User List',
                    orientation: 'landscape', // Email কলাম লম্বা হতে পারে
                    pageSize: 'A4',
                    exportOptions: {
                        columns: [0, 1, 3] // exclude Image & Action column
                    },
                    customize: function(doc) {
                        doc.content[1].table.widths = ['8%', '57%',
                            '45%'
                        ];

                        doc.styles.tableHeader = {
                            bold: true,
                            fontSize: 11,
                            color: 'white',
                            fillColor: '#4361ee',
                        };

                        doc.defaultStyle.fontSize = 9;
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="bi bi-printer-fill"></i> Print',
                    title: 'Role List',
                    exportOptions: {
                        columns: [0, 1, 3] // exclude Permission & Action column
                    }
                }
            ],
            columns: [{
                    data: null,
                    name: 'serial_number',
                    orderable: false,
                    searchable: false,
                    render: (data, type, row, meta) =>
                        type === 'display' ?
                        '<span class="serial-badge">' + (meta.row + meta.settings._iDisplayStart +
                            1) +
                        '</span>' : (meta.row + meta.settings._iDisplayStart + 1)
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'permission',
                    name: 'permission',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'created_at',
                    name: 'created_at',
                    render: function(data) {
                        return new Date(data).toLocaleDateString('en-GB', {
                            day: '2-digit',
                            month: 'short',
                            year: 'numeric'
                        });
                    }
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });

        // move buttons into custom header container
        table.buttons().container().appendTo('#roleTableButtons');
    });
</script>
@endsection
