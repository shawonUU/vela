@extends('admin.admin_master')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Edit Expense Category</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);"></a></li>
                            <li class="m-2 breadcrumb-item"><a href="{{ route('expenses.category.index') }}"> BACK </a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <form method="POST" action="{{ route('expenses.category.update', $expenseCategory->id) }}" id="myForm">
                            @csrf
                            @method('PUT')

                            <div class="row mb-3">
                                <label for="name" class="col-sm-2 col-form-label">Category Name</label>
                                <div class="form-group col-sm-10">
                                    <input name="name" class="form-control" value="{{ $expenseCategory->name }}" type="text" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="status" class="col-sm-2 col-form-label">Status</label>
                                <div class="form-group col-sm-10">
                                    <select name="status" class="form-control" required>
                                        <option value="1" {{ $expenseCategory->status == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ $expenseCategory->status == 0 ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <input type="submit" class="btn btn-info btn-rounded waves-effect waves-light" value="Update Category">

                        </form>

                    </div>
                </div>
            </div> <!-- end col -->
        </div>

    </div>
</div>


<!-- JavaScript validation -->
<script type="text/javascript">
    $(document).ready(function() {
        $('#myForm').validate({
            rules: {
                name: {
                    required: true,
                },
                status: {
                    required: true,
                },
            },
            messages: {
                name: {
                    required: 'Please enter the category name',
                },
                status: {
                    required: 'Please select a status',
                },
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            },
        });
    });
</script>

@endsection
