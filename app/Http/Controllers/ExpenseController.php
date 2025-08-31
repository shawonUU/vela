<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the expenses.
     */
    public function index(Request $request)
    {
        $show_start_date=$request->get('startDate');
        $show_end_date=$request->get('endDate');
        $filter = $request->get('filter');
        $approval = $request->get('approval');
        if($request->get('startDate') && $request->get('endDate')){
            $startDate = Carbon::parse($request->get('startDate'));
            $endDate = Carbon::parse($request->get('endDate'));
            $endDate->endOfDay();
        }else{
            $startDate = Carbon::parse(today());
            $endDate = Carbon::parse(today());
            $endDate->endOfDay();
        }
        $expenses = Expense::whereBetween('date', [$startDate, $endDate]);


        if( $approval != ""){
            $expenses = $expenses->where('is_approved', $approval);
        }
        
        $expenses = $expenses->with(['category', 'creator', 'updater'])->latest()->get();

        $totalExpanseAmount            = $expenses->sum('amount');
        $totalNoApprovedExpanseAmount  = $expenses->where('is_approved', '0')->sum('amount');
        $totalExpanse                  = $expenses->count();
        $totalNoApprovedExpanse        = $expenses->where('is_approved', '0')->count();

        return view('backend.expense.index', compact('totalExpanseAmount','totalNoApprovedExpanseAmount', 'totalExpanse', 'totalNoApprovedExpanse', 'expenses','filter','approval','show_start_date','show_end_date'));
    }

    /**
     * Show the form for creating a new expense.
     */
    public function create()
    {
        $categories = ExpenseCategory::where('status', 1)->get();
        return view('backend.expense.create', compact('categories'));
    }

    /**
     * Store a newly created expense in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:expense_categories,id',
            'amount'      => 'required|numeric|min:0',
            'date'        => 'required|date',
            'note'        => 'nullable|string',
        ]);

        Expense::create([
            'category_id' => $request->category_id,
            'amount'      => $request->amount,
            'date'        => $request->date,
            'note'        => $request->note,
            'created_by'  => Auth::id(),
        ]);

        $notification = array(
            'message' => 'Expense Saved Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('expenses.index')->with($notification);
    }

    /**
     * Display the specified expense.
     */
    public function show(Expense $expense)
    {
        return view('backend.expense.show', compact('expense'));
    }

    /**
     * Show the form for editing the specified expense.
     */
    public function edit(Expense $expense)
    {
        $categories = ExpenseCategory::where('status', 1)->get();
        return view('backend.expense.edit', compact('expense', 'categories'));
    }

    /**
     * Update the specified expense in storage.
     */
    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'category_id' => 'required|exists:expense_categories,id',
            'amount'      => 'required|numeric|min:0',
            'date'        => 'required|date',
            'note'        => 'nullable|string',
            'approval' => 'nullable|numeric|in:0,1',
        ]);

        $expense->update([
            'category_id' => $request->category_id,
            'amount'      => $request->amount,
            'date'        => $request->date,
            'note'        => $request->note,
            'is_approved' => $request->approval,
            'updated_by'  => Auth::id(),
        ]);

        $notification = array(
            'message' => 'Expense Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('expenses.index')->with($notification);
    }

    /**
     * Remove the specified expense from storage.
     */
    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
    }
}
