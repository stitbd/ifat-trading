<!-- Modal Header -->
<div
    class="modal-header"
    style="
        background-color:#333333;
        border-bottom:1px solid #ccc;
    ">

    <h5
        class="modal-title"
        id="categoryEditModalLabel"
        style="color:#ffffff;">

        Edit Category

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
        id="categoryEditModalForm"
        method="POST"
        enctype="multipart/form-data">

        @csrf

        @method('PUT')


        <!-- Row 1 -->
        <div class="row">

            <!-- Name -->
            <div class="col-md-6 mb-3">

                <label
                    for="edit_category_name"
                    class="form-label fw-bold text-dark">

                    Category Name:

                </label>

                <input
                    type="text"
                    class="form-control border-dark"
                    id="edit_category_name"
                    name="name"
                    maxlength="100"
                    placeholder="Enter category name"
                    value="{{ $data->name }}">

                <div
                    class="invalid-feedback name-error">
                </div>

            </div>


            <!-- Image -->
            <div class="col-md-6 mb-3">

                <label
                    for="edit_category_image"
                    class="form-label fw-bold text-dark">

                    Category Image:

                </label>

                <input
                    type="file"
                    class="form-control border-dark"
                    id="edit_category_image"
                    name="image"
                    accept="image/*">

                <div
                    class="invalid-feedback image-error">
                </div>


                @if ($data->image)

                    <div class="mt-2">

                        <img
                            src="{{ asset('categories/image/' . $data->image) }}"
                            width="120"
                            height="120"
                            style="
                                object-fit:cover;
                                border-radius:5px;
                                border:1px solid #ddd;
                            "
                            alt="Category Image">

                    </div>

                @endif

            </div>

        </div>


        <!-- Row 2 -->
        <div class="row">

            <!-- Description -->
            <div class="col-md-12 mb-3">

                <label
                    for="edit_category_description"
                    class="form-label fw-bold text-dark">

                    Description:

                </label>

                <textarea
                    class="form-control border-dark"
                    id="edit_category_description"
                    name="description"
                    rows="5"
                    placeholder="Enter category description">{{ $data->description }}</textarea>

                <div
                    class="invalid-feedback description-error">
                </div>

            </div>

        </div>


        <input
            type="hidden"
            value="{{ $data->id }}"
            id="category_id">


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

                Update Category

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


<script>

    $(document).ready(function() {

        /*
        |--------------------------------------------------------------------------
        | Update Category
        |--------------------------------------------------------------------------
        */

        $("#categoryEditModalForm").on(
            "submit",
            function(e) {

                e.preventDefault();

                showLoading();

                let formData =
                    new FormData(this);

                let categoryId =
                    $("#category_id").val();


                $.ajax({

                    url:
                        "{{ route('category.update', ':id') }}"
                            .replace(
                                ':id',
                                categoryId
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

                            $(
                                "#categoryEditModalForm"
                            )[0].reset();


                            $(
                                "#categoryEditModal"
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