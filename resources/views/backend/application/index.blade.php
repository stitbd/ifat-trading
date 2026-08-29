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
    Application
@endsection
<style>
    #applicatoion .app-container {
        max-width: 1000px;
        margin: auto;
    }

    #applicatoion .card-header {
        background: #007bff;
        color: white !important;
        text-align: center;
        font-weight: bold;
        align-items: center !important;
    }

    #applicatoion .card-header h2 {

        color: white !important;

    }

    #applicatoion .form-group label {
        color: #0056b3;
        font-weight: bold;
    }

    #applicatoion .form-control {
        border: 1px solid #007bff;
        border-radius: 5px;
    }

    #applicatoion .btn-custom {
        background-color: #28a745;
        color: white;
        font-weight: bold;
        padding: 10px;
        border-radius: 5px;
    }

    #applicatoion .btn-custom:hover {
        background-color: #218838;
    }

    #applicatoion .border-danger {
        border-color: #ff6b6b !important;
    }
</style>
<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading text-dark fw-bold fs-4 fs-3 my-0">
                System-Setting
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                    <span class="bullet bg-gray-400 w-5px h-2px"></span>
                </li>
                <li class="breadcrumb-item text-muted">System-Setting</li>
            </ul>
        </div>
    </div>
</div>
<!--end::Toolbar-->
<div id="applicatoion">
    <div class="app-container container-fluid">
        <div class="card mt-4 shadow border-0">
            <div class="card-header py-3">
                <h2>System-setting</h2>
            </div>
            <div class="card-body bg-light">
                @if ($data)
                    <form method="POST" action="{{ route('applications.update', $data->id) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group row mb-3">
                            <label for="company_name" class="col-md-3 col-form-label">Company Name:</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="company_name" name="company_name"
                                    placeholder="Enter company name" value="{{ $data->company_name }}">
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="company_email" class="col-md-3 col-form-label">Company Email:</label>
                            <div class="col-md-9">
                                <input type="email" class="form-control" id="company_email" name="company_email"
                                    placeholder="Enter company email" value="{{ $data->company_email }}">
                            </div>
                        </div>


                        <div class="form-group row mb-3">
                            <label for="phone" class="col-md-3 col-form-label">Contact Number:</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="phone" name="phone"
                                    placeholder="Enter contact number" value="{{ $data->phone }}">
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="address" class="col-md-3 col-form-label">Company Address:</label>
                            <div class="col-md-9">
                                <textarea class="form-control" id="address" name="address" placeholder="Enter company address" rows="2">{{ $data->address }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label for="map" class="col-md-3 col-form-label">Google Map:</label>
                            <div class="col-md-9">
                                <textarea class="form-control" id="map" name="map" placeholder="Google Map Link" rows="2">{{ $data->google_map }}</textarea>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="about_company" class="col-md-3 col-form-label">About Company:</label>
                            <div class="col-md-9">
                                <textarea class="form-control" id="about_company" name="about_company" placeholder="Enter information about the company"
                                    rows="3">{{ $data->about_company }}</textarea>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="copy_right_text" class="col-md-3 col-form-label">Copyright Text:</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="copy_right_text" name="copy_right_text"
                                    placeholder="Enter Copyright Text" value="{{ $data->copy_right_text }}">
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="logo" class="col-md-3 col-form-label">Company Logo:</label>
                            <div class="col-md-9">
                                <input type="file" class="form-control" id="logo" name="logo">
                                @if ($data->logo)
                                    <img src="{{ asset('image/application/' . $data->logo) }}" height="100"
                                        class="mt-2 border rounded" alt="Current Logo">
                                @endif
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="fav_icon" class="col-md-3 col-form-label">Fav Icon:</label>
                            <div class="col-md-9">
                                <input type="file" class="form-control" id="fav_icon" name="fav_icon">
                                @if ($data->fav_icon)
                                    <img src="{{ asset('image/application/' . $data->fav_icon) }}" height="50"
                                        class="mt-2 border rounded" alt="Current Icon">
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-9 offset-md-3">
                                <button type="submit" class="btn btn-custom btn-block">Update Data</button>
                            </div>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>




@endsection
