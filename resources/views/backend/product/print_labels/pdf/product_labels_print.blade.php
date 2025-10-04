<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Product Labels Print</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Figtree', sans-serif;
        }

        .label-wrapper {
            display: flex;
            flex-direction: column;
            padding-left: 5mm;
        }

        .row-wrapper {
            display: flex;
            flex-wrap: nowrap;
            justify-content: flex-start;
            page-break-inside: avoid;
            break-inside: avoid;
            margin-bottom: 6mm;
            /* gap between rows */
        }

        .label-box {
            width: 38mm;
            height: 25mm;
            border: 1px solid #000;
            padding: 2mm;
            margin-right: 4mm;
            /* gap between columns */
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            page-break-inside: avoid;
            break-inside: avoid;
            overflow: hidden;
        }

        span,
        .label-box b {
            font-size: 9px;
            line-height: 1.1;
        }

        img {
            max-width: 100%;
            height: auto;
        }

        @media print {
            body {
                margin: 0;
            }

            @page {
                size: A4;
                margin: 5mm;
            }

            .label-box,
            .row-wrapper {
                page-break-inside: avoid;
                break-inside: avoid;
            }
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

    <script>
        function printDiv() {
            var printContents = document.getElementById('printableArea').innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }

        window.onload = printDiv;

        function performAction() {
            window.location.href = '{{ route("productLabelsprint.index") }}';
        }

        setTimeout(performAction, 100);
    </script>

    @php
    $org = App\Models\OrgDetails::first();
    @endphp

    <div class="row mx-auto" id="printableArea">
        <div class="col-md-12">
            <div class="label-wrapper">
                @foreach($labelItems as $item)
                @for($i = 0; $i < $item['qty']; $i++)
                    @if($i % 4==0)
                    <div class="row-wrapper">
                    @endif
                    <div class="label-box">
                        <span>
                            <b style="display: block; text-align: center; font-size:12px;">{{ $org->org_name_en ?? 'Company Name' }}</b>
                            <b>
                                {{ $item['product'] }}
                                @if($is_size_included=='1')
                                    ({{ $item['size_name'] }})
                                @endif
                            </b>
                            <div style="display: flex; justify-content: space-between;">
                                <b>Code: {{ $item['buying_price_code'] }}</b>
                                <b>BC: {{ $item['barcode'] !== 'N/A' ? $item['barcode'] : 'No Barcode' }}</b>
                            </div>
                        </span>

                        @if($item['barcode'] !== 'N/A' && $item['barcode'])
                        <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($item['barcode'], 'C128') }}" alt="barcode" />
                        @else
                        <span>[ No Barcode Available ]</span>
                        @endif

                        <div style="display: flex; justify-content: space-between;">
                            <b>Price: {{ number_format($item['selling_price'], 0) }}</b>
                            @if ($item['discounted_price'] >0)
                            <b>Off.Price: {{ number_format($item['discounted_price'], 0) }}</b>
                            @endif
                        </div>
                    </div>

                    @if(($i + 1) % 4 == 0 || $i + 1 == $item['qty'])
            </div> <!-- Close .row-wrapper -->
            @endif
            @endfor
            @endforeach
        </div>
    </div>
    </div>
</body>

</html>