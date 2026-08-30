<div
    class="modal fade"
    id="productCreateModal"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog modal-xl">

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
                    background-color:#fff;
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
                            class="bi bi-box-seam-fill me-2"
                            style="color:#4361ee;">
                        </i>

                        Create Product

                    </h5>

                    <p
                        class="mb-0"
                        style="
                            color:#8a8a9a;
                            font-size:13px;
                        ">

                        Add a new product to your catalog

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
                    background-color:#fbfbfd;
                ">

                <form
                    id="productCreateForm"
                    method="POST"
                    enctype="multipart/form-data">

                    @csrf


                    <div
                        class="p-4 mb-3"
                        style="
                            background:#fff;
                            border:1px solid #eef0f2;
                            border-radius:10px;
                        ">

                        <div class="row">


                            <!-- Wings -->

                            <div class="col-md-6 mb-3">

                                <label class="form-label fw-bold">
                                    Wings <span class="text-danger">*</span>
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
                                            value="{{ $wing->id }}">

                                            {{ $wing->name }}

                                        </option>

                                    @endforeach

                                </select>

                                <div
                                    class="invalid-feedback wing_id-error">
                                </div>

                            </div>


                            <!-- Product Code -->

                            <div class="col-md-6 mb-3">

                                <label
                                    class="form-label fw-bold"
                                    style="
                                        color:#1e1e2d;
                                        font-size:13px;
                                    ">

                                    Product Code
                                    <span class="text-danger">*</span>

                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    name="product_code"
                                    maxlength="25"
                                    placeholder="Enter Product Code"
                                    required>

                                <div
                                    class="invalid-feedback product_code-error">
                                </div>

                            </div>


                            <!-- Product Name -->

                            <div class="col-md-6 mb-3">

                                <label
                                    class="form-label fw-bold"
                                    style="
                                        color:#1e1e2d;
                                        font-size:13px;
                                    ">

                                    Product Name
                                    <span class="text-danger">*</span>

                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    name="name"
                                    maxlength="100"
                                    placeholder="Enter Product Name"
                                    required>

                                <div
                                    class="invalid-feedback name-error">
                                </div>

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
                                    id="categories_id"
                                    required>

                                    <option value="">
                                        Select Category
                                    </option>

                                    @foreach($categories as $category)

                                        <option
                                            value="{{ $category->id }}">

                                            {{ $category->name }}

                                        </option>

                                    @endforeach

                                </select>

                                <div
                                    class="invalid-feedback categories_id-error">
                                </div>

                            </div>


                            <!-- Sub Category -->

                            <div class="col-md-6 mb-3">

                                <label class="form-label fw-bold">
                                    Sub Category
                                </label>

                                <select
                                    class="form-select"
                                    name="sub_categories_id"
                                    id="sub_categories_id">

                                    <option value="">
                                        Select Sub Category
                                    </option>

                                </select>

                                <div
                                    class="invalid-feedback sub_categories_id-error">
                                </div>

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
                                            value="{{ $brand->id }}">

                                            {{ $brand->name }}

                                        </option>

                                    @endforeach

                                </select>

                                <div
                                    class="invalid-feedback brand_id-error">
                                </div>

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
                                            value="{{ $manufacturer->id }}">

                                            {{ $manufacturer->name }}

                                        </option>

                                    @endforeach

                                </select>

                                <div
                                    class="invalid-feedback manufacturer_id-error">
                                </div>

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
                                            value="{{ $country->id }}">

                                            {{ $country->name }}

                                        </option>

                                    @endforeach

                                </select>

                                <div
                                    class="invalid-feedback country_of_origin_id-error">
                                </div>

                            </div>


                            <!-- Product Type -->

                            <div class="col-md-6 mb-3">

                                <label class="form-label fw-bold">
                                    Product Type
                                </label>

                                <select
                                    class="form-select"
                                    name="product_type_id"
                                    id="product_type_id">

                                    <option value="">
                                        Select Product Type
                                    </option>

                                </select>

                                <div
                                    class="invalid-feedback product_type_id-error">
                                </div>

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
                                            value="{{ $type->id }}">

                                            {{ $type->name }}

                                        </option>

                                    @endforeach

                                </select>

                                <div
                                    class="invalid-feedback vehicle_type_id-error">
                                </div>

                            </div>


                            <!-- Product Size -->

                            <div class="col-md-6 mb-3">

                                <label class="form-label fw-bold">
                                    Product Size
                                </label>

                                <select
                                    class="form-select"
                                    name="product_size_id"
                                    id="product_size_id">

                                    <option value="">
                                        Select Product Size
                                    </option>

                                </select>

                                <div
                                    class="invalid-feedback product_size_id-error">
                                </div>

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
                                            value="{{ $warranty->id }}">

                                            {{ $warranty->title }}

                                        </option>

                                    @endforeach

                                </select>

                                <div
                                    class="invalid-feedback warranty_period_id-error">
                                </div>

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
                                            value="{{ $vat->id }}">

                                            {{ $vat->title }}

                                        </option>

                                    @endforeach

                                </select>

                                <div
                                    class="invalid-feedback vat_percentage_id-error">
                                </div>

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
                                    placeholder="Enter Position">

                                <div
                                    class="invalid-feedback position-error">
                                </div>

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
                                    placeholder="e.g. Piece, Kg, Liter">

                                <div
                                    class="invalid-feedback unit_of_measurement-error">
                                </div>

                            </div>


                            <!-- Min Stock -->

                            <div class="col-md-6 mb-3">

                                <label class="form-label fw-bold">
                                    Minimum Alert Stock
                                </label>

                                <input
                                    type="number"
                                    class="form-control"
                                    name="min_alert_stock"
                                    min="0"
                                    placeholder="Enter Minimum Stock">

                                <div
                                    class="invalid-feedback min_alert_stock-error">
                                </div>

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

                                <div
                                    class="invalid-feedback image-error">
                                </div>

                            </div>


                            <!-- Status -->

                            <div class="col-md-6">

                                <label class="form-label fw-bold">
                                    Status
                                </label>

                                <select
                                    class="form-select"
                                    name="status">

                                    <option value="1">
                                        Active
                                    </option>

                                    <option value="0">
                                        Inactive
                                    </option>

                                </select>

                                <div
                                    class="invalid-feedback status-error">
                                </div>

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

                    <i
                        class="bi bi-check-circle-fill text-success me-1">
                    </i>

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
                        form="productCreateForm"
                        class="btn submit-btn"
                        style="
                            background:#4361ee;
                            color:#fff;
                            border-radius:8px;
                            padding:8px 20px;
                            font-weight:600;
                        ">

                        <i class="bi bi-check-lg me-1"></i>
                        Create

                    </button>

                </div>

            </div>

        </div>

    </div>

