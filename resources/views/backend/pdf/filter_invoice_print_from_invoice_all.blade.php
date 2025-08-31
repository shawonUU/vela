<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title> Invoice/ Challan Report - Printed At: {{ now()->format('d-m-Y  h:i A') }} - Printed By: {{ Auth::user()->name }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Bootstrap Css -->
    <link href="{{ asset('backend/assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="d-flex flex-column min-vh-100" style="margin-left: 42px;">
    <script>
        function printDiv() {
            var printContents = document.getElementById('printableArea').innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
        window.onload = function() {
            printDiv();

            // Execute performAction after 500ms (0.5 seconds)
            setTimeout(performAction, 500);
        };
        function performAction() {
            window.location.href = '{{ route("invoice.all") }}';
        }
        
    </script>
    <div class="invoice-container flex-fill" id="printableArea">
        <style>
            .invoice-container {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                position: relative;
            }
            .invoice-watermark {
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                opacity: 0.05;
                z-index: -1;
                width: 400px;
                height: auto;
                filter: grayscale(100%);
                pointer-events: none;
                /* So it doesn’t interfere with clicks */
            }

            .invoice-header {
                text-align: center;
                border-bottom: 2px solid #000;
                padding-bottom: 1rem;
                margin-bottom: .5rem;
            }

            .company-logo {
                max-width: 400px;
                margin: 0;
            }

            .invoice-details {
                display: grid;
                color: #000;
                grid-template-columns: 1fr 1fr;
                gap: 1rem;
                margin: .5rem 0;
            }

            .invoice-table {
                width: 100%;
                border-collapse: collapse;
                margin: 1rem 0;
            }

            .invoice-table th {
                background-color: #f8f9fa;
                padding: 12px;
                font-size: 13px;
                border-bottom: 2px solid #000;
            }

            .invoice-table td {
                padding: 12px;
                font-size: 12px;
                color: #000;
                border-bottom: 1px solid #000;
            }
            .invoice-table th,
            .invoice-table td {
                border: 1px solid #000;
            }
            .total-section {
                padding: 1rem 0rem;
                margin-top: 2rem;
            }

            .total-row {
                display: flex;
                justify-content: space-between;
                margin-bottom: 0.5rem;
            }

            .total-row {
                display: flex;
                justify-content: space-between;
            }

            @media print {
                @page {
                    size: A4;
                    margin-top: 12.7mm;
                    margin-right: 12.7mm;
                    /* optional: top/right/bottom/left margin */
                }
                .modal-header,.btn-close {
                    display: none !important;
                }
                .invoice-watermark {
                    opacity: 0.05;
                }
                .invoice-container {
                    padding: 0;
                    font-size: 12pt;
                }
                tr {
                    page-break-inside: avoid;
                }
                footer {
                    position: fixed;
                    bottom: 0;
                    left: 0;
                    width: 100%;
                    background: white;
                    padding: 0.5rem 1rem;
                    margin-top: 100px !important;
                    border-top: 1px solid #000;
                }
            }
        </style>
        <img src="{{ asset('backend/assets/images/logo-dark.png') }}" class="invoice-watermark" alt="Watermark">
        <div class="invoice-header">
            <img src="{{ asset('backend/assets/images/logo-dark.png') }}" class="company-logo" alt="Company Logo" style="text-align: center; display: block; margin: 0 auto;">
            <p class="mb-0 text-muted" style="font-size: 1rem;">
                Mob: 01730 430806, 01943 336105 | Email: masbah@ecsbd.net,
                sales@ecsbd.net
            </p>
            <p class="mb-0 text-muted" style="font-size: 0.9rem;">
                Eastern Housing (2nd Pharse), Alubdi Bazar, Pallabi, Mirpur-12, Dhaka
            </p>
        </div>
        <!-- <h3 style="text-align: center; margin: 0rem 0rem;"><b>Invoice/Bill</b></h3> -->
        <div class="invoice-details" style="font-size: 0.8rem;">
            <div class="text-start mt-2" >
                <p class="mb-1"><strong>Date Range:</strong> {{date('d/m/Y', strtotime($show_start_date))}} - {{date('d/m/Y', strtotime($show_end_date))}}</p>
                @if ($invoice_type_filter != 'null')
                <p class="mb-1"><strong>Invoice Type#:</strong> {{ $invoice_type_filter}}</p>
                    
                @endif
            </div>
            @if ($customer_filter != 'null')
            @php
                $customer = App\Models\Customer::findOrFail($customer_filter);
            @endphp
            <div class="text-end mt-2">
                <p class="mb-1"><strong>{{ $customer->id != -1 ? $customer->name : "পথচারি কাস্টমার"}}</strong></p>
                <p class="mb-1">Email: {{$customer->id != -1 ? $customer->email :'N/A' }}</p>
                <p class="mb-1">Phone: {{$customer->id != -1 ? $customer->mobile_no :'N/A' }}</p>
                @if ($customer->address && $customer->id != -1)
                    <p class="mb-1">Address: {{$customer->id != -1 ? $customer->address :'N/A' }}</p>
                @endif
            </div>
            @endif
        </div>
        <table class="invoice-table" style="width: 100%; border-collapse: collapse; margin-top: 0px; font-size: 0.6rem;">
            <thead style="background-color: #dc3545; color: black; font-weight: bold;">
                <tr style="background-color: #dc3545; color: black; font-weight: bold;">
                    <th style="padding: 8px 12px; text-align: left;">#</th>
                    <th style="padding: 8px 12px; text-align: center;">Company</th>
                    <th style="padding: 8px 12px; text-align: left;">Invoice No</th>
                    <th style="padding: 8px 12px; text-align: center;">Date</th>
                    <th style="padding: 8px 12px; text-align: right;">Due</th>
                    <th style="padding: 8px 12px; text-align: right;">Total Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($allData as $key => $item)
                <tr style="border-bottom: 1px solid #000;">
                    <td style="padding: 8px 12px;">{{ $key+1}}</td>
                    <td style="padding: 8px 12px; text-align: left;">{{ ($item['payment']['customer_id'] != -1)? $item['payment']['customer']['name']: 'পথচারি কাস্টমার' }}</td>
                    <td style="padding: 8px 12px; text-align: left;">#{{ $item->invoice_no }}</td>
                    <td style="padding: 8px 12px; text-align: center;">{{ date('d-m-Y',strtotime($item->date)) }}</td>
                    <td style="padding: 8px 12px; text-align: right;">{{ number_format($item['payment']['due_amount'],2) }} Tk</td>
                    <td style="padding: 8px 12px; text-align: right;">{{ number_format($item['payment']['total_amount'],2) }} Tk</td>
                </tr>
                @endforeach
                <tr style="font-weight: 400; ">
                    <td colspan="4" class="thick-line text-end">
                        <strong>Grand Total</strong>
                    </td>
                    <td class="text-end" >{{ (!empty($total_due)?number_format($total_due):'0') }} Tk</td>
                    <td class="text-end" >{{ (!empty($total_amount)?number_format($total_amount):'0') }} Tk</td>
                </tr>
            </tbody>
        </table>
    </div>
    <footer class="text-center mt-1 text-muted d-flex justify-content-center px-3" 
        style="font-size: .6rem; text-align: right; margin-left: 42px;">
        <small>ECS Engineering | Generated By: {{ Auth::user()->name }} | At: {{ now()->format('d-m-Y  h:i A') }} | Software by Munsoft IT (+8801840885553)</small>
    </footer>
</body>

</html>