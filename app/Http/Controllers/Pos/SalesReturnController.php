<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Models\AdditionalFee;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\InvoiceDetailsTax;
use App\Models\Payment;
use App\Models\PaymentDetail;
use App\Models\Product;
use App\Models\ProductSize;
use App\Models\SalesReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SalesReturnController extends Controller
{
    public function allReturn(Request $request)
    {
        $all_customers = Customer::all();
        $show_start_date = $request->get('startDate');
        $show_end_date = $request->get('endDate');
        $filter = $request->get('filter');
        $customer_filter = $request->get('customer_filter');

        // Handle date range
        if ($request->get('startDate') && $request->get('endDate')) {
            $startDate = Carbon::parse($request->get('startDate'));
            $endDate = Carbon::parse($request->get('endDate'))->endOfDay();
        } else {
            $startDate = Carbon::parse(today())->startOfDay();
            $endDate = Carbon::parse(today())->endOfDay();
        }

        $sub = SalesReturn::selectRaw('MAX(id) as id')
            ->whereBetween('return_date', [$startDate, $endDate])
            ->when($customer_filter, function ($query, $customer_filter) {
                return $query->where('customer_id', $customer_filter);
            })
            ->groupBy('invoice_id');

        $return_invoices = SalesReturn::whereIn('id', $sub)
            ->orderBy('created_at', 'desc')
            ->get();

        // dd($allData);

        // dd($allData);

        // $total_return_buying_price = 0;
        // $total_return_selling_price = 0;

        // foreach ($allData as $return_product) {
        //     $total_return_buying_price += $return_product->buying_price;
        //     $total_return_selling_price += $return_product->selling_price;
        // }
        // dd(
        //     "Total Amount: " . $total_amount . "\n" .
        //         "total_discount: " . $total_discount . "\n" .
        //         "total_due: " . $total_due . "\n" .
        //         "total_profit: " . ($total_profit - $total_refund) . "\n" .
        //         "total_selling_price: " . $total_selling_price . "\n" .
        //         "total_buying_price: " . $total_buying_price . "\n" .
        //         "total_return_buying_price: " . $total_return_buying_price .     "\n" .
        //         "total_return_selling_price: " . $total_return_selling_price . "\n" .
        //         "total_refund: " . $total_refund
        // );
        return response()->view('backend.sales-return.return_all', compact(
            'return_invoices',
            // 'total_return_buying_price',
            // 'total_return_selling_price',
            'all_customers',
            'filter',
            'show_start_date',
            'show_end_date',
            'startDate',
            'endDate',
            'customer_filter',
        ));
    }
    public function index()
    {
        $category = Category::latest()->get();
        $brands = Brand::latest()->get();
        $customer = Customer::latest()->get();
        $payment = Payment::all();
        $invoice_data = Invoice::orderBy('id', 'desc')->first();
        $products = ProductSize::with('product')->latest()->get();
        $additional_fees = AdditionalFee::orderBy('id', 'desc')->get();
        if ($invoice_data == null) {
            $firstReg = '0';
            $invoice_no = $firstReg + 1;
        } else {
            $invoice_data = Invoice::orderBy('id', 'desc')->first()->id;
            $invoice_no = $invoice_data + 1;
        }
        $date = date('Y-m-d');
        return view('backend.sales-return.index', compact('invoice_no', 'products', 'category', 'date', 'customer', 'payment', 'brands', 'additional_fees'));
    }
    public function getProducts(Request $request)
    {
        $customerId = $request->input('customer_id');
        // 1 month product
        $startDate = Carbon::today()->subDays(30)->startOfDay();
        $endDate   = Carbon::today()->endOfDay();
        // dd($startDate, $endDate);
        $query = DB::table('invoice_details')
            ->join('product_sizes', 'invoice_details.product_id', '=', 'product_sizes.id')
            ->join('products', 'product_sizes.product_id', '=', 'products.id')
            ->join('sizes', 'product_sizes.size_id', '=', 'sizes.id')
            // ->join('brands', 'products.brand_id', '=', 'brands.id')
            ->join('payments', 'payments.invoice_id', '=', 'invoice_details.invoice_id')
            ->where('payments.due_amount', '<=', 0)
            ->where('invoice_details.exchange', '!=', 1)
            ->whereBetween('invoice_details.created_at', [$startDate, $endDate])
            // ->join('customers', 'payments.customer_id', '=', 'customers.id')
            ->select(
                'invoice_details.date as buying_date',
                'invoice_details.invoice_id as return_buying_invoice_id',
                'invoice_details.id as return_invoice_detail_id',
                'products.category_id as return_category_id',
                'product_sizes.id as return_product_size_id',
                'products.name as product_name',
                'sizes.name as size_name',
                // 'brands.name as brand_name',
                'invoice_details.selling_qty as return_product_quantity',
                'invoice_details.unit_price as return_unit_price',
                'product_sizes.buying_price as return_unit_buying_price',
                'invoice_details.selling_price as return_product_selling_price',
                'invoice_details.discount_amount as return_discount_amount',
                'invoice_details.exchange as exchange',
                'payments.id as payment_id',
                'payments.due_amount as payment_due_amount',
                // 'customers.name as customer_name',
                // 'customers.mobile_no as customer_mobile_no',
            )
            ->orderBy('invoice_details.created_at', 'desc');
        // dd($query->get());
        // ✅ Filter by customer if given
        if ($customerId) {
            $invoiceIds = DB::table('payments')
                ->where('customer_id', $customerId)
                ->pluck('invoice_id');
            // dd($invoiceIds);
            if ($invoiceIds->isEmpty()) {
                return response()->json([]);
            }

            $query->whereIn('invoice_details.invoice_id', $invoiceIds);
        }

        $invoiceDetails = $query->get();
        // dd($invoiceDetails);
        return response()->json($invoiceDetails);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        // Validate input
        $request->validate([
            'invoice_no' => 'nullable',
            'date' => 'nullable|date',
            'product_size_id' => 'nullable|array',
            'selling_qty' => 'nullable|array',
            'unit_price' => 'nullable|array',
            'selling_price' => 'nullable|array',
            'total' => 'nullable|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
        ]);

        // If nothing is selected
        if (($request->grand_total == 0) && empty($request->return_product_size_id)) {
            return redirect()->back()->with([
                'message' => 'Sorry, you did not select any item.',
                'alert-type' => 'error'
            ]);
        }

        DB::transaction(function () use ($request) {
            $invoice = null;
            $invoice_id = null;
            $invoice_type = 'invoice';

            // 1. Exchange logic (if exchange products exist)
            $total_exchange_product_price = 0;
            if (!empty($request->product_size_id)) {
                $invoice = Invoice::create([
                    'invoice_no' => $request->invoice_no,
                    'invoice_type' => $invoice_type,
                    'date' => date('Y-m-d', strtotime($request->date)),
                    'description' => $request->description,
                    'invoice_discount_type' => $request->discount_status,
                    'invoice_discount_rate' => 0,
                    'invoice_discount_amount' => 0,
                    'status' => '1',
                    'created_by' => Auth::id(),
                    'created_at' => Carbon::now(),
                ]);

                $total_per_product_discount = array_sum($request->discount_amount_per_product ?? []);
                $invoice_discount = $request->discount_status == 'percentage_discount'
                    ? $request->discount_show
                    : $request->discount_amount;

                $round_discount = $request->total_discount_amount - $total_per_product_discount - $invoice_discount;
                $total_product = count($request->product_size_id);
                $additional_discount_per_product = ($total_product > 0)
                    ? ($round_discount + $invoice_discount) / $total_product
                    : 0;

                foreach ($request->product_size_id as $index => $productId) {
                    $original_discount = $request->discount_amount_per_product[$index] ?? 0;
                    $final_discount = $original_discount + $additional_discount_per_product;
                    $selling_price = $request->selling_price[$index] - $additional_discount_per_product;
                    $buying_price = $request->buying_price[$index];
                    $total_exchange_product_price += $selling_price;
                    InvoiceDetail::create([
                        'date' => date('Y-m-d', strtotime($request->date)),
                        'invoice_id' => $request->invoice_no,
                        'category_id' => $request->category_id[$index],
                        'product_id' => $productId,
                        'selling_qty' => $request->selling_qty[$index],
                        'unit_price' => $request->unit_price[$index],
                        'buying_price' => $buying_price,
                        'selling_price' => $selling_price,
                        'profit' => $selling_price - $buying_price,
                        'discount_type' => 'fixed',
                        'discount_rate' => $final_discount,
                        'discount_amount' => $final_discount,
                        'tax_type' => json_encode([]),
                        'tax_amount' => 0,
                        'status' => '1',
                        'created_at' => Carbon::now(),
                    ]);

                    if (in_array($request->saveBtn, [2, 4])) {
                        ProductSize::where('id', $productId)
                            ->decrement('quantity', $request->selling_qty[$index]);
                    }
                }
            }

            // 2. Handle customer (common for both return & exchange)
            if (empty($request->mobile_no) && empty($request->name)) {
                $customer_id = random_int(1000, 9999);
            } else {
                $customer = Customer::where('mobile_no', $request->mobile_no)->first();
                $customer_id = $customer ? $customer->id : Customer::create([
                    'name' => $request->name,
                    'mobile_no' => $request->mobile_no,
                    'email' => $request->email,
                    'created_by' => Auth::id(),
                    'created_at' => Carbon::now(),
                ])->id;
            }

            // 3. Payment (only if exchange or return products exist)
            if (!empty($request->product_size_id) || !empty($request->return_product_size_id)) {

                // ✅ Create dummy invoice if not created in exchange
                if (!$invoice) {
                    $invoice = Invoice::create([
                        'invoice_no' => $request->invoice_no ?? 'RET-' . now()->timestamp,
                        'invoice_type' => 'return-only',
                        'date' => date('Y-m-d', strtotime($request->date)),
                        'description' => 'Return Only',
                        'invoice_discount_type' => null,
                        'invoice_discount_rate' => 0,
                        'invoice_discount_amount' => 0,
                        'status' => '1',
                        'created_by' => Auth::id(),
                        'created_at' => Carbon::now(),
                    ]);
                    $invoice_id = $invoice->id;
                }

                $total_payment = $request->cash + $request->visa_card + $request->master_card + $request->bKash + $request->Nagad + $request->Rocket + $request->Upay + $request->SureCash + $request->online;

                $cash = ($request->change < 0)
                    ? $request->cash + $request->change
                    : (($total_payment == 0 && $request->change == 0) ? $request->paid_amount : $request->cash);
                $cash += $request->total_return_amount;
                $total_paid_amount = 0;
                if($request->total_return_amount > $total_exchange_product_price){
                    $cash += $request->grand_total;
                    $total_paid_amount = $cash;
                }else{
                    $total_paid_amount = $request->paid_amount + $request->total_return_amount;
                }

                $payment = Payment::create([
                    'invoice_id' => $invoice->id,
                    'customer_id' => $customer_id,
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
                    'discount_amount' => $request->total_discount_amount ?? 0,
                    'total_amount' => $request->total ?? 0,
                    'paid_amount' => $total_paid_amount,
                    'due_amount' => $request->due_amount < 0 ? 0 : $request->due_amount,
                    'total_tax_amount' => 0,
                    'total_additional_charge_amount' => 0,
                ]);

                if (in_array($request->saveBtn, [2, 4])) {
                    PaymentDetail::create([
                        'customer_id' => $customer_id,
                        'transaction_id' => 'TXN-' . strtoupper(Str::random(8)),
                        'invoice_id' => $invoice->id,
                        'date' => date('Y-m-d', strtotime($request->date)),
                        'current_paid_amount' => $payment->paid_amount,
                        'received_by' => Auth::user()->name
                    ]);
                }
            }


            // 4. Sales Return (if return products exist)
            if ($request->has('return_product_size_id') && is_array($request->return_product_size_id)) {
                foreach ($request->return_product_size_id as $index => $returnProductSizeId) {
                    InvoiceDetail::where('id', $request->return_invoice_detail_id[$index])->update(['exchange' => 1]);
                    $productSize = ProductSize::find($returnProductSizeId);
                    SalesReturn::create([
                        'customer_id' => $customer_id,
                        'invoice_id' => $request->invoice_no,
                        'invoice_detail_id' => $request->return_invoice_detail_id[$index] ?? null,
                        'return_invoice_id' => $request->return_buying_invoice_id[$index] ?? null,
                        'category_id' => $request->return_category_id[$index] ?? null,
                        'product_size_id' => $returnProductSizeId,
                        'buying_date' => $request->buying_date,
                        'return_date' => $request->return_date,
                        'return_qty' => $request->return_product_quantity[$index],
                        'unit_price' => $request->return_unit_price[$index],
                        'discount_type' => 'fixed',
                        'discount_rate' => $request->return_discount_amount_per_product[$index] ?? 0,
                        'discount_amount' => $request->return_discount_amount_per_product[$index] ?? 0,
                        'tax_type' => null,
                        'tax_rate' => 0,
                        'tax_amount' => 0,
                        'buying_price' => ($request->return_product_quantity[$index] ?? 0) * ($productSize->buying_price ?? 0),
                        'selling_price' => $request->return_product_selling_price[$index] ?? 0,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);

                    ProductSize::where('id', $returnProductSizeId)
                        ->increment('quantity', $request->return_product_quantity[$index]);
                }
            }
        }); // End Transaction

        // NEW CODE
        // $redirect = 'invoice-add';
        // $exchange_and_return_invoice = Invoice::find($request->invoice_no);

        // dd($exchange_and_return_invoice);
        // return view('backend.sales-return.pdf.return_pos', compact('invoice','redirect'));
        // // return view('backend.pdf.invoice_pdf_print_by_add_invoice', compact('invoice', 'pre_due', 'redirect'));


        // // NEW CODE 
        return redirect()->route('invoice.all')->with([
            'message' => 'Exchange & Return Invoice Processed Successfully',
            'alert-type' => 'success'
        ]);
    }


    public function preView(Request $request)
    {
        if (collect($request->return_product_quantity)->sum() == 0 && $request->total_return_amount == 0) {
            return redirect()->back()->with([
                'message' => 'Sorry, you did not select any item.',
                'alert-type' => 'error'
            ]);
        }
        $data = $request->all();
        // dd($data);
        // Validate input
        $request->validate([
            // 🧾 Invoice & general
            'invoice_no' => 'required',
            'date' => 'required|date',

            // 🆕 New / Exchanged Products
            'product_id' => 'nullable|array',
            'product_id.*' => 'nullable|integer|exists:products,id',
            'selling_qty' => 'nullable|array',
            'selling_qty.*' => 'nullable|numeric|min:0',
            'unit_price' => 'nullable|array',
            'unit_price.*' => 'nullable|numeric|min:0',
            'selling_price' => 'nullable|array',
            'selling_price.*' => 'nullable|numeric|min:0',

            // 💰 Payments
            'total' => 'nullable|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',

            // 🔁 Returned Products
            'return_invoice_detail_id' => 'nullable|array',
            'return_invoice_detail_id.*' => 'nullable|integer|exists:invoice_details,id',
            'return_category_id' => 'nullable|array',
            'return_category_id.*' => 'nullable|integer|exists:categories,id',
            'return_product_size_id' => 'nullable|array',
            'return_product_size_id.*' => 'nullable|integer|exists:product_sizes,id',
            'return_product_quantity' => 'nullable|array',
            'return_product_quantity.*' => 'nullable|numeric|min:0',
            'return_product_unit_price' => 'nullable|array',
            'return_product_unit_price.*' => 'nullable|numeric|min:0',
            'return_discount_rate' => 'nullable|array',
            'return_discount_rate.*' => 'nullable|numeric|min:0',
            'return_discount_amount_per_product' => 'nullable|array',
            'return_discount_amount_per_product.*' => 'nullable|numeric|min:0',
            'return_product_selling_price' => 'nullable|array',
            'return_product_selling_price.*' => 'nullable|numeric|min:0',
            'return_product_buying_price' => 'nullable|array',
            'return_product_buying_price.*' => 'nullable|numeric|min:0',
        ]);
        $finalProducts = [];
        if ($request->product_size_id != null) {

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
        }

        $returnProducts = [];

        if ($request->has('return_invoice_detail_id')) {
            foreach ($request->return_invoice_detail_id as $index => $invoice_detail_id) {
                $product_size_details = ProductSize::find($request->return_product_size_id[$index]);

                $returnProducts[] = (object)[
                    'product_name'          => $product_size_details->product->name ?? '',
                    'product_description'   => $product_size_details->product->description ?? '',
                    'brand'                 => $product_size_details->product->brand->name ?? '',
                    'unit'                  => $product_size_details->product->unit->name ?? '',
                    'size_name'             => $product_size_details->size->name ?? '',
                    'qty'              => $request->return_product_quantity[$index],
                    'unit_price'              => $request->return_unit_price[$index],
                    'discount_rate'           => $request->return_discount_rate[$index],
                    'discount_amount'         => $request->return_discount_amount_per_product[$index],
                    'total'           => $request->return_product_selling_price[$index],
                ];
            }
        }

        // dd($returnProducts);
        // Handle Save Button Logic
        if ($request->saveBtn == 2) {
            return view('backend.sales-return.preview.return-pos-preview', compact('data', 'finalProducts', 'returnProducts'));
        }
    }
    public function view(Request $request)
    {
        // dd($request->all());
        $exchange_products = InvoiceDetail::where('invoice_id', $request->invoice_id)->get();
        $return_products   = SalesReturn::where('invoice_id', $request->invoice_id)->get();

        $finalProducts = [];

        // ✅ Existing request-based logic
        if ($exchange_products->isNotEmpty()) {
            foreach ($exchange_products as $index => $item) {
                // dd($item->product_id);
                $product_size = ProductSize::find($item->product_id);

                if ($product_size) {
                    $finalProducts[] = (object)[
                        'product_name'    => $product_size->product->name,
                        'product_description' => $product_size->product->description,
                        'brand'           => $product_size->product->brand->name ?? '',
                        'unit'            => $product_size->product->unit->name ?? '',
                        'size_name'       => $product_size->size->name ?? '',
                        'qty'             => $item->selling_qty ?? 0,
                        'unit_price'      => $item->unit_price ?? 0,
                        'discount_rate'   => $item->discount_rate ?? 0,
                        'discount_amount' => $item->discount_amount ?? 0,
                        'total'           => $item->selling_price ?? 0,
                    ];
                }
            }
        }
        // dd($finalProducts);

        // Handle return products
        $returnProducts = [];

        if ($return_products->isNotEmpty()) {
            foreach ($return_products as $index => $item) {
                $product_size_details = ProductSize::find($item->product_size_id);

                if ($product_size_details) {
                    $returnProducts[] = (object)[
                        'product_name'        => $product_size_details->product->name ?? '',
                        'product_description' => $product_size_details->product->description ?? '',
                        'brand'               => $product_size_details->product->brand->name ?? '',
                        'unit'                => $product_size_details->product->unit->name ?? '',
                        'size_name'           => $product_size_details->size->name ?? '',
                        'qty'                 => $item->return_qty ?? 0,
                        'unit_price'          => $item->unit_price ?? 0,
                        'discount_rate'       => $item->discount_rate ?? 0,
                        'discount_amount'     => $item->discount_amount ?? 0,
                        'total'               => $item->selling_price ?? 0,
                    ];
                }
            }
        }

        // Prepare data for view
        $data = SalesReturn::where('invoice_id', $request->invoice_id)->first();

        return view(
            'backend.sales-return.preview.return-pos-view',
            compact('data', 'finalProducts', 'returnProducts')
        );
    }
}
