<div class="modal-header align-items-start"
    style="background-color: #ffffff; border-bottom: 1px solid #eef0f2; padding: 20px 24px;">
    <div>
        <h5 class="modal-title mb-1" style="color:#1e1e2d; font-weight:700; font-size:18px;">
            <i class="bi bi-truck me-2" style="color:#4361ee;"></i>
            Supplier Details
        </h5>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body" style="padding:24px; background:#fbfbfd;">

    <div class="p-4 mb-3 text-center" style="background:#fff; border:1px solid #eef0f2; border-radius:10px;">
        @if ($data->image)
            <img src="{{ asset('image/supplier/' . $data->image) }}" alt="{{ $data->name }}"
                style="height:100px; width:100px; object-fit:cover; border-radius:10px; border:1px solid #ddd;">
        @else
            <div style="color:#8a8a9a;">No image uploaded</div>
        @endif
    </div>

    <div class="p-4 mb-3" style="background:#fff; border:1px solid #eef0f2; border-radius:10px;">
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="view-label">Name</div>
                <div class="view-value">{{ $data->name }}</div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="view-label">Phone</div>
                <div class="view-value">{{ $data->phone ?? '-' }}</div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="view-label">Email</div>
                <div class="view-value">{{ $data->email ?? '-' }}</div>
            </div>
            <div class="col-md-12 mb-3">
                <div class="view-label">Address</div>
                <div class="view-value">{{ $data->address ?? '-' }}</div>
            </div>
        </div>
    </div>

    <div class="p-4" style="background:#fff; border:1px solid #eef0f2; border-radius:10px;">
        <h6 class="fw-bold mb-3" style="color:#1e1e2d;">Company Info</h6>
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="view-label">Company Name</div>
                <div class="view-value">{{ $data->company_name ?? '-' }}</div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="view-label">Company Email</div>
                <div class="view-value">{{ $data->company_email ?? '-' }}</div>
            </div>
            <div class="col-md-12">
                <div class="view-label">Company Address</div>
                <div class="view-value">{{ $data->company_address ?? '-' }}</div>
            </div>
        </div>
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
</style>
