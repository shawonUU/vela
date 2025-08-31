@extends('admin.admin_master')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<div class="page-content">
    <div class="container-fluid">
       <div class="row">

           <form method="POST" action="{{ route('expenses.store') }}">
                @csrf
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="card-title">Add Expense</h4> <br><br>

                            <!-- Category -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Category</label>
                                <div class="col-sm-10">
                                    <select name="category_id" class="form-control" required>
                                        <option value="">-- Select Category --</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Amount -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Amount</label>
                                <div class="col-sm-10">
                                    <input name="amount" class="form-control" type="number" step="0.01" placeholder="Enter amount" required>
                                </div>
                            </div>

                            <!-- Date -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Date</label>
                                <div class="col-sm-10">
                                    <input name="date" class="form-control" type="date" required>
                                </div>
                            </div>

                            <!-- Note -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Note</label>
                                <div class="col-sm-10">
                                    <textarea name="note" class="form-control" placeholder="Optional note"></textarea>
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


<!-- Java Script validation for empty form -->
<script type="text/javascript">
    $(document).ready(function (){
        $('#myForm').validate({
            rules: {
                name: {
                    required : true,
                }, 
 
            },
            messages :{
                name: {
                    required : 'Please Enter the unit name',
                },
            },
            errorElement : 'span', 
            errorPlacement: function (error,element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight : function(element, errorClass, validClass){
                $(element).addClass('is-invalid');
            },
            unhighlight : function(element, errorClass, validClass){
                $(element).removeClass('is-invalid');
            },
        });
    });
    
</script>


@endsection