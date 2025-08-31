<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExpenseCategory;
use Illuminate\Support\Facades\Auth;

class ExpenseCategoryController extends Controller
{
    /**
     * Display a listing of the expense categories.
     */
    public function index()
    {
        $categories = ExpenseCategory::latest()->get();
        return view('backend.expense-category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new expense category.
     */
    public function create()
    {
        return view('backend.expense-category.create');
    }

    /**
     * Store a newly created expense category in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255|unique:expense_categories,name',
            'status' => 'required|in:0,1',
        ]);

        ExpenseCategory::create([
            'name'       => $request->name,
            'status'     => $request->status,
            'created_by' => Auth::id(),
        ]);

        $notification = [
            'message' => 'Expense Category Saved Successfully',
            'alert-type' => 'success'
        ];

        return redirect()->route('expenses.category.index')->with($notification);
    }

    /**
     * Display the specified expense category.
     */
    public function show(ExpenseCategory $expenseCategory)
    {
        return view('backend.expense-category.show', compact('expenseCategory'));
    }

    /**
     * Show the form for editing the specified expense category.
     */
  public function edit($id)
{
    $expenseCategory = ExpenseCategory::findOrFail($id);
    return view('backend.expense-category.edit', compact('expenseCategory'));
}


    /**
     * Update the specified expense category in storage.
     */
    public function update(Request $request, $id)
    {
        $expenseCategory = ExpenseCategory::findOrFail($id);
        $request->validate([
            'name'   => 'required|string|max:255|unique:expense_categories,name,' . $expenseCategory->id,
            'status' => 'required|in:0,1',
        ]);

        $expenseCategory->update([
            'name'       => $request->name,
            'status'     => $request->status,
            'updated_by' => Auth::id(),
        ]);

        $notification = [
            'message' => 'Expense Category Updated Successfully',
            'alert-type' => 'success'
        ];

        return redirect()->route('expenses.category.index')->with($notification);
    }

    /**
     * Remove the specified expense category from storage.
     */
    public function destroy($id)
    {
        $expenseCategory = ExpenseCategory::findOrFail($id);
        $expenseCategory->delete();
        return redirect()->route('expenses.category.index')
                        ->with('success', 'Expense Category deleted successfully.');
    }

}
