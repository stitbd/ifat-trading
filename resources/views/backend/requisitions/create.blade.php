@extends('layouts.backend')

@section('title')
    Add Requisition
@endsection

@section('content')
    <div class="app-toolbar py-3 py-lg-6">
        <div class="app-container container-fluid">
            <div class="admin-page-header">
                <div class="admin-page-header-title">
                    <span class="icon-box"><i class="bi bi-file-earmark-text"></i></span>
                    <h1>Add Requisition</h1>
                </div>
                <a href="{{ route('requisition.index') }}" class="btn-admin-secondary">
                    <i class="bi bi-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
    </div>

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">

            <form id="requisitionForm">
                @csrf

                <!-- Header Fields -->
                <div class="admin-card mb-4">
                    <div class="admin-card-header">
                        <h5><i class="bi bi-info-circle" style="color:#4361ee;"></i> Requisition Info</h5>
                    </div>

                    <div style="padding:24px;">
                        <div class="row">

                            <!-- Requisition No (readonly preview) -->
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">Requisition No</label>
                                <input type="text" class="form-control" value="{{ $requisitionNoPreview }}" readonly>
                            </div>

                            <!-- Requisition Type -->
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold d-block">Type of Requisition <span
                                        class="text-danger">*</span></label>
                                <div class="d-flex gap-3 pt-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="requisition_type"
                                            id="type_local" value="local" checked>
                                        <label class="form-check-label" for="type_local">Local</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="requisition_type"
                                            id="type_import" value="import">
                                        <label class="form-check-label" for="type_import">Import</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Wing -->
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">Wing <span class="text-danger">*</span></label>
                                <select class="form-select" name="wing_id" required>
                                    <option value="">Select Wing</option>
                                    @foreach ($wings as $wing)
                                        <option value="{{ $wing->id }}">{{ $wing->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback wing_id-error"></div>
                            </div>

                            <!-- Warehouse -->
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">Warehouse <span class="text-danger">*</span></label>
                                <select class="form-select" name="warehouse_id" required>
                                    <option value="">Select Warehouse</option>
                                    @foreach ($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback warehouse_id-error"></div>
                            </div>

                            <!-- Date -->
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">Requisition Date <span
                                        class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="date" value="{{ date('Y-m-d') }}"
                                    required>
                                <div class="invalid-feedback date-error"></div>
                            </div>

                            <!-- Requisition By (auto, readonly) -->
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">Requisition By</label>
                                <input type="text" class="form-control" value="{{ Auth::user()->name }}" readonly>
                            </div>

                            <!-- Place of Supply -->
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">Place of Supply</label>
                                <input type="text" class="form-control" name="place_of_supply"
                                    placeholder="Enter Place of Supply">
                                <div class="invalid-feedback place_of_supply-error"></div>
                            </div>

                            <!-- Note -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Note</label>
                                <textarea class="form-control" name="note" rows="1" placeholder="Enter Note"></textarea>
                                <div class="invalid-feedback note-error"></div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Category Product Picker -->
                <div class="admin-card mb-4">
                    <div class="admin-card-header">
                        <h5><i class="bi bi-box-seam" style="color:#4361ee;"></i> Select Products</h5>
                    </div>

                    <div style="padding:24px;">

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Category</label>
                                <select id="category_select" class="form-select">
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div id="categoryProductsWrapper" style="display:none;">

                            <table class="table table-bordered align-middle" id="categoryProductsTable">
                                <thead>
                                    <tr>
                                        <th width="40"><input type="checkbox" id="checkAllProducts"></th>
                                        <th>Product Name</th>
                                        <th>Product Code</th>
                                        <th>Brand</th>
                                        <th>Size</th>
                                        <th width="120">Quantity</th>
                                        <th width="180">Note</th>
                                    </tr>
                                </thead>
                                <tbody id="categoryProductsBody">
                                    <!-- AJAX loaded rows -->
                                </tbody>
                            </table>

                            <button type="button" id="addSelectedBtn" class="btn-admin-primary">
                                <i class="bi bi-plus-lg"></i> Add to Requisition
                            </button>

                        </div>

                        <div id="categoryLoadingMsg" class="text-muted" style="display:none;">
                            Loading products...
                        </div>

                        <div id="categoryEmptyMsg" class="text-muted" style="display:none;">
                            No products found under this category.
                        </div>

                    </div>
                </div>

                <!-- Added Items Summary -->
                <div class="admin-card mb-4">
                    <div class="admin-card-header">
                        <h5><i class="bi bi-list-check" style="color:#4361ee;"></i> Requisition Items</h5>
                    </div>

                    <div style="padding:24px;">

                        <table class="table table-bordered align-middle" id="summaryTable">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Product Code</th>
                                    <th>Brand</th>
                                    <th>Size</th>
                                    <th width="100">Quantity</th>
                                    <th>Note</th>
                                    <th width="60">Action</th>
                                </tr>
                            </thead>
                            <tbody id="summaryTableBody">
                                <tr id="summaryEmptyRow">
                                    <td colspan="7" class="text-center text-muted">No items added yet</td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mb-5">
                    <a href="{{ route('requisition.index') }}" class="btn"
                        style="border:1px solid #dfe2e8;color:#4a4a5a;border-radius:8px;padding:8px 18px;">
                        <i class="bi bi-x-lg me-1"></i> Cancel
                    </a>
                    <button type="submit" class="btn submit-btn"
                        style="background:#4361ee;color:#fff;border-radius:8px;padding:8px 20px;font-weight:600;">
                        <i class="bi bi-check-lg me-1"></i> Save Requisition
                    </button>
                </div>

            </form>

        </div>
    </div>

    <script>
        $(document).ready(function() {

            // Holds all added items across category switches: { product_id: {...} }
            let requisitionItems = {};

            /*
            |--------------------------------------------------------------------------
            | Category Change -> Load Products
            |--------------------------------------------------------------------------
            */
            $('#category_select').on('change', function() {

                let categoryId = $(this).val();

                $('#categoryProductsBody').html('');
                $('#categoryProductsWrapper').hide();
                $('#categoryEmptyMsg').hide();

                if (!categoryId) return;

                $('#categoryLoadingMsg').show();

                $.ajax({
                    url: "{{ route('requisition.products-by-category', ':id') }}".replace(':id',
                        categoryId),
                    type: 'GET',
                    success: function(products) {

                        $('#categoryLoadingMsg').hide();

                        if (!products.length) {
                            $('#categoryEmptyMsg').show();
                            return;
                        }

                        let rows = '';

                        $.each(products, function(i, p) {

                            let alreadyAdded = requisitionItems[p.id] ? 'checked' : '';
                            let existingQty = requisitionItems[p.id] ? requisitionItems[
                                p.id].quantity : 1;
                            let existingNote = requisitionItems[p.id] ?
                                requisitionItems[p.id].note : '';

                            rows += `
                        <tr data-product-id="${p.id}"
                            data-name="${p.name}"
                            data-code="${p.product_code}"
                            data-brand="${p.brand_name}"
                            data-size="${p.size_name}">
                            <td><input type="checkbox" class="product-check" ${alreadyAdded}></td>
                            <td>${p.name}</td>
                            <td>${p.product_code}</td>
                            <td>${p.brand_name}</td>
                            <td>${p.size_name}</td>
                            <td><input type="number" min="1" class="form-control product-qty" value="${existingQty}"></td>
                            <td><input type="text" class="form-control product-note" value="${existingNote}" placeholder="Note"></td>
                        </tr>
                    `;
                        });

                        $('#categoryProductsBody').html(rows);
                        $('#categoryProductsWrapper').show();
                    },
                    error: function() {
                        $('#categoryLoadingMsg').hide();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Unable to load products for this category!'
                        });
                    }
                });
            });

            /*
            |--------------------------------------------------------------------------
            | Check All
            |--------------------------------------------------------------------------
            */
            $(document).on('change', '#checkAllProducts', function() {
                $('.product-check').prop('checked', $(this).is(':checked'));
            });

            /*
            |--------------------------------------------------------------------------
            | Add Selected Products to Summary
            |--------------------------------------------------------------------------
            */
            $('#addSelectedBtn').on('click', function() {

                let addedCount = 0;

                $('#categoryProductsBody tr').each(function() {

                    let row = $(this);
                    let checked = row.find('.product-check').is(':checked');

                    if (!checked) return;

                    let productId = row.data('product-id');
                    let quantity = parseInt(row.find('.product-qty').val()) || 0;
                    let note = row.find('.product-note').val();

                    if (quantity <= 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Invalid Quantity',
                            text: 'Quantity must be greater than 0 for: ' + row.data('name')
                        });
                        return;
                    }

                    requisitionItems[productId] = {
                        product_id: productId,
                        name: row.data('name'),
                        code: row.data('code'),
                        brand: row.data('brand'),
                        size: row.data('size'),
                        quantity: quantity,
                        note: note
                    };

                    addedCount++;
                });

                if (addedCount > 0) {
                    renderSummaryTable();
                }
            });

            /*
            |--------------------------------------------------------------------------
            | Render Summary Table
            |--------------------------------------------------------------------------
            */
            function renderSummaryTable() {

                let items = Object.values(requisitionItems);

                if (!items.length) {
                    $('#summaryTableBody').html(
                        '<tr id="summaryEmptyRow"><td colspan="7" class="text-center text-muted">No items added yet</td></tr>'
                    );
                    return;
                }

                let rows = '';

                $.each(items, function(i, item) {
                    rows += `
                <tr>
                    <td>${item.name}</td>
                    <td>${item.code}</td>
                    <td>${item.brand}</td>
                    <td>${item.size}</td>
                    <td>${item.quantity}</td>
                    <td>${item.note ?? ''}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-outline-danger remove-item" data-id="${item.product_id}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
                });

                $('#summaryTableBody').html(rows);
            }

            /*
            |--------------------------------------------------------------------------
            | Remove Item from Summary
            |--------------------------------------------------------------------------
            */
            $(document).on('click', '.remove-item', function() {
                let productId = $(this).data('id');
                delete requisitionItems[productId];
                renderSummaryTable();
            });

            /*
            |--------------------------------------------------------------------------
            | Submit Requisition
            |--------------------------------------------------------------------------
            */
            $('#requisitionForm').on('submit', function(e) {

                e.preventDefault();

                let items = Object.values(requisitionItems);

                if (!items.length) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Items',
                        text: 'Please add at least one product to the requisition!'
                    });
                    return;
                }

                showLoading();

                let formData = $(this).serializeArray();
                let data = {};

                $.each(formData, function(i, field) {
                    data[field.name] = field.value;
                });

                data.items = JSON.stringify(items);

                $.ajax({
                    url: "{{ route('requisition.store') }}",
                    type: 'POST',
                    data: data,
                    beforeSend: function() {
                        $('.invalid-feedback').text('').hide();
                    },
                    success: function(response) {
                        hideLoading();
                        if (response.success) {
                            window.location.href = "{{ route('requisition.index') }}" +
                                "?added-successfully=" + encodeURIComponent(response.message);
                        }
                    },
                    error: function(xhr) {
                        hideLoading();

                        if (xhr.status === 422 && xhr.responseJSON.errors) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(field, messages) {
                                $('.' + field + '-error').text(messages[0]).show();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: xhr.responseJSON?.message ||
                                    'Something went wrong!'
                            });
                        }
                    }
                });
            });

        });
    </script>
@endsection
