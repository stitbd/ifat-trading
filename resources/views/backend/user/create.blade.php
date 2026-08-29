<div class="modal fade" id="userCreateModal" tabindex="-1" aria-labelledby="userCreateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 12px; border: none; overflow: hidden;">

            <!-- Header -->
            <div class="modal-header align-items-start"
                style="background-color: #ffffff; border-bottom: 1px solid #eef0f2; padding: 20px 24px;">
                <div>
                    <h5 class="modal-title mb-1" id="userCreateModalLabel"
                        style="color:#1e1e2d; font-weight:700; font-size:18px;">
                        <i class="bi bi-person-plus-fill me-2" style="color:#4361ee;"></i>
                        Create New User
                    </h5>
                    <p class="mb-0" style="color:#8a8a9a; font-size:13px;">
                        Add a new user account
                    </p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Body -->
            <div class="modal-body" style="padding: 24px; background-color: #fbfbfd;">
                <form id="userCreateModalForm" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="p-4 mb-3" style="background:#fff; border:1px solid #eef0f2; border-radius:10px;">

                        <div class="row">
                            <!-- Name -->
                            <div class="col-md-12 mb-3">
                                <label for="name" class="form-label fw-bold" style="color:#1e1e2d; font-size:13px;">
                                    Full Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Enter full name"
                                    style="border:1px solid #dfe2e8; border-radius:8px; padding:11px 14px; font-size:14px;">
                                <div class="invalid-feedback name-error"></div>
                            </div>

                            <!-- Email -->
                            <div class="col-md-12 mb-3">
                                <label for="email" class="form-label fw-bold" style="color:#1e1e2d; font-size:13px;">
                                    Email <span class="text-danger">*</span>
                                </label>
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="Enter email"
                                    style="border:1px solid #dfe2e8; border-radius:8px; padding:11px 14px; font-size:14px;">
                                <div class="invalid-feedback email-error"></div>
                            </div>

                            <!-- Password -->
                            <div class="col-md-12 mb-3">
                                <label for="password" class="form-label fw-bold" style="color:#1e1e2d; font-size:13px;">
                                    Password <span class="text-danger">*</span>
                                </label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Enter password"
                                    style="border:1px solid #dfe2e8; border-radius:8px; padding:11px 14px; font-size:14px;">
                                <div class="invalid-feedback password-error"></div>
                            </div>

                            <!-- Image -->
                            <div class="col-md-12 mb-3">
                                <label for="image" class="form-label fw-bold" style="color:#1e1e2d; font-size:13px;">
                                    Profile Picture
                                </label>
                                <input type="file" class="form-control" id="image" name="image"
                                    accept="image/*"
                                    style="border:1px solid #dfe2e8; border-radius:8px; padding:11px 14px; font-size:14px;">
                                <div class="invalid-feedback image-error"></div>
                            </div>

                            <!-- Role -->
                            <div class="col-md-12">
                                <label for="role" class="form-label fw-bold" style="color:#1e1e2d; font-size:13px;">
                                    Role <span class="text-danger">*</span>
                                </label>
                                <select class="form-control" id="role" name="role[]" multiple>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Ctrl/Cmd chepe multiple select korun</small>
                                <div class="invalid-feedback role-error"></div>
                            </div>
                        </div>
                    </div>
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
                    <button type="submit" form="userCreateModalForm" class="btn submit-btn"
                        style="background-color:#4361ee; color:#fff; border-radius:8px; padding:8px 20px; font-size:14px; font-weight:600;">
                        <i class="bi bi-check-lg me-1"></i> Create
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#role').select2({
            placeholder: "Select role(s)",
            dropdownParent: $('#userCreateModal')
        });
    });
</script>
<script>
    $(document).ready(function() {
        $("#userCreateModalForm").on("submit", function(e) {
            showLoading();
            e.preventDefault();

            let formData = new FormData(this);

            $.ajax({
                url: "{{ route('user.store') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $(".invalid-feedback").text("").hide();
                },
                success: function(response) {
                    if (response.success) {
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

                        if (errors.name) $(".name-error").text(errors.name[0]).show();
                        if (errors.email) $(".email-error").text(errors.email[0]).show();
                        if (errors.password) $(".password-error").text(errors.password[0])
                            .show();
                        if (errors.image) $(".image-error").text(errors.image[0]).show();
                        if (errors.role) $(".role-error").text(errors.role[0]).show();

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
