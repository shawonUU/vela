@extends('admin.admin_master')
@section('admin')

<div class="page-content">
                    <div class="container-fluid">
                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0">Daily Invoice Report</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);"></a></li>
                                            <li class="breadcrumb-item active">Daily Invoice</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->
                       
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
        
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="invoice-title">
                                                    <h3>
                                                        <img src="{{asset('backend/assets/images/logo-sm.png')}}" alt="logo" height="24"/> Ovee Electric Enterprise
                                                    </h3>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-6">
                                                    <address>
                                                             <strong> Proprietor: Foyez Ullah Miazi</strong> <br>
                                                            Munshirhat, Fulgazi, Feni <br>
                                                            Mob: 01717323252
                                                        </address>
                                                        
                                                    </div>
                                                    <div class="col-6 text-end">
                                                        <address>
                                                           
                                                        </address>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-6 mt-4">
                                                        <address>
                                                            
                                                        </address>
                                                    </div>
                                                    <div class="col-6 mt-4 text-end">
                                                        <address>
                                                            
                                                        </address>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
        
                                        <div class="row">
                                            <div class="col-12">
                                                <div>
                                                    <div class="p-2">
                                                        <h3 class="font-size-16"><strong>ডেলিভারি জোন রিপোর্ট
                                                            <span class="btn btn-info"> {{date('d/m/y', strtotime($start_date))}}</span> -
                                                            <span class="btn btn-success"> {{date('d/m/y', strtotime($end_date))}}</span>
                                                        </strong></h3>
                                                    </div>
                                                    <div class="">
                                                        <div class="table-responsive">
                                                            <table class="table">
                                                                <thead>
                                                                <tr>
                                                                    <td class="text-center"><strong>Sl</strong></td>
                                                                    <td class="text-center"><strong>Delivery Zone</strong></td>
                                                                    <td class="text-center"><strong>Customer Name</strong></td>
                                                                    <td class="text-center"><strong>Product</strong></td>
                                                                    <td class="text-center"><strong>SR</strong></td>
                                                                    <td class="text-center"><strong>Invoice No</strong></td>
                                                                    <td class="text-center"><strong>Quantity</strong></td>
                                                                    <td class="text-center"><strong>Unit Price</strong></td>
                                                                    <td class="text-center"><strong>Sell Commission</strong></td>
                                                                    <td class="text-center"><strong>Amount</strong></td>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <!-- foreach ($order->lineItems as $line) or some such thing here -->
                                                                @php
                                                                    $total_sum = '0';
                                                                @endphp
                                                                @foreach($allData as $key => $item)
                                                                <tr>
                                                                    
                                                                    <td class="text-center">{{$key+1}}</td>
                                                                    
                                                                    
                                                                    <td class="text-center">{{ $item['delivery_zones']['delivery_zone'] }}</td>
                                                                    <td class="text-center">{{ $item['payment']['customer']['name'] }}</td>
                                                                    {{-- <td class="text-center">{{ $item['invoice_details']['product']['name'] }}</td> --}}
                                                                    <td class="text-center">{{ $item['invoice_details2']['product']['name'] }}</td>
                                                                    <td class="text-center">{{ $item['sales_rep']['name'] }}</td>
                                                
                                                                    <td class="text-center"># {{ $item->invoice_no }}</td>
                                                                    
                                                                    {{-- <td class="text-center">{{date('d/m/y', strtotime($item->date))}}</td> --}}
                                                                    <td class="text-center">{{ $item['invoice_details2']['selling_qty'] }}</td>
                                                                    <td class="text-center">{{ $item['invoice_details2']['unit_price'] }}</td>
                                                                    <td class="text-center">{{ $item['invoice_details2']['total_sell_commission'] }}</td>
                                                                    <td class="text-center">{{ $item['invoice_details2']['selling_price'] }}</td>
                                                                </tr>
                                                                @php
                                                                    $total_sum += $item['invoice_details2']['selling_price'];
                                                                @endphp
                                                                @endforeach
                                                               
                                                                <tr>
                                                                    {{-- <td class="no-line"></td>
                                                                    <td class="no-line"></td>
                                                                    <td class="no-line"></td> --}}
                                                                    <!-- <td class="no-line"></td> -->
                                                                    <td colspan="7" class="no-line text-end">
                                                                        <h4>Grand Total</h4></td>
                                                                    <td class="no-line text-Center"><h4 class="m-0">{{ $total_sum }}</h4></td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <div class="d-print-none">
                                                            <div class="float-end">
                                                                <a href="javascript:window.print()" class="btn btn-success waves-effect waves-light"><i class="fa fa-print"></i></a>
                                                                <a href="#" class="btn btn-primary waves-effect waves-light ms-2">Download</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> <!-- end row -->
        
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->

                    </div> <!-- container-fluid -->
                </div>
                <!-- End Page-content -->

@endsection