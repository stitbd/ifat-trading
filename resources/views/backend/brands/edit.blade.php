<div
    class="modal-header"
    style="
        background-color:#333333;
        border-bottom:1px solid #ccc;
    ">

    <h5
        class="modal-title"
        style="color:#ffffff;">

        Edit Brand

    </h5>

    <button
        type="button"
        class="btn-close btn-close-white"
        data-bs-dismiss="modal"
        aria-label="Close">
    </button>

</div>


<div class="modal-body">

    <form
        id="brandEditModalForm"
        method="POST"
        enctype="multipart/form-data">

        @csrf

        @method('PUT')


        <div class="row">

            <div class="col-md-6 mb-3">

                <label
                    for="edit_brand_name"
                    class="form-label fw-bold text-dark">

                    Brand Name:

                </label>

                <input
                    type="text"
                    class="form-control border-dark"
                    id="edit_brand_name"
                    name="name"
                    maxlength="100"
                    placeholder="Enter brand name"
                    value="{{ $data->name }}">

                <div
                    class="invalid-feedback name-error">
                </div>

            </div>


            <div class="col-md-6 mb-3">

                <label
                    for="edit_brand_image"
                    class="form-label fw-bold text-dark">

                    Brand Image:

                </label>

                <input
                    type="file"
                    class="form-control border-dark"
                    id="edit_brand_image"
                    name="image"
                    accept="image/*">

                <div
                    class="invalid-feedback image-error">
                </div>


                @if ($data->image)

                    <div class="mt-2">

                        <img
                            src="{{ asset('brands/image/' . $data->image) }}"
                            width="120"
                            height="120"
                            style="
                                object-fit:cover;
                                border-radius:5px;
                                border:1px solid #ddd;
                            "
                            alt="Brand Image">

                    </div>

                @endif

            </div>

        </div>


        <div class="row">

            <div class="col-md-12 mb-3">

                <label
                    for="edit_brand_description"
                    class="form-label fw-bold text-dark">

                    Description:

                </label>

                <textarea
                    class="form-control border-dark"
                    id="edit_brand_description"
                    name="description"
                    rows="5"
                    placeholder="Enter brand description">{{ $data->description }}</textarea>

                <div
                    class="invalid-feedback description-error">
                </div>

            </div>

        </div>


        <input
            type="hidden"
            value="{{ $data->id }}"
            id="brand_id">


        <div class="text-end">

            <button
                type="submit"
                class="btn submit-btn"
                style="
                    background-color:#FF4C29;
                    color:#ffffff;
                    border-radius:5px;
                ">

                Update Brand

            </button>

        </div>

    </form>

</div>


<div
    class="modal-footer"
    style="
        background-color:#f8f9fa;
        border-top:1px solid #ccc;
    ">

    <button
        type="button"
        class="btn"
        style="
            background-color:#FF4C29;
            color:#ffffff;
            border-radius:5px;
        "
        data-bs-dismiss="modal">

        Close

    </button>

</div>


<script>

$(document).ready(function() {

    $("#brandEditModalForm").on(
        "submit",
        function(e) {

            e.preventDefault();

            showLoading();

            let formData =
                new FormData(this);

            let brandId =
                $("#brand_id").val();

            $.ajax({

                url:
                    "{{ route('brand.update', ':id') }}"
                    .replace(
                        ':id',
                        brandId
                    ),

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

                        $("#brandEditModalForm")[0]
                            .reset();

                        $("#brandEditModal")
                            .modal("hide");

                        $('#brandTable')
                            .DataTable()
                            .ajax
                            .reload(null, false);

                        Swal.fire({

                            icon: "success",

                            title:
                                response.message,

                            showConfirmButton: false,

                            timer: 2000

                        });

                    }

                },

                error: function(xhr) {

                    hideLoading();

                    if (xhr.status === 422) {

                        let errors =
                            xhr.responseJSON.errors;

                        if (errors.name) {

                            $(".name-error")
                                .text(
                                    errors.name[0]
                                )
                                .show();

                        }

                        if (errors.image) {

                            $(".image-error")
                                .text(
                                    errors.image[0]
                                )
                                .show();

                        }

                        if (errors.description) {

                            $(".description-error")
                                .text(
                                    errors.description[0]
                                )
                                .show();

                        }

                        setTimeout(function() {

                            $(".invalid-feedback")
                                .fadeOut();

                        }, 3000);

                    }

                }

            });

        }
    );

});

</script>
