{{--
    Requisition Details - Modal (matches Print page design)
    -------------------------------------------------------------
    Expects `$data` = single Requisition model, eager-loaded with
    `wing`, `warehouse`, `details.product.brand`, `details.product.productType`,
    `details.product.productSize`, `createdBy`.
--}}
@php
    $company = \App\Models\Application::first();
    $companyLogo = $company ? $company->logo : '';
    $companyName = $company ? $company->company_name : '';
    $companyAddress = $company ? $company->address : '';
    $companyPhone = $company ? $company->phone : '';
    $companyEmail = $company ? $company->company_email : '';
@endphp

<div class="modal-header align-items-start" style="background:#fff; border-bottom:2px solid #14532d; padding:22px 24px;">

    <div class="w-100 text-center">

        @if ($companyLogo)
            <img src="{{ asset('image/application/' . $companyLogo) }}" alt="{{ $companyName }}"
                style="max-height:55px; margin-bottom:6px;">
        @endif

        <div style="color:#14532d; font-weight:700; font-size:20px; letter-spacing:1px;">
            {{ $companyName }}
        </div>
        <div style="color:#555; font-size:11px; line-height:1.5; margin-top:2px;">
            Address: {{ $companyAddress }} &nbsp;|&nbsp; Phone: {{ $companyPhone }} &nbsp;|&nbsp; {{ $companyEmail }}
        </div>
        <div style="color:#1e1e2d; font-weight:700; font-size:14px; text-decoration:underline; margin-top:10px;">
            REQUISITION FORM
        </div>
    </div>

    <button type="button" class="btn-close" data-bs-dismiss="modal"
        style="position:absolute; top:16px; right:16px;"></button>

</div>


<div class="modal-body" style="padding:24px; background:#fbfbfd;">

    {{-- ================= TOP INFO ================= --}}
    <div class="p-4 mb-3" style="background:#fff; border:1px solid #eef0f2; border-radius:10px;">

        <div class="row">

            <div class="col-md-6 mb-3">
                <div class="view-label">Requisition By</div>
                <div class="view-value">{{ $data->createdBy->name ?? '-' }}</div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="view-label">Requisition No</div>
                <div class="view-value">{{ $data->requisition_no ?? '-' }}</div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="view-label">Type of Procurement</div>
                <div class="view-value">
                    {{ $data->requisition_type === 'import' ? 'Import' : 'Local Purchase' }}
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="view-label">Requisition Date</div>
                <div class="view-value">
                    {{ $data->date ? \Carbon\Carbon::parse($data->date)->format('Y-m-d') : '-' }}
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="view-label">Wing</div>
                <div class="view-value">{{ $data->wing?->name ?? '-' }}</div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="view-label">Warehouse</div>
                <div class="view-value">{{ $data->warehouse?->name ?? '-' }}</div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="view-label">Place of Supply / Delivery Location</div>
                <div class="view-value">{{ $data->place_of_supply ?? '-' }}</div>
            </div>

        </div>

    </div>


    {{-- ================= ITEMS TABLE (matches Print page columns) ================= --}}
    <div class="p-4 mb-3" style="background:#fff; border:1px solid #eef0f2; border-radius:10px;">

        <h6 class="fw-bold mb-3" style="color:#1e1e2d;">
            <i class="bi bi-box-seam me-2" style="color:#14532d;"></i>
            Product Requisition Details
        </h6>

        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0" style="border-color:#333;">
                <thead style="background:#f3f3f3;">
                    <tr>
                        <th class="text-center" style="width:50px;">SL. No</th>
                        <th style="width:120px;">Category</th>
                        <th>Product</th>
                        <th>Note</th>
                        <th class="text-center" style="width:110px;">Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data->details as $index => $detail)
                        <tr>
                            <td class="text-center">
                                <span class="serial-badge">{{ $index + 1 }}</span>
                            </td>
                            <td>{{ $detail->product?->category?->name ?? '-' }}</td>
                            <td>
                                <strong>Name: </strong> {{ $detail->product?->name ?? 'Unknown Product' }}<br>
                                <strong>Brand: </strong> {{ $detail->product?->brand?->name ?? '-' }}<br>
                                <strong>Type: </strong> {{ $detail->product?->productType?->name ?? '-' }}<br>
                                <strong>Size: </strong> {{ $detail->product?->productSize?->name ?? '-' }}
                            </td>
                            <td>{{ $detail->note ?? '-' }}</td>
                            <td class="text-center">
                                <span class="quantity-badge">{{ number_format($detail->quantity, 0) }} Pcs</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="bi bi-box-seam" style="font-size:25px;"></i>
                                <div class="mt-2">No products found.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if ($data->details->count())
                    <tfoot>
                        <tr>
                            <th colspan="4" class="text-end">Total Quantity</th>
                            <th class="text-center">{{ number_format($data->details->sum('quantity'), 0) }} Pcs</th>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>

        @if ($data->note)
            <div class="view-note mt-3"><strong>Note:</strong> {{ $data->note }}</div>
        @endif
    </div>

    <div class="text-center" style="font-size:11px; color:#444; margin-top:20px;">
        This is a system generated Requisition. No signature required.
    </div>

</div>


<div class="modal-footer" style="background:#fff; border-top:1px solid #eef0f2; padding:16px 24px;">

    <button type="button" class="btn" data-bs-dismiss="modal"
        style="border:1px solid #dfe2e8; color:#4a4a5a; border-radius:8px; padding:8px 18px;">
        <i class="bi bi-x-lg me-1"></i> Close
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

    .view-note {
        background: #f8f9fa;
        border: 1px solid #eef0f2;
        border-radius: 8px;
        padding: 12px 15px;
        color: #555;
        font-size: 14px;
        line-height: 1.6;
    }

    .serial-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 30px;
        height: 30px;
        background: #eef2ff;
        color: #14532d;
        border-radius: 6px;
        font-weight: 600;
        font-size: 13px;
    }

    .quantity-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 55px;
        padding: 6px 12px;
        background: #eef2ff;
        color: #14532d;
        border-radius: 6px;
        font-weight: 600;
        font-size: 13px;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-pill i {
        font-size: 8px;
    }

    .status-active {
        background: #e6f4ea;
        color: #1e7e34;
    }

    .status-inactive {
        background: #f1f2f6;
        color: #6c757d;
    }

    .status-warning {
        background: #fdecea;
        color: #c0392b;
    }
</style>
