@extends('layouts.backend')

@section('title')
    Generate CS - {{ $requisition->requisition_no }}
@endsection

@section('content')
    <div class="app-toolbar py-3 py-lg-6">
        <div class="app-container container-fluid">
            <div class="admin-page-header">
                <div class="admin-page-header-title">
                    <span class="icon-box"><i class="bi bi-file-earmark-text"></i></span>
                    <h1>Generate Comparative Statement</h1>
                </div>
                <a href="{{ route('requisition.index') }}" class="btn-admin-secondary">
                    <i class="bi bi-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
    </div>

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">

            <form id="csForm">
                @csrf

                <!-- CS / Requsition Info -->
                <div class="admin-card mb-4">
                    <div class="admin-card-header">
                        <h5><i class="bi bi-info-circle" style="color:#4361ee;"></i> CS Info</h5>
                    </div>
                    <div style="padding:24px;">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">CS Create By</label>
                                <input type="text" class="form-control" value="{{ Auth::user()->name }}" readonly>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Requsition By</label>
                                <input type="text" class="form-control"
                                    value="{{ $requisition->createdBy?->name ?? '-' }}" readonly>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">CS Reference No</label>
                                <input type="text" class="form-control" value="{{ $csNoPreview }}" readonly>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Requsition No</label>
                                <input type="text" class="form-control" value="{{ $requisition->requisition_no }}"
                                    readonly>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Date of CS <span class="text-danger">*</span></label>
                                <input type="date" name="cs_date" id="csDate" class="form-control"
                                    value="{{ date('Y-m-d') }}" required>
                                <div class="invalid-feedback cs_date-error"></div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Requsition Date</label>
                                <input type="text" class="form-control"
                                    value="{{ \Carbon\Carbon::parse($requisition->date)->format('d-m-Y') }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Requsition Details + Supplier Quotation entry — MERGED into ONE table -->
                <div class="admin-card mb-4">
                    <div class="admin-card-header">
                        <h5><i class="bi bi-box-seam" style="color:#4361ee;"></i> Product Requsition Details</h5>
                    </div>
                    <div style="padding:24px;">

                        <div id="itemsLockedNotice" class="alert alert-info py-2 px-3 mb-3" style="display:none;">
                            <i class="bi bi-lock-fill"></i>
                            Item Qty is locked because at least one supplier quotation has been added.
                            Unit Price above is now for the NEXT supplier — Qty stays fixed for this CS.
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Supplier <span class="text-danger">*</span></label>
                                <select id="supplierSelect" class="form-select">
                                    <option value="">Choose a supplier</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Supplier Quotation Number</label>
                                <input type="text" id="quotationNumber" class="form-control"
                                    placeholder="Supplier Quotation Number">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Quotation Date</label>
                                <input type="date" id="quotationDate" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Place of Supply</label>
                                <input type="text" id="placeOfSupply" class="form-control" placeholder="Place of Supply"
                                    value="{{ $requisition->place_of_supply }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Time of Supply</label>
                                <input type="date" id="timeOfSupply" class="form-control">
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle" id="indentDetailsTable">
                                <thead>
                                    <tr class="text-center">
                                        <th width="40">SL No</th>
                                        <th>Products</th>
                                        <th width="100">Required Qty</th>
                                        <th width="100">CS Created Qty</th>
                                        <th width="120">Current CS Qty</th>
                                        <th width="130">Unit Price</th>
                                        <th width="120">Total</th>
                                    </tr>
                                </thead>
                                <tbody id="indentDetailsBody">
                                    <!-- rendered by JS -->
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="6" class="text-end fw-bold">Total Amount</td>
                                        <td class="text-center fw-bold" id="entryRunningTotal">0.00</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Special Note</label>
                            <textarea id="groupNote" class="form-control" rows="3" placeholder="Input your note for this supplier"></textarea>
                        </div>

                        <button type="button" id="addSupplierBtn" class="btn-admin-primary">
                            <i class="bi bi-plus-lg"></i> Add Supplier
                        </button>
                    </div>
                </div>

                <!-- CS Details: side-by-side comparison across all added suppliers -->
                <div class="admin-card mb-4">
                    <div class="admin-card-header">
                        <h5><i class="bi bi-list-check" style="color:#4361ee;"></i> CS Details</h5>
                    </div>
                    <div style="padding:24px;">
                        <div id="csComparisonWrapper">
                            <div class="text-muted text-center" id="csComparisonEmptyMsg">No supplier quotations added yet
                            </div>
                        </div>

                        <div class="mb-3 mt-4">
                            <label class="form-label fw-bold">Remarks</label>
                            <textarea name="remarks" class="form-control" rows="3" placeholder="Input your remarks"></textarea>
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
                        <i class="bi bi-check-lg me-1"></i> Create CS
                    </button>
                </div>

            </form>

        </div>
    </div>

    <style>
        .product-code-sub {
            display: block;
            font-size: 12px;
            color: #6c757d;
        }

        #csComparisonTable th,
        #csComparisonTable td {
            vertical-align: middle;
        }

        .supplier-col-header {
            background: #eef2ff;
            font-weight: 700;
            color: #14532d;
        }

        .supplier-remove-btn {
            color: #dc3545;
            cursor: pointer;
            border: none;
            background: none;
            font-size: 14px;
            margin-left: 6px;
        }

        .cs-footer-label {
            text-align: right;
            font-weight: 700;
            background: #f8f9fa;
        }
    </style>

    <script>
        $(document).ready(function() {

            // Requisition line items: required_qty, already_covered_qty, remaining_qty
            const requisitionItems = @json($items);

            // Fixed CS Qty per item, keyed by requisition_detail_id.
            // Locked (read-only) once at least one supplier has been added.
            let csQtyMap = {};
            requisitionItems.forEach(i => csQtyMap[i.requisition_detail_id] = null);

            let itemsLocked = false;

            // Suppliers added so far, keyed by supplier_id.
            // { supplier_id: { supplier_id, supplier_name, quotation_number, quotation_date,
            //   place_of_supply, time_of_supply, note, prices: { requisition_detail_id: unit_price } } }
            let csGroups = {};

            /*
            |--------------------------------------------------------------------------
            | Single merged table: Qty entry (first time) + Price entry (every supplier)
            |--------------------------------------------------------------------------
            */
            function renderIndentDetailsTable() {

                let rows = '';
                let sl = 1;

                requisitionItems.forEach(item => {

                    let qty = csQtyMap[item.requisition_detail_id];
                    let fullyCovered = item.remaining_qty <= 0; // <-- fully covered check
                    let qtyDisabled = (itemsLocked || fullyCovered) ? 'disabled' : '';
                    let hasQty = qty > 0;
                    let priceDisabled = (hasQty && !fullyCovered) ? '' : 'disabled';

                    rows += `
                            <tr data-requisition-detail-id="${item.requisition_detail_id}">
                                <td class="text-center">${sl++}</td>
                                <td>
                                    <strong>${item.name}</strong>
                                    <span class="product-code-sub">${item.code} ${item.size ? '&middot; ' + item.size : ''}</span>
                                </td>
                                <td class="text-center">${item.required_qty}</td>
                                <td class="text-center">${item.already_covered_qty.toFixed(2)}</td>
                                <td>
                                    <input type="number" min="0" max="${item.remaining_qty}" step="any"
                                        class="form-control cs-qty-input" value="${qty ?? (fullyCovered ? 0 : '')}"
                                        placeholder="0" ${qtyDisabled}>
                                </td>
                                <td>
                                    <input type="number" min="0" step="any"
                                        class="form-control unit-price-input" value="" placeholder="Unit Rate" ${priceDisabled}>
                                </td>
                                <td class="text-center row-total">0.00</td>
                            </tr>
                        `;
                });

                $('#indentDetailsBody').html(rows);
                $('#itemsLockedNotice').toggle(itemsLocked);
                updateRunningTotal();
            }

            function updateRunningTotal() {
                let total = 0;
                $('#indentDetailsBody tr').each(function() {
                    let qty = parseFloat($(this).find('.cs-qty-input').val()) || 0;
                    let price = parseFloat($(this).find('.unit-price-input').val()) || 0;
                    let lineTotal = qty * price;
                    $(this).find('.row-total').text(lineTotal.toFixed(2));
                    total += lineTotal;
                });
                $('#entryRunningTotal').text(total.toFixed(2));
            }

            // While NOT locked, typing a Qty immediately enables/disables that row's price input
            $(document).on('input', '.cs-qty-input', function() {
                if (itemsLocked) return;

                let row = $(this).closest('tr');
                let val = parseFloat($(this).val());
                if (isNaN(val) || val < 0) val = 0;

                let item = requisitionItems.find(i => i.requisition_detail_id == row.data(
                    'requisition-detail-id'));

                // Cap against remaining_qty (required minus already covered by past CS), not required_qty
                if (val > item.remaining_qty) {
                    val = item.remaining_qty;
                    $(this).val(val);
                }

                row.find('.unit-price-input').prop('disabled', val <= 0);
                if (val <= 0) row.find('.unit-price-input').val('');

                updateRunningTotal();
            });

            $(document).on('input', '.unit-price-input', function() {
                updateRunningTotal();
            });

            renderIndentDetailsTable();

            /*
            |--------------------------------------------------------------------------
            | Add Supplier -> finalizes Qty (first time only), reads prices + Place/Time of
            | Supply for THIS supplier, adds a comparison column, then resets entry fields.
            |--------------------------------------------------------------------------
            */
            $('#addSupplierBtn').on('click', function() {

                let supplierId = $('#supplierSelect').val();
                let supplierName = $('#supplierSelect option:selected').text();

                if (!supplierId) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Select Supplier',
                        text: 'Please choose a supplier!'
                    });
                    return;
                }

                if (csGroups[supplierId]) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Already Added',
                        text: 'This supplier has already been added. Remove it first to re-enter.'
                    });
                    return;
                }

                let prices = {};
                let anySelected = false;
                let missingPrice = false;

                $('#indentDetailsBody tr').each(function() {

                    let row = $(this);
                    let requisitionDetailId = row.data('requisition-detail-id');
                    let qty = parseFloat(row.find('.cs-qty-input').val()) || 0;

                    if (qty <= 0) return;

                    anySelected = true;

                    // Finalize the qty into csQtyMap the FIRST time only.
                    csQtyMap[requisitionDetailId] = qty;

                    let price = parseFloat(row.find('.unit-price-input').val());
                    if (isNaN(price) || price <= 0) {
                        missingPrice = true;
                        return;
                    }
                    prices[requisitionDetailId] = price;
                });

                if (!anySelected) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Items',
                        text: 'Please set a CS Qty for at least one item!'
                    });
                    return;
                }

                if (missingPrice) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Missing Price',
                        text: 'Please enter a Unit Price for every selected item!'
                    });
                    return;
                }

                csGroups[supplierId] = {
                    supplier_id: supplierId,
                    supplier_name: supplierName,
                    quotation_number: $('#quotationNumber').val(),
                    quotation_date: $('#quotationDate').val(),
                    place_of_supply: $('#placeOfSupply').val(),
                    time_of_supply: $('#timeOfSupply').val(),
                    note: $('#groupNote').val(),
                    prices: prices,
                };

                // Lock qty from now on — future suppliers only fill in Unit Price.
                itemsLocked = true;

                // reset entry fields for the next supplier
                $('#supplierSelect').val('');
                $('#quotationNumber').val('');
                $('#quotationDate').val('');
                $('#placeOfSupply').val('{{ $requisition->place_of_supply }}');
                $('#timeOfSupply').val('');
                $('#groupNote').val('');

                renderIndentDetailsTable(); // qty inputs now disabled, price inputs cleared & re-enabled
                renderComparisonTable();
            });

            /*
            |--------------------------------------------------------------------------
            | STEP 3: Side-by-side comparison table (matches the reference image)
            |--------------------------------------------------------------------------
            */
            function selectedItemsList() {
                return requisitionItems.filter(i => csQtyMap[i.requisition_detail_id] > 0);
            }

            function renderComparisonTable() {

                let supplierIds = Object.keys(csGroups);

                if (!supplierIds.length) {
                    $('#csComparisonWrapper').html(
                        '<div class="text-muted text-center" id="csComparisonEmptyMsg">No supplier quotations added yet</div>'
                    );
                    return;
                }

                let selected = selectedItemsList();

                // ---- header rows (2 rows: supplier name spanning 2 cols, then Unit Price/Total) ----
                let headRow1 =
                    `<th rowspan="2" width="40">SL/No</th><th rowspan="2">Material Item</th><th rowspan="2" width="90">CS Qty</th>`;
                let headRow2 = '';

                supplierIds.forEach(sid => {
                    let g = csGroups[sid];
                    headRow1 += `
                        <th colspan="2" class="text-center supplier-col-header">
                            ${g.supplier_name}
                            <button type="button" class="supplier-remove-btn" data-supplier-id="${sid}" title="Remove Supplier">
                                <i class="bi bi-x-circle-fill"></i>
                            </button>
                        </th>
                    `;
                    headRow2 += `<th class="text-center">Unit Price</th><th class="text-center">Total</th>`;
                });

                // ---- body rows: one per selected item ----
                let bodyRows = '';
                let sl = 1;

                selected.forEach(item => {

                    let qty = csQtyMap[item.requisition_detail_id];

                    bodyRows += `<tr><td class="text-center">${sl++}</td>
                        <td><strong>${item.name}</strong><br><span class="product-code-sub">${item.specification}</span></td>
                        <td class="text-center">${qty}</td>`;

                    supplierIds.forEach(sid => {
                        let price = csGroups[sid].prices[item.requisition_detail_id] || 0;
                        let total = price * qty;
                        bodyRows +=
                            `<td class="text-center">${price.toFixed(2)}</td><td class="text-center">${total.toFixed(2)}</td>`;
                    });

                    bodyRows += `</tr>`;
                });

                // ---- footer rows: Total Amount / Net Amount / Place & Time of Supply / Quotation info / Special Note ----
                function footerRow(label, cellFn) {
                    let row = `<tr><td colspan="3" class="cs-footer-label">${label}</td>`;
                    supplierIds.forEach(sid => {
                        row += `<td colspan="2" class="text-center">${cellFn(csGroups[sid], sid)}</td>`;
                    });
                    return row + `</tr>`;
                }

                function groupTotal(g) {
                    return selected.reduce((sum, item) => sum + (g.prices[item.requisition_detail_id] || 0) *
                        csQtyMap[item.requisition_detail_id], 0);
                }

                let totalRow = footerRow('Total Amount', g => groupTotal(g).toFixed(2));
                let netRow = footerRow('Net Amount', g => groupTotal(g).toFixed(2));
                let placeRow = footerRow('Place of Supply', g => g.place_of_supply || '-');
                let timeRow = footerRow('Time of Supply', g => g.time_of_supply || '-');
                let quotationRow = footerRow('Supplier Quotation Number & Quotation Date',
                    g => [g.quotation_number, g.quotation_date].filter(Boolean).join(' &middot; ') || '-');
                let noteRow = footerRow('Special Note', g => g.note || '-');

                let html = `
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle" id="csComparisonTable">
                            <thead>
                                <tr>${headRow1}</tr>
                                <tr>${headRow2}</tr>
                            </thead>
                            <tbody>${bodyRows}</tbody>
                            <tfoot>
                                ${totalRow}
                                ${netRow}
                                ${placeRow}
                                ${timeRow}
                                ${quotationRow}
                                ${noteRow}
                            </tfoot>
                        </table>
                    </div>
                `;

                $('#csComparisonWrapper').html(html);
            }

            $(document).on('click', '.supplier-remove-btn', function() {

                let supplierId = $(this).data('supplier-id');
                delete csGroups[supplierId];

                // Unlock the item list again if no suppliers remain.
                if (Object.keys(csGroups).length === 0) {
                    itemsLocked = false;
                }

                renderIndentDetailsTable();
                renderComparisonTable();
            });

            /*
            |--------------------------------------------------------------------------
            | Submit CS
            |--------------------------------------------------------------------------
            */
            $('#csForm').on('submit', function(e) {

                e.preventDefault();

                let selected = selectedItemsList();
                let supplierIds = Object.keys(csGroups);

                if (!selected.length) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Items',
                        text: 'Please set a CS Qty for at least one item!'
                    });
                    return;
                }

                if (!supplierIds.length) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Suppliers',
                        text: 'Please add at least one supplier quotation!'
                    });
                    return;
                }

                let itemsPayload = selected.map(item => ({
                    requisition_detail_id: item.requisition_detail_id,
                    cs_qty: csQtyMap[item.requisition_detail_id],
                }));

                let groupsPayload = supplierIds.map(sid => {
                    let g = csGroups[sid];
                    return {
                        supplier_id: g.supplier_id,
                        quotation_number: g.quotation_number,
                        quotation_date: g.quotation_date,
                        place_of_supply: g.place_of_supply,
                        time_of_supply: g.time_of_supply,
                        note: g.note,
                        quotes: Object.keys(g.prices).map(reqId => ({
                            requisition_detail_id: reqId,
                            unit_price: g.prices[reqId],
                        })),
                    };
                });

                showLoading();

                let data = {
                    _token: $('input[name="_token"]').val(),
                    cs_date: $('#csDate').val(),
                    remarks: $('textarea[name="remarks"]').val(),
                    items: JSON.stringify(itemsPayload),
                    groups: JSON.stringify(groupsPayload),
                };

                $.ajax({
                    url: "{{ route('requisition.generate-cs.store', $requisition->id) }}",
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
