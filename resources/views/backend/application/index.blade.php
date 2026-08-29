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

    <!-- validation error message -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

@section('title')
    Application
@endsection

<div class="app-toolbar py-3 py-lg-6">
    <div class="app-container container-fluid">
        <div class="admin-page-header">
            <div class="admin-page-header-title">
                <span class="icon-box"><i class="bi bi-gear"></i></span>
                <h1>System Setting</h1>
            </div>
        </div>
    </div>
</div>

<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-fluid">
        <div class="admin-card">
            <div class="admin-card-header">
                <h5><i class="bi bi-building" style="color:#4361ee;"></i> Company Information</h5>
            </div>

            <div style="padding: 24px; background-color: #fbfbfd;">
                @if ($data)
                    <form method="POST" action="{{ route('applications.update', $data->id) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="p-4 mb-3" style="background:#fff; border:1px solid #eef0f2; border-radius:10px;">

                            <div class="row">
                                <!-- Company Name -->
                                <div class="col-md-6 mb-3">
                                    <label for="company_name" class="form-label fw-bold"
                                        style="color:#1e1e2d; font-size:13px;">
                                        Company Name
                                    </label>
                                    <input type="text" class="form-control" id="company_name" name="company_name"
                                        placeholder="Enter company name"
                                        value="{{ old('company_name', $data->company_name) }}"
                                        style="border:1px solid #dfe2e8; border-radius:8px; padding:11px 14px; font-size:14px;">

                                    @error('company_name')
                                        <div class="text-danger mt-1" style="font-size:13px;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Company Email -->
                                <div class="col-md-6 mb-3">
                                    <label for="company_email" class="form-label fw-bold"
                                        style="color:#1e1e2d; font-size:13px;">
                                        Company Email
                                    </label>
                                    <input type="email" class="form-control" id="company_email" name="company_email"
                                        placeholder="Enter company email"
                                        value="{{ old('company_email', $data->company_email) }}"
                                        style="border:1px solid #dfe2e8; border-radius:8px; padding:11px 14px; font-size:14px;">

                                    @error('company_email')
                                        <div class="text-danger mt-1" style="font-size:13px;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Contact Number -->
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label fw-bold"
                                        style="color:#1e1e2d; font-size:13px;">
                                        Contact Number
                                    </label>
                                    <input type="text" class="form-control" id="phone" name="phone"
                                        placeholder="Enter contact number" value="{{ old('phone', $data->phone) }}"
                                        style="border:1px solid #dfe2e8; border-radius:8px; padding:11px 14px; font-size:14px;">

                                    @error('phone')
                                        <div class="text-danger mt-1" style="font-size:13px;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Copyright Text -->
                                <div class="col-md-6 mb-3">
                                    <label for="copy_right_text" class="form-label fw-bold"
                                        style="color:#1e1e2d; font-size:13px;">
                                        Copyright Text
                                    </label>
                                    <input type="text" class="form-control" id="copy_right_text"
                                        name="copy_right_text" placeholder="Enter Copyright Text"
                                        value="{{ old('copy_right_text', $data->copy_right_text) }}"
                                        style="border:1px solid #dfe2e8; border-radius:8px; padding:11px 14px; font-size:14px;">

                                    @error('copy_right_text')
                                        <div class="text-danger mt-1" style="font-size:13px;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Company Address -->
                                <div class="col-md-12 mb-3">
                                    <label for="address" class="form-label fw-bold"
                                        style="color:#1e1e2d; font-size:13px;">
                                        Company Address
                                    </label>
                                    <textarea class="form-control" id="address" name="address" placeholder="Enter company address" rows="2"
                                        style="border:1px solid #dfe2e8; border-radius:8px; padding:11px 14px; font-size:14px;">{{ old('address', $data->address) }}</textarea>

                                    @error('address')
                                        <div class="text-danger mt-1" style="font-size:13px;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Google Map -->
                                <div class="col-md-12 mb-3">
                                    <label for="map" class="form-label fw-bold"
                                        style="color:#1e1e2d; font-size:13px;">
                                        Google Map
                                    </label>
                                    <textarea class="form-control" id="map" name="map" placeholder="Google Map Link" rows="2"
                                        style="border:1px solid #dfe2e8; border-radius:8px; padding:11px 14px; font-size:14px;">{{ old('map', $data->google_map) }}</textarea>

                                    @error('map')
                                        <div class="text-danger mt-1" style="font-size:13px;">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                    @error('google_map')
                                        <div class="text-danger mt-1" style="font-size:13px;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- About Company -->
                                <div class="col-md-12 mb-3">
                                    <label for="about_company" class="form-label fw-bold"
                                        style="color:#1e1e2d; font-size:13px;">
                                        About Company
                                    </label>
                                    <textarea class="form-control" id="about_company" name="about_company"
                                        placeholder="Enter information about the company" rows="3"
                                        style="border:1px solid #dfe2e8; border-radius:8px; padding:11px 14px; font-size:14px;">{{ old('about_company', $data->about_company) }}</textarea>

                                    @error('about_company')
                                        <div class="text-danger mt-1" style="font-size:13px;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Company Logo -->
                                <div class="col-md-6 mb-3">
                                    <label for="logo" class="form-label fw-bold"
                                        style="color:#1e1e2d; font-size:13px;">
                                        Company Logo
                                    </label>
                                    <input type="file" class="form-control" id="logo" name="logo"
                                        style="border:1px solid #dfe2e8; border-radius:8px; padding:11px 14px; font-size:14px;">

                                    @error('logo')
                                        <div class="text-danger mt-1" style="font-size:13px;">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                    @if ($data->logo)
                                        <div class="mt-2">
                                            <img src="{{ asset('image/application/' . $data->logo) }}" height="90"
                                                style="object-fit:cover; border-radius:8px; border:1px solid #eef0f2;"
                                                alt="Current Logo">
                                        </div>
                                    @endif
                                </div>

                                <!-- Fav Icon -->
                                <div class="col-md-6 mb-3">
                                    <label for="fav_icon" class="form-label fw-bold"
                                        style="color:#1e1e2d; font-size:13px;">
                                        Fav Icon
                                    </label>
                                    <input type="file" class="form-control" id="fav_icon" name="fav_icon"
                                        style="border:1px solid #dfe2e8; border-radius:8px; padding:11px 14px; font-size:14px;">

                                    @error('fav_icon')
                                        <div class="text-danger mt-1" style="font-size:13px;">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                    @if ($data->fav_icon)
                                        <div class="mt-2">
                                            <img src="{{ asset('image/application/' . $data->fav_icon) }}"
                                                height="50"
                                                style="object-fit:cover; border-radius:8px; border:1px solid #eef0f2;"
                                                alt="Current Icon">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn submit-btn"
                                style="background-color:#4361ee; color:#fff; border-radius:8px; padding:8px 20px; font-size:14px; font-weight:600;">
                                <i class="bi bi-check-lg me-1"></i> Update Data
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
