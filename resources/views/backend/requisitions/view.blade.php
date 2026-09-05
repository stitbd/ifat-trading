{{--
    Requisition Details - Modal (Category-wise grouped, matches actual schema)
    -------------------------------------------------------------
    requisition_details columns:
        physical_stock, in_transit_stock, lc_pending_stock,
        pi_stock, sale_one_stock, sale_two_stock, sale_three_stock,
        required_stock, note

    Expects `$data` = single Requisition model, eager-loaded with
    `wing`, `warehouse`, `details.product.category`,
    `details.product.brand`, `details.product.productType`,
    `details.product.productSize`, `createdBy`.
--}}
@php
    use Carbon\Carbon;

    $company = \App\Models\Application::first();
    $companyLogo = $company ? $company->logo : '';
    $companyName = $company ? $company->company_name : '';
    $companyAddress = $company ? $company->address : '';
    $companyPhone = $company ? $company->phone : '';
    $companyEmail = $company ? $company->company_email : '';

    // Group details by category name
    $groupedDetails = $data->details->groupBy(function ($detail) {
        return $detail->product?->category?->name ?? 'Uncategorized';
    });

    $grandTotal = [
        'physical_stock' => $data->details->sum('physical_stock'),
        'in_transit_stock' => $data->details->sum('in_transit_stock'),
        'lc_pending_stock' => $data->details->sum('lc_pending_stock'),
        'pi_stock' => $data->details->sum('pi_stock'),
        'sale_one_stock' => $data->details->sum('sale_one_stock'),
        'sale_two_stock' => $data->details->sum('sale_two_stock'),
        'sale_three_stock' => $data->details->sum('sale_three_stock'),
        'required_stock' => $data->details->sum('required_stock'),
    ];

    /*
                |--------------------------------------------------------------------------
                | Dynamic date-based column labels — calculated from the Requisition Date
                | itself, the same rules used on the Create Requisition form:
                |   - AS ON        = the exact requisition date
                |   - PI            = same month/year as the requisition date
                |   - Sale 1 / 2 / 3 = the 3 months before the PI month (oldest -> newest)
                |   - Requirement    = FOR {month}'{year} of the requisition date
    |--------------------------------------------------------------------------
    */
$reqDate = $data->date ? Carbon::parse($data->date) : Carbon::now();

$asOnLabel = 'AS ON ' . $reqDate->day . '.' . $reqDate->month . '.' . $reqDate->year;
$piLabel = $reqDate->format('F') . "'" . $reqDate->year;
$requirementLabel = 'FOR ' . strtoupper($reqDate->format('F')) . "'" . $reqDate->year;

$formatSaleRange = function (Carbon $monthDate) {
    $start = $monthDate->copy()->startOfMonth();
    $end = $monthDate->copy()->endOfMonth();

    return $start->format('jS') .
        ' ' .
        $start->format('M') .
        "'" .
        $start->format('y') .
        ' to ' .
        $end->format('jS') .
        ' ' .
        $end->format('M') .
        "'" .
        $end->format('y');
    };

    $sale3Month = $reqDate->copy()->subMonthsNoOverflow(1);
    $sale2Month = $reqDate->copy()->subMonthsNoOverflow(2);
    $sale1Month = $reqDate->copy()->subMonthsNoOverflow(3);

    $saleOneLabel = $formatSaleRange($sale1Month);
    $saleTwoLabel = $formatSaleRange($sale2Month);
    $saleThreeLabel = $formatSaleRange($sale3Month);
@endphp

<div class="modal-header align-items-start" style="background:#fff; border-bottom:2px solid #14532d; padding:22px 24px;">

    <div class="w-100 text-center">

        @if ($companyLogo)
            <img src="{{ asset('image/application/' . $companyLogo) }}" alt="{{ $companyName }}"
                style="max-height:55px; margin-bottom:6px;">
        @endif

        <div style="color:#14532d; font-weight:700; font-size:20px; letter-spacing:1px;">
            {{ $companyName }}
        </div>
        <div style="color:#555; font-size:11px; line-height:1.5; margin-top:2px;">
            Address: {{ $companyAddress }} &nbsp;|&nbsp; Phone: {{ $companyPhone }} &nbsp;|&nbsp; {{ $companyEmail }}
        </div>
        <div style="color:#1e1e2d; font-weight:700; font-size:14px; text-decoration:underline; margin-top:10px;">
            REQUISITION FORM
        </div>
    </div>

    <button type="button" class="btn-close" data-bs-dismiss="modal"
        style="position:absolute; top:16px; right:16px;"></button>

</div>


