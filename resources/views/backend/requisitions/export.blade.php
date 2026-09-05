@php
    use Carbon\Carbon;

    $company = \App\Models\Application::first();
    $companyName = $company ? $company->company_name : '';
    $companyAddress = $company ? $company->address : '';
    $companyPhone = $company ? $company->phone : '';
    $companyEmail = $company ? $company->company_email : '';

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
                | Dynamic date-based column labels — same rules as the Print/PDF view:
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

    // Total columns: SL, Size, Physical Stock, In Transit, LC Pending, PI, Sale1, Sale2, Sale3, Requirement
    $totalCols = 10;
@endphp
<table>
    {{-- Company Header --}}
    <tr>
        <td colspan="{{ $totalCols }}" style="text-align:center; font-weight:bold; font-size:16px;">
            {{ $companyName }}
        </td>
    </tr>
    <tr>
        <td colspan="{{ $totalCols }}" style="text-align:center;">
            {{ $companyAddress }} | Phone: {{ $companyPhone }} | Email: {{ $companyEmail }}
        </td>
    </tr>
    <tr>
        <td colspan="{{ $totalCols }}" style="text-align:center; font-weight:bold; text-decoration:underline;">
            REQUISITION FORM
        </td>
    </tr>
    <tr>
        <td colspan="{{ $totalCols }}"></td>
    </tr>

    {{-- Requisition Meta Info --}}
    <tr>
        <td colspan="5"><strong>Requisition No:</strong> {{ $data->requisition_no ?? '-' }}</td>
        <td colspan="5"><strong>Wing:</strong> {{ $data->wing?->name ?? '-' }}</td>
    </tr>
    <tr>
        <td colspan="5">
            <strong>Type:</strong>
            {{ $data->requisition_type === 'import' ? 'Import' : 'Local Purchase' }}
        </td>
        <td colspan="5"><strong>Warehouse:</strong> {{ $data->warehouse?->name ?? '-' }}</td>
    </tr>
    <tr>
        <td colspan="5">
            <strong>Date:</strong>
            {{ $data->date ? \Carbon\Carbon::parse($data->date)->format('Y-m-d') : '-' }}
        </td>
        <td colspan="5"><strong>Requisition By:</strong> {{ $data->createdBy->name ?? '-' }}</td>
    </tr>
    <tr>
        <td colspan="{{ $totalCols }}"></td>
    </tr>

    {{-- Section title --}}
    <tr>
        <td colspan="{{ $totalCols }}" style="text-align:center; font-weight:bold; background-color:#F3F3F3;">
            REQUIREMENT - {{ $requirementLabel }}
        </td>
    </tr>

    {{-- Table Header with dynamic date sub-labels --}}
    <tr>
        <th>SL</th>
        <th>Size</th>
        <th>Physical Stock<br><small>{{ $asOnLabel }}</small></th>
        <th>In Transit<br><small>{{ $asOnLabel }}</small></th>
        <th>LC Pending<br><small>{{ $asOnLabel }}</small></th>
        <th>PI<br><small>{{ $piLabel }}</small></th>
        <th>Sale <br><small>{{ $saleOneLabel }}</small></th>
        <th>Sale <br><small>{{ $saleTwoLabel }}</small></th>
        <th>Sale <br><small>{{ $saleThreeLabel }}</small></th>
        <th>Requirement<br><small>{{ $requirementLabel }}</small></th>
    </tr>

    @forelse ($groupedDetails as $categoryName => $categoryDetails)
        {{-- Category header row --}}
        <tr>
            <td colspan="{{ $totalCols }}" style="font-weight:bold; background-color:#EEF2FF; color:#14532D;">
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
                <td>{{ $index + 1 }}</td>
                <td>{{ $detail->product?->productSize?->name ?? '-' }}</td>
                <td>{{ number_format($detail->physical_stock, 0) }}</td>
                <td>{{ number_format($detail->in_transit_stock, 0) }}</td>
                <td>{{ number_format($detail->lc_pending_stock, 0) }}</td>
                <td>{{ number_format($detail->pi_stock, 0) }}</td>
                <td>{{ number_format($detail->sale_one_stock, 0) }}</td>
                <td>{{ number_format($detail->sale_two_stock, 0) }}</td>
                <td>{{ number_format($detail->sale_three_stock, 0) }}</td>
                <td>{{ number_format($detail->required_stock, 0) }}</td>
            </tr>
        @endforeach

        {{-- Sub Total row --}}
        <tr style="font-weight:bold; background-color:#F8F9FA;">
            <td colspan="2" style="text-align:right;">Sub Total</td>
            <td>{{ number_format($subTotal['physical_stock'], 0) }}</td>
            <td>{{ number_format($subTotal['in_transit_stock'], 0) }}</td>
            <td>{{ number_format($subTotal['lc_pending_stock'], 0) }}</td>
            <td>{{ number_format($subTotal['pi_stock'], 0) }}</td>
            <td>{{ number_format($subTotal['sale_one_stock'], 0) }}</td>
            <td>{{ number_format($subTotal['sale_two_stock'], 0) }}</td>
            <td>{{ number_format($subTotal['sale_three_stock'], 0) }}</td>
            <td>{{ number_format($subTotal['required_stock'], 0) }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="{{ $totalCols }}" style="text-align:center;">No products found.</td>
        </tr>
    @endforelse

    @if ($data->details->count())
        {{-- Grand Total row --}}
        <tr style="font-weight:bold; background-color:#14532D; color:#FFFFFF;">
            <td colspan="2" style="text-align:right;">Grand Total</td>
            <td>{{ number_format($grandTotal['physical_stock'], 0) }}</td>
            <td>{{ number_format($grandTotal['in_transit_stock'], 0) }}</td>
            <td>{{ number_format($grandTotal['lc_pending_stock'], 0) }}</td>
            <td>{{ number_format($grandTotal['pi_stock'], 0) }}</td>
            <td>{{ number_format($grandTotal['sale_one_stock'], 0) }}</td>
            <td>{{ number_format($grandTotal['sale_two_stock'], 0) }}</td>
            <td>{{ number_format($grandTotal['sale_three_stock'], 0) }}</td>
            <td>{{ number_format($grandTotal['required_stock'], 0) }}</td>
        </tr>
    @endif

    <tr>
        <td colspan="{{ $totalCols }}"></td>
    </tr>
    <tr>
        <td colspan="{{ $totalCols }}"><strong>Contact Person:</strong> {{ $data->contact_person_info ?? '-' }}
        </td>
    </tr>
    <tr>
        <td colspan="{{ $totalCols }}"><strong>Delivery Location:</strong> {{ $data->place_of_supply ?? '-' }}</td>
    </tr>
    <tr>
        <td colspan="{{ $totalCols }}">{{ $data->note ?? '-' }}</td>
    </tr>
</table>
