<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductSize;
use Illuminate\Http\Request;

class ProductLabelsPrintController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:product-label-list'], ['only' => ['index']]);
    }
    public function index(Request $request)
    {
        // $products = Product::all();
        // dd($products);
        return view('backend.product.print_labels.index');
    }
    public function labelPrint(Request $request)
    {
        // dd($request->all());
        $productIds = $request->product_id;
        $sizes = $request->sizes;
        $quantities = $request->qty;

        $labelItems = [];

        for ($i = 0; $i < count($productIds); $i++) {
            $product = \App\Models\Product::find($productIds[$i]);
            $productSize = \App\Models\ProductSize::with('size')->where('size_id',$sizes[$i])->where('product_id', $productIds[$i])->first();

            $labelItems[] = [
                'product' => $product ? $product->name : 'N/A',
                'size_name' => $productSize && $productSize->size ? $productSize->size->name : 'N/A',
                'barcode' => $productSize ? $productSize->barcode : 'N/A',
                'buying_price_code' => $productSize ? $productSize->buying_price_code : 'N/A',
                'selling_price' => $productSize ? $productSize->selling_price : 0,
                'discounted_price' => $productSize ? $productSize->discounted_price : 0,
                'qty' => $quantities[$i],
            ];
        }
        // dd($labelItems);
        if ($request->btn == 1) {
            // A4 Page Label Print
            return view('backend.product.print_labels.pdf.product_labels_print', compact('labelItems'));
        } elseif ($request->btn == 2) {
            // Thermal Label Print
            return view('backend.product.print_labels.pdf.product_labels_thermal_print', compact('labelItems'));
        }
    }
}
