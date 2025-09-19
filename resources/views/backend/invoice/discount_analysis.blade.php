@extends('admin.admin_master')
@section('admin')


<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Invoice Discount Analysis</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);"> </a></li>
                            <!-- <li class="breadcrumb-item m-2 "><a href="{{route('product.add')}}">ADD PRODUCT</a></li> -->
                            @can('product-price-code-list')
                            <li class="breadcrumb-item m-2 mt-3"><a href="{{route('productpricecode.all')}}">PRODUCT PRICE CODE</a></li>
                            @endcan
                            @can('product-label-list')
                            <li class="breadcrumb-item m-2 mt-3"><a href="{{route('productLabelsprint.index')}}">PRINT PRODUCT LABELS</a></li>
                            @endcan
                            @can('brand-list')
                            <li class="breadcrumb-item m-2 mt-3"><a href="{{route('brand.add')}}">ALL BRAND</a></li>
                            @endcan
                            @can('category-list')
                            <li class="breadcrumb-item m-2 mt-3"><a href="{{route('category.add')}}">ALL CATEGORY</a></li>
                            @endcan
                            @can('unit-list')
                            <li class="breadcrumb-item m-2 mt-3"><a href="{{route('unit.all')}}">ALL UNIT</a></li>
                            @endcan
                            <!-- <li class=" m-2 "><a href="{{ route('product.add') }}" class="btn btn-success"><i class="fas fa-plus-circle"></i> Add </a></li> -->
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
                        <h4 class="card-title">All Invoice </h4>
                        <div class="table-responsive">
                            <table id="datatable" class="table table-bordered " style="border-collapse: collapse; border-spacing: 0; width: 100%; white-space:nowrap;">
                                <thead>
                                    <tr style="background: #eaeaea;">
                                        <th width="20px;">#</th>
                                        <th>Name</th>
                                        <th>Barcode Number</th>
                                        <th>Invoice</th>
                                        <th>Date</th>
                                        <th>Qty</th>
                                        <th>Purchase Price</th>
                                        <th>Selling Price</th>
                                        <th>Discount%</th>
                                        <th>Actual Price</th>
                                        <th>Profit</th>
                                        <th>% Of Profit</th>
                                       
                                       
                                </thead>
                                <tbody>
                                    @php $dx = 1; @endphp
                                    @foreach($invoices as $invoice)
                                        @foreach($invoice->invoice_details as $invoiceDetails)

                                            @php
                                                $productSize = $invoiceDetails->product_size;
                                                $product = $productSize?->product;
                                                $buying  = $invoiceDetails->buying_price > 0 ? $invoiceDetails->buying_price : 1;
                                                $selling = $invoiceDetails->selling_price;
                                                $actualPrice = $invoiceDetails->selling_price - $invoiceDetails->discount_amount;
                                                $profit  = $actualPrice - $buying;
                                                $pProfit = round(100 * $profit / $buying) 
                                               
                                            @endphp

                                            <tr style="background: {{($dx%2) == 0 ? '#eaeaea' : '#ffffff'}};">
                                                <td>{{ $dx++ }}</td>
                                                <td>{{ $product->name }} ({{ $productSize['size']->name }})</td>
                                                <td>{{ $productSize->barcode }}</td>
                                                <td>{{ $invoice->invoice_no }}</td>
                                                <td>{{$invoice->date }}</td>
                                                <td>{{ $invoiceDetails->selling_qty }}</td>
                                                <td>{{ $buying }}</td>
                                                <td>{{ $selling }}</td>
                                                <td>{{ $invoiceDetails->discount_rate }}</td>
                                                <td>{{$actualPrice}}</td>
                                                <td>{{$profit}}</td>
                                                <td>{{$pProfit}}</td>

                                               
                                            </tr>
                                            
                                        @endforeach
                                    @endforeach


                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->



    </div> <!-- container-fluid -->
</div>


@endsection