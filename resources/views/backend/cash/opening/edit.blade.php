@extends('admin.admin_master')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<div class="page-content">
    <div class="container-fluid">

        {{-- Page Title --}}
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Edit Opening Cash</h4>
                </div>
            </div>
        </div>

        {{-- Opening Cash Form --}}
        <div class="row">
            <form method="POST" action="{{ route('cash.opening.update', $cashRegister->id) }}" id="openingForm">
                @csrf
                @method('PUT')
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            {{-- Date --}}
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Date</label>
                                <div class="form-group col-sm-10">
                                    <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" 
                                           value="{{ old('date', $cashRegister->date) }}" required>
                                    @error('date')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Cashier Name --}}
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Cashier Name</label>
                                <div class="form-group col-sm-10">
                                    <select name="cashier_id" class="form-control @error('cashier_id') is-invalid @enderror" required>
                                        <option value="">-- Select Cashier --</option>
                                        @foreach($cashiers as $cashier)
                                            <option value="{{ $cashier->id }}" 
                                                {{ old('cashier_id', $cashRegister->cashier_id) == $cashier->id ? 'selected' : '' }}>
                                                {{ $cashier->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('cashier_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>


                            {{-- Opening Balance --}}
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Opening Balance</label>
                                <div class="form-group col-sm-10">
                                    <input type="number" step="0.01" name="opening_balance" class="form-control @error('opening_balance') is-invalid @enderror"
                                           value="{{ old('opening_balance', $cashRegister->opening_balance) }}" required>
                                    @error('opening_balance')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Submit --}}
                            <input type="submit" class="btn btn-info btn-rounded waves-effect waves-light" value="Update Opening Cash">
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>

{{-- Client-side Validation --}}
<script type="text/javascript">
    $(document).ready(function() {
        $('#openingForm').validate({
            rules: {
                date: { required: true },
                cashier_name: { required: true },
                opening_balance: { required: true, number: true },
            },
            messages: {
                date: { required: 'Please select a date' },
                cashier_name: { required: 'Please enter cashier name' },
                opening_balance: { required: 'Please enter opening balance', number: 'Must be a number' },
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
</script>
@endsection
