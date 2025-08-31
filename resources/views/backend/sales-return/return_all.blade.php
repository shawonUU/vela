@extends('admin.admin_master')
@section('admin')
@php
$filterName = 'Today';

if($filter === 'today')
$filterName = 'Today';
elseif($filter === 'yesterday')
$filterName = 'Yesterday';

elseif($filter === 'last7Days')
$filterName = 'Last 7 Days';

elseif($filter === 'last30Days')
$filterName = 'Last 30 Days';

elseif($filter === 'thisMonth')
$filterName = 'This Month';

elseif($filter === 'lastMonth')
$filterName = 'Last Month';

elseif($filter === 'thisMonthLastYear')
$filterName = 'This Month Last Year';

elseif($filter === 'thisYear')
$filterName = 'This Year';

elseif($filter === 'lastYear')
$filterName = 'Last Year';

elseif($filter === 'currentFinancialYear')
$filterName = 'Current Financial Year';

elseif($filter === 'lastFinancialYear')
$filterName = 'Last Financial Year';

elseif($filter === 'customRange')
$filterName = $show_start_date.' To '.$show_end_date .' Date';
@endphp
<div class="page-content">
    <div class="container-fluid">
        <!-- Invoice Filter -->
        <div class="row g-2 align-items-end flex-wrap mb-3">

            {{-- Start Date --}}
            <div class="col-md-2">
                <label for="customStartDate" class="form-label mb-0 small">Start Date</label>
                <input type="date" id="customStartDate" name="customStartDate" class="form-control"
                    value="{{ $show_start_date ?? \Carbon\Carbon::now()->format('Y-m-d') }}" required>
            </div>

            {{-- End Date --}}
            <div class="col-md-2">
                <label for="customEndDate" class="form-label mb-0 small">End Date</label>
                <input type="date" id="customEndDate" name="customEndDate" class="form-control"
                    value="{{ $show_end_date ?? \Carbon\Carbon::now()->format('Y-m-d') }}" required>
            </div>

            {{-- Apply Button --}}
            <div class="col-md-1">
                <label class="form-label mb-0 invisible">Apply</label>
                <button class="btn btn-dark w-100" onclick="updateDashboardWithCustomRange()">Apply</button>
            </div>

            {{-- Date Filter --}}
            <div class="col-md-2">
                <label for="dateFilter" class="form-label mb-0 small">Date Filter</label>
                <select id="dateFilter" class="form-select" onchange="updateDashboard()">
                    <option value="">Select</option>
                    <option value="today" {{ $filter == 'today' ? 'selected' : '' }}>Today</option>
                    <option value="yesterday" {{ $filter == 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                    <option value="last7Days" {{ $filter == 'last7Days' ? 'selected' : '' }}>Last 7 Days</option>
                    <option value="last30Days" {{ $filter == 'last30Days' ? 'selected' : '' }}>Last 30 Days</option>
                    <option value="thisMonth" {{ $filter == 'thisMonth' ? 'selected' : '' }}>This Month</option>
                    <option value="lastMonth" {{ $filter == 'lastMonth' ? 'selected' : '' }}>Last Month</option>
                    <option value="thisMonthLastYear" {{ $filter == 'thisMonthLastYear' ? 'selected' : '' }}>This Month Last Year</option>
                    <option value="thisYear" {{ $filter == 'thisYear' ? 'selected' : '' }}>This Year</option>
                    <option value="lastYear" {{ $filter == 'lastYear' ? 'selected' : '' }}>Last Year</option>
                    <option value="currentFinancialYear" {{ $filter == 'currentFinancialYear' ? 'selected' : '' }}>Current FY</option>
                    <option value="lastFinancialYear" {{ $filter == 'lastFinancialYear' ? 'selected' : '' }}>Last FY</option>
                </select>
            </div>

            {{-- Customer Filter --}}
            <div class="col-md-3">
                <label for="customer_filter" class="form-label mb-0 small">Customer</label>
                <select id="customer_filter" class="form-select" onchange="updateDashboard()">
                    <option value="">Select</option>
                    @foreach($all_customers as $cust)
                    <option value="{{ $cust->id }}" {{ $customer_filter == $cust->id ? 'selected' : '' }}>
                        {{ $cust->name }} - {{ $cust->mobile_no }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Invoice Type Filter 
            <div class="col-md-1">
                <label for="invoice_type_filter" class="form-label mb-0 small">Type</label>
                <select id="invoice_type_filter" class="form-select" onchange="updateDashboard()">
                    <option value="">All</option>
                    <option value="draft" {{ $invoice_type_filter == 'draft' ? 'selected' : '' }}>Draft</option>
            <option value="challan" {{ $invoice_type_filter == 'challan' ? 'selected' : '' }}>Challan</option>
            <option value="invoice" {{ $invoice_type_filter == 'invoice' ? 'selected' : '' }}>Invoice</option>
            </select>
        </div>
        --}}
        {{-- Right-Aligned Buttons --}}
        <div class="col-md-2 ms-auto d-flex gap-2">
            <div>
                <label class="form-label mb-0 invisible">Reset</label>
                <a href="{{ route('sales.return.all') }}" class="btn btn-outline-dark">
                    <i class="fas fa-undo"></i> Reset
                </a>
            </div>
            @can('invoice-create')
            <div>
                <label class="form-label mb-0 invisible">Add</label>
                <a href="{{ route('invoice.add') }}" class="btn btn-success">
                    <i class="fas fa-plus-circle"></i> Add
                </a>
            </div>
            @endcan
        </div>
    </div>
    <!-- <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-truncate font-size-14 mb-2">Sales</p>
                                <h4 class="mb-2" id="sales">৳ {{ (!empty($total_amount)?number_format($total_amount):'0') }} Tk</h4>
                                <h4 class="mb-2"></h4>
                                <p class="text-muted mb-0"><span class="text-success fw-bold font-size-12 me-2">
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-light text-primary rounded-3">
                                    <i class="ri-shopping-cart-2-line font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-truncate font-size-14 mb-2">Paid Amount</p>
                                <h4 class="mb-2" id="paid">৳ {{(!empty($total_paid)?number_format($total_paid):'0')}} Tk</h4>
                                <p class="text-muted mb-0"><span class="text-info fw-bold font-size-12 me-2">
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-light text-success rounded-3">
                                    <i class="mdi mdi-currency-bdt font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-truncate font-size-14 mb-2">Due Amount</p>
                                <h4 class="mb-2" id="due">৳ {{(!empty($total_due)?number_format($total_due):'0')}} Tk</h4>
                                <p class="text-muted mb-0"><span class="text-info fw-bold font-size-12 me-2">
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-light text-success rounded-3">
                                    <i class="mdi mdi-currency-bdt font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-truncate font-size-14 mb-2">Profit</p>
                                <h4 class="mb-2" id="profit">৳ {{(!empty($total_profit)?number_format($total_profit):'0')}} Tk</h4>
                                <p class="text-muted mb-0"><span class="text-info fw-bold font-size-12 me-2">
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-light text-success rounded-3">
                                    <i class="mdi mdi-currency-bdt font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
    <!-- end page title -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <h4 class="card-title">Invoice/ Challan of - {{ $filterName }} </h4>
                        </div>
                        <div class="col-6">
                            <div class="text-sm-end">
                                <a href="{{ route('print.invoice-all-filer',
                                        [!empty($show_start_date) ? $show_start_date : \Carbon\Carbon::now()->subDays(30)->format('Y-m-d'),
                                        !empty($show_end_date)?$show_end_date:Carbon\Carbon::now()->format('Y-m-d'),
                                        $filter??'null',
                                        $invoice_type_filter??'null',
                                        $customer_filter??'null'
                                        ]) }}" target="_blank" class="btn btn-dark waves-effect waves-light mb-2 me-2"><i class="mdi mdi-printer"></i> Print </a>
                            </div>
                        </div>
                    </div>
                    <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <colgroup>
                            <col style="width: 5%;">
                            <col style="width: 33%;">
                            <!-- <col style="width: 5%;"> -->
                            <col style="width: 10%;">
                            <col style="width: 10%;">
                            <col style="width: 10%;">
                            <!-- <col style="width: 8%;"> -->
                        </colgroup>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Customer Name</th>
                                <th>Invoice Id</th>
                                <!-- <th>Return Invoice Id</th> -->
                                <th>Return Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($return_invoices as $key => $item)
                            <tr>
                                <td> {{ $key+1}} </td>
                                <td> {{ $item['customer']?($item['customer']['name']??$item['customer']['mobile_no']):'Walking Customer'}} </td>
                                <td>#{{ $item->invoice_id }}</td>
                                <!-- <td>#{{ $item->return_invoice_id }}</td> -->
                                <td> {{ date('d-m-Y',strtotime($item->return_date)) }} </td>
                                <td>
                                    <!-- <a href="{{ url('print/report') }}/{{$item->invoice_id }}/3 "
                                        class="btn btn-success sm"
                                        title="Invoice Print">
                                        <i class="fas fa-print"></i>
                                    </a> -->
                                    <a href="javascript:void(0);"
                                        class="btn btn-primary sm previewBtn"
                                        data-id="{{ $item->invoice_id }}"
                                        data-type="invoicePos"
                                        title="Invoice Print">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <!-- <a href="{{ route('invoice.edit',$item->invoice_id) }}" class="btn btn-info sm" title="Edit Return Invoice"> <i class="fas fa-edit"></i> </a>
                                    <a href="{{ route('invoice.delete',$item->id) }}" class="btn btn-danger sm" title="Delete Return" id="delete"> <i class="fas fa-trash-alt"></i> </a> -->
                                </td>
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
@endsection
@section('admin_custom_js')
<script>
    $(document).ready(function() {
        $('#customer_filter').select2({
            placeholder: "Select Company",
            allowClear: true,
            width: '100%'
        });
    });
