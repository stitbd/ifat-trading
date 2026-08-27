<!-- Modal Header -->
<div class="modal-header" style="background-color: #333333; border-bottom: 1px solid #ccc;">
    <h5 class="modal-title" id="userEditModalLabel" style="color: #ffffff;">Edit New User</h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<!-- Modal Body -->
<div class="modal-body">
    <form id="userEditModalForm" method="POST" enctype="multipart/form-data">
        @csrf
        @method('put')
        <div class="row">
            <!-- Name -->
            <div class="col-md-12 mb-3">
                <label for="name" class="form-label fw-bold text-dark">Full Name:</label>
                <input type="text" class="form-control border-dark" id="name" name="name"
                    placeholder="Enter full name" value="{{ $data->name }}">
                <div class="invalid-feedback name-error"></div>
            </div>

            <!-- Email -->
            <div class="col-md-12 mb-3">
                <label for="email" class="form-label fw-bold text-dark">Email:</label>
                <input type="email" class="form-control border-dark" id="email" name="email"
                    placeholder="Enter email" value="{{ $data->email }}">
                <div class="invalid-feedback email-error"></div>
            </div>
        </div>

        <div class="row">
            <!-- Password -->
            <div class="col-md-12 mb-3">
                <label for="password" class="form-label fw-bold text-dark">Password:</label>
                <input type="password" class="form-control border-dark" id="password" name="password"
                    placeholder="Enter password">
                <div class="invalid-feedback password-error"></div>
            </div>

            <!-- Image Upload -->
            <div class="col-md-12 mb-3">
                <label for="image" class="form-label fw-bold text-dark">Profile Picture:</label>
                <input type="file" class="form-control border-dark" id="image" name="image" accept="image/*">
                <div class="invalid-feedback image-error"></div>
                <img src="{{ asset('image/' . $data->image) }}" width="150" alt="">
            </div>
        </div>
        <input type="hidden" value="{{ $data->id }}" id="user_id">
        <!-- Submit Button -->
        <div class="text-end">
            <button type="submit" class="btn submit-btn"
                style="background-color: #FF4C29; color: #ffffff; border-radius: 5px;">
                Update User
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
        $("#userEditModalForm").on("submit", function(e) {

            showLoading();
            e.preventDefault(); // Prevent default form submission

            let formData = new FormData(this);
            let userId = $("#user_id").val();

            $.ajax({
                url: "{{ route('user.update', ':id') }}".replace(':id', userId),
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
                        $("#userEditModalForm")[0].reset();
                        $("#userEditModal").modal("hide");

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
