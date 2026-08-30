@extends('layouts.backend')

@section('title')
    Product
@endsection

@section('content')

<div class="app-toolbar py-3 py-lg-6">
    <div class="app-container container-fluid">

        <div class="admin-page-header">

            <div class="admin-page-header-title">
                <span class="icon-box">
                    <i class="bi bi-box-seam"></i>
                </span>

                <h1>Product</h1>
            </div>

            <button
                data-bs-toggle="modal"
                data-bs-target="#productCreateModal"
                class="btn-admin-primary">

                <i class="bi bi-plus-lg"></i>
                Add Product

            </button>

        </div>

    </div>
</div>


<div id="kt_app_content" class="app-content flex-column-fluid">

    <div
        id="kt_app_content_container"
        class="app-container container-fluid">

        <div class="admin-card">

            <div class="admin-card-header">

                <h5>
                    <i
                        class="bi bi-table"
                        style="color:#4361ee;">
                    </i>

                    Product List
                </h5>

                <div id="productTableButtons"></div>

            </div>


            <table
                id="productTable"
                class="display admin-table"
                style="width:100%">

                <thead>

                    <tr>
                        <th>#</th>
                        <th>Product Code</th>
                        <th>Name</th>
                        <th>Image</th>
                        <th>Wing</th>
                        <th>Category</th>
                        
                        <th>Brand</th>
                        
                        <th>Status</th>
                        <th>Action</th>
                    </tr>

                </thead>

            </table>

        </div>

    </div>

</div>


<div
    class="modal fade"
    id="productEditModal"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog modal-xl">

        <div
            class="modal-content admin-modal-content"
            id="productModalShow">
        </div>

    </div>

</div>

<!-- Product View Modal -->

<div
    class="modal fade"
    id="productViewModal"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div
            class="modal-content"
            id="productViewModalContent"
            style="
                border-radius:12px;
                border:none;
                overflow:hidden;
            ">
        </div>

    </div>

</div>



@include('backend.products.create')


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

$(document).ready(function () {

    var table = $('#productTable').DataTable({

        processing: true,
        serverSide: true,

        ajax: '{{ route('product.getdata') }}',

        dom: 'Blfrtip',

        buttons: [

            {
                extend: 'excelHtml5',

                text:
                    '<i class="bi bi-file-earmark-excel-fill"></i> Excel',

                title: 'Product List',

                exportOptions: {
                    columns: [
                        0,1,2,
                        4,5,6,7,8,9,10,11,12,13,
                        14,15,16,17
                    ]
                }
            },

            {
                extend: 'print',

                text:
                    '<i class="bi bi-printer-fill"></i> Print',

                title: 'Product List',

                exportOptions: {
                    columns: [
                        0,1,2,
                        4,5,6,7,8,9,10,11,12,13,
                        14,15,16,17
                    ]
                }
            }

        ],

        columns: [

            {
                data: null,
                name: 'serial_number',
                orderable: false,
                searchable: false,

                render: function (
                    data,
                    type,
                    row,
                    meta
                ) {

                    let number =
                        meta.row +
                        meta.settings._iDisplayStart +
                        1;

                    return type === 'display'
                        ? '<span class="serial-badge">' +
                            number +
                          '</span>'
                        : number;
                }
            },

            {
        data: 'product_code',
        name: 'product_code'
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
        data: 'wing_name',
        name: 'wing.name'
    },

    {
        data: 'category_name',
        name: 'category.name'
    },

    {
        data: 'brand_name',
        name: 'brand.name'
    },

    {
        data: 'status',
        name: 'status',

        render: function (
            data,
            type,
            row
        ) {

            if (type === 'export') {
                return $(data).text();
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


    table
        .buttons()
        .container()
        .appendTo('#productTableButtons');


    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'click',
        '.edit',
        function () {

            let dataId =
                $(this).data('id');

            $.ajax({

                url:
                    '/product/' +
                    dataId +
                    '/edit',

                type: 'GET',

                success: function (response) {

                    $('#productModalShow')
                        .html(response);

                    $('#productEditModal')
                        .modal('show');

                },

                error: function (xhr) {

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text:
                            xhr.responseJSON?.message ||
                            'Something went wrong!'
                    });

                }

            });

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'click',
        '.delete',
        function (event) {

            event.preventDefault();

            let form =
                $(this).closest('form');

            Swal.fire({

                title: "Are you sure?",

                text:
                    "You won't be able to revert this!",

                icon: "warning",

                showCancelButton: true,

                confirmButtonColor: "#d33",

                cancelButtonColor: "#3085d6",

                confirmButtonText:
                    "Yes, delete it!"

            }).then((result) => {

                if (result.isConfirmed) {
                    form.submit();
                }

            });

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Status
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'click',
        '.status-toggle',
        function () {

            let btn = $(this);

            let id =
                btn.data('id');

            $.ajax({

                url:
                    '/product/status/' +
                    id,

                type: 'PUT',

                data: {
                    _token:
                        '{{ csrf_token() }}'
                },

                beforeSend: function () {

                    btn.prop(
                        'disabled',
                        true
                    );

                },

                success: function (response) {

                    if (response.success) {

                        $('#productTable')
                            .DataTable()
                            .ajax
                            .reload(
                                null,
                                false
                            );

                        Swal.fire({

                            icon: 'success',

                            title:
                                response.message,

                            showConfirmButton:
                                false,

                            timer: 1200
                        });
                    }

                },

                error: function (xhr) {

                    btn.prop(
                        'disabled',
                        false
                    );

                    Swal.fire({

                        icon: 'error',

                        title:
                            xhr.status === 403
                                ? 'Access Denied'
                                : 'Error',

                        text:
                            xhr.responseJSON?.message ||
                            'Something went wrong!'

                    });

                }

            });

        }
    );

});


/*
|--------------------------------------------------------------------------
| Success Message
|--------------------------------------------------------------------------
*/

@if (request()->has('added-successfully'))

$(document).ready(function () {

    Swal.fire({

        icon: "success",

        title:
            "{{ request('added-successfully') }}",

        showConfirmButton: false,

        timer: 2000

    });


    const url =
        new URL(window.location.href);

    url.searchParams.delete(
        'added-successfully'
    );

    window.history.replaceState(
        null,
        '',
        url
    );

});

@endif

</script>

<script>
    $(document).on(
    'click',
    '.view',
    function () {

        let dataId =
            $(this).data('id');

        $('#productViewModalContent').html(`
            <div
                class="modal-body text-center"
                style="padding:50px;">

                <div
                    class="spinner-border"
                    style="color:#4361ee;"
                    role="status">
                </div>

                <div
                    class="mt-3"
                    style="color:#8a8a9a;">
                    Loading product details...
                </div>

            </div>
        `);

        $('#productViewModal').modal('show');


        $.ajax({

            url:
                "{{ route('product.view', ':id') }}"
                    .replace(':id', dataId),

            type: 'GET',

            success: function (response) {

                $('#productViewModalContent')
                    .html(response);

            },

            error: function (xhr) {

                $('#productViewModal').modal('hide');

                Swal.fire({

                    icon: 'error',

                    title: 'Error',

                    text:
                        xhr.responseJSON?.message ||
                        'Unable to load product details!'

                });

            }

        });

    }
);
</script>



@endsection
