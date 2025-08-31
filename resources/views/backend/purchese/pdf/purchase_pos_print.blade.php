<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Purchase Print</title>

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
            window.location.href='{{ route("purchase.add")}}';
        }
        // Execute performAction after 30 seconds (1000 milliseconds) 1second
        setTimeout(performAction, 1000);
    </script>
    @php

    $supplier_purchese_payment = App\Models\SupplierPurchesePayment::where('purchase_id',$purchase->id)->first();
    @endphp

    <div class="row mx-auto" id="printableArea">
        <style>
            .dashed-hr {
                border-bottom: 2px dashed #dddddd;
                display: block;
                margin: 15px 0;
            }

            @page {
                size: auto;
                margin: 0 5px !important;
            }

            @media print {
                * {
                    color: #000000 !important;
                    font-weight: 500 !important;
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
                    padding: .75rem;
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

                /* .com-name{
                                                        font-size: 25px !important;
                                                        margin-bottom: -20px !important;
                                                    } */
            }
        </style>
        <div class="col-md-12">
            <div class="mx-auto" style="width:563px">
                <div class="text-center">
                    <div class="row">
                        <div class="col-12">
                            <div class="invoice-title">

                                <div class="text-center mb-2">
                                    {{-- <img class="report-logo" src="{{asset('backend/assets/images/logo-dark.png')}}" alt="logo" height=""/> --}}
                                    <h3 class="com-name" style="font-size:25px;margin-bottom:-20px;">Ovee Electric Enterprise</h3> <br>
                                    <strong style="margin-bottom:-10px;">Proprietor: Foyez Ullah Miazi</strong>
                                </div>
                                <h4 class="text-center font-size-14" style="margin-top:-7px;">
                                    Munshirhat, Fulgazi, Feni <br>
                                    <!-- <strong>মোবাইল:</strong>  -->
                                    01717323252<br>
                                    <!-- <strong>Email:</strong>  -->
                                    
                                </h4>
                            </div>
                            <span class="dashed-hr"></span>
                            <div class="row">
                                <div class="col-6 text-start">
                                    <address>
                                        <h5><strong>purchase # {{$purchase->purchase_no}}</strong><br></h5>
                                        <strong>Date:</strong> {{date('d/m/y', strtotime($purchase->date))}}<br>
                                    </address>
                                </div>
                                <div class="col-6 text-end">
                                    <address>
                                        <strong>Billed To:</strong><br>
                                        {{ $supplier_purchese_payment->supplier_id != -1 ? $supplier_purchese_payment['supplier']['name'] : "N/A"}}<br>
                                        {{$supplier_purchese_payment->supplier_id != -1 ? $supplier_purchese_payment['supplier']['mobile_no'] :'N/A' }}<br>
                                        {{$supplier_purchese_payment->supplier_id != -1 ? $supplier_purchese_payment['supplier']['email'] :'N/A' }}<br>
                                        <!-- <h3 class="font-size-16"><strong>supplier purchase</strong></h3> -->
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
                        border: 2px dashed #c6c6c6;
                    }
                </style>
                <table id="pos-print-demo" class="table table-bordered text-left" style="width: calc(100% - 1px) !important">
                    <thead>
                        <tr>
                            <th class="m-0 p-0"><strong>No.</strong></th>
                            <th class="text-left m-0 p-0"><strong>Products</strong></th>
                            <th class="m-0 p-0">
                                <center><strong>Pcs</strong></center>
                            </th>
                            <th class="m-0 p-0">
                                <center><strong>UP</strong></center>
                            </th>
                            <th class="m-0 p-0">
                                <center><strong>DB</strong></center>
                            </th>
                            <th class="m-0 p-0">
                                <center><strong>MC</strong></center>
                            </th>
                            <th class="m-0 p-0">
                                <center><strong>SC</strong></center>
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
                        @foreach($purchase['supplier_purchese_details'] as $key => $details)
                        @php
                        $sl++;
                        @endphp
                        <tr>
                            <td class="m-0 p-0 text-start">{{$sl}}</td>
                            <td class="m-0 p-0 text-start">{{ $details['product']['product_sort_name'] }}{{ $details->brand_id !=0?'-'.$details['brand']['name']:'' }}</td>
                            {{-- <td class="m-0 p-0 text-center">{{ $details->selling_qty }} {{ $details['product']['unit']['name']}}</td> --}}
                            <td class="m-0 p-0 text-center">{{ $details->buying_qty }}</td>
                            <td class="m-0 p-0 text-center">{{ number_format($details->product_buying_price,1) }}</td>
                            <td class="m-0 p-0 text-center">{{ number_format($details->total_db_com,1) }}</td>
                            <td class="m-0 p-0 text-center">{{ number_format($details->total_mc_com,1) }}</td>
                            <td class="m-0 p-0 text-center">{{ number_format($details->total_sp_com,1) }}</td>
                            <td class="m-0 p-0 text-center">{{ number_format($details->buying_price,1) }}</td>
                        </tr>
                        @php
                        $total_sum += $details->buying_price;
                        @endphp
                        @endforeach
                    </tbody>
                </table>
                <span class="dashed-hr"></span>
                <table style="color: black!important; width: 100%!important">
                    <tbody>
                        <tr>
                            <td colspan="2"></td>
                            <td class="text-right">Total Price:</td>
                            <td class="text-right">{{ number_format($total_sum,2) }} Tk</td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td class="text-right">Discount:</td>
                            <td class="text-right">{{ number_format($supplier_purchese_payment->discount_amount,2) }} Tk</td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td class="text-right">Total Payable:</td>
                            <td class="text-right">{{ number_format($supplier_purchese_payment->total_amount,2) }} Tk</td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td class="text-right">Paid:</td>
                            <td class="text-right">{{ number_format($supplier_purchese_payment->paid_amount,2) }} Tk</td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td class="text-right">Due:</td>
                            <td class="text-right">{{ number_format($supplier_purchese_payment->due_amount,2) }} Tk</td>
                        </tr>
                        @if ($supplier_purchese_payment->supplier_id != -1)
                        <tr>
                            <td colspan="2"></td>
                            <td class="text-right">Previous Due:</td>
                            <td class="text-right">{{ number_format($pre_due,2) }} Tk</td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td class="text-right">Total Due:</td>
                            <td class="text-right">{{ number_format($pre_due+$supplier_purchese_payment->due_amount,2) }} Tk</td>
                        </tr>
                        @endif


                    </tbody>
                </table>

                <span class="dashed-hr"></span>
                <h5 class="text-center">
                    Software: Munsoft BD, 01815229363
                </h5>
                <p class="text-center">
                    <strong></strong>
                    <span class="dashed-hr"></span>
            </div>
        </div>
    </div>

</body>

</html>