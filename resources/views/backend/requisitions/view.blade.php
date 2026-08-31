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
                class="bi bi-clipboard-check-fill me-2"
                style="color:#4361ee;">
            </i>

            Requisition Details

        </h5>

        <p
            class="mb-0"
            style="
                color:#8a8a9a;
                font-size:13px;
            ">

            Complete information about this requisition

        </p>

    </div>


    <button
        type="button"
        class="btn-close"
        data-bs-dismiss="modal">
    </button>

</div>


<div
    class="modal-body"
    style="
        padding:24px;
        background:#fbfbfd;
    ">


    <!-- Requisition Header -->

    <div
        class="p-4 mb-3"
        style="
            background:#fff;
            border:1px solid #eef0f2;
            border-radius:10px;
        ">

        <div class="row align-items-center">


            <div class="col-md-3 text-center">

                <div
                    style="
                        width:110px;
                        height:110px;
                        margin:auto;
                        background:#eef2ff;
                        border-radius:12px;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        color:#4361ee;
                    ">

                    <i
                        class="bi bi-clipboard-check"
                        style="font-size:48px;">
                    </i>

                </div>

            </div>


            <div class="col-md-9">


                <div
                    style="
                        color:#8a8a9a;
                        font-size:12px;
                        margin-bottom:5px;
                    ">

                    REQUISITION NO

                </div>


                <h4
                    style="
                        color:#1e1e2d;
                        font-weight:700;
                        margin-bottom:8px;
                    ">

                    {{ $data->requisition_no ?? '-' }}

                </h4>


                <div
                    style="
                        color:#4361ee;
                        font-size:14px;
                        font-weight:600;
                    ">

                    @switch($data->requisition_type)

                        @case(1)
                            Purchase
                            @break

                        @case(2)
                            Transfer
                            @break

                        @case(3)
                            Issue
                            @break

                        @default
                            {{ $data->requisition_type ?? '-' }}

                    @endswitch

                </div>


                <div class="mt-3">

                    @switch($data->status)

                        @case(1)

                            <span
                                class="status-pill status-active">

                                <i class="bi bi-circle-fill"></i>

                                Approved

                            </span>

                            @break


                        @case(0)

                            <span
                                class="status-pill status-inactive">

                                <i class="bi bi-circle-fill"></i>

                                Pending

                            </span>

                            @break


                        @case(2)

                            <span
                                class="status-pill status-warning">

                                <i class="bi bi-circle-fill"></i>

                                Rejected

                            </span>

                            @break


                        @default

                            <span
                                class="status-pill status-inactive">

                                <i class="bi bi-circle-fill"></i>

                                Unknown

                            </span>

                    @endswitch

                </div>

            </div>

        </div>

    </div>



    <!-- Basic Information -->

    <div
        class="p-4 mb-3"
        style="
            background:#fff;
            border:1px solid #eef0f2;
            border-radius:10px;
        ">

        <h6
            class="fw-bold mb-3"
            style="color:#1e1e2d;">

            <i
                class="bi bi-info-circle me-2"
                style="color:#4361ee;">
            </i>

            Basic Information

        </h6>


        <div class="row">


            <div class="col-md-6 mb-3">

                <div class="view-label">
                    Requisition No
                </div>

                <div class="view-value">
                    {{ $data->requisition_no ?? '-' }}
                </div>

            </div>


            <div class="col-md-6 mb-3">

                <div class="view-label">
                    Requisition Type
                </div>

                <div class="view-value">

                    @switch($data->requisition_type)

                        @case(1)
                            Purchase
                            @break

                        @case(2)
                            Transfer
                            @break

                        @case(3)
                            Issue
                            @break

                        @default
                            {{ $data->requisition_type ?? '-' }}

                    @endswitch

                </div>

            </div>


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
                    Warehouse
                </div>

                <div class="view-value">
                    {{ $data->warehouse?->name ?? '-' }}
                </div>

            </div>


            <div class="col-md-6 mb-3">

                <div class="view-label">
                    Requisition Date
                </div>

                <div class="view-value">

                    {{ $data->date
                        ? \Carbon\Carbon::parse($data->date)->format('d-m-Y')
                        : '-' }}

                </div>

            </div>


            <div class="col-md-6 mb-3">

                <div class="view-label">
                    Place of Supply
                </div>

                <div class="view-value">
                    {{ $data->place_of_supply ?? '-' }}
                </div>

            </div>


            <div class="col-md-6 mb-3">

                <div class="view-label">
                    Total Quantity
                </div>

                <div class="view-value">

                    {{ $data->total_quantity ?? 0 }}

                </div>

            </div>


            <div class="col-md-6 mb-3">

                <div class="view-label">
                    Status
                </div>

                <div class="view-value">

                    @switch($data->status)

                        @case(1)

                            <span
                                class="status-pill status-active">

                                <i class="bi bi-circle-fill"></i>
                                Approved

                            </span>

                            @break


                        @case(0)

                            <span
                                class="status-pill status-inactive">

                                <i class="bi bi-circle-fill"></i>
                                Pending

                            </span>

                            @break


                        @case(2)

                            <span
                                class="status-pill status-warning">

                                <i class="bi bi-circle-fill"></i>
                                Rejected

                            </span>

                            @break


                        @default

                            -

                    @endswitch

                </div>

            </div>


        </div>

    </div>



    <!-- Products -->

    <div
        class="p-4 mb-3"
        style="
            background:#fff;
            border:1px solid #eef0f2;
            border-radius:10px;
        ">

        <h6
            class="fw-bold mb-3"
            style="color:#1e1e2d;">

            <i
                class="bi bi-box-seam me-2"
                style="color:#4361ee;">
            </i>

            Requisition Products

        </h6>


        <div class="table-responsive">

            <table
                class="table table-bordered align-middle mb-0">

                <thead
                    style="background:#f8f9fa;">

                    <tr>

                        <th
                            style="width:60px;">
                            #
                        </th>

                        <th>
                            Product
                        </th>

                        <th
                            style="width:140px;">
                            Quantity
                        </th>

                        <th>
                            Note
                        </th>

                    </tr>

                </thead>


                <tbody>


                    @forelse(
                        $data->details
                        as $index => $detail
                    )

                        <tr>


                            <td>

                                <span
                                    class="serial-badge">

                                    {{ $index + 1 }}

                                </span>

                            </td>


                            <td>

                                <div
                                    class="product-name">

                                    {{ $detail->product?->name
                                        ?? 'Unknown Product' }}

                                </div>


                                @if(
                                    $detail->product?->product_code
                                )

                                    <div
                                        class="product-code">

                                        Code:
                                        {{ $detail->product->product_code }}

                                    </div>

                                @endif

                            </td>


                            <td>

                                <span
                                    class="quantity-badge">

                                    {{ $detail->quantity }}

                                </span>

                            </td>


                            <td>

                                {{ $detail->note ?? '-' }}

                            </td>


                        </tr>


                    @empty


                        <tr>

                            <td
                                colspan="4"
                                class="text-center text-muted py-4">

                                <i
                                    class="bi bi-box-seam"
                                    style="font-size:25px;">
                                </i>

                                <div class="mt-2">

                                    No products found.

                                </div>

                            </td>

                        </tr>


                    @endforelse


                </tbody>


                @if($data->details->count())

                    <tfoot>

                        <tr>

                            <th
                                colspan="2"
                                class="text-end">

                                Total Quantity

                            </th>

                            <th>

                                {{ $data->details->sum('quantity') }}

                            </th>

                            <th></th>

                        </tr>

                    </tfoot>

                @endif

            </table>

        </div>

    </div>



    <!-- Note -->

    @if($data->note)

        <div
            class="p-4"
            style="
                background:#fff;
                border:1px solid #eef0f2;
                border-radius:10px;
            ">

            <h6
                class="fw-bold mb-3"
                style="color:#1e1e2d;">

                <i
                    class="bi bi-chat-left-text me-2"
                    style="color:#4361ee;">
                </i>

                Requisition Note

            </h6>


            <div
                class="view-note">

                {{ $data->note }}

            </div>

        </div>

    @endif


