@extends('admin.admin_master')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Edit Purchase ({{ $purchase->purchase_type }})</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);"></a></li>
                            <li class="breadcrumb-item active"><a href="{{route('purchase.all')}}">Back</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-s-12">
                <div class="card">
                    <form method="post" action="{{ route('purchase.update') }}" onsubmit="return confirmAction(event)">
                        @csrf
                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="form-group col-md-3 ">
                                    <label for="example-text-input" class="col-form-label">Add Products (Scan Barcode)</label>
                                    <div>
                                        <!-- Barcode input START-->
                                        <input type="search" id="search-product-or-barcode-input" class="form-control" placeholder="Scan Barcode">
                                        <!-- Barcode input END-->
                                    </div>
                                </div>
                                <div class="form-group col-md-9 pb-0">
                                    <label for="example-text-input" class="col-form-label">Add Products </label>
                                    <select id="product_barcode" class="form-select">
                                        <option value="">Add Product</option>
                                        @foreach($product_sizes as $key => $item)
                                        <option value="{{ $item->barcode}}">{{ $item['product']->name }} {{ $item['size']['name']?" ({$item['size']['name']})":'' }}
                                            {{ $item['product']->category_id !=0 ? "-{$item['product']['category']['name']}" : '' }}
                                            {{ $item['product']->brand_id !=0 ? "-{$item['product']['brand']['name']}" : '' }}
                                        </option>
                                        @endforeach
                                    </select><br>
                                </div>
                                <div class="col-md-2 text-left">
                                    <label for="example-text-input" class="col-form-label">Purchase No.</label>
                                    <input class="form-control" type="text" name="purchase_no" value="{{ $purchase->id }}" id="purchase_no" readonly style="background-color:#ddd; ">
                                </div>
                                <!-- <div class="col-md-2 text-left">
                                    <label for="example-text-input" class="col-form-label">Challan No/DN No.</label>
                                    <input class="form-control" type="text" name="dn_no" value="{{ $purchase->dn_no }}" id="dn_no" readonly style="background-color:#ddd; ">
                                </div> -->
                                <div class="col-md-3 text-left">
                                    <label for="example-text-input" class="col-form-label">Challan No.</label>
                                    <input class="form-control" type="text" name="wo_no" value="{{ $purchase->wo_no }}" id="wo_no" placeholder="Enter WO No">
                                </div>
                                <div class="form-group col-md-5 pb-0">
                                    <label for="example-text-input" class="col-form-label">Supplier </label>
                                    <select name="supplier_id" id="supplier_id" class="form-select">
                                        <option value="-2">Select Supplier</option>
                                        <option value="0">+ New Supplier</option>
                                        @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ $payment->supplier_id == $supplier->id ?'selected': '' }}>{{ $supplier->name }} - {{$supplier->mobile_no }}</option>
                                        @endforeach
                                    </select><br>
                                </div>
                                <div class="col-md-2">
                                    <label for="example-text-input" class="col-form-label" style="text-align: right;">Date:</label>
                                    <input class="form-control example-date-input" name="date" value="{{ $purchase->date }}" type="date" id="date">
                                </div>

                                <!-- <div class="form-group col-md-1" style="text-align: right; padding-top: 42px;">
                                    <button type="submit" class="btn btn-info sm fas fa-plus-circle addProduct"></button>
                                </div> -->
                            </div>
                            <!-- Hide New Supplier insert form -->
                            <div class="row new_supplier mb-0" style="display: none;">
                                <div class="form-group col-md-4">
                                    <input type="text" name="name" id="name" class="form-control" placeholder="Supplier Name">
                                </div>

                                <div class="form-group col-md-4">
                                    <input type="number" name="mobile_no" id="mobile_no" class="form-control" placeholder="Supplier Mobile No">
                                </div>

                                <div class="form-group col-md-4">
                                    <input type="email" name="email" id="email" class="form-control" placeholder="Supplier Email">
                                </div>
                                <div class="form-group col-md-12 mt-2">
                                    <textarea name="address" id="address" class="form-control" placeholder="Write address Here..."></textarea>
                                </div>
                            </div>
                            <!-- End Hide New Supplier insert form -->
                        </div> <!-- End card-body -->
                        <div class="card-body">
                            <table class="table-sm table-bordered" width="100%" style="border-color: #ddd;">
                                <thead>
                                    <tr>
                                        <th width="230px;">Product Name </th>
                                        <th width="160px;">PSC/Stock</th>
                                        <th width="200px;">Buying Price</th>
                                        <th width="200px;">VAT</th>
                                        <th width="200px;">Discount(%)</th>
                                        <th>Price/VAT</th>
                                    </tr>
                                </thead>

                                <tbody id="addRow" class="addRow">
                                    @foreach ($purchase['supplier_purchese_details'] as $key => $purchase_details)
                                    @php
                                    $product_size = \App\Models\ProductSize::with('product')->find($purchase_details->product_id);
                                    @endphp
                                    <!-- @if ($product_size) -->
                                    <tr class="delete_add_more_item" id="delete_add_more_item">
                                        <input type="hidden" name="date" value="{{$purchase_details->date?? ''}}">
                                        <input type="hidden" name="purchase_no" value="{{$purchase->id ?? ''}}">
                                        <input type="hidden" name="purchase_details_id[]" value="{{$purchase_details->id ?? ''}}">
                                        <input type="hidden" name="category_id[]" value="{{$purchase_details->category_id ?? ''}}">
                                        <input type="hidden" name="product_size_id[]" value="{{$product_size->id}}">
                                        <input type="hidden" name="size_name[]" value="{{$product_size['size']['name']}}">
                                        <td>
                                            {{ $product_size['product']->name ?? '' }}
                                            {{ $product_size['size']['name']?" ({$product_size['size']['name']})":'' }}
                                            {{ $product_size['product']->brand_id?"-{$product_size['product']['brand']['name']}":'' }}
                                        </td>
                                        <td>
                                            <input type="number" min="1" class="form-control buying_qty text-right" name="buying_qty[]" id="buying_qty" value="{{$purchase_details->buying_qty}}">
                                            <input type="text" class="form-control stock_quantity text-right" name="stock_quantity" value="{{$product_size->quantity}}" readonly> 
                                        </td>
                                        <td>
                                            <input type="text" class="form-control product_buying_price text-right" name="product_buying_price[]" value="{{$purchase_details->product_buying_price}}">
                                            <input type="text" class="form-control unit_price text-right" name="unit_price[]" value="{{$purchase_details->unit_price}}" hidden>
                                        </td>
                                        <!-- TAX  -->
                                        @php
                                        $tax_ids = json_decode($purchase_details->tax_type);
                                        $selected_tax = App\Models\Tax::whereIn('id', $tax_ids)->get();
                                        $unselected_tax = App\Models\Tax::whereNotIn('id', $tax_ids)->get();
                                        @endphp
                                        <td class="discount-row">
                                            <select name="product_tax[{{$purchase_details->product_id}}][]" multiple="multiple" class="form-group form-select product_tax">
                                                <option value="TaxFree">Tax Free</option>
                                                @foreach ($selected_tax as $tax)

                                                <option value="{{$tax->id}}" data-rate="{{$tax->rate}}" selected>
                                                    {{$tax->name}}
                                                </option>
                                                @endforeach
                                                @foreach ($unselected_tax as $tax)
                                                <option value="{{$tax->id}}" data-rate="{{$tax->rate}}">
                                                    {{$tax->name}}
                                                </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <!-- Discount  -->
                                        <td class="discount-row">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input discount_per_product" type="checkbox" {{ $purchase_details->discount_type == 'percentage' ? 'checked' : '' }}>
                                                <label class="form-check-label discount_label" for="flexSwitchCheckDefault">{{ $purchase_details->discount_type == 'percentage' ? '% Percent' : 'Fixed' }}</label>
                                            </div>
                                            <input type="text" class="form-control discount_rate text-right" id="discount_rate" name="discount_rate[]" value="{{$purchase_details->discount_rate}}" autocomplete="off">
                                            <input type="text" class="form-control discount_amount_per_product text-right" id="discount_amount_per_product" name="discount_amount_per_product[]" value="{{$purchase_details->discount_amount}}" readonly>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control buying_price text-right" id="buying_price" name="buying_price[]" value="{{$purchase_details->buying_price}}" readonly>
                                            <input type="text" class="form-control product_tax_amount text-right" id="" name="product_tax_amount[]" value="{{$purchase_details->tax_amount ?? 0 }}" readonly>
                                            @php
                                            $product_price_for_tax = $purchase_details->product_buying_price * $purchase_details->buying_qty;
                                            @endphp
                                            <input type="text" class="form-control product_price_for_tax text-right" id="" name="product_price_for_tax[]" value="{{$product_price_for_tax}}" readonly hidden>
                                            <input type="text" class="form-control product_price_for_tax text-right" id="" name="product_price_for_tax[]" value="{{$purchase_details->product_buying_price * $purchase_details->buying_qty}}" readonly hidden>
                                        </td>
                                        <td style="width:fit-content;">
                                            <i class="btn btn-danger btn-sm fas fa-window-close removeeventmore"></i>
                                        </td>
                                    </tr>
                                    <!-- @endif -->
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="5" class="text-end">
                                            <div class="form-group">
                                                <select name="discount_status" id="discount_status" class="form-select" style="float: right; width:31%">
                                                    <option value="fixed_discount" {{ $purchase->purchase_discount_type === 'fixed_discount' ? 'selected':'' }}>Fixed Discount</option>
                                                    <option value="percentage_discount" {{ $purchase->purchase_discount_type === 'percentage_discount' ? 'selected':'' }}>Percentage Discount(%)</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="text" name="discount_amount" id="discount_amount" class="form-control discount_amount" value="{{$purchase->purchase_discount_type === 'percentage_discount' ? $purchase->purchase_discount_rate : $purchase->purchase_discount_amount }}" autocomplete="off">
                                            <input type="text" name="discount_show" id="discount_show" class="form-control discount_show" value="{{$purchase->purchase_discount_type === 'percentage_discount' ? $purchase->purchase_discount_amount : $purchase->purchase_discount_rate }}" readonly>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-end">
                                            Total Discount
                                        </td>
                                        <td>
                                            <input type="text" name="total_discount_amount" id="total_discount_amount" class="form-control total_discount_amount" value="{{$payment->discount_amount ?? 0 }}" readonly>
                                        </td>
                                    </tr>
                                    <tr>
                                        <!-- Invoice Tax -->
                                        @php
                                        $tax_ids = json_decode($purchase->purchase_tax_type);
                                        $selected_tax = App\Models\Tax::whereIn('id', $tax_ids)->get();
                                        $unselected_tax = App\Models\Tax::whereNotIn('id', $tax_ids)->get();
                                        @endphp
                                        <td colspan="5" class="text-end">TAX</td>
                                        <td>
                                            <select name="total_taxes[]" multiple="multiple" id="total_taxes" class="form-group form-select">
                                                <option value="TaxFree">Tax Free</option>
                                                @foreach ($selected_tax as $key=>$value)
                                                <option value="{{$value->id}}" data-rate="{{$value->rate}}" selected>{{$value->name }}</option>
                                                @endforeach
                                                @foreach ($unselected_tax as $key=>$value)
                                                <option value="{{$value->id}}" data-rate="{{$value->rate}}">{{$value->name }}</option>
                                                @endforeach
                                            </select>
                                            <input type="text" name="tax_value" id="tax_value" class="form-control discount_show" value="{{$payment->total_tax_amount ?? 0}}" readonly>
                                            <input type="text" name="purchase_tax_amount" id="purchase_tax_amount" class="form-control " value="{{ $purchase->purchase_tax_amount ?? 0}}" hidden>
                                        </td>
                                    </tr>
                                    <tr>
                                        <!-- Additional Fee -->
                                        @php
                                        $additional_fee_ids = json_decode($purchase->additional_charge_type);
                                        $selected_additional_fee = App\Models\AdditionalFee::whereIn('id', $additional_fee_ids)->get();
                                        $unselected_additional_fee = App\Models\AdditionalFee::whereNotIn('id', $additional_fee_ids)->get();
                                        @endphp
                                        <td colspan="5" class="text-end">Additioanl Fee</td>
                                        <td>
                                            <select name="total_additional_fees_type[]" multiple="multiple" id="total_additional_fees_type" class="form-group form-select">
                                                <option value="noFee">No Fee</option>
                                                @foreach ($selected_additional_fee as $key=>$value)
                                                <option value="{{$value->id}}" data-amount="{{$value->amount}}" selected>{{$value->name }}</option>
                                                @endforeach
                                                @foreach ($unselected_additional_fee as $key=>$value)
                                                <option value="{{$value->id}}" data-amount="{{$value->amount}}">{{$value->name }}</option>
                                                @endforeach
                                            </select>
                                            <input type="text" name="total_additional_fees_amount" id="total_additional_fees_amount" class="form-control" value="{{$purchase->additional_charge_amount ?? 0}}" readonly>
                                        </td>
                                    </tr>
                                    <tr>

                                        <td colspan="5" class="text-end"> Total</td>
                                        <td>
                                            <input type="text" name="estimated_amount" value="{{$payment->total_amount - ($payment->total_tax_amount + $payment->total_additional_charge_amount )}}" id="estimated_amount" class="form-control estimated_amount" readonly style="background-color: #ddd;">
                                        </td>

                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-end">Grand Total</td>
                                        <td>
                                            <input type="text" name="total" value="{{$payment->total_amount ?? 0}}" id="total" class="form-control total" readonly style="background-color: #ddd;">
                                        </td>
                                    </tr>
                                    <tr>
                                        <!-- <td class="text-start">Total Profit Code</td> -->
                                        <td colspan="5" class="text-end">
                                            <div class="form-group">
                                                <select name="paid_status" id="paid_status" class="form-select" style="float: right; width:30%">
                                                    <option value="full-due" {{ $payment->paid_status ==='full-due' ?'selected':'' }}>Select Paid Status</option>
                                                    <option value="full-due" {{ $payment->paid_status === 'full-due' ?'selected':'' }}>Full Due</option>
                                                    <option value="full-paid" {{ $payment->paid_status === 'full-paid'?'selected':'' }}>Full Paid</option>
                                                    <option value="partial-paid" {{ $payment->paid_status === 'partial-paid'?'selected':'' }}>Partial Paid</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="text" name="paid_amount" class="form-control paid_amount" id="paid_amount" readonly placeholder="Paid" value="{{$payment->paid_amount ?? 0}}" autocomplete="off">
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="5" class="text-end">Due</td>
                                        <td>
                                            <input type="text" name="due_amount" class="form-control due_amount" id="due_amount" style="background-color: #e7b5b5;" value="{{$payment->due_amount ?? 0}}" readonly>
                                        </td>
                                    </tr>

                                </tfoot>
                            </table><br>

                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <textarea name="description" id="description" class="form-control" placeholder="Write Description Here..."></textarea>
                                </div>
                            </div> <br>

                            <div class="form-group">
                                <button type="submit" name="saveBtn" value="0" class="btn btn-success" id="updateButton"><i class="fas fa-check-circle"></i> Update</button>
                                <button type="submit" name="saveBtn" value="2" class="btn btn-primary" id="saveAndPrintPDFButton"><i class="fas fa-print"></i> Print Purchase</button>
                            </div>
                        </div> <!-- End card-body -->
                    </form>
                </div>
            </div> <!-- end col -->
        </div>
    </div>
    <!-- Product row insert in invoice -->
    <script id="document-template" type="text/x-handlebars-template">
        <tr class="delete_add_more_item" id="delete_add_more_item">
            <input type="hidden" name="date" value="@{{date}}">
            <input type="hidden" name="purchase_no" value="@{{purchase_no}}">
            <input type="hidden" name="purchase_details_id[]" value="0">
            <input type="hidden" name="category_id[]" value="@{{category_id}}">
            <input type="hidden" name="product_size_id[]" value="@{{product_size_id}}">
            <input type="hidden" name="size_name[]" value="@{{size_name}}">
            <td>
                @{{ product_name }} (@{{ size_name }}) @{{brand_name}}
            </td>
            <td>
                <input type="number"  min="1"  class="form-control buying_qty text-right" name="buying_qty[]" id="buying_qty" value="0"> 
                <input type="text" class="form-control stock_quantity text-right" name="stock_quantity" value="@{{quantity}}" readonly> 
            </td>
            <td>
                <input type="text" class="form-control product_buying_price text-right" name="product_buying_price[]" value="@{{product_buying_price}}" > 
                <input type="text" class="form-control unit_price text-right" name="unit_price[]" value="@{{product_price}}" readonly> 
            </td>
            <!-- <td colspan="2">
                <input type="text" class="form-control unit_price_code text-right" name="product_price_code" value="@{{product_price_code}}" readonly> 
            </td> -->
            <!-- VAT  -->
            <td class="discount-row">
                <select name="product_tax[@{{product_size_id}}][]" multiple="multiple" class="form-group form-select product_tax">
                    <option value="TaxFree">VAT Free</option>
                    @{{#each tax}}
                        <option value="@{{this.id}}" data-rate="@{{this.rate}}" >@{{this.name}} </option>
                    @{{/each}}
                </select>
            </td>
            <!-- Discount  -->
            <td class="discount-row">
                    <div class="form-check form-switch">
                        <input class="form-check-input discount_per_product" type="checkbox">
                        <label class="form-check-label discount_label" for="flexSwitchCheckDefault">Fixed</label>
                    </div>
                    <input type="text" class="form-control discount_rate text-right" id="discount_rate" name="discount_rate[]" value="@{{product_discount}}" autocomplete="off">
                    <input type="text" class="form-control discount_amount_per_product text-right" id="discount_amount_per_product" name="discount_amount_per_product[]" readonly >
            </td>
            
        <td>
            <input type="text" class="form-control selling_price text-right" id="selling_price" name="selling_price[]" value="0" readonly hidden>
            <input type="text" class="form-control buying_price text-right" id="buying_price" name="buying_price[]" value="0" readonly>
            <input type="text" class="form-control product_tax_amount text-right" id="" name="product_tax_amount[]" value="0" readonly>
            <input type="text" class="form-control product_price_for_tax text-right" id="" name="product_price_for_tax[]" value="0" readonly hidden>
        </td>

        <td style="width:fit-content;">
            <i class="btn btn-danger btn-sm fas fa-window-close removeeventmore"></i>
        </td>
        </tr>
    </script>

    <!--Select2 Start -->
    <script>
        function initializeSelect2() {
            $('.product_tax').select2({
                placeholder: "Select Tax",
                allowClear: true,
                width: '100%'
            });
        }
        $(document).ready(function() {
            // Initialize Select2
            $('#total_taxes').select2();
            initializeSelect2();
            $('#total_additional_fees_type').select2();
            // $('#supplier_id').select2();
        });
    </script>
    <!-- Select2 End-->
    <!-- Show Confirm Msg -->
    <script>
        function confirmAction(event) {
            // Get the clicked button
            const button = event.submitter;
            const action = button.innerText.trim(); // Get button label text

            const confirmed = confirm(`Are you sure you want to perform "${action}"?`);
            if (!confirmed) {
                event.preventDefault(); // Stop form submission
                return false;
            }
            return true;
        }
    </script>
    <!-- To enable Save and Save & Print invoice Btn START-->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const saveAndPrintPDFButton = document.getElementById('saveAndPrintPDFButton');
            const updateButton = document.getElementById('updateButton');
            const newSupplierForm = document.querySelector('.new_supplier');

            function toggleUI(selectedValue) {
                // Default: disable everything and hide form
                saveAndPrintPDFButton.disabled = true;
                updateButton.disabled = true;
                newSupplierForm.style.display = 'none';
                // Enable buttons if valid Supplier is selected
                if (selectedValue !== '-2') {
                    saveAndPrintPDFButton.disabled = false;
                    updateButton.disabled = false;
                }

                const nameField = document.getElementById('name');
                const mobileField = document.getElementById('mobile_no');
                const emailField = document.getElementById('email');
                const addressField = document.getElementById('address');

                if (selectedValue === '0') {
                    newSupplierForm.style.display = 'flex'; // show new Supplier form
                    nameField.required = false;
                    mobileField.required = true;
                    emailField.required = false;
                    addressField.required = false;
                } else {
                    nameField.required = false;
                    mobileField.required = false;
                    emailField.required = false;
                    addressField.required = false;
                }
            }

            // Initialize Select2
            $(document).ready(function() {
                $('#supplier_id').select2();

                // Trigger toggleUI on select2 change
                $('#supplier_id').on('change', function() {
                    const selectedValue = $(this).val();
                    toggleUI(selectedValue);
                });

                // Call toggleUI initially
                toggleUI($('#supplier_id').val());
            });
        });
    </script>
    <!-- To enable Save and Save & Print invoice Btn END-->
    <!-- Add Data Using Barcode and select START-->
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function() {
            let barcodeInput = document.getElementById("search-product-or-barcode-input");

            barcodeInput.addEventListener("keypress", function(event) {

                if (event.key === "Enter") { // Barcode scanner sends an "Enter" key after scanning
                    let barcode = barcodeInput.value.trim();
                    if (barcode !== "") {
                        fetchProductDetails(barcode);
                    }
                    barcodeInput.value = ""; // Clear input field
                }
            });
            $(document).ready(function() {
                $('#product_barcode').select2();
                // Trigger toggleUI on select2 change
                $('#product_barcode').on('change', function() {
                    const selectedValue = $(this).val();
                    fetchProductDetails(selectedValue);
                });
            });

            function fetchProductDetails(barcode) {
                var barcode = barcode;
                $.ajax({
                    url: "{{ route('get-product-by-barcode') }}",
                    type: "GET",
                    data: {
                        barcode: barcode
                    },
                    success: function(data) {
                        product_list = data.product;
                        brand_name = data.brand;
                        size_name = data.size;
                        tax = data.tax;
                        var date = $('#date').val();
                        var purchase_no = $('#purchase_no').val();
                        var product_id = product_list.product.id;
                        var product_size_id = product_list.id;
                        var product_name = product_list.product.name;
                        var category_id = product_list.product.category_id;
                        var discounted_price = product_list.discounted_price;
                        var product_price = discounted_price && discounted_price !== '' ? discounted_price : product_list.selling_price;
                        var product_price_code = product_list.buying_price_code;
                        var quantity = product_list.quantity;
                        var product_discount = 0;

                        if (date == '') {
                            $.notify("Date is Required", {
                                globalPosition: 'top right',
                                className: 'error'
                            });
                            return false;
                        }
                        if (product_size_id == '') {
                            $.notify("Product Field is Required", {
                                globalPosition: 'top right',
                                className: 'error'
                            });
                            return false;
                        }
                        var source = $("#document-template").html();
                        var tamplate = Handlebars.compile(source);
                        var data = {
                            date: date,
                            purchase_no: purchase_no,
                            product_id: product_id,
                            product_size_id: product_size_id,
                            product_name: product_name,
                            category_id: category_id,
                            brand_name: brand_name,
                            size_name: size_name,
                            product_price: product_price ? product_price : 0,
                            product_buying_price: product_list.buying_price ? product_list.buying_price : 0,
                            product_price_code: product_price_code,
                            quantity: quantity,
                            tax: tax,
                            product_discount: product_discount,
                        };
                        var html = tamplate(data);
                        $("#addRow").append(html);
                        $("#addPreviewRow").append(html);
                        $('#search-product-or-barcode-input').val('');
                        // Enable select2 product_tax 
                        initializeSelect2();
                    }
                })
            }
        });
    </script>
    <!-- Add Data Using Barcode and select END-->
    <!-- All calculations Start-->
    <script>
        $(document).ready(function() {
            // Remove product from invoice
            $(document).on("click", ".removeeventmore", function() {
                $(this).closest(".delete_add_more_item").remove();
                updateInvoiceCalculations();
            });

            // Event listener for price, quantity, discount, and commission changes
            $(document).on('keyup click change', '.product_buying_price, .buying_qty, .discount_rate, .discount_per_product, .product_tax', function() {
                updateProductRow($(this).closest("tr"));
                updateInvoiceCalculations();
            });

            // Handle tax calculation updates
            $('#total_taxes, #estimated_amount, #total_additional_fees_type').on('change', updateInvoiceCalculations);

            // Handle additional fees update
            $('#total_additional_fees_type').on('change', additionalFeeCalculations);

            // Paid status change handler
            $(document).on('change', '#paid_status', function() {
                adjustPaidAmount();
                updateInvoiceCalculations();
            });

            // Discount status change handler
            $(document).on('change', '#discount_status', updateInvoiceCalculations);

            // General update triggers for key financial fields
            $(document).on('keyup', '#discount_amount, #paid_amount, #due_amount, #paid_status, .buying_qty, #discount_status', updateInvoiceCalculations);

            /**
             * Update individual product row calculations
             */
            function updateProductRow(row) {
                let unitPrice = parseFloat(row.find("input.unit_price").val()) || 0;
                let buyingPrice = parseFloat(row.find("input.product_buying_price").val()) || 0;
                let quantity = parseFloat(row.find("input.buying_qty").val()) || 0;
                let discount = parseFloat(row.find("input.discount_rate").val()) || 0;
                let isPercentageDiscount = row.find('.discount_per_product').is(':checked');
                let discountLabel = row.find('.discount_label');
                let totalDiscount = 0;

                // Calculate discount per product
                if (!isNaN(discount)) {
                    let baseValue = buyingPrice * quantity;
                    if (isPercentageDiscount) {
                        discountLabel.text('% Percent');
                        totalDiscount = (discount / 100) * baseValue;
                    } else {
                        discountLabel.text('Fixed');
                        totalDiscount = discount;
                    }
                }

                // Set discount total
                row.find("input.discount_amount_per_product").val(totalDiscount.toFixed(2));

                // Calculate total price after discount
                let totalSellingPrice = (buyingPrice * quantity) - totalDiscount;
                let totalBuyingPrice = (buyingPrice * quantity) - totalDiscount;
                row.find("input.selling_price").val(totalSellingPrice.toFixed(2));
                row.find("input.buying_price").val(totalBuyingPrice.toFixed(2));

                // Calculate tax per product
                let totalTax = 0;
                row.find('select.product_tax option:selected').each(function() {
                    totalTax += parseFloat($(this).data('rate')) || 0;
                });

                row.find("input.product_price_for_tax").val((buyingPrice * quantity).toFixed(2));
                row.find("input.product_tax_amount").val((totalTax * buyingPrice * quantity).toFixed(2));
            }

            /**
             * Calculate total tax, additional fees, and update invoice amounts
             */
            function updateInvoiceCalculations() {
                calculateTax();
                additionalFeeCalculations();
                calculateTotalAmount();
                adjustPaidAmount();
            }

            /**
             * Calculate total tax based on selected options
             */
            function calculateTax() {
                let totalTaxRate = 0;
                $('#total_taxes option:selected').each(function() {
                    totalTaxRate += parseFloat($(this).data('rate')) || 0;
                });

                let productPriceSum = sumValues(".product_price_for_tax");
                let productTaxSum = sumValues(".product_tax_amount");

                let totalTaxValue = productTaxSum + (productPriceSum * totalTaxRate);
                $('#tax_value').val(totalTaxValue.toFixed(2));
                $('#invoice_tax_amount').val((productPriceSum * totalTaxRate).toFixed(2));
            }

            /**
             * Calculate additional fees from selected options
             */
            function additionalFeeCalculations() {
                let totalAdditionalFees = sumValues('#total_additional_fees_type option:selected', 'data-amount');
                $('#total_additional_fees_amount').val(totalAdditionalFees.toFixed(2));
            }

            /**
             * Calculate total invoice amount
             */
            function calculateTotalAmount() {
                let totalDiscount = sumValues(".discount_amount_per_product");
                let totalSellingPrice = sumValues(".selling_price");
                let totalBuyingPrice = sumValues(".buying_price");

                let discountAmount = parseFloat($('#discount_amount').val()) || 0;
                let discountStatus = $('#discount_status').val();

                if (discountAmount) {
                    if (discountStatus === 'percentage_discount') {
                        let discount = (discountAmount / 100) * totalBuyingPrice;
                        totalBuyingPrice -= discount;
                        totalDiscount += discount;
                        $('#discount_show').val(discount.toFixed(2));
                    } else {
                        let discount = (discountAmount * 100) / totalBuyingPrice;
                        totalBuyingPrice -= discountAmount;
                        totalDiscount += discountAmount;
                        $('#discount_show').val(discount.toFixed(2));
                    }
                }

                $('#total_discount_amount').val(totalDiscount.toFixed(2));
                $('#secret_grand_total_price_code').val(totalBuyingPrice.toFixed(0));
                $('#estimated_amount').val(totalBuyingPrice.toFixed(2));
                // $('#estimated_amount').val(totalSellingPrice.toFixed(2));

                adjustPaidAmount();
            }

            /**
             * Adjust paid amount based on status
             */
            function adjustPaidAmount() {
                let paidStatus = $('#paid_status').val();
                if (paidStatus === 'full-due') {
                    $('#paid_amount').val(0).attr('readonly', true);
                } else if (paidStatus === 'full-paid') {
                    $('#paid_amount').val($('#total').val()).attr('readonly', true);
                } else {
                    $('#paid_amount').attr('readonly', false);
                }

                let paidAmount = parseFloat($('#paid_amount').val()) || 0;
                let dueAmount = (parseFloat($('#total').val()) || 0) - paidAmount;
                $('#due_amount').val(dueAmount.toFixed(2));

                let total = sumValues('#total_additional_fees_amount, #tax_value, #estimated_amount');
                $('#total').val(total.toFixed(2));
            }

            /**
             * Helper function to sum values from multiple elements
             * @param {string} selector - The selector for the elements to sum
             * @param {string} [attribute] - Optional attribute to extract value from (e.g., 'data-amount')
             * @returns {number} - The total sum
             */
            function sumValues(selector, attribute) {
                let sum = 0;
                $(selector).each(function() {
                    let value = attribute ? parseFloat($(this).attr(attribute)) : parseFloat($(this).val());
                    if (!isNaN(value)) sum += value;
                });
                return sum;
            }
        });
    </script>
    <!-- All calculations End-->

    <!-- Add Product END -->
    <!-- Loading Category as from Brand -->
    <script type="text/javascript">
        $(function() {
            $(document).on('change', '#brand_id', function() {
                var brand_id = $(this).val();
                $.ajax({
                    url: "{{ route('get-category-by-brand') }}",
                    type: "GET",
                    data: {
                        brand_id: brand_id
                    },
                    success: function(data) {
                        product_list = data;
                        var html = '<option value="">Select Brand</option>';
                        $.each(data, function(key, v) {
                            html += '<option value=" ' + v.id + ' "> ' + v.name + '</option>';
                        });
                        $('#category_id').html(html);
                    }
                })
            });
        });
    </script>
    <!-- Loading Product as from Category -->
    <script type="text/javascript">
        $(function() {
            $(document).on('change', '#category_id', function() {
                var category_id = $(this).val();
                $.ajax({
                    url: "{{ route('get-product') }}",
                    type: "GET",
                    data: {
                        category_id: category_id
                    },
                    success: function(data) {
                        product_list = data;
                        var html = '<option value="">Select Product</option>';
                        $.each(data, function(key, v) {
                            html += '<option value=" ' + v.id + ' "> ' + v.name + '</option>';
                        });
                        $('#product_id').html(html);
                    }
                })
            });
        });
    </script>

    <!-- Display Product wise stock  -->
    <script type="text/javascript">
        $(function() {
            $(document).on('change', '#product_id', function() {
                var product_id = $(this).val();
                $.ajax({
                    url: "{{ route('check-product-stock') }}",
                    type: "GET",
                    data: {
                        product_id: product_id
                    },
                    success: function(data) {
                        $('#current_stock_qty').val(data);
                    }
                });
            });
        });
    </script>

    <!-- New Supplier Insert form select New Supplier Option -->
    <script type="text/javascript">
        $(document).on('change', '#supplier_id', function() {
            var supplier_id = $(this).val();
            if (supplier_id == '0') {
                $('.new_supplier').show();
            } else {
                $('.new_supplier').hide();
            }
        });
    </script>
    <!-- Search Data Table -->
    <script type="text/javascript">
        const searchDataTable = () => {
            let filterData = document.getElementById('search-product-or-barcode-input').value.toUpperCase();
            let productDataTable = document.getElementById('productDataTable');
            let tr = productDataTable.getElementsByTagName('tr');
            for (var i = 0; i < tr.length; i++) {
                let td = tr[i].getElementsByTagName('td');
                if (td.length > 0) {
                    if (td.length > 0) { // Check if the row has any td elements
                        let productCode = td[0].textContent || td[0].innerText;
                        let productName = td[1].textContent || td[1].innerText;

                        if (productName.toUpperCase().indexOf(filterData) > -1 || productCode.toUpperCase().indexOf(filterData) > -1) {
                            tr[i].style.display = '';
                        } else {
                            tr[i].style.display = 'none';
                        }
                    }
                }

            }
        }
    </script>
    @endsection