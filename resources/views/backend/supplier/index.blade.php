@extends('layouts.backend')

@section('title')
    Supplier
@endsection

@section('content')
    <div class="app-toolbar py-3 py-lg-6">
        <div class="app-container container-fluid">
            <div class="admin-page-header">
                <div class="admin-page-header-title">
                    <span class="icon-box"><i class="bi bi-truck"></i></span>
                    <h1>Supplier</h1>
                </div>
                <button data-bs-toggle="modal" data-bs-target="#supplierCreateModal" class="btn-admin-primary">
                    <i class="bi bi-plus-lg"></i> Add Supplier
                </button>
            </div>
        </div>
    </div>

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h5><i class="bi bi-table" style="color:#4361ee;"></i> Supplier List</h5>
                    <div id="supplierTableButtons"></div>
                </div>

                <table id="supplierTable" class="display admin-table" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Company Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="supplierEditModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content admin-modal-content" id="supplierModalShow"></div>
        </div>
    </div>

    <div class="modal fade" id="supplierViewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" id="supplierViewModalContent"
                style="border-radius:12px; border:none; overflow:hidden;"></div>
        </div>
    </div>

    @include('backend.supplier.create')

    <style>
        table.dataTable td img {
            display: block;
            height: 55px;
            width: 55px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #ddd;
        }
    </style>

    <script>
        $(document).ready(function() {

            var table = $('#supplierTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('supplier.getdata') }}',
                dom: 'Blfrtip',

                buttons: [{
                        extend: 'excelHtml5',
                        text: '<i class="bi bi-file-earmark-excel-fill"></i> Excel',
                        title: 'Supplier List',
                        exportOptions: {
                            columns: [0, 2, 3, 4, 5]
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="bi bi-printer-fill"></i> Print',
                        title: 'Supplier List',
                        exportOptions: {
                            columns: [0, 2, 3, 4, 5]
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
                        data: 'image',
                        name: 'image',
                        orderable: false,
                        searchable: false
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
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'company_name',
                        name: 'company_name'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            table.buttons().container().appendTo('#supplierTableButtons');

            /*
            |--------------------------------------------------------------------------
            | Edit
            |--------------------------------------------------------------------------
            */
            $(document).on('click', '.edit', function() {
                let dataId = $(this).data('id');
                $.ajax({
                    url: "{{ route('supplier.edit', ':id') }}".replace(':id', dataId),
                    type: 'GET',
                    success: function(response) {
                        $('#supplierModalShow').html(response);
                        $('#supplierEditModal').modal('show');
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

                $('#supplierViewModalContent').html(`
                    <div class="modal-body text-center" style="padding:50px;">
                        <div class="spinner-border" style="color:#4361ee;" role="status"></div>
                        <div class="mt-3" style="color:#8a8a9a;">Loading supplier details...</div>
                    </div>
                `);

                $('#supplierViewModal').modal('show');

                $.ajax({
                    url: "{{ route('supplier.view', ':id') }}".replace(':id', dataId),
                    type: 'GET',
                    success: function(response) {
                        $('#supplierViewModalContent').html(response);
                    },
                    error: function(xhr) {
                        $('#supplierViewModal').modal('hide');
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.message ||
                                'Unable to load supplier details!'
                        });
                    }
                });
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
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            });
        });

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
