@extends('admin.admin_master')
@section('admin')


<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Product Discount Analysis</h4>
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
                        <h4 class="card-title">All Product </h4>
                        <div class="table-responsive">
                            <table id="datatable" class="table table-bordered " style="border-collapse: collapse; border-spacing: 0; width: 100%; white-space:nowrap;">
                                <thead>
                                    <tr style="background: #eaeaea;">
                                        <th width="20px;">#</th>
                                        <th>Name</th>
                                        <th>Barcode Number</th>
                                        <th>Purchase Price</th>
                                        <th>Markup%</th>
                                        <th>Selling Price</th>
                                        <th>Profit</th>
                                        <th>Discount 10%</th>
                                        <th>Profit 10%</th>
                                        <th>Discount 15%</th>
                                        <th>Profit 15%</th>
                                        <th>Discount 20%</th>
                                        <th>Profit 20%</th>
                                       
                                </thead>
                                <tbody>
                                    @php $dx = 1; @endphp
                                    @foreach($products as $key => $item)
                                        @foreach ($item['productSizes'] as $key => $tem)

                                            @php
                                                $buying  = $tem->buying_price > 0 ? $tem->buying_price : 1;
                                                $selling = $tem->selling_price;
                                                $profit  = $selling - $buying;
                                                $markup  = round(100 * $profit / $buying);

                                                // Discounts
                                                $d10 = round($selling * 0.90);
                                                $d15 = round($selling * 0.85);
                                                $d20 = round($selling * 0.80);

                                                // Profits after discount
                                                $p10 = $d10 - $buying;
                                                $p15 = $d15 - $buying;
                                                $p20 = $d20 - $buying;

                                                // Profit after 10% VAT cut
                                                $vatProfit   = round($profit * 0.90);
                                                $vatP10      = round($p10 * 0.90);
                                                $vatP15      = round($p15 * 0.90);
                                                $vatP20      = round($p20 * 0.90);
                                            @endphp

                                            <tr style="background: {{($dx%2) == 0 ? '#eaeaea' : '#ffffff'}};">
                                                <td>{{ $dx++ }}</td>
                                                <td>{{ $item->name }} ({{ $tem['size']->name }})</td>
                                                <td>{{ $tem->barcode }}</td>
                                                <td>{{ $buying }}</td>
                                                <td>{{ $markup }}%</td>
                                                <td>{{ $selling }}</td>
                                                <td style="padding:0;">
                                                    <table >
                                                        <tr>
                                                            <td style="border-right:none; border-bottom : 1px solid #000;">{{ $profit }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border-right:none;">{{ $vatProfit }}</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <td>{{ $d10 }}</td>
                                                <td style="padding:0;">
                                                    <table >
                                                        <tr>
                                                            <td style="border-right:none; border-bottom : 1px solid #000;">{{ $p10 }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border-right:none;">{{ $vatP10 }}</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <td>{{ $d15 }}</td>
                                                <td style="padding:0;">
                                                    <table >
                                                        <tr>
                                                            <td style="border-right:none; border-bottom : 1px solid #000;">{{ $p15 }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border-right:none;">{{ $vatP15 }}</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <td>{{ $d20 }}</td>
                                                <td style="padding:0;">
                                                    <table >
                                                        <tr>
                                                            <td style="border-right:none; border-bottom : 1px solid #000;">{{ $p20 }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border-right:none;">{{ $vatP20 }}</td>
                                                        </tr>
                                                    </table>
                                                </td>
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