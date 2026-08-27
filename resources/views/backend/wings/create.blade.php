<div class="modal fade" id="wingCreateModal" tabindex="-1"
    aria-labelledby="wingCreateModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-xl">

        <div class="modal-content"
            style="background-color:#f8f9fa; border-radius:8px; border:1px solid #ddd;">

            <!-- Modal Header -->
            <div class="modal-header"
                style="background-color:#333333; border-bottom:1px solid #ccc;">

                <h5 class="modal-title"
                    id="wingCreateModalLabel"
                    style="color:#ffffff;">
                    Create New Wing
                </h5>

                <button type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal"
                    aria-label="Close">
                </button>

            </div>


            <!-- Modal Body -->
            <div class="modal-body">

                <form id="wingCreateModalForm"
                    method="POST"
                    enctype="multipart/form-data">

                    @csrf

                    <!-- Row 1 -->
                    <div class="row">

                        <!-- Name -->
                        <div class="col-md-6 mb-3">

                            <label for="name"
                                class="form-label fw-bold text-dark">
                                Wing Name:
                            </label>

                            <input type="text"
                                class="form-control border-dark"
                                id="name"
                                name="name"
                                maxlength="100"
                                placeholder="Enter wing name">

                            <div class="invalid-feedback name-error"></div>

                        </div>


                        <!-- Imported Number -->
                        <div class="col-md-6 mb-3">

                            <label for="imported_number"
                                class="form-label fw-bold text-dark">
                                Imported Number:
                            </label>

                            <input type="text"
                                class="form-control border-dark"
                                id="imported_number"
                                name="imported_number"
                                maxlength="100"
                                placeholder="Enter imported number">

                            <div class="invalid-feedback imported_number-error"></div>

                        </div>

                    </div>


                    <!-- Row 2 -->
                    <div class="row">

                        <!-- BIN Number -->
                        <div class="col-md-6 mb-3">

                            <label for="bin_number"
                                class="form-label fw-bold text-dark">
                                BIN Number:
                            </label>

                            <input type="text"
                                class="form-control border-dark"
                                id="bin_number"
                                name="bin_number"
                                maxlength="50"
                                placeholder="Enter BIN number">

                            <div class="invalid-feedback bin_number-error"></div>

                        </div>


                        <!-- Mobile Number -->
                        <div class="col-md-6 mb-3">

                            <label for="mobile_number"
                                class="form-label fw-bold text-dark">
                                Mobile Number:
                            </label>

                            <input type="text"
                                class="form-control border-dark"
                                id="mobile_number"
                                name="mobile_number"
                                maxlength="50"
                                placeholder="Enter mobile number">

                            <div class="invalid-feedback mobile_number-error"></div>

                        </div>

                    </div>


                    <!-- Row 3 -->
                    <div class="row">

                        <!-- Email -->
                        <div class="col-md-6 mb-3">

                            <label for="email"
                                class="form-label fw-bold text-dark">
                                Email:
                            </label>

                            <input type="email"
                                class="form-control border-dark"
                                id="email"
                                name="email"
                                maxlength="50"
                                placeholder="Enter email">

                            <div class="invalid-feedback email-error"></div>

                        </div>


                        <!-- Image -->
                        <div class="col-md-6 mb-3">

                            <label for="image"
                                class="form-label fw-bold text-dark">
                                Wing Image:
                            </label>

                            <input type="file"
                                class="form-control border-dark"
                                id="image"
                                name="image"
                                accept="image/*">

                            <div class="invalid-feedback image-error"></div>

                        </div>

                    </div>


                    <!-- Row 4 -->
                    <div class="row">

                        <!-- Authority Signature -->
                        <div class="col-md-6 mb-3">

                            <label for="authority_signature"
                                class="form-label fw-bold text-dark">
                                Authority Signature:
                            </label>

                            <input type="file"
                                class="form-control border-dark"
                                id="authority_signature"
                                name="authority_signature"
                                accept="image/*">

                            <div class="invalid-feedback authority_signature-error"></div>

                        </div>


                        <!-- Description -->
                        <div class="col-md-6 mb-3">

                            <label for="description"
                                class="form-label fw-bold text-dark">
                                Description:
                            </label>

                            <textarea class="form-control border-dark"
                                id="description"
                                name="description"
                                rows="4"
                                placeholder="Enter description"></textarea>

                            <div class="invalid-feedback description-error"></div>

                        </div>

                    </div>


                    <!-- Submit -->
                    <div class="text-end">

                        <button type="submit"
                            class="btn submit-btn"
                            style="background-color:#FF4C29;
                                   color:#ffffff;
                                   border-radius:5px;">

                            Create Wing

                        </button>

                    </div>

                </form>

            </div>


            <!-- Modal Footer -->
            <div class="modal-footer"
                style="background-color:#f8f9fa;
                       border-top:1px solid #ccc;">

                <button type="button"
                    class="btn"
                    style="background-color:#FF4C29;
                           color:#ffffff;
                           border-radius:5px;"
                    data-bs-dismiss="modal">

                    Close

                </button>

            </div>

        </div>

    </div>

</div>


<script>

    $(document).ready(function() {

        $("#wingCreateModalForm").on("submit", function(e) {

            e.preventDefault();

            showLoading();

            let formData = new FormData(this);

            $.ajax({

                url: "{{ route('wing.store') }}",

                type: "POST",

                data: formData,

                processData: false,

                contentType: false,

                beforeSend: function() {

                    $(".invalid-feedback")
                        .text("")
                        .hide();

                },

                success: function(response) {

                    hideLoading();

                    if (response.success) {

                        $("#wingCreateModalForm")[0].reset();

                        $("#wingCreateModal").modal("hide");

                        window.location.href =
                            "{{ route('wing.index') }}?added-successfully=" +
                            encodeURIComponent(response.message);

                    }

                },

                error: function(xhr) {

                    hideLoading();

                    if (xhr.status === 422) {

                        let errors = xhr.responseJSON.errors;

                        if (errors.name) {
                            $(".name-error")
                                .text(errors.name[0])
                                .show();
                        }

                        if (errors.imported_number) {
                            $(".imported_number-error")
                                .text(errors.imported_number[0])
                                .show();
                        }

                        if (errors.bin_number) {
                            $(".bin_number-error")
                                .text(errors.bin_number[0])
                                .show();
                        }

                        if (errors.mobile_number) {
                            $(".mobile_number-error")
                                .text(errors.mobile_number[0])
                                .show();
                        }

                        if (errors.email) {
                            $(".email-error")
                                .text(errors.email[0])
                                .show();
                        }

                        if (errors.image) {
                            $(".image-error")
                                .text(errors.image[0])
                                .show();
                        }

                        if (errors.authority_signature) {
                            $(".authority_signature-error")
                                .text(errors.authority_signature[0])
                                .show();
                        }

                        if (errors.description) {
                            $(".description-error")
                                .text(errors.description[0])
                                .show();
                        }

                        setTimeout(function() {

                            $(".invalid-feedback")
                                .fadeOut();

                        }, 3000);

                    }

                }

            });

        });

    });

</script>
