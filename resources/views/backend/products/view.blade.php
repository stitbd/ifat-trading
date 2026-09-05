<div class="modal-header align-items-start"
    style="
        background:#fff;
        border-bottom:1px solid #eef0f2;
        padding:20px 24px;
    ">

    <div>

        <h5 class="modal-title mb-1"
            style="
                color:#1e1e2d;
                font-weight:700;
                font-size:18px;
            ">

            <i class="bi bi-box-seam-fill me-2" style="color:#4361ee;">
            </i>

            Product Details

        </h5>

        <p class="mb-0" style="
                color:#8a8a9a;
                font-size:13px;
            ">

            Complete information about this product

        </p>

    </div>

    <button type="button" class="btn-close" data-bs-dismiss="modal">
    </button>

</div>


<div class="modal-body" style="
        padding:24px;
        background:#fbfbfd;
    ">

    <!-- Product Header -->

    <div class="p-4 mb-3"
        style="
            background:#fff;
            border:1px solid #eef0f2;
            border-radius:10px;
        ">

        <div class="row align-items-center">

            <div class="col-md-3 text-center">

                @if ($data->image)
                    <img src="{{ asset('products/image/' . $data->image) }}" alt="Product Image"
                        style="
                            width:130px;
                            height:130px;
                            object-fit:cover;
                            border-radius:12px;
                            border:1px solid #eef0f2;
                        ">
                @else
                    <div
                        style="
                            width:130px;
                            height:130px;
                            margin:auto;
                            background:#f5f6f8;
                            border-radius:12px;
                            display:flex;
                            align-items:center;
                            justify-content:center;
                            color:#999;
                        ">

                        <i class="bi bi-image" style="font-size:40px;">
                        </i>

                    </div>
                @endif

            </div>


            <div class="col-md-9">

                <div
                    style="
                        color:#8a8a9a;
                        font-size:12px;
                        margin-bottom:5px;
                    ">

                    PRODUCT CODE

                </div>

                <h4
                    style="
                        color:#1e1e2d;
                        font-weight:700;
                        margin-bottom:8px;
                    ">

                    {{ $data->name }}

                </h4>

                <div
                    style="
                        color:#4361ee;
                        font-size:14px;
                        font-weight:600;
                    ">

                    {{ $data->product_code }}

                </div>

                <div class="mt-3">

                    @if ($data->status)
                        <span class="status-pill status-active">

                            <i class="bi bi-circle-fill"></i>
                            Active

                        </span>
                    @else
                        <span class="status-pill status-inactive">

                            <i class="bi bi-circle-fill"></i>
                            Inactive

                        </span>
                    @endif

                </div>

            </div>

        </div>

    </div>


    <!-- Basic Information -->

    <div class="p-4 mb-3"
        style="
            background:#fff;
            border:1px solid #eef0f2;
            border-radius:10px;
        ">

        <h6 class="fw-bold mb-3" style="color:#1e1e2d;">

            <i class="bi bi-info-circle me-2" style="color:#4361ee;">
            </i>

            Basic Information

        </h6>


        <div class="row">

            <div class="col-md-6 mb-3">

                <div class="view-label">
                    Wing
                </div>

                <div class="view-value">
                    {{ $data->wing?->name ?? '-' }}
                </div>

            </div>


            <div class="col-md-6 mb-3">

                <div class="view-label">
                    Category
                </div>

                <div class="view-value">
                    {{ $data->category?->name ?? '-' }}
                </div>

            </div>


            <div class="col-md-6 mb-3">

                <div class="view-label">
                    Sub Category
                </div>

                <div class="view-value">
                    {{ $data->subCategory?->name ?? '-' }}
                </div>

            </div>


            <div class="col-md-6 mb-3">

                <div class="view-label">
                    Brand
                </div>

                <div class="view-value">
                    {{ $data->brand?->name ?? '-' }}
                </div>

            </div>

            <div class="col-md-6 mb-3">

                <div class="view-label">
                    Hs Code
                </div>

                <div class="view-value">
                    {{ $data->hs_code ?? '-' }}
                </div>

            </div>
            <div class="col-md-6 mb-3">

                <div class="view-label">
                    Product Size
                </div>

                <div class="view-value">
                    {{ $data->product_size ?? '-' }}
                </div>

            </div>


            <div class="col-md-6 mb-3">

                <div class="view-label">
                    Manufacturer
                </div>

                <div class="view-value">
                    {{ $data->manufacturer?->name ?? '-' }}
                </div>

            </div>


            <div class="col-md-6 mb-3">

                <div class="view-label">
                    Country of Origin
                </div>

                <div class="view-value">
                    {{ $data->countryOfOrigin?->name ?? '-' }}
                </div>

            </div>

        </div>

    </div>


    <!-- Product Specification -->

    <div class="p-4 mb-3"
        style="
            background:#fff;
            border:1px solid #eef0f2;
            border-radius:10px;
        ">

        <h6 class="fw-bold mb-3" style="color:#1e1e2d;">

            <i class="bi bi-gear me-2" style="color:#4361ee;">
            </i>

            Product Specification

        </h6>


        <div class="row">

            <div class="col-md-6 mb-3">

                <div class="view-label">
                    Product Type
                </div>

                <div class="view-value">
                    {{ $data->productType?->name ?? '-' }}
                </div>

            </div>


            <div class="col-md-6 mb-3">

                <div class="view-label">
                    Vehicle Type
                </div>

                <div class="view-value">
                    {{ $data->vehicleType?->name ?? '-' }}
                </div>

            </div>


            {{-- <div class="col-md-6 mb-3">

                <div class="view-label">
                    Product Size
                </div>

                <div class="view-value">
                    {{ $data->productSize?->name ?? '-' }}
                </div>

            </div> --}}


            <div class="col-md-6 mb-3">

                <div class="view-label">
                    Warranty Period
                </div>

                <div class="view-value">
                    {{ $data->warrantyPeriod?->title ?? '-' }}
                </div>

            </div>


            <div class="col-md-6 mb-3">

                <div class="view-label">
                    VAT Percentage
                </div>

                <div class="view-value">
                    {{ $data->vatPercentage?->title ?? '-' }}
                </div>

            </div>

        </div>

    </div>


    <!-- Stock & Other Information -->

    <div class="p-4"
        style="
            background:#fff;
            border:1px solid #eef0f2;
            border-radius:10px;
        ">

        <h6 class="fw-bold mb-3" style="color:#1e1e2d;">

            <i class="bi bi-boxes me-2" style="color:#4361ee;">
            </i>

            Stock & Other Information

        </h6>


        <div class="row">

            <div class="col-md-6 mb-3">

                <div class="view-label">
                    Position
                </div>

                <div class="view-value">
                    {{ $data->position ?? '-' }}
                </div>

            </div>


            <div class="col-md-6 mb-3">

                <div class="view-label">
                    Unit of Measurement
                </div>

                <div class="view-value">
                    {{ $data->unit_of_measurement ?? '-' }}
                </div>

            </div>


            <div class="col-md-6">

                <div class="view-label">
                    Minimum Alert Stock
                </div>

                <div class="view-value">
                    {{ $data->min_alert_stock ?? '0' }}
                </div>

            </div>

        </div>

    </div>

</div>


<div class="modal-footer"
    style="
        background:#fff;
        border-top:1px solid #eef0f2;
        padding:16px 24px;
    ">

    <button type="button" class="btn" data-bs-dismiss="modal"
        style="
            border:1px solid #dfe2e8;
            color:#4a4a5a;
            border-radius:8px;
            padding:8px 18px;
        ">

        <i class="bi bi-x-lg me-1"></i>

        Close

    </button>

</div>


<style>
    .view-label {
        color: #8a8a9a;
        font-size: 12px;
        margin-bottom: 4px;
    }

    .view-value {
        color: #1e1e2d;
        font-size: 14px;
        font-weight: 600;
    }
</style>
