<!-- Modal Content Wrapper (matches create modal style) -->
<div class="modal-content" style="border-radius: 12px; border: none; overflow: hidden;">

    <!-- Header -->
    <div class="modal-header align-items-start"
        style="background-color: #ffffff; border-bottom: 1px solid #eef0f2; padding: 20px 24px;">
        <div>
            <h5 class="modal-title mb-1" style="color:#1e1e2d; font-weight:700; font-size:18px;">
                <i class="bi bi-pencil-square me-2" style="color:#4361ee;"></i>
                Edit Subcategory
            </h5>
            <p class="mb-0" style="color:#8a8a9a; font-size:13px;">
                Update the details for this subcategory
            </p>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    <!-- Body -->
    <div class="modal-body" style="padding: 24px; background-color: #fbfbfd;">
        <form id="subcategoryEditModalForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="p-4 mb-3" style="background:#fff; border:1px solid #eef0f2; border-radius:10px;">

                <div class="row">
                    <!-- Category -->
                    <div class="col-md-12 mb-3">
                        <label for="edit_category_id" class="form-label fw-bold" style="color:#1e1e2d; font-size:13px;">
                            Category <span class="text-danger">*</span>
                        </label>
                        <select class="form-select" id="edit_category_id" name="category_id"
                            style="border:1px solid #dfe2e8; border-radius:8px; padding:11px 14px; font-size:14px;">
                            <option value="">Select Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ $data->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback category_id-error"></div>
                    </div>

                    <!-- Name -->
                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-bold" style="color:#1e1e2d; font-size:13px;">
                            Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" name="name" maxlength="100"
                            value="{{ $data->name }}" placeholder="Enter subcategory name"
                            style="border:1px solid #dfe2e8; border-radius:8px; padding:11px 14px; font-size:14px;">
                        <div class="invalid-feedback name-error"></div>
                    </div>

                    <!-- Image -->
                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-bold" style="color:#1e1e2d; font-size:13px;">
                            Image
                        </label>
                        <input type="file" class="form-control" name="image" accept="image/*"
                            style="border:1px solid #dfe2e8; border-radius:8px; padding:11px 14px; font-size:14px;">
                        <div class="invalid-feedback image-error"></div>

                        @if ($data->image)
                            <div class="mt-2">
                                <img src="{{ asset('subcategories/image/' . $data->image) }}" width="90"
                                    height="90"
                                    style="object-fit:cover; border-radius:8px; border:1px solid #eef0f2;"
                                    alt="Subcategory Image">
                            </div>
                        @endif
                    </div>

                    <!-- Description -->
                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-bold" style="color:#1e1e2d; font-size:13px;">
                            Description
                        </label>
                        <textarea class="form-control" name="description" rows="4" placeholder="Enter subcategory description"
                            style="border:1px solid #dfe2e8; border-radius:8px; padding:11px 14px; font-size:14px;">{{ $data->description }}</textarea>
                        <div class="invalid-feedback description-error"></div>
                    </div>

                    <!-- Status -->
                    <div class="col-md-12">
                        <label class="form-label fw-bold" style="color:#1e1e2d; font-size:13px;">
                            Status
                        </label>
                        <select class="form-select" name="status"
                            style="border:1px solid #dfe2e8; border-radius:8px; padding:11px 14px; font-size:14px;">
                            <option value="1" {{ $data->status == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ $data->status == 0 ? 'selected' : '' }}>Inactive</option>
                        </select>
                        <div class="invalid-feedback status-error"></div>
                    </div>
                </div>
            </div>

            <input type="hidden" id="subcategory_id" value="{{ $data->id }}">
        </form>
    </div>

    <!-- Footer -->
    <div class="modal-footer d-flex justify-content-between align-items-center"
        style="background-color: #ffffff; border-top: 1px solid #eef0f2; padding: 16px 24px;">
        <span style="color:#8a8a9a; font-size:13px;">
            <i class="bi bi-check-circle-fill text-success me-1"></i> 5 Fields
        </span>
        <div>
            <button type="button" class="btn me-2" data-bs-dismiss="modal"
                style="border:1px solid #dfe2e8; color:#4a4a5a; border-radius:8px; padding:8px 18px; font-size:14px;">
                <i class="bi bi-x-lg me-1"></i> Cancel
            </button>
            <button type="submit" form="subcategoryEditModalForm" class="btn submit-btn"
                style="background-color:#4361ee; color:#fff; border-radius:8px; padding:8px 20px; font-size:14px; font-weight:600;">
                <i class="bi bi-check-lg me-1"></i> Update
            </button>
        </div>
    </div>

</div>

<script>
    $(document).ready(function() {
        $("#subcategoryEditModalForm").on("submit", function(e) {
            e.preventDefault();
            showLoading();

            let formData = new FormData(this);
            let subcategoryId = $("#subcategory_id").val();

            $.ajax({
                url: "{{ route('subcategory.update', ':id') }}".replace(':id', subcategoryId),
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
                        $("#subcategoryEditModalForm")[0].reset();
                        $("#subcategoryEditModal").modal("hide");
                        window.location.href =
                            "{{ route('subcategory.index') }}?added-successfully=" +
                            encodeURIComponent(response.message);
                    }
                },
                error: function(xhr) {
                    hideLoading();
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function(field, messages) {
                            $("." + field + "-error").text(messages[0]).show();
                        });
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
                                'Subcategory not found!',
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
