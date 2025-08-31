@extends('admin.admin_master')
@section('admin')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Product Label Print</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);"> </a></li>
                            <!-- <li class="breadcrumb-item m-2 "><a href="{{route('product.add')}}">ADD PRODUCT</a></li> -->
                            <li class="breadcrumb-item m-2 "><a href="{{route('product.all')}}">ALL PRODUCT</a></li>
                            <li class="breadcrumb-item m-2 "><a href="{{route('productpricecode.all')}}">PRODUCT PRICE CODE</a></li>
                            <li class="breadcrumb-item m-2 "><a href="{{route('brand.add')}}">ALL BRAND</a></li>
                            <li class="breadcrumb-item m-2 "><a href="{{route('category.add')}}">ALL CATEGORY</a></li>
                            <li class="breadcrumb-item m-2 "><a href="{{route('unit.all')}}">ALL UNIT</a></li>
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
                        <h4 class="card-title">Label Print</h4>
                        <form method="POST" action="{{ route('productLabelsprint.labelPrint') }}" id="myForm">
                            @csrf
                            <div id="variantContainer">
                                <div class="row mb-3 variantRow border p-3 rounded">
                                    <div class="form-group col-sm-4">
                                        <label>Product</label>
                                        <select name="product_id[]" class="form-select product_id" required>
                                            <option value="">Select Product</option>
                                            @foreach(\App\Models\Product::all() as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-sm-4">
                                        <label>Size</label>
                                        <select class="form-select size-dropdown" name="sizes[]" required>
                                            <option value="">Select Size</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-sm-3">
                                        <label>Labels Quantity</label>
                                        <input name="qty[]" class="form-control" type="number" value="10" required>
                                    </div>

                                    <div class="form-group col-sm-1 text-end">
                                        <label>&nbsp;</label>
                                        <button type="button" class="btn btn-danger btn-sm removeVariantRow w-100">X</button>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center my-3">
                                <button type="button" id="addVariantRow" class="btn btn-primary w-100">+ Add More Sizes</button>
                            </div>

                            <button type="submit" class="btn btn-success" name="btn" value="1"><i class="fas fa-plus-circle"></i> A4 Page Label Print</button>
                            <button type="submit" class="btn btn-success" name="btn" value="2"><i class="fas fa-plus-circle"></i> Thermal Label Print</button>
                        </form>

                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->
    </div> <!-- container-fluid -->
</div>
<script>
$(document).ready(function () {
    // Load sizes based on product
    $(document).on('change', '.product_id', function () {
        const $row = $(this).closest('.variantRow');
        const productId = $(this).val();
        const sizeSelect = $row.find('.size-dropdown');

        sizeSelect.html('<option value="">Loading...</option>');

        if (productId) {
            $.ajax({
                url: '{{ route("get.product.sizes") }}',
                type: 'GET',
                data: { product_id: productId },
                success: function (data) {
                    sizeSelect.empty().append('<option value="">Select Size</option>');
                    $.each(data, function (key, value) {
                        sizeSelect.append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                }
            });
        } else {
            sizeSelect.empty().append('<option value="">Select Size</option>');
        }
    });

    // Add new variant row
    $('#addVariantRow').click(function () {
        const $firstRow = $('.variantRow').first();
        const $newRow = $firstRow.clone();

        $newRow.find('select').val('');
        $newRow.find('input').val('10');
        $('#variantContainer').append($newRow);
    });

    // Remove variant row
    $(document).on('click', '.removeVariantRow', function () {
        if ($('.variantRow').length > 1) {
            $(this).closest('.variantRow').remove();
        }
    });
});
</script>

@endsection