@extends('admin.admin_master')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<div class="page-content">
    <div class="container-fluid">

        {{-- Page Title --}}
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Add Closing Cash</h4>
                </div>
            </div>
        </div>

        {{-- Closing Cash Form --}}
        <div class="row">
            <form method="POST" action="{{ route('cash.closing.store') }}" id="closingForm">
                @csrf
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            {{-- Date --}}
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Date</label>
                                <div class="form-group col-sm-10">
                                    <input type="date" name="date" id="date" class="form-control" required value="{{ old('date', date('Y-m-d')) }}">
                                    @error('date')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            {{-- Cashier --}}
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Cashier Name</label>
                                <div class="form-group col-sm-10">
                                    <select name="cashier_id" class="form-control" required>
                                        <option value="">Select Cashier</option>
                                        @foreach($cashiers as $cashier)
                                            <option value="{{ $cashier->id }}" {{ old('cashier_id') == $cashier->id ? 'selected' : '' }}>
                                                {{ $cashier->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('cashier_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            {{-- Closing Balance --}}
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Closing Balance</label>
                                <div class="form-group col-sm-10">
                                    <input type="number" step="0.01" name="closing_balance" class="form-control" 
                                        value="0" readonly id="closing_balance">
                                    <small class="text-muted">Auto calculate: Opening + Total Sales - Total Expenses</small>
                                    @error('closing_balance')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            {{-- Submit --}}
                            <input type="submit" class="btn btn-info btn-rounded waves-effect waves-light" value="Add Closing Cash">
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- Closing Cash Table --}}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">All Closing Cash Records</h4>
                        <table id="datatable" class="table table-bordered dt-responsive nowrap"
                               style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Date</th>
                                    <th>Cashier Name</th>
                                    <th>Opening Balance</th>
                                    <th>Total Sales</th>
                                    <th>Total Expenses</th>
                                    <th>Closing Balance</th>
                                    <th>Status</th>
                                    <th width="20%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cashRegisters as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->date }}</td>
                                    <td>{{ $item->cashier->name ?? $item->cashier_name }}</td>
                                    <td>{{ $item->opening_balance }}</td>
                                    <td>{{ $item->total_sales }}</td>
                                    <td>{{ $item->total_expenses }}</td>
                                    <td>{{ $item->closing_balance }}</td>
                                    <td>{{ $item->status }}</td>
                                    <td>
                                        <a href="{{ route('cash.closing.edit', $item->id) }}" class="btn btn-info sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('cash.closing.delete', $item->id) }}" class="btn btn-danger sm" id="delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
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
