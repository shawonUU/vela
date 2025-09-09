<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductSize;
use App\Models\SalesReturn;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Expense;

class DashboardController extends Controller
{
    public function getDashboardData(Request $request)
    {
        $show_start_date = $request->get('startDate');
        $show_end_date = $request->get('endDate');
        $filter = $request->get('filter');
        if ($request->get('startDate') && $request->get('endDate')) {
            $startDate = Carbon::parse($request->get('startDate'));
            $endDate = Carbon::parse($request->get('endDate'))->endOfDay();
        } else {
            $startDate = Carbon::parse(today())->startOfDay();
            $endDate = Carbon::parse(today())->endOfDay();
        }
        // Return
        $all_return_products = SalesReturn::whereBetween('return_date', [$startDate, $endDate])
            ->orderBy('return_date', 'desc')->get();
        $total_return_buying_price = 0;
        $total_return_selling_price = 0;

        foreach ($all_return_products as $return_product) {
            $total_return_buying_price += $return_product->buying_price;
            $total_return_selling_price += $return_product->selling_price;
        }
        $total_refund = $total_return_selling_price - $total_return_buying_price;
        
        $allData = Invoice::whereBetween('created_at', [$startDate, $endDate])->where('invoice_type', 'invoice')->where('status', '1')->orderBy('created_at', 'desc')->get();
        // Get filtered invoice IDs from the main invoice query
        $filtered_invoice_ids = $allData->pluck('id')->toArray();
        // Fetch sales data based on the date range
        $payment = Payment::whereIn('invoice_id', $filtered_invoice_ids)->get();
        $total_expense = Expense::whereBetween('date', [$startDate, $endDate])->sum('amount');
        // dd( $payment);

        $balance_payment = Payment::whereBetween('created_at', [$startDate, $endDate])->get();
        $total_balance = array_sum([
                        $balance_payment->sum('cash'),
                        $balance_payment->sum('visa_card'),
                        $balance_payment->sum('master_card'),
                        $balance_payment->sum('bKash'),
                        $balance_payment->sum('Nagad'),
                        $balance_payment->sum('Rocket'),
                        $balance_payment->sum('Upay'),
                        $balance_payment->sum('SureCash'),
                        $balance_payment->sum('online'),
        ])-$total_expense;

        $total_amount = 0;
        $total_discount = 0;
        $total_due = 0;
        $total_paid = 0;
        foreach ($payment as $pay) {
            $total_amount += $pay->total_amount;
            $total_discount += $pay->discount_amount;
            $total_due += $pay->due_amount;
            $total_paid += $pay->paid_amount;
        }

        $invoice_details = InvoiceDetail::whereIn('invoice_id', $filtered_invoice_ids)->get();
        // dd($invoice_details->all());

        // Calculate total profit, total selling price, total buying price, and total quantity
        // Initialize totals
        $total_profit = 0;
        $total_qty = 0;
        $total_selling_price = 0;
        $total_buying_price = 0;
        foreach ($invoice_details as $inv) {
            $total_selling_price += $inv->selling_price;

            $total_buying_price += $inv->buying_price;
            $total_profit += $inv->profit;

            $total_qty += $inv->selling_qty;
        }
        // $total_profit = $total_selling_price - $total_buying_price - $total_discount;

        // dd($total_amount, $total_selling_price, $total_buying_price,$total_selling_price - $total_buying_price,$total_profit,$total_discount);
        // top selling products
        $top_selling_products = InvoiceDetail::select('product_id')
            ->selectRaw('SUM(selling_qty) as total_sold')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();
        $low_stock_products = ProductSize::with('product')
            ->where('quantity', '<=', 100)
            ->where('quantity', '>', 0) // to exclude out of stock items
            ->orderBy('quantity', 'asc')
            ->take(5)
            ->get();
        $out_of_stock_products = ProductSize::with('product')
            ->where('quantity', '=', 0)
            ->take(5)
            ->get();

        return response()->view('admin.index', compact('allData',
         'filter',
         'total_return_selling_price',
         'total_refund',
         'show_start_date',
         'show_end_date',
         'startDate', 'endDate', 'total_amount', 'total_profit', 'total_paid', 'total_due', 'top_selling_products', 'low_stock_products', 'out_of_stock_products','total_expense','total_balance'));
    }

    public function dashboardReportPrint($startDate, $endDate, $filterName = 'Today', $total_amount, $total_profit, $total_paid, $total_due)
    {
        // dd($filterName);
        $allData = Invoice::whereBetween('created_at', [$startDate, $endDate])->orderBy('created_at', 'desc')->get();
        return view('backend.pdf.dashboardReportPrint', compact('allData', 'filterName', 'startDate', 'endDate', 'total_amount', 'total_profit', 'total_paid', 'total_due'));
    }
}
