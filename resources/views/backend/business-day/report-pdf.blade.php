<!DOCTYPE html>
<html>
<head>
    <title>Business Day Report - {{ $businessDay->business_date }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: center; }
        th { background-color: #f0f0f0; color:#000;}
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
<h4>Details</h4>
<table>
    <tr class="header">
        <th >Method</th>
        <th>Opening</th>
        <th>Sales</th>
        <th>Expense</th>
        <th>Closing</th>
    </tr>

    
    <tr>
        <td>Cash</td>
        <td>{{ number_format($businessDay->opening_cash, 2) }}</td>
        <td>{{ number_format($payment['cash'], 2) }}</td>
        <td>{{ number_format($expense['cash'], 2) }}</td>
        <td>{{ number_format($closing['closing_cash'], 2) }}</td>
    </tr>
    <tr>
        <td>Visa Card</td>
        <td>{{ number_format($businessDay->opening_visa_card, 2) }}</td>
        <td>{{ number_format($payment['visa_card'], 2) }}</td>
        <td>{{ number_format($expense['visa_card'], 2) }}</td>
        <td>{{ number_format($closing['closing_visa_card'], 2) }}</td>
    </tr>
    <tr>
        <td>Master Card</td>
        <td>{{ number_format($businessDay->opening_master_card, 2) }}</td>
        <td>{{ number_format($payment['master_card'], 2) }}</td>
        <td>{{ number_format($expense['master_card'], 2) }}</td>
        <td>{{ number_format($closing['closing_master_card'], 2) }}</td>
    </tr>
    <tr>
        <td>Bkash</td>
        <td>{{ number_format($businessDay->opening_bkash, 2) }}</td>
        <td>{{ number_format($payment['bkash'], 2) }}</td>
        <td>{{ number_format($expense['bkash'], 2) }}</td>
        <td>{{ number_format($closing['closing_bkash'], 2) }}</td>
    </tr>
    <tr>
        <td>Nagad</td>
        <td>{{ number_format($businessDay->opening_nagad, 2) }}</td>
        <td>{{ number_format($payment['nagad'], 2) }}</td>
        <td>{{ number_format($expense['nagad'], 2) }}</td>
        <td>{{ number_format($closing['closing_nagad'], 2) }}</td>
    </tr>
    <tr>
        <td>Rocket</td>
        <td>{{ number_format($businessDay->opening_rocket, 2) }}</td>
        <td>{{ number_format($payment['rocket'], 2) }}</td>
        <td>{{ number_format($expense['Rocket'], 2) }}</td>
        <td>{{ number_format($closing['closing_rocket'], 2) }}</td>
    </tr>
    <tr>
        <td>Upay</td>
        <td>{{ number_format($businessDay->opening_upay, 2) }}</td>
        <td>{{ number_format($payment['upay'], 2) }}</td>
        <td>{{ number_format($expense['Upay'], 2) }}</td>
        <td>{{ number_format($closing['closing_upay'], 2) }}</td>
    </tr>
    <tr>
        <td>SureCash</td>
        <td>{{ number_format($businessDay->opening_surecash, 2) }}</td>
        <td>{{ number_format($payment['surecash'], 2) }}</td>
        <td>{{ number_format($expense['SureCash'], 2) }}</td>
        <td>{{ number_format($closing['closing_surecash'], 2) }}</td>
    </tr>
    <tr>
        <td>Online</td>
        <td>{{ number_format($businessDay->opening_online, 2) }}</td>
        <td>{{ number_format($payment['online'], 2) }}</td>
        <td>{{ number_format($expense['online'], 2) }}</td>
        <td>{{ number_format($closing['closing_online'], 2) }}</td>
    </tr>
    <tr>
        <td>Total</td>
        <td>{{ number_format($businessDay->opening_balance, 2) }}</td>
        <td>{{ number_format($payment['balance'], 2) }}</td>
        <td>{{ number_format($expense['total'], 2) }}</td>
        <td>{{ number_format($closing['closing_balance'], 2) }}</td>
    </tr>
                            

</table>





</body>
</html>
