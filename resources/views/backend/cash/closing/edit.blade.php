@extends('admin.admin_master')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<div class="page-content">
    <div class="container-fluid">

        {{-- Page Title --}}
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">{{ isset($cashRegister) ? 'Edit Closing Cash' : 'Add Closing Cash' }}</h4>
                </div>
            </div>
        </div>

        {{-- Closing Cash Form --}}
        <div class="row">
            <form method="POST" 
                  action="{{ isset($cashRegister) ? route('cash.closing.update', $cashRegister->id) : route('cash.closing.store') }}" 
                  id="closingForm">
                @csrf
                @if(isset($cashRegister))
                    @method('PUT')
                @endif
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            {{-- Date --}}
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Date</label>
                                <div class="form-group col-sm-10">
                                    <input type="date" name="date" id="date" class="form-control" required
                                        value="{{ old('date', $cashRegister->date ?? date('Y-m-d')) }}">
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
                                            <option value="{{ $cashier->id }}" 
                                                {{ old('cashier_id', $cashRegister->cashier_id ?? '') == $cashier->id ? 'selected' : '' }}>
                                                {{ $cashier->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('cashier_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            {{-- Opening Balance --}}
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Opening Balance</label>
                                <div class="form-group col-sm-10">
                                    <input type="number" step="0.01" name="opening_balance" id="opening_balance" 
                                        class="form-control" value="{{ old('opening_balance', $cashRegister->opening_balance ?? 0) }}">
                                    @error('opening_balance')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            {{-- Closing Balance --}}
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Closing Balance</label>
                                <div class="form-group col-sm-10">
                                    <input type="number" step="0.01" name="closing_balance" class="form-control" 
                                        value="{{ old('closing_balance', $cashRegister->closing_balance ?? 0) }}" 
                                        readonly id="closing_balance">
                                    <small class="text-muted">Auto calculate: Opening + Total Invoice - Total Expenses</small>
                                    @error('closing_balance')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            {{-- Submit --}}
                            <input type="submit" class="btn btn-info btn-rounded waves-effect waves-light" 
                                   value="{{ isset($cashRegister) ? 'Update Closing Cash' : 'Add Closing Cash' }}">
                        </div>
                    </div>
                </div>
            </form>
        </div>


    </div>
</div>

{{-- Form Validation and Closing Balance Calculation --}}
<script type="text/javascript">
$(document).ready(function() {
    $('#closingForm').validate({
        rules: {
            date: { required: true },
            cashier_id: { required: true },
            closing_balance: { required: true, number: true },
        },
        messages: {
            date: { required: 'Please select a date' },
            cashier_id: { required: 'Please select a cashier' },
            closing_balance: { required: 'Closing balance is required' },
        },
        errorElement: 'span',
        errorPlacement: function(error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function(element) { $(element).addClass('is-invalid'); },
        unhighlight: function(element) { $(element).removeClass('is-invalid'); },
    });

    // Ajker date set kora (only for add)
    @if(!isset($cashRegister))
    let today = new Date().toISOString().split('T')[0];
    $('#date').val(today);
    @endif

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
                    let totalInvoice = parseFloat(data.total_invoice_amount) || 0;
                    let totalExpenses = parseFloat(data.total_expenses) || 0;
                    let closing = opening + totalInvoice - totalExpenses;
                    $('#closing_balance').val(closing.toFixed(2));
                },
                error: function(err) {
                    console.error(err);
                }
            });
        }
    }

    calculateClosing();

    $('#date, #opening_balance').change(function() {
        calculateClosing();
    });
});
</script>

@endsection
