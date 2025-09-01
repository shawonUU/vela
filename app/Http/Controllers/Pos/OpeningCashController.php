<?php

namespace App\Http\Controllers\Pos;

use App\Models\User;
use App\Models\CashRegister;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class OpeningCashController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // CashRegister er sathe cashier relation load
        $cashRegisters = CashRegister::with('cashier')
            ->where('status','open')
            ->orderBy('date', 'desc')
            ->get();
        $cashiers = User::all();

        return view('backend.cash.opening.index', compact('cashRegisters', 'cashiers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Dynamic cashiers selection from users table
        $cashiers = User::all();
        return view('backend.cash.opening.create', compact('cashiers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'cashier_id' => 'required|exists:users,id',
            'opening_balance' => 'required|numeric',
        ]);

        $exists = CashRegister::where('date', $request->date)
            ->where('cashier_id', $request->cashier_id)
            ->where('status', 'open')
            ->first();

        if ($exists) {
            return redirect()->back()->withErrors(['opening_balance' => 'Opening balance for this cashier on this date already exists.'])->withInput();
        }

        CashRegister::create([
            'date' => $request->date,
            'cashier_id' => $request->cashier_id,
            'opening_balance' => $request->opening_balance,
            'status' => 'open',
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('cash.opening.index')->with('success', 'Opening Cash added successfully.');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $cashRegister = CashRegister::findOrFail($id);
        $cashiers = User::all();
        return view('backend.cash.opening.edit', compact('cashRegister', 'cashiers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date',
            'cashier_id' => 'required|exists:users,id',
            'opening_balance' => 'required|numeric',
        ]);

        $cashRegister = CashRegister::findOrFail($id);
        $cashRegister->update([
            'date' => $request->date,
            'cashier_id' => $request->cashier_id,
            'opening_balance' => $request->opening_balance,
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('cash.opening.index')->with('success', 'Opening Cash updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $cashRegister = CashRegister::findOrFail($id);
        $cashRegister->delete();

        return redirect()->route('cash.opening.index')->with('success', 'Opening Cash deleted successfully.');
    }
}
