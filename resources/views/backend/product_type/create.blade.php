<div class="modal fade" id="productTypeCreateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 12px; border: none; overflow: hidden;">

            <!-- Header -->
            <div class="modal-header align-items-start"
                style="background-color: #ffffff; border-bottom: 1px solid #eef0f2; padding: 20px 24px;">
                <div>
                    <h5 class="modal-title mb-1" style="color:#1e1e2d; font-weight:700; font-size:18px;">
                        <i class="bi bi-grid-3x3-gap-fill me-2" style="color:#4361ee;"></i>
                        Create Product Type
                    </h5>
                    <p class="mb-0" style="color:#8a8a9a; font-size:13px;">
                        Add a new product type to your catalog
                    </p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Body -->
            <div class="modal-body" style="padding: 24px; background-color: #fbfbfd;">
                <form id="productTypeCreateForm" method="POST">
                    @csrf

                    <div class="p-4 mb-3" style="background:#fff; border:1px solid #eef0f2; border-radius:10px;">
                        {{-- <div class="d-flex align-items-center mb-1">
                            <span class="badge rounded-pill me-2"
                                style="background:#eaf0ff; color:#4361ee; font-weight:600; font-size:12px; padding:5px 10px;">01</span>
                            <span style="font-weight:700; color:#1e1e2d; font-size:15px;">Basic Information</span>
                        </div> --}}
                        {{-- <p class="mb-3" style="color:#8a8a9a; font-size:13px;">
                            Enter the details for this product type
                        </p> --}}

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold" style="color:#1e1e2d; font-size:13px;">
                                    Category <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" name="category_id"
                                    style="border:1px solid #dfe2e8; border-radius:8px; padding:11px 14px; font-size:14px;">
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback category-error"></div>
                            </div>
                            <!-- Name -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold" style="color:#1e1e2d; font-size:13px;">
                                    Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="name"
                                    placeholder="Enter Product Type"
                                    style="border:1px solid #dfe2e8; border-radius:8px; padding:11px 14px; font-size:14px;">
                                <div class="invalid-feedback name-error"></div>
                            </div>

                            <!-- Status -->
                            <div class="col-md-12">
                                <label class="form-label fw-bold" style="color:#1e1e2d; font-size:13px;">
                                    Status
                                </label>
                                <select class="form-select" name="status"
                                    style="border:1px solid #dfe2e8; border-radius:8px; padding:11px 14px; font-size:14px;">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                                <div class="invalid-feedback status-error"></div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="modal-footer d-flex justify-content-between align-items-center"
                style="background-color: #ffffff; border-top: 1px solid #eef0f2; padding: 16px 24px;">
                <span style="color:#8a8a9a; font-size:13px;">
                    <i class="bi bi-check-circle-fill text-success me-1"></i> 2 Fields
                </span>
                <div>
                    <button type="button" class="btn me-2" data-bs-dismiss="modal"
                        style="border:1px solid #dfe2e8; color:#4a4a5a; border-radius:8px; padding:8px 18px; font-size:14px;">
                        <i class="bi bi-x-lg me-1"></i> Cancel
                    </button>
                    <button type="submit" form="productTypeCreateForm" class="btn submit-btn"
                        style="background-color:#4361ee; color:#fff; border-radius:8px; padding:8px 20px; font-size:14px; font-weight:600;">
                        <i class="bi bi-check-lg me-1"></i> Create
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $("#productTypeCreateForm").on("submit", function(e) {
            showLoading();
            e.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                url: "{{ route('product-type.store') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $(".invalid-feedback").text("").hide();
                },
                success: function(response) {
                    if (response.success) {
                        $("#productTypeCreateForm")[0].reset();
                        $("#productTypeCreateModal").modal("hide");
                        hideLoading();
                        window.location.href =
                            "{{ route('product-type.index') }}?added-successfully=" +
                            encodeURIComponent(response.message);
                    }
                },
                error: function(xhr) {
                    hideLoading();
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        if (errors.category_id) $(".category-error").text(errors
                            .category_id[0]).show();
                        if (errors.name) $(".name-error").text(errors.name[0]).show();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.message ||
                                'Something went wrong!'
                        });
                    }
                }
            });
        });
    });
</script>
