@extends('admin.admin_master')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<div class="page-content">
    <div class="container-fluid">
       <div class="row">
           <div class="col-12">
               <form method="POST" action="{{ isset($expense) ? route('expenses.update', $expense->id) : route('expenses.store') }}" id="expenseForm">
                    @csrf
                    @if(isset($expense))
                        @method('PUT')
                    @endif

                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">{{ isset($expense) ? 'Edit Expense' : 'Add Expense' }}</h4>

                            <!-- Type -->
                            <div class="row mb-3 align-items-center">
                                <label class="col-sm-2 col-form-label">Type <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <select name="type" id="expense_type" class="form-select" required>
                                        <option value="">-- Select Type --</option>
                                        <option value="daily" {{ (old('type') ?? $expense->type ?? '')=='daily' ? 'selected' : '' }}>Daily Expense</option>
                                        <option value="management" {{ (old('type') ?? $expense->type ?? '')=='management' ? 'selected' : '' }}>Management Expense</option>
                                    </select>
                                    @error('type')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Management Name -->
                            <div class="row mb-3 align-items-center" id="management_name_div" style="display:{{ (old('type') ?? $expense->type ?? '')=='management' ? 'flex' : 'none' }};">
                                <label class="col-sm-2 col-form-label">Management Name <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <select name="management_name" class="form-select">
                                        <option value="">-- Select Management --</option>
                                        @foreach($managements as $management)
                                            <option value="{{ $management['name'] }}" 
                                                {{ (old('management_name') ?? $expense->management_name ?? '')==$management['name'] ? 'selected' : '' }}>
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
                            <div class="row mb-3 align-items-center">
                                <label class="col-sm-2 col-form-label">Category <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <select name="category_id" class="form-select" required>
                                        <option value="">-- Select Category --</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ (old('category_id') ?? $expense->category_id ?? '')==$category->id ? 'selected' : '' }}>
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
                            <div class="row mb-3 align-items-center">
                                <label class="col-sm-2 col-form-label">Amount <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input name="amount" class="form-control" type="number" step="0.01" placeholder="Enter amount" value="{{ old('amount') ?? $expense->amount ?? '' }}" required>
                                    @error('amount')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Expense Method -->
                            <div class="row mb-3 align-items-center">
                                <label class="col-sm-2 col-form-label">Expense Method <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <select name="payment_method" class="form-select" required>
                                        <option value="">-- Select Method --</option>
                                        <option value="cash" {{ (old('payment_method') ?? $expense->payment_method ?? '')=='cash' ? 'selected' : '' }}>Cash</option>
                                        <option value="bkash" {{ (old('payment_method') ?? $expense->payment_method ?? '')=='bkash' ? 'selected' : '' }}>Bkash</option>
                                        <option value="nagad" {{ (old('payment_method') ?? $expense->payment_method ?? '')=='nagad' ? 'selected' : '' }}>Nagad</option>
                                        <option value="bank" {{ (old('payment_method') ?? $expense->payment_method ?? '')=='bank' ? 'selected' : '' }}>Bank</option>
                                    </select>
                                    @error('payment_method')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Date -->
                            <div class="row mb-3 align-items-center">
                                <label class="col-sm-2 col-form-label">Date <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input name="date" class="form-control" type="date" value="{{ old('date') ?? $expense->date ?? '' }}" required>
                                    @error('date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Note -->
                            <div class="row mb-3 align-items-start">
                                <label class="col-sm-2 col-form-label">Note</label>
                                <div class="col-sm-10">
                                    <textarea name="note" class="form-control" placeholder="Optional note">{{ old('note') ?? $expense->note ?? '' }}</textarea>
                                    @error('note')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Approval Status -->
                            <div class="row mb-3 align-items-center">
                                <label for="approval" class="col-sm-2 col-form-label">Approval Status</label>
                                <div class="col-sm-10">
                                    <select name="approval" id="approval" class="form-select">
                                        <option value="">All</option>
                                        <option value="1" {{ ($expense->is_approved === '1' ? 'selected' : '') }}>Approved</option>
                                        <option value="0" {{ ($expense->is_approved === '0' ? 'selected' : '') }}>Not Approved</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Submit -->
                            <div class="row">
                                <div class="col-sm-12 text-end">
                                    <input type="submit" class="btn btn-info btn-rounded waves-effect waves-light" value="{{ isset($expense) ? 'Update Expense' : 'Add Expense' }}">
                                </div>
                            </div>

                        </div>
                    </div>
               </form>
           </div>
       </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function () {
    function toggleManagement() {
        if ($('#expense_type').val() === 'management') {
            $('#management_name_div').slideDown();
        } else {
            $('#management_name_div').slideUp();
            $('#management_name_div select').val('');
        }
    }

    $('#expense_type').on('change', toggleManagement);
    toggleManagement();
});
</script>
@endsection