<div class="modal-body" style="padding:24px; background:#fbfbfd;">

    {{-- ================= TOP INFO ================= --}}
    <div class="p-4 mb-3" style="background:#fff; border:1px solid #eef0f2; border-radius:10px;">

        <div class="row">

            <div class="col-md-6 mb-3">
                <div class="view-label">Requisition By</div>
                <div class="view-value">{{ $data->createdBy->name ?? '-' }}</div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="view-label">Requisition No</div>
                <div class="view-value">{{ $data->requisition_no ?? '-' }}</div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="view-label">Type of Procurement</div>
                <div class="view-value">
                    {{ $data->requisition_type === 'import' ? 'Import' : 'Local Purchase' }}
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="view-label">Requisition Date</div>
                <div class="view-value">
                    {{ $data->date ? \Carbon\Carbon::parse($data->date)->format('Y-m-d') : '-' }}
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="view-label">Wing</div>
                <div class="view-value">{{ $data->wing?->name ?? '-' }}</div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="view-label">Warehouse</div>
                <div class="view-value">{{ $data->warehouse?->name ?? '-' }}</div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="view-label">Contact Person & Number</div>
                <div class="view-value">{{ $data->contact_person_info ?? '-' }}</div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="view-label">Place of Supply / Delivery Location</div>
                <div class="view-value">{{ $data->place_of_supply ?? '-' }}</div>
            </div>

        </div>

    </div>


    {{-- ================= ITEMS TABLE (Category-wise grouped, actual schema) ================= --}}
    <div class="p-4 mb-3" style="background:#fff; border:1px solid #eef0f2; border-radius:10px;">

        <h6 class="fw-bold mb-3" style="color:#1e1e2d;">
            <i class="bi bi-box-seam me-2" style="color:#14532d;"></i>
            REQUIREMENT - {{ $requirementLabel }}
        </h6>

        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0" style="border-color:#333;">
                <thead style="background:#f3f3f3;">
                    <tr class="text-center">
                        <th style="width:50px;">SL</th>
                        <th>Size</th>
                        <th style="width:110px;">Physical Stock<br><small
                                class="text-muted fw-normal">{{ $asOnLabel }}</small></th>
                        <th style="width:110px;">In Transit<br><small
                                class="text-muted fw-normal">{{ $asOnLabel }}</small></th>
                        <th style="width:110px;">LC Pending<br><small
                                class="text-muted fw-normal">{{ $asOnLabel }}</small></th>
                        <th style="width:100px;">PI<br><small class="text-muted fw-normal">{{ $piLabel }}</small>
                        </th>
                        <th style="width:140px;">Sale 1<br><small
                                class="text-muted fw-normal">{{ $saleOneLabel }}</small></th>
                        <th style="width:140px;">Sale 2<br><small
                                class="text-muted fw-normal">{{ $saleTwoLabel }}</small></th>
                        <th style="width:140px;">Sale 3<br><small
                                class="text-muted fw-normal">{{ $saleThreeLabel }}</small></th>
                        <th style="width:110px;">Requirement<br><small
                                class="text-muted fw-normal">{{ $requirementLabel }}</small></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($groupedDetails as $categoryName => $categoryDetails)

                        {{-- Category Header Row --}}
                        <tr style="background:#eef2ff;">
                            <td colspan="10" style="font-weight:700; color:#14532d;">
                                {{ $categoryName }}
                            </td>
                        </tr>

                        @php
                            $subTotal = [
                                'physical_stock' => $categoryDetails->sum('physical_stock'),
                                'in_transit_stock' => $categoryDetails->sum('in_transit_stock'),
                                'lc_pending_stock' => $categoryDetails->sum('lc_pending_stock'),
                                'pi_stock' => $categoryDetails->sum('pi_stock'),
                                'sale_one_stock' => $categoryDetails->sum('sale_one_stock'),
                                'sale_two_stock' => $categoryDetails->sum('sale_two_stock'),
                                'sale_three_stock' => $categoryDetails->sum('sale_three_stock'),
                                'required_stock' => $categoryDetails->sum('required_stock'),
                            ];
                        @endphp

                        @foreach ($categoryDetails as $index => $detail)
                            <tr>
                                <td class="text-center">
                                    <span class="serial-badge">{{ $index + 1 }}</span>
                                </td>
                                <td>{{ $detail->product?->productSize?->name ?? '-' }}</td>
                                <td class="text-center">{{ number_format($detail->physical_stock, 0) }}</td>
                                <td class="text-center">{{ number_format($detail->in_transit_stock, 0) }}</td>
                                <td class="text-center">{{ number_format($detail->lc_pending_stock, 0) }}</td>
                                <td class="text-center">{{ number_format($detail->pi_stock, 0) }}</td>
                                <td class="text-center">{{ number_format($detail->sale_one_stock, 0) }}</td>
                                <td class="text-center">{{ number_format($detail->sale_two_stock, 0) }}</td>
                                <td class="text-center">{{ number_format($detail->sale_three_stock, 0) }}</td>
                                <td class="text-center">
                                    <span class="quantity-badge">{{ number_format($detail->required_stock, 0) }}</span>
                                </td>
                            </tr>
                        @endforeach

                        {{-- Sub Total Row --}}
                        <tr style="background:#f8f9fa; font-weight:600;">
                            <td colspan="2" class="text-end">Sub Total</td>
                            <td class="text-center">{{ number_format($subTotal['physical_stock'], 0) }}</td>
                            <td class="text-center">{{ number_format($subTotal['in_transit_stock'], 0) }}</td>
                            <td class="text-center">{{ number_format($subTotal['lc_pending_stock'], 0) }}</td>
                            <td class="text-center">{{ number_format($subTotal['pi_stock'], 0) }}</td>
                            <td class="text-center">{{ number_format($subTotal['sale_one_stock'], 0) }}</td>
                            <td class="text-center">{{ number_format($subTotal['sale_two_stock'], 0) }}</td>
                            <td class="text-center">{{ number_format($subTotal['sale_three_stock'], 0) }}</td>
                            <td class="text-center">{{ number_format($subTotal['required_stock'], 0) }}</td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted py-4">
                                <i class="bi bi-box-seam" style="font-size:25px;"></i>
                                <div class="mt-2">No products found.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>

                @if ($data->details->count())
                    <tfoot>
                        <tr style="background:#14532d; color:#fff; font-weight:700;">
                            <td colspan="2" class="text-end">Grand Total</td>
                            <td class="text-center">{{ number_format($grandTotal['physical_stock'], 0) }}</td>
                            <td class="text-center">{{ number_format($grandTotal['in_transit_stock'], 0) }}</td>
                            <td class="text-center">{{ number_format($grandTotal['lc_pending_stock'], 0) }}</td>
                            <td class="text-center">{{ number_format($grandTotal['pi_stock'], 0) }}</td>
                            <td class="text-center">{{ number_format($grandTotal['sale_one_stock'], 0) }}</td>
                            <td class="text-center">{{ number_format($grandTotal['sale_two_stock'], 0) }}</td>
                            <td class="text-center">{{ number_format($grandTotal['sale_three_stock'], 0) }}</td>
                            <td class="text-center">{{ number_format($grandTotal['required_stock'], 0) }}</td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>

        @if ($data->note)
            <div class="view-note mt-3"><strong>Note:</strong> {{ $data->note }}</div>
        @endif
    </div>

    <div class="text-center" style="font-size:11px; color:#444; margin-top:20px;">
        This is a system generated Requisition. No signature required.
    </div>