</div>


<script>

$(document).ready(function () {


    /*
    |--------------------------------------------------------------------------
    | Category Change
    |--------------------------------------------------------------------------
    */

    $("#categories_id").on("change", function () {

        let categoryId = $(this).val();


        /*
        |--------------------------------------------------------------------------
        | Reset Dependent Dropdowns
        |--------------------------------------------------------------------------
        */

        $("#sub_categories_id").html(
            '<option value="">Select Sub Category</option>'
        );

        $("#product_type_id").html(
            '<option value="">Select Product Type</option>'
        );

        $("#product_size_id").html(
            '<option value="">Select Product Size</option>'
        );


        /*
        |--------------------------------------------------------------------------
        | No Category Selected
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

                    $("#sub_categories_id").append(

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

                    $("#product_type_id").append(

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

                    $("#product_size_id").append(

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
    | Product Create
    |--------------------------------------------------------------------------
    */

    $("#productCreateForm").on(
        "submit",
        function (e) {

            e.preventDefault();

            showLoading();

            let formData =
                new FormData(this);

            $.ajax({

                url:
                    "{{ route('product.store') }}",

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

                        $("#productCreateForm")[0]
                            .reset();


                        /*
                        |--------------------------------------------------------------------------
                        | Reset Dependent Dropdowns
                        |--------------------------------------------------------------------------
                        */

                        $("#sub_categories_id").html(
                            '<option value="">Select Sub Category</option>'
                        );

                        $("#product_type_id").html(
                            '<option value="">Select Product Type</option>'
                        );

                        $("#product_size_id").html(
                            '<option value="">Select Product Size</option>'
                        );


                        $("#productCreateModal")
                            .modal("hide");

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
                            function (
                                field,
                                messages
                            ) {

                                $("." +
                                    field +
                                    "-error")
                                    .text(
                                        messages[0]
                                    )
                                    .show();

                            }
                        );

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

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Reset Modal When Closed
    |--------------------------------------------------------------------------
    */

    $("#productCreateModal").on(
        "hidden.bs.modal",
        function () {

            $("#productCreateForm")[0].reset();

            $("#sub_categories_id").html(
                '<option value="">Select Sub Category</option>'
            );

            $("#product_type_id").html(
                '<option value="">Select Product Type</option>'
            );

            $("#product_size_id").html(
                '<option value="">Select Product Size</option>'
            );

            $(".invalid-feedback")
                .text("")
                .hide();

        }
    );

});

</script>
