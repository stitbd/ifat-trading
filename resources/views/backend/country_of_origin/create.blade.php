<div class="modal fade" id="countryOfOriginCreateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 12px; border: none; overflow: hidden;">

            <!-- Header -->
            <div class="modal-header align-items-start"
                style="background-color: #ffffff; border-bottom: 1px solid #eef0f2; padding: 20px 24px;">
                <div>
                    <h5 class="modal-title mb-1" style="color:#1e1e2d; font-weight:700; font-size:18px;">
                        <i class="bi bi-globe-americas me-2" style="color:#4361ee;"></i>
                        Create Country Of Origin
                    </h5>
                    <p class="mb-0" style="color:#8a8a9a; font-size:13px;">
                        Add a new country of origin
                    </p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Body -->
            <div class="modal-body" style="padding: 24px; background-color: #fbfbfd;">
                <form id="countryOfOriginCreateForm" method="POST">
                    @csrf

                    <div class="p-4 mb-3" style="background:#fff; border:1px solid #eef0f2; border-radius:10px;">

                        <div class="row">
                            <!-- Name -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold" style="color:#1e1e2d; font-size:13px;">
                                    Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="name"
                                    placeholder="Enter Country Name"
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
                    <button type="submit" form="countryOfOriginCreateForm" class="btn submit-btn"
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
        $("#countryOfOriginCreateForm").on("submit", function(e) {
            showLoading();
            e.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                url: "{{ route('country-of-origin.store') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $(".invalid-feedback").text("").hide();
                },
                success: function(response) {
                    if (response.success) {
                        $("#countryOfOriginCreateForm")[0].reset();
                        $("#countryOfOriginCreateModal").modal("hide");
                        hideLoading();
                        window.location.href =
                            "{{ route('country-of-origin.index') }}?added-successfully=" +
                            encodeURIComponent(response.message);
                    }
                },
                error: function(xhr) {
                    hideLoading();
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
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
