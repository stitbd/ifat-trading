<div class="modal fade" id="userCreateModal" tabindex="-1" aria-labelledby="userCreateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="background-color: #f8f9fa; border-radius: 8px; border: 1px solid #ddd;">
            <!-- Modal Header -->
            <div class="modal-header" style="background-color: #333333; border-bottom: 1px solid #ccc;">
                <h5 class="modal-title" id="userCreateModalLabel" style="color: #ffffff;">Create New User</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <form id="userCreateModalForm" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <!-- Name -->
                        <div class="col-md-12 mb-3">
                            <label for="name" class="form-label fw-bold text-dark">Full Name: <span
                                    class="text-danger ml-1">*</span></label>
                            <input type="text" class="form-control border-dark" id="name" name="name"
                                placeholder="Enter full name">
                            <div class="invalid-feedback name-error"></div>
                        </div>

                        <!-- Email -->
                        <div class="col-md-12 mb-3">
                            <label for="email" class="form-label fw-bold text-dark">Email: <span
                                    class="text-danger ml-1">*</span></label>
                            <input type="email" class="form-control border-dark" id="email" name="email"
                                placeholder="Enter email">
                            <div class="invalid-feedback email-error"></div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Password -->
                        <div class="col-md-12 mb-3">
                            <label for="password" class="form-label fw-bold text-dark">Password: <span
                                    class="text-danger ml-1">*</span></label>
                            <input type="password" class="form-control border-dark" id="password" name="password"
                                placeholder="Enter password">
                            <div class="invalid-feedback password-error"></div>
                        </div>

                        <!-- Image Upload -->
                        <div class="col-md-12 mb-3">
                            <label for="image" class="form-label fw-bold text-dark">Profile Picture: </label>
                            <input type="file" class="form-control border-dark" id="image" name="image"
                                accept="image/*">
                            <div class="invalid-feedback image-error"></div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-end">
                        <button type="submit" class="btn submit-btn"
                            style="background-color: #FF4C29; color: #ffffff; border-radius: 5px;">
                            Create User
                        </button>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer" style="background-color: #f8f9fa; border-top: 1px solid #ccc;">
                <button type="button" class="btn"
                    style="background-color: #FF4C29; color: #ffffff; border-radius: 5px;"
                    data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $("#userCreateModalForm").on("submit", function(e) {
            showLoading();
            e.preventDefault(); // Prevent default form submission

            let formData = new FormData(this);

            $.ajax({
                url: "{{ route('user.store') }}", // Laravel route for storing user
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $(".invalid-feedback").text("").hide(); // Clear previous errors
                },
                success: function(response) {
                    if (response.success) {
                        // Success message


                        // Reset form and close modal
                        $("#userCreateModalForm")[0].reset();
                        $("#userCreateModal").modal("hide");

                        hideLoading();
                        window.location.href =
                            "{{ route('user.index') }}?added-successfully=" +
                            encodeURIComponent(response.message);

                    }
                },
                error: function(xhr) {
                    hideLoading();
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;

                        // Display validation errors
                        if (errors.name) {
                            $(".name-error").text(errors.name[0]).show();
                        }
                        if (errors.email) {
                            $(".email-error").text(errors.email[0]).show();
                        }
                        if (errors.password) {
                            $(".password-error").text(errors.password[0]).show();
                        }
                        if (errors.image) {
                            $(".image-error").text(errors.image[0]).show();
                        }

                        // Auto-hide error messages after 3 seconds
                        setTimeout(function() {
                            $(".invalid-feedback").fadeOut();
                        }, 3000);
                    }
                }
            });
        });
    });
</script>
