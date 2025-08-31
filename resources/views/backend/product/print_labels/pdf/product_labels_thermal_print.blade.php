<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Thermal Label Print</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: sans-serif;
            margin: 0;
            padding: 0;
        }

        .label-box {
            width: 38mm;
            height: 25mm;
            padding: 0mm;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .label-box b,
        .label-box span {
            font-size: 11px;
            line-height: 1.2;
        }

        img {
            max-width: 100%;
            height: auto;
        }

        @media print {
            @page {
                size: 38mm 25mm;
                margin: 0;
            }

            body {
                margin: 0;
            }

            .label-box {
                page-break-after: always;
            }
        }
    </style>

    <script>
        function printDiv() {
            var printContents = document.getElementById('printableArea').innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }

        window.onload = printDiv;

        function redirectAfterPrint() {
            window.location.href = '{{ route("productLabelsprint.index") }}';
        }

        setTimeout(redirectAfterPrint, 1000);
    </script>
</head>

<body>
    @php
    $org = App\Models\OrgDetails::first();
    @endphp

    <div id="printableArea">
        @foreach($labelItems as $item)
        @for($i = 0; $i < $item['qty']; $i++)
            <div class="label-box">
            <span style="padding-bottom:2px; margin-top:3px;">
                <b style="display: block; text-align: center; font-size:15px;">{{ $org->org_name_en ?? 'Company Name' }}</b>
                <b style="display: block; text-align: center;">{{ $item['product'] }} ({{ $item['size_name'] }})</b>
                <div style="display: flex; justify-content: center;">
                    <b>{{ $item['barcode'] !== 'N/A' ? $item['barcode'] : 'No Barcode' }}</b>
                    <!-- <b>C: {{ $item['buying_price_code'] }}</b> -->
                </div>
            </span>
            @if($item['barcode'] !== 'N/A' && $item['barcode'])
            <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($item['barcode'], 'C128') }}"
                style="margin-top: 2px; margin-bottom:2px; margin-left:5px;margin-right:5px; padding: 0; max-width: 100%; height: 20px;" alt="barcode" />

            @else
            <span>[ No Barcode Available ]</span>
            @endif
            <div style="display: flex; justify-content: center; padding-top: 1px; padding-left:2px; padding-right:2px;">
                <!-- <b>{{ $item['barcode'] !== 'N/A' ? $item['barcode'] : 'No Barcode' }}</b> -->
                @if ($item['discounted_price'] > 0)
                <b>Tk. <span style="text-decoration: line-through;">{{ number_format($item['selling_price'], 2) }}</span> {{ number_format($item['discounted_price'], 2) }}</b>
                @else
                <b>Tk. {{ number_format($item['selling_price'], 2) }}</b>
                @endif
            </div>
    </div>
    @endfor
    @endforeach
    </div>
</body>

</html>