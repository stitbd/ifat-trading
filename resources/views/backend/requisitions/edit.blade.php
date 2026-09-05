@extends('layouts.backend')

@section('title')
    Edit Requisition
@endsection

@section('content')
    <div class="app-toolbar py-3 py-lg-6">
        <div class="app-container container-fluid">
            <div class="admin-page-header">
                <div class="admin-page-header-title">
                    <span class="icon-box"><i class="bi bi-pencil-square"></i></span>
                    <h1>Edit Requisition</h1>
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
                @method('PUT')

                <input type="hidden" id="requisition_id" value="{{ $data->id }}">

                <!-- Header Fields -->
                <div class="admin-card mb-4">
                    <div class="admin-card-header">
                        <h5><i class="bi bi-info-circle" style="color:#4361ee;"></i> Requisition Info</h5>
                    </div>

                    <div style="padding:24px;">
                        <div class="row">

                            <!-- Requisition No (readonly) -->
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">Requisition No</label>
                                <input type="text" class="form-control" value="{{ $data->requisition_no }}" readonly>
                            </div>

                            <!-- Requisition Type -->
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold d-block">Type of Requisition <span
                                        class="text-danger">*</span></label>
                                <div class="d-flex gap-3 pt-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="requisition_type"
                                            id="type_local" value="local"
                                            {{ $data->requisition_type == 'local' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="type_local">Local</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="requisition_type"
                                            id="type_import" value="import"
                                            {{ $data->requisition_type == 'import' ? 'checked' : '' }}>
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
                                        <option value="{{ $wing->id }}"
                                            {{ $data->wing_id == $wing->id ? 'selected' : '' }}>
                                            {{ $wing->name }}
                                        </option>
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
                                        <option value="{{ $warehouse->id }}"
                                            {{ $data->warehouse_id == $warehouse->id ? 'selected' : '' }}>
                                            {{ $warehouse->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback warehouse_id-error"></div>
                            </div>

                            <!-- Place of Supply -->
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">Place of Supply</label>
                                <input type="text" class="form-control" name="place_of_supply"
                                    value="{{ $data->place_of_supply }}" placeholder="Enter Place of Supply">
                                <div class="invalid-feedback place_of_supply-error"></div>
                            </div>

                            <!-- Date -->
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">Requisition Date <span
                                        class="text-danger">*</span></label>
                                <input type="date" id="requisitionDate" class="form-control" name="date"
                                    value="{{ \Carbon\Carbon::parse($data->date)->format('Y-m-d') }}" required>
                                <div class="invalid-feedback date-error"></div>
                            </div>

                            <!-- Contact Person -->
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">Contact Person & Number</label>
                                <textarea class="form-control" name="contact_person_info" rows="1" placeholder="Contact Person & number">{{ $data->contact_person_info }}</textarea>
                            </div>

                            <!-- Note -->
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">Note</label>
                                <textarea class="form-control" name="note" rows="1" placeholder="Enter Note">{{ $data->note }}</textarea>
                                <div class="invalid-feedback note-error"></div>
                            </div>

                            <!-- Requisition By -->
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">Requisition By</label>
                                <input type="text" class="form-control" value="{{ $data->createdBy?->name ?? '-' }}"
                                    readonly>
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

                            <div class="table-responsive">
                                <table class="table table-bordered align-middle" id="categoryProductsTable">
                                    <thead>
                                        <tr class="text-center">
                                            <th width="40"><input type="checkbox" id="checkAllProducts"></th>
                                            <th>Product Name</th>
                                            <th>Size</th>
                                            <th width="120">Physical Stock<br><small
                                                    class="as-on-label text-muted fw-normal"></small></th>
                                            <th width="120">In Transit<br><small
                                                    class="as-on-label text-muted fw-normal"></small></th>
                                            <th width="120">LC Pending<br><small
                                                    class="as-on-label text-muted fw-normal"></small></th>
                                            <th width="110">PI<br><small
                                                    class="pi-month-label text-muted fw-normal"></small></th>
                                            <th width="150">Sale<br><small
                                                    class="sale-label-1 text-muted fw-normal"></small></th>
                                            <th width="150">Sale<br><small
                                                    class="sale-label-2 text-muted fw-normal"></small></th>
                                            <th width="150">Sale<br><small
                                                    class="sale-label-3 text-muted fw-normal"></small></th>
                                            <th width="120">Requirement<br><small
                                                    class="requirement-month-label text-muted fw-normal"></small></th>
                                        </tr>
                                    </thead>
                                    <tbody id="categoryProductsBody">
                                        <!-- AJAX loaded rows -->
                                    </tbody>
                                </table>
                            </div>

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

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle" id="summaryTable">
                                <thead>
                                    <tr class="text-center">
                                        <th>Size</th>
                                        <th width="120">Physical Stock<br><small
                                                class="as-on-label text-muted fw-normal"></small></th>
                                        <th width="120">In Transit<br><small
                                                class="as-on-label text-muted fw-normal"></small></th>
                                        <th width="120">LC Pending<br><small
                                                class="as-on-label text-muted fw-normal"></small></th>
                                        <th width="110">PI<br><small
                                                class="pi-month-label text-muted fw-normal"></small></th>
                                        <th width="150">Sale 1<br><small
                                                class="sale-label-1 text-muted fw-normal"></small></th>
                                        <th width="150">Sale 2<br><small
                                                class="sale-label-2 text-muted fw-normal"></small></th>
                                        <th width="150">Sale 3<br><small
                                                class="sale-label-3 text-muted fw-normal"></small></th>
                                        <th width="120">Requirement<br><small
                                                class="requirement-month-label text-muted fw-normal"></small></th>
                                        <th width="60">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="summaryTableBody">
                                    <tr id="summaryEmptyRow">
                                        <td colspan="10" class="text-center text-muted">No items added yet</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mb-5">
                    <a href="{{ route('requisition.index') }}" class="btn"
                        style="border:1px solid #dfe2e8;color:#4a4a5a;border-radius:8px;padding:8px 18px;">
                        <i class="bi bi-x-lg me-1"></i> Cancel
                    </a>
                    <button type="submit" class="btn submit-btn"
                        style="background:#4361ee;color:#fff;border-radius:8px;padding:8px 20px;font-weight:600;">
                        <i class="bi bi-check-lg me-1"></i> Update Requisition
                    </button>
                </div>

            </form>

        </div>
    </div>

    <style>
        .category-row td {
            background: #eef2ff;
            font-weight: 700;
            color: #14532d;
        }

        .subtotal-row td {
            background: #f8f9fa;
            font-weight: 600;
        }

        .grandtotal-row td {
            background: #14532d;
            color: #fff;
            font-weight: 700;
        }

        .product-code-sub {
            display: block;
            font-size: 12px;
            color: #6c757d;
        }
    </style>

    <script>
        $(document).ready(function() {

            // Pre-fill existing items from server
            let existingItemsData = @json($existingItems);

            let requisitionItems = {};

            $.each(existingItemsData, function(i, item) {
                requisitionItems[item.product_id] = item;
            });

            const monthFull = ['January', 'February', 'March', 'April', 'May', 'June', 'July',
                'August', 'September', 'October', 'November', 'December'
            ];
            const monthShort = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep',
                'Oct', 'Nov', 'Dec'
            ];

            /*
            |--------------------------------------------------------------------------
            | Date Helpers
            |--------------------------------------------------------------------------
            */
            function daysInMonth(year, month) {
                return new Date(year, month, 0).getDate();
            }

            function ordinal(day) {
                if (day > 3 && day < 21) return day + 'th';
                switch (day % 10) {
                    case 1:
                        return day + 'st';
                    case 2:
                        return day + 'nd';
                    case 3:
                        return day + 'rd';
                    default:
                        return day + 'th';
                }
            }

            function shiftMonth(year, month, offset) {
                let d = new Date(year, (month - 1) + offset, 1);
                return {
                    year: d.getFullYear(),
                    month: d.getMonth() + 1
                };
            }

            function formatSaleRange(m) {
                let lastDay = daysInMonth(m.year, m.month);
                let yy = String(m.year).slice(-2);
                let mon = monthShort[m.month - 1];
                return '1st ' + mon + "'" + yy + ' to ' + ordinal(lastDay) + ' ' + mon + "'" + yy;
            }

            /*
            |--------------------------------------------------------------------------
            | Recompute & render all date-based column headers based on Requisition Date
            |--------------------------------------------------------------------------
            */
            function updateDateLabels() {

                let dateVal = $('#requisitionDate').val();
                let baseDate = dateVal ? new Date(dateVal) : new Date();

                let reqYear = baseDate.getFullYear();
                let reqMonth = baseDate.getMonth() + 1;

                let reqDay = baseDate.getDate();
                let asOnText = 'AS ON ' + reqDay + '.' + reqMonth + '.' + reqYear;

                let piM = {
                    year: reqYear,
                    month: reqMonth
                };
                let piText = monthFull[piM.month - 1] + "'" + piM.year;

                let requirementText = 'FOR ' + monthFull[reqMonth - 1].toUpperCase() + "'" +
                    reqYear;

                $('.as-on-label').text(asOnText);
                $('.pi-month-label').text(piText);
                $('.requirement-month-label').text(requirementText);

                let s3 = shiftMonth(piM.year, piM.month, -1);
                let s2 = shiftMonth(piM.year, piM.month, -2);
                let s1 = shiftMonth(piM.year, piM.month, -3);

                $('.sale-label-1').text(formatSaleRange(s1));
                $('.sale-label-2').text(formatSaleRange(s2));
                $('.sale-label-3').text(formatSaleRange(s3));
            }

            updateDateLabels();

            $('#requisitionDate').on('input change', function() {
                updateDateLabels();
            });

            renderSummaryTable();

            /*
            |--------------------------------------------------------------------------
            | Category Change -> Load Products
            |--------------------------------------------------------------------------
            */
            $('#category_select').on('change', function() {

                let categoryId = $(this).val();
                let categoryName = $(this).find('option:selected').text();

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

                            let existing = requisitionItems[p.id];
                            let alreadyAdded = existing ? 'checked' : '';
                            let disabledAttr = alreadyAdded ? '' : 'disabled';

                            let physicalStock = existing ? existing.physical_stock : 0;
                            let inTransit = existing ? existing.in_transit : 0;
                            let lcPending = existing ? existing.lc_pending : 0;
                            let pi = existing ? existing.pi : 0;
                            let saleOne = existing ? existing.sale_one : 0;
                            let saleTwo = existing ? existing.sale_two : 0;
                            let saleThree = existing ? existing.sale_three : 0;
                            let requirement = existing ? existing.quantity : 1;

                            rows += `
                                <tr data-product-id="${p.id}"
                                    data-name="${p.name}"
                                    data-code="${p.product_code}"
                                    data-brand="${p.brand_name}"
                                    data-size="${p.size_name}"
                                    data-category="${categoryName}">
                                    <td class="text-center"><input type="checkbox" class="product-check" ${alreadyAdded}></td>
                                    <td>${p.name}<span class="product-code-sub">${p.product_code}</span></td>
                                    <td>${p.size_name}</td>
                                    <td><input type="number" min="0" class="form-control product-physical-stock" value="${physicalStock}" ${disabledAttr}></td>
                                    <td><input type="number" min="0" class="form-control product-in-transit" value="${inTransit}" ${disabledAttr}></td>
                                    <td><input type="number" min="0" class="form-control product-lc-pending" value="${lcPending}" ${disabledAttr}></td>
                                    <td><input type="number" min="0" class="form-control product-pi" value="${pi}" ${disabledAttr}></td>
                                    <td><input type="number" min="0" class="form-control product-sale-one" value="${saleOne}" ${disabledAttr}></td>
                                    <td><input type="number" min="0" class="form-control product-sale-two" value="${saleTwo}" ${disabledAttr}></td>
                                    <td><input type="number" min="0" class="form-control product-sale-three" value="${saleThree}" ${disabledAttr}></td>
                                    <td><input type="number" min="1" class="form-control product-requirement" value="${requirement}" ${disabledAttr}></td>
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
            | Toggle input fields based on checkbox state
            |--------------------------------------------------------------------------
            */
            $(document).on('change', '.product-check', function() {

                let row = $(this).closest('tr');
                let inputs = row.find(
                    '.product-physical-stock, .product-in-transit, .product-lc-pending, .product-pi, .product-sale-one, .product-sale-two, .product-sale-three, .product-requirement'
                );

                if ($(this).is(':checked')) {
                    inputs.prop('disabled', false);
                    let requirementInput = row.find('.product-requirement');
                    if (!requirementInput.val() || requirementInput.val() <= 0) {
                        requirementInput.val(1);
                    }
                } else {
                    inputs.prop('disabled', true).val(0);
                    row.find('.product-requirement').val('');
                }
            });

            /*
            |--------------------------------------------------------------------------
            | Check All
            |--------------------------------------------------------------------------
            */
            $(document).on('change', '#checkAllProducts', function() {
                let isChecked = $(this).is(':checked');
                $('.product-check').prop('checked', isChecked).trigger('change');
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
                    let physicalStock = parseInt(row.find('.product-physical-stock').val()) || 0;
                    let inTransit = parseInt(row.find('.product-in-transit').val()) || 0;
                    let lcPending = parseInt(row.find('.product-lc-pending').val()) || 0;
                    let pi = parseInt(row.find('.product-pi').val()) || 0;
                    let saleOne = parseInt(row.find('.product-sale-one').val()) || 0;
                    let saleTwo = parseInt(row.find('.product-sale-two').val()) || 0;
                    let saleThree = parseInt(row.find('.product-sale-three').val()) || 0;
                    let requirement = parseInt(row.find('.product-requirement').val()) || 0;

                    if (requirement <= 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Invalid Requirement',
                            text: 'Requirement must be greater than 0 for: ' + row.data(
                                'name')
                        });
                        return;
                    }

                    requisitionItems[productId] = {
                        product_id: productId,
                        name: row.data('name'),
                        code: row.data('code'),
                        brand: row.data('brand'),
                        size: row.data('size'),
                        category: row.data('category'),
                        physical_stock: physicalStock,
                        in_transit: inTransit,
                        lc_pending: lcPending,
                        pi: pi,
                        sale_one: saleOne,
                        sale_two: saleTwo,
                        sale_three: saleThree,
                        quantity: requirement
                    };

                    addedCount++;
                });

                if (addedCount > 0) {
                    renderSummaryTable();
                }
            });

            /*
            |--------------------------------------------------------------------------
            | Render Summary Table (Grouped by Category, like Excel)
            |--------------------------------------------------------------------------
            */
            function renderSummaryTable() {

                let items = Object.values(requisitionItems);

                if (!items.length) {
                    $('#summaryTableBody').html(
                        '<tr id="summaryEmptyRow"><td colspan="10" class="text-center text-muted">No items added yet</td></tr>'
                    );
                    return;
                }

                let grouped = {};

                $.each(items, function(i, item) {
                    let cat = item.category || 'Uncategorized';
                    if (!grouped[cat]) {
                        grouped[cat] = [];
                    }
                    grouped[cat].push(item);
                });

                let rows = '';

                let grandTotal = {
                    physical_stock: 0,
                    in_transit: 0,
                    lc_pending: 0,
                    pi: 0,
                    sale_one: 0,
                    sale_two: 0,
                    sale_three: 0,
                    quantity: 0
                };

                $.each(grouped, function(categoryName, categoryItems) {

                    rows += `
                        <tr class="category-row">
                            <td colspan="10">${categoryName}</td>
                        </tr>
                    `;

                    let subTotal = {
                        physical_stock: 0,
                        in_transit: 0,
                        lc_pending: 0,
                        pi: 0,
                        sale_one: 0,
                        sale_two: 0,
                        sale_three: 0,
                        quantity: 0
                    };

                    $.each(categoryItems, function(i, item) {

                        rows += `
                            <tr>
                                <td>${item.size}</td>
                                <td class="text-center">${item.physical_stock}</td>
                                <td class="text-center">${item.in_transit}</td>
                                <td class="text-center">${item.lc_pending}</td>
                                <td class="text-center">${item.pi}</td>
                                <td class="text-center">${item.sale_one}</td>
                                <td class="text-center">${item.sale_two}</td>
                                <td class="text-center">${item.sale_three}</td>
                                <td class="text-center">${item.quantity}</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-danger remove-item" data-id="${item.product_id}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `;

                        subTotal.physical_stock += item.physical_stock;
                        subTotal.in_transit += item.in_transit;
                        subTotal.lc_pending += item.lc_pending;
                        subTotal.pi += item.pi;
                        subTotal.sale_one += item.sale_one;
                        subTotal.sale_two += item.sale_two;
                        subTotal.sale_three += item.sale_three;
                        subTotal.quantity += item.quantity;
                    });

                    rows += `
                        <tr class="subtotal-row">
                            <td colspan="1" class="text-end">Sub Total</td>
                            <td class="text-center">${subTotal.physical_stock}</td>
                            <td class="text-center">${subTotal.in_transit}</td>
                            <td class="text-center">${subTotal.lc_pending}</td>
                            <td class="text-center">${subTotal.pi}</td>
                            <td class="text-center">${subTotal.sale_one}</td>
                            <td class="text-center">${subTotal.sale_two}</td>
                            <td class="text-center">${subTotal.sale_three}</td>
                            <td class="text-center">${subTotal.quantity}</td>
                            <td></td>
                        </tr>
                    `;

                    grandTotal.physical_stock += subTotal.physical_stock;
                    grandTotal.in_transit += subTotal.in_transit;
                    grandTotal.lc_pending += subTotal.lc_pending;
                    grandTotal.pi += subTotal.pi;
                    grandTotal.sale_one += subTotal.sale_one;
                    grandTotal.sale_two += subTotal.sale_two;
                    grandTotal.sale_three += subTotal.sale_three;
                    grandTotal.quantity += subTotal.quantity;
                });

                rows += `
                    <tr class="grandtotal-row">
                        <td colspan="1" class="text-end">Grand Total</td>
                        <td class="text-center">${grandTotal.physical_stock}</td>
                        <td class="text-center">${grandTotal.in_transit}</td>
                        <td class="text-center">${grandTotal.lc_pending}</td>
                        <td class="text-center">${grandTotal.pi}</td>
                        <td class="text-center">${grandTotal.sale_one}</td>
                        <td class="text-center">${grandTotal.sale_two}</td>
                        <td class="text-center">${grandTotal.sale_three}</td>
                        <td class="text-center">${grandTotal.quantity}</td>
                        <td></td>
                    </tr>
                `;

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
            | Submit Update
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

                let requisitionId = $('#requisition_id').val();

                $.ajax({
                    url: "{{ route('requisition.update', ':id') }}".replace(':id', requisitionId),
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
