<!DOCTYPE html>
<html>
<head>
    <title>Business Day Report - {{ $businessDay->business_date }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: center; }
        th { background-color: #f0f0f0; }
        .header { background-color: #007bff; color: #fff; font-weight: bold; }
        .success { background-color: #28a745; color: #fff; font-weight: bold; }
        .danger { background-color: #dc3545; color: #fff; font-weight: bold; }
        .dark { background-color: #343a40; color: #fff; font-weight: bold; }
        .total { font-weight: bold; background-color: #e2e2e2; }
    </style>
</head>
<body>

<h2 style="text-align:center;">Business Day Report</h2>
<h4 style="text-align:center;">Date: {{ $businessDay->business_date }}</h4>

{{-- Opening --}}
<h4>Opening Details</h4>
<table>
    <tr class="header">
        <th>Method</th>
        <th>Opening</th>
    </tr>
    @foreach(['cash','visa_card','master_card','bkash','nagad','rocket','upay','surecash','online'] as $method)
        <tr>
            <td>{{ ucfirst(str_replace('_', ' ', $method)) }}</td>
            <td>{{ number_format($businessDay->{'opening_'.$method},2) }}</td>
        </tr>
    @endforeach
    <tr class="total">
        <td>Total</td>
        <td>{{ number_format($businessDay->opening_balance,2) }}</td>
    </tr>
</table>

{{-- Payments --}}
<h4>Sales</h4>
<table>
    <tr class="success">
        <th>Method</th>
        <th>Amount</th>
    </tr>
    @foreach(['cash','visa_card','master_card','bkash','nagad','rocket','upay','surecash','online'] as $method)
        <tr>
            <td>{{ ucfirst(str_replace('_', ' ', $method)) }}</td>
            <td>{{ number_format($payment[$method],2) }}</td>
        </tr>
    @endforeach
    <tr class="total">
        <td>Total</td>
        <td>{{ number_format($payment['balance'],2) }}</td>
    </tr>
</table>

{{-- Expenses --}}
<h4>Expenses</h4>
<table>
    <tr class="danger">
        <th>Method</th>
        <th>Amount</th>
    </tr>
    @foreach(['cash','visa_card','master_card','bkash','nagad','Rocket','Upay','SureCash','online'] as $method)
        <tr>
            <td>{{ $method }}</td>
            <td>{{ number_format($expense[$method] ?? 0,2) }}</td>
        </tr>
    @endforeach
    <tr class="total">
        <td>Total</td>
        <td>{{ number_format($expense['total'],2) }}</td>
    </tr>
</table>

{{-- Closing --}}
<h4>Closing Details</h4>
<table>
    <tr class="dark">
        <th>Method</th>
        <th>Closing</th>
    </tr>
    @foreach(['cash','visa_card','master_card','bkash','nagad','rocket','upay','surecash','online'] as $method)
        <tr>
            <td>{{ ucfirst(str_replace('_', ' ', $method)) }}</td>
            <td>{{ number_format($closing['closing_'.$method],2) }}</td>
        </tr>
    @endforeach
    <tr class="total">
        <td>Total</td>
        <td>{{ number_format($closing['closing_balance'],2) }}</td>
    </tr>
</table>

</body>
</html>
