@extends('layouts.backend')

@section('title')
    Create PO - {{ $comparativeStatement->cs_no }}
@endsection

@section('content')
    <div class="app-toolbar py-3 py-lg-6">
        <div class="app-container container-fluid">
            <div class="admin-page-header">
                <div class="admin-page-header-title">
                    <span class="icon-box"><i class="bi bi-file-earmark-plus"></i></span>
                    <h1>Create Purchase Order</h1>
                </div>
                <a href="{{ route('comparative-statement.index') }}" class="btn-admin-secondary">
                    <i class="bi bi-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
    </div>

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">

            <form id="poForm">
                @csrf

                <!-- PO / CS / Requisition Info -->
                <div class="admin-card mb-4">
                    <div class="admin-card-header">
                        <h5><i class="bi bi-info-circle" style="color:#4361ee;"></i> PO Info</h5>
                    </div>
                    <div style="padding:24px;">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">PO Create By</label>
                                <input type="text" class="form-control" value="{{ Auth::user()->name }}" readonly>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Requisition No</label>
                                <input type="text" class="form-control"
                                    value="{{ $comparativeStatement->requisition->requisition_no }}" readonly>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">CS Reference No</label>
                                <input type="text" class="form-control" value="{{ $comparativeStatement->cs_no }}"
                                    readonly>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Date of PO <span class="text-danger">*</span></label>
                                <input type="date" name="po_date" id="poDate" class="form-control"
                                    value="{{ date('Y-m-d') }}" required>
                                <div class="invalid-feedback po_date-error"></div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Place of Supply</label>
                                <input type="text" name="place_of_supply" id="placeOfSupply" class="form-control"
                                    placeholder="Select a quotation first">
                                <div class="invalid-feedback place_of_supply-error d-block text-danger"></div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Time of Supply</label>
                                <input type="date" name="time_of_supply" id="timeOfSupply" class="form-control">
                                <div class="invalid-feedback time_of_supply-error d-block text-danger"></div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Contact Person</label>
                                <input type="text" name="contact_person" id="contactPerson" class="form-control"
                                    placeholder="Contact Person">
                                <div class="invalid-feedback contact_person-error d-block text-danger"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CS Details: side-by-side comparison, radio select per supplier -->
                <div class="admin-card mb-4">
                    <div class="admin-card-header">
                        <h5><i class="bi bi-list-check" style="color:#4361ee;"></i> CS Details</h5>
                    </div>
                    <div style="padding:24px;">

                        <div class="alert alert-info py-2 px-3 mb-3">
                            <i class="bi bi-info-circle-fill"></i>
                            Select one supplier quotation below to generate the Purchase Order.
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle" id="csComparisonTable">
                                <thead>
                                    <tr>
                                        <th rowspan="2" width="40">SL/No</th>
                                        <th rowspan="2">Material Item</th>
                                        <th rowspan="2" width="90">CS Qty</th>
                                        @foreach ($comparativeStatement->details as $detail)
                                            <th colspan="2" class="text-center supplier-col-header">
                                                <div
                                                    class="form-check d-flex align-items-center justify-content-center gap-2 mb-1">
                                                    <input class="form-check-input cs-detail-radio" type="radio"
                                                        name="cs_details_id" value="{{ $detail->id }}"
                                                        id="csDetail{{ $detail->id }}"
                                                        data-place="{{ $detail->place_of_supply }}"
                                                        data-time="{{ $detail->time_of_supply ? \Carbon\Carbon::parse($detail->time_of_supply)->format('Y-m-d') : '' }}"
                                                        style="cursor:pointer;">
                                                    <label class="form-check-label mb-0" style="cursor:pointer;"
                                                        for="csDetail{{ $detail->id }}">
                                                        {{ $detail->supplier->name ?? '-' }}
                                                    </label>
                                                </div>
                                            </th>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        @foreach ($comparativeStatement->details as $detail)
                                            <th class="text-center">Unit Price</th>
                                            <th class="text-center">Total</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        // Union of requisition_detail_ids across all suppliers (order preserved)
                                        $refItems = collect();
                                        foreach ($comparativeStatement->details as $detail) {
                                            foreach ($detail->items as $item) {
                                                $refItems->put($item->requisition_detail_id, $item);
                                            }
                                        }
                                        $refItems = $refItems->values();
                                    @endphp

                                    @foreach ($refItems as $sl => $refItem)
                                        <tr>
                                            <td class="text-center">{{ $sl + 1 }}</td>
                                            <td>
                                                <strong>{{ $refItem->product->name ?? 'Unknown Product' }}</strong>
                                                <span class="product-code-sub">
                                                    {{ $refItem->product->product_code ?? '-' }}
                                                </span>
                                            </td>
                                            <td class="text-center">{{ (float) $refItem->cs_qty }}</td>

                                            @foreach ($comparativeStatement->details as $detail)
                                                @php
                                                    $item = $detail->items->firstWhere(
                                                        'requisition_detail_id',
                                                        $refItem->requisition_detail_id,
                                                    );
                                                @endphp
                                                <td class="text-center">
                                                    {{ $item ? number_format($item->unit_price, 2) : '-' }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $item ? number_format($item->total, 2) : '-' }}
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="cs-footer-label">Total Amount</td>
                                        @foreach ($comparativeStatement->details as $detail)
                                            <td colspan="2" class="text-center">
                                                {{ number_format($detail->total_amount, 2) }}
                                            </td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="cs-footer-label">Net Amount</td>
                                        @foreach ($comparativeStatement->details as $detail)
                                            <td colspan="2" class="text-center">
                                                {{ number_format($detail->total_amount, 2) }}
                                            </td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="cs-footer-label">Place of Supply</td>
                                        @foreach ($comparativeStatement->details as $detail)
                                            <td colspan="2" class="text-center">
                                                {{ $detail->place_of_supply ?: '-' }}
                                            </td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="cs-footer-label">Time of Supply</td>
                                        @foreach ($comparativeStatement->details as $detail)
                                            <td colspan="2" class="text-center">
                                                {{ $detail->time_of_supply ? \Carbon\Carbon::parse($detail->time_of_supply)->format('d-m-Y') : '-' }}
                                            </td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="cs-footer-label">Supplier Quotation Number & Date</td>
                                        @foreach ($comparativeStatement->details as $detail)
                                            <td colspan="2" class="text-center">
                                                {{ collect([$detail->quotation_number, $detail->quotation_date ? \Carbon\Carbon::parse($detail->quotation_date)->format('d-m-Y') : null])->filter()->implode(' · ') ?:'-' }}
                                            </td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="cs-footer-label">Special Note</td>
                                        @foreach ($comparativeStatement->details as $detail)
                                            <td colspan="2" class="text-center">{{ $detail->note ?: '-' }}</td>
                                        @endforeach
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="invalid-feedback cs_details_id-error d-block text-danger"></div>

                        <div class="mb-3 mt-4">
                            <label class="form-label fw-bold">Remarks</label>
                            <textarea name="remarks" id="poRemarks" class="form-control" rows="3" placeholder="Input your remarks"></textarea>
                            <div class="invalid-feedback remarks-error"></div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mb-5">
                    <a href="{{ route('comparative-statement.index') }}" class="btn"
                        style="border:1px solid #dfe2e8;color:#4a4a5a;border-radius:8px;padding:8px 18px;">
                        <i class="bi bi-x-lg me-1"></i> Cancel
                    </a>
                    <button type="submit" class="btn submit-btn"
                        style="background:#4361ee;color:#fff;border-radius:8px;padding:8px 20px;font-weight:600;">
                        <i class="bi bi-check-lg me-1"></i> Create PO
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

        .cs-footer-label {
            text-align: right;
            font-weight: 700;
            background: #f8f9fa;
        }

        .cs-detail-radio:checked~label,
        td.supplier-selected {
            background: #d1fae5 !important;
        }
    </style>

    <script>
        $(document).on('change', '.cs-detail-radio', function() {
            let place = $(this).data('place');
            let time = $(this).data('time');

            $('#placeOfSupply').val(place || '');
            $('#timeOfSupply').val(time || '');

            $('.cs_details_id-error').text('').hide();
            highlightSelectedSupplier();
        });
        $(document).ready(function() {


            // Highlight selected supplier's column pair (visual aid)
            function highlightSelectedSupplier() {
                let selectedId = $('input[name="cs_details_id"]:checked').val();

                $('#csComparisonTable thead th.supplier-col-header').removeClass('bg-success bg-opacity-25');

                if (selectedId) {
                    $('#csDetail' + selectedId).closest('th').addClass('bg-success bg-opacity-25');
                }
            }

            // Auto-fill Place/Time of Supply from the selected quotation
            $(document).on('change', '.cs-detail-radio', function() {
                let place = $(this).data('place');
                let time = $(this).data('time');

                if (place) $('#placeOfSupply').val(place);
                if (time) $('#timeOfSupply').val(time);

                $('.cs_details_id-error').text('').hide();
                highlightSelectedSupplier();
            });

            /*
            |--------------------------------------------------------------------------
            | Submit PO
            |--------------------------------------------------------------------------
            */
            $('#poForm').on('submit', function(e) {
                e.preventDefault();

                let csDetailsId = $('input[name="cs_details_id"]:checked').val();

                if (!csDetailsId) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Select a Quotation',
                        text: 'Please select one supplier quotation to proceed!'
                    });
                    return;
                }

                let data = {
                    _token: $('input[name="_token"]').val(),
                    cs_details_id: csDetailsId,
                    po_date: $('#poDate').val(),
                    place_of_supply: $('#placeOfSupply').val(),
                    time_of_supply: $('#timeOfSupply').val(),
                    contact_person: $('#contactPerson').val(),
                    remarks: $('#poRemarks').val(),
                };

                $('.invalid-feedback').text('').hide();

                showLoading();

                $.ajax({
                    url: "{{ route('comparative-statement.store-po', $comparativeStatement->id) }}",
                    type: 'POST',
                    data: data,
                    success: function(response) {
                        hideLoading();
                        if (response.success) {
                            window.location.href =
                                "{{ route('comparative-statement.index') }}" +
                                "?added-successfully=" + encodeURIComponent(response.message);
                        } else {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Notice',
                                text: response.message || 'Something went wrong!'
                            });
                        }
                    },
                    error: function(xhr) {
                        hideLoading();

                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            let errors = xhr.responseJSON.errors;

                            if (errors.cs_details_id) $('.cs_details_id-error').text(errors
                                .cs_details_id[0]).show();
                            if (errors.po_date) $('.po_date-error').text(errors.po_date[0])
                                .show();
                            if (errors.place_of_supply) $('.place_of_supply-error').text(errors
                                .place_of_supply[0]).show();
                            if (errors.time_of_supply) $('.time_of_supply-error').text(errors
                                .time_of_supply[0]).show();
                            if (errors.contact_person) $('.contact_person-error').text(errors
                                .contact_person[0]).show();
                            if (errors.remarks) $('.remarks-error').text(errors.remarks[0])
                                .show();

                        } else if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON
                            .message) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Notice',
                                text: xhr.responseJSON.message
                            });
                        } else if (xhr.status === 403) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Not Allowed',
                                text: xhr.responseJSON?.message ||
                                    'Comparative Statement must be approved by MD to create PO.'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: xhr.responseJSON?.message ||
                                    'Something went wrong!'
                            });
                        }
                    },
                    complete: function() {
                        hideLoading(); // fail-safe, jate kono case-e loading atke na thake
                    }
                });
            });
        });
    </script>
@endsection
