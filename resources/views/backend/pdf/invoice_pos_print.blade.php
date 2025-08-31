<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>POS Invoice Print</title>

    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            width: 380px;
            margin: 0 auto;
            padding: 0;
            background: #fff;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        .dashed-line {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            padding: 2px 4px;
        }

        @media print {
            body {
                margin: 0;
                color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>

    <script>
        function printDiv() {
            var printContents = document.getElementById('printableArea').innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }

        window.onload = printDiv;

        function redirectAfterPrint() {
            window.location.href = '{{ route("invoice.add") }}';
        }

        setTimeout(redirectAfterPrint, 1000);
    </script>
</head>

<body>
    @php $payment = App\Models\Payment::where('invoice_id', $invoice->id)->first(); @endphp

    <div id="printableArea">
        <div class="text-center">
            <strong>Ovee Electric Enterprise</strong><br>
            Proprietor: Foyez Ullah Miazi<br>
            Munshirhat, Fulgazi, Feni<br>
            01717323252
        </div>

        <div class="dashed-line"></div>

        <div class="text-left">
            Invoice #: {{ $invoice->invoice_no }}<br>
            Date: {{ date('d/m/y', strtotime($invoice->date)) }}<br>
            Customer: {{ $payment->customer_id != -1 ? $payment['customer']['name'] : 'Walking Customer' }}
        </div>

        <div class="dashed-line"></div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Item</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">UP</th>
                    <th class="text-right">Dis</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @php $sl = 1; $total_sum = 0; @endphp
                @foreach($invoice['invoice_details'] as $details)
                <tr>
                    <td>{{ $sl++ }}</td>
                    <td>
                        {{ $details['product']['name'] }}
                        @if (!empty($details['product']['brand']['name']))
                        ({{ $details['product']['brand']['name'] }})
                        @endif
                    </td>
                    <td class="text-right">{{ $details->selling_qty }}</td>
                    <td class="text-right">{{ number_format($details->unit_price, 1) }}</td>
                    <td class="text-right">{{ number_format($details->total_sell_commission, 1) }}</td>
                    <td class="text-right">{{ number_format($details->selling_price, 1) }}</td>
                </tr>
                @php $total_sum += $details->selling_price; @endphp
                @endforeach
            </tbody>
        </table>

        <div class="dashed-line"></div>

        <table>
            <tr>
                <td class="text-left" colspan="4">Total:</td>
                <td class="text-right" colspan="2">{{ number_format($total_sum, 2) }} Tk</td>
            </tr>
            <tr>
                <td class="text-left" colspan="4">Discount:</td>
                <td class="text-right" colspan="2">{{ number_format($payment->discount_amount, 2) }} Tk</td>
            </tr>
            <tr>
                <td class="text-left" colspan="4">Payable:</td>
                <td class="text-right" colspan="2">{{ number_format($payment->total_amount, 2) }} Tk</td>
            </tr>
            <tr>
                <td class="text-left" colspan="4">Paid:</td>
                <td class="text-right" colspan="2">{{ number_format($payment->paid_amount, 2) }} Tk</td>
            </tr>
            <tr>
                <td class="text-left" colspan="4">Due:</td>
                <td class="text-right" colspan="2">{{ number_format($payment->due_amount, 2) }} Tk</td>
            </tr>
            @if ($payment->customer_id != -1)
            <tr>
                <td class="text-left" colspan="4">Prev. Due:</td>
                <td class="text-right" colspan="2">{{ number_format($pre_due, 2) }} Tk</td>
            </tr>
            <tr>
                <td class="text-left" colspan="4"><strong>Total Due:</strong></td>
                <td class="text-right" colspan="2"><strong>{{ number_format($pre_due + $payment->due_amount, 2) }} Tk</strong></td>
            </tr>
            @endif
        </table>

        <div class="dashed-line"></div>

        <div class="text-center">
            <strong>Thank You!</strong><br>
            Software: Munsoft BD, 01815229363
        </div>
    </div>
</body>

</html>