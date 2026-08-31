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

        <div id="kt_app_content_container"
            class="app-container container-fluid">

            <div class="admin-card">


                {{-- Header --}}

                <div class="admin-card-header">

                    <h5>
                        <i class="bi bi-table"
                            style="color:#4361ee;">
                        </i>

                        Requisition List
                    </h5>

                    <div id="requisitionTableButtons"></div>

                </div>


                {{-- Filters --}}

                <div class="row p-3"
                    style="border-bottom:1px solid #eef0f2;">


                    {{-- Wing --}}

                    <div class="col-md-3 mb-2">

                        <select
                            id="filter_wing"
                            class="form-select">

                            <option value="">
                                All Wings
                            </option>

                            @foreach ($wings as $wing)

                                <option value="{{ $wing->id }}">
                                    {{ $wing->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Warehouse --}}

                    <div class="col-md-3 mb-2">

                        <select
                            id="filter_warehouse"
                            class="form-select">

                            <option value="">
                                All Warehouses
                            </option>

                            @foreach ($warehouses as $warehouse)

                                <option value="{{ $warehouse->id }}">
                                    {{ $warehouse->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Requisition Type --}}

                    <div class="col-md-3 mb-2">

                        <select
                            id="filter_requisition_type"
                            class="form-select">

                            <option value="">
                                All Types
                            </option>

                            <option value="local">
                                Local
                            </option>

                            <option value="import">
                                Import
                            </option>

                          

                        </select>

                    </div>


                    {{-- Reset --}}

                    <div class="col-md-3 mb-2">

                        <button
                            id="filter_reset"
                            class="btn btn-outline-secondary w-100">

                            <i class="bi bi-x-circle"></i>

                            Reset Filters

                        </button>

                    </div>

                </div>


                {{-- DataTable --}}

                <table
                    id="requisitionTable"
                    class="display admin-table"
                    style="width:100%;">

                    <thead>

                        <tr>

                            <th>#</th>

                            <th>Requisition No</th>

                            <th>Wing</th>

                            <th>Warehouse</th>

                            <th>Type</th>

                            <th>Products</th>

                            <th>Total Quantity</th>

                            <th>Date</th>

                            <th>Place of Supply</th>

                            <th>Status</th>

                            <th>Action</th>

                        </tr>

                    </thead>

                </table>

            </div>

        </div>

    </div>



    {{-- Requisition View Modal --}}

    <div
        class="modal fade"
        id="requisitionViewModal"
        tabindex="-1"
        aria-hidden="true">

        <div
            class="modal-dialog modal-xl modal-dialog-centered">

            <div
                class="modal-content"
                id="requisitionViewModalContent"
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

            display:flex;

            flex-direction:column;

            gap:4px;

        }


        .requisition-product-item {

            background:#f8f9fa;

            border-radius:5px;

            padding:4px 8px;

            font-size:13px;

        }


        .requisition-product-name {

            font-weight:600;

            color:#333;

        }


        .requisition-product-qty {

            color:#777;

            margin-left:5px;

        }


        /*
        |--------------------------------------------------------------------------
        | Serial badge
        |--------------------------------------------------------------------------
        */

        .serial-badge {

            display:inline-flex;

            align-items:center;

            justify-content:center;

            min-width:28px;

            height:28px;

            background:#eef2ff;

            color:#4361ee;

            border-radius:6px;

            font-weight:600;

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

                        d.wing_id =
                            $('#filter_wing').val();

                        d.warehouse_id =
                            $('#filter_warehouse').val();

                        d.requisition_type =
                            $('#filter_requisition_type').val();

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

                        text:
                            '<i class="bi bi-file-earmark-excel-fill"></i> Excel',

                        title:
                            'Requisition List',

                        exportOptions: {

                            columns: [
                                0,
                                1,
                                2,
                                3,
                                4,
                                5,
                                6,
                                7,
                                8,
                                9
                            ],

                            format: {

                                body: function(data, row, column) {

                                    /*
                                    |--------------------------------------------------------------------------
                                    | Remove HTML from export
                                    |--------------------------------------------------------------------------
                                    */

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

                        text:
                            '<i class="bi bi-printer-fill"></i> Print',

                        title:
                            'Requisition List',

                        exportOptions: {

                            columns: [
                                0,
                                1,
                                2,
                                3,
                                4,
                                5,
                                6,
                                7,
                                8,
                                9
                            ],

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


                    /*
                    |--------------------------------------------------------------------------
                    | Serial
                    |--------------------------------------------------------------------------
                    */

                    {

                        data: null,

                        name: 'serial_number',

                        orderable: false,

                        searchable: false,


                        render: function(
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

                                ?

                                '<span class="serial-badge">'
                                + number +
                                '</span>'

                                :

                                number;

                        }

                    },


                    /*
                    |--------------------------------------------------------------------------
                    | Requisition No
                    |--------------------------------------------------------------------------
                    */

                    {

                        data: 'requisition_no',

                        name: 'requisition_no'

                    },


                    /*
                    |--------------------------------------------------------------------------
                    | Wing
                    |--------------------------------------------------------------------------
                    */

                    {

                        data: 'wing_name',

                        name: 'wing.name'

                    },


                    /*
                    |--------------------------------------------------------------------------
                    | Warehouse
                    |--------------------------------------------------------------------------
                    */

                    {

                        data: 'warehouse_name',

                        name: 'warehouse.name'

                    },


                    /*
                    |--------------------------------------------------------------------------
                    | Type
                    |--------------------------------------------------------------------------
                    */

                    {

                        data: 'requisition_type_name',

                        name: 'requisition_type'

                    },


                    /*
                    |--------------------------------------------------------------------------
                    | Products
                    |--------------------------------------------------------------------------
                    */

                    {

                        data: 'products',

                        name: 'products',

                        orderable: false,

                        searchable: false

                    },


                    /*
                    |--------------------------------------------------------------------------
                    | Total Quantity
                    |--------------------------------------------------------------------------
                    */

                    {

                        data: 'total_quantity',

                        name: 'total_quantity'

                    },


                    /*
                    |--------------------------------------------------------------------------
                    | Date
                    |--------------------------------------------------------------------------
                    */

                    {

                        data: 'date',

                        name: 'date'

                    },


                    /*
                    |--------------------------------------------------------------------------
                    | Place of Supply
                    |--------------------------------------------------------------------------
                    */

                    {

                        data: 'place_of_supply',

                        name: 'place_of_supply'

                    },


                    /*
                    |--------------------------------------------------------------------------
                    | Status
                    |--------------------------------------------------------------------------
                    */

                    {

                        data: 'status',

                        name: 'status',

                        render: function(
                            data,
                            type,
                            row
                        ) {

                            if (type === 'export') {

                                return $(data)
                                    .text();

                            }

                            return data;

                        }

                    },


                    /*
                    |--------------------------------------------------------------------------
                    | Action
                    |--------------------------------------------------------------------------
                    */

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

            table
                .buttons()
                .container()
                .appendTo(
                    '#requisitionTableButtons'
                );


            /*
            |--------------------------------------------------------------------------
            | Filters
            |--------------------------------------------------------------------------
            */

            $(
                '#filter_wing, ' +
                '#filter_warehouse, ' +
                '#filter_requisition_type'
            )
            .on('change', function() {

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
                    '#filter_requisition_type'
                )
                .val('');

                table.ajax.reload();

            });


            /*
            |--------------------------------------------------------------------------
            | Edit
            |--------------------------------------------------------------------------
            */

            $(document).on(
                'click',
                '.edit',
                function() {

                    let dataId =
                        $(this).data('id');


                    window.location.href =
                        "{{ route('requisition.edit', ':id') }}"
                        .replace(':id', dataId);

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
                function(event) {

                    event.preventDefault();


                    let form =
                        $(this).closest('form');


                    Swal.fire({

                        title:
                            "Are you sure?",

                        text:
                            "You won't be able to revert this!",

                        icon:
                            "warning",

                        showCancelButton:
                            true,

                        confirmButtonColor:
                            "#d33",

                        cancelButtonColor:
                            "#3085d6",

                        confirmButtonText:
                            "Yes, delete it!"

                    })
                    .then((result) => {

                        if (
                            result.isConfirmed
                        ) {

                            form.submit();

                        }

                    });

                }
            );


            /*
            |--------------------------------------------------------------------------
            | View
            |--------------------------------------------------------------------------
            */

            $(document).on(
                'click',
                '.view',
                function() {

                    let dataId =
                        $(this).data('id');


                    $('#requisitionViewModalContent')
                        .html(`

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

                                    Loading requisition details...

                                </div>

                            </div>

                        `);


                    $('#requisitionViewModal')
                        .modal('show');


                    $.ajax({

                        url:
                            "{{ route('requisition.view', ':id') }}"
                            .replace(':id', dataId),

                        type:
                            'GET',


                        success: function(response) {

                            $('#requisitionViewModalContent')
                                .html(response);

                        },


                        error: function(xhr) {

                            $('#requisitionViewModal')
                                .modal('hide');


                            Swal.fire({

                                icon:
                                    'error',

                                title:
                                    'Error',

                                text:
                                    xhr.responseJSON?.message ||
                                    'Unable to load requisition details!'

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

            $(document).ready(function() {

                Swal.fire({

                    icon:
                        "success",

                    title:
                        "{{ request('added-successfully') }}",

                    showConfirmButton:
                        false,

                    timer:
                        2000

                });


                const url =
                    new URL(
                        window.location.href
                    );


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

@endsection
