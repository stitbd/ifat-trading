@extends('layouts.backend')

@section('title')
    Categories
@endsection

@section('content')

    {{-- Success Message --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Error Message --}}
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif


    <!-- Toolbar -->
    <div id="kt_app_toolbar"
        class="app-toolbar py-3 py-lg-6">

        <div id="kt_app_toolbar_container"
            class="app-container container-fluid d-flex flex-stack">

            <!-- Page Title -->
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">

                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                    Categories
                </h1>

                <!-- Breadcrumb -->
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">

                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('category.index') }}"
                            class="text-muted text-hover-primary">
                            All-Categories
                        </a>
                    </li>

                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>

                    <li class="breadcrumb-item text-muted">
                        Categories
                    </li>

                </ul>

            </div>

        </div>

    </div>


    <!-- Content -->
    <div id="kt_app_content"
        class="app-content flex-column-fluid">

        <div id="kt_app_content_container"
            class="app-container container-fluid">


            {{-- Add Category Button --}}
            <button
                data-bs-toggle="modal"
                data-bs-target="#categoryCreateModal"
                class="btn btn-sm btn-success mb-2">

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                    style="width:20px;height:20px;">

                    <path
                        fill-rule="evenodd"
                        clip-rule="evenodd"
                        d="m3.99 16.854-1.314 3.504a.75.75 0 0 0 .966.965l3.503-1.314a3 3 0 0 0 1.068-.687L18.36 9.175s-.354-1.061-1.414-2.122c-1.06-1.06-2.122-1.414-2.122-1.414L4.677 15.786a3 3 0 0 0-.687 1.068zm12.249-12.63 1.383-1.383c.248-.248.579-.406.925-.348.487.08 1.232.322 1.934 1.025.703.703.945 1.447 1.025 1.934.058.346-.1.677-.348.925L19.774 7.76s-.353-1.06-1.414-2.12c-1.06-1.062-2.121-1.415-2.121-1.415z"
                        fill="#ffffff">
                    </path>

                </svg>

                Add Category

            </button>


            {{-- Category Table --}}
            <table
                id="categoryTable"
                class="display"
                style="width:100%;">

                <thead>

                    <tr>

                        <th>Serial ID</th>

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
        id="categoryEditModal"
        tabindex="-1"
        aria-labelledby="categoryEditModalLabel"
        aria-hidden="true">

        <div class="modal-dialog modal-xl">

            <div
                class="modal-content"
                id="modalShow"
                style="
                    background-color:#f8f9fa;
                    border-radius:8px;
                    border:1px solid #ddd;
                ">

            </div>

        </div>

    </div>


    {{-- Create Modal --}}
    @include('backend.categories.create')


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

            $('#categoryTable').DataTable({

                processing: true,

                serverSide: true,

                ajax: '{{ route('category.getdata') }}',

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
            | Edit Category
            |--------------------------------------------------------------------------
            */

            $(document).on(
                'click',
                '.edit',
                function() {

                    var dataId =
                        $(this).data('id');

                    $.ajax({

                        url:
                            "{{ route('category.edit', ':id') }}"
                            .replace(
                                ':id',
                                dataId
                            ),

                        type: 'GET',

                        success: function(response) {

                            $('#modalShow')
                                .html(response);

                            $('#categoryEditModal')
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
            | Delete Category
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
                            "This category will be deleted!",

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

                    }).then(
                        (result) => {

                            if (
                                result.isConfirmed
                            ) {

                                form.submit();

                            }

                        }
                    );

                }
            );

        });

    </script>


    {{-- Added Successfully --}}
    @if (request()->has('added-successfully'))

        <script>

            $(document).ready(function() {

                Swal.fire({

                    icon: "success",

                    title:
                        "{{ request('added-successfully') }}",

                    showConfirmButton: false,

                    timer: 2000

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

        </script>

    @endif

@endsection