<!-- Modal Header -->
<div class="modal-header"
    style="background-color:#333333; border-bottom:1px solid #ccc;">

    <h5 class="modal-title"
        id="wingEditModalLabel"
        style="color:#ffffff;">
        Edit Wing
    </h5>

    <button type="button"
        class="btn-close btn-close-white"
        data-bs-dismiss="modal"
        aria-label="Close">
    </button>

</div>


<!-- Modal Body -->
<div class="modal-body">

    <form id="wingEditModalForm"
        method="POST"
        enctype="multipart/form-data">

        @csrf
        @method('PUT')

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
                    placeholder="Enter wing name"
                    value="{{ $data->name }}">

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
                    placeholder="Enter imported number"
                    value="{{ $data->imported_number }}">

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
                    placeholder="Enter BIN number"
                    value="{{ $data->bin_number }}">

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
                    placeholder="Enter mobile number"
                    value="{{ $data->mobile_number }}">

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
                    placeholder="Enter email"
                    value="{{ $data->email }}">

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


                @if ($data->image)
                    <div class="mt-2">

                        <img src="{{ asset('image/' . $data->image) }}"
                            width="120"
                            height="120"
                            style="object-fit:cover;border-radius:5px;border:1px solid #ddd;"
                            alt="Wing Image">

                    </div>
                @endif

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


                @if ($data->authority_signature)
                    <div class="mt-2">

                        <img src="{{ asset('signature/' . $data->authority_signature) }}"
                            width="150"
                            height="70"
                            style="object-fit:contain;border:1px solid #ddd;border-radius:5px;"
                            alt="Authority Signature">

                    </div>
                @endif

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
                    placeholder="Enter description">{{ $data->description }}</textarea>

                <div class="invalid-feedback description-error"></div>

            </div>

        </div>


        <input type="hidden"
            value="{{ $data->id }}"
            id="wing_id">


        <!-- Submit Button -->
        <div class="text-end">

            <button type="submit"
                class="btn submit-btn"
                style="background-color:#FF4C29;
                       color:#ffffff;
                       border-radius:5px;">

                Update Wing

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


<script>

    $(document).ready(function() {

        $("#wingEditModalForm").on("submit", function(e) {

            e.preventDefault();

            showLoading();

            let formData = new FormData(this);

            let wingId = $("#wing_id").val();


            $.ajax({

                url: "{{ route('wing.update', ':id') }}"
                    .replace(':id', wingId),

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

                        $("#wingEditModalForm")[0].reset();

                        $("#wingEditModal").modal("hide");

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

                            $(".invalid-feedback").fadeOut();

                        }, 3000);

                    }

                }

            });

        });

    });

</script>
