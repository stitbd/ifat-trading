@extends('layouts.backend')
@section('content')

@section('title')
    Brand
@endsection

<div class="app-toolbar py-3 py-lg-6">
    <div class="app-container container-fluid">
        <div class="admin-page-header">
            <div class="admin-page-header-title">
                <span class="icon-box"><i class="bi bi-award"></i></span>
                <h1>Brand</h1>
            </div>
            <button data-bs-toggle="modal" data-bs-target="#brandCreateModal" class="btn-admin-primary">
                <i class="bi bi-plus-lg"></i> Add Brand
            </button>
        </div>
    </div>
</div>

<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-fluid">
        <div class="admin-card">
            <div class="admin-card-header">
                <h5><i class="bi bi-table" style="color:#4361ee;"></i> Brand List</h5>
                <div id="brandTableButtons"></div>
            </div>

            <table id="brandTable" class="display admin-table" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Image</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="brandEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content admin-modal-content" id="brandModalShow"></div>
    </div>
</div>

@include('backend.brands.create')

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
        var table = $('#brandTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('brand.getdata') }}',
            dom: 'Blfrtip', // B = buttons
            buttons: [{
                    extend: 'excelHtml5',
                    text: '<i class="bi bi-file-earmark-excel-fill"></i> Excel',
                    title: 'Brand List',
                    exportOptions: {
                        columns: [0, 1, 3, 4] // exclude Image & Action column
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="bi bi-file-earmark-pdf-fill"></i> PDF',
                    title: 'Brand List',
                    orientation: 'landscape',
                    pageSize: 'A4',
                    exportOptions: {
                        columns: [0, 1, 3, 4] // exclude Image & Action column
                    },
                    customize: function(doc) {
                        doc.content[1].table.widths = ['8%', '22%', '50%', '20%'];

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
                    title: 'Brand List',
                    exportOptions: {
                        columns: [0, 1, 3, 4] // exclude Image & Action column
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
                    data: 'image',
                    name: 'image',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'description',
                    name: 'description'
                },
                {
                    data: 'status',
                    name: 'status',
                    render: function(data, type, row) {
                        if (type === 'export') {
                            return $(data).text(); // plain text for excel/print, no html badge
                        }
                        return data;
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
        table.buttons().container().appendTo('#brandTableButtons');
    });

    $(document).on('click', '.edit', function() {
        let dataId = $(this).data('id');
        $.ajax({
            url: "{{ route('brand.edit', ':id') }}".replace(':id', dataId),
            type: 'GET',
            success: function(response) {
                $('#brandModalShow').html(response);
                $('#brandEditModal').modal('show');
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

    $(document).on('click', '.delete', function(event) {
        event.preventDefault();
        let form = $(this).closest('form');
        Swal.fire({
            title: "Are you sure?",
            text: "This brand will be deleted!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then(function(result) {
            if (result.isConfirmed) form.submit();
        });
    });
</script>
<script>
    $(document).on('click', '.status-toggle', function() {
        let btn = $(this);
        let id = btn.data('id');

        $.ajax({
            url: '/brand/status/' + id,
            type: 'PUT',
            data: {
                _token: '{{ csrf_token() }}'
            },
            beforeSend: function() {
                btn.prop('disabled', true);
            },
            success: function(response) {
                if (response.success) {
                    $('#brandTable').DataTable().ajax.reload(null, false);

                    Swal.fire({
                        icon: 'success',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 1200
                    });
                }
            },
            error: function(xhr) {
                btn.prop('disabled', false);

                if (xhr.status === 403) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Access Denied',
                        text: xhr.responseJSON?.message || 'You do not have permission!',
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message || 'Something went wrong!',
                    });
                }
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