</div>


<div class="modal-footer" style="background:#fff; border-top:1px solid #eef0f2; padding:16px 24px;">

    <button type="button" class="btn" data-bs-dismiss="modal"
        style="border:1px solid #dfe2e8; color:#4a4a5a; border-radius:8px; padding:8px 18px;">
        <i class="bi bi-x-lg me-1"></i> Close
    </button>

</div>


<style>
    .view-label {
        color: #8a8a9a;
        font-size: 12px;
        margin-bottom: 4px;
    }

    .view-value {
        color: #1e1e2d;
        font-size: 14px;
        font-weight: 600;
    }

    .view-note {
        background: #f8f9fa;
        border: 1px solid #eef0f2;
        border-radius: 8px;
        padding: 12px 15px;
        color: #555;
        font-size: 14px;
        line-height: 1.6;
    }

    .product-code-sub {
        display: block;
        font-size: 12px;
        color: #6c757d;
    }

    .serial-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 30px;
        height: 30px;
        background: #eef2ff;
        color: #14532d;
        border-radius: 6px;
        font-weight: 600;
        font-size: 13px;
    }

    .quantity-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 45px;
        padding: 6px 12px;
        background: #eef2ff;
        color: #14532d;
        border-radius: 6px;
        font-weight: 600;
        font-size: 13px;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-pill i {
        font-size: 8px;
    }

    .status-active {
        background: #e6f4ea;
        color: #1e7e34;
    }

    .status-inactive {
        background: #f1f2f6;
        color: #6c757d;
    }

    .status-warning {
        background: #fdecea;
        color: #c0392b;
    }
</style>
