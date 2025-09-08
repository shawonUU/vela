@extends('admin.admin_master')
@section('admin')

<div class="page-content">
    <div class="container-fluid">

        {{-- Page Title --}}
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Business Day Report - {{ $businessDay->business_date }}</h4>
                    <a href="{{route('business-days.pdf', $businessDay->id)}}" class="btn btn-sm btn-primary">Download pdf</a>
                </div>
            </div>
        </div>

        <div class="row">

            {{-- Opening Details --}}
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">Details</div>
                    <div class="card-body p-0">
                        <table class="table table-bordered mb-0 text-center">
                            <thead class="table-light">
                                <tr>
                                    <th>Method</th>
                                    <th>Opening</th>
                                    <th>Sales</th>
                                    <th>Expense</th>
                                    <th>Closing</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Cash</td>
                                    <td>{{ number_format($businessDay->opening_cash, 2) }}</td>
                                    <td>{{ number_format($payment['cash'], 2) }}</td>
                                    <td>{{ number_format($expense['cash'], 2) }}</td>
                                    <td>{{ number_format($closing['closing_cash'], 2) }}</td>
                                </tr>
                                <tr>
                                    <td>Visa Card</td>
                                    <td>{{ number_format($businessDay->opening_visa_card, 2) }}</td>
                                    <td>{{ number_format($payment['visa_card'], 2) }}</td>
                                    <td>{{ number_format($expense['visa_card'], 2) }}</td>
                                    <td>{{ number_format($closing['closing_visa_card'], 2) }}</td>
                                </tr>
                                <tr>
                                    <td>Master Card</td>
                                    <td>{{ number_format($businessDay->opening_master_card, 2) }}</td>
                                    <td>{{ number_format($payment['master_card'], 2) }}</td>
                                    <td>{{ number_format($expense['master_card'], 2) }}</td>
                                    <td>{{ number_format($closing['closing_master_card'], 2) }}</td>
                                </tr>
                                <tr>
                                    <td>Bkash</td>
                                    <td>{{ number_format($businessDay->opening_bkash, 2) }}</td>
                                    <td>{{ number_format($payment['bkash'], 2) }}</td>
                                    <td>{{ number_format($expense['bkash'], 2) }}</td>
                                    <td>{{ number_format($closing['closing_bkash'], 2) }}</td>
                                </tr>
                                <tr>
                                    <td>Nagad</td>
                                    <td>{{ number_format($businessDay->opening_nagad, 2) }}</td>
                                    <td>{{ number_format($payment['nagad'], 2) }}</td>
                                    <td>{{ number_format($expense['nagad'], 2) }}</td>
                                    <td>{{ number_format($closing['closing_nagad'], 2) }}</td>
                                </tr>
                                <tr>
                                    <td>Rocket</td>
                                    <td>{{ number_format($businessDay->opening_rocket, 2) }}</td>
                                    <td>{{ number_format($payment['rocket'], 2) }}</td>
                                    <td>{{ number_format($expense['Rocket'], 2) }}</td>
                                    <td>{{ number_format($closing['closing_rocket'], 2) }}</td>
                                </tr>
                                <tr>
                                    <td>Upay</td>
                                    <td>{{ number_format($businessDay->opening_upay, 2) }}</td>
                                    <td>{{ number_format($payment['upay'], 2) }}</td>
                                    <td>{{ number_format($expense['Upay'], 2) }}</td>
                                    <td>{{ number_format($closing['closing_upay'], 2) }}</td>
                                </tr>
                                <tr>
                                    <td>SureCash</td>
                                    <td>{{ number_format($businessDay->opening_surecash, 2) }}</td>
                                    <td>{{ number_format($payment['surecash'], 2) }}</td>
                                    <td>{{ number_format($expense['SureCash'], 2) }}</td>
                                    <td>{{ number_format($closing['closing_surecash'], 2) }}</td>
                                </tr>
                                <tr>
                                    <td>Online</td>
                                    <td>{{ number_format($businessDay->opening_online, 2) }}</td>
                                    <td>{{ number_format($payment['online'], 2) }}</td>
                                    <td>{{ number_format($expense['online'], 2) }}</td>
                                    <td>{{ number_format($closing['closing_online'], 2) }}</td>
                                </tr>
                                <tr class="fw-bold table-secondary">
                                    <td>Total</td>
                                    <td>{{ number_format($businessDay->opening_balance, 2) }}</td>
                                    <td>{{ number_format($payment['balance'], 2) }}</td>
                                    <td>{{ number_format($expense['total'], 2) }}</td>
                                    <td>{{ number_format($closing['closing_balance'], 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>


    </div>
</div>

@endsection
