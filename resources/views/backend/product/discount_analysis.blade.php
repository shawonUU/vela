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
                            <li class=" m-2 "><a href="{{ route('product.add') }}" class="btn btn-success"><i class="fas fa-plus-circle"></i> Add </a></li>
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
                            <table id="datatable" class="table table-bordered " style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th width="20px;">#</th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Size</th>
                                        <th>Brand</th>
                                        <th>Category</th>
                                </thead>
                                <tbody>
                                    @php $dx = 1; @endphp
                                    @foreach($products as $key => $item)
                                        @foreach ($item['productSizes'] as $key => $tem)
                                            <tr>
                                                <td> {{ $dx++}} </td>
                                                <td>
                                                    <img class="rounded"
                                                        src="{{ !empty($item->product_image) && file_exists(public_path($item->product_image)) ? asset($item->product_image)  : asset('upload/no_image.png') }}"
                                                        style="width:50px; height:50px;">
                                                </td>

                                                <td> {{ $item->name }} </td>
                                                <td>
                                                    
                                                    {{ $tem['size']->name.' ('.$tem->selling_price.' TK)' }}@if (!$loop->last), @endif
                                                
                                                </td>

                                                <td> {{ !empty($item['brand']['name'])?$item['brand']['name']:'Null' }} </td>
                                                <td> {{ (!empty($item['category']['name'])?$item['category']['name']:'Null') }}</td>
                                                <!-- <td> {!! (!empty($item->product_code)?DNS1D::getBarcodeHTML($item->product_code,"PHARMA"):"Null") !!} </td>  -->
                                                

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