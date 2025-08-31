<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Fabric;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FabricController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:fabric-list|fabric-create|fabric-edit|fabric-delete'], ['only' => ['FabricAdd']]);
        $this->middleware(['permission:fabric-create'], ['only' => ['FabricStore']]);
        $this->middleware(['permission:fabric-edit'], ['only' => ['FabricEdit', 'FabricUpdate']]);
        $this->middleware(['permission:fabric-delete'], ['only' => ['FabricDelete']]);
    }
    // Fabric insert form
    public function FabricAdd()
    {
        $fabrics = Fabric::latest()->get();
        return view('backend.fabric.fabric_add', compact('fabrics'));
    } //End Method

    // Save unit insert form to Database
    public function FabricStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:fabrics,name',
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

        $fabric = new Fabric();
        $fabric->name = $request->name;
        $fabric->slug = strtolower(str_replace(' ', '-', $request->name));
        $fabric->description = $request->description;
        $fabric->save();

        return redirect()->route('fabric.add')->with([
            'message' => 'Fabric Created Successfully',
            'alert-type' => 'success'
        ]);
    } //End Method

    // Fabric Edit form
    public function FabricEdit($id)
    {
        $fabric = Fabric::findOrFail($id);
        return view('backend.fabric.fabric_edit', compact('fabric'));
    } //End Method

    // Fabric edited data save to database
    public function FabricUpdate(Request $request)
    {
        $fabric = Fabric::findOrFail($request->id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:fabrics,name,' . $fabric->id,
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

        $fabric->name = $request->name;
        $fabric->slug = strtolower(str_replace(' ', '-', $request->name));
        $fabric->description = $request->description;
        $fabric->save();

        return redirect()->route('fabric.add')->with([
            'message' => 'Fabric Updated Successfully',
            'alert-type' => 'success'
        ]);
    }
    //End Method

    // Brand Delete from database
    public function FabricDelete($id)
    {
        Fabric::findOrFail($id)->delete();
        $notification = array(
            'message' => 'Fabric Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }
}
