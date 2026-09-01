@php
    $company = \App\Models\Application::first();
    $companyLogo = $company ? $company->logo : '';
    $companyName = $company ? $company->company_name : '';
    $companyAddress = $company ? $company->address : '';
    $companyPhone = $company ? $company->phone : '';
    $companyEmail = $company ? $company->company_email : '';
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
            font-size: 13px;
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
            background: #ffffff;
            font-size: 12px;
            padding: 6px;
            text-align: center;
        }

        td {
            font-size: 12px;
            padding: 6px 8px;
            vertical-align: top;
        }

        .section-title {
            text-align: center;
            font-weight: 700;
            background: #ffffff;
            padding: 5px;
            border: 1px solid #ccc;
            border-bottom: none;
        }

        .spec-label {
            font-weight: 600;
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
        <div class="form-title">Requisition FORM</div>
    </div>

    <div class="top-info">
        <div class="left">
            Wing : {{ $data->wing?->name ?? '-' }}<br>
            Requsition Type : {{ $data->requisition_type === 'import' ? 'Import' : 'Local Purchase' }}
        </div>
        <div class="right">
            Warehouse : {{ $data->warehouse?->name ?? '-' }}<br>
            Requisition No : {{ $data->requisition_no ?? '-' }}<br>
            Requisition Date : {{ $data->date ? \Carbon\Carbon::parse($data->date)->format('Y-m-d') : '-' }}<br>
            Requisition By : {{ $data->createdBy->name ?? '-' }}
        </div>
    </div>

    <div class="section-title">Product Requsation Details</div>
    <table>
        <thead>
            <tr>
                <th style="width:40px;">SL.<br>No</th>
                <th style="width:90px;">Category</th>
                <th>Product</th>
                <th>Note</th>
                <th style="width:70px;">Qty</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data->details as $index => $detail)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $detail->product?->category?->name ?? '-' }}</td>
                    <td>
                        <strong>Name: </strong> {{ $detail->product?->name ?? 'Unknown Product' }}<br>
                        <strong>Brand: </strong> {{ $detail->product?->brand?->name ?? 'Unknown Product' }}<br>
                        <strong>Type: </strong> {{ $detail->product?->productType?->name ?? 'Unknown Product' }}<br>
                        <strong>Size: </strong> {{ $detail->product?->productSize?->name ?? 'Unknown Product' }}<br>

                    </td>
                    <td>
                        {{ $detail->note }}
                    </td>
                    <td class="text-center">{{ number_format($detail->quantity, 0) }} Pcs</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">No products found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer-text">
        <strong>Contact Person :</strong> {{ $data->contact_person_info ?? '-' }}<br><br>
        <strong>Delivery Location :</strong> {{ $data->place_of_supply ?? '-' }}<br><br>
        {{ $data->note ?? '-' }}
    </div>

    <div class="system-note">
        This is a system generated Indent. No signature required.
    </div>
    <script>
        window.onafterprint = function() {
            window.close(); // print dialog বন্ধ হলে ট্যাবটাও বন্ধ হয়ে যাবে
        };
    </script>
</body>

</html>
