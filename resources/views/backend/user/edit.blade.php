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
            <div class="col-md-12 mb-3">
                <label for="role" class="form-label fw-bold text-dark">Role: <span
                        class="text-danger ml-1">*</span></label>
                <select class="form-control border-dark" id="role" name="role[]" multiple>
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}"
                            {{ $data->roles->pluck('name')->contains($role->name) ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
                <small class="text-muted">Ctrl/Cmd chepe multiple select korun</small>
                <div class="invalid-feedback role-error"></div>
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
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#role').select2({
            placeholder: "Select role(s)",
            dropdownParent: $(
                '#userEditModal') // modal er vitore thakle এটা must, নাহলে dropdown hide hoye jay
        });
    });
</script>
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
                        if (errors.role) {
                            $(".role-error").text(errors.role[0]).show();
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
                            text: xhr.responseJSON?.message || 'User not found!',
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
