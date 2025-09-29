@extends('admin.admin_master')
@section('admin')

<div class="page-content">
    <div class="container-fluid">

        <!-- Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Product Details</h4>
                    <div class="page-title-right">
                        <a href="{{ route('product.all') }}" class="btn btn-dark btn-rounded"><i class="fa fa-chevron-circle-left"></i> Back</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Info -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <div class="row mb-2">
                            <div class="col-sm-3"><strong>Name:</strong> {{ $product->name }}</div>
                            <div class="col-sm-3"><strong>Sort Name:</strong> {{ $product->product_sort_name }}</div>
                            <div class="col-sm-3"><strong>Expire Date:</strong> {{ $product->expire_date ?? '-' }}</div>
                            <div class="col-sm-3">
                                <strong>Fabrics:</strong>
                                @php
                                    $selectedFabrics = json_decode($product->fabrics, true) ?: [];
                                @endphp
                                @foreach ($fabrics as $fabric)
                                    @if(in_array($fabric->id, $selectedFabrics))
                                        <span class="badge bg-primary">{{ $fabric->name }}</span>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-3"><strong>Brand:</strong> {{ $product->brand->name ?? '-' }}</div>
                            <div class="col-sm-3"><strong>Category:</strong> {{ $product->category->name ?? '-' }}</div>
                            <div class="col-sm-3"><strong>Unit:</strong> {{ $product->unit->name ?? '-' }}</div>
                        </div>

                        <hr>

                        <!-- Product Variants -->
                        <h6 class="mt-3 mb-2">Variants:</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th>Size</th>
                                        <th>Stock</th>
                                        <th>SKU</th>
                                        <th>Barcode</th>
                                        <th>Colors</th>
                                        <th>Buying Price</th>
                                        <th>Price Code</th>
                                        <th>Retail Price</th>
                                        <th>Retail Offer</th>
                                        <th>Wholesale</th>
                                        <th>Wholesale Offer</th>
                                        <th>Fixed Price</th>
                                        <th>Max Discount%</th>
                                        <th>Offer Discount%</th>
                                        <th>Offer From</th>
                                        <th>Offer To</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($product->productSizes as $variant)
                                        @php
                                            $buying = $variant->buying_price > 0 ? $variant->buying_price : 1;
                                            $retail = round(100 * ($variant->selling_price - $buying) / $buying);
                                            $retailOffer = round(100 * ($variant->discounted_price - $buying) / $buying);
                                            $wholesale = round(100 * ($variant->wholesell_price - $buying) / $buying);
                                            $wholesaleOffer = round(100 * ($variant->wholesell_discounted_price - $buying) / $buying);
                                            $selectedColors = json_decode($variant->color, true) ?: [];
                                        @endphp
                                        <tr>
                                            <td>{{ $variant->size->name ?? '-' }}</td>
                                            <td>{{ $variant->quantity }}</td>
                                            <td>{{ $variant->sku }}</td>
                                            <td>{{ $variant->barcode }}</td>
                                            <td>
                                                @foreach($selectedColors as $color)
                                                    <span class="badge bg-secondary">{{ $color }}</span>
                                                @endforeach
                                            </td>
                                            <td>{{ $variant->buying_price }}</td>
                                            <td>{{ $variant->buying_price_code }}</td>
                                            <td>{{ $variant->selling_price }}</td>
                                            <td>{{ $variant->discounted_price }}</td>
                                            <td>{{ $variant->wholesell_price }}</td>
                                            <td>{{ $variant->wholesell_discounted_price }}</td>
                                            <td>{{ $variant->fixed_price ? 'Yes' : 'No' }}</td>
                                            <td>{{ $variant->max_discount }}</td>
                                            <td>{{ $variant->offer_discount }}</td>
                                            <td>{{ $variant->offer_from ?? '-' }}</td>
                                            <td>{{ $variant->offer_to ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <hr>

                        <!-- Description -->
                        <div class="mt-3">
                            <h6>Description:</h6>
                            <p>{!! $product->description !!}</p>
                        </div>

                        <hr>

                        <!-- Product Image -->
                        <div class="mt-3">
                            <h6>Product Image:</h6>
                            <img src="{{ asset($product->product_image ?? '/upload/no_image.png') }}" class="rounded avatar-lg" alt="Product Image">
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
