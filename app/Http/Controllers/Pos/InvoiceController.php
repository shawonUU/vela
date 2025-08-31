<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Models\AdditionalFee;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Payment;
use App\Models\PaymentDetail;
use App\Models\Customer;
use App\Models\ProductPriceCodes;
use App\Models\ProductSize;
use App\Models\SalesReturn;
use App\Models\Tax;
use Auth;
use Illuminate\support\Carbon;
use DB;
use App\Traits\MailAndSmsHelper;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    use MailAndSmsHelper;
    public function __construct()
    {
        $this->middleware(['permission:invoice-create'], ['only' => ['InvoiceAdd', 'InvoiceStore']]);
        $this->middleware(['permission:invoice-edit'], ['only' => ['InvoiceEdit', 'InvoiceUpdate']]);
        $this->middleware(['permission:invoice-delete'], ['only' => ['InvoiceEdit', 'InvoiceDelete']]);
    }
    // Invoice Show Data
    public function InvoiceAll(Request $request)
    {
        $all_customers = Customer::all();
        $show_start_date = $request->get('startDate');
        $show_end_date = $request->get('endDate');
        $filter = $request->get('filter');
        $invoice_type_filter = $request->get('invoice_type_filter');
        $customer_filter = $request->get('customer_filter');

        // Handle date range
        if ($request->get('startDate') && $request->get('endDate')) {
            $startDate = Carbon::parse($request->get('startDate'));
            $endDate = Carbon::parse($request->get('endDate'))->endOfDay();
        } else {
            $startDate = Carbon::parse(today())->startOfDay();
            $endDate = Carbon::parse(today())->endOfDay();
        }

        // Build base query
        $allDataQuery = Invoice::whereBetween('created_at', [$startDate, $endDate])
            ->where('invoice_type', 'invoice')
            ->orderBy('created_at', 'desc');
        // RETURN
        $all_return_products = SalesReturn::whereBetween('return_date', [$startDate, $endDate])
            ->orderBy('return_date', 'desc')->get();
        $total_return_buying_price = 0;
        $total_return_selling_price = 0;

        foreach ($all_return_products as $return_product) {
            $total_return_buying_price += $return_product->buying_price;
            $total_return_selling_price += $return_product->selling_price;
        }
        $total_refund = $total_return_selling_price - $total_return_buying_price;
        // Apply invoice type filter
        // if ($invoice_type_filter != null) {
        //     if (in_array($invoice_type_filter, ['draft', 'challan', 'quotation', 'invoice'])) {
        //         $allDataQuery->where('invoice_type', $invoice_type_filter);
        //     }
        // }

        // Apply customer filter
        if ($customer_filter != null) {
            $find_customer_invoices = Payment::where('customer_id', $customer_filter)->pluck('invoice_id')->toArray();
            if (!empty($find_customer_invoices)) {
                $allDataQuery->whereIn('id', $find_customer_invoices);
            } else {
                // If no invoice found for customer, return empty collection
                $allDataQuery->whereRaw('0 = 1');
            }
        }

        // Final query execution
        $allData = $allDataQuery->get();

        // Get filtered invoice IDs from the main invoice query
        $filtered_invoice_ids = $allData->pluck('id')->toArray();

        // Filter payments based on invoices
        $payment = Payment::whereIn('invoice_id', $filtered_invoice_ids)->get();

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

        // Filter invoice details based on filtered invoice IDs
        $invoice_details = InvoiceDetail::whereIn('invoice_id', $filtered_invoice_ids)->get();

        $total_profit = 0;
        $total_qty = 0;
        $total_selling_price = 0;
        $total_buying_price = 0;

        foreach ($invoice_details as $inv) {
            $total_selling_price += $inv->selling_price;
            $total_buying_price += $inv->buying_price;
            // $total_profit += $inv->profit;
            $total_qty += $inv->selling_qty;
        }

        $total_profit = $total_selling_price - $total_buying_price; //- $total_discount;
        // dd(
        //     "Total Amount: " . $total_amount . "\n" .
        //         "total_discount: " . $total_discount . "\n" .
        //         "total_due: " . $total_due . "\n" .
        //         "total_profit: " . ($total_profit - $total_refund) . "\n" .
        //         "total_selling_price: " . $total_selling_price . "\n" .
        //         "total_buying_price: " . $total_buying_price . "\n" .
        //         "total_return_buying_price: " . $total_return_buying_price . "\n" .
        //         "total_return_selling_price: " . $total_return_selling_price . "\n" .
        //         "total_refund: " . $total_refund
        // );
        return response()->view('backend.invoice.invoice_all', compact(
            'allData',
            'all_customers',
            'filter',
            'show_start_date',
            'show_end_date',
            'startDate',
            'endDate',
            'invoice_type_filter',
            'customer_filter',
            'total_amount',
            'total_return_selling_price',
            'total_profit',
            'total_refund',
            'total_paid',
            'total_due'
        ));
    }
    //End Method
    // Invoice add form
    public function InvoiceAdd()
    {
        $category = Category::latest()->get();
        $brands = Brand::latest()->get();
        $customer = Customer::latest()->get();
        $payment = Payment::all();
        $invoice_data = Invoice::orderBy('id', 'desc')->first();
        // $products = ProductSize::with('product')
        //     ->leftJoin('invoice_details', 'product_sizes.id', '=', 'invoice_details.product_id')
        //     ->select('product_sizes.*', DB::raw('SUM(invoice_details.selling_qty) as total_sold_qty'))
        //     ->groupBy('product_sizes.id')
        //     ->latest('product_sizes.created_at')
        //     ->get();

        // dd($products);
        $products = ProductSize::with('product')->latest()->get();
        $additional_fees = AdditionalFee::orderBy('id', 'desc')->get();
        if ($invoice_data == null) {
            $firstReg = '0';
            $invoice_no = $firstReg + 1;
        } else {
            $invoice_data = Invoice::orderBy('id', 'desc')->first()->id;
            $invoice_no = $invoice_data + 1;
        }
        $productPriceCode = ProductPriceCodes::all();
        $date = date('Y-m-d');
        return view('backend.invoice.invoice_add', compact('invoice_no', 'products', 'category', 'date', 'customer', 'payment', 'brands', 'productPriceCode', 'additional_fees'));
    } //End Method

    public function InvoiceStore(Request $request)
    {
        // Validate input
        $request->validate([
            'invoice_no' => 'required',
            'date' => 'required|date',
            'product_size_id' => 'required|array',
            'selling_qty' => 'required|array',
            'unit_price' => 'required|array',
            'selling_price' => 'required|array',
            // 'estimated_amount' => 'required|numeric|min:1',
            'total' => 'required|numeric|min:1',
            'paid_amount' => 'nullable|numeric|min:0',
        ]);

        // Check if any items are selected
        if ($request->total == 0) {
            return redirect()->back()->with([
                'message' => 'Sorry, you did not select any item.',
                'alert-type' => 'error'
            ]);
        }

        // Validate paid amount
        if ($request->paid_amount > $request->total) {
            return redirect()->back()->with([
                'message' => 'Sorry, paid amount is greater than the total price.',
                'alert-type' => 'error'
            ]);
        }

        // Transaction ensures atomicity
        $invoiceId = DB::transaction(function () use ($request) {
            // Create Invoice
            if ($request->saveBtn == 2) {
                $invoice_type = 'invoice';
            } else if ($request->saveBtn == 4) {
                $invoice_type = 'challan';
            } else if ($request->saveBtn == 1) {
                $invoice_type = 'quotation';
            } else {
                $invoice_type = 'draft';
            }
            $invoice = Invoice::create([
                'invoice_no' => $request->invoice_no,
                'invoice_type' => $invoice_type,
                'date' => date('Y-m-d', strtotime($request->date)),
                'description' => $request->description,
                'invoice_discount_type' => $request->discount_status,
                // 'invoice_discount_rate' => ($request->discount_status === 'fixed_discount' ? $request->discount_show : $request->discount_amount),
                // 'invoice_discount_amount' => ($request->discount_status === 'fixed_discount' ? $request->discount_amount : $request->discount_show),
                'invoice_discount_rate' => 0,
                'invoice_discount_amount' => 0,
                'status' => '1', // Change this to '0' if approval is required
                'created_by' => Auth::id(),
                'created_at' => Carbon::now(),
            ]);

            $total_selling_price = 0;

            $discountAmounts = $request->discount_amount_per_product ?? [];
            $total_per_product_discount = array_sum($discountAmounts);

            $invoice_discount = $request->discount_status == 'percentage_discount'
                ? $request->discount_show
                : $request->discount_amount;

            $round_discount = $request->total_discount_amount - $total_per_product_discount - $invoice_discount;

            // Calculate how much additional discount to spread evenly per product
            $total_product = count($request->product_size_id);
            $additional_discount_per_product = ($total_product > 0)
                ? ($round_discount + $invoice_discount) / $total_product
                : 0;
            // dd($additional_discount_per_product);
            // Loop through each product and store invoice detail
            foreach ($request->product_size_id as $index => $productId) {

                $original_discount = $request->discount_amount_per_product[$index] ?? 0;
                $final_discount = $original_discount + $additional_discount_per_product;

                $selling_price = $request->selling_price[$index] - $additional_discount_per_product;
                $buying_price = $request->buying_price[$index];

                $total_selling_price += $selling_price;

                InvoiceDetail::create([
                    'date' => date('Y-m-d', strtotime($request->date)),
                    'invoice_id' => $invoice->id,
                    'category_id' => $request->category_id[$index],
                    'product_id' => $productId, // use product_size_id if needed
                    'selling_qty' => $request->selling_qty[$index],
                    'unit_price' => $request->unit_price[$index],
                    'buying_price' => $buying_price,
                    'selling_price' => $selling_price,
                    'profit' => $selling_price - $buying_price,
                    'discount_type' => 'fixed', // adjust logic if needed
                    'discount_rate' => $final_discount,
                    'discount_amount' => $final_discount,
                    'tax_type' => json_encode([]),
                    'tax_amount' => 0,
                    'status' => '1',
                    'created_at' => Carbon::now(),
                ]);

                // Update Product Stock, when it is invoice or challan
                if ($request->saveBtn == 2 || $request->saveBtn == 4) {
                    ProductSize::where('id', $productId)->decrement('quantity', $request->selling_qty[$index]);
                }
            }
            // Handle New or Existing Customer NEW VERSION
            if (empty($request->mobile_no) && empty($request->name) && empty($request->email)) {
                $customer_id = random_int(1000, 9999); // Generate a random customer ID for walking customers
            } else if (!empty($request->mobile_no)) {
                $customer = Customer::where('mobile_no', $request->mobile_no)->first();
                if ($customer) {
                    $customer_id = $customer->id;
                } else {
                    $customer_id = Customer::create([
                        'name' => $request->name,
                        'mobile_no' => $request->mobile_no,
                        'email' => $request->email,
                        'created_by' => Auth::id(),
                        'created_at' => Carbon::now(),
                    ])->id;
                }
            }
            // Handle New or Existing Customer OLD VERSION
            // if ($request->customer_id == '0' || (empty($request->mobile_no) && empty($request->name))) {
            //     $customer_id = random_int(1000, 9999); // Generate a random customer ID for walking customers
            // } else {
            //     $customer_id = ($request->customer_id == '-1')
            //         ? Customer::create([
            //             'name' => $request->name,
            //             'mobile_no' => $request->mobile_no,
            //             // 'email' => $request->email,
            //             'address' => $request->address,
            //             'created_by' => Auth::id(),
            //             'created_at' => Carbon::now(),
            //         ])->id
            //         : $request->customer_id;
            // }
            $cash = 0;
            $total_payment = $request->cash + $request->visa_card + $request->master_card + $request->bKash + $request->Nagad + $request->Rocket + $request->Upay + $request->SureCash + $request->online;
            if ($request->change < 0) {
                $cash = $request->cash + $request->change;
            } else if ($total_payment == 0 && $request->change == 0) {
                $cash = $request->paid_amount;
            } else {
                $cash = $request->cash;
            }
            // Create Payment Record
            $payment = Payment::create([
                'invoice_id' => $invoice->id,
                'customer_id' => $customer_id,
                // 'paid_status' => $request->paid_status,
                'paid_status' => 'partial-paid',
                'cash' => $cash ?? 0,
                'visa_card' => $request->visa_card ?? 0,
                'master_card' => $request->master_card ?? 0,
                'bKash' => $request->bKash ?? 0,
                'Nagad' => $request->Nagad ?? 0,
                'Rocket' => $request->Rocket ?? 0,
                'Upay' => $request->Upay ?? 0,
                'SureCash' => $request->SureCash ?? 0,
                'online' => $request->online ?? 0,
                'discount_amount' => $request->total_discount_amount,
                'total_amount' => $request->total,
                'paid_amount' =>  $request->paid_amount ?? 0,
                'due_amount' => $request->due_amount ?? 0,
                'total_tax_amount' => 0,
                'total_additional_charge_amount' => 0,
                // 'total_tax_amount' => $request->tax_value,
                // 'total_additional_charge_amount' => $request->total_additional_fees_amount,
            ]);

            // Create Payment Detail Record
            if ($request->saveBtn == 2 || $request->saveBtn == 4) {
                PaymentDetail::create([
                    'customer_id' => $customer_id,
                    'transaction_id' => 'TXN-' . Str::upper(Str::random(8)),
                    'invoice_id' => $invoice->id,
                    'date' => date('Y-m-d', strtotime($request->date)),
                    'current_paid_amount' => $payment->paid_amount,
                    'received_by' => Auth::user()->name
                ]);
            }
            return $invoice->id;  // Return Invoice ID
        });

        // Fetch invoice after transaction
        $invoice = Invoice::with('invoice_details')->findOrFail($invoiceId);

        // Handle Save Button Logic
        $redirect = 'invoice-add';
        switch ($request->saveBtn) {
            case 4:
                return view('backend.pdf.challan_pdf_print_by_add_invoice', compact('invoice', 'redirect'));
            case 3:
                return redirect()->route('invoice.add')->with([
                    'message' => 'Invoice Draft Saved Successfully',
                    'alert-type' => 'success'
                ]);
            case 2:
                $pre_due = Invoice::whereHas('payment', function ($q) use ($invoice) {
                    $q->where('customer_id', $invoice->payment->customer_id);
                })
                    ->where('id', '!=', $invoiceId)
                    ->where('status', 1)
                    ->withSum('payment', 'due_amount')
                    ->get()
                    ->sum('payment_sum_due_amount');

                return view('backend.invoice.pdf.invoice_pos', compact('invoice', 'pre_due', 'redirect'));
                // return view('backend.pdf.invoice_pdf_print_by_add_invoice', compact('invoice', 'pre_due', 'redirect'));

            case 1:
                $pre_due = Invoice::whereHas('payment', function ($q) use ($invoice) {
                    $q->where('customer_id', $invoice->payment->customer_id);
                })
                    ->where('id', '!=', $invoiceId)
                    ->where('status', 1)
                    ->withSum('payment', 'due_amount')
                    ->get()
                    ->sum('payment_sum_due_amount');

                return view('backend.invoice.pdf.quotation_pdf', compact('invoice', 'pre_due', 'redirect'));
            case 0:
            default:
                return redirect()->route('invoice.add')->with([
                    'message' => 'Invoice Draft Saved Successfully',
                    'alert-type' => 'success'
                ]);
        }
    }

    public function InvoiceSmsSend(Request $request)
    {
        $this->validate($request, ['id' => 'required', 'sr_id' => 'required|integer|min:0']);
        if ($request->sr_id > 0) {
            $invoice = Invoice::with('payment.customer')->whereHas('payment.customer')->find($request->id);
        } else {
            $invoice = Invoice::with('sales_rep')->whereHas('sales_rep')->find($request->id);
        }
        if (!$invoice) {
            return response(['status' => false, 'message' => $request->sr_id > 0 ? 'SR not found.' : 'Customer not found.'], 403);
        }

        if ($request->sr_id > 0) {
            $number = $invoice?->sales_rep->mobile_no;
            $message = "Dear " . $invoice?->sales_rep?->name . " pls collect invoice for customer: " . $invoice?->payment?->customer?->name . "\nlink: " . route('PublicPrintInvoice', base64_encode($invoice->id)) . "\n-Foisal";
        } else {
            $number = $invoice?->payment?->customer?->mobile_no;
            $message = "Dear " . $invoice?->payment?->customer?->name . "\nPls collect your invoice.\nLink: " . route('PublicPrintInvoice', base64_encode($invoice->id)) . "\nThanks by Foisal";
        }

        if ($this->send_sms($number, $message)) {
            return response(['message' => "Message send successful."], 200);
        } else {
            return response(['status' => false, 'message' => 'Message not send.'], 403);
        }
    }
    // Invoice Edit
    public function InvoiceEdit($id)
    {
        $brands = Brand::all();
        $category = Category::all();
        $customers = Customer::all();
        $products = ProductSize::with('product')->latest()->get();
        $product_price_code = ProductPriceCodes::all();
        $invoice = Invoice::findOrFail($id);
        $payment = Payment::where('invoice_id', $invoice->id)->first();
        return view('backend.invoice.invoice_edit', compact('brands', 'category', 'customers', 'products', 'product_price_code', 'invoice', 'payment'));
    }
    public function InvoiceUpdate(Request $request)
    {
        // dd($request->all());
        // Validate input
        $request->validate([
            'invoice_no' => 'required',
            'date' => 'required|date',
            'product_size_id' => 'required|array',
            'selling_qty' => 'required|array',
            'unit_price' => 'required|array',
            'selling_price' => 'required|array',
            // 'estimated_amount' => 'required|numeric|min:1',
            'total' => 'required|numeric|min:1',
            'paid_amount' => 'nullable|numeric|min:0',
        ]);
        // Check if any items are selected
        if ($request->total == 0) {
            return redirect()->back()->with([
                'message' => 'Sorry, you did not select any item.',
                'alert-type' => 'error'
            ]);
        }

        // Validate paid amount
        if ($request->paid_amount > $request->total) {
            return redirect()->back()->with([
                'message' => 'Sorry, paid amount is greater than the total price.',
                'alert-type' => 'error'
            ]);
        }

        DB::transaction(function () use ($request) {
            //update invoice
            $invoice = Invoice::findOrFail($request->invoice_no);
            // $old_invoice_type = $invoice->invoice_type;

            // // Determine new invoice type based on saveBtn
            // switch ($request->saveBtn) {
            //     case 2:
            //         $invoice_type = 'invoice';
            //         break;
            //     case 4:
            //         $invoice_type = 'challan';
            //         break;
            //     case 1:
            //         $invoice_type = 'quotation';
            //         break;
            //     default:
            //         $invoice_type = $old_invoice_type;
            // }

            // Restrict downgrading to quotation if old type is invoice or challan
            // if ($invoice_type === 'quotation' && in_array($old_invoice_type, ['invoice', 'challan'])) {
            //     $invoice_type = $old_invoice_type;
            // }

            $invoice->update([
                'invoice_no' => $request->invoice_no,
                'invoice_type' => $invoice->invoice_type,
                'date' => date('Y-m-d', strtotime($request->date)),
                'description' => $request->description,
                'invoice_discount_type' => $request->discount_status,
                'invoice_discount_rate' => 0,
                'invoice_discount_amount' => 0,
                'updated_by' => Auth::id(),
                'updated_at' => Carbon::now(),
            ]);

            $existingDetails = InvoiceDetail::where('invoice_id', $request->invoice_no)->get()->keyBy('id');

            $submittedIds = collect($request->invoice_details_id)->filter(fn($id) => $id > 0)->values();

            // Handle deleted items
            foreach ($existingDetails as $detailId => $detail) {
                if (!$submittedIds->contains($detailId)) {
                    ProductSize::where('id', $detail->product_id)->increment('quantity', $detail->selling_qty); // restore stock
                    $detail->delete();
                }
            }
            // calculation of discount
            $total_selling_price = 0;

            $discountAmounts = $request->discount_amount_per_product ?? [];
            $total_per_product_discount = array_sum($discountAmounts);

            $invoice_discount = $request->discount_status == 'percentage_discount'
                ? $request->discount_show
                : $request->discount_amount;

            $round_discount = $request->total_discount_amount - $total_per_product_discount - $invoice_discount;

            // Calculate how much additional discount to spread evenly per product
            $total_product = count($request->product_size_id);
            $additional_discount_per_product = ($total_product > 0)
                ? ($round_discount + $invoice_discount) / $total_product
                : 0;
            // dd($additional_discount_per_product);


            for ($index = 0; $index < $total_product; $index++) {
                $productId = $request->product_size_id[$index];
                $detailId = $request->invoice_details_id[$index];
                $sellingQty = $request->selling_qty[$index];
                $buyingPrice = $request->buying_price[$index];
                // Discount Calculation
                $original_discount = $request->discount_amount_per_product[$index] ?? 0;
                $final_discount = $original_discount + $additional_discount_per_product;

                $sellingPrice = $request->selling_price[$index] - $additional_discount_per_product;

                $data = [
                    'date' => date('Y-m-d', strtotime($request->date)),
                    'invoice_id' => $invoice->id,
                    'category_id' => $request->category_id[$index],
                    'product_id' => $productId,
                    'selling_qty' => $sellingQty,
                    'unit_price' => $request->unit_price[$index],
                    'buying_price' => $buyingPrice,
                    'selling_price' => $sellingPrice,
                    'profit' => $sellingPrice - $buyingPrice,
                    'discount_type' => 'fixed',
                    'discount_rate' => $final_discount,
                    'discount_amount' => $final_discount,
                    'status' => '1',
                    'updated_at' => Carbon::now(),
                ];

                if ($detailId == 0) {
                    InvoiceDetail::create($data);
                    ProductSize::where('id', $productId)->decrement('quantity', $sellingQty);
                } else {
                    $existingDetail = $existingDetails[$detailId];
                    $oldQty = $existingDetail->selling_qty;
                    $qtyDiff = $sellingQty - $oldQty;
                    $existingDetail->update($data);

                    ProductSize::where('id', $productId)->decrement('quantity', $qtyDiff);
                }
            }

            $payment = Payment::where('invoice_id', $invoice->id)->first();
            // Handle New or Existing Customer NEW VERSION
            if (empty($request->mobile_no) && empty($request->name) && empty($request->email)) {
                $customer_id = random_int(1000, 9999); // Generate a random customer ID for walking customers
            } else if (!empty($request->mobile_no)) {
                $customer = Customer::where('mobile_no', $request->mobile_no)->first();
                if ($customer) {
                    $customer_id = $customer->id;
                } else {
                    $customer_id = Customer::create([
                        'name' => $request->name,
                        'mobile_no' => $request->mobile_no,
                        'email' => $request->email,
                        'created_by' => Auth::id(),
                        'created_at' => Carbon::now(),
                    ])->id;
                }
            }
            // $old_customer = \App\Models\Customer::find($request->customer_id);
            // if ($old_customer) {
            //     $customer_id = $request->customer_id;
            // } else {
            //     // Handle New or Existing Customer
            //     if ($request->customer_id == '0' || (empty($request->mobile_no) && empty($request->name))) {
            //         $customer_id = random_int(1000, 9999); // Generate a random customer ID for walking customers
            //     } else {
            //         $customer_id = ($request->customer_id == '-1')
            //             ? Customer::create([
            //                 'name' => $request->name,
            //                 'mobile_no' => $request->mobile_no,
            //                 // 'email' => $request->email,
            //                 'address' => $request->address,
            //                 'created_by' => Auth::id(),
            //                 'created_at' => Carbon::now(),
            //             ])->id
            //             : $request->customer_id;
            //     }
            // }
            $cash = 0;
            $total_payment = $request->cash + $request->visa_card + $request->master_card + $request->bKash + $request->Nagad + $request->Rocket + $request->Upay + $request->SureCash + $request->online;
            if ($request->change < 0) {
                $cash = $request->cash + $request->change;
            } else if ($total_payment == 0 && $request->change == 0) {
                $cash = $request->paid_amount;
            } else {
                $cash = $request->cash;
            }
            // dd($customer_id);
            $payment->update([
                'customer_id' => $customer_id,
                // 'paid_status' => $request->paid_status,
                'paid_status' => 'partial-paid',
                'cash' => $cash ?? 0,
                'visa_card' => $request->visa_card ?? 0,
                'master_card' => $request->master_card ?? 0,
                'bKash' => $request->bKash ?? 0,
                'Nagad' => $request->Nagad ?? 0,
                'Rocket' => $request->Rocket ?? 0,
                'Upay' => $request->Upay ?? 0,
                'SureCash' => $request->SureCash ?? 0,
                'online' => $request->online ?? 0,
                'discount_amount' => $request->total_discount_amount,
                'total_amount' => $request->total,
                // 
                'paid_amount' =>  $request->paid_amount ?? 0,
                'due_amount' => $request->due_amount ?? 0,
                'total_tax_amount' => 0,
                'total_additional_charge_amount' => 0,
            ]);

            $payment_details = PaymentDetail::where('invoice_id', $invoice->id)->first();
            $payment_details->update(
                [
                    'customer_id' => $customer_id,
                    'date' => date('Y-m-d', strtotime($request->date)),
                    'current_paid_amount' => $payment->paid_amount,
                ]
            );

            // dd('ok');
        });
        // Handle Save Button Logic
        $invoice = Invoice::findOrFail($request->invoice_no);
        $redirect = 'invoice-edit';
        switch ($request->saveBtn) {
            case 2:
                $pre_due = Invoice::whereHas('payment', function ($q) use ($invoice) {
                    $q->where('customer_id', $invoice->payment->customer_id);
                })
                    ->where('id', '!=', $invoice->id)
                    ->where('status', 1)
                    ->withSum('payment', 'due_amount')
                    ->get()
                    ->sum('payment_sum_due_amount');

                return view('backend.invoice.pdf.invoice_pos', compact('invoice', 'pre_due', 'redirect'));
            case 0:
            default:
                return redirect()->route('invoice.edit', $request->invoice_no)->with([
                    'message' => 'Invoice Updated Successfully.',
                    'alert-type' => 'success'
                ]);
        }
    }
    // Invoice Delete 
    public function InvoiceDelete($id)
    {
        // dd($id);
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();
        $invoice_details = InvoiceDetail::where('invoice_id', $id)->get();
        // dd($invoice_details);
        // Handle deleted items
        foreach ($invoice_details as $detail) {
            ProductSize::where('id', $detail->product_id)->increment('quantity', $detail->selling_qty); // restore stock
        }
        InvoiceDetail::where('invoice_id', $invoice->id)->delete();
        Payment::where('invoice_id', $invoice->id)->delete();
        PaymentDetail::where('invoice_id', $invoice->id)->delete();

        $notification = array(
            'message' => 'Invoice Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    } //End Method

    public function ReportPrint($id, $invoice_type)
    {
        // Fetch invoice after transaction
        $invoice = Invoice::with('invoice_details')->findOrFail($id);

        // Handle Save Button Logic
        $redirect = 'invoice-all';
        switch ($invoice_type) {
            case 3:
                $pre_due = Invoice::whereHas('payment', function ($q) use ($invoice) {
                    $q->where('customer_id', $invoice->payment->customer_id);
                })
                    ->where('id', '!=', $id)
                    ->where('status', 1)
                    ->withSum('payment', 'due_amount')
                    ->get()
                    ->sum('payment_sum_due_amount');

                return view('backend.invoice.pdf.invoice_pos', compact('invoice', 'pre_due', 'redirect'));
            case 2:
                $pre_due = Invoice::whereHas('payment', function ($q) use ($invoice) {
                    $q->where('customer_id', $invoice->payment->customer_id);
                })
                    ->where('id', '!=', $id)
                    ->where('status', 1)
                    ->withSum('payment', 'due_amount')
                    ->get()
                    ->sum('payment_sum_due_amount');

                return view('backend.invoice.pdf.invoice_a4', compact('invoice', 'pre_due', 'redirect'));
        }
    }
    public function PrintInvoiceList()
    {
        $allData = Invoice::orderBy('date', 'desc')->orderBy('id', 'desc')->where('status', '1')->get();
        return view('backend.invoice.print_invoice_list', compact('allData'));
    } // End Method

    // Direct Invoice print from Add invoice page
    public function InvoicePosPrint($id)
    {
        // dd($id);
        $invoice = Invoice::with('invoice_details')->findOrFail($id);
        $pre_due = Invoice::whereHas('payment', function ($q) use ($invoice) {
            $q->where('customer_id', $invoice?->payment?->customer_id);
        })->withSum('payment', 'due_amount')->where([['id', '!=', $id], ['status', 1]])->get()->sum('payment_sum_due_amount');
        // dd($invoice);
        return view('backend.pdf.invoice_pos_print', compact('invoice', 'pre_due'));
    } // End Method
    public function PrintInvoice(Request $request, $id)
    {
        // dd($request->route()->getName());
        // if ($request->route()->getName() == "PublicPrintInvoice") {
        //     $invoice = Invoice::with('invoice_details')->findOrFail(base64_decode($id));
        //     $pre_due = Invoice::whereHas('payment', function ($q) use ($invoice) {
        //         $q->where('customer_id', $invoice?->payment?->customer_id);
        //     })->withSum('payment', 'due_amount')->where([['id', '!=', base64_decode($id)], ['status', 1]])->get()->sum('payment_sum_due_amount');
        //     return view('backend.pdf.public_invoice_pdf', compact('invoice', 'pre_due'));
        // }
        $invoice = Invoice::with('invoice_details')->findOrFail($id);
        $pre_due = Invoice::whereHas('payment', function ($q) use ($invoice) {
            $q->where('customer_id', $invoice?->payment?->customer_id);
        })->withSum('payment', 'due_amount')->where([['id', '!=', $id], ['status', 1]])->get()->sum('payment_sum_due_amount');
        return view('backend.pdf.invoice_pdf', compact('invoice', 'pre_due'));
    } // End Method

    // Invoice All Page Print filter wise invoice/challan
    public function InvoiceAllFilterPrint($startDate = null, $endDate = null, $filter = 'null', $invoice_type_filter = 'null', $customer_filter = 'null')
    {
        $show_start_date = $startDate;
        $show_end_date = $endDate;

        // Handle date range
        $startDate = $startDate ? Carbon::parse($startDate) : now()->startOfDay();
        $endDate = $endDate ? Carbon::parse($endDate)->endOfDay() : now()->endOfDay();

        // Build base query
        $allDataQuery = Invoice::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '1')
            ->orderByDesc('created_at');

        // Apply invoice type filter
        if ($invoice_type_filter !== 'null') {
            $allDataQuery->where('invoice_type', in_array($invoice_type_filter, ['draft', 'challan']) ? $invoice_type_filter : 'invoice');
        }

        // Apply customer filter
        if ($customer_filter !== 'null') {
            $invoiceIds = Payment::where('customer_id', $customer_filter)->pluck('invoice_id');

            if ($invoiceIds->isNotEmpty()) {
                $allDataQuery->whereIn('id', $invoiceIds);
            } else {
                // No matching invoices, return empty result
                $allDataQuery->whereRaw('0 = 1');
            }
        }

        $allData = $allDataQuery->get();

        // Get filtered invoice IDs
        $filteredInvoiceIds = $allData->pluck('id');

        // Payment details
        $payments = Payment::whereIn('invoice_id', $filteredInvoiceIds)->get();

        $total_amount = $payments->sum('total_amount');
        $total_discount = $payments->sum('discount_amount');
        $total_due = $payments->sum('due_amount');
        $total_paid = $payments->sum('paid_amount'); // You were calculating it but not returning/using it

        // Invoice details
        $invoiceDetails = InvoiceDetail::whereIn('invoice_id', $filteredInvoiceIds)->get();

        $total_selling_price = $invoiceDetails->sum('selling_price');
        $total_buying_price = $invoiceDetails->sum('buying_price');
        $total_qty = $invoiceDetails->sum('selling_qty');

        $total_profit = $total_selling_price - $total_buying_price - $total_discount;

        return response()->view('backend.pdf.filter_invoice_print_from_invoice_all', compact(
            'allData',
            'filter',
            'show_start_date',
            'show_end_date',
            'startDate',
            'endDate',
            'invoice_type_filter',
            'customer_filter',
            'total_amount',
            'total_due'
        ));
    }
    // invoice preview
    public function PreView(Request $request)
    {
        $data = $request->all();
        // dd($data);
        if (collect($request->selling_qty)->sum() == 0 && $request->total == 0) {
            return redirect()->back()->with([
                'message' => 'Sorry, you did not select any item.',
                'alert-type' => 'error'
            ]);
        }
        // dd($data);
        // Validate input
        $request->validate([
            'invoice_no' => 'required',
            'date' => 'required|date',
            'product_id' => 'required|array',
            'selling_qty' => 'required|array',
            'unit_price' => 'required|array',
            'selling_price' => 'required|array',
            'total' => 'required|numeric|min:1',
            'paid_amount' => 'nullable|numeric|min:0',
        ]);

        // Validate paid amount
        if ($request->paid_amount > $request->total) {
            return redirect()->back()->with([
                'message' => 'Sorry, paid amount is greater than the total price.',
                'alert-type' => 'error'
            ]);
        }
        $finalProducts = [];
        $invoice_discount = $request->discount_status == 'percentage_discount'
            ? $request->discount_show
            : $request->discount_amount;



        // Calculate how much additional discount to spread evenly per product
        $total_product = count($request->product_size_id);
        $additional_discount_per_product = ($total_product > 0)
            ? ($request->round_amount + $invoice_discount) / $total_product
            : 0;
        foreach ($request->product_id as $index => $product_id) {
            $product = Product::find($product_id);

            if ($product) {
                $finalProducts[] = (object)[
                    'product_name'    => $product->name,
                    'product_description' => $product->description,
                    'brand'           => $product->brand->name ?? '',
                    'unit'            => $product->unit->name,
                    'size_name'       => $request->size_name[$index] ?? '',
                    'qty'             => $request->selling_qty[$index],
                    'unit_price'      => $request->unit_price[$index],
                    'discount_rate'   => $request->discount_rate[$index] ?? 0,
                    'discount_amount' => $request->discount_amount_per_product[$index] ?? 0,
                    'total'           => $request->selling_price[$index] - $additional_discount_per_product,
                ];
            }
        }
        // dd($finalProducts);
        // Handle Save Button Logic
        if ($request->saveBtn == 2) {
            return view('backend.invoice.preview.invoice-pos-preview', compact('data', 'finalProducts'));
        }
    }
    // invoice preview
    public function InvoiceAllPreView(Request $request)
    {

        $dataObject = FacadesDB::table('invoices')
            ->join('payments', 'invoices.id', '=', 'payments.invoice_id')
            ->leftJoin('customers', 'payments.customer_id', '=', 'customers.id')
            ->where('invoices.id', $request->invoice_id)
            ->select(
                'invoices.*',
                'invoices.invoice_discount_type as discount_status',
                'invoices.invoice_discount_amount as discount_show',
                'invoices.invoice_discount_amount as discount_amount',
                'payments.customer_id',
                'payments.paid_amount',
                'payments.due_amount',
                'payments.total_amount as estimated_amount',
                'payments.discount_amount as total_discount_amount',
                'customers.mobile_no as mobile_no',
            )
            ->first();

        $data = (array) $dataObject;
        // dd($data);
        $finalProducts = FacadesDB::table('invoice_details')
            ->join('product_sizes', 'invoice_details.product_id', '=', 'product_sizes.id')
            ->join('products', 'product_sizes.product_id', '=', 'products.id') // Join products table
            ->join('sizes', 'product_sizes.size_id', '=', 'sizes.id')         // Join sizes table
            ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
            ->leftJoin('units', 'products.unit_id', '=', 'units.id')
            ->select(
                'invoice_details.id as invoice_detail_id',
                'invoice_details.invoice_id',
                'invoice_details.category_id',
                'invoice_details.product_id',
                'invoice_details.date',
                'invoice_details.selling_qty as qty',
                'invoice_details.unit_price',
                'invoice_details.discount_type',
                'invoice_details.discount_rate',
                'invoice_details.discount_amount',
                'invoice_details.profit',
                'invoice_details.buying_price',
                'invoice_details.selling_price as total',
                'invoice_details.status as invoice_status',

                // From Products table
                'products.name as product_name',
                'products.description as product_description',

                // From Sizes table
                'sizes.name as size_name',

                // Brand and Unit
                'brands.name as brand',
                'units.name as unit'
            )
            ->where('invoice_details.invoice_id', $request->invoice_id)
            ->get();

        // dd($data);
        if ($request->invoice_type == 'invoicePos') {
            return view('backend.invoice.preview.invoice-pos-preview', compact('data', 'finalProducts'));
        } else {
            return view('backend.invoice.preview.invoice-preview', compact('data', 'finalProducts'));
        }
    }
}
