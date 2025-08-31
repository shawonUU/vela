@php
$org = App\Models\OrgDetails::first();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Invoice Preview</title>
  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
  <!-- Scripts -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
  <style>
    .dashed-hr {
      border-bottom: 2px dashed #000000;
      display: block;
      margin: 5px 0;
    }

    @page {
      size: auto;
      margin: 0 15px !important;
    }

    * {
      color: #000000 !important;
      font-weight: 700 !important;
    }

    table,
    table th,
    table td {
      font-size: 13px !important;
    }

    h2,
    h3,
    h4,
    h5,
    h6 {
      font-weight: 700 !important;
    }

    .table {
      width: 100%;
      margin-bottom: 1rem;
      color: #000000;
      border-collapse: collapse;
    }

    .table td,
    .table th {
      padding: .1rem;
      vertical-align: top;
      border-top: 1px solid #000000
    }

    .table thead th {
      vertical-align: bottom;
      border-bottom: 1px solid #000000
    }

    .table tbody+tbody {
      border-top: 1px solid #000000
    }

    .table-sm td,
    .table-sm th {
      padding: .3rem
    }

    .table-bordered {
      border: 1px solid #000000
    }

    .table-bordered td,
    .table-bordered th {
      border: 1px solid #000000
    }

    .table-bordered thead td,
    .table-bordered thead th {
      border-bottom-width: 1px
    }

    .text-left {
      text-align: left !important;
    }

    .text-right {
      text-align: right !important;
    }

    .pl--0 {
      padding-left: 0 !important;
    }

    .pr--0 {
      padding-right: 0 !important;
    }

    .text-center {
      text-align: center;
    }
  </style>
  <div class="row mx-auto" id="printableArea">
    <div class="col-md-12">
      <div class="mx-auto" style="width:290px">
        <div class="text-center">
          <div class="row">
            <div class="col-12">
              <div class="invoice-title">
                <div class="text-center mb-2" style="text-align: center;">
                  <h3 class="com-name" style="font-size:30px;margin-bottom:-22px;"><strong>{{ $org->org_name_en??'N/A' }}</strong></h3> <br>
                </div>
                <h5 class="text-center font-size-10" style="margin-top:0px; margin-bottom: 8px;">
                  {{ $org->address??'N/A' }}<br>
                  Mob: {{ $org->mobile_no??'N/A' }}<br>
                </h5>
              </div>
              <span class="dashed-hr"></span>
              <style>
                .invoice-header {
                  display: flex;
                  justify-content: space-between;
                  font-size: 13px;
                  line-height: 1.5;
                  margin-bottom: 10px;
                }

                .invoice-col {
                  width: 48%;
                }

                .invoice-col address {
                  font-style: normal;
                  margin: 0;
                  padding: 0;
                }

                .text-right {
                  text-align: right;
                }
              </style>
              <div class="invoice-header">
                <div class="invoice-col text-left">
                  <address>
                    <strong>Invoice #{{ $data['invoice_no'] }}</strong><br>
                    <strong>Date:</strong> {{ date('d/m/y', strtotime($data['date'])) }}
                  </address>
                </div>
                <div class="invoice-col text-right">
                  <address>
                    <strong>Customer: {{ $data['mobile_no'] ?? 'WC' }}</strong><br>
                  </address>
                </div>
              </div>
            </div>
          </div>
        </div>
        <span class="dashed-hr"></span>
        <style>
          #pos-print-demo,
          #pos-print-demo tr,
          #pos-print-demo td,
          #pos-print-demo th {
            border: 1px dashed #000000;
          }
        </style>
        <table id="pos-print-demo" class="table table-bordered text-left" style="width: calc(100% - 1px) !important">
          <thead>
            <tr>
              <th class="m-0 p-0"><strong>#</strong></th>
              <th class="text-left m-0 p-0"><strong>Item</strong></th>
              <th class="m-0 p-0">
                <center><strong>Qty</strong></center>
              </th>
              <th class="m-0 p-0">
                <center><strong>UP</strong></center>
              </th>
              <!-- <th class="m-0 p-0">
                <center><strong>DIS</strong></center>
              </th> -->
              <th class="m-0 p-0">
                <center><strong>Price</strong></center>
              </th>
            </tr>
          </thead>
          <tbody>
            @php
            $sl = 0;
            $total_sum=0;
            @endphp
            @foreach($finalProducts as $details)
            @php
            $sl++;
            @endphp
            <tr>
              <td class="m-0 p-0 text-start">{{$sl}}</td>
              <td class="text-left">
                {{ $details->product_name }}
                @if (!empty($details->size_name))
                ({{ $details->size_name }})
                @endif
              </td>
              <td class="m-0 p-0 text-center">{{ $details->qty }}</td>
              <td class="m-0 p-0 text-center">{{ number_format($details->unit_price, 1) }}</td>
              <!-- <td class="m-0 p-0 text-center">{{ number_format($details->discount_amount, 1) }}</td> -->
              <td class="m-0 p-0 text-center">{{ number_format($details->total, 1) }}</td>
            </tr>
            @php $total_sum += $details->unit_price * $details->qty; @endphp
            @endforeach
          </tbody>
        </table>
        <span class="dashed-hr"></span>
        <table style="color: black!important; width: 100%!important">
          <tbody>
            <tr>
              <td colspan="2"></td>
              <td class="text-right">Total Price(+Inc. Vat):</td>
              <td class="text-right">{{ number_format($total_sum,2) }} Tk</td>
            </tr>
            <tr>
              <td colspan="2"></td>
              <td class="text-right">Total Discount:</td>
              <td class="text-right">{{ number_format($data['total_discount_amount'], 2) }} Tk</td>
            </tr>
            <tr>
              <td colspan="2"></td>
              <td class="text-right">Paid:</td>
              <td class="text-right">{{ number_format($data['paid_amount'], 2) }} Tk</td>
            </tr>
            <tr>
              <td colspan="2"></td>
              <td class="text-right">Due:</td>
              <td class="text-right">{{ number_format($data['due_amount'], 2) }} Tk</td>
            </tr>
          </tbody>
        </table>

        <span class="dashed-hr"></span>
        <h5 class="text-center">
          <strong>Thanks for Shopping.</strong><br>
        </h5>
        <p class="text-center">
          <strong></strong>
          <span class="dashed-hr"></span>
      </div>
    </div>

  </div>
</body>

</html>