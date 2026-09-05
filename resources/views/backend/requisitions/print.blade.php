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
        | Dynamic date-based column labels — same rules as Create/View:
        |   - AS ON          = the exact requisition date
        |   - PI              = same month/year as the requisition date
        |   - Sale 1 / 2 / 3  = the 3 months before the PI month (oldest -> newest)
        |   - Requirement      = FOR {month}'{year} of the requisition date
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
<!DOCTYPE html>
<html>

<head>
    <title>Requisition {{ $data->requisition_no ?? '' }}</title>
    <meta charset="utf-8">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #000;
            padding: 25px 35px;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .header img {
            max-height: 55px;
            margin-bottom: 4px;
        }

        .company-name {
            font-weight: 700;
            font-size: 20px;
            letter-spacing: 1px;
            margin: 2px 0;
        }

        .company-meta {
            font-size: 11px;
            color: #222;
            margin-bottom: 6px;
        }

        .form-title {
            font-weight: 700;
            font-size: 14px;
            text-decoration: underline;
            margin: 6px 0 18px;
        }

        .top-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 14px;
        }

        .top-info .left,
        .top-info .right {
            font-size: 13px;
            line-height: 1.9;
        }

        .top-info .right {
            text-align: right;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }

        table,
        th,
        td {
            border: 1px solid #ccc;
        }

        th {
            background: #f3f3f3;
            font-size: 11px;
            padding: 6px;
            text-align: center;
        }

        th small {
            display: block;
            font-weight: normal;
            font-size: 9px;
            color: #555;
        }

        td {
            font-size: 11px;
            padding: 5px 7px;
            vertical-align: middle;
        }

        .product-code-sub {
            display: block;
            font-size: 9px;
            color: #666;
        }

        .category-row td {
            background: #eef2ff;
            font-weight: 700;
            color: #14532d;
            text-align: left;
            padding: 6px 8px;
        }

        .subtotal-row td {
            background: #f8f9fa;
            font-weight: 700;
        }

        .grandtotal-row td {
            background: #14532d;
            color: #fff;
            font-weight: 700;
        }

        .section-title {
            text-align: center;
            font-weight: 700;
            background: #f3f3f3;
            padding: 5px;
            border: 1px solid #ccc;
            border-bottom: none;
        }

        .footer-text {
            margin-top: 14px;
            line-height: 1.7;
            font-size: 13px;
        }

        .system-note {
            margin-top: 25px;
            text-align: center;
            font-size: 11px;
            border: 1px solid #ccc;
            padding: 6px;
        }

        .print-toolbar {
            text-align: right;
            margin-bottom: 15px;
        }

        .print-toolbar button {
            background: #4361ee;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 8px 18px;
            font-size: 13px;
            cursor: pointer;
        }

        @media print {
            .print-toolbar {
                display: none !important;
            }

            body {
                padding: 10px 20px;
            }
        }
    </style>
</head>

<body onload="window.print()">

    <div class="print-toolbar">
        <button onclick="window.print()">
            <i class="bi bi-printer-fill"></i> Print
        </button>
    </div>

    <div class="header">
        @if ($companyLogo)
            <img src="{{ asset('image/application/' . $companyLogo) }}" alt="{{ $companyName }}">
        @endif
        <div class="company-name">{{ $companyName }}</div>
        <div class="company-meta">
            Address: {{ $companyAddress }}<br>
            Phone : {{ $companyPhone }}<br>
            E-mail : {{ $companyEmail }}
        </div>
        <div class="form-title">REQUISITION FORM</div>
    </div>

    <div class="top-info">
        <div class="left">
            Wing : {{ $data->wing?->name ?? '-' }}<br>
            Requisition Type : {{ $data->requisition_type === 'import' ? 'Import' : 'Local Purchase' }}
        </div>
        <div class="right">
            Warehouse : {{ $data->warehouse?->name ?? '-' }}<br>
            Requisition No : {{ $data->requisition_no ?? '-' }}<br>
            Requisition Date : {{ $data->date ? \Carbon\Carbon::parse($data->date)->format('Y-m-d') : '-' }}<br>
            Requisition By : {{ $data->createdBy->name ?? '-' }}
        </div>
    </div>

    <div class="section-title">REQUIREMENT - {{ $requirementLabel }}</div>
    <table>
        <thead>
            <tr>
                <th style="width:35px;">SL</th>
                <th style="width:70px;">Size</th>
                <th style="width:80px;">Physical Stock<br><small>{{ $asOnLabel }}</small></th>
                <th style="width:80px;">In Transit<br><small>{{ $asOnLabel }}</small></th>
                <th style="width:80px;">LC Pending<br><small>{{ $asOnLabel }}</small></th>
                <th style="width:70px;">PI<br><small>{{ $piLabel }}</small></th>
                <th style="width:95px;">Sale 1<br><small>{{ $saleOneLabel }}</small></th>
                <th style="width:95px;">Sale 2<br><small>{{ $saleTwoLabel }}</small></th>
                <th style="width:95px;">Sale 3<br><small>{{ $saleThreeLabel }}</small></th>
                <th style="width:85px;">Requirement<br><small>{{ $requirementLabel }}</small></th>
            </tr>
        </thead>
        <tbody>
            @forelse($groupedDetails as $categoryName => $categoryDetails)

                <tr class="category-row">
                    <td colspan="10">{{ $categoryName }}</td>
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
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-center">{{ $detail->product?->productSize?->name ?? '-' }}</td>
                        <td class="text-center">{{ number_format($detail->physical_stock, 0) }}</td>
                        <td class="text-center">{{ number_format($detail->in_transit_stock, 0) }}</td>
                        <td class="text-center">{{ number_format($detail->lc_pending_stock, 0) }}</td>
                        <td class="text-center">{{ number_format($detail->pi_stock, 0) }}</td>
                        <td class="text-center">{{ number_format($detail->sale_one_stock, 0) }}</td>
                        <td class="text-center">{{ number_format($detail->sale_two_stock, 0) }}</td>
                        <td class="text-center">{{ number_format($detail->sale_three_stock, 0) }}</td>
                        <td class="text-center">{{ number_format($detail->required_stock, 0) }}</td>
                    </tr>
                @endforeach

                <tr class="subtotal-row">
                    <td colspan="2" style="text-align:right;">Sub Total</td>
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
                    <td colspan="10" class="text-center">No products found.</td>
                </tr>
            @endforelse
        </tbody>
        @if ($data->details->count())
            <tfoot>
                <tr class="grandtotal-row">
                    <td colspan="2" style="text-align:right;">Grand Total</td>
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

    <div class="footer-text">
        <strong>Contact Person :</strong> {{ $data->contact_person_info ?? '-' }}<br><br>
        <strong>Delivery Location :</strong> {{ $data->place_of_supply ?? '-' }}<br><br>
        {{ $data->note ?? '-' }}
    </div>

    <div class="system-note">
        This is a system generated Requisition. No signature required.
    </div>

    <script>
        window.onafterprint = function() {
            window.close();
        };
    </script>
</body>

</html>
