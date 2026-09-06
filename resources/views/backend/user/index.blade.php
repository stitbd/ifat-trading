@extends('layouts.backend')
@section('content')

@section('title')
    User
@endsection

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<div class="app-toolbar py-3 py-lg-6">
    <div class="app-container container-fluid">
        <div class="admin-page-header">
            <div class="admin-page-header-title">
                <span class="icon-box"><i class="bi bi-people"></i></span>
                <h1>User</h1>
            </div>
            <a href="{{ route('user.create') }}" class="btn-admin-primary">
                <i class="bi bi-plus-lg"></i> Add User
            </a>
        </div>
    </div>
</div>

<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-fluid">
        <div class="admin-card">
            <div class="admin-card-header">
                <h5><i class="bi bi-table" style="color:#4361ee;"></i> User List</h5>
                <div id="userTableButtons"></div>
            </div>

            <table id="featuredProjectTitleHeading" class="display admin-table" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>User Type</th>
                        <th>Image</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<style>
    table.dataTable td img {
        display: block;
        height: 80px;
        width: 80px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid #555;
    }
</style>

<script>
    $(document).ready(function() {
        var table = $('#featuredProjectTitleHeading').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('user.getdata') }}',
            dom: 'Blfrtip', // B = buttons
            buttons: [{
                    extend: 'excelHtml5',
                    text: '<i class="bi bi-file-earmark-excel-fill"></i> Excel',
                    title: 'User List',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 5] // exclude Image & Action column
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="bi bi-file-earmark-pdf-fill"></i> PDF',
                    title: 'User List',
                    orientation: 'landscape', // Email কলাম লম্বা হতে পারে
                    pageSize: 'A4',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 5] // exclude Image & Action column
                    },
                    customize: function(doc) {
                        doc.content[1].table.widths = ['7%', '22%', '32%', '14%', '25%'];

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
                    title: 'User List',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 5] // exclude Image & Action column
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
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'user_type',
                    name: 'user_type'
                },
                {
                    data: 'image',
                    name: 'image',
                    render: function(data, type, row) {
                        let image = data ?
                            '{{ asset('image/') }}/' + data :
                            '{{ asset('user.avif') }}';

                        return '<img src="' + image +
                            '" height="100" width="100" style="object-fit: cover; border:none; border-radius: 5px;">';
                    },
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'role',
                    name: 'role'
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
        table.buttons().container().appendTo('#userTableButtons');
    });

    // Edit is now a full page (see UserController@edit), so no modal/ajax handler
    // is needed here anymore — the action column already renders a plain <a> link.

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
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
</script>

@if (request()->has('added-successfully'))
    <script>
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
    </script>
@endif
@endsection