</div>



<div
    class="modal-footer"
    style="
        background:#fff;
        border-top:1px solid #eef0f2;
        padding:16px 24px;
    ">

    <button
        type="button"
        class="btn"
        data-bs-dismiss="modal"
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

        color:#8a8a9a;

        font-size:12px;

        margin-bottom:4px;

    }


    .view-value {

        color:#1e1e2d;

        font-size:14px;

        font-weight:600;

    }


    .view-note {

        background:#f8f9fa;

        border:1px solid #eef0f2;

        border-radius:8px;

        padding:12px 15px;

        color:#555;

        font-size:14px;

        line-height:1.6;

    }


    .serial-badge {

        display:inline-flex;

        align-items:center;

        justify-content:center;

        min-width:30px;

        height:30px;

        background:#eef2ff;

        color:#4361ee;

        border-radius:6px;

        font-weight:600;

        font-size:13px;

    }


    .product-name {

        color:#1e1e2d;

        font-size:14px;

        font-weight:600;

    }


    .product-code {

        color:#8a8a9a;

        font-size:12px;

        margin-top:3px;

    }


    .quantity-badge {

        display:inline-flex;

        align-items:center;

        justify-content:center;

        min-width:45px;

        padding:6px 12px;

        background:#eef2ff;

        color:#4361ee;

        border-radius:6px;

        font-weight:600;

        font-size:13px;

    }

</style>