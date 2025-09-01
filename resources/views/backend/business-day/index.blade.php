@extends('admin.admin_master')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<div class="page-content">
    <div class="container-fluid">

        {{-- Page Title --}}
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Business Day</h4>
                </div>
            </div>
        </div>

        {{-- Closing Cash Form --}}
        <div class="row">

            @if(true)
                <div class="card-body p-0">
                    <table class="table table-striped table-bordered mb-0 text-center align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Payment Method</th>
                                <th>Opening Balance</th>
                                <th>Closing Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Cash</td>
                                <td>{{ number_format($businessDay->opening_cash, 2) }}</td>
                                <td>{{ number_format($businessDay->opening_cash, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Visa Card</td>
                                <td>{{ number_format($businessDay->opening_visa_card, 2) }}</td>
                                <td>{{ number_format($businessDay->opening_visa_card, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Master Card</td>
                                <td>{{ number_format($businessDay->opening_master_card, 2) }}</td>
                                <td>{{ number_format($businessDay->opening_master_card, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Bkash</td>
                                <td>{{ number_format($businessDay->opening_bkash, 2) }}</td>
                                <td>{{ number_format($businessDay->opening_bkash, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Nagad</td>
                                <td>{{ number_format($businessDay->opening_nagad, 2) }}</td>
                                <td>{{ number_format($businessDay->opening_nagad, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Rocket</td>
                                <td>{{ number_format($businessDay->opening_rocket, 2) }}</td>
                                <td>{{ number_format($businessDay->opening_rocket, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Upay</td>
                                <td>{{ number_format($businessDay->opening_upay, 2) }}</td>
                                <td>{{ number_format($businessDay->opening_upay, 2) }}</td>
                            </tr>
                            <tr>
                                <td>SureCash</td>
                                <td>{{ number_format($businessDay->opening_surecash, 2) }}</td>
                                <td>{{ number_format($businessDay->opening_surecash, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Online</td>
                                <td>{{ number_format($businessDay->opening_online, 2) }}</td>
                                <td>{{ number_format($businessDay->opening_online, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Total</td>
                                <td>{{ number_format($businessDay->opening_balance, 2) }}</td>
                                <td>{{ number_format($businessDay->opening_balance, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endif

            @if(false)
                <div class="card-body p-0">
                    <table class="table table-striped table-bordered mb-0 text-center align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Payment Method</th>
                                <th>Opening Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Cash</td>
                                <td>{{ number_format($closedDay->opening_cash, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Visa Card</td>
                                <td>{{ number_format($closedDay->opening_visa_card, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Master Card</td>
                                <td>{{ number_format($closedDay->opening_master_card, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Bkash</td>
                                <td>{{ number_format($closedDay->opening_bkash, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Nagad</td>
                                <td>{{ number_format($closedDay->opening_nagad, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Rocket</td>
                                <td>{{ number_format($closedDay->opening_rocket, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Upay</td>
                                <td>{{ number_format($closedDay->opening_upay, 2) }}</td>
                            </tr>
                            <tr>
                                <td>SureCash</td>
                                <td>{{ number_format($closedDay->opening_surecash, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Online</td>
                                <td>{{ number_format($closedDay->opening_online, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Total</td>
                                <td>{{ number_format($closedDay->opening_balance, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endif






            <form method="POST" action="{{ route(isDayOpen() ? 'business-days.close' : 'business-days.open') }}" id="closingForm">
                @csrf
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            
                            {{-- Submit --}}
                            <div class="d-flex justify-content-center">
                                <input type="submit" class="btn btn-info btn-rounded waves-effect waves-light" value="{{ isDayOpen() ? 'Closing Day' : 'Opening Day'}}">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

       

    </div>
</div>

{{-- Form Validation --}}
<script type="text/javascript">
    $(document).ready(function() {
        $('#closingForm').validate({
            rules: {
                date: { required: true },
                cashier_name: { required: true },
                closing_balance: { required: true, number: true },
            },
            messages: {
                date: { required: 'Please select a date' },
                cashier_name: { required: 'Please enter cashier name' },
                closing_balance: { required: 'Please enter closing balance' },
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element) { $(element).addClass('is-invalid'); },
            unhighlight: function(element) { $(element).removeClass('is-invalid'); },
        });
    });

$(document).ready(function() {
    // Ajker date set kora
    let today = new Date().toISOString().split('T')[0];
    $('#date').val(today);

    // Function to calculate closing balance
    function calculateClosing() {
        let date = $('#date').val();
        let opening = parseFloat($('#opening_balance').val()) || 0;

        if(date){
            $.ajax({
                url: '{{ route("cash.getClosingBalance") }}',
                type: 'GET',
                data: { date: date },
                success: function(data) {
                    console.log(data)
                    let totalSales = parseFloat(data.total_invoice_amount) || 0;
                    let totalExpenses = parseFloat(data.total_expenses) || 0;
                    let closing = opening + totalSales - totalExpenses;
                    $('#closing_balance').val(closing.toFixed(2));
                },
                error: function(err) {
                    console.error(err);
                }
            });
        }
    }

    // Page load e closing balance calculate koro
    calculateClosing();

    // Date or opening balance change holeo recalc koro
    $('#date, #opening_balance').change(function() {
        calculateClosing();
    });
});



</script>

@endsection
