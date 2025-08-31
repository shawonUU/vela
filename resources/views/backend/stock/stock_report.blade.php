@extends('admin.admin_master')
@section('admin')
<div class="page-content">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Stock</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);"> </a></li>
                            <!-- <li class="m-2 ">PRINT STOCK REPORT</a> </li> -->
                            <li class=" active">
                                <a href="{{route('stock.report.pdf')}}"  class="btn btn-dark  waves-effect waves-light" style="float:right;"><i class="fas fa-print"> Print </i> </a>
                            </li>
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
                        <h4 class="card-title">All Stock</h4>
                        <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product Name</th>
                                    <th>Brand</th>
                                    <th>Category</th>
                                    <th>Stock</th>
                                    <th>Unit</th>
                                    <th>Out Qty</th>
                            </thead>
                            <tbody>
                                @foreach($allData as $key => $item)
                                @php
                                $buying_total = App\Models\SupplierPurcheseDetails::where('category_id', $item['product']->category_id)->where('product_id',$item->id)->where('status','1')->sum('buying_qty');

                                $selling_total = App\Models\InvoiceDetail::where('category_id', $item['product']->category_id)->where('product_id',$item->id)->where('status','1')->sum('selling_qty');

                                $brand = App\Models\Brand::find($item['product']->brand_id);
                                $category = App\Models\Category::find($item['product']->category_id);
                                @endphp
                                <tr>
                                    <td> {{ $key+1}} </td>
                                    <!-- <td> {{ (!empty($supplier->name)?$supplier->name:"Null") }} </td> -->
                                    <td> {{ $item['product']->name}} {{ $item['size']->name?'('.$item['size']->name.')':'' }} </td>
                                    <td> {{ !empty($brand->name)?$brand->name:"Null"  }} </td>
                                    <td> {{ !empty($category->name)?$category->name:"Null"  }} </td>
                                    <td> {{ $item->quantity  }}</td>
                                    <td> {{ $item['product']['unit']['name']??'' }} </td>
                                    <!-- <td> <span class="btn btn-info">{{ $buying_total  }}</span> </td> -->
                                    <td>{{ $selling_total }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->
    </div> <!-- container-fluid -->
</div>
@endsection