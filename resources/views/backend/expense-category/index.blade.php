@extends('admin.admin_master')
@section('admin')

<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Expense Categories</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);"> </a></li>
                            <li class="breadcrumb-item m-2"><a href="{{ route('expenses.index') }}">ALL EXPENSES</a></li>
                            <li class="breadcrumb-item m-2"><a href="{{ route('expenses.category.index') }}">ALL CATEGORIES</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <!-- Add Expense Category -->
        <div class="row">
            <form method="POST" action="{{ route('expenses.category.store')}}" id="myForm">
                @csrf
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="card-title">Add Expense Category</h4> <br>
                            <div class="row mb-3">
                                <label for="name" class="col-sm-2 col-form-label">Category Name</label>
                                <div class="form-group col-sm-10">
                                    <input name="name" class="form-control" type="text" required>
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

                            <input type="submit" class="btn btn-info btn-rounded waves-effect waves-light" value="Add Category">
                        </div>
                    </div>
                </div> <!-- end col -->
            </form>
        </div>

        <!-- List Expense Categories -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">All Expense Categories</h4>
                        <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th width="20%">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($categories as $key => $item)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>
                                        @if($item->status == 1)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('expenses.category.edit', $item->id) }}" class="btn btn-info sm" title="Edit Data">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{route('expenses.category.delete',$item->id)}}" class="btn btn-danger sm" title="Delete Data" id="delete"> <i class="fas fa-trash-alt"></i> </a>




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
