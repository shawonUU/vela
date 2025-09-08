@extends('admin.admin_master')
@section('admin')

<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Edit Pay To User</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('payto.users.index') }}">Back</a></li>
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

                        <form method="POST" action="{{ route('payto.users.update', $user->id) }}" id="myForm">
                            @csrf
                            @method('PUT')

                            <!-- Name -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Name <span class="text-danger">*</span></label>
                                <div class="form-group col-sm-10">
                                    <input name="name" class="form-control" type="text" value="{{ old('name', $user->name) }}" required>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Email</label>
                                <div class="form-group col-sm-10">
                                    <input name="email" class="form-control" type="email" value="{{ old('email', $user->email) }}">
                                </div>
                            </div>

                            <!-- Phone -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Phone</label>
                                <div class="form-group col-sm-10">
                                    <input name="phone" class="form-control" type="text" value="{{ old('phone', $user->phone) }}">
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Address</label>
                                <div class="form-group col-sm-10">
                                    <textarea name="address" class="form-control">{{ old('address', $user->address) }}</textarea>
                                </div>
                            </div>

                            <input type="submit" class="btn btn-info btn-rounded waves-effect waves-light" value="Update">

                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
