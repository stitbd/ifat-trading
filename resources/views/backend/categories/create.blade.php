<div
    class="modal fade"
    id="categoryCreateModal"
    tabindex="-1"
    aria-labelledby="categoryCreateModalLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-xl">

        <div
            class="modal-content"
            style="
                background-color:#f8f9fa;
                border-radius:8px;
                border:1px solid #ddd;
            ">


            <!-- Modal Header -->
            <div
                class="modal-header"
                style="
                    background-color:#333333;
                    border-bottom:1px solid #ccc;
                ">

                <h5
                    class="modal-title"
                    id="categoryCreateModalLabel"
                    style="color:#ffffff;">

                    Create New Category

                </h5>


                <button
                    type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal"
                    aria-label="Close">
                </button>

            </div>


            <!-- Modal Body -->
            <div class="modal-body">

                <form
                    id="categoryCreateModalForm"
                    method="POST"
                    enctype="multipart/form-data">

                    @csrf


                    <!-- Row 1 -->
                    <div class="row">

                        <!-- Name -->
                        <div class="col-md-6 mb-3">

                            <label
                                for="category_name"
                                class="form-label fw-bold text-dark">

                                Category Name:

                            </label>

                            <input
                                type="text"
                                class="form-control border-dark"
                                id="category_name"
                                name="name"
                                maxlength="100"
                                placeholder="Enter category name">

                            <div
                                class="invalid-feedback name-error">
                            </div>

                        </div>


                        <!-- Image -->
                        <div class="col-md-6 mb-3">

                            <label
                                for="category_image"
                                class="form-label fw-bold text-dark">

                                Category Image:

                            </label>

                            <input
                                type="file"
                                class="form-control border-dark"
                                id="category_image"
                                name="image"
                                accept="image/*">

                            <div
                                class="invalid-feedback image-error">
                            </div>

                        </div>

                    </div>


                    <!-- Row 2 -->
                    <div class="row">

                        <!-- Description -->
                        <div class="col-md-12 mb-3">

                            <label
                                for="category_description"
                                class="form-label fw-bold text-dark">

                                Description:

                            </label>

                            <textarea
                                class="form-control border-dark"
                                id="category_description"
                                name="description"
                                rows="5"
                                placeholder="Enter category description"></textarea>

                            <div
                                class="invalid-feedback description-error">
                            </div>

                        </div>

                    </div>


                    <!-- Submit -->
                    <div class="text-end">

                        <button
                            type="submit"
                            class="btn submit-btn"
                            style="
                                background-color:#FF4C29;
                                color:#ffffff;
                                border-radius:5px;
                            ">

                            Create Category

                        </button>

                    </div>

                </form>

            </div>


            <!-- Footer -->
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

        </div>

    </div>

</div>


<script>

    $(document).ready(function() {

        /*
        |--------------------------------------------------------------------------
        | Create Category
        |--------------------------------------------------------------------------
        */

        $("#categoryCreateModalForm").on(
            "submit",
            function(e) {

                e.preventDefault();

                showLoading();

                let formData =
                    new FormData(this);


                $.ajax({

                    url:
                        "{{ route('category.store') }}",

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

                            $(
                                "#categoryCreateModalForm"
                            )[0].reset();


                            $(
                                "#categoryCreateModal"
                            ).modal("hide");


                            window.location.href =
                                "{{ route('category.index') }}?added-successfully=" +
                                encodeURIComponent(
                                    response.message
                                );

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