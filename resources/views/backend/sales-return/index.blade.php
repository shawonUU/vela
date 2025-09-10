@php
$org = App\Models\OrgDetails::first();
@endphp
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $org->org_name_en??'N/A' }} | POS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard " name="description" />
    <meta content="Efat khan" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset($org->logo) }}">
    <!-- select2 button for purchase form -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- jquery.vectormap css -->
    <link href="{{ asset('backend/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css') }}" rel="stylesheet" type="text/css" />
    <!-- DataTables -->
    <link href="{{ asset('backend/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('backend/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('backend/assets/libs/datatables.net-select-bs4/css/select.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
    <style>
        div.dataTables_wrapper div.dataTables_filter input {
            width: 400px !important;
        }
    </style>
    <!-- Responsive datatable examples -->
    <link href="{{ asset('backend/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Bootstrap Css -->
    <link href="{{ asset('backend/assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('backend/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('backend/assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <!-- Toaster CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
    <!-- summer-note -->
    <link rel="stylesheet" href="{{ asset('/backend/assets/libs/summer-note/summernote.min.css')}}">
    <!-- Custom Css-->
    <link href="{{ asset('backend/assets/css/custom.css') }}" id="app-style" rel="stylesheet" type="text/css" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @yield('admin_custom_css')
</head>
<!-- <body data-topbar="dark" class="sidebar-enable vertical-collpsed"> -->

<body data-topbar="dark">
    <!-- <body data-layout="horizontal" data-topbar="dark"> -->
    <!-- Begin page -->
    <div id="layout-wrapper">
        @include('admin.body.header')
        <div>
            <div class="page-content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0">Sales Return</h4>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);"></a></li>
                                        <li class="breadcrumb-item active"><a href="{{route('sales.return.all')}}">Back</a></li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3 col-s-12">
                            <div class="card p-3 m-0">
                                <h4 class="card-title mb-3">Exchange Products </h4>
                                <div id="datatable_filter" class="dataTables_filter position-relative pb-2">
                                    <!-- Search /Barcode input START-->
                                    <input type="search" id="search-product-or-barcode-input" onkeyup="searchDataTable()" class="form-control" placeholder="Search Product Name / Barcode">
                                    <!-- Search /Barcode input END-->
                                </div>
                            </div>
                            <div class="card " style="max-height: 561px; overflow:scroll">
                                <div class="card-body">
                                    <table id="productDataTable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                            <tr>
                                                <th width="5%">BC</th>
                                                <th width="90%">Name</th>
                                                <!-- <th></th> -->
                                                <th width="5%">Add</th>
                                            </tr>
                                        </thead>
                                        <tbody id="productDataTable">
                                            @foreach($products as $key => $item)
                                            <tr>
                                                <td style="max-width: 20%;"> {{ ($item->barcode !=null? $item->barcode:'Null')}} </td>
                                                <!-- <td class="d-inline-block text-truncate " style="max-width: 100px;"> {{ $item->name }} </td> -->
                                                <td class="max-width-50">
                                                    {{ $item->product->name.' ('.$item->size->name.') (Qty:'.$item->quantity.')' }}
                                                    {{ $item->product->brand_id !=0 ? "({$item->product->brand->name})" : '' }}
                                                </td>
                                                <td style="max-width: 10%;">
                                                    <!-- <a href="{{route('product.edit',$item->id)}}" class="btn btn-info sm addProduct" title="Add Product">  -->
                                                    <!-- <i id="productId" value="{{$item->id}}" class="btn btn-info sm fas fa-plus-circle addProduct"></i> -->
                                                    <input type="text" class="brandName" value="{{ $item->product->brand_id !=0 ? "{$item->product->brand->name}" : '' }}" hidden=true>
                                                    <input type="text" class="barcode" value="{{$item->barcode}}" disabled=true hidden=true>
                                                    <button type="submit" class="btn btn-info sm fas fa-plus-circle addProduct"></button>
                                                    <!-- </a> -->
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-9 col-s-12">
                            <div class="card">
                                <!-- <form method="post" action="{{ route('invoice.store') }}" onsubmit="return confirmAction(event)"> -->
                                <form method="post" action="{{ route('sales.return.store') }}" id="postForm">
                                    @csrf
                                    <div class="card-body pb-0">
                                        <div class="row pb-2">
                                            <!-- Customer Name -->
                                            <div class="form-group col-md-3" hidden>
                                                <label for="example-text-input" class="col-form-label">Customer Name</label>
                                                <input type="text" name="name" id="name" class="form-control" placeholder="Customer Name" autocomplete="off">
                                            </div>

                                            <!-- Mobile with Autocomplete -->
                                            <div class="form-group col-md-8 position-relative" autocapitalize="off">
                                                <label for="example-text-input" class="col-form-label">Search by Name or Mobile</label>
                                                <input type="text" name="mobile_no" id="mobile_no" class="form-control" placeholder="Customer Mobile No / Name search here."                                          autocomplete="off"
                                                spellcheck="false"
                                                autocapitalize="off"
                                                autocorrect="off">
                                                <!-- Custom Dropdown -->
                                                <div id="mobile_suggestions" class="list-group position-absolute w-100" style="z-index: 1000; display: none; max-height: 200px; overflow-y: auto;">
                                                    <!-- Suggestions will appear here -->
                                                </div>
                                            </div>
                                            <!-- {{-- Start Date --}}
                                            <div class="col-md-2">
                                                <label for="example-text-input" class="col-form-label">Start Date</label>
                                                <input type="date" id="customStartDate" name="customStartDate" class="form-control"
                                                    value="{{ $show_start_date ?? \Carbon\Carbon::now()->format('Y-m-d') }}" required>
                                            </div>

                                            {{-- End Date --}}
                                            <div class="col-md-2">
                                                <label for="example-text-input" class="col-form-label">End Date</label>
                                                <input type="date" id="customEndDate" name="customEndDate" class="form-control"
                                                    value="{{ $show_end_date ?? \Carbon\Carbon::now()->format('Y-m-d') }}" required>
                                            </div>

                                            {{-- Apply Button --}}
                                            <div class="col-md-1 ">
                                                <label for="example-text-input" class="col-form-label">Apply</label>
                                                <button type="button" class="btn btn-dark w-100" onclick="updateDashboardWithCustomRange()">Apply</button>
                                            </div> -->

                                            <div class="col-md-2 text-left">
                                                <label for="example-text-input" class="col-form-label">Invoice No.</label>
                                                <input class="form-control" type="text" name="invoice_no" value="{{ $invoice_no }}" id="invoice_no" readonly style="background-color:#ddd; ">
                                            </div>
                                            <div class="col-md-2">
                                                <label for="example-text-input" class="col-form-label" style="text-align: right;">Date:</label>
                                                <input class="form-control example-date-input" name="date" value="{{ $date }}" type="date" id="date">
                                            </div>

                                            <!-- <div class="form-group col-md-1" style="text-align: right; padding-top: 42px;">
                                    <button type="submit" class="btn btn-info sm fas fa-plus-circle addProduct"></button>
                                </div> -->
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-12 position-relative">
                                                <label for="product_dropdown" class="col-form-label">Purchased Products</label>
                                                <div class="input-group">
                                                    <input type="text" id="product_dropdown" class="form-control" placeholder="Select Product" autocomplete="off">
                                                    <div class="input-group-append">
                                                        <button type="button" id="product_suggestions_show_or_hide" class="btn btn-outline-secondary" title="Show/Hide Suggestions">
                                                            <i class="fas fa-eye-slash"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                                <!-- Suggestions Dropdown -->
                                                <div id="product_suggestions"
                                                    class="list-group position-absolute w-100 shadow-sm border rounded bg-white"
                                                    style="z-index: 1000; display: none; max-height: 200px; overflow-y: auto;">
                                                    <!-- Product suggestions will appear here -->
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="card-body">
                                        <table class="table-sm table-bordered" width="100%" style="border-color: #ddd;">
                                            <thead>
                                                <tr>
                                                    <th width="5%">S/N</th>
                                                    <th width="20%">Product Name </th>
                                                    <th width="15%">PSC/Stock</th>
                                                    <th width="20%">Unit Price /P.Code</th>
                                                    <th width="20%">Discount(%)</th>
                                                    <th width="20%">Price</th>
                                                </tr>
                                            </thead>
                                            <tbody id="addRowSalesReturn" class="addRowSalesReturn">
                                            </tbody>
                                            <tbody id="addRow" class="addRow">
                                            </tbody>
                                            <tbody>
                                                <tr>
                                                    <td colspan="5" class="text-end">
                                                        <div class="form-group">
                                                            <select name="discount_status" id="discount_status" class="form-select" style="float: right; width:31%">
                                                                <option value="percentage_discount">Percentage Discount(%)</option>
                                                                <option value="fixed_discount">Fixed Discount</option>
                                                            </select>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="discount_amount" id="discount_amount" class="form-control discount_amount" value="0" autocomplete="off">
                                                        <input type="text" name="discount_show" id="discount_show" class="form-control discount_show" value="0" readonly>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td colspan="5" class="text-end">
                                                        Total Discount
                                                    </td>
                                                    <td>
                                                        <input type="text" name="total_discount_amount" id="total_discount_amount" class="form-control total_discount_amount" value="0" readonly>
                                                    </td>
                                                </tr>

                                                <tr hidden>
                                                    <!-- <td colspan="1" class="text-start" >Total Buying Price Code</td>
                                        <td colspan="2" >
                                            <input type="text" name="secret_grand_total_price_code" value="0" id="secret_grand_total_price_code" class="form-control secret_grand_total_price_code" readonly style="background-color: #ddd;">
                                        </td> -->
                                                    <td colspan="5" class="text-end"> Total</td>
                                                    <td>
                                                        <input type="text" name="estimated_amount" value="0" id="estimated_amount" class="form-control estimated_amount" readonly style="background-color: #ddd;">
                                                    </td>
                                                </tr>
                                                <!-- VAT and additional fees -->
                                                {{--
                                    <tr>
                                        <td colspan="5" class="text-end">VAT</td>
                                        <td>
                                            <select name="total_taxes[]" multiple="multiple" id="total_taxes" class="form-group form-select">
                                                <!-- <option value="TaxFree">VAT Free</option> -->
                                                @foreach ($tax as $key=>$value)
                                                <option value="{{$value->id}}" data-rate="{{$value->rate}}">{{$value->name }}</option>
                                                @endforeach
                                                </select>
                                                <input type="text" name="tax_value" id="tax_value" class="form-control discount_show" value="0" readonly>
                                                <input type="text" name="invoice_tax_amount" id="invoice_tax_amount" class="form-control " value="0" hidden>
                                                </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="5" class="text-end">Additional Fee</td>
                                                    <td>
                                                        <select name="total_additional_fees_type[]" multiple="multiple" id="total_additional_fees_type" class="form-group form-select">
                                                            <option value="noFee">No Fee</option>
                                                            @foreach ($additional_fees as $key=>$value)
                                                            <option value="{{$value->id}}" data-amount="{{$value->amount}}">{{$value->name }} </option>
                                                            @endforeach
                                                        </select>
                                                        <input type="text" name="total_additional_fees_amount" id="total_additional_fees_amount" class="form-control" value="0" readonly>
                                                    </td>
                                                </tr>
                                                --}}
                                                <tr>
                                                    <td colspan="5" class="text-end align-middle">
                                                        <label for="round_amount" class="fw-bold mb-1">Total</label>
                                                        <div class="d-flex justify-content-end align-items-center gap-2">
                                                            <label for="round_amount" class="form-label mb-0">Round:</label>
                                                            <input type="text" name="round_amount" value="0" id="round_amount" class="form-control form-control-sm w-25 text-end" style="max-width: 100px;">
                                                        </div>
                                                    </td>
                                                    <td class="align-middle">
                                                        <input type="text" name="total" value="0" id="total" class="form-control form-control text-start bg-light" readonly>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="5" class="text-end">
                                                        Total Return
                                                    </td>
                                                    <td>
                                                        <input type="text" name="total_return_amount" id="total_return_amount" class="form-control total_return_amount" value="0" readonly>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="5" class="text-end">
                                                        Grand Total
                                                    </td>
                                                    <td>
                                                        <input type="text" name="grand_total" id="grand_total" class="form-control grand_total" value="0" readonly>
                                                    </td>
                                                </tr>

                                                {{--
                                        <tr>
                                            <td colspan="4" class="text-start">Total Profit Code</td>
=======
                                        <td colspan="5" class="text-end">Additional Fee</td>
                                        <td>
                                            <select name="total_additional_fees_type[]" multiple="multiple" id="total_additional_fees_type" class="form-group form-select">
                                                <option value="noFee">No Fee</option>
                                                @foreach ($additional_fees as $key=>$value)
                                                <option value="{{$value->id}}" data-amount="{{$value->amount}}">{{$value->name }} </option>
                                                @endforeach
                                                </select>
                                                <input type="text" name="total_additional_fees_amount" id="total_additional_fees_amount" class="form-control" value="0" readonly>
                                                </td>
                                                </tr>
                                                --}}
                                                <tr>
                                                    <td colspan="5" class="text-start">
                                                        <div class="row g-2" style="padding-right: 100px;">
                                                            {{--
                                                            <!-- Paid Status Dropdown -->
                                                            <div class="col-md-4 offset-md-8 text-end">
                                                                <label for="paid_status" class="form-label fw-bold">Paid Status</label>
                                                                <select name="paid_status" id="paid_status" class="form-select">
                                                                    <option value="full-paid">Full Paid</option>
                                                                    <option value="full-due">Full Due</option>
                                                                    <option value="partial-paid">Partial Paid</option>
                                                                </select>
                                                            </div>
                                                            --}}
                                                            <!-- Card Payments -->
                                                            <!-- MFS Payments -->
                                                            <div class="col-md-3">
                                                                <label for="bkash" class="form-label">bkash</label>
                                                                <input type="text" name="bkash" id="bkash" class="form-control bkash" value="0" placeholder="bkash" autocomplete="off">
                                                            </div>

                                                            <div class="col-md-3">
                                                                <label for="nagad" class="form-label">nagad</label>
                                                                <input type="text" name="nagad" id="nagad" class="form-control nagad" value="0" placeholder="nagad" autocomplete="off">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="visa_card" class="form-label">Visa Card</label>
                                                                <input type="text" name="visa_card" id="visa_card" class="form-control visa_card" value="0" placeholder="Visa" autocomplete="off">
                                                            </div>

                                                            <div class="col-md-3">
                                                                <label for="master_card" class="form-label">Master Card</label>
                                                                <input type="text" name="master_card" id="master_card" class="form-control master_card" value="0" placeholder="MasterCard" autocomplete="off">
                                                            </div>


                                                            <div class="col-md-3">
                                                                <label for="rocket" class="form-label">rocket</label>
                                                                <input type="text" name="rocket" id="rocket" class="form-control rocket" value="0" placeholder="rocket" autocomplete="off">
                                                            </div>

                                                            <div class="col-md-3">
                                                                <label for="upay" class="form-label">upay</label>
                                                                <input type="text" name="upay" id="upay" class="form-control upay" value="0" placeholder="upay" autocomplete="off">
                                                            </div>

                                                            <div class="col-md-3">
                                                                <label for="surecash" class="form-label">surecash</label>
                                                                <input type="text" name="surecash" id="surecash" class="form-control surecash" value="0" placeholder="surecash" autocomplete="off">
                                                            </div>

                                                            <div class="col-md-3">
                                                                <label for="online" class="form-label">Online</label>
                                                                <input type="text" name="online" id="online" class="form-control online" value="0" placeholder="online" autocomplete="off">
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <!-- Paid Amount -->
                                                    <td>
                                                        <!-- Cash Payment -->
                                                        <label for="cash" class="form-label">Cash</label>
                                                        <input type="text" name="cash" id="cash" class="form-control cash" value="0" placeholder="Cash" autocomplete="off">
                                                        <label for="paid_amount" class="form-label">Paid Amount</label>
                                                        <input type="text" name="paid_amount" id="paid_amount" class="form-control paid_amount" value="0" readonly placeholder="Paid" autocomplete="off">
                                                        <label for="paid_amount" class="form-label">To Pay(+)/ Change(-)</label>
                                                        <input type="text" id="change" name="change" class="form-control" value="0" readonly placeholder="Change" autocomplete="off">
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td colspan="5" class="text-end">Due/ Refund</td>
                                                    <td>
                                                        <input type="text" name="due_amount" class="form-control due_amount" id="due_amount" style="background-color: #e7b5b5;" value="0" readonly>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table><br>
                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <textarea name="description" id="description" class="form-control" placeholder="Write Description Here..."></textarea>
                                            </div>
                                        </div> <br>
                                        <!-- Hidden input to track which button was clicked -->
                                        <input type="hidden" name="saveBtn" id="saveBtn" value="2">
                                        <div class="form-group">
                                            {{--
                                            <button type="button" class="btn btn-info previewBtn" data-value="3" data-action="draft" id="draftButton" disabled>
                                                <i class="fas fa-check-circle"></i> Draft
                                            </button>
                                            <button type="button" class="btn btn-primary previewBtn" data-value="1" data-action="quotation" id="quotationButton" disabled>
                                                <i class="fas fa-check-circle"></i> Quotation
                                            </button>
                                            <button type="button" class="btn btn-primary previewBtn" data-value="4" data-action="challan" id="chalanButton" disabled>
                                                <i class="fas fa-check-circle"></i> Challan
                                            </button>
                                            
                                            <button type="submit" class="btn btn-primary" id="PosPrint">
                                                <i class="fas fa-check-circle"></i> Print
                                            </button>
                                            --}}
                                            <button type="button" class="btn btn-primary previewBtn" data-value="2" data-action="invoice" id="saveAndPrintPDFButton">
                                                <i class="fas fa-eye"></i> Preview & Print
                                            </button>
                                        </div>

                                    </div> <!-- End card-body -->
                            </div>
                            </form>
                        </div> <!-- end col -->
                    </div>
                </div>
                <!-- Preview model start-->
                <style>
                    .modal-400 {
                        max-width: 400px !important;
                        width: 100%;
                    }

                    .modal-body {
                        padding: 0;
                    }

                    #invoicePreviewIframe {
                        width: 100%;
                        height: 600px;
                        border: none;
                    }
                </style>

                <div class="modal fade" id="invoicePreviewModal" tabindex="-1">
                    <div class="modal-dialog modal-400">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Preview</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <iframe id="invoicePreviewIframe"></iframe>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-primary" id="confirmSubmitBtn"><i class="fas fa-print"></i> Print</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Preview model end-->
             <!-- Product row insert in invoice -->
                <script id="document-template" type="text/x-handlebars-template">
                    <tr class="delete_add_more_item" id="delete_add_more_item">
                    <input type="hidden" name="date" value="@{{date}}">
                    <input type="hidden" name="invoice_no" value="@{{invoice_no}}">
                    <input type="hidden" name="category_id[]" value="@{{category_id}}">
                    <input type="hidden" name="product_id[]" value="@{{product_id}}">
                    <input type="hidden" name="product_size_id[]" value="@{{product_size_id}}">
                    <input type="hidden" name="size_name[]" value="@{{size_name}}">
                    <td class="serial-number"></td>
                    <td>
                        <span 
                            title="Buying Price: @{{product_buying_price}}&#10;Profit (10% Discount): @{{profit_10}}&#10;Profit (15% Discount): @{{profit_15}}&#10;Profit (20% Discount): @{{profit_20}}&#10;Max Discount: @{{max_discount}}"
                        >
                            @{{ product_name }} (@{{ size_name }}) @{{brand_name}} <span 
                            style="display: inline-block;
                                    padding: 0.25em 0.6em;
                                    font-size: 75%;
                                    font-weight: 600;
                                    color: #fff;
                                    background-color: #28a745; /* Bootstrap green */
                                    border-radius: 0.25rem;">Exchange</span>
                        </span>
                
                    </td>
                    <td>
                        <input type="number"  min="1" max="@{{quantity}}" class="form-control selling_qty text-right" name="selling_qty[]" id="selling_qty" value="1"> 
                        <input type="text" class="form-control stock_quantity text-right" value="@{{quantity}}" readonly> 
                    </td>
                    <td>
                    <input type="text" class="form-control product_buying_price text-right" name="product_buying_price[]" value="@{{product_buying_price}}" hidden> 
                    <input type="text" class="form-control unit_price text-right" name="unit_price[]" value="@{{product_price}}" readonly> 
                    <input type="text" class="form-control unit_price_code text-right" name="product_price_code" value="@{{product_price_code}}" readonly>
                    </td>
                <!-- <td colspan="2">
                    <input type="text" class="form-control unit_price_code text-right" name="product_price_code" value="@{{product_price_code}}" readonly> 
                </td> -->
                <!-- VAT  -->
                    {{--  
                    <td class="discount-row">
                        <select name="product_tax[@{{product_id}}][]" multiple="multiple" class="form-group form-select product_tax">
                            <option value="TaxFree">VAT Free</option>
                            @{{#each selected_tax}}
                                <option value="@{{this.id}}" data-rate="@{{this.rate}}" selected>@{{this.name}} </option>
                            @{{/each}}
                            @{{#each unselected_tax}}
                                <option value="@{{this.id}}" data-rate="@{{this.rate}}">@{{this.name}}</option>
                            @{{/each}}
                        </select>
                    </td>
                    --}}
                    <!-- Discount  -->
                    <td class="discount-row">
                            <div class="form-check form-switch">
                                <input class="form-check-input discount_per_product" type="checkbox">
                                <label class="form-check-label discount_label" for="flexSwitchCheckDefault">% Percent</label>
                            </div>
                            <input type="text" class="form-control discount_rate text-right" id="discount_rate" name="discount_rate[]" value="@{{product_discount}}" autocomplete="off">
                            <input type="text" class="form-control discount_amount_per_product text-right" id="discount_amount_per_product" name="discount_amount_per_product[]" readonly >
                    </td>
            
                    <td>
                        <input type="text" class="form-control selling_price text-right" id="selling_price" name="selling_price[]" value="0" readonly>
                        <input type="text" class="form-control buying_price text-right" id="buying_price" name="buying_price[]" value="0" hidden>
                        <input type="text" class="form-control product_tax_amount text-right" id="" name="product_tax_amount[]" value="0" readonly hidden>
                        <input type="text" class="form-control product_price_for_tax text-right" id="" name="product_price_for_tax[]" value="0" readonly hidden>
                    </td>

                    <td style="width:fit-content;">
                        <i class="btn btn-danger btn-sm fas fa-window-close removeeventmore"></i>
                    </td>
                    </tr>
    </script>
                <!-- Product row insert in invoice End-->
                <!-- Return product -->
                <script id="sales-return-product" type="text/x-handlebars-template">
                    <tr class="delete_add_more_item" id="delete_add_more_item">
                        <input type="hidden" name="buying_date" value="@{{buying_date}}">
                        <input type="hidden" name="return_date" value="@{{date}}">
                        <input type="hidden" name="return_invoice_id" value="@{{invoice_no}}">
                        <input type="hidden" name="return_buying_invoice_id[]" value="@{{return_buying_invoice_id}}">
                        <input type="hidden" name="return_invoice_detail_id[]" value="@{{return_invoice_detail_id}}">
                    <input type="hidden" name="return_category_id[]" value="@{{return_category_id}}">

                    <input type="hidden" name="return_product_size_id[]" value="@{{return_product_size_id}}">
                   
                    <td class="serial-number"></td>
                    <td>
                        <span 
                            title="Buying Price: @{{return_product_buying_price}}&#10;Profit (10% Discount): @{{profit_10}}&#10;Profit (15% Discount): @{{profit_15}}&#10;Profit (20% Discount): @{{profit_20}}&#10;Max Discount: @{{max_discount}}">
                            @{{ product_name }} (@{{ size_name }}) @{{brand_name}} <span 
                            style="display: inline-block;
                                    padding: 0.25em 0.6em;
                                    font-size: 75%;
                                    font-weight: 600;
                                    color: #fff;
                                    background-color: #dc3545; /* Bootstrap red */
                                    border-radius: 0.25rem;">Return</span>
                        </span>
                    </td>
                    <td>
                        <input type="number"  min="1" max="@{{return_product_quantity}}" class="form-control return_product_quantity text-right" name="return_product_quantity[]" id="return_product_quantity" value="@{{return_product_quantity}}"> 
                        <input type="text" class="form-control return_product_quantity text-right" value="@{{return_product_quantity}}" readonly> 
                    </td>
                    <td>
                    <input type="text" class="form-control return_product_unit_price text-right" name="return_product_unit_price[]" value="@{{return_punit_buying_price}}" hidden> 
                    <input type="text" class="form-control return_unit_price text-right" name="return_unit_price[]" value="@{{return_unit_price}}" readonly> 
                    </td>
                <!-- <td colspan="2">
                    <input type="text" class="form-control unit_price_code text-right" name="product_price_code" value="@{{product_price_code}}" readonly> 
                </td> -->
                <!-- VAT  -->
                    {{--  
                    <td class="discount-row">
                        <select name="product_tax[@{{return_product_size_id}}][]" multiple="multiple" class="form-group form-select product_tax">
                            <option value="TaxFree">VAT Free</option>
                            @{{#each selected_tax}}
                                <option value="@{{this.id}}" data-rate="@{{this.rate}}" selected>@{{this.name}} </option>
                            @{{/each}}
                            @{{#each unselected_tax}}
                                <option value="@{{this.id}}" data-rate="@{{this.rate}}">@{{this.name}}</option>
                            @{{/each}}
                        </select>
                    </td>
                    --}}
                    <!-- Discount  -->
                    <td class="discount-row">
                            <div class="form-check form-switch">
                                <input class="form-check-input return_discount_per_product" type="checkbox">
                                <label class="form-check-label return_discount_label" for="flexSwitchCheckDefault">Fixed</label>
                            </div>
                            <input type="text" class="form-control return_discount_rate text-right" id="return_discount_rate" name="return_discount_rate[]" value="@{{return_discount_amount}}" autocomplete="off">
                            <input type="text" class="form-control return_discount_amount_per_product text-right" id="return_discount_amount_per_product" name="return_discount_amount_per_product[]" readonly >
                    </td>
            
                    <td>
                        <input type="text" class="form-control return_product_selling_price text-right" id="return_product_selling_price" name="return_product_selling_price[]" value="@{{return_product_selling_price}}" readonly>
                        <input type="text" class="form-control return_product_buying_price text-right" id="return_product_buying_price" name="return_product_buying_price[]" value="0" hidden>
                        <input type="text" class="form-control product_tax_amount text-right" id="" name="product_tax_amount[]" value="0" readonly hidden>
                        <input type="text" class="form-control product_price_for_tax text-right" id="" name="product_price_for_tax[]" value="0" readonly hidden>
                    </td>

                    <td style="width:fit-content;">
                        <i class="btn btn-danger btn-sm fas fa-window-close removeeventmore"></i>
                    </td>
                    </tr>
    </script>
                <!-- End return product -->
                <script>
                    function updateDashboardWithCustomRange() {
                        // event.preventDefault(); // stop page reload

                        const customStartDate = document.getElementById('customStartDate').value;
                        const customEndDate = document.getElementById('customEndDate').value;
                        if (!customStartDate || !customEndDate) {
                            alert('Please select both start and end dates for the custom range.');
                            return;
                        }

                        const startDate = new Date(customStartDate);
                        const endDate = new Date(customEndDate);

                        fetchDashboardData(startDate, endDate);
                    }

                    function fetchDashboardData(startDate, endDate) {
                        const formattedStartDate = startDate.toISOString().split('T')[0];
                        const formattedEndDate = endDate.toISOString().split('T')[0];

                        $.ajax({
                            url: '/customer-products',
                            method: 'GET',
                            data: {
                                startDate: formattedStartDate,
                                endDate: formattedEndDate
                            },
                            success: function(data) {
                                const suggestionBox = $('#product_suggestions');
                                const productInput = $('#product_dropdown');
                                // console.log(data);
                                suggestionBox.empty();

                                if (data.length === 0) {
                                    alert('No products found for this selected date range.');
                                    suggestionBox.hide();
                                    return;
                                }

                                data.forEach(product => {
                                    suggestionBox.append(`
                                    <a href="#" class="list-group-item list-group-item-action"
                                        data-buying_date="${product.buying_date}"
                                        data-return_buying_invoice_id="${product.return_buying_invoice_id}"
                                        data-return_invoice_detail_id="${product.return_invoice_detail_id}"
                                        data-return_category_id="${product.return_category_id}"
                                        data-return_product_size_id="${product.return_product_size_id}"
                                        data-product_name="${product.product_name}"
                                        data-size_name="${product.size_name}"
                                        data-brand_name="${product.brand_name}"
                                        data-return_product_quantity="${product.return_product_quantity}"
                                        data-return_unit_price="${product.return_unit_price}"
                                        data-return_unit_buying_price="${product.return_unit_buying_price}"
                                        data-return_product_selling_price="${product.return_product_selling_price}"
                                        data-return_discount_amount="${product.return_discount_amount}">
                                        ${product.product_name} (${product.size_name}) - 
                                        Qty: ${product.return_product_quantity} - 
                                        Selling Price: ${product.return_product_selling_price}৳ -
                                        Selling Date: ${product.buying_date} -
                                        Invoice Id: ${product.return_buying_invoice_id} -
                                        Exchangeable: ${product.exchange != 1 ? 'Yes' : 'No'}
                                    </a>
                                `);
                                });

                                suggestionBox.show();

                                productInput.off('input').on('input', function() {
                                    const query = $(this).val().toLowerCase();
                                    $('#product_suggestions a').each(function() {
                                        const itemText = $(this).text().toLowerCase();
                                        $(this).toggle(itemText.includes(query));
                                    });
                                });
                            },
                            error: function(err) {
                                console.error('Error loading products:', err);
                            }
                        });
                    }
                </script>

                <!-- Customer add option -->
                <script>
                    const customers = @json($customer);

                    $(document).ready(function() {
                        $('#mobile_no').on('input', function() {
                            const query = $(this).val().toLowerCase();
                            const suggestionBox = $('#mobile_suggestions');
                            suggestionBox.empty();

                            if (query.length === 0) {
                                suggestionBox.hide();
                                return;
                            }

                            let matches = customers.filter(c => {
                                return (
                                    (c.mobile_no && c.mobile_no.toLowerCase().includes(query)) ||
                                    (c.name && c.name.toLowerCase().includes(query))
                                );
                            });
                            // console.log('Query:', query);
                            // console.log('All Customers:', customers);
                            // console.log('Matches:', matches);
                            if (matches.length === 0) {
                                suggestionBox.hide();
                                return;
                            }

                            matches.forEach(c => {
                                suggestionBox.append(`
                                <a href="#" class="list-group-item list-group-item-action"
                                data-id="${c.id}"
                                data-name="${c.name}"
                                data-mobile="${c.mobile_no}"
                                data-email="${c.email}">
                                ${c.name} - ${c.mobile_no}
                                </a>`);
                            });

                            suggestionBox.show();
                        });

                        // When suggestion is clicked
                        $(document).on('click', '#mobile_suggestions a', function(e) {
                            e.preventDefault();

                            let customerId = $(this).data('id');
                            let name = $(this).data('name');
                            let mobile = $(this).data('mobile');
                            let email = $(this).data('email');

                            // Fill form fields
                            $('#name').val(name);
                            $('#mobile_no').val(mobile);
                            $('#email').val(email);

                            // Hide suggestions
                            $('#mobile_suggestions').hide();

                            // Fetch products by customer ID
                            fetchCustomerProducts(customerId);
                        });

                        function fetchCustomerProducts(customerId) {
                            $.ajax({
                                url: '/customer-products',
                                method: 'GET',
                                data: {
                                    customer_id: customerId
                                },
                                success: function(data) {
                                    const suggestionBox = $('#product_suggestions');
                                    const productInput = $('#product_dropdown');

                                    suggestionBox.empty();

                                    if (data.length === 0) {
                                        alert('No products found for this customer.');
                                        suggestionBox.hide();
                                        return;
                                    }

                                    // Loop through each product and add to dropdown
                                    data.forEach(product => {
                                        suggestionBox.append(`
                                        <a href="#" class="list-group-item list-group-item-action"
                                        data-buying_date="${product.buying_date}"
                                        data-return_buying_invoice_id="${product.return_buying_invoice_id}"
                                        data-return_invoice_detail_id="${product.return_invoice_detail_id}"
                                        data-return_category_id="${product.return_category_id}"
                                        data-return_product_size_id="${product.return_product_size_id}"
                                        data-product_name="${product.product_name}"
                                        data-size_name="${product.size_name}"
                                        data-brand_name="${product.brand_name}"
                                        data-return_product_quantity="${product.return_product_quantity}"

                                        data-return_unit_price="${product.return_unit_price}"
                                        
                                        data-return_unit_buying_price="${product.return_unit_buying_price}"
                                        data-return_product_selling_price="${product.return_product_selling_price}"
                                        data-return_discount_amount="${product.return_discount_amount}"
                                        >
                                        ${product.product_name} (${product.size_name}) - Qty: ${product.return_product_quantity} - Selling Price: ${product.return_product_selling_price}৳ - Selling Date: ${product.buying_date}- Exchanged: ${product.exchange != 0? 'Yes' : 'No'} - Invoice: ${product.return_buying_invoice_id}
                                        </a>
                                    `);
                                    });

                                    suggestionBox.show();

                                    // Filter suggestions when typing
                                    productInput.off('input').on('input', function() {
                                        const query = $(this).val().toLowerCase();
                                        $('#product_suggestions a').each(function() {
                                            const itemText = $(this).text().toLowerCase();
                                            $(this).toggle(itemText.includes(query));
                                        });
                                    });
                                },
                                error: function(err) {
                                    console.error('Error loading products:', err);
                                }
                            });
                        }
                        // When suggestion is clicked
                        $(document).on('click', '#product_suggestions a', function(e) {
                            e.preventDefault();
                            $('#product_suggestions').hide();
                            var date = $('#date').val();
                            var invoice_no = $('#invoice_no').val();

                            if (!date) {
                                $.notify("Date is Required", {
                                    globalPosition: 'top right',
                                    className: 'error'
                                });
                                return;
                            }

                            // Collect data from clicked suggestion
                            var data = {
                                date: date,
                                invoice_no: invoice_no,
                                buying_date: $(this).data('buying_date'),
                                return_buying_invoice_id: $(this).data('return_buying_invoice_id'),
                                return_invoice_detail_id: $(this).data('return_invoice_detail_id'),
                                return_category_id: $(this).data('return_category_id'),
                                return_product_size_id: $(this).data('return_product_size_id'),
                                product_name: $(this).data('product_name'),
                                size_name: $(this).data('size_name'),
                                brand_name: $(this).data('brand_name'),
                                return_product_quantity: parseFloat($(this).data('return_product_quantity')) || 1,
                                return_product_selling_price: parseFloat($(this).data('return_product_selling_price')) || 0,
                                return_unit_buying_price: parseFloat($(this).data('return_unit_buying_price')) || 0,
                                return_unit_price: parseFloat($(this).data('return_unit_price')) || 0,
                                return_discount_amount: parseFloat($(this).data('return_discount_amount')) || 0,
                            };

                            // Profit & Discount calculations
                            data.profit_10 = data.return_unit_price * 0.90 - data.return_unit_buying_price;
                            data.profit_15 = data.return_unit_price * 0.85 - data.return_unit_buying_price;
                            data.profit_20 = data.return_unit_price * 0.80 - data.return_unit_buying_price;
                            data.max_discount = data.return_unit_price - data.return_unit_buying_price;

                            // Compile and append row
                            var source = $("#sales-return-product").html();
                            var template = Handlebars.compile(source);
                            var html = template(data);

                            $("#addRow").prepend(html);
                            $("#addPreviewRow").prepend(html);
                            $('#product_dropdown').val('');
                            
                            setTimeout(function() {
                                let newRow = $("#addRow").find("tr").first();
                                let qtyInput = newRow.find(".return_product_quantity");
                                if (qtyInput.length) {
                                    qtyInput.trigger("change");
                                }
                            }, 10);

                            $("#addRow tr").each(function(index) {
                                $(this).find(".serial-number").text(index + 1);
                            });
                            let totalExchangeProductPrice = parseFloat($('#total').val()) || 0;
                            let totalReturnProductSellingPrice = 0;
                            $('.return_product_selling_price').each(function() {
                                let value = parseFloat($(this).val());
                                if (!isNaN(value)) totalReturnProductSellingPrice += value;
                            });
                            // console.log(totalReturnProductSellingPrice);
                            $('#total_return_amount').val((totalReturnProductSellingPrice).toFixed(0));
                            $('#grand_total').val((totalExchangeProductPrice - totalReturnProductSellingPrice).toFixed(0));
                        });


                        // Hide suggestion dropdown on click outside
                        // $(document).on('click', function(e) {
                        //     if (!$(e.target).closest('#product_suggestions').length) {
                        //         $('#product_suggestions').hide();
                        //     }
                        // });
                        $('#product_suggestions_show_or_hide').on('click', function() {
                            const suggestionBox = $('#product_suggestions');
                            const icon = $(this).find('i');

                            if (suggestionBox.is(':visible')) {
                                suggestionBox.hide();
                                icon.removeClass('fa-eye-slash').addClass('fa-eye');
                            } else {
                                suggestionBox.show();
                                icon.removeClass('fa-eye').addClass('fa-eye-slash');
                            }
                        });
                        $(document).on('click', function(e) {
                            if (!$(e.target).closest('#mobile_no, #product_suggestions').length) {
                                $('#mobile_suggestions').hide();
                            } else {
                                $('#product_suggestions').show();
                            }
                        });
                    });
                </script>
                <!--product_tax Select2 Start -->
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
                        // $('#total_taxes').select2();
                        initializeSelect2();
                        // $('#total_additional_fees_type').select2();
                        // $('#customer_id').select2();
                    });
                </script>
                <!-- Select2 End-->
                <!-- Show Confirm Msg -->

                <!-- Preview Script start-->
                <script>
                    const previewIframe = document.getElementById('invoicePreviewIframe');

                    document.querySelectorAll('.previewBtn').forEach(button => {
                        button.addEventListener('click', function() {
                            const type = this.dataset.value;
                            document.getElementById('saveBtn').value = type;

                            const form = document.getElementById('postForm');
                            const formData = new FormData(form);

                            fetch("{{ route('sales.return.preview') }}", {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    },
                                    body: formData
                                })
                                .then(res => res.blob())
                                .then(blob => {
                                    const url = URL.createObjectURL(blob);
                                    previewIframe.src = url;

                                    const modal = new bootstrap.Modal(document.getElementById('invoicePreviewModal'));
                                    modal.show();
                                });
                        });
                    });

                    document.getElementById('confirmSubmitBtn').addEventListener('click', function() {
                        if (previewIframe.contentWindow) {
                            previewIframe.contentWindow.focus();
                            previewIframe.contentWindow.print();
                        }
                        document.getElementById('postForm').submit();
                    });
                </script>


                <!-- Preview Script end-->
                <!-- To enable Save and Save & Print invoice Btn START-->
                <!-- <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const saveAndPrintPDFButton = document.getElementById('saveAndPrintPDFButton');
                        const posPrint = document.getElementById('PosPrint');
                        // const chalanButton = document.getElementById('chalanButton');
                        // const draftButton = document.getElementById('draftButton');
                        // const quotationButton = document.getElementById('quotationButton');
                        const newCustomerForm = document.querySelector('.new_customer');

                        function toggleUI(selectedValue) {
                            // Default: disable everything and hide form
                            saveAndPrintPDFButton.disabled = true;
                            posPrint.disabled = true;
                            // draftButton.disabled = true;
                            // quotationButton.disabled = true;
                            // chalanButton.disabled = true;
                            newCustomerForm.style.display = 'none';

                            // Enable buttons if valid customer is selected
                            if (selectedValue !== '-2') {
                                saveAndPrintPDFButton.disabled = false;
                                // draftButton.disabled = false;
                                posPrint.disabled = false;
                                // quotationButton.disabled = false;
                                // chalanButton.disabled = false;
                            }

                            const nameField = document.getElementById('name');
                            const mobileField = document.getElementById('mobile_no');
                            const emailField = document.getElementById('email');
                            const addressField = document.getElementById('address');

                            if (selectedValue === '-1') {
                                newCustomerForm.style.display = 'flex'; // show new customer form
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
                            $('#customer_id').select2();

                            // Trigger toggleUI on select2 change
                            $('#customer_id').on('change', function() {
                                const selectedValue = $(this).val();
                                // alert("Selected Value: " + selectedValue); // Debugging line
                                toggleUI(selectedValue);
                            });
                            // Call toggleUI initially
                            toggleUI($('#customer_id').val());
                        });
                    });
                </script> -->

                <!-- To enable Save and Save & Print invoice Btn END-->
                <script type="text/javascript">
                    var product_list = [];

                    function fetchProductDetails(barcode) {
                        if (!barcode) return;
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

                                var date = $('#date').val();
                                var invoice_no = $('#invoice_no').val();
                                var product_id = product_list.product.id;
                                var product_size_id = product_list.id;
                                var product_name = product_list.product.name;
                                var category_id = product_list.product.category_id;

                                var discounted_price = product_list.discounted_price;
                                var product_price = discounted_price && discounted_price !== '' ? discounted_price : product_list.selling_price;
                                var product_price_code = product_list.buying_price_code;
                                var quantity = product_list.quantity;
                                var product_discount = 0;
                                var product_buying_price = product_list.buying_price ? product_list.buying_price : 0;
                                if (quantity == 0) {
                                    $.notify("Product is out of stock.", {
                                        globalPosition: 'top right',
                                        className: 'error'
                                    });
                                    return;
                                }

                                if (!date) {
                                    $.notify("Date is Required", {
                                        globalPosition: 'top right',
                                        className: 'error'
                                    });
                                    return;
                                }

                                if (!product_size_id) {
                                    $.notify("Product Field is Required", {
                                        globalPosition: 'top right',
                                        className: 'error'
                                    });
                                    return;
                                }

                                var source = $("#document-template").html();
                                var template = Handlebars.compile(source);
                                var data = {
                                    date: date,
                                    invoice_no: invoice_no,
                                    product_id: product_id,
                                    product_size_id: product_size_id,
                                    product_name: product_name,
                                    category_id: category_id,
                                    product_price: product_price ? product_price : 0,
                                    product_buying_price: product_buying_price,
                                    profit_10: product_price * 0.90 - product_buying_price,
                                    profit_15: product_price * 0.85 - product_buying_price,
                                    profit_20: product_price * 0.80 - product_buying_price,
                                    max_discount: product_price - product_buying_price,
                                    product_price_code: product_price_code,
                                    quantity: quantity,
                                    brand_name: brand_name,
                                    size_name: size_name,
                                    product_discount: product_discount,
                                };

                                var html = template(data);
                                $("#addRow").prepend(html);
                                $("#addPreviewRow").prepend(html);
                                $('#search-product-or-barcode-input').val('');
                                // Wait for DOM update, then manually trigger .change on .selling_qty
                                setTimeout(function() {
                                    let newRow = $("#addRow").find("tr").first();
                                    let qtyInput = newRow.find(".selling_qty");

                                    if (qtyInput.length) {
                                        // Trigger change to force recalculation
                                        qtyInput.trigger("change");
                                    } else {
                                        console.warn("No .selling_qty found in new row");
                                    }

                                }, 10);
                                // Fix: Properly update all serial numbers
                                $("#addRow tr").each(function(index) {
                                    $(this).find(".serial-number").text(index + 1);
                                });
                                updateInvoiceCalculations();
                                // Enable select2 product_tax 
                                initializeSelect2();
                            }
                        });
                    }

                    $(document).ready(function() {
                        // Sidebar product add button
                        $(document).on("click", ".addProduct", function() {
                            $(this).siblings(".barcode").prop('disabled', false);
                            var barcode = $(this).siblings(".barcode").val().trim();
                            fetchProductDetails(barcode);
                        });

                        // Barcode input (scanner/manual)
                        $('#search-product-or-barcode-input').on("keypress", function(event) {
                            if (event.key === "Enter") {
                                event.preventDefault();
                                var barcode = $(this).val().trim();
                                if (barcode !== "") {
                                    fetchProductDetails(barcode);
                                }
                                $(this).val('');
                            }
                        });

                        // Barcode dropdown/select
                        $('#product_barcode').on('change', function() {
                            var selectedValue = $(this).val();
                            fetchProductDetails(selectedValue);
                        });
                    });
                </script>

                <!-- All calculations Start-->
                <script>
                    $(document).ready(function() {

                        // Remove product from invoice
                        $(document).on("click", ".removeeventmore", function() {
                            $(this).closest(".delete_add_more_item").remove();
                            // Renumber Rows After Each Addition or Deletion
                            $("#addRow tr").each(function(index) {
                                $(this).find(".serial-number").text(index + 1);
                            });
                            updateInvoiceCalculations();
                        });

                        // Event listener for price, quantity, discount, and commission changes
                        $(document).on('keyup click change', '.unit_price, .selling_qty, .discount_rate, .discount_per_product, .product_tax', function() {
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
                        $(document).on('keyup', '#discount_amount, #paid_amount, #due_amount, #paid_status, .selling_qty, #discount_status,#cash, #visa_card, #master_card,#bkash,#nagad,#rocket,#upay,#surecash,#online,#discount_amount', updateInvoiceCalculations);

                        $(document).on('keyup', '#round_amount', adjustPaidAmount);

                        /**
                         * Update individual product row calculations
                         */
                        function updateProductRow(row) {
                            let unitPrice = parseFloat(row.find("input.unit_price").val()) || 0;
                            let buyingPrice = parseFloat(row.find("input.product_buying_price").val()) || 0;
                            let quantity = parseFloat(row.find("input.selling_qty").val()) || 0;
                            let discount = parseFloat(row.find("input.discount_rate").val()) || 0;
                            let isFixedDiscount = row.find('.discount_per_product').is(':checked');
                            let discountLabel = row.find('.discount_label');
                            let totalDiscount = 0;

                            // Calculate discount per product
                            if (!isNaN(discount)) {
                                let baseValue = unitPrice * quantity;
                                if (isFixedDiscount) {
                                    discountLabel.text('Fixed');
                                    totalDiscount = discount;
                                } else {
                                    discountLabel.text('% Percent');
                                    totalDiscount = (discount / 100) * baseValue;
                                }
                            }

                            // Set discount total
                            row.find("input.discount_amount_per_product").val(totalDiscount.toFixed(2));

                            // Calculate total price after discount
                            let totalSellingPrice = (unitPrice * quantity) - totalDiscount;
                            let totalBuyingPrice = buyingPrice * quantity;
                            row.find("input.selling_price").val(totalSellingPrice.toFixed(2));
                            row.find("input.buying_price").val(totalBuyingPrice.toFixed(2));

                            // Calculate tax per product
                            let totalTax = 0;
                            row.find('select.product_tax option:selected').each(function() {
                                totalTax += parseFloat($(this).data('rate')) || 0;
                            });

                            row.find("input.product_price_for_tax").val((unitPrice * quantity).toFixed(2));
                            row.find("input.product_tax_amount").val((totalTax * unitPrice * quantity).toFixed(2));
                        }
                        // Event listener for RETURN PRODUCT price, quantity, discount, and commission changes
                        $(document).on('keyup click change', '.return_unit_price, .return_product_quantity, .return_discount_rate, .return_discount_amount_per_product,.return_discount_per_product', function() {
                            updateReturnProductRow($(this).closest("tr"));
                            adjustPaidAmount();
                        });
                        /**
                         * Update individual RETURN product row calculations
                         */
                        function updateReturnProductRow(row) {
                            let unitPrice = parseFloat(row.find("input.return_unit_price").val()) || 0;
                            let buyingPrice = parseFloat(row.find("input.return_product_buying_price").val()) || 0;
                            let quantity = parseFloat(row.find("input.return_product_quantity").val()) || 0;
                            let discount = parseFloat(row.find("input.return_discount_rate").val()) || 0;
                            let isPercentageDiscount = row.find('.return_discount_per_product').is(':checked');
                            let discountLabel = row.find('.return_discount_label');
                            let totalDiscount = 0;
                            // console.log(isPercentageDiscount);
                            // Calculate discount per product
                            if (!isNaN(discount)) {
                                let baseValue = unitPrice * quantity;
                                if (isPercentageDiscount) {
                                    discountLabel.text('% Percent');
                                    totalDiscount = (discount / 100) * baseValue;
                                } else {
                                    discountLabel.text('Fixed');
                                    totalDiscount = discount;
                                }
                            }

                            // Set discount total
                            row.find("input.return_discount_amount_per_product").val(totalDiscount.toFixed(2));

                            // Calculate total price after discount
                            let totalSellingPrice = (unitPrice * quantity) - totalDiscount;
                            let totalBuyingPrice = buyingPrice * quantity;
                            row.find("input.return_product_selling_price").val(totalSellingPrice.toFixed(2));
                            row.find("input.return_product_buying_price").val(totalBuyingPrice.toFixed(2));

                            // // Calculate tax per product
                            // let totalTax = 0;
                            // row.find('select.product_tax option:selected').each(function() {
                            //     totalTax += parseFloat($(this).data('rate')) || 0;
                            // });

                            // row.find("input.product_price_for_tax").val((unitPrice * quantity).toFixed(2));
                            // row.find("input.product_tax_amount").val((totalTax * unitPrice * quantity).toFixed(2));
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
                            // console.log(discountAmount);
                            let discountStatus = $('#discount_status').val();

                            if (discountAmount >= 0) {
                                if (discountStatus === 'percentage_discount') {
                                    let discount = (discountAmount / 100) * totalSellingPrice;
                                    totalSellingPrice -= discount;
                                    totalDiscount += discount;
                                    $('#discount_show').val(discount.toFixed(2));
                                } else {
                                    let discount = (discountAmount * 100) / totalSellingPrice;
                                    totalSellingPrice -= discountAmount;
                                    totalDiscount += discountAmount;
                                    $('#discount_show').val(discount.toFixed(2));
                                }
                            }

                            $('#total_discount_amount').val(totalDiscount.toFixed(2));
                            $('#secret_grand_total_price_code').val(totalBuyingPrice.toFixed(0));
                            $('#estimated_amount').val(totalSellingPrice.toFixed(2));
                            let totalProfit = totalSellingPrice - totalBuyingPrice;
                            $('#total_profit_code').val(totalProfit > 0 ? totalProfit.toFixed(0) : -1);
                            adjustPaidAmount();
                        }

                        /**
                         * Adjust paid amount based on status
                         */
                        function adjustPaidAmount() {
                            // Calculate the total amount before rounding
                            let total = sumValues('#total_additional_fees_amount, #tax_value, #estimated_amount');
                            //When rounded amount is using , we round the grand total its means subtract the round_amount frm total, ann add the round value to discount
                            //START
                            let decimalPart = total - Math.floor(total);
                            total = (decimalPart >= 0.5) ? Math.ceil(total) : Math.floor(total);
                            $('#total').val(total.toFixed(2));

                            if ($('#round_amount').val() >= 0) {
                                let round = parseFloat($('#round_amount').val()) || 0;
                                total = total - round;
                                $('#total').val(total.toFixed(2));

                                // Discount calculation
                                let total_discount_per_product = sumValues(".discount_amount_per_product");

                                let invoice_discount_amount = parseFloat($('#discount_amount').val()) || 0;
                                let invoice_discount_show = parseFloat($('#discount_show').val()) || 0;
                                let invoice_discount_status = $('#discount_status').val();

                                let invoice_discount = 0;
                                if (invoice_discount_status === 'percentage_discount') {
                                    invoice_discount = invoice_discount_show;
                                } else {
                                    invoice_discount = invoice_discount_amount;
                                }

                                let newDiscount = total_discount_per_product + invoice_discount + round;
                                $('#total_discount_amount').val(newDiscount.toFixed(2));
                                let totalExchangeProductPrice = $('#total').val();
                                // console.log(totalExchangeProductPrice);
                                let totalReturnProductSellingPrice = sumValues(".return_product_selling_price");
                                $('#total_return_amount').val((totalReturnProductSellingPrice).toFixed(0));
                                $('#grand_total').val((totalExchangeProductPrice - totalReturnProductSellingPrice).toFixed(0));
                                // console.log($('#total_discount_amount').val());
                            }

                            //END

                            let grand_total = $('#grand_total').val();
                            // Calculate total payment from all payment methods
                            let total_payment = sumValues('#cash, #visa_card, #master_card,#bkash,#nagad,#rocket,#upay,#surecash,#online');

                            // Determine paid amount and change
                            if (total_payment > 0) {
                                if (total_payment >= grand_total) {
                                    $('#paid_amount').val(grand_total);
                                    $('#change').val(grand_total - total_payment);
                                } else {
                                    $('#paid_amount').val(total_payment);
                                    $('#change').val(grand_total - total_payment);
                                }
                            } else {
                                // No specific payment input, assume full payment
                                $('#paid_amount').val(0);
                                $('#change').val(0);
                            }

                            // Calculate and display the due amount
                            let dueAmount = grand_total - (parseFloat($('#paid_amount').val()) || 0);
                            $('#due_amount').val(dueAmount.toFixed(2));
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

                <!-- New Customer Insert form select New customer Option -->
                <!-- <script type="text/javascript">
                    $(document).on('change', '#customer_id', function() {
                        var customer_id = $(this).val();
                        if (customer_id == '-1') {
                            $('.new_customer').show();
                        } else {
                            $('.new_customer').hide();
                        }
                    });
                </script> -->
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



            </div>
            <!-- end main content-->
        </div>
        <!-- END layout-wrapper -->

        <!-- Right Sidebar -->

        <!-- /Right-bar -->

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

        <!-- JAVASCRIPT -->
        <script src="{{ asset('backend/assets/libs/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/metismenu/metisMenu.min.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/simplebar/simplebar.min.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/node-waves/waves.min.js') }}"></script>

        <!-- apexcharts -->
        <script src="{{ asset('backend/assets/libs/apexcharts/apexcharts.min.js') }}"></script>

        <!-- jquery.vectormap map -->
        <script src="{{ asset('backend/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-us-merc-en.js') }}"></script>

        <!-- Required datatable js -->
        <script src="{{ asset('backend/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>

        <!-- Responsive examples -->
        <script src="{{ asset('backend/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

        <script src="{{ asset('backend/assets/js/pages/dashboard.init.js') }}"></script>

        <!-- App js -->
        <script src="{{ asset('backend/assets/js/app.js') }}"></script>

        <!-- Toastr js -->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


        <script>
            @if(Session::has('message'))
            var type = "{{ Session::get('alert-type','info') }}"
            switch (type) {
                case 'info':
                    toastr.info(" {{ Session::get('message') }} ");
                    break;

                case 'success':
                    toastr.success(" {{ Session::get('message') }} ");
                    break;

                case 'warning':
                    toastr.warning(" {{ Session::get('message') }} ");
                    break;

                case 'error':
                    toastr.error(" {{ Session::get('message') }} ");
                    break;
            }
            @endif
        </script>
        <!-- Required datatable js -->
        <script src="{{ asset('backend/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>

        <!-- Buttons examples -->
        <script src="{{asset('backend/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
        <script src="{{asset('backend/assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js')}}"></script>
        <script src="{{asset('backend/assets/libs/jszip/jszip.min.js')}}"></script>
        <script src="{{asset('backend/assets/libs/pdfmake/build/pdfmake.min.js')}}"></script>
        <script src="{{asset('backend/assets/libs/pdfmake/build/vfs_fonts.js')}}"></script>
        <script src="{{asset('backend/assets/libs/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
        <script src="{{asset('backend/assets/libs/datatables.net-buttons/js/buttons.print.min.js')}}"></script>
        <script src="{{asset('backend/assets/libs/datatables.net-buttons/js/buttons.colVis.min.js')}}"></script>

        <script src="{{asset('backend/assets/libs/datatables.net-keytable/js/dataTables.keyTable.min.js')}}"></script>
        <script src="{{asset('backend/assets/libs/datatables.net-select/js/dataTables.select.min.js')}}"></script>

        <!-- Datatable init js -->
        <script src="{{ asset('backend/assets/js/pages/datatables.init.js') }}"></script>

        <!-- validate js for insert form -->
        <script src="{{ asset('backend/assets/js/validate.min.js') }}"></script>

        <!-- Sweetalert js for delete button -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
        <script src="{{ asset('backend/assets/js/code.js') }}"></script>

        <!-- handlebars js for  -->
        <script src="{{ asset('backend/assets/js/handlebars.min.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/notify/0.4.2/notify.min.js"></script>

        <!-- select2 button JS for purchase form -->
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <!-- Delete sweet alert -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const deleteButtons = document.querySelectorAll('.show_confirm');

                deleteButtons.forEach(function(btn) {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault(); // prevent form from submitting immediately

                        Swal.fire({
                            title: 'Are you sure?',
                            text: "This action cannot be undone!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, delete it!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                btn.closest('form').submit(); // submit the form if confirmed
                            }
                        });
                    });
                });
            });
        </script>
        <!-- Full screen mode message -->
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1100;">
            <div id="fullscreenToast" class="toast align-items-center text-white bg-info border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        To enter or exit fullscreen mode, please press <strong>F11</strong> on your keyboard.
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>
        <!-- Full screen mode msg JS -->
        <script>
            function showFullscreenHint() {
                const toastEl = document.getElementById('fullscreenToast');
                const toast = new bootstrap.Toast(toastEl);
                toast.show();
            }
        </script>
        @yield('admin_custom_js')
</body>

</html>