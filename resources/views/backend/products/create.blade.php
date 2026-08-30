@extends('layouts.backend')

@section('title')
    Add Product
@endsection

@section('content')
    <div class="app-toolbar py-3 py-lg-6">
        <div class="app-container container-fluid">
            <div class="admin-page-header">
                <div class="admin-page-header-title">
                    <span class="icon-box"><i class="bi bi-box-seam-fill"></i></span>
                    <h1>Add Product</h1>
                </div>
                <a href="{{ route('product.index') }}" class="btn-admin-secondary">
                    <i class="bi bi-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
    </div>

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">

            <div class="admin-card">
                <div class="admin-card-header">
                    <h5>
                        <i class="bi bi-box-seam-fill" style="color:#4361ee;"></i>
                        Product Fields
                    </h5>
                </div>

                <div style="padding:24px;">

                    <form id="productCreateForm" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">

                            <!-- Wings -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Wings <span class="text-danger">*</span></label>
                                <select required class="form-select" name="wing_id">
                                    <option value="">Select Wing</option>
                                    @foreach ($wings as $wing)
                                        <option value="{{ $wing->id }}">{{ $wing->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback wing_id-error"></div>
                            </div>

                            <!-- Product Code -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Product Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="product_code" maxlength="25"
                                    placeholder="Enter Product Code" required>
                                <div class="invalid-feedback product_code-error"></div>
                            </div>

                            <!-- Product Name -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Product Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" maxlength="100"
                                    placeholder="Enter Product Name" required>
                                <div class="invalid-feedback name-error"></div>
                            </div>

                            <!-- Category -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Category <span class="text-danger">*</span></label>
                                <select class="form-select" name="categories_id" id="categories_id" required>
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback categories_id-error"></div>
                            </div>

                            <!-- Sub Category -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Sub Category</label>
                                <select class="form-select" name="sub_categories_id" id="sub_categories_id">
                                    <option value="">Select Sub Category</option>
                                </select>
                                <div class="invalid-feedback sub_categories_id-error"></div>
                            </div>

                            <!-- Brand -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Brand <span class="text-danger">*</span></label>
                                <select class="form-select" name="brand_id" required>
                                    <option value="">Select Brand</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback brand_id-error"></div>
                            </div>

                            <!-- Manufacturer -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Manufacturer</label>
                                <select class="form-select" name="manufacturer_id">
                                    <option value="">Select Manufacturer</option>
                                    @foreach ($manufacturers as $manufacturer)
                                        <option value="{{ $manufacturer->id }}">{{ $manufacturer->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback manufacturer_id-error"></div>
                            </div>

                            <!-- Country -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Country of Origin</label>
                                <select class="form-select" name="country_of_origin_id">
                                    <option value="">Select Country</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback country_of_origin_id-error"></div>
                            </div>

                            <!-- Product Type -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Product Type</label>
                                <select class="form-select" name="product_type_id" id="product_type_id">
                                    <option value="">Select Product Type</option>
                                </select>
                                <div class="invalid-feedback product_type_id-error"></div>
                            </div>

                            <!-- Vehicle Type -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Vehicle Type</label>
                                <select class="form-select" name="vehicle_type_id">
                                    <option value="">Select Vehicle Type</option>
                                    @foreach ($vehicleTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback vehicle_type_id-error"></div>
                            </div>

                            <!-- Product Size -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Product Size</label>
                                <select class="form-select" name="product_size_id" id="product_size_id">
                                    <option value="">Select Product Size</option>
                                </select>
                                <div class="invalid-feedback product_size_id-error"></div>
                            </div>

                            <!-- Warranty -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Warranty Period</label>
                                <select class="form-select" name="warranty_period_id">
                                    <option value="">Select Warranty</option>
                                    @foreach ($warrantyPeriods as $warranty)
                                        <option value="{{ $warranty->id }}">{{ $warranty->title }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback warranty_period_id-error"></div>
                            </div>

                            <!-- VAT -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">VAT Percentage</label>
                                <select class="form-select" name="vat_percentage_id">
                                    <option value="">Select VAT</option>
                                    @foreach ($vatPercentages as $vat)
                                        <option value="{{ $vat->id }}">{{ $vat->title }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback vat_percentage_id-error"></div>
                            </div>

                            <!-- Position -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Position</label>
                                <input type="text" class="form-control" name="position" maxlength="100"
                                    placeholder="Enter Position">
                                <div class="invalid-feedback position-error"></div>
                            </div>

                            <!-- Unit -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Unit of Measurement</label>
                                <input type="text" class="form-control" name="unit_of_measurement" maxlength="100"
                                    placeholder="e.g. Piece, Kg, Liter">
                                <div class="invalid-feedback unit_of_measurement-error"></div>
                            </div>

                            <!-- Min Stock -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Minimum Alert Stock</label>
                                <input type="number" class="form-control" name="min_alert_stock" min="0"
                                    placeholder="Enter Minimum Stock">
                                <div class="invalid-feedback min_alert_stock-error"></div>
                            </div>

                            <!-- Image -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Image</label>
                                <input type="file" class="form-control" name="image" accept="image/*">
                                <div class="invalid-feedback image-error"></div>
                            </div>

                            <!-- Status -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Status</label>
                                <select class="form-select" name="status">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                                <div class="invalid-feedback status-error"></div>
                            </div>

                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4 pt-3" style="border-top:1px solid #eef0f2;">
                            <a href="{{ route('product.index') }}" class="btn"
                                style="border:1px solid #dfe2e8;color:#4a4a5a;border-radius:8px;padding:8px 18px;">
                                <i class="bi bi-x-lg me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn submit-btn"
                                style="background:#4361ee;color:#fff;border-radius:8px;padding:8px 20px;font-weight:600;">
                                <i class="bi bi-check-lg me-1"></i> Create
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

    <script>
        $(document).ready(function() {

            $("#categories_id").on("change", function() {
                let categoryId = $(this).val();

                $("#sub_categories_id").html('<option value="">Select Sub Category</option>');
                $("#product_type_id").html('<option value="">Select Product Type</option>');
                $("#product_size_id").html('<option value="">Select Product Size</option>');

                if (!categoryId) return;

                $.ajax({
                    url: "{{ route('product.subcategories', ':id') }}".replace(":id", categoryId),
                    type: "GET",
                    success: function(data) {
                        $.each(data, function(i, item) {
                            $("#sub_categories_id").append($("<option>", {
                                value: item.id,
                                text: item.name
                            }));
                        });
                    }
                });

                $.ajax({
                    url: "{{ route('product.product-types', ':id') }}".replace(":id", categoryId),
                    type: "GET",
                    success: function(data) {
                        $.each(data, function(i, item) {
                            $("#product_type_id").append($("<option>", {
                                value: item.id,
                                text: item.name
                            }));
                        });
                    }
                });

                $.ajax({
                    url: "{{ route('product.product-sizes', ':id') }}".replace(":id", categoryId),
                    type: "GET",
                    success: function(data) {
                        $.each(data, function(i, item) {
                            $("#product_size_id").append($("<option>", {
                                value: item.id,
                                text: item.name
                            }));
                        });
                    }
                });
            });

            $("#productCreateForm").on("submit", function(e) {
                e.preventDefault();
                showLoading();

                let formData = new FormData(this);

                $.ajax({
                    url: "{{ route('product.store') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $(".invalid-feedback").text("").hide();
                    },
                    success: function(response) {
                        hideLoading();
                        if (response.success) {
                            window.location.href = "{{ route('product.index') }}" +
                                "?added-successfully=" + encodeURIComponent(response.message);
                        }
                    },
                    error: function(xhr) {
                        hideLoading();
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(field, messages) {
                                $("." + field + "-error").text(messages[0]).show();
                            });
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: xhr.responseJSON?.message ||
                                    "Something went wrong!"
                            });
                        }
                    }
                });
            });

        });
    </script>
@endsection
