@extends('admin.admin_master')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<div class="page-content">
    <div class="container-fluid">
       <div class="row">

           <form method="POST" action="{{ route('expenses.update', $expense->id) }}" id="expenseForm">
                @csrf
                @method('PUT')
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="card-title mb-4">Edit Expense</h4>

                            <!-- Category -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Category <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <select name="category_id" id="category_id" class="form-control" required>
                                        <option value="">-- Select Category --</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ $expense->category_id == $category->id ? 'selected' : '' }}>
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
                                        @if($articles)
                                            @foreach($articles as $article)
                                                <option value="{{ $article->id }}" {{ $expense->article_id == $article->id ? 'selected' : '' }}>
                                                    {{ $article->name }}
                                                </option>
                                            @endforeach
                                        @endif
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
                                            <option value="{{ $user->id }}" {{ $expense->pay_to == $user->id ? 'selected' : '' }}>
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
                                    <input name="amount" class="form-control" type="number" step="0.01" placeholder="Enter amount" value="{{ $expense->amount }}" required>
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
                                        <option value="cash" {{ $expense->payment_method=='cash' ? 'selected' : '' }}>Cash</option>
                                        <option value="bkash" {{ $expense->payment_method=='bkash' ? 'selected' : '' }}>Bkash</option>
                                        <option value="nagad" {{ $expense->payment_method=='nagad' ? 'selected' : '' }}>Nagad</option>
                                        <option value="visa_card" {{ $expense->payment_method=='visa_card' ? 'selected' : '' }}>Visa Card</option>
                                        <option value="master_card" {{ $expense->payment_method=='master_card' ? 'selected' : '' }}>Master Card</option>
                                        <option value="rocket" {{ $expense->payment_method=='rocket' ? 'selected' : '' }}>Rocket</option>
                                        <option value="upay" {{ $expense->payment_method=='upay' ? 'selected' : '' }}>Upay</option>
                                        <option value="surecash" {{ $expense->payment_method=='surecash' ? 'selected' : '' }}>Surecash</option>
                                        <option value="online" {{ $expense->payment_method=='online' ? 'selected' : '' }}>Online</option>

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
                                    <input name="date" class="form-control" type="date" value="{{ \Carbon\Carbon::parse($expense->date)->format('Y-m-d') }}" required>

                                    @error('date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Note -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Note</label>
                                <div class="col-sm-10">
                                    <textarea name="note" class="form-control" placeholder="Optional note">{{ $expense->note }}</textarea>
                                    @error('note')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <!-- Approval Status -->
                            <div class="row mb-3 align-items-center">
                                <label for="approval" class="col-sm-2 col-form-label">Approval Status</label>
                                <div class="col-sm-10">
                                    <select name="is_approved" id="approval" class="form-select">
                                        <option value="">All</option>
                                        <option value="1" {{ (isset($expense) && $expense->is_approved == '1' ? 'selected' : '') }}>Approved</option>
                                        <option value="0" {{ (isset($expense) && $expense->is_approved == '0' ? 'selected' : '') }}>Not Approved</option>
                                    </select>
                                </div>
                            </div>
                         
                            <!-- Submit -->
                            <div class="row">
                                <div class="col-sm-12 text-end">
                                    <input type="submit" class="btn btn-info btn-rounded waves-effect waves-light" value="Update Expense">
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
    function loadArticles(categoryID, selectedArticle = null) {
        if(categoryID) {
            $.ajax({
                url: '/expenses/get-articles/'+categoryID,
                type: "GET",
                dataType: "json",
                success:function(data) {
                    $('#article_id').empty();
                    $('#article_id').append('<option value="">-- Select Article --</option>');
                    $.each(data, function(key, value) {
                        let selected = selectedArticle == value.id ? 'selected' : '';
                        $('#article_id').append('<option value="'+ value.id +'" '+selected+'>'+ value.name +'</option>');
                    });
                }
            });
        } else {
            $('#article_id').empty();
            $('#article_id').append('<option value="">-- Select Article --</option>');
        }
    }

    // Load articles on category change
    $('#category_id').on('change', function () {
        var categoryID = $(this).val();
        loadArticles(categoryID);
    });

    // Load articles initially for the selected category
    var initialCategory = $('#category_id').val();
    var initialArticle = '{{ $expense->article_id }}';
    loadArticles(initialCategory, initialArticle);
});
</script>

@endsection
