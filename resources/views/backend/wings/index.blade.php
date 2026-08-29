@extends('layouts.backend')

@section('title')
    Wing
@endsection

@section('content')
    <!-- Success Message -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Error Message -->
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="app-toolbar py-3 py-lg-6">
        <div class="app-container container-fluid">
            <div class="admin-page-header">
                <div class="admin-page-header-title">
                    <span class="icon-box"><i class="bi bi-building"></i></span>
                    <h1>Wing</h1>
                </div>
                <button data-bs-toggle="modal" data-bs-target="#wingCreateModal" class="btn-admin-primary">
                    <i class="bi bi-plus-lg"></i> Add Wing
                </button>
            </div>
        </div>
    </div>

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h5><i class="bi bi-table" style="color:#4361ee;"></i> Wing List</h5>
                    <div id="wingTableButtons"></div>
                </div>

                <table id="wingTable" class="display admin-table" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Imported Number</th>
                            <th>BIN Number</th>
                            <th>Mobile Number</th>
                            <th>Email</th>
                            <th>Image</th>
                            <th>Authority Signature</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="wingEditModal" tabindex="-1" aria-labelledby="wingEditModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content admin-modal-content" id="modalShow"></div>
        </div>
    </div>

    @include('backend.wings.create')

    <style>
        table.dataTable td img {
            display: block;
            height: 60px;
            width: 60px;
            object-fit: cover;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
    </style>

    <script>
        $(document).ready(function() {
            var table = $('#wingTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('wing.getdata') }}',
                dom: 'Blfrtip', // B = buttons
                buttons: [{
                        extend: 'excelHtml5',
                        text: '<i class="bi bi-file-earmark-excel-fill"></i> Excel',
                        title: 'Wing List',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5] // exclude Image, Signature & Action column
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="bi bi-printer-fill"></i> Print',
                        title: 'Wing List',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5] // exclude Image, Signature & Action column
                        }
                    }
                ],
                columns: [{
                        data: null,
                        name: 'serial_number',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return type === 'display' ?
                                '<span class="serial-badge">' + (meta.row + meta.settings
                                    ._iDisplayStart + 1) + '</span>' :
                                (meta.row + meta.settings._iDisplayStart + 1);
                        }
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'imported_number',
                        name: 'imported_number'
                    },
                    {
                        data: 'bin_number',
                        name: 'bin_number'
                    },
                    {
                        data: 'mobile_number',
                        name: 'mobile_number'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'image',
                        name: 'image',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'authority_signature',
                        name: 'authority_signature',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            if (type === 'export') {
                                return data ? 'Available' : 'No Signature';
                            }

                            if (data) {
                                let signature = '{{ asset('signature/') }}/' + data;
                                return '<img src="' + signature +
                                    '" style="height:50px;width:100px;object-fit:contain;border:none;">';
                            }

                            return '<span class="text-muted">No Signature</span>';
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
            table.buttons().container().appendTo('#wingTableButtons');
        });

        /*
        |--------------------------------------------------------------------------
        | Edit Wing
        |--------------------------------------------------------------------------
        */
        $(document).on('click', '.edit', function() {
            var dataId = $(this).data('id');

            $.ajax({
                url: "{{ route('wing.edit', ':id') }}".replace(':id', dataId),
                type: 'GET',
                success: function(response) {
                    $('#modalShow').html(response);
                    $('#wingEditModal').modal('show');
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Something went wrong!'
                    });
                }
            });
        });

        /*
        |--------------------------------------------------------------------------
        | Delete Wing Confirmation
        |--------------------------------------------------------------------------
        */
        $(document).on('click', '.delete', function(event) {
            event.preventDefault();

            let form = $(this).closest('form');

            Swal.fire({
                title: "Are you sure?",
                text: "This wing will be deleted!",
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

    {{-- Added Successfully --}}
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
