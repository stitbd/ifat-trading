<div
    class="modal fade"
    id="subcategoryCreateModal"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog modal-xl">

        <div
            class="modal-content"
            style="
                background-color:#f8f9fa;
                border-radius:8px;
                border:1px solid #ddd;
            ">


            <div
                class="modal-header"
                style="
                    background-color:#333333;
                    border-bottom:1px solid #ccc;
                ">

                <h5
                    class="modal-title"
                    style="color:#ffffff;">

                    Create New Subcategory

                </h5>

                <button
                    type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal">
                </button>

            </div>


            <div class="modal-body">

                <form
                    id="subcategoryCreateModalForm"
                    method="POST"
                    enctype="multipart/form-data">

                    @csrf


                    <div class="row">

                        {{-- Category --}}

                        <div class="col-md-6 mb-3">

                            <label
                                class="form-label fw-bold text-dark">

                                Category:

                            </label>

                            <select
                                name="category_id"
                                id="subcategory_category_id"
                                class="form-select border-dark">

                                <option value="">
                                    Select Category
                                </option>

                                @foreach ($categories as $category)

                                    <option
                                        value="{{ $category->id }}">

                                        {{ $category->name }}

                                    </option>

                                @endforeach

                            </select>

                            <div
                                class="invalid-feedback category_id-error">
                            </div>

                        </div>


                        {{-- Name --}}

                        <div class="col-md-6 mb-3">

                            <label
                                class="form-label fw-bold text-dark">

                                Subcategory Name:

                            </label>

                            <input
                                type="text"
                                class="form-control border-dark"
                                name="name"
                                maxlength="100"
                                placeholder="Enter subcategory name">

                            <div
                                class="invalid-feedback name-error">
                            </div>

                        </div>

                    </div>


                    <div class="row">

                        {{-- Image --}}

                        <div class="col-md-6 mb-3">

                            <label
                                class="form-label fw-bold text-dark">

                                Subcategory Image:

                            </label>

                            <input
                                type="file"
                                class="form-control border-dark"
                                name="image"
                                accept="image/*">

                            <div
                                class="invalid-feedback image-error">
                            </div>

                        </div>


                        {{-- Description --}}

                        <div class="col-md-6 mb-3">

                            <label
                                class="form-label fw-bold text-dark">

                                Description:

                            </label>

                            <textarea
                                class="form-control border-dark"
                                name="description"
                                rows="4"
                                placeholder="Enter description"></textarea>

                            <div
                                class="invalid-feedback description-error">
                            </div>

                        </div>

                    </div>


                    <div class="text-end">

                        <button
                            type="submit"
                            class="btn"
                            style="
                                background-color:#FF4C29;
                                color:#ffffff;
                                border-radius:5px;
                            ">

                            Create Subcategory

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

        $('#subcategoryCreateModalForm').on(
            'submit',
            function(e) {

                e.preventDefault();

                showLoading();

                let formData =
                    new FormData(this);


                $.ajax({

                    url:
                        "{{ route('subcategory.store') }}",

                    type:
                        'POST',

                    data:
                        formData,

                    processData:
                        false,

                    contentType:
                        false,


                    beforeSend: function() {

                        $('.invalid-feedback')
                            .text('')
                            .hide();

                    },


                    success: function(response) {

                        hideLoading();

                        if (response.success) {

                            $('#subcategoryCreateModalForm')[0]
                                .reset();

                            $('#subcategoryCreateModal')
                                .modal('hide');

                            $('#subcategoryTable')
                                .DataTable()
                                .ajax
                                .reload(null, false);

                            Swal.fire({

                                icon:
                                    'success',

                                title:
                                    response.message,

                                showConfirmButton:
                                    false,

                                timer:
                                    2000
                            });
                        }

                    },


                    error: function(xhr) {

                        hideLoading();

                        if (xhr.status === 422) {

                            let errors =
                                xhr.responseJSON.errors;

                            $.each(
                                errors,
                                function(field, messages) {

                                    $('.' +
                                        field +
                                        '-error')
                                        .text(messages[0])
                                        .show();

                                }
                            );

                            setTimeout(function() {

                                $('.invalid-feedback')
                                    .fadeOut();

                            }, 3000);

                        }

                    }

                });

            }
        );

    });

</script>
