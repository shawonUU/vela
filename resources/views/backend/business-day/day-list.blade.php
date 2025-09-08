@extends('admin.admin_master')
@section('admin')

<div class="page-content">
    <div class="container-fluid">

        {{-- Page Title --}}
        <div class="row mb-3">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <h4>Business Day List</h4>

                {{-- Date Filter Form --}}
                <form action="{{ route('business-days.list') }}" method="GET" class="d-flex">
                    <input type="date" name="from_date" class="form-control me-2" value="{{ request('from_date') }}">
                    <input type="date" name="to_date" class="form-control me-2" value="{{ request('to_date') }}">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </form>
                <form action="{{ route('business-days.reprocess') }}" method="POST" class="d-flex">
                    @csrf
                    <button type="submit" class="btn btn-warning">Reprocess Business Days</button>
                </form>
            </div>
        </div>

        {{-- Business Day Table --}}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-0">
                        <table class="table table-bordered mb-0 text-center">
                            <thead class="table-light">
                                <tr>
                                    <th>SL</th>
                                    <th>Business Date</th>
                                    <th>Opening Balance</th>
                                    <th>Closing Balance</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($businessDays as $index => $businessDay)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $businessDay->business_date }}</td>
                                        <td>{{ number_format($businessDay->opening_balance, 2) }}</td>
                                        <td>{{ number_format($businessDay->closing_balance, 2) }}</td>
                                        <td>
                                            <a href="{{route('business-days.report', $businessDay->id)}}" class="btn btn-sm btn-primary">Details</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4">No business days found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
