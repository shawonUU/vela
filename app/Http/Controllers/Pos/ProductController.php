<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Category;
use App\Models\Fabric;
use App\Models\ProductPriceCodes;
use App\Models\ProductSize;
use App\Models\ProductTax;
use App\Models\Size;
use App\Models\Tax;
use App\Models\Unit;
use Auth;
use Illuminate\support\Carbon;
use Image;
use DB;
use Hamcrest\Type\IsString;
use Illuminate\Support\Str;


class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:product-list|product-create|product-edit|product-delete'], ['only' => ['ProductAll']]);
        $this->middleware(['permission:product-create'], ['only' => ['ProductAdd', 'ProductStore']]);
        $this->middleware(['permission:product-edit'], ['only' => ['ProductEdit', 'ProductUpdate']]);
        $this->middleware(['permission:product-delete'], ['only' => ['ProductDelete']]);
    }
    // Product show from database
    public function ProductAll()
    {
        $products = Product::latest()->get();
        // dd($products);
        return view('backend.product.product_all', compact('products'));
    } //End Method


    // Product insert form
    public function ProductAdd()
    {
        $size = Size::all();
        $category = Category::all();
        $brands = Brand::all();
        $unit     = Unit::all();
        $fabrics = Fabric::all();
        $productPriceCode = ProductPriceCodes::all();
        return view('backend.product.product_add', compact('size', 'unit', 'brands', 'category', 'productPriceCode', 'fabrics'));
    } //End Method

    // Save Product insert form to Database
    public function ProductStore(Request $request)
    {
        // dd($request->all());
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'product_sort_name' => 'nullable|string|max:255',
            'brand_id' => 'integer|min:-1',
            'category_id' => 'integer|min:-1',
            'unit_id' => 'required|integer|min:-1',
            'sizes.*' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:256',
        ]);

        // Check if new brand needs to be created
        if ($request->brand_id == -1) {
            $brand = Brand::create([
                'name' => $request->brand_name,
                'created_by' => Auth::id(),
            ]);
            $brandName = $request->brand_name;
            $brandId = $brand->id;
        } else if ($request->brand_id != 0) {
            $brandId = $request->brand_id;
            $brandName = Brand::find($brandId)->name;
        } else {
            $brandId = 0;
            $brandName = '';
        }

        // Check if new category needs to be created
        if ($request->category_id == -1) {
            $category = Category::create([
                'name' => $request->category_name,
                'created_by' => Auth::id(),
            ]);
            $categoryName = $request->category_name;
            $categoryId = $category->id;
        } else if ($request->category_id != 0) {
            $categoryId = $request->category_id;
            $categoryName = Category::find($categoryId)->name;
        } else {
            $categoryId = 0;
            $categoryName = '';
        }
        // Check if new unit needs to be created
        if ($request->unit_id == -1) {
            $unit = Unit::create([
                'name' => $request->unit_name,
                'created_by' => Auth::id(),
            ]);
            $unitId = $unit->id;
        } else {
            $unitId = $request->unit_id;
        }
        // SKU
        // if (($request->brand_id != 0 && $request->category_id != 0) || ($request->brand_id == -1 && $request->category_id == -1 || $request->category_id == -1)) {
        //     $sku = Str::upper(substr($brandName, 0, 2)) . '-' . Str::upper(substr($categoryName, 0, 2)) . '-' . $request->sku;
        // } else {
        //     $sku = $request->sku;
        // }
        // Handle product image
        $productImage = $this->handleImageUpload($request->file('product_image'));

        // Insert product
        $product = Product::create([
            'name' => $request->name,
            'product_sort_name' => $request->product_sort_name,
            'product_image' => $productImage,
            'category_id' => $categoryId,
            'brand_id' => $brandId,
            'unit_id' => $unitId,
            'expire_date' => $request->expire_date,
            'fabrics' => json_encode($request->fabrics ?? []),
            'description' => $request->description,
            'created_by' => Auth::id(),
        ]);

        // Insert each size variant into product_sizes table
        if ($request->has('sizes') && is_array($request->sizes)) {
            foreach ($request->sizes as $index => $sizeId) {
                ProductSize::create([
                    'product_id'      => $product->id,
                    'size_id'         => $sizeId,
                    'color'           => json_encode($request->colors[$index] ?? []),
                    'quantity'        => $request->quantities[$index] ?? 0,
                    'sku'             => $request->skus[$index] ?? null,
                    'barcode'         => $request->barcodes[$index] ?? null,
                    'buying_price'    => $request->buying_prices[$index] ?? 0,
                    'buying_price_code'      => $request->price_codes[$index] ?? null,
                    'selling_price'   => $request->selling_prices[$index] ?? 0,
                    'discounted_price'     => $request->discounted_price[$index] ?? 0,
                    'wholesell_price'     => $request->wholesell_price[$index] ?? 0,
                    'wholesell_discounted_price'     => $request->wholesell_discounted_price[$index] ?? 0,
                    'fixed_price' => is_numeric($request->fiexed_price[$index] ?? 0) ? $request->fiexed_price[$index] : 0,
                    'max_discount' => $request->max_discount[$index] ?? 0,
                    'offer_discount' => $request->offer_discount[$index] ?? 0,
                    'offer_from' => $request->offer_from[$index] ?? 0,
                    'offer_to' => $request->offer_to[$index] ?? 0,
                ]);
            }
        }

        return redirect()->route('product.all')->with([
            'message' => 'Product Inserted Successfully',
            'alert-type' => 'success',
        ]);
    }

    /**
     * Handles product image upload.
     */
    private function handleImageUpload($image)
    {
        if ($image) {
            $imageName = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            Image::make($image)->resize(200, 200)->save('upload/product_images/' . $imageName);
            return 'upload/product_images/' . $imageName;
        }
        return null;
    } //End Method



    // Product Edit form
    public function ProductEdit($id)
    {
        $supplier = Supplier::all();
        $category = Category::all();
        $brands   = Brand::all();
        $unit     = Unit::all();
        $productPriceCode = ProductPriceCodes::all();
        $size = Size::all();
        $fabrics = Fabric::all();
        $product = Product::findOrFail($id);
        return view('backend.product.product_edit', compact('product', 'supplier', 'category', 'unit', 'brands', 'productPriceCode', 'size', 'fabrics'));
    } //End Method


    // Product Update data save to database
    public function ProductUpdate(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required|string|max:255',
            'product_sort_name' => 'nullable|string|max:255',
            'brand_id' => 'integer|min:-1',
            'category_id' => 'integer|min:-1',
            'unit_id' => 'required|integer|min:-1',
            'sizes.*' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:256',
        ]);

        $product = Product::findOrFail($request->id);
        // dd($product);
        $oldImage = $product->product_image;

        // Handle brand, category, unit creation logic
        $brandId = $this->handleDynamicCreation(Brand::class, $request->brand_id, $request->brand_name);
        $categoryId = $this->handleDynamicCreation(Category::class, $request->category_id, $request->category_name);
        $unitId = $this->handleDynamicCreation(Unit::class, $request->unit_id, $request->unit_name);

        // Product image update
        if ($request->hasFile('product_image')) {
            $productImage = $this->handleImageUpload($request->file('product_image'));
            if ($oldImage && file_exists(public_path($oldImage))) {
                @unlink(public_path($oldImage));
            }
        } else {
            $productImage = $oldImage;
        }

        // Update product
        $product->update([
            'name' => $request->name,
            'product_sort_name' => $request->product_sort_name,
            'product_image' => $productImage,
            'category_id' => $categoryId,
            'brand_id' => $brandId,
            'unit_id' => $unitId,
            'expire_date' => $request->expire_date,
            'fabrics' => json_encode($request->fabrics ?? []),
            'description' => $request->description,
        ]);

        $existingProductSizes = ProductSize::where('product_id', $request->id)->get();
        // dd($existingProductSizes);
        // Get IDs of variants submitted with the form (excluding "0")
        $submittedProductSizeIds = collect($request->product_size_id)
        ->filter(fn($id) => intval($id) > 0)
        ->map(fn($id) => intval($id));
        // dd($submittedProductSizeIds);
        // Delete variants that exist in DB but were removed in the form
        foreach ($existingProductSizes as $variant) {
            if (!$submittedProductSizeIds->contains($variant->id)) {
                $variant->delete();
            }
        }

        // Now insert or update variants
        foreach ($request->product_size_id as $index => $psId) {
            $psId = intval($psId); // Ensure integer

            $sizeData = [
                'product_id' => $product->id,
                'size_id' => $request->sizes[$index],
                'color' => json_encode($request->colors[$index] ?? []),
                'quantity' => $request->quantities[$index] ?? 0,
                'sku' => $request->skus[$index] ?? null,
                'barcode' => $request->barcodes[$index] ?? null,
                'buying_price' => $request->buying_prices[$index] ?? 0,
                'buying_price_code' => $request->price_codes[$index] ?? null,
                'selling_price' => $request->selling_prices[$index] ?? 0,
                'discounted_price' => $request->discounted_price[$index] ?? 0,
                'wholesell_price' => $request->wholesell_price[$index] ?? 0,
                'wholesell_discounted_price' => $request->wholesell_discounted_price[$index] ?? 0,
                'fixed_price' => (int)($request->fixed_price[$index] ?? 0),
                'max_discount' => $request->max_discount[$index] ?? 0,
                'offer_discount' => $request->offer_discount[$index] ?? 0,
                'offer_from' => $request->offer_from[$index] ?? null,
                'offer_to' => $request->offer_to[$index] ?? null,
            ];

            // return $sizeData;

            if ($psId === 0) {
                ProductSize::create($sizeData);
            } else {
                $variant = ProductSize::where('id', $psId)
                    ->where('product_id', $product->id) // extra safety
                    ->first();

                if ($variant) {
                    $variant->update($sizeData);
                } else {
                    // Optional: fallback to create if not found
                    ProductSize::create($sizeData);
                }
            }
        }

        return redirect()->route('product.edit', $request->id)->with([
            'message' => 'Product Updated Successfully',
            'alert-type' => 'success',
        ]);
    }

    private function handleDynamicCreation($model, $id, $name)
    {
        if ($id == -1) {
            return $model::create(array_merge(['name' => $name, 'created_by' => Auth::id()]))->id;
        } elseif ($id != 0) {
            return $id;
        } else {
            return 0;
        }
    }

    //End Method

    // Product Delete from database
    public function ProductDelete($id)
    {
        $products = Product::findOrFail($id);
        $img =  $products->product_image;

        if ($img) {
            unlink($img);
        }

        Product::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Product Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    } //End Method

    public function getProductSizes(Request $request)
    {
        // dd($request->all());
        $productId = $request->product_id;

        // Assuming product_id is in ProductSize table
        $sizes = ProductSize::where('product_id', $productId)->with('size')->get();

        // Format output
        $data = $sizes->map(function ($item) {
            return [
                'id' => $item->size->id,
                'name' => $item->size->name
            ];
        });

        return response()->json($data);
    }

    public function discountAnalysis(){
        $products = Product::latest()->get();
        $product = Product::first();

        return view('backend.product.discount_analysis', compact('products'));
    }
}
