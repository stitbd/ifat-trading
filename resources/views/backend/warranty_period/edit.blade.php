<!-- Modal Content Wrapper (matches create modal style) -->
<div class="modal-content" style="border-radius: 12px; border: none; overflow: hidden;">

    <!-- Header -->
    <div class="modal-header align-items-start"
        style="background-color: #ffffff; border-bottom: 1px solid #eef0f2; padding: 20px 24px;">
        <div>
            <h5 class="modal-title mb-1" id="warrantyPeriodEditModalLabel"
                style="color:#1e1e2d; font-weight:700; font-size:18px;">
                <i class="bi bi-pencil-square me-2" style="color:#4361ee;"></i>
                Edit Warranty Period
            </h5>
            <p class="mb-0" style="color:#8a8a9a; font-size:13px;">
                Update the details for this warranty period
            </p>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    <!-- Body -->
    <div class="modal-body" style="padding: 24px; background-color: #fbfbfd;">
        <form id="warrantyPeriodEditForm" method="POST">
            @csrf
            @method('put')

            <div class="p-4 mb-3" style="background:#fff; border:1px solid #eef0f2; border-radius:10px;">
                <div class="row">
                    <!-- Title -->
                    <div class="col-md-12 mb-3">
                        <label for="title" class="form-label fw-bold" style="color:#1e1e2d; font-size:13px;">
                            Title <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="title" name="title"
                            placeholder="Enter title" value="{{ $data->title }}"
                            style="border:1px solid #dfe2e8; border-radius:8px; padding:11px 14px; font-size:14px;">
                        <div class="invalid-feedback title-error"></div>
                    </div>

                    <!-- Value -->
                    <div class="col-md-12 mb-3">
                        <label for="value" class="form-label fw-bold" style="color:#1e1e2d; font-size:13px;">
                            Value <span class="text-danger">*</span>
                        </label>
                        <input type="number" min="0" class="form-control" id="value" name="value"
                            placeholder="Enter value" value="{{ $data->value }}"
                            style="border:1px solid #dfe2e8; border-radius:8px; padding:11px 14px; font-size:14px;">
                        <div class="invalid-feedback value-error"></div>
                    </div>

                    <!-- Status -->
                    <div class="col-md-12">
                        <label for="status" class="form-label fw-bold" style="color:#1e1e2d; font-size:13px;">
                            Status
                        </label>
                        <select class="form-select" id="status" name="status"
                            style="border:1px solid #dfe2e8; border-radius:8px; padding:11px 14px; font-size:14px;">
                            <option value="1" {{ $data->status == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ $data->status == 0 ? 'selected' : '' }}>Inactive</option>
                        </select>
                        <div class="invalid-feedback status-error"></div>
                    </div>
                </div>
            </div>

            <input type="hidden" value="{{ $data->id }}" id="warranty_period_id">
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
            <button type="submit" form="warrantyPeriodEditForm" class="btn submit-btn"
                style="background-color:#4361ee; color:#fff; border-radius:8px; padding:8px 20px; font-size:14px; font-weight:600;">
                <i class="bi bi-check-lg me-1"></i> Update
            </button>
        </div>
    </div>

</div>

<script>
    $(document).ready(function() {
        $("#warrantyPeriodEditForm").on("submit", function(e) {

            showLoading();
            e.preventDefault();

            let formData = new FormData(this);
            let warrantyPeriodId = $("#warranty_period_id").val();

            $.ajax({
                url: "{{ route('warranty-period.update', ':id') }}".replace(':id',
                    warrantyPeriodId),
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $(".invalid-feedback").text("").hide();
                },
                success: function(response) {
                    if (response.success) {
                        $("#warrantyPeriodEditForm")[0].reset();
                        $("#warrantyPeriodEditModal").modal("hide");

                        hideLoading();
                        window.location.href =
                            "{{ route('warranty-period.index') }}?added-successfully=" +
                            encodeURIComponent(response.message);
                    }
                },
                error: function(xhr) {
                    hideLoading();
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;

                        if (errors.title) $(".title-error").text(errors.title[0]).show();
                        if (errors.value) $(".value-error").text(errors.value[0]).show();
                        if (errors.status) $(".status-error").text(errors.status[0]).show();

                        setTimeout(function() {
                            $(".invalid-feedback").fadeOut();
                        }, 3000);

                    } else if (xhr.status === 403) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Access Denied',
                            text: xhr.responseJSON?.message ||
                                'You do not have permission to perform this action!',
                            confirmButtonColor: '#FF4C29'
                        });

                    } else if (xhr.status === 404) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Not Found',
                            text: xhr.responseJSON?.message ||
                                'Warranty Period not found!',
                            confirmButtonColor: '#FF4C29'
                        });

                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.message ||
                                'Something went wrong! Please try again.',
                            confirmButtonColor: '#FF4C29'
                        });
                    }
                }
            });
        });
    });
</script>
