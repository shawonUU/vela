@extends('admin.admin_master')
@section('admin')


<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Product </h4>
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
                        <div class="d-flex justify-content-end mb-2">
                            <form id="filter_form" action="" method="get" class="d-flex align-items-center gap-2">
                                @csrf

                                <!-- Category -->
                                <div>
                                    <select class="selectpicker form-control" id="category" name="category_id" data-live-search="true" title="Choose Category">
                                        <option value="">Select</option>
                                        @foreach ($categories as $item)
                                            <option value="{{ $item->id }}" {{ $request?->category_id == $item->id ? 'selected' : '' }}>
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Brand (only if user has permission) -->
                                @can('show_brand')
                                    <div>
                                        <select class="selectpicker form-control" id="brand" name="brand_id" data-live-search="true" title="Choose Brand">
                                            <option value="">Select</option>
                                            @foreach ($brands as $item)
                                                <option value="{{ $item->id }}" {{ $request?->brand_id == $item->id ? 'selected' : '' }}>
                                                    {{ $item->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endcan

                                <!-- Buttons -->
                                <div> 
                                    <button onclick="document.getElementById('filter_form').action='{{ route('product.all') }}'; document.getElementById('filter_form').submit();" name="filter" value="1" class="btn btn-sm btn-primary">Filter</button>
                                    @can('download_product_excel')
                                        <button onclick="document.getElementById('filter_form').action='{{ route('product.download_excel') }}'; document.getElementById('filter_form').submit();" name="download" value="1" class="btn btn-sm btn-success">Download Excel</button>
                                    @endcan
                                </div>
                            </form>
                        </div>

                        <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th width="20px;">#</th>
                                    <!-- <th>Image</th> -->
                                    <th>Name</th>
                                    <th>Barcode</th>
                                    <th>Selling Price</th>
                                    @can('show_brand')
                                    <th>Brand</th>
                                    @endcan
                                    <th>Category</th>
                                    <th width="30px;">Action</th>
                            </thead>
                            <tbody>
                                @php $dx=1; @endphp
                                @foreach($products as $key => $item)
                                    @foreach ($item['productSizes'] as $key => $tem)
                                        <tr>
                                            <td> {{ $dx++ }} </td>
                                            <!-- <td>
                                                <img class="rounded" src="{{ !empty($item->product_image) && file_exists(public_path($item->product_image)) ? asset($item->product_image) : asset('upload/no_image.png') }}" style="width:50px; height:50px;">
                                            </td> -->
                                            <td> {{ $item->name }} ({{$tem['size']->name}})</td>
                                            <td> {{ $tem->barcode }} </td>
                                            <td style="border-right:none;">{{$tem->selling_price}} TK</td>
                                            @can('show_brand')
                                            <td> {{ !empty($item['brand']['name'])?$item['brand']['name']:'Null' }} </td>
                                            @endcan
                                            <td> {{ (!empty($item['category']['name'])?$item['category']['name']:'Null') }}</td>
                                            <!-- <td> {!! (!empty($item->product_code)?DNS1D::getBarcodeHTML($item->product_code,"PHARMA"):"Null") !!} </td>  -->
                                            <td>
                                                @can('product-details')
                                                    <a href="{{route('product.show',$item->id)}}" class="btn btn-info sm" title="Show Data"> <i class="fas fa-eye"></i> </a>
                                                @endcan
                                                @can('product-edit')
                                                <a href="{{route('product.edit',$item->id)}}" class="btn btn-info sm" title="Edit Data"> <i class="fas fa-edit"></i> </a>
                                                @endcan
                                                @can('product-delete')
                                                <a href="{{route('product.delete',$item->id)}}" class="btn btn-danger sm" title="Delete Data" id="delete"> <i class="fas fa-trash-alt"></i> </a>
                                                @endcan
                                            </td>

                                        </tr>
                                    @endforeach
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