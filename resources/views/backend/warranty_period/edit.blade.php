<!-- Modal Header -->
<div class="modal-header" style="background-color: #333333; border-bottom: 1px solid #ccc;">
    <h5 class="modal-title" id="warrantyPeriodEditModalLabel" style="color: #ffffff;">Edit Warranty Period</h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<!-- Modal Body -->
<div class="modal-body">
    <form id="warrantyPeriodEditForm" method="POST">
        @csrf
        @method('put')
        <div class="row">
            <!-- Title -->
            <div class="col-md-12 mb-3">
                <label for="title" class="form-label fw-bold text-dark">Title: <span
                        class="text-danger ml-1">*</span></label>
                <input type="text" class="form-control border-dark" id="title" name="title"
                    placeholder="Enter title" value="{{ $data->title }}">
                <div class="invalid-feedback title-error"></div>
            </div>

            <!-- Value -->
            <div class="col-md-12 mb-3">
                <label for="value" class="form-label fw-bold text-dark">Value: <span
                        class="text-danger ml-1">*</span></label>
                <input type="number" min="0" class="form-control border-dark" id="value" name="value"
                    placeholder="Enter value" value="{{ $data->value }}">
                <div class="invalid-feedback value-error"></div>
            </div>

            <!-- Status -->
            <div class="col-md-12 mb-3">
                <label for="status" class="form-label fw-bold text-dark">Status:</label>
                <select class="form-control border-dark" id="status" name="status">
                    <option value="1" {{ $data->status == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ $data->status == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
                <div class="invalid-feedback status-error"></div>
            </div>
        </div>

        <input type="hidden" value="{{ $data->id }}" id="warranty_period_id">

        <!-- Submit Button -->
        <div class="text-end">
            <button type="submit" class="btn submit-btn"
                style="background-color: #FF4C29; color: #ffffff; border-radius: 5px;">
                Update Warranty Period
            </button>
        </div>
    </form>
</div>

<!-- Modal Footer -->
<div class="modal-footer" style="background-color: #f8f9fa; border-top: 1px solid #ccc;">
    <button type="button" class="btn" style="background-color: #FF4C29; color: #ffffff; border-radius: 5px;"
        data-bs-dismiss="modal">Close</button>
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

                        if (errors.title) {
                            $(".title-error").text(errors.title[0]).show();
                        }
                        if (errors.value) {
                            $(".value-error").text(errors.value[0]).show();
                        }
                        if (errors.status) {
                            $(".status-error").text(errors.status[0]).show();
                        }

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
