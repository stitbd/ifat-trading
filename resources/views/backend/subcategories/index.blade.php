@extends('layouts.backend')

@section('title')
    Subcategories
@endsection

@section('content')

    <div id="kt_app_toolbar"
        class="app-toolbar py-3 py-lg-6">

        <div id="kt_app_toolbar_container"
            class="app-container container-fluid d-flex flex-stack">

            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">

                <h1 class="page-heading d-flex text-dark fw-bold fs-3 my-0">
                    Subcategories
                </h1>

                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">

                    <li class="breadcrumb-item text-muted">

                        <a href="{{ route('subcategory.index') }}"
                            class="text-muted text-hover-primary">

                            All-Subcategories

                        </a>

                    </li>

                    <li class="breadcrumb-item">

                        <span class="bullet bg-gray-400 w-5px h-2px"></span>

                    </li>

                    <li class="breadcrumb-item text-muted">

                        Subcategories

                    </li>

                </ul>

            </div>

        </div>

    </div>


    <div id="kt_app_content"
        class="app-content flex-column-fluid">

        <div id="kt_app_content_container"
            class="app-container container-fluid">


            <button
                data-bs-toggle="modal"
                data-bs-target="#subcategoryCreateModal"
                class="btn btn-sm btn-success mb-2">

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                    style="width:20px;height:20px;">

                    <path
                        d="M12 5V19M5 12H19"
                        stroke="#ffffff"
                        stroke-width="2"
                        stroke-linecap="round"
                    />

                </svg>

                Add Subcategory

            </button>


            <table
                id="subcategoryTable"
                class="display"
                style="width:100%;">

                <thead>

                    <tr>

                        <th>Serial ID</th>

                        <th>Category</th>

                        <th>Name</th>

                        <th>Image</th>

                        <th>Description</th>

                        <th>Action</th>

                    </tr>

                </thead>

            </table>

        </div>

    </div>


    {{-- Edit Modal --}}

    <div
        class="modal fade"
        id="subcategoryEditModal"
        tabindex="-1"
        aria-hidden="true">

        <div class="modal-dialog modal-xl">

            <div
                class="modal-content"
                id="subcategoryModalShow"
                style="
                    background-color:#f8f9fa;
                    border-radius:8px;
                    border:1px solid #ddd;
                ">

            </div>

        </div>

    </div>


    {{-- Create Modal --}}

    @include(
        'backend.subcategories.create'
    )


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

            /*
            |--------------------------------------------------------------------------
            | DataTable
            |--------------------------------------------------------------------------
            */

            $('#subcategoryTable').DataTable({

                processing: true,

                serverSide: true,

                ajax:
                    '{{ route('subcategory.getdata') }}',

                columns: [

                    {
                        data: null,

                        name: 'serial_number',

                        render: function(
                            data,
                            type,
                            row,
                            meta
                        ) {

                            return meta.row +
                                meta.settings
                                    ._iDisplayStart +
                                1;
                        },

                        orderable: false,

                        searchable: false
                    },

                    {
                        data: 'category_name',

                        name: 'category_name'
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
                        data: 'action',

                        name: 'action',

                        orderable: false,

                        searchable: false
                    }

                ]

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

                    $.ajax({

                        url:
                            "{{ route('subcategory.edit', ':id') }}"
                            .replace(
                                ':id',
                                dataId
                            ),

                        type: 'GET',

                        success: function(response) {

                            $('#subcategoryModalShow')
                                .html(response);

                            $('#subcategoryEditModal')
                                .modal('show');

                        },

                        error: function() {

                            Swal.fire({

                                icon: 'error',

                                title: 'Error',

                                text:
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
                function(event) {

                    event.preventDefault();

                    let form =
                        $(this).closest('form');

                    Swal.fire({

                        title:
                            'Are you sure?',

                        text:
                            'This subcategory will be deleted!',

                        icon:
                            'warning',

                        showCancelButton:
                            true,

                        confirmButtonColor:
                            '#d33',

                        cancelButtonColor:
                            '#3085d6',

                        confirmButtonText:
                            'Yes, delete it!'

                    }).then(function(result) {

                        if (result.isConfirmed) {

                            form.submit();

                        }

                    });

                }
            );

        });

    </script>

@endsection
