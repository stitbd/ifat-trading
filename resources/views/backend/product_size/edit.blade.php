<!-- Modal Header -->
<div class="modal-header" style="background-color: #333333; border-bottom: 1px solid #ccc;">
    <h5 class="modal-title" id="productSizeEditModalLabel" style="color: #ffffff;">Edit Product Size</h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<!-- Modal Body -->
<div class="modal-body">
    <form id="productSizeEditForm" method="POST">
        @csrf
        @method('put')
        <div class="row">
            <!-- Name -->
            <div class="col-md-12 mb-3">
                <label for="name" class="form-label fw-bold text-dark">Name: <span
                        class="text-danger ml-1">*</span></label>
                <input type="text" class="form-control border-dark" id="name" name="name"
                    placeholder="Enter product size name" value="{{ $data->name }}">
                <div class="invalid-feedback name-error"></div>
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

        <input type="hidden" value="{{ $data->id }}" id="product_size_id">

        <!-- Submit Button -->
        <div class="text-end">
            <button type="submit" class="btn submit-btn"
                style="background-color: #FF4C29; color: #ffffff; border-radius: 5px;">
                Update Product Size
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
        $("#productSizeEditForm").on("submit", function(e) {

            showLoading();
            e.preventDefault();

            let formData = new FormData(this);
            let productSizeId = $("#product_size_id").val();

            $.ajax({
                url: "{{ route('product-size.update', ':id') }}".replace(':id', productSizeId),
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $(".invalid-feedback").text("").hide();
                },
                success: function(response) {
                    if (response.success) {
                        $("#productSizeEditForm")[0].reset();
                        $("#productSizeEditModal").modal("hide");

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

                        if (errors.name) {
                            $(".name-error").text(errors.name[0]).show();
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
                                'Product Size not found!',
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
