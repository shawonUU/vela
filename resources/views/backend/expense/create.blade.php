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

                            <!-- Category -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Category <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <select name="category_id" id="category_id" class="form-control" required>
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

                            <!-- Article (load via AJAX) -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Article <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <select name="article_id" id="article_id" class="form-control" required>
                                        <option value="">-- Select Article --</option>
                                    </select>
                                    @error('article_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Pay To -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Pay To</label>
                                <div class="col-sm-10">
                                    <select name="pay_to_user_id" class="form-control">
                                        <option value="">-- Select Payee --</option>
                                        @foreach($payToUsers as $user)
                                            <option value="{{ $user->id }}" {{ old('pay_to_user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('pay_to_user_id')
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
                                        <option value="visa_card" {{ old('payment_method')=='visa_card' ? 'selected' : '' }}>Visa Card</option>
                                        <option value="master_card" {{ old('payment_method')=='master_card' ? 'selected' : '' }}>Master Card</option>
                                        <option value="rocket" {{ old('payment_method')=='rocket' ? 'selected' : '' }}>Rocket</option>
                                        <option value="upay" {{ old('payment_method')=='upay' ? 'selected' : '' }}>Upay</option>
                                        <option value="surecash" {{ old('payment_method')=='surecash' ? 'selected' : '' }}>Surecash</option>
                                        <option value="online" {{ old('payment_method')=='online' ? 'selected' : '' }}>Online</option>
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
                                    <input name="date" class="form-control" type="date" value="{{ old('date', date('Y-m-d')) }}" required>
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
    // Load Articles based on Category
    $('#category_id').on('change', function () {
        var categoryID = $(this).val();
        if(categoryID) {
            $.ajax({
                url: '/expenses/get-articles/'+categoryID,
                type: "GET",
                dataType: "json",
                success:function(data) {
                    $('#article_id').empty();
                    $('#article_id').append('<option value="">-- Select Article --</option>');
                    $.each(data, function(key, value) {
                        $('#article_id').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                    });
                }
            });
        } else {
            $('#article_id').empty();
            $('#article_id').append('<option value="">-- Select Article --</option>');
        }
    });
});
</script>

@endsection
