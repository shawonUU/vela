<?php

namespace App\Http\Controllers\Pos;

use App\Models\User;
use App\Models\Sale; 
use App\Models\Invoice;
use App\Models\Expense; 
use App\Models\CashRegister;
use Illuminate\Http\Request;
use App\Models\InvoiceDetail;
use App\Http\Controllers\Controller;

class ClosingCashController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cashRegisters = CashRegister::with('cashier')->where('status','closed')->orderBy('date', 'desc')->get();
        $cashiers = User::all();
        return view('backend.cash.closing.index', compact('cashRegisters','cashiers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cashiers = User::all(); // Cashier selection যদি লাগে
        return view('backend.cash.closing.create', compact('cashiers'));
    }

    /**
     * Store a newly created resource in storage.
     */
  public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'cashier_id' => 'required|exists:users,id', // assuming cashier_id is from users table
        ]);

        // Check if this cashier already has closing for this date
        $existing = CashRegister::where('date', $request->date)
                    ->where('cashier_id', $request->cashier_id)
                    ->where('status', 'closed')
                    ->first();

        if($existing){
            return back()->withErrors(['cashier_id' => 'This cashier has already closed cash for this date.'])->withInput();
        }

        // Auto calculate
        $openingBalance = CashRegister::whereDate('date', '<=', $request->date)
        ->orderBy('date', 'desc')
        ->value('opening_balance') ?? 0;


        $totalSales = InvoiceDetail::whereDate('date', $request->date)->sum('selling_price'); // Invoice detail selling_price
        $totalExpenses = Expense::whereDate('date', $request->date)->sum('amount');

        $closingBalance = $openingBalance + $totalSales - $totalExpenses;

        // Create record
        CashRegister::create([
            'date' => $request->date,
            'cashier_id' => $request->cashier_id,
            'opening_balance' => $openingBalance,
            'total_sales' => $totalSales,
            'total_expenses' => $totalExpenses,
            'closing_balance' => $closingBalance,
            'status' => 'closed',
        ]);

        return redirect()->route('cash.closing.index')->with('success', 'Closing Cash added successfully.');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $cashRegister = CashRegister::findOrFail($id);
        $cashiers = User::all();
        return view('backend.cash.closing.edit', compact('cashRegister', 'cashiers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date',
            'cashier_name' => 'required|string',
        ]);

        $cashRegister = CashRegister::findOrFail($id);

        $openingBalance = CashRegister::where('date', '<', $request->date)
            ->orderBy('date', 'desc')->value('closing_balance') ?? 0;

        $totalSales = Sale::whereDate('created_at', $request->date)->sum('amount');
        $totalExpenses = Expense::whereDate('created_at', $request->date)->sum('amount');

        $closingBalance = $openingBalance + $totalSales - $totalExpenses;

        $cashRegister->update([
            'date' => $request->date,
            'cashier_name' => $request->cashier_name,
            'opening_balance' => $openingBalance,
            'total_sales' => $totalSales,
            'total_expenses' => $totalExpenses,
            'closing_balance' => $closingBalance,
            'status' => 'closed',
        ]);

        return redirect()->route('cash.closing.index')->with('success', 'Closing Cash updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $cashRegister = CashRegister::findOrFail($id);
        $cashRegister->delete();

        return redirect()->route('cash.closing.index')->with('success', 'Closing Cash deleted successfully.');
    }

    public function getClosingBalance(Request $request)
{
     $request;
    $request->validate([
        'date' => 'required|date',
    ]);

    $date = $request->date;

    // Total sales from invoice_details table
    $totalSales = \DB::table('invoice_details')
        ->whereDate('date', $date)
        ->sum('selling_price');

    // Total expenses
    $totalExpenses = Expense::whereDate('date', $date)->sum('amount');

    return response()->json([
        'total_invoice_amount' => $totalSales,   // key updated
        'total_expenses' => $totalExpenses,
    ]);
}

}
