@extends('admin.admin_master')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<div class="page-content">
    <div class="container-fluid">
       <div class="row">

           <form method="POST" action="{{ route('expenses.store') }}" id="expenseForm">
                @csrf
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="card-title mb-4">Add Expense</h4>

                            <!-- Type -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Type <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <select name="type" id="expense_type" class="form-control" required>
                                        <option value="">-- Select Type --</option>
                                        <option value="daily" {{ old('type')=='daily' ? 'selected' : '' }}>Daily Expense</option>
                                        <option value="management" {{ old('type')=='management' ? 'selected' : '' }}>Management Expense</option>
                                    </select>
                                    @error('type')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Management Name -->
                            <div class="row mb-3" id="management_name_div" style="display:{{ old('type')=='management' ? 'block' : 'none' }};">
                                <label class="col-sm-2 col-form-label">Management Name <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <select name="management_name" class="form-control">
                                        <option value="">-- Select Management --</option>
                                        @foreach($managements as $management)
                                            <option value="{{ $management['name'] }}" {{ old('management_name')==$management['name'] ? 'selected' : '' }}>
                                                {{ $management['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('management_name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Category -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Category <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <select name="category_id" class="form-control" required>
                                        <option value="">-- Select Category --</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id')==$category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Amount -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Amount <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input name="amount" class="form-control" type="number" step="0.01" placeholder="Enter amount" value="{{ old('amount') }}" required>
                                    @error('amount')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Expense Method -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Expense Method <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <select name="payment_method" class="form-control" required>
                                        <option value="">-- Select Method --</option>
                                        <option value="cash" {{ old('payment_method')=='cash' ? 'selected' : '' }}>Cash</option>
                                        <option value="bkash" {{ old('payment_method')=='bkash' ? 'selected' : '' }}>Bkash</option>
                                        <option value="nagad" {{ old('payment_method')=='nagad' ? 'selected' : '' }}>Nagad</option>
                                        <option value="bank" {{ old('payment_method')=='bank' ? 'selected' : '' }}>Bank</option>
                                    </select>
                                    @error('payment_method')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Date -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Date <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input name="date" class="form-control" type="date" value="{{ old('date') }}" required>
                                    @error('date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Note -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Note</label>
                                <div class="col-sm-10">
                                    <textarea name="note" class="form-control" placeholder="Optional note">{{ old('note') }}</textarea>
                                    @error('note')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Submit -->
                            <div class="row">
                                <div class="col-sm-12 text-end">
                                    <input type="submit" class="btn btn-info btn-rounded waves-effect waves-light" value="Add Expense">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </form>

        </div>
                
    </div>
</div>

<script type="text/javascript">
$(document).ready(function () {
    // Show/Hide management name based on type
    function toggleManagement() {
        if ($('#expense_type').val() === 'management') {
            $('#management_name_div').slideDown();
        } else {
            $('#management_name_div').slideUp();
            $('#management_name_div select').val('');
        }
    }

    $('#expense_type').on('change', toggleManagement);

    // Call on page load in case of old data
    toggleManagement();
});
</script>

@endsection
