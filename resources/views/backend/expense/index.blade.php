@extends('admin.admin_master')
@section('admin')

<!-- NO NEED this page Similer to Print_invoice_list -->
<div class="page-content">
    <div class="container-fluid">

    <!-- start page title -->
    <div class="row">
            <div class="col-12 pt-2">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
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
                    <h4 class="mb-sm-0"> 
                        All  Expanse 
                    </h4>
                    <a href="{{ route('expenses.create') }}" class="btn btn-dark btn-rounded waves-effect waves-light"><i class="fas fa-plus-circle"> </i> Add New Expanse </a>
                </div>
            </div>
            <div class="col-12">
                <div class="row align-items-end mb-3">

                    <div class="col-12 col-md-2 px-1">
                        <label for="customStartDate" class="form-label">Start Date</label>
                                <input type="date" id="customStartDate" name="customStartDate"
                                    class="form-control px-0"
                                    value="{{ !empty($show_start_date) ? $show_start_date : Carbon\Carbon::now()->format('Y-m-d') }}"
                                    required>
                    </div>
                    <div class="col-12 col-md-2 px-1">
                        <label for="customEndDate" class="form-label">End Date</label>
                        <input type="date" id="customEndDate" name="customEndDate"
                            class="form-control px-0"
                            value="{{ !empty($show_end_date) ? $show_end_date : Carbon\Carbon::now()->format('Y-m-d') }}">
                    </div>
                    <div class="col-12 col-md-2">
                        <label for="approval" class="form-label">Approval Status</label>
                        <select name="" id="approval" class="form-select">
                            <option value="">All</option>
                            <option value="1" {{ ($approval === '1' ? 'selected' : '') }}>Approved</option>
                            <option value="0" {{ ($approval === '0' ? 'selected' : '') }}>Not Approved</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-2 col-lg-1">
                        <label for="customEndDate" class="form-label"></label>
                        <button type="button" class="btn btn-dark mt-3" style="height: 40px;" onclick="updateDashboardWithCustomRange()">
                            Apply
                        </button>
                    </div>
                    <div class="col-12 col-md-2 col-lg-3">
                        <label for="dateFilter" class="form-label">Quick Filter</label>
                        <select id="dateFilter" class="form-select shadow-none" onchange="updateDashboard()">
                            <option value="">Select</option>
                            <option value="customRange" {{ ($filter === 'customRange' ? 'selected' : '') }}>Custom Range</option>
                            <option value="today" {{ ($filter === 'today' ? 'selected' : '') }}>Today</option>
                            <option value="yesterday" {{ ($filter === 'yesterday' ? 'selected' : '') }}>Yesterday</option>
                            <option value="last7Days" {{ ($filter === 'last7Days' ? 'selected' : '') }}>Last 7 Days</option>
                            <option value="last30Days" {{ ($filter === 'last30Days' ? 'selected' : '') }}>Last 30 Days</option>
                            <option value="thisMonth" {{ ($filter === 'thisMonth' ? 'selected' : '') }}>This Month</option>
                            <option value="lastMonth" {{ ($filter === 'lastMonth' ? 'selected' : '') }}>Last Month</option>
                            <option value="thisMonthLastYear" {{ ($filter === 'thisMonthLastYear' ? 'selected' : '') }}>This Month Last Year</option>
                            <option value="thisYear" {{ ($filter === 'thisYear' ? 'selected' : '') }}>This Year</option>
                            <option value="lastYear" {{ ($filter === 'lastYear' ? 'selected' : '') }}>Last Year</option>
                            <option value="currentFinancialYear" {{ ($filter === 'currentFinancialYear' ? 'selected' : '') }}>Current Financial Year</option>
                            <option value="lastFinancialYear" {{ ($filter === 'lastFinancialYear' ? 'selected' : '') }}>Last Financial Year</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-2 d-grid">
                        <a href="{{ route('expenses.index') }}" class="btn btn-dark">
                            <i class="fa fa-undo me-1"></i> Refresh
                        </a>
                    </div>
                    
                </div>

            </div>
        </div>
        <div class="row ">

            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <span class="text-truncate font-size-14 mb-2">Total Expense</span>
                                <h4 class="mb-2">৳ {{$totalExpanseAmount}} Tk</h4>
                                <h4 class="mb-2"></h4>
                                <p class="text-muted mb-0"><span class="text-success fw-bold font-size-12 me-2">
                                        <!-- <i class="ri-arrow-right-up-line me-1 align-middle"></i></span>Invoices</p> -->
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-light text-primary rounded-3">
                                     <i class="mdi mdi-currency-bdt font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div><!-- end cardbody -->
                </div><!-- end card -->
            </div><!-- end col -->
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <span class="text-truncate font-size-14 mb-2">Unapproved Expense</span>
                                <h4 class="mb-2">৳ {{$totalNoApprovedExpanseAmount}} Tk</h4>
                                <p class="text-muted mb-0"><span class="text-info fw-bold font-size-12 me-2">
                                        <!-- <i class="ri-arrow-right-up-line me-1 align-middle"></i>1.09%</span>from previous period</p> -->
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-light text-success rounded-3">
                                    <i class="mdi mdi-currency-bdt font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div><!-- end cardbody -->
                </div><!-- end card -->
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <span class="text-truncate font-size-14 mb-2">Total Entries</span>
                                <h4 class="mb-2">{{$totalExpanse}}</h4>
                                <p class="text-muted mb-0"><span class="text-danger fw-bold font-size-12 me-2">
                                        <!-- <i class="ri-arrow-right-up-line me-1 align-middle"></i>1.09%</span>from previous period</p> -->
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-light text-success rounded-3">
                                    <i class="mdi mdi-pound-box font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div><!-- end cardbody -->
                </div><!-- end card -->
            </div><!-- end col -->
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <span class="text-truncate font-size-14 mb-2">Unapproved Entries</span>
                                <h4 class="mb-2"> {{$totalNoApprovedExpanse}} </h4>
                                <p class="text-muted mb-0"><span class="text-info fw-bold font-size-12 me-2">
                                        <!-- <i class="ri-arrow-right-up-line me-1 align-middle"></i>1.09%</span>from previous period</p> -->
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-light text-success rounded-3">
                                    <i class="mdi mdi-pound-box font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div><!-- end cardbody -->
                </div><!-- end card -->
            </div><!-- end col -->
            <!-- end col -->

        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <h4 class="card-title">All Expanse Data </h4>
                            </div>
                            <div class="col-6">
                                
                            </div>
                        </div>
                       <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th width="5%">Sl</th>
                                    <th>Category</th>
                                    <th>Article ID</th>
                                    <th>Pay To</th>
                                    <th>Amount</th>
                                    <th>Payment Method</th>
                                    <th>Notes</th>
                                    <th>Created By</th>
                                    <th>Approval Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($expenses as $key => $item)
                                <tr>
                                    <td width="5%">{{ $key + 1 }}</td>
                                    <td>{{ $item->category->name ?? '-' }}</td>                                     
                                    <td>{{ $item->article->name ?? '-' }}</td>
                                    <td>{{ $item->pay_to ?? '-' }}</td>
                                    <td>{{ $item->amount }}</td>
                                    <td>{{ ucfirst($item->payment_method) }}</td>
                                    <td>{{ $item->note ?? '-' }}</td>
                                    <td>{{ $item->creator->name ?? '-' }}</td>
                                    <td>
                                        <span class="{{ $item->is_approved == 1 ? 'text-success' : 'text-danger' }}">
                                            {{ $item->is_approved == 1 ? 'Approved' : 'Not Approved' }}
                                        </span>
                                    </td>

                                    <td>
                                        <a href="{{ route('expenses.edit', $item) }}" class="btn btn-info sm" title="Edit Expense">
                                            <i class="fas fa-edit"></i>
                                        </a>
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


@endsection

@section('admin_custom_js')

<script>
    function updateDashboard() {
        const filter = document.getElementById('dateFilter').value;
        // const customRangeInputs = document.getElementById('customRangeInputs');
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
                // customRangeInputs.style.display = 'block';
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
        const approval = document.getElementById('approval').value;
        // alert(formattedStartDate + formattedEndDate);
        var url = '{{ url()->current() }}?'
        url += '&startDate=' + formattedStartDate;
        url += '&endDate=' + formattedEndDate;
        url += '&filter=' + filter;
        url += '&approval=' + approval;
        window.location.href = url;

    }
</script>



@endsection