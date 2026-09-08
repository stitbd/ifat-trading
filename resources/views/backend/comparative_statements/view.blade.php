@php
    // একই requisition_detail_id বিভিন্ন supplier-এর items-এ থাকতে পারে,
    // তাই সব detail এর items থেকে unique product/item list বানানো হচ্ছে (row হিসেবে ব্যবহারের জন্য)
    $allItems = $comparativeStatement->details
        ->flatMap(fn($detail) => $detail->items)
        ->unique('requisition_detail_id')
        ->values();
@endphp

<div class="modal-header" style="border-bottom:1px solid #eef0f2;">
    <h5 class="modal-title text-center w-100" style="text-decoration:underline;font-weight:700;">
        Comparative Statement
    </h5>
    <a href="{{ route('comparative-statement.print', $comparativeStatement->id) }}" target="_blank" class="btn btn-sm"
        title="Print">
        <i class="bi bi-printer-fill" style="font-size:18px;color:#4361ee;"></i>
    </a>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body" id="csPrintArea" style="padding:24px;">

    <div class="mb-1"><strong>Requsition No :</strong> {{ $comparativeStatement->requisition->requisition_no ?? '-' }}
    </div>
    <div class="mb-1"><strong>CS Reference No :</strong> {{ $comparativeStatement->cs_no }}</div>
    <div class="mb-3">
        <strong>Date of CS :</strong>
        {{ $comparativeStatement->cs_date ? \Carbon\Carbon::parse($comparativeStatement->cs_date)->format('d-M-Y') : '-' }}
    </div>

    <div class="table-responsive">
        <table class="table table-bordered align-middle" id="csViewTable" style="font-size:13px;">
            <thead>
                <tr class="text-center" style="background:#f8f9fa;">
                    <td colspan="{{ 3 + $comparativeStatement->details->count() * 2 }}" class="fw-bold">
                        CS Details
                    </td>
                </tr>
                <tr class="text-center">
                    <th rowspan="2" width="40">SL/No</th>
                    <th rowspan="2">Material Item</th>
                    <th rowspan="2" width="90">Required Qty</th>
                    @foreach ($comparativeStatement->details as $detail)
                        <th colspan="2" class="text-center">
                            {{ $detail->supplier->name ?? '-' }}
                        </th>
                    @endforeach
                </tr>
                <tr class="text-center">
                    @foreach ($comparativeStatement->details as $detail)
                        <th>Unit Price</th>
                        <th>Total</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse ($allItems as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $item->product->name ?? '-' }}</td>
                        <td class="text-center">
                            {{ $item->requisitionDetail?->required_stock ?? '-' }}
                        </td>
                        @foreach ($comparativeStatement->details as $detail)
                            @php
                                $matched = $detail->items->firstWhere(
                                    'requisition_detail_id',
                                    $item->requisition_detail_id,
                                );
                            @endphp
                            <td class="text-center">{{ $matched ? number_format($matched->unit_price, 2) : '-' }}</td>
                            <td class="text-center">{{ $matched ? number_format($matched->total, 2) : '-' }}</td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ 3 + $comparativeStatement->details->count() * 2 }}"
                            class="text-center text-muted">
                            No items found
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-end fw-bold" style="background:#f8f9fa;">Total Amount</td>
                    @foreach ($comparativeStatement->details as $detail)
                        <td colspan="2" class="text-center fw-bold">
                            {{ number_format($detail->total_amount, 2) }}
                        </td>
                    @endforeach
                </tr>
                <tr>
                    <td colspan="3" class="text-end fw-bold" style="background:#f8f9fa;">Time of Supply</td>
                    @foreach ($comparativeStatement->details as $detail)
                        <td colspan="2" class="text-center">
                            {{ $detail->time_of_supply ? \Carbon\Carbon::parse($detail->time_of_supply)->format('d-M-Y') : '-' }}
                        </td>
                    @endforeach
                </tr>
                <tr>
                    <td colspan="3" class="text-end fw-bold" style="background:#f8f9fa;">Supplier Quotation No / Date
                    </td>
                    @foreach ($comparativeStatement->details as $detail)
                        <td colspan="2" class="text-center">
                            {{ collect([
                                $detail->quotation_number,
                                $detail->quotation_date ? \Carbon\Carbon::parse($detail->quotation_date)->format('d-M-Y') : null,
                            ])->filter()->implode(' / ') ?:
                                '-' }}
                        </td>
                    @endforeach
                </tr>
                <tr>
                    <td colspan="3" class="text-end fw-bold" style="background:#f8f9fa;">Special Note</td>
                    @foreach ($comparativeStatement->details as $detail)
                        <td colspan="2" class="text-center">{{ $detail->note ?: '-' }}</td>
                    @endforeach
                </tr>
            </tfoot>
        </table>
    </div>

    @if ($comparativeStatement->remarks)
        <div class="mt-3">
            <strong>Remarks:</strong>
            <div>{{ $comparativeStatement->remarks }}</div>
        </div>
    @endif

    <div class="mt-3" style="font-size:13px;color:#6c757d;">
        Created By: {{ $comparativeStatement->createdBy->name ?? '-' }}
        &middot;
        {{ $comparativeStatement->created_at?->format('d-M-Y h:i A') }}
    </div>

</div>

<style>
    #csViewTable th,
    #csViewTable td {
        vertical-align: middle;
    }

    @media print {
        body * {
            visibility: hidden;
        }

        #csPrintArea,
        #csPrintArea * {
            visibility: visible;
        }

        #csPrintArea {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
        }
    }
</style>

<script>
    (function() {
        document.getElementById('csPrintBtn')?.addEventListener('click', function() {
            window.print();
        });
    })();
</script>
