<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Comparative Statement - {{ $comparativeStatement->cs_no }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f1f2f6;
            padding: 24px;
        }

        .print-sheet {
            max-width: 1100px;
            margin: 0 auto;
            background: #fff;
            border-radius: 8px;
            padding: 24px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .08);
        }

        .print-toolbar {
            max-width: 1100px;
            margin: 0 auto 12px auto;
            display: flex;
            justify-content: flex-end;
        }

        #csViewTable th,
        #csViewTable td {
            vertical-align: middle;
            font-size: 13px;
        }

        @media print {
            body {
                background: #fff;
                padding: 0;
            }

            .print-toolbar {
                display: none !important;
            }

            .print-sheet {
                box-shadow: none;
                border-radius: 0;
                max-width: 100%;
            }
        }
    </style>
</head>

<body>

    <div class="print-toolbar">
        <button type="button" class="btn btn-primary" id="csPrintBtn">
            <i class="bi bi-printer-fill"></i> Print
        </button>
    </div>

    <div class="print-sheet" id="csPrintArea">

        <h4 class="text-center fw-bold" style="text-decoration:underline;">Comparative Statement</h4>

        <div class="mb-1"><strong>Indent No :</strong> {{ $comparativeStatement->requisition->requisition_no ?? '-' }}
        </div>
        <div class="mb-1"><strong>CS Reference No :</strong> {{ $comparativeStatement->cs_no }}</div>
        <div class="mb-3">
            <strong>Date of CS :</strong>
            {{ $comparativeStatement->cs_date ? \Carbon\Carbon::parse($comparativeStatement->cs_date)->format('d-M-Y') : '-' }}
        </div>

        @php
            $allItems = $comparativeStatement->details
                ->flatMap(fn($detail) => $detail->items)
                ->unique('requisition_detail_id')
                ->values();
        @endphp

        <div class="table-responsive">
            <table class="table table-bordered align-middle" id="csViewTable">
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
                                <td class="text-center">{{ $matched ? number_format($matched->unit_price, 2) : '-' }}
                                </td>
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
                        <td colspan="3" class="text-end fw-bold" style="background:#f8f9fa;">Supplier Quotation No /
                            Date</td>
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

    <script>
        document.getElementById('csPrintBtn').addEventListener('click', function() {
            window.print();
        });
    </script>

</body>

</html>
