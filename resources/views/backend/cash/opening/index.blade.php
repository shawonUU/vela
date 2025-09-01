@extends('admin.admin_master')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<div class="page-content">
    <div class="container-fluid">

        {{-- Page Title --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Add Opening Cash</h4>
                </div>
            </div>
        </div>

        {{-- Opening Cash Form --}}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                      <form method="POST" action="{{ route('cash.opening.store') }}" id="openingForm">
    @csrf

    {{-- Date --}}
    <div class="row mb-3">
        <label class="col-sm-2 col-form-label">Date</label>
        <div class="col-sm-10">
            <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" 
                   value="{{ old('date', date('Y-m-d')) }}" required>
            @error('date')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>

    {{-- Cashier --}}
    <div class="row mb-3">
        <label class="col-sm-2 col-form-label" for="cashier_id">Cashier</label>
        <div class="col-sm-10">
            <select name="cashier_id" id="cashier_id" class="form-control @error('cashier_id') is-invalid @enderror" required>
                <option value="">-- Select Cashier --</option>
                @foreach($cashiers as $cashier)
                    <option value="{{ $cashier->id }}" {{ old('cashier_id') == $cashier->id ? 'selected' : '' }}>
                        {{ $cashier->name }}
                    </option>
                @endforeach
            </select>
            @error('cashier_id')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>

    {{-- Opening Balance --}}
    <div class="row mb-3">
        <label class="col-sm-2 col-form-label">Opening Balance</label>
        <div class="col-sm-10">
            <input type="number" step="0.01" name="opening_balance" 
                   class="form-control @error('opening_balance') is-invalid @enderror"
                   value="{{ old('opening_balance') }}" required>
            @error('opening_balance')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>

    {{-- Submit --}}
    <div class="row">
        <div class="col-sm-10 offset-sm-2">
            <button type="submit" class="btn btn-info btn-rounded waves-effect waves-light">
                Add Opening Cash
            </button>
        </div>
    </div>
</form>

                    </div>
                </div>
            </div>
        </div>

        {{-- Opening Cash Table --}}
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-3">All Opening Cash Records</h4>
                        <div class="table-responsive">
                            <table id="datatable" class="table table-bordered table-striped">
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
                                            <a href="{{ route('cash.opening.edit', $item->id) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('cash.opening.delete', $item->id) }}" class="btn btn-danger btn-sm" id="delete">
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
</div>

{{-- Form Validation --}}
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
                cashier_name: { required: 'Please select a cashier' },
                opening_balance: { required: 'Please enter opening balance' },
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.col-sm-10').append(error);
            },
            highlight: function(element) { $(element).addClass('is-invalid'); },
            unhighlight: function(element) { $(element).removeClass('is-invalid'); },
        });
    });
</script>
@endsection
