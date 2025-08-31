<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Size;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SizeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:size-list|size-create|size-edit|size-delete'], ['only' => ['SizeAdd']]);
        $this->middleware(['permission:size-create'], ['only' => ['SizeStore']]);
        $this->middleware(['permission:size-edit'], ['only' => ['SizeEdit', 'SizeUpdate']]);
        $this->middleware(['permission:size-delete'], ['only' => ['SizeDelete']]);
    }
    // Size insert form
    public function SizeAdd()
    {
        $sizes = Size::latest()->get();
        return view('backend.size.size_add', compact('sizes'));
    } //End Method

    // Save unit insert form to Database
    public function SizeStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:sizes,name',
            'description' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with([
                    'message' => 'Warning! Try again.',
                    'alert-type' => 'warning'
                ]);
        }

        $size = new Size();
        $size->name = $request->name;
        $size->slug = strtolower(str_replace(' ', '-', $request->name));
        $size->description = $request->description;
        $size->save();

        return redirect()->route('size.add')->with([
            'message' => 'Size Created Successfully',
            'alert-type' => 'success'
        ]);
    } //End Method

    // Size Edit form
    public function SizeEdit($id)
    {
        $size = Size::findOrFail($id);
        return view('backend.size.size_edit', compact('size'));
    } //End Method

    // Size edited data save to database
    public function SizeUpdate(Request $request)
    {
        $size = Size::findOrFail($request->id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:sizes,name,' . $size->id,
            'description' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with([
                    'message' => 'Warning! Try again.',
                    'alert-type' => 'warning'
                ]);
        }

        $size->name = $request->name;
        $size->slug = strtolower(str_replace(' ', '-', $request->name));
        $size->description = $request->description;
        $size->save();

        return redirect()->route('size.add')->with([
            'message' => 'Size Updated Successfully',
            'alert-type' => 'success'
        ]);
    }
    //End Method

    // Brand Delete from database
    public function SizeDelete($id)
    {
        Size::findOrFail($id)->delete();
        $notification = array(
            'message' => 'Size Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }
}
