@extends('admin.admin_master')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<div class="page-content">
    <div class="container-fluid">
       <div class="row">

           <form method="POST" action="{{ route('expenses.update', $expense->id) }}">
                @csrf
                @method('PUT') <!-- PUT method for update -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="card-title">Edit Expense</h4> <br><br>

                            <!-- Category -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Category</label>
                                <div class="col-sm-10">
                                    <select name="category_id" class="form-control" required>
                                        <option value="">-- Select Category --</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" 
                                                {{ $category->id == $expense->category_id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Amount -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Amount</label>
                                <div class="col-sm-10">
                                    <input name="amount" class="form-control" type="number" step="0.01" 
                                           value="{{ $expense->amount }}" required>
                                </div>
                            </div>

                            <!-- Date -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Date</label>
                                <div class="col-sm-10">
                                    <input name="date" class="form-control" type="date" 
                                           value="{{ $expense->date }}" required>
                                </div>
                            </div>

                            <!-- Note -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Note</label>
                                <div class="col-sm-10">
                                    <textarea name="note" class="form-control" placeholder="Optional note">{{ $expense->note }}</textarea>
                                </div>
                            </div>

                            <div class="row mb-3">
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
                                    <input type="submit" class="btn btn-success btn-rounded waves-effect waves-light" value="Update Expense">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </form>

        </div>
                
    </div>
</div>

@endsection