</script>
<!-- Preview Script start-->
<script>
    const previewIframe = document.getElementById('invoicePreviewIframe');
    let currentInvoiceId = null;
    document.querySelectorAll('.previewBtn').forEach(button => {
        button.addEventListener('click', function() {
            currentInvoiceId = this.dataset.id;

            fetch("{{ route('sales.return.view') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        invoice_id: currentInvoiceId
                    })
                })
                .then(res => res.blob())
                .then(blob => {
                    const url = URL.createObjectURL(blob);
                    document.getElementById('invoicePreviewIframe').src = url;

                    const modal = new bootstrap.Modal(document.getElementById('invoicePreviewModal'));
                    modal.show();
                })
                .catch(error => {
                    console.error('Preview fetch error:', error);
                });
        });
    });

    document.getElementById('confirmSubmitBtn').addEventListener('click', function() {
        alert('Please wait, printing...');
        if (previewIframe.contentWindow) {
            previewIframe.contentWindow.focus();
            previewIframe.contentWindow.print();
        }
        // document.getElementById('postForm').submit();
    });
</script>

<!-- Preview Script end-->
<script>
    function updateDashboard() {
        const filter = document.getElementById('dateFilter').value;
        const customRangeInputs = document.getElementById('customRangeInputs');
        let startDate, endDate;

        const today = new Date();
        const yesterday = new Date(today);
        yesterday.setDate(today.getDate() - 1);

        const last7DaysStart = new Date(today);
        last7DaysStart.setDate(today.getDate() - 7);

        const last30DaysStart = new Date(today);
        last30DaysStart.setDate(today.getDate() - 30);

        const currentMonthStart = new Date(today.getFullYear(), today.getMonth(), 1);
        const currentMonthEnd = new Date(today.getFullYear(), today.getMonth() + 1, 0);

        const lastMonthStart = new Date(today.getFullYear(), today.getMonth() - 1, 1);
        const lastMonthEnd = new Date(today.getFullYear(), today.getMonth(), 0);

        const thisMonthLastYearStart = new Date(today.getFullYear() - 1, today.getMonth(), 1);
        const thisMonthLastYearEnd = new Date(today.getFullYear() - 1, today.getMonth() + 1, 0);

        const currentYearStart = new Date(today.getFullYear(), 0, 1);
        const currentYearEnd = new Date(today.getFullYear(), 11, 31);

        const lastYearStart = new Date(today.getFullYear() - 1, 0, 1);
        const lastYearEnd = new Date(today.getFullYear() - 1, 11, 31);

        const financialYearStart = new Date(today.getFullYear(), 3, 1); // Assuming financial year starts in April
        const financialYearEnd = new Date(today.getFullYear() + 1, 2, 31);

        const lastFinancialYearStart = new Date(today.getFullYear() - 1, 3, 1);
        const lastFinancialYearEnd = new Date(today.getFullYear(), 2, 31);
        switch (filter) {
            case 'today':
                startDate = today;
                endDate = today;
                break;
            case 'yesterday':
                startDate = yesterday;
                endDate = yesterday;
                break;
            case 'last7Days':
                startDate = last7DaysStart;
                endDate = today;
                break;
            case 'last30Days':
                startDate = last30DaysStart;
                endDate = today;
                break;
            case 'thisMonth':
                startDate = currentMonthStart;
                endDate = currentMonthEnd;
                break;
            case 'lastMonth':
                startDate = lastMonthStart;
                endDate = lastMonthEnd;
                break;
            case 'thisMonthLastYear':
                startDate = thisMonthLastYearStart;
                endDate = thisMonthLastYearEnd;
                break;
            case 'thisYear':
                startDate = currentYearStart;
                endDate = currentYearEnd;
                break;
            case 'lastYear':
                startDate = lastYearStart;
                endDate = lastYearEnd;
                break;
            case 'currentFinancialYear':
                startDate = financialYearStart;
                endDate = financialYearEnd;
                break;
            case 'lastFinancialYear':
                startDate = lastFinancialYearStart;
                endDate = lastFinancialYearEnd;
                break;
            case 'customRange':
                customRangeInputs.style.display = 'block';
            default:
                startDate = last30DaysStart;
                endDate = today;
                break;
                return;
        }

        // customRangeInputs.style.display = 'none';
        fetchDashboardData(startDate, endDate);
    }

    function updateDashboardWithCustomRange() {
        const customStartDate = document.getElementById('customStartDate').value;
        const customEndDate = document.getElementById('customEndDate').value;

        if (customStartDate && customEndDate) {
            const startDate = new Date(customStartDate);
            const endDate = new Date(customEndDate);
            fetchDashboardData(startDate, endDate);
        } else {
            alert('Please select both start and end dates for the custom range.');
        }
    }

    function fetchDashboardData(startDate, endDate) {
        // Convert dates to a suitable format for your backend
        const formattedStartDate = startDate.toISOString().split('T')[0];
        const formattedEndDate = endDate.toISOString().split('T')[0];
        const filter = document.getElementById('dateFilter').value;
        // const invoice_type_filter = document.getElementById('invoice_type_filter').value;
        const customer_filter = document.getElementById('customer_filter').value;
        // alert(invoice_type_filter);
        var url = '{{ url()->current() }}?'
        url += '&startDate=' + formattedStartDate;
        url += '&endDate=' + formattedEndDate;
        url += '&filter=' + filter;
        // url += '&invoice_type_filter=' + invoice_type_filter;
        url += '&customer_filter=' + customer_filter;
        window.location.href = url;
    }
</script>
<script>
    function sendSMS(id, sr_id = 0) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{ route('invoice.sms-send') }}",
            type: "POST",
            data: {
                id: id,
                sr_id: sr_id,
            },
            success: function(data) {
                alert(data.message);
                console.log(data);
                // console.log(data);
                // window.location.reload();
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert(XMLHttpRequest?.responseJSON?.message);
            }
        })
    }
</script>
@endsection