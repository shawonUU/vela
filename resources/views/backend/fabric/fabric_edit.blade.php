@extends('admin.admin_master')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Fabric</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);"> </a></li>
                            <li class="m-2 breadcrumb-item"><a href="{{route('fabric.add')}}">BACK</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-0">Edit Fabric</h4>
                        <form method="POST" action="{{ route('fabric.update')}}" id="myForm">
                            @csrf
                            <div class="row mb-3">
                                <div class="form-group col-sm-6">
                                    <label for="example-text-input" class="col-sm-12 col-form-label">Name</label>
                                    <input name="name" class="form-control" value="{{$fabric->name}}" type="text">
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="example-text-input" class="col-sm-12 col-form-label">Description</label>
                                    <input name="description" class="form-control" value="{{$fabric->description}}" type="text">
                                </div>
                            </div>
                            <input type="hidden" name="id" value="{{ $fabric->id }}">
                            <!-- end row -->
                            <button type="submit" class="btn btn-primary"><i class="fas fa-sync-alt"></i> Update</button>
                    </div>
                </div>
            </div> <!-- end col -->
            </form>
        </div>
    </div>
</div>
<!-- Java Script validation for empty form -->
<script type="text/javascript">
    $(document).ready(function() {
        $('#myForm').validate({
            rules: {
                name: {
                    required: true,
                },
            },
            messages: {
                name: {
                    required: 'Please Enter the Fabric name',
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