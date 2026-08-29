<div class="modal fade" id="productSizeCreateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="background-color: #f8f9fa; border-radius: 8px; border: 1px solid #ddd;">
            <div class="modal-header" style="background-color: #333333;">
                <h5 class="modal-title" style="color:#fff;">Create Product Size</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="productSizeCreateForm" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Name: <span class="text-danger">*</span></label>
                        <input type="text" class="form-control border-dark" name="name"
                            placeholder="Enter product size name">
                        <div class="invalid-feedback name-error"></div>
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
        $("#productSizeCreateForm").on("submit", function(e) {
            showLoading();
            e.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                url: "{{ route('product-size.store') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $(".invalid-feedback").text("").hide();
                },
                success: function(response) {
                    if (response.success) {
                        $("#productSizeCreateForm")[0].reset();
                        $("#productSizeCreateModal").modal("hide");
                        hideLoading();
                        window.location.href =
                            "{{ route('product-size.index') }}?added-successfully=" +
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
