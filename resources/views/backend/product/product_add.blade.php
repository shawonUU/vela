@extends('admin.admin_master')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<div class="page-content">
    <!--Start Page Title-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Add Product</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);"> </a></li>
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
                            @can('size-list')
                            <li class="breadcrumb-item m-2 mt-3"><a href="{{route('size.add')}}">ALL SIZE</a></li>
                            @endcan
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">
            <form method="POST" action="{{ route('product.store')}}" id="myForm" enctype="multipart/form-data">
                @csrf
                <div class="col-12">
                    <div class="card">
                        <div class="card-body" style="font-size: .8rem;">
                            <div class="row mb-3">
                                <div class="form-group col-sm-2">
                                    <label for="example-text-input" class="col-sm-5 col-form-label">Name</label>
                                    <input name="name" id="productName" class="form-control" type="text" placeholder="Product Name" required>
                                </div>

                                <div class="form-group col-sm-2">
                                    <label for="example-text-input" class="col-sm-5 col-form-label">Sort Name</label>
                                    <input name="product_sort_name" class="form-control" id="productShortName" type="text" placeholder="Product Sort Name">
                                </div>
                                <!-- Expire Date -->
                                <div class="form-group col-sm-2">
                                    <label for="example-text-input" class="col-sm-5 col-form-label">Expire Date</label>
                                    <input name="expire_date" id="expire_date" class="form-control" type="date" autocomplete="off" placeholder="Expire Date">
                                </div>
                                <!-- Fabrics -->
                                <div class="form-group col-sm-6">
                                    <label for="example-text-input" class="col-sm-5 col-form-label">Fabrics</label>
                                    <select multiple class="form-select fabrics" name="fabrics[]">
                                        @foreach($fabrics as $value)
                                        <option value="{{$value->id}}">{{$value->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Brand Dropdown button  -->
                                <div class="form-group col-sm-4" id="brand_col">
                                    <label class="col-sm-12 col-form-label">Brand</label>
                                    <select class="form-select " name="brand_id" id="brand_id" aria-label="Default select example" required>
                                        <option selected value="0">Select Brand</option>
                                        <option value="-1">+ Add</option>
                                        @foreach($brands as $brand)
                                        <option value="{{$brand->id}}">{{$brand->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Hide New Brand insert form -->
                                <div class="new_brand col-sm-2" style="display: none;">
                                    <div class="form-group col-md-12">
                                        <label for="example-text-input" class="col-sm-5 col-form-label">+ Add Brand</label>
                                        <input type="text" name="brand_name" id="brand_name" class="form-control" placeholder="New Brand Name" required>
                                    </div>
                                </div> <br>
                                <!-- Brand button End Row -->
                                <!-- Category Dropdown button  -->
                                <div class="form-group col-sm-4" id="category_col">
                                    <label class="col-sm-12 col-form-label">Category </label>
                                    <select class="form-select" name="category_id" id="category_id" aria-label="Default select example" required>
                                        <option selected value="0">Select Category</option>
                                        <option value="-1">+ Add</option>
                                        @foreach($category as $cat)
                                        <option value="{{$cat->id}}">{{$cat->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Category button End Row -->
                                <!-- Hide New Category insert form -->
                                <div class="new_category col-sm-2" style="display: none;">
                                    <div class="form-group col-md-12">
                                        <label for="example-text-input" class="col-sm-12 col-form-label">+ Add Category</label>
                                        <input type="text" name="category_name" id="category_name" class="form-control" placeholder="New Category Name" required>
                                    </div>
                                </div> <br>
                                <!-- Category button End Row -->
                                <!-- Units Dropdown button  -->
                                <div class="col-sm-4" id="unit_col">
                                    <label class="col-sm-6 col-form-label">Unit </label>
                                    <select class="form-group form-select" name="unit_id" id="unit_id" aria-label="Default select example">
                                        <!-- <option selected value="0">Select Unit</option> -->
                                        
                                        @foreach($unit as $uni)
                                        <option value="{{$uni->id}}">{{$uni->name}}</option>
                                        @endforeach
                                        <option value="-1">+ Add</option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-2" id="new_unit" style="display: none;">
                                    <label for="example-text-input" class="col-sm-12 col-form-label">+Add Unit</label>
                                    <input name="unit_name" id="unit_name" class="form-control" type="text" autocomplete="off" placeholder="New Unit" required>
                                </div>


                            </div>
                            <!-- end row -->
                            <!-- supplier Dropdown button  -->
                            {{--
                            <div class="row mb-3">
                                <label class="form-group col-sm-2 col-form-label">Supplier Name</label>
                                <div class="col-sm-10">
                                    <select class="form-select" name="supplier_id" aria-label="Default select example">
                                        <option selected="">Select Supplier</option>
                                        @foreach($supplier as $supp)
                                        <option value="{{$supp->id}}">{{$supp->name}}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>
                    --}}
                    <!-- Dropdown button End Row -->
                    {{--
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="part_number" class="col-sm-12 col-form-label">Part Number</label>
                                        <input name="part_number" class="form-control" id="part_number" type="text" placeholder="Part Number" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="model_number" class="col-sm-12 col-form-label">Model Number</label>
                                        <input name="model_number" class="form-control" id="model_number" type="text" placeholder="Model Number" />
                                    </div>
                                </div>
                            </div>
                            --}}
                    <div id="variantContainer">
                        <div class="row mb-3 variantRow border p-3 rounded">

                            <!-- Size -->
                            <div class="form-group col-sm-2">
                                <label>Size</label>
                                <select class="form-select" name="sizes[]" required>
                                    <option selected value="">Select Size</option>
                                    @foreach($size as $val)
                                    <option value="{{$val->id}}">{{$val->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Stock -->
                            <div class="form-group col-sm-2">
                                <label>Stock</label>
                                <input name="quantities[]" class="form-control" type="number" placeholder="Stock" readonly>
                            </div>

                            <!-- SKU -->
                            <div class="form-group col-sm-2">
                                <label>SKU</label>
                                <input name="skus[]" class="form-control" type="text" placeholder="SKU">
                            </div>

                            <!-- Barcode -->
                            <div class="form-group col-sm-2">
                                <label>Barcode</label>
                                <input name="barcodes[]" class="form-control" type="text" readonly>
                            </div>
                            <!-- Color -->
                            <div class="form-group col-sm-4">
                                <label>Colors</label>
                                <select multiple class="form-select colors">
                                    <option disabled>Select Colors</option>
                                    <option value="Red">Red</option>
                                    <option value="Blue">Blue</option>
                                    <option value="Green">Green</option>
                                    <option value="Black">Black</option>
                                    <option value="White">White</option>
                                    <option value="Yellow">Yellow</option>
                                    <option value="Orange">Orange</option>
                                    <option value="Purple">Purple</option>
                                    <option value="Pink">Pink</option>
                                    <option value="Brown">Brown</option>
                                    <option value="Gray">Gray</option>
                                    <option value="Maroon">Maroon</option>
                                    <option value="Navy">Navy</option>
                                    <option value="Teal">Teal</option>
                                    <option value="Olive">Olive</option>
                                </select>
                            </div>
                            <!-- Buying Price -->
                            <div class="form-group col-sm-6">
                                <label>Buying Price</label>
                                <input name="buying_prices[]" class="form-control" type="text" placeholder="Buying Price" value="0" required>
                            </div>

                            <!-- Price Code -->
                            <div class="form-group col-sm-6">
                                <label>Price Code</label>
                                <input name="price_codes[]" class="form-control" type="text" readonly>
                            </div>
                            <!-- Retail Price -->
                            <div class="form-group col-sm-6">
                                <div class="card my-2">
                                    <div class="card-body p-2" style="border: 1px solid #000; border-radius: 15px;">
                                        <div class="row">
                                            <div class="col-6">
                                                    <label>Retail Markup <span class="retailMarkupValue">0%</span></label>
                                                    <hr class="m-0">
                                                    <label for="" >Retail Price</label>
                                                    <input type="number" min="0" max="100" value="0" class="form-control retailMarkupRange">
                                                    <input name="selling_prices[]" class="form-control selling_price" type="text" value="0" >
                                            </div>
                                            <div class="col-6">
                                                    <label for="">Profit</label>
                                                    <hr class="m-0">
                                                    <label for="" >Vat 10%</label>
                                                    <input type="number" class="form-control retailProfitShow" value="0" readonly>
                                                    <input  type="text" class="form-control vatRetailProfitShow" value="0" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                
                                
                            </div>

                            <!-- Retail Offer -->
                            <div class="form-group col-sm-6 d-none">
                                <label>Retail Offer Markup <span class="retailOfferValue">0%</span></label>
                                <input type="number" min="0" max="100" value="0" class="form-control-range retailOfferRange">
                                <input name="discounted_price[]" class="form-control retail_offer" type="text" value="0" >
                            </div>

                            <!-- Wholesale Price -->
                            <div class="form-group col-sm-6">

                                <div class="card my-2">
                                    <div class="card-body p-2" style="border: 1px solid #000; border-radius: 15px;">
                                        <div class="row">
                                            <div class="col-6">
                                                    <label>Wholesale Markup <span class="wholesaleMarkupValue">0%</span></label>
                                                    <hr class="m-0">
                                                    <label for="" >Wholesale Price</label>
                                                    <input type="number" min="0" max="100" value="0" class="form-control -range wholesaleMarkupRange">
                                                    <input name="wholesell_price[]" class="form-control wholesale_price" type="text" value="0" >
                                            </div>
                                            <div class="col-6">
                                                    <label for="">Profit</label>
                                                    <hr class="m-0">
                                                    <label for="" >Vat 10%</label>
                                                    <input type="number" class="form-control wholesaleProfitShow" value="0" readonly>
                                                    <input  type="text" class="form-control vatWholesaleProfitShow" value="0" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>

                            <!-- Wholesale Offer -->
                            <div class="form-group col-sm-6 d-none">
                                <label>Wholesale Offer Markup <span class="wholesaleOfferValue">0%</span></label>
                                <input type="number" min="0" max="100" value="0" class="form-control-range wholesaleOfferRange">
                                <input name="wholesell_discounted_price[]" class="form-control wholesale_offer" type="text" value="0" >
                            </div>

                            <div class="form-group col-sm-12">
                                <input type="checkbox" style="margin-top:10px; height: 20px; width:20px;" name="fixed_price[]"> <label for="" class="mb-2">Fiexed Price</label>
                            </div>


                            <hr style="margin: 10px; height: 2px;">

                            <div class="form-group col-12">
                                <div class="row">
                                    
                                    
                                    <div class="form-group col-sm-6">

                                        <div class="card my-2">
                                            <div class="card-body p-2" style="border: 1px solid #000; border-radius: 15px;">
                                                <div class="row">
                                                    <div class="col-6">
                                                            <label>Offer Discount%</label>
                                                            <hr class="m-0">
                                                            <label for="" >Offer Price</label>
                                                            <input name="offer_discount[]" class="form-control offerDiscount" type="number" placeholder="Default Discount" min="0" max="100" value="0">
                                                            <input name="offer_price[]" class="form-control offerPrice" type="number" placeholder="Offer Price" min="0" value="0">
                                                    </div>
                                                    <div class="col-6">
                                                            <label for="">Profit</label>
                                                            <hr class="m-0">
                                                            <label for="" >Vat 10%</label>
                                                            <input type="number" class="form-control offerDiscountShow" value="0" readonly>
                                                            <input  type="text" class="form-control vatOfferDiscountShow" value="0" readonly>
                                                    </div>
                                                    <div class="form-group col-sm-6">
                                                        <label for="example-text-input">Offer From</label>
                                                        <input name="offer_from[]" class="form-control" type="date" autocomplete="off" placeholder="Offer From">
                                                    </div>
                                                    <div class="form-group col-sm-6">
                                                        <label for="example-text-input">Offer To</label>
                                                        <input name="offer_to[]" class="form-control" type="date" autocomplete="off" placeholder="Offer To">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    </div>

                                    <div class="form-group col-sm-6">
                                        <div class="card my-2">
                                            <div class="card-body p-2" style="border: 1px solid #000; border-radius: 15px;">
                                                <div class="row">
                                                    <div class="col-6">
                                                            <label>Max Discount%</label>
                                                            <hr class="m-0">
                                                            <label for="" >Min Price</label>
                                                            <input name="max_discount[]" class="form-control maxDiscount" type="number" placeholder="Max Discount" min="0" max="100" value="0">
                                                            <input name="min_price[]" class="form-control minPrice" type="number" placeholder="Min Price" min="0" value="0">
                                                    </div>
                                                    <div class="col-6">
                                                            <label for="">Profit</label>
                                                            <hr class="m-0">
                                                            <label for="" >Vat 10%</label>
                                                            <input type="number" class="form-control maxDiscountShow" value="0" readonly>
                                                            <input  type="text" class="form-control vatMaxDiscountShow" value="0" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>

                                    
                                </div>
                            </div>




                            <!-- Remove Button -->
                            <div class="form-group col-sm-12 text-end pt-2">
                                <button type="button" class="btn btn-danger btn-sm removeVariantRow">Remove</button>
                            </div>
                        </div>
                    </div>

                    <!-- Add More Button -->
                    <div class="row">
                        <div class="col-12 text-center mt-2">
                            <button type="button" id="addVariantRow" class="btn btn-primary w-100">
                                + Add More Sizes
                            </button>
                        </div>
                    </div>

                    <!-- Product Pricing -->

                    <!-- Product Pricing row end -->

                    <!-- end row -->
                    <!-- <div class="row mb-3">
                                <label for="example-text-input" class="col-sm-2 col-form-label">Market Com</label>
                                <div class="form-group col-sm-10">
                                    <input name="market_com" class="form-control" type="text" placeholder="Market Commission">
                                </div>
                            </div> -->
                    <!-- end row -->
                    <!-- <div class="row mb-3">
                                <label for="example-text-input" class="col-sm-2 col-form-label">Special Com</label>
                                <div class="form-group col-sm-10">
                                    <input name="special_com" class="form-control" type="text" placeholder="Special Commission">
                                </div>
                            </div> -->
                    <!-- end row -->
                    <div class="row mb-3">
                        <div class="form-group col-sm-12">
                            <label for="example-text-input" class="col-sm-2 col-form-label">Description</label>
                            <textarea name="description" id="description" class="form-control summernote" placeholder="Write Description Here..." autocomplete="off" rows="4"></textarea>
                        </div>
                    </div>
                    <!-- end row -->
                    <!-- Product Image -->
                    <div class="row mb-3">
                        <div class="form-group col-sm-12">
                            <label for="example-text-input" class="col-sm-2 col-form-label">Image</label>
                            <input name="product_image" class="form-control" type="file" id="image">
                        </div>

                    </div>
                    <!-- end row -->
                    <div class="row mb-3">
                        <div class="col-sm-12">
                            <img id="showImage" class="rounded avatar-lg" src="{{ asset('/upload/no_image.png') }}" alt="Card image cap">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="form-group col-md-12 text-start">
                            <button type="submit" class="btn btn-success"><i class="fas fa-plus-circle"></i> Add </button>
                        </div>
                    </div>
                    <!-- end row Product Image -->

                </div>
                </div>
                </div> <!-- 1st end col -->
            </form>
</div>

</div>
</div>
<!-- JS Start -->
<script>
    tinymce.init({
        selector: '#description',
        plugins: 'lists advlist bold italic link',
        toolbar: 'bold italic | bullist numlist | link',
        menubar: false
    });
</script>
<script>
    $(document).ready(function() {
        $("#brand_id").select2();
        $("#category_id").select2();
        $("#size_id").select2();
        $("#unit_id").select2();
        $(".colors").select2();
        $(".fabrics").select2();
    });
</script>
<!-- Tax btn Toggle START-->
<!-- <script type="text/javascript">
    $(function() {
        $(document).on('change', '#tax_type', function() {
            var tax_type = $(this).val();
            if (tax_type == 'Excluded') {
                $('.div-tax').show();
                $('#tax_type_btn').removeClass('col-sm-4');
                $('#tax_type_btn').addClass('col-sm-2');
            } else {
                $('.div-tax').hide();
                $('#tax_type_btn').removeClass('col-sm-2');
                $('#tax_type_btn').addClass('col-sm-4');
            }
        })
    })
</script> -->
<!-- Tax btn Toggle END-->
<!-- variant js -->
<script>
    $(document).ready(function () {

        // Custom rounding: ceil if ≥ 0.5, else floor
        function customRound(num) {
            let whole = Math.floor(num);
            let decimal = num - whole;
            return decimal >= 0.5 ? Math.ceil(num) : Math.floor(num);
        }

        // Attach logic to one variant row
        // function attachPriceLogic($row) {
        //     const $buying = $row.find('input[name="buying_prices[]"]');

        //     const ranges = {
        //         retail: $row.find('.retailMarkupRange'),
        //         retailOffer: $row.find('.retailOfferRange'),
        //         wholesale: $row.find('.wholesaleMarkupRange'),
        //         wholesaleOffer: $row.find('.wholesaleOfferRange'),
        //     };

        //     const outputs = {
        //         retail: $row.find('.selling_price'),
        //         retailOffer: $row.find('.retail_offer'),
        //         wholesale: $row.find('.wholesale_price'),
        //         wholesaleOffer: $row.find('.wholesale_offer'),
        //     };

        //     const labels = {
        //         retail: $row.find('.retailMarkupValue'),
        //         retailOffer: $row.find('.retailOfferValue'),
        //         wholesale: $row.find('.wholesaleMarkupValue'),
        //         wholesaleOffer: $row.find('.wholesaleOfferValue'),
        //     };

        //     function calculate(type) {
        //         const buying = parseFloat($buying.val()) || 0;
        //         const percent = parseInt(ranges[type].val()) || 0;

        //         if (percent === 0 || buying === 0) {
        //             outputs[type].val(0);
        //         } else {
        //             const value = customRound(buying + (buying * percent / 100));
        //             outputs[type].val(value);
        //         }

        //         labels[type].text(percent + '%');
        //     }

        //     function bindEvents(type) {
        //         ranges[type].on('input change', () => calculate(type));
        //         $buying.on('input', () => calculate(type));
        //     }

        //     ['retail', 'retailOffer', 'wholesale', 'wholesaleOffer'].forEach(type => {
        //         bindEvents(type);
        //         calculate(type);
        //     });



        // }


        function attachPriceLogic($row){
            const $buying = $row.find('input[name="buying_prices[]"]');

            const changable = {
                retail: $row.find('.retailMarkupRange'),
                retailOffer: $row.find('.retailOfferRange'),
                wholesale: $row.find('.wholesaleMarkupRange'),
                wholesaleOffer: $row.find('.wholesaleOfferRange'),
                offerDiscount: $row.find('.offerDiscount'),
                maxDiscount: $row.find('.maxDiscount'),
                sellingPrice : $row.find('.selling_price'),
                wholesalePrice : $row.find('.wholesale_price'),
                minPrice: $row.find('.minPrice'),
                offerPrice: $row.find('.offerPrice'),
            };

            const inputs = {
                retail: $row.find('.retailMarkupRange'),
                sellingPrice: $row.find('.selling_price'),
                retailOffer: $row.find('.retail_offer'),
                wholesaleMarkupRange: $row.find('.wholesaleMarkupRange'),
                wholesale: $row.find('.wholesale_price'),
                wholesaleOffer: $row.find('.wholesale_offer'),
                minPrice: $row.find('.minPrice'),
                offerPrice: $row.find('.offerPrice'),
                offerDiscount: $row.find('.offerDiscount'),
                maxDiscount: $row.find('.maxDiscount'),
            };

            const labels = {
                retail: $row.find('.retailMarkupValue'),
                retailOffer: $row.find('.retailOfferValue'),
                wholesale: $row.find('.wholesaleMarkupValue'),
                wholesaleOffer: $row.find('.wholesaleOfferValue'),
            };


            const profits = {
                retail: {
                    profit: $row.find('.retailProfitShow'),
                    vatProfit: $row.find('.vatRetailProfitShow'),
                },
                wholesale: {
                    profit: $row.find('.wholesaleProfitShow'),
                    vatProfit: $row.find('.vatWholesaleProfitShow'),
                },
                offerDiscount: {
                    profit: $row.find('.offerDiscountShow'),
                    vatProfit: $row.find('.vatOfferDiscountShow'),
                },
                maxDiscount: {
                    profit: $row.find('.maxDiscountShow'),
                    vatProfit: $row.find('.vatMaxDiscountShow'),
                },
            };

            function calculate(type) {
                const buying = parseFloat($buying.val()) || 0;
                let selling = 0;
                let wholeselling = 0;

                if(type === 'retail'){
                    labels.retail.text(changable.retail.val() + '%');
                    var percent = parseFloat(changable.retail.val()) || 0;
                    if (percent === 0 || buying === 0) {
                        inputs.sellingPrice.val(0);
                    } else {
                        selling = customRound(buying + (buying * percent / 100));
                        inputs.sellingPrice.val(selling);
                    }

                    var profit = customRound(selling - buying);
                    var vatProfit = customRound(profit - (profit * 0.10));
                    profits.retail.profit.val(profit);
                    profits.retail.vatProfit.val(vatProfit);

                    // wholesale
                    percent = parseFloat(changable.wholesale.val()) || 0;
                    if (percent === 0 || buying === 0) {
                        inputs.wholesale.val(0);
                    } else {
                        wholeselling = customRound(buying + (buying * percent / 100));
                        inputs.wholesale.val(wholeselling);
                    }
                    profit = customRound(wholeselling - buying);
                    vatProfit = customRound(profit - (profit * 0.10));
                    profits.wholesale.profit.val(profit);
                    profits.wholesale.vatProfit.val(vatProfit);

                    // Max Discount
                    var sellingPrice = parseFloat( changable.sellingPrice.val()) || 0;
                    var maxDiscount = parseFloat( changable.maxDiscount.val()) || 0;
                    var maxDiscountSellingPrice = customRound(sellingPrice - (sellingPrice * maxDiscount / 100));
                    var profit = customRound(maxDiscountSellingPrice - buying);
                    var vatProfit = customRound(profit - (profit * 0.10));
                    profits.maxDiscount.profit.val(profit);
                    profits.maxDiscount.vatProfit.val(vatProfit);

                    // Offer Discount
                    var sellingPrice = parseFloat( changable.sellingPrice.val()) || 0;
                    var offerDiscount = parseFloat( changable.offerDiscount.val()) || 0;
                    var offerDiscountSellingPrice = customRound(sellingPrice - (sellingPrice * offerDiscount / 100));
                    var profit = customRound(offerDiscountSellingPrice - buying);
                    var vatProfit = customRound(profit - (profit * 0.10));
                    profits.offerDiscount.profit.val(profit);
                    profits.offerDiscount.vatProfit.val(vatProfit);


                }
                if(type === 'sellingPrice'){
                    selling = parseFloat(changable.sellingPrice.val()) || 0; 
                    if(buying>0){
                        labels.retail.text(customRound((selling - buying) / buying * 100) + '%');
                        inputs.retail.val(customRound((selling - buying) / buying * 100));
                    } 

                    var profit = customRound(selling - buying);
                    var vatProfit = customRound(profit - (profit * 0.10));
                    profits.retail.profit.val(profit);
                    profits.retail.vatProfit.val(vatProfit);

                    // wholesale
                    percent = parseFloat(changable.wholesale.val()) || 0;
                    if (percent === 0 || buying === 0) {
                        inputs.wholesale.val(0);
                    } else {
                        wholeselling = customRound(buying + (buying * percent / 100));
                        inputs.wholesale.val(wholeselling);
                    }
                    profit = customRound(wholeselling - buying);
                    vatProfit = customRound(profit - (profit * 0.10));
                    profits.wholesale.profit.val(profit);
                    profits.wholesale.vatProfit.val(vatProfit);

                    // Max Discount
                    var sellingPrice = parseFloat( changable.sellingPrice.val()) || 0;
                    var maxDiscount = parseFloat( changable.maxDiscount.val()) || 0;
                    var maxDiscountSellingPrice = customRound(sellingPrice - (sellingPrice * maxDiscount / 100));
                    var profit = customRound(maxDiscountSellingPrice - buying);
                    var vatProfit = customRound(profit - (profit * 0.10));
                    profits.maxDiscount.profit.val(profit);
                    profits.maxDiscount.vatProfit.val(vatProfit);

                    // Offer Discount
                    var sellingPrice = parseFloat( changable.sellingPrice.val()) || 0;
                    var offerDiscount = parseFloat( changable.offerDiscount.val()) || 0;
                    var offerDiscountSellingPrice = customRound(sellingPrice - (sellingPrice * offerDiscount / 100));
                    var profit = customRound(offerDiscountSellingPrice - buying);
                    var vatProfit = customRound(profit - (profit * 0.10));
                    profits.offerDiscount.profit.val(profit);
                    profits.offerDiscount.vatProfit.val(vatProfit);
                }
                if(type === 'wholesale'){
                    var percent = parseFloat(changable.wholesale.val()) || 0;
                    labels.wholesale.text(changable.wholesale.val() + '%');
                    if (percent === 0 || buying === 0) {
                        inputs.wholesale.val(0);
                    } else {
                        wholeselling = customRound(buying + (buying * percent / 100));
                        inputs.wholesale.val(wholeselling);
                    }
                    profit = customRound(wholeselling - buying);
                    vatProfit = customRound(profit - (profit * 0.10));
                    profits.wholesale.profit.val(profit);
                    profits.wholesale.vatProfit.val(vatProfit);
                }
                if(type === 'wholesalePrice'){
                    wholeselling = parseFloat(changable.wholesalePrice.val()) || 0; 
                    if(buying>0){
                        labels.wholesale.text(customRound((wholeselling - buying) / buying * 100) + '%');
                        inputs.wholesaleMarkupRange.val(customRound((wholeselling - buying) / buying * 100));
                    } 

                    
                    profit = customRound(wholeselling - buying);
                    vatProfit = customRound(profit - (profit * 0.10));
                    profits.wholesale.profit.val(profit);
                    profits.wholesale.vatProfit.val(vatProfit);
                }
                if(type === 'offerDiscount'){
                    var sellingPrice = parseFloat( changable.sellingPrice.val()) || 0;
                    var offerDiscount = parseFloat( changable.offerDiscount.val()) || 0;
                    var offerDiscountSellingPrice = customRound(sellingPrice - (sellingPrice * offerDiscount / 100));
                    var profit = customRound(offerDiscountSellingPrice - buying);
                    var vatProfit = customRound(profit - (profit * 0.10));
                    inputs.offerPrice.val(offerDiscountSellingPrice);
                    profits.offerDiscount.profit.val(profit);
                    profits.offerDiscount.vatProfit.val(vatProfit);

                    //Max Discount
                    var offerDiscount = parseFloat( changable.offerDiscount.val()) || 0;
                    var maxDiscount = parseFloat( changable.maxDiscount.val()) || 0;
                    if(offerDiscount > maxDiscount){
                        inputs.maxDiscount.val(offerDiscount);
                        var sellingPrice = parseFloat( changable.sellingPrice.val()) || 0;
                        var maxDiscount = parseFloat( changable.maxDiscount.val()) || 0;
                        var minDiscountSellingPrice = customRound(sellingPrice - (sellingPrice * maxDiscount / 100));
                        inputs.minPrice.val(minDiscountSellingPrice);
                        var profit = customRound(minDiscountSellingPrice - buying);
                        var vatProfit = customRound(profit - (profit * 0.10));
                        profits.maxDiscount.profit.val(profit);
                        profits.maxDiscount.vatProfit.val(vatProfit);
                    }
                }
                if(type === 'maxDiscount'){
                    var sellingPrice = parseFloat( changable.sellingPrice.val()) || 0;
                    var maxDiscount = parseFloat( changable.maxDiscount.val()) || 0;
                    var minDiscountSellingPrice = customRound(sellingPrice - (sellingPrice * maxDiscount / 100));
                    inputs.minPrice.val(minDiscountSellingPrice);
                    var profit = customRound(minDiscountSellingPrice - buying);
                    var vatProfit = customRound(profit - (profit * 0.10));
                    profits.maxDiscount.profit.val(profit);
                    profits.maxDiscount.vatProfit.val(vatProfit);
                }
                if(type === 'offerPrice'){
                    var sellingPrice = parseFloat( changable.sellingPrice.val()) || 0;
                    var offerPrice = parseFloat( changable.offerPrice.val()) || 0;
                    if(sellingPrice>0){
                        inputs.offerDiscount.val(customRound((sellingPrice - offerPrice) / sellingPrice * 100));
                    } 
                    var profit = customRound(offerPrice - buying);
                    var vatProfit = customRound(profit - (profit * 0.10));
                    profits.offerDiscount.profit.val(profit);
                    profits.offerDiscount.vatProfit.val(vatProfit);

                    //Max Discount
                    var offerDiscount = parseFloat( changable.offerDiscount.val()) || 0;
                    var maxDiscount = parseFloat( changable.maxDiscount.val()) || 0;
                    if(offerDiscount > maxDiscount){
                        inputs.maxDiscount.val(offerDiscount);
                        var sellingPrice = parseFloat( changable.sellingPrice.val()) || 0;
                        var maxDiscount = parseFloat( changable.maxDiscount.val()) || 0;
                        var minDiscountSellingPrice = customRound(sellingPrice - (sellingPrice * maxDiscount / 100));
                        inputs.minPrice.val(minDiscountSellingPrice);
                        var profit = customRound(minDiscountSellingPrice - buying);
                        var vatProfit = customRound(profit - (profit * 0.10));
                        profits.maxDiscount.profit.val(profit);
                        profits.maxDiscount.vatProfit.val(vatProfit);
                    }
                }
                if(type === 'minPrice'){
                    var sellingPrice = parseFloat( changable.sellingPrice.val()) || 0;
                    var minPrice = parseFloat( changable.minPrice.val()) || 0;
                    if(sellingPrice>0){
                        inputs.maxDiscount.val(customRound((sellingPrice - minPrice) / sellingPrice * 100));
                    } 
                    var profit = customRound(minPrice - buying);
                    var vatProfit = customRound(profit - (profit * 0.10));
                    profits.maxDiscount.profit.val(profit);
                    profits.maxDiscount.vatProfit.val(vatProfit);
                }
            }

            function bindEvents(type) {
                if (changable[type]) changable[type].on('input change', () => calculate(type));
                $buying.on('input', () => calculate(type));
            }

            ['retail', 'retailOffer', 'wholesale', 'wholesaleOffer', 'offerDiscount', 'maxDiscount', 'sellingPrice','wholesalePrice', 'offerPrice','minPrice'].forEach(type => {
                bindEvents(type);
                calculate(type);
            });

        }

        // Color field name update (indexed)
        function updateColorFieldNames() {
            $('#variantContainer .variantRow').each(function (index) {
                $(this).find('.colors').attr('name', `colors[${index}][]`);
            });
        }

        // Create new variant row
        function createVariantRow() {
            const html = `
            <div class="row mb-3 variantRow border p-3 rounded">
                <div class="form-group col-sm-2">
                    <label>Size</label>
                    <select class="form-select" name="sizes[]" required>
                        <option selected value="">Select Size</option>
                        @foreach($size as $val)
                        <option value="{{$val->id}}">{{$val->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-sm-2"><label>Stock</label><input name="quantities[]" class="form-control" type="number" readonly></div>
                <div class="form-group col-sm-2"><label>SKU</label><input name="skus[]" class="form-control" type="text"></div>
                <div class="form-group col-sm-2"><label>Barcode</label><input name="barcodes[]" class="form-control" type="text" readonly></div>
                <div class="form-group col-sm-4"><label>Colors</label>
                    <select multiple class="form-select colors" style="width: 100%;">
                        <option value="Red">Red</option><option value="Blue">Blue</option><option value="Green">Green</option>
                        <option value="Black">Black</option><option value="White">White</option><option value="Yellow">Yellow</option>
                        <option value="Orange">Orange</option><option value="Purple">Purple</option><option value="Pink">Pink</option>
                        <option value="Brown">Brown</option><option value="Gray">Gray</option><option value="Maroon">Maroon</option>
                        <option value="Navy">Navy</option><option value="Teal">Teal</option><option value="Olive">Olive</option>
                    </select>
                </div>

                <div class="form-group col-sm-6"><label>Buying Price</label><input name="buying_prices[]" class="form-control" type="text" value="0" required></div>
                <div class="form-group col-sm-6"><label>Price Code</label><input name="price_codes[]" class="form-control" type="text" readonly></div>

                <!-- Retail Price -->
                <div class="form-group col-sm-6">

                    <div class="card-body p-2" style="border: 1px solid #000; border-radius: 15px;">
                        <div class="row">
                            <div class="col-6">
                                    <label>Retail Markup <span class="retailMarkupValue">0%</span></label>
                                    <hr class="m-0">
                                    <label for="" >Retail Price</label>
                                    <input type="number" min="0" max="100" value="0" class="form-control retailMarkupRange">
                                    <input name="selling_prices[]" class="form-control selling_price" type="text" value="0" >
                            </div>
                            <div class="col-6">
                                    <label for="">Profit</label>
                                    <hr class="m-0">
                                    <label for="" >Vat 10%</label>
                                    <input type="number" class="form-control retailProfitShow" value="0" readonly>
                                    <input  type="text" class="form-control vatRetailProfitShow" value="0" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Retail Offer -->
                <div class="form-group col-sm-6 d-none">
                    <label>Retail Offer Markup <span class="retailOfferValue">0%</span></label>
                    <input type="number" min="0" max="100" value="0" class="form-control-range retailOfferRange">
                    <input name="discounted_price[]" class="form-control retail_offer" type="text" value="0" >
                </div>

                <!-- Wholesale -->
                <div class="form-group col-sm-6">

                     <div class="card my-2">
                        <div class="card-body p-2" style="border: 1px solid #000; border-radius: 15px;">
                            <div class="row">
                                <div class="col-6">
                                        <label>Wholesale Markup <span class="wholesaleMarkupValue">0%</span></label>
                                        <hr class="m-0">
                                        <label for="" >Wholesale Price</label>
                                        <input type="number" min="0" max="100" value="0" class="form-control -range wholesaleMarkupRange">
                                        <input name="wholesell_price[]" class="form-control wholesale_price" type="text" value="0" >
                                </div>
                                <div class="col-6">
                                        <label for="">Profit</label>
                                        <hr class="m-0">
                                        <label for="" >Vat 10%</label>
                                        <input type="number" class="form-control wholesaleProfitShow" value="0" readonly>
                                        <input  type="text" class="form-control vatWholesaleProfitShow" value="0" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>

                <!-- Wholesale Offer -->
                <div class="form-group col-sm-6 d-none">
                    <label>Wholesale Offer Markup <span class="wholesaleOfferValue">0%</span></label>
                    <input type="number" min="0" max="100" value="0" class="form-control-range wholesaleOfferRange">
                    <input name="wholesell_discounted_price[]" class="form-control wholesale_offer" type="text" value="0" >
                </div>

                 <div class="form-group col-sm-12">
                    <input type="checkbox" style="margin-top:10px; height: 20px; width:20px;" name="fixed_price[]"> <label for="" class="mb-2">Fiexed Price</label>
                </div>
                <hr style="margin: 10px; height: 2px;">
                <div class="form-group col-12">
                    <div class="row">

                         <div class="form-group col-sm-6">
                            <div class="card my-2">
                                <div class="card-body p-2" style="border: 1px solid #000; border-radius: 15px;">
                                    <div class="row">
                                        <div class="col-6">
                                                <label>Offer Discount%</label>
                                                <hr class="m-0">
                                                <label for="" >Offer Price</label>
                                                <input name="offer_discount[]" class="form-control offerDiscount" type="number" placeholder="Default Discount" min="0" max="100" value="0">
                                                <input name="offer_price[]" class="form-control offerPrice" type="number" placeholder="Offer Price" min="0" value="0">
                                        </div>
                                        <div class="col-6">
                                                <label for="">Profit</label>
                                                <hr class="m-0">
                                                <label for="" >Vat 10%</label>
                                                <input type="number" class="form-control offerDiscountShow" value="0" readonly>
                                                <input  type="text" class="form-control vatOfferDiscountShow" value="0" readonly>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="example-text-input">Offer From</label>
                                            <input name="offer_from[]" class="form-control" type="date" autocomplete="off" placeholder="Offer From">
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="example-text-input">Offer To</label>
                                            <input name="offer_to[]" class="form-control" type="date" autocomplete="off" placeholder="Offer To">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-sm-6">

                            <div class="card my-2">
                                <div class="card-body p-2" style="border: 1px solid #000; border-radius: 15px;">
                                    <div class="row">
                                        <div class="col-6">
                                                <label>Max Discount%</label>
                                                <hr class="m-0">
                                                <label for="" >Min Price</label>
                                                <input name="max_discount[]" class="form-control maxDiscount" type="number" placeholder="Max Discount" min="0" max="100" value="0">
                                                <input name="min_price[]" class="form-control minPrice" type="number" placeholder="Min Price" min="0" value="0">
                                        </div>
                                        <div class="col-6">
                                                <label for="">Profit</label>
                                                <hr class="m-0">
                                                <label for="" >Vat 10%</label>
                                                <input type="number" class="form-control maxDiscountShow" value="0" readonly>
                                                <input  type="text" class="form-control vatMaxDiscountShow" value="0" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                       
                    </div>
                </div>

                <div class="form-group col-sm-12 text-end pt-2">
                    <button type="button" class="btn btn-danger btn-sm removeVariantRow">Remove</button>
                </div>
            </div>`;
            return $(html);
        }

        // Add row button
        $('#addVariantRow').click(function () {
            const $newRow = createVariantRow();
            $('#variantContainer').append($newRow);
            $newRow.find('.colors').select2({ placeholder: 'Select Colors', width: '100%' });
            updateColorFieldNames();
            // attachPriceLogic($newRow);
            attachPriceLogic($newRow);
        });

        // Remove row
        $(document).on('click', '.removeVariantRow', function () {
            if ($('.variantRow').length > 1) {
                $(this).closest('.variantRow').remove();
                updateColorFieldNames();
            } else {
                alert('At least one variant row is required.');
            }
        });

        // Init Select2 and existing rows
        $('.colors').select2({ placeholder: 'Select Colors', width: '100%' });
        updateColorFieldNames();
        $('#variantContainer .variantRow').each(function () {
            // attachPriceLogic($(this));
            attachPriceLogic($(this));
        });

    });
</script>



<!-- generate SKU and Barcode START -->
<script>
    $(document).ready(function() {
        function generateRandomSKU() {
            return 'SKU-' + Math.floor(1000 + Math.random() * 9000); // 4-digit random SKU
        }

        function generateRandomBarcode() {
            return Math.floor(100000 + Math.random() * 900000); // 6-digit barcode
        }

        // Trigger when size is changed
        $(document).on('change', 'select[name="sizes[]"]', function() {
            let row = $(this).closest('.variantRow');
            row.find('input[name="skus[]"]').val(generateRandomSKU());
            row.find('input[name="barcodes[]"]').val(generateRandomBarcode());
        });
    });
</script>
<!-- generate SKU and Barcode START -->
<!-- Unit Option -->
<script type="text/javascript">
    $(function() {
        $(document).on('change', '#unit_id', function() {
            var unit_id = $(this).val();

            if (unit_id == -1) {
                $('#new_unit').show();
                $('#unit_col').removeClass('col-sm-4');
                $('#unit_col').addClass('col-sm-2');

            } else {
                $('#new_unit').hide();
                $('#unit_col').addClass('col-sm-4');
            }
        });
    });
</script>
<!-- Brand Option -->
<script type="text/javascript">
    $(function() {
        $(document).on('change', '#brand_id', function() {
            var category_id = $(this).val();

            if (category_id == -1) {
                $('.new_brand').show();
                $('#brand_col').removeClass('col-sm-4');
                $('#brand_col').addClass('col-sm-2');

            } else {
                $('.new_brand').hide();
                $('#brand_col').addClass('col-sm-4');
            }
        });
    });
</script>
<!-- Category Option -->
<script type="text/javascript">
    $(function() {
        $(document).on('change', '#category_id', function() {
            var category_id = $(this).val();

            if (category_id == -1) {
                $('.new_category').show();
                $('#category_col').removeClass('col-sm-4');
                $('#category_col').addClass('col-sm-2');

            } else {
                $('.new_category').hide();
                $('#category_col').addClass('col-sm-4');
            }
        });
    });
</script>

<!-- Java Script validation for empty form -->
<script type="text/javascript">
    $(document).ready(function() {
        $('#myForm').validate({
            rules: {
                name: {
                    required: true,
                },
                product_sort_name: {
                    required: true,
                },
                brand_id: {
                    required: true,
                },
                unit_id: {
                    required: true,
                },

                product_buying_price: {
                    required: true,
                },
                product_selling_price: {
                    required: true,
                },
                sku: {
                    required: false,
                },
                product_image: {
                    required: false,
                },
            },

            messages: {
                name: {
                    required: 'Please Enter the Product Name.',
                },
                product_sort_name: {
                    required: 'Please Enter the Product Sort Name.',
                },
                product_buying_price: {
                    required: 'Please Enter the Product Buying Price.',
                },
                product_selling_price: {
                    required: 'Please Enter the Product Selling Price',
                },
                category_id: {
                    required: 'Please select the category name',
                },
                brand_id: {
                    required: 'Please select the brand name',
                },
                unit_id: {
                    required: 'Please select the unit name',
                },
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            },
        });
    });
</script>
<!-- Product Image show -->
<script type="text/javascript">
    $(document).ready(function() {
        $('#image').change(function(e) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#showImage').attr('src', e.target.result);
            }
            reader.readAsDataURL(e.target.files['0']);
        });
    });
</script>
<!-- Product name show -->
<script type="text/javascript">
    $(document).ready(function() {
        // For existing and future buying_price inputs
        $(document).on('input', '#productName', function() {
            $('#productShortName').val($(this).val())
        });
    });
</script>
<!-- Product price code generation -->
<script type="text/javascript">
    $(document).ready(function() {
        const numberDictionary = @json($productPriceCode -> pluck('code', 'number'));

        function numberToCode(buyingPrice) {
            let num = buyingPrice.toString();
            let result = "";
            let i = 0;

            while (i < num.length) {
                if (i + 2 < num.length && num.substring(i, i + 3) === "000" && numberDictionary?.["000"]) {
                    result += numberDictionary["000"];
                    i += 3;
                } else if (i + 1 < num.length && num.substring(i, i + 2) === "00" && numberDictionary?.["00"]) {
                    result += numberDictionary["00"];
                    i += 2;
                } else {
                    result += numberDictionary?.[num[i]] || "";
                    i++;
                }
            }
            return result.toUpperCase();
        }

        // For existing and future buying_price inputs
        $(document).on('input', 'input[name="buying_prices[]"]', function() {
            let buyingPrice = $(this).val().trim();
            let codeInput = $(this).closest('.variantRow').find('input[name="price_codes[]"]');
            let html = buyingPrice ? numberToCode(parseInt(buyingPrice)) : "";
            codeInput.val(html);
        });
    });
</script>

@endsection