<div class="modal fade" id="wingCreateModal" tabindex="-1" aria-labelledby="wingCreateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 12px; border: none; overflow: hidden;">

            <!-- Header -->
            <div class="modal-header align-items-start"
                style="background-color: #ffffff; border-bottom: 1px solid #eef0f2; padding: 20px 24px;">
                <div>
                    <h5 class="modal-title mb-1" id="wingCreateModalLabel"
                        style="color:#1e1e2d; font-weight:700; font-size:18px;">
                        <i class="bi bi-building-fill-add me-2" style="color:#4361ee;"></i>
                        Create Wing
                    </h5>
                    <p class="mb-0" style="color:#8a8a9a; font-size:13px;">
                        Add a new wing to your organization
                    </p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Body -->
            <div class="modal-body" style="padding: 24px; background-color: #fbfbfd;">
                <form id="wingCreateModalForm" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="p-4 mb-3" style="background:#fff; border:1px solid #eef0f2; border-radius:10px;">

                        <div class="row">
                            <!-- Name -->
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label fw-bold" style="color:#1e1e2d; font-size:13px;">
                                    Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="name" name="name" maxlength="100"
                                    placeholder="Enter wing name"
                                    style="border:1px solid #dfe2e8; border-radius:8px; padding:11px 14px; font-size:14px;">
                                <div class="invalid-feedback name-error"></div>
                            </div>

                            <!-- Imported Number -->
                            <div class="col-md-6 mb-3">
                                <label for="imported_number" class="form-label fw-bold"
                                    style="color:#1e1e2d; font-size:13px;">
                                    Imported Number
                                </label>
                                <input type="text" class="form-control" id="imported_number" name="imported_number"
                                    maxlength="100" placeholder="Enter imported number"
                                    style="border:1px solid #dfe2e8; border-radius:8px; padding:11px 14px; font-size:14px;">
                                <div class="invalid-feedback imported_number-error"></div>
                            </div>

                            <!-- BIN Number -->
                            <div class="col-md-6 mb-3">
                                <label for="bin_number" class="form-label fw-bold"
                                    style="color:#1e1e2d; font-size:13px;">
                                    BIN Number
                                </label>
                                <input type="text" class="form-control" id="bin_number" name="bin_number"
                                    maxlength="50" placeholder="Enter BIN number"
                                    style="border:1px solid #dfe2e8; border-radius:8px; padding:11px 14px; font-size:14px;">
                                <div class="invalid-feedback bin_number-error"></div>
                            </div>

                            <!-- Mobile Number -->
                            <div class="col-md-6 mb-3">
                                <label for="mobile_number" class="form-label fw-bold"
                                    style="color:#1e1e2d; font-size:13px;">
                                    Mobile Number
                                </label>
                                <input type="text" class="form-control" id="mobile_number" name="mobile_number"
                                    maxlength="50" placeholder="Enter mobile number"
                                    style="border:1px solid #dfe2e8; border-radius:8px; padding:11px 14px; font-size:14px;">
                                <div class="invalid-feedback mobile_number-error"></div>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label fw-bold" style="color:#1e1e2d; font-size:13px;">
                                    Email
                                </label>
                                <input type="email" class="form-control" id="email" name="email" maxlength="50"
                                    placeholder="Enter email"
                                    style="border:1px solid #dfe2e8; border-radius:8px; padding:11px 14px; font-size:14px;">
                                <div class="invalid-feedback email-error"></div>
                            </div>

                            <!-- Image -->
                            <div class="col-md-6 mb-3">
                                <label for="image" class="form-label fw-bold" style="color:#1e1e2d; font-size:13px;">
                                    Image
                                </label>
                                <input type="file" class="form-control" id="image" name="image"
                                    accept="image/*"
                                    style="border:1px solid #dfe2e8; border-radius:8px; padding:11px 14px; font-size:14px;">
                                <div class="invalid-feedback image-error"></div>
                            </div>

                            <!-- Authority Signature -->
                            <div class="col-md-6 mb-3">
                                <label for="authority_signature" class="form-label fw-bold"
                                    style="color:#1e1e2d; font-size:13px;">
                                    Authority Signature
                                </label>
                                <input type="file" class="form-control" id="authority_signature"
                                    name="authority_signature" accept="image/*"
                                    style="border:1px solid #dfe2e8; border-radius:8px; padding:11px 14px; font-size:14px;">
                                <div class="invalid-feedback authority_signature-error"></div>
                            </div>

                            <!-- Description -->
                            <div class="col-md-6 mb-3">
                                <label for="description" class="form-label fw-bold"
                                    style="color:#1e1e2d; font-size:13px;">
                                    Description
                                </label>
                                <textarea class="form-control" id="description" name="description" rows="4" placeholder="Enter description"
                                    style="border:1px solid #dfe2e8; border-radius:8px; padding:11px 14px; font-size:14px;"></textarea>
                                <div class="invalid-feedback description-error"></div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="modal-footer d-flex justify-content-between align-items-center"
                style="background-color: #ffffff; border-top: 1px solid #eef0f2; padding: 16px 24px;">
                <span style="color:#8a8a9a; font-size:13px;">
                    <i class="bi bi-check-circle-fill text-success me-1"></i> 8 Fields
                </span>
                <div>
                    <button type="button" class="btn me-2" data-bs-dismiss="modal"
                        style="border:1px solid #dfe2e8; color:#4a4a5a; border-radius:8px; padding:8px 18px; font-size:14px;">
                        <i class="bi bi-x-lg me-1"></i> Cancel
                    </button>
                    <button type="submit" form="wingCreateModalForm" class="btn submit-btn"
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
        $("#wingCreateModalForm").on("submit", function(e) {
            e.preventDefault();
            showLoading();

            let formData = new FormData(this);

            $.ajax({
                url: "{{ route('wing.store') }}",
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
                        $("#wingCreateModalForm")[0].reset();
                        $("#wingCreateModal").modal("hide");
                        window.location.href =
                            "{{ route('wing.index') }}?added-successfully=" +
                            encodeURIComponent(response.message);
                    }
                },
                error: function(xhr) {
                    hideLoading();
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;

                        if (errors.name) $(".name-error").text(errors.name[0]).show();
                        if (errors.imported_number) $(".imported_number-error").text(errors
                            .imported_number[0]).show();
                        if (errors.bin_number) $(".bin_number-error").text(errors
                            .bin_number[0]).show();
                        if (errors.mobile_number) $(".mobile_number-error").text(errors
                            .mobile_number[0]).show();
                        if (errors.email) $(".email-error").text(errors.email[0]).show();
                        if (errors.image) $(".image-error").text(errors.image[0]).show();
                        if (errors.authority_signature) $(".authority_signature-error")
                            .text(errors.authority_signature[0]).show();
                        if (errors.description) $(".description-error").text(errors
                            .description[0]).show();

                        setTimeout(function() {
                            $(".invalid-feedback").fadeOut();
                        }, 3000);
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
