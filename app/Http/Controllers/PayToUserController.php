<?php

namespace App\Http\Controllers;

use App\Models\PayToUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaytoUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = PayToUser::latest()->get();
        return view('backend.payto_users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.payto_users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255|unique:pay_to_users,name',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        PayToUser::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'phone'      => $request->phone,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('payto.users.index')->with([
            'message' => 'Pay To User created successfully.',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PayToUser $user)
    {
        return view('backend.payto_users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PayToUser $user)
    {
        $request->validate([
            'name'  => 'required|string|max:255|unique:pay_to_users,name,' . $user->id,
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update([
            'name'       => $request->name,
            'email'      => $request->email,
            'phone'      => $request->phone,
            'address'      => $request->address,
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('payto.users.index')->with([
            'message' => 'Pay To User updated successfully.',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = PayToUser::findOrFail($id);
        $user->delete();

        return redirect()->route('payto.users.index')->with([
            'message' => 'Pay To User deleted successfully.',
            'alert-type' => 'success'
        ]);
    }
}
