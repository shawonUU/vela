@extends('admin.admin_master')
@section('admin')

<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Expense Articles</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);"> </a></li>
                            <li class="breadcrumb-item m-2"><a href="{{ route('expenses.index') }}">ALL EXPENSES</a></li>
                            <li class="breadcrumb-item m-2"><a href="{{ route('expenses.article.index') }}">ALL ARTICLES</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <!-- Add Expense Article -->
        <div class="row">
            <form method="POST" action="{{ route('expenses.article.store')}}" id="myForm">
                @csrf
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="card-title">Add Expense Article</h4> <br>

                            <div class="row mb-3">
                                <label for="expense_category_id" class="col-sm-2 col-form-label">Expense Category</label>
                                <div class="form-group col-sm-10">
                                    <select name="expense_category_id" class="form-control" required>
                                        <option value="">Select Category</option>
                                        @foreach($expenseCategories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="name" class="col-sm-2 col-form-label">Article Name</label>
                                <div class="form-group col-sm-10">
                                    <input name="name" class="form-control" type="text" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="notes" class="col-sm-2 col-form-label">Notes</label>
                                <div class="form-group col-sm-10">
                                    <textarea name="notes" class="form-control" rows="3"></textarea>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="status" class="col-sm-2 col-form-label">Status</label>
                                <div class="form-group col-sm-10">
                                    <select name="status" class="form-control" required>
                                        <option value="1" selected>Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="is_approved" class="col-sm-2 col-form-label">Approveable</label>
                                <div class="form-group col-sm-10">
                                    <select name="is_approved" class="form-control" required>
                                        <option value="" selected disabled>--Choose Approval Status--</option>
                                        <option value="0">NO</option>
                                        <option value="1">YES</option>
                                    </select>
                                </div>
                            </div>

                            <input type="submit" class="btn btn-info btn-rounded waves-effect waves-light" value="Add Article">
                        </div>
                    </div>
                </div> <!-- end col -->
            </form>
        </div>

        <!-- List Expense Articles -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">All Expense Articles</h4>
                        <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Category</th>
                                    <th>Name</th>
                                    <th>Notes</th>
                                    <th>Status</th>
                                    <th>Approveable</th>
                                    <th width="20%">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($articles as $key => $item)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $item->category->name ?? '-' }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->notes }}</td>
                                    <td>
                                        @if($item->status == 1)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->is_approved == 1)
                                            <span class="badge bg-success">YES</span>
                                        @else
                                            <span class="badge bg-warning">NO</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('expenses.article.edit', $item->id) }}" class="btn btn-info sm" title="Edit Data">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{route('expenses.article.delete',$item->id)}}" class="btn btn-danger sm" title="Delete Data" id="delete"> 
                                            <i class="fas fa-trash-alt"></i> 
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->

    </div> <!-- container-fluid -->
</div>
@endsection
