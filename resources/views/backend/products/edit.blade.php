<div
    class="modal-content"
    style="
        border-radius:12px;
        border:none;
        overflow:hidden;
    ">

    <!-- Header -->

    <div
        class="modal-header align-items-start"
        style="
            background:#fff;
            border-bottom:1px solid #eef0f2;
            padding:20px 24px;
        ">

        <div>

            <h5
                class="modal-title mb-1"
                style="
                    color:#1e1e2d;
                    font-weight:700;
                    font-size:18px;
                ">

                <i
                    class="bi bi-pencil-square me-2"
                    style="color:#4361ee;">
                </i>

                Edit Product

            </h5>

            <p
                class="mb-0"
                style="
                    color:#8a8a9a;
                    font-size:13px;
                ">

                Update the details for this product

            </p>

        </div>

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal">
        </button>

    </div>


    <!-- Body -->

    <div
        class="modal-body"
        style="
            padding:24px;
            background:#fbfbfd;
        ">

        <form
            id="productEditForm"
            method="POST"
            enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <input
                type="hidden"
                id="product_id"
                value="{{ $data->id }}">


            <div
                class="p-4"
                style="
                    background:#fff;
                    border:1px solid #eef0f2;
                    border-radius:10px;
                ">

                <div class="row">

                    <!-- Wings -->

                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">
                            Wings
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            required
                            class="form-select"
                            name="wing_id">

                            <option value="">
                                Select Wing
                            </option>

                            @foreach($wings as $wing)

                                <option
                                    value="{{ $wing->id }}"
                                    {{ $data->wing_id == $wing->id ? 'selected' : '' }}>

                                    {{ $wing->name }}

                                </option>

                            @endforeach

                        </select>

                        <div class="invalid-feedback wing_id-error"></div>

                    </div>


                    <!-- Product Code -->

                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">
                            Product Code
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            name="product_code"
                            maxlength="25"
                            value="{{ $data->product_code }}"
                            placeholder="Enter Product Code"
                            required>

                        <div class="invalid-feedback product_code-error"></div>

                    </div>


                    <!-- Product Name -->

                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">
                            Product Name
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            name="name"
                            maxlength="100"
                            value="{{ $data->name }}"
                            placeholder="Enter Product Name"
                            required>

                        <div class="invalid-feedback name-error"></div>

                    </div>


                    <!-- Category -->

                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">
                            Category
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            class="form-select"
                            name="categories_id"
                            id="edit_categories_id"
                            required>

                            <option value="">
                                Select Category
                            </option>

                            @foreach($categories as $category)

                                <option
                                    value="{{ $category->id }}"
                                    {{ $data->categories_id == $category->id ? 'selected' : '' }}>

                                    {{ $category->name }}

                                </option>

                            @endforeach

                        </select>

                        <div class="invalid-feedback categories_id-error"></div>

                    </div>


                    <!-- Sub Category -->

                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">
                            Sub Category
                        </label>

                        <select
                            class="form-select"
                            name="sub_categories_id"
                            id="edit_sub_categories_id">

                            <option value="">
                                Select Sub Category
                            </option>

                            @foreach($subcategories as $subcategory)

                                <option
                                    value="{{ $subcategory->id }}"
                                    {{ $data->sub_categories_id == $subcategory->id ? 'selected' : '' }}>

                                    {{ $subcategory->name }}

                                </option>

                            @endforeach

                        </select>

                        <div class="invalid-feedback sub_categories_id-error"></div>

                    </div>


                    <!-- Brand -->

                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">
                            Brand
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            class="form-select"
                            name="brand_id"
                            required>

                            <option value="">
                                Select Brand
                            </option>

                            @foreach($brands as $brand)

                                <option
                                    value="{{ $brand->id }}"
                                    {{ $data->brand_id == $brand->id ? 'selected' : '' }}>

                                    {{ $brand->name }}

                                </option>

                            @endforeach

                        </select>

                        <div class="invalid-feedback brand_id-error"></div>

                    </div>


                    <!-- Manufacturer -->

                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">
                            Manufacturer
                        </label>

                        <select
                            class="form-select"
                            name="manufacturer_id">

                            <option value="">
                                Select Manufacturer
                            </option>

                            @foreach($manufacturers as $manufacturer)

                                <option
                                    value="{{ $manufacturer->id }}"
                                    {{ $data->manufacturer_id == $manufacturer->id ? 'selected' : '' }}>

                                    {{ $manufacturer->name }}

                                </option>

                            @endforeach

                        </select>

                        <div class="invalid-feedback manufacturer_id-error"></div>

                    </div>


                    <!-- Country -->

                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">
                            Country of Origin
                        </label>

                        <select
                            class="form-select"
                            name="country_of_origin_id">

                            <option value="">
                                Select Country
                            </option>

                            @foreach($countries as $country)

                                <option
                                    value="{{ $country->id }}"
                                    {{ $data->country_of_origin_id == $country->id ? 'selected' : '' }}>

                                    {{ $country->name }}

                                </option>

                            @endforeach

                        </select>

                        <div class="invalid-feedback country_of_origin_id-error"></div>

                    </div>


                    <!-- Product Type -->

                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">
                            Product Type
                        </label>

                        <select
                            class="form-select"
                            name="product_type_id"
                            id="edit_product_type_id">

                            <option value="">
                                Select Product Type
                            </option>

                            @foreach($productTypes as $type)

                                <option
                                    value="{{ $type->id }}"
                                    {{ $data->product_type_id == $type->id ? 'selected' : '' }}>

                                    {{ $type->name }}

                                </option>

                            @endforeach

                        </select>

                        <div class="invalid-feedback product_type_id-error"></div>

                    </div>


                    <!-- Vehicle Type -->

                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">
                            Vehicle Type
                        </label>

                        <select
                            class="form-select"
                            name="vehicle_type_id">

                            <option value="">
                                Select Vehicle Type
                            </option>

                            @foreach($vehicleTypes as $type)

                                <option
                                    value="{{ $type->id }}"
                                    {{ $data->vehicle_type_id == $type->id ? 'selected' : '' }}>

                                    {{ $type->name }}

                                </option>

                            @endforeach

                        </select>

                        <div class="invalid-feedback vehicle_type_id-error"></div>

                    </div>


                    <!-- Product Size -->

                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">
                            Product Size
                        </label>

                        <select
                            class="form-select"
                            name="product_size_id"
                            id="edit_product_size_id">

                            <option value="">
                                Select Product Size
                            </option>

                            @foreach($productSizes as $size)

                                <option
                                    value="{{ $size->id }}"
                                    {{ $data->product_size_id == $size->id ? 'selected' : '' }}>

                                    {{ $size->name }}

                                </option>

                            @endforeach

                        </select>

                        <div class="invalid-feedback product_size_id-error"></div>

                    </div>


                    <!-- Warranty -->

                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">
                            Warranty Period
                        </label>

                        <select
                            class="form-select"
                            name="warranty_period_id">

                            <option value="">
                                Select Warranty
                            </option>

                            @foreach($warrantyPeriods as $warranty)

                                <option
                                    value="{{ $warranty->id }}"
                                    {{ $data->warranty_period_id == $warranty->id ? 'selected' : '' }}>

                                    {{ $warranty->title }}

                                </option>

                            @endforeach

                        </select>

                        <div class="invalid-feedback warranty_period_id-error"></div>

                    </div>


                    <!-- VAT -->

                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">
                            VAT Percentage
                        </label>

                        <select
                            class="form-select"
                            name="vat_percentage_id">

                            <option value="">
                                Select VAT
                            </option>

                            @foreach($vatPercentages as $vat)

                                <option
                                    value="{{ $vat->id }}"
                                    {{ $data->vat_percentage_id == $vat->id ? 'selected' : '' }}>

                                    {{ $vat->title }}

                                </option>

                            @endforeach

                        </select>

                        <div class="invalid-feedback vat_percentage_id-error"></div>

                    </div>


                    <!-- Position -->

                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">
                            Position
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            name="position"
                            maxlength="100"
                            value="{{ $data->position }}"
                            placeholder="Enter Position">

                        <div class="invalid-feedback position-error"></div>

                    </div>


                    <!-- Unit -->

                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">
                            Unit of Measurement
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            name="unit_of_measurement"
                            maxlength="100"
                            value="{{ $data->unit_of_measurement }}"
                            placeholder="e.g. Piece, Kg, Liter">

                        <div class="invalid-feedback unit_of_measurement-error"></div>

                    </div>


                    <!-- Minimum Stock -->

                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">
                            Minimum Alert Stock
                        </label>

                        <input
                            type="number"
                            class="form-control"
                            name="min_alert_stock"
                            min="0"
                            value="{{ $data->min_alert_stock }}"
                            placeholder="Enter Minimum Stock">

                        <div class="invalid-feedback min_alert_stock-error"></div>

                    </div>


                    <!-- Image -->

                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">
                            Image
                        </label>

                        <input
                            type="file"
                            class="form-control"
                            name="image"
                            accept="image/*">

                        <div class="invalid-feedback image-error"></div>

                        @if($data->image)

                            <div class="mt-2">

                                <img
                                    src="{{ asset('products/image/' . $data->image) }}"
                                    width="90"
                                    height="90"
                                    style="
                                        object-fit:cover;
                                        border-radius:8px;
                                        border:1px solid #eef0f2;
                                    "
                                    alt="Product Image">

                            </div>

                        @endif

                    </div>


                    <!-- Status -->

                    <div class="col-md-6">

                        <label class="form-label fw-bold">
                            Status
                        </label>

                        <select
                            class="form-select"
                            name="status">

                            <option
                                value="1"
                                {{ $data->status == 1 ? 'selected' : '' }}>
                                Active
                            </option>

                            <option
                                value="0"
                                {{ $data->status == 0 ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>

                        <div class="invalid-feedback status-error"></div>

                    </div>

                </div>

            </div>

        </form>

    </div>


    <!-- Footer -->

    <div
        class="modal-footer d-flex justify-content-between align-items-center"
        style="
            background:#fff;
            border-top:1px solid #eef0f2;
            padding:16px 24px;
        ">

        <span
            style="
                color:#8a8a9a;
                font-size:13px;
            ">

            <i class="bi bi-check-circle-fill text-success me-1"></i>

            Product Fields

        </span>


        <div>

            <button
                type="button"
                class="btn me-2"
                data-bs-dismiss="modal"
                style="
                    border:1px solid #dfe2e8;
                    color:#4a4a5a;
                    border-radius:8px;
                    padding:8px 18px;
                ">

                <i class="bi bi-x-lg me-1"></i>
                Cancel

            </button>


            <button
                type="submit"
                form="productEditForm"
                class="btn submit-btn"
                style="
                    background:#4361ee;
                    color:#fff;
                    border-radius:8px;
                    padding:8px 20px;
                    font-weight:600;
                ">

                <i class="bi bi-check-lg me-1"></i>
                Update

            </button>

        </div>

    </div>

</div>


<script>

$(document).ready(function () {

    /*
    |--------------------------------------------------------------------------
    | Category Change - Edit Product
    |--------------------------------------------------------------------------
    */

    $("#edit_categories_id").on("change", function () {

        let categoryId = $(this).val();


        /*
        |--------------------------------------------------------------------------
        | Reset Dependent Dropdowns
        |--------------------------------------------------------------------------
        */

        $("#edit_sub_categories_id").html(
            '<option value="">Select Sub Category</option>'
        );

        $("#edit_product_type_id").html(
            '<option value="">Select Product Type</option>'
        );

        $("#edit_product_size_id").html(
            '<option value="">Select Product Size</option>'
        );


        /*
        |--------------------------------------------------------------------------
        | If Category Empty
        |--------------------------------------------------------------------------
        */

        if (!categoryId) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Load Sub Categories
        |--------------------------------------------------------------------------
        */

        $.ajax({

            url: "{{ route('product.subcategories', ':id') }}"
                .replace(":id", categoryId),

            type: "GET",

            success: function (data) {

                $.each(data, function (index, item) {

                    $("#edit_sub_categories_id").append(

                        $("<option>", {
                            value: item.id,
                            text: item.name
                        })

                    );

                });

            },

            error: function (xhr) {

                Swal.fire({

                    icon: "error",
                    title: "Error",

                    text:
                        xhr.responseJSON?.message ||
                        "Unable to load sub categories!"

                });

            }

        });


        /*
        |--------------------------------------------------------------------------
        | Load Product Types
        |--------------------------------------------------------------------------
        */

        $.ajax({

            url: "{{ route('product.product-types', ':id') }}"
                .replace(":id", categoryId),

            type: "GET",

            success: function (data) {

                $.each(data, function (index, item) {

                    $("#edit_product_type_id").append(

                        $("<option>", {
                            value: item.id,
                            text: item.name
                        })

                    );

                });

            },

            error: function (xhr) {

                Swal.fire({

                    icon: "error",
                    title: "Error",

                    text:
                        xhr.responseJSON?.message ||
                        "Unable to load product types!"

                });

            }

        });


        /*
        |--------------------------------------------------------------------------
        | Load Product Sizes
        |--------------------------------------------------------------------------
        */

        $.ajax({

            url: "{{ route('product.product-sizes', ':id') }}"
                .replace(":id", categoryId),

            type: "GET",

            success: function (data) {

                $.each(data, function (index, item) {

                    $("#edit_product_size_id").append(

                        $("<option>", {
                            value: item.id,
                            text: item.name
                        })

                    );

                });

            },

            error: function (xhr) {

                Swal.fire({

                    icon: "error",
                    title: "Error",

                    text:
                        xhr.responseJSON?.message ||
                        "Unable to load product sizes!"

                });

            }

        });

    });


    /*
    |--------------------------------------------------------------------------
    | Product Update
    |--------------------------------------------------------------------------
    */

    $("#productEditForm").on("submit", function (e) {

        e.preventDefault();

        showLoading();

        let formData = new FormData(this);

        let productId = $("#product_id").val();


        $.ajax({

            url: "{{ route('product.update', ':id') }}"
                .replace(":id", productId),

            type: "POST",

            data: formData,

            processData: false,

            contentType: false,

            beforeSend: function () {

                $(".invalid-feedback")
                    .text("")
                    .hide();

            },

            success: function (response) {

                if (response.success) {

                    $("#productEditModal").modal("hide");

                    hideLoading();

                    window.location.href =
                        "{{ route('product.index') }}" +
                        "?added-successfully=" +
                        encodeURIComponent(
                            response.message
                        );

                }

            },

            error: function (xhr) {

                hideLoading();

                if (xhr.status === 422) {

                    let errors =
                        xhr.responseJSON.errors;

                    $.each(
                        errors,
                        function (field, messages) {

                            $("." + field + "-error")
                                .text(messages[0])
                                .show();

                        }
                    );

                    setTimeout(function () {

                        $(".invalid-feedback")
                            .fadeOut();

                    }, 3000);

                } else if (xhr.status === 403) {

                    Swal.fire({

                        icon: "error",
                        title: "Access Denied",

                        text:
                            xhr.responseJSON?.message ||
                            "You do not have permission!"

                    });

                } else if (xhr.status === 404) {

                    Swal.fire({

                        icon: "error",
                        title: "Not Found",

                        text:
                            xhr.responseJSON?.message ||
                            "Product not found!"

                    });

                } else {

                    Swal.fire({

                        icon: "error",
                        title: "Error",

                        text:
                            xhr.responseJSON?.message ||
                            "Something went wrong!"

                    });

                }

            }

        });

    });


    /*
    |--------------------------------------------------------------------------
    | Reset Error Messages When Modal Closed
    |--------------------------------------------------------------------------
    */

    $("#productEditModal").on("hidden.bs.modal", function () {

        $(".invalid-feedback")
            .text("")
            .hide();

    });

});

</script>
