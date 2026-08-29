<div class="modal fade" id="vatPercentageCreateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="background-color: #f8f9fa; border-radius: 8px; border: 1px solid #ddd;">
            <div class="modal-header" style="background-color: #333333;">
                <h5 class="modal-title" style="color:#fff;">Create VAT Percentage</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="vatPercentageCreateForm" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Title: <span class="text-danger">*</span></label>
                        <input type="text" class="form-control border-dark" name="title"
                            placeholder="Enter title (e.g. Standard VAT)">
                        <div class="invalid-feedback title-error"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Value (%): <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0" max="100"
                            class="form-control border-dark" name="value" placeholder="Enter VAT value e.g. 15">
                        <div class="invalid-feedback value-error"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Status:</label>
                        <select class="form-control border-dark" name="status">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn submit-btn"
                            style="background-color:#FF4C29;color:#fff;">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $("#vatPercentageCreateForm").on("submit", function(e) {
            showLoading();
            e.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                url: "{{ route('vat-percentage.store') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $(".invalid-feedback").text("").hide();
                },
                success: function(response) {
                    if (response.success) {
                        $("#vatPercentageCreateForm")[0].reset();
                        $("#vatPercentageCreateModal").modal("hide");
                        hideLoading();
                        window.location.href =
                            "{{ route('vat-percentage.index') }}?added-successfully=" +
                            encodeURIComponent(response.message);
                    }
                },
                error: function(xhr) {
                    hideLoading();
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        if (errors.title) $(".title-error").text(errors.title[0]).show();
                        if (errors.value) $(".value-error").text(errors.value[0]).show();
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
