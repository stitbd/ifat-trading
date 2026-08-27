@extends('layouts.backend')
@section('content')
    <!-- success message  -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- error message  -->
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

@section('title')
    User
@endsection

<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <!--begin::Toolbar container-->
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
        <!--begin::Page title-->
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <!--begin::Title-->
            <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">User
            </h1>
            <!--end::Title-->
            <!--begin::Breadcrumb-->
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <!--begin::Item-->
                <li class="breadcrumb-item text-muted">
                    <a href="{{ route('user.index') }}" class="text-muted text-hover-primary">All-User</a>
                </li>
                <!--end::Item-->
                <!--begin::Item-->
                <li class="breadcrumb-item">
                    <span class="bullet bg-gray-400 w-5px h-2px"></span>
                </li>
                <!--end::Item-->
                <!--begin::Item-->
                <li class="breadcrumb-item text-muted">User</li>
                <!--end::Item-->
            </ul>
            <!--end::Breadcrumb-->
        </div>
        <!--end::Page title-->
    </div>
    <!--end::Toolbar container-->
</div>
<!--end::Toolbar-->



<div id="kt_app_content" class="app-content flex-column-fluid">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-fluid">
        {{-- add button  --}}
        <button data-bs-toggle="modal" data-bs-target="#userCreateModal" class="btn btn-sm btn-success mb-2"><svg
                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 20px; height:20px">
                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                <g id="SVGRepo_iconCarrier">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="m3.99 16.854-1.314 3.504a.75.75 0 0 0 .966.965l3.503-1.314a3 3 0 0 0 1.068-.687L18.36 9.175s-.354-1.061-1.414-2.122c-1.06-1.06-2.122-1.414-2.122-1.414L4.677 15.786a3 3 0 0 0-.687 1.068zm12.249-12.63 1.383-1.383c.248-.248.579-.406.925-.348.487.08 1.232.322 1.934 1.025.703.703.945 1.447 1.025 1.934.058.346-.1.677-.348.925L19.774 7.76s-.353-1.06-1.414-2.12c-1.06-1.062-2.121-1.415-2.121-1.415z"
                        fill="#ffffff"></path>
                </g>
            </svg> Add User
        </button>

        <table id="featuredProjectTitleHeading" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>Serial ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Image</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>
</div>


<div class="modal fade" id="userEditModal" tabindex="-1" aria-labelledby="userEditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id="modalShow"
            style="background-color: #f8f9fa; border-radius: 8px; border: 1px solid #ddd;">

        </div>
    </div>
</div>
<!-- Custom CSS for Table Borders -->
@include('backend.user.create')
<style>
    table.dataTable td img {
        display: block;
        /* margin: auto; */
        height: 80px;
        width: 80px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid #555;
    }
</style>
<script>
    $(document).ready(function() {
        $('#featuredProjectTitleHeading').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('user.getdata') }}',
            columns: [{
                    data: null, // Use null to signify that this column does not map directly to any data source
                    name: 'serial_number',
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart +
                            1; // Calculate the serial number
                    },
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
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });

    });


    $(document).on('click', '.edit', function() {
        var dataId = $(this).data('id');
        $.ajax({
            url: '/user/edit/' + dataId,
            type: 'GET',
            success: function(response) {
                $('#modalShow').html(response);
                $('#userEditModal').modal('show');

            }
        })
    });

    $(document).on('click', '.delete', function(event) {
        event.preventDefault(); // Prevent the default form submission

        let form = $(this).closest('form'); // Get the closest form element

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
                form.submit(); // Submit the form if confirmed
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

            // Clear the query parameter from the URL
            const url = new URL(window.location.href);
            url.searchParams.delete('added-successfully');
            window.history.replaceState(null, '', url);
        });
    </script>
@endif
@endsection
