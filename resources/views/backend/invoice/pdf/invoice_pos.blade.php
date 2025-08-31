@php
$org = App\Models\OrgDetails::first();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{$org->org_name_en}}-Invoice #: {{ $invoice->invoice_no }}-{{Date::now()}}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Scripts -->
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
            @if($redirect === 'invoice-edit')
            window.location.href = '{{ route("invoice.edit", $invoice->id) }}';
            @endif
            @if($redirect === 'invoice-add')
            window.location.href = '{{ route("invoice.add") }}';
            @endif
            @if($redirect === 'invoice-all')
            window.location.href = '{{ route("invoice.all") }}';
            @endif
        }
        // Execute performAction after 30 seconds (1000 milliseconds) 1second
        setTimeout(performAction, 1000);
    </script>
    @php
    $payment = App\Models\Payment::where('invoice_id', $invoice->id)->first();
    @endphp

    <div class="row mx-auto" id="printableArea">
        <style>
            .dashed-hr {
                border-bottom: 2px dashed #000000;
                display: block;
                margin: 5px 0;
            }

            @page {
                size: auto;
                margin: 0 15px !important;
            }

            @media print {
                * {
                    color: #000000 !important;
                    font-weight: 700 !important;
                }

                table,
                table th,
                table td {
                    font-size: 12px !important;
                }

                h2,
                h3,
                h4,
                h5,
                h6 {
                    font-weight: 700 !important;
                }

                .table {
                    width: 100%;
                    margin-bottom: 1rem;
                    color: #000000;
                    border-collapse: collapse;
                }

                .table td,
                .table th {
                    padding: .1rem;
                    vertical-align: top;
                    border-top: 1px solid #000000
                }

                .table thead th {
                    vertical-align: bottom;
                    border-bottom: 1px solid #000000
                }

                .table tbody+tbody {
                    border-top: 1px solid #000000
                }

                .table-sm td,
                .table-sm th {
                    padding: .3rem
                }

                .table-bordered {
                    border: 1px solid #000000
                }

                .table-bordered td,
                .table-bordered th {
                    border: 1px solid #000000
                }

                .table-bordered thead td,
                .table-bordered thead th {
                    border-bottom-width: 1px
                }

                .text-left {
                    text-align: left !important;
                }

                .text-right {
                    text-align: right !important;
                }

                .pl--0 {
                    padding-left: 0 !important;
                }

                .pr--0 {
                    padding-right: 0 !important;
                }
                .text-center{
                    text-align: center;
                }
            }
        </style>
        <div class="col-md-12">
            <div class="mx-auto" style="width:290px">
                <div class="text-center">
                    <div class="row">
                        <div class="col-12">
                            <div class="invoice-title" style="text-align: center;">
                                <div class="text-center mb-2" >
                                    {{-- <img class="report-logo" src="{{asset('backend/assets/images/logo-dark.png')}}" alt="logo" height=""/> --}}
                                    <h3 class="com-name" style="font-size:30px;margin-bottom:-22px;"><strong>{{ $org->org_name_en??'N/A' }}</strong></h3> <br>
                                </div>
                                <h5 class="text-center font-size-10" style="margin-top:0px; margin-bottom: 8px;">
                                    {{ $org->address??'N/A' }}<br>
                                    Mob: {{ $org->mobile_no??'N/A' }}<br>
                                </h5>
                            </div>
                            <span class="dashed-hr"></span>
                            <style>
                                .invoice-header {
                                    display: flex;
                                    justify-content: space-between;
                                    font-size: 13px;
                                    line-height: 1.5;
                                    margin-bottom: 10px;
                                }

                                .invoice-col {
                                    width: 48%;
                                }

                                .invoice-col address {
                                    font-style: normal;
                                    margin: 0;
                                    padding: 0;
                                }

                                .text-right {
                                    text-align: right;
                                }
                            </style>
                            <div class="invoice-header">
                                <div class="invoice-col text-left">
                                    <address>
                                        <strong>Invoice #{{ $invoice->id }}</strong><br>
                                        <strong>Date:</strong> {{ date('d/m/y', strtotime($invoice->date)) }}
                                    </address>
                                </div>
                                <div class="invoice-col text-right">
                                    <address>
                                        <strong>Customer: {{ optional($payment->customer)->mobile_no ?? 'N/A' }}</strong><br>
                                        <!-- @php
                                        $transaction_id = optional(\App\Models\PaymentDetail::where('invoice_id', $invoice->id)->first())->transaction_id;
                                        @endphp
                                        @if ($transaction_id)
                                        T.ID: {{ $transaction_id }}
                                        @endif -->
                                    </address>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <span class="dashed-hr"></span>
                <style>
                    #pos-print-demo,
                    #pos-print-demo tr,
                    #pos-print-demo td,
                    #pos-print-demo th {
                        border: 1px dashed #000000;
                    }
                </style>
                <table id="pos-print-demo" class="table table-bordered text-left" style="width: calc(100% - 1px) !important">
                    <thead>
                        <tr>
                            <th class="m-0 p-0"><strong>#</strong></th>
                            <th class="text-left m-0 p-0"><strong>Item</strong></th>
                            <th class="m-0 p-0">
                                <center><strong>Qty</strong></center>
                            </th>
                            <th class="m-0 p-0">
                                <center><strong>UP</strong></center>
                            </th>
                            <th class="m-0 p-0">
                                <center><strong>Price</strong></center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $sl = 0;
                        $total_sum=0;
                        @endphp
                        @foreach($invoice['invoice_details'] as $key => $details)
                        @php
                        $sl++;
                        $product_size = App\Models\ProductSize::find($details->product_id);
                        $product = App\Models\Product::find($product_size->product_id);
                        $productName = $product ? $product->product_sort_name : 'Unknown Product';
                        $productSize = $product_size ? $product_size->size->name : 'Unknown';
                        $productBrand = $product && $product->brand ? $product->brand->name : '';
                        @endphp
                        <tr>
                            <td class="m-0 p-0 text-start">{{$sl}}</td>
                            <td class="text-left">{{ $productName }} {{$productSize?"({$productSize})":''}}</td>
                            <td class="m-0 p-0 text-center">{{ $details->selling_qty }}</td>
                            <td class="m-0 p-0 text-center">{{ number_format($details->unit_price, 1) }}</td>
                            <td class="m-0 p-0 text-center">{{ number_format($details->selling_price, 1) }}</td>
                        </tr>
                        @php
                        $total_sum += $details->unit_price * $details->selling_qty;
                        @endphp
                        @endforeach
                    </tbody>
                </table>
                <span class="dashed-hr"></span>
                <table style="color: black!important; width: 100%!important">
                    <tbody>
                        <tr>
                            <td colspan="2"></td>
                            <td class="text-right">Total Price(+ Inc. Vat):</td>
                            <td class="text-right">{{ number_format($total_sum,2) }} Tk</td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td class="text-right">Total Discount:</td>
                            <td class="text-right">{{ number_format($payment->discount_amount,2) }} Tk</td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td class="text-right">Total Payable:</td>
                            <td class="text-right">{{ number_format($payment->total_amount,2) }} Tk</td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td class="text-right">Paid:</td>
                            <td class="text-right">{{ number_format($payment->paid_amount,2) }} Tk</td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td class="text-right">Due:</td>
                            <td class="text-right">{{ number_format($payment->due_amount,2) }} Tk</td>
                        </tr>
                        {{--
                    @if ($payment->customer_id != -1)
                    <tr>
                        <td colspan="2"></td>
                        <td class="text-right">Previous Due:</td>
                        <td class="text-right">{{ number_format($pre_due,2) }} Tk</td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td class="text-right">Total Due:</td>
                            <td class="text-right">{{ number_format($pre_due+$payment->due_amount,2) }} Tk</td>
                        </tr>
                        @endif
                        --}}

                    </tbody>
                </table>

                <span class="dashed-hr"></span>
                <h5 class="text-center">
                    <strong>Thanks for Shopping.</strong><br>
                    <!-- Software: Munsoft BD, 01815229363 -->
                </h5>
                <p class="text-center">
                    <strong></strong>
                    <span class="dashed-hr"></span>
            </div>
        </div>
    </div>

</body>

</html>