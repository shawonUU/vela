<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExpenseArticle;
use App\Models\ExpenseCategory;

class ExpenseArticleController extends Controller
{
    /**
     * Display a listing of the expense articles.
     */
    public function index()
    {
        $articles = ExpenseArticle::latest()->get();
        $expenseCategories = ExpenseCategory::where('status', 1)->latest()->get();
        return view('backend.expenses-articles.index', compact('articles', 'expenseCategories'));
    }

    /**
     * Show the form for creating a new expense article.
     */
    public function create()
    {
        return view('expenses.articles.create');
    }

    /**
     * Store a newly created expense article in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'name'                => 'required|string|max:255',
            'notes'               => 'nullable|string',
            'status'              => 'required|boolean',
            'expense_category_id' => 'required|exists:expense_categories,id',
            'is_approved'          => 'required|boolean',
        ]);

        ExpenseArticle::create([
            'name'                => $request->name,
            'notes'               => $request->notes,
            'status'              => $request->status,
            'expense_category_id' => $request->expense_category_id,
            'is_approved'          => $request->is_approved,
            'created_by'          => auth()->id(),
        ]);

        return redirect()->route('expenses.article.index')
            ->with('success', 'Expense Article created successfully.');
    }


    /**
     * Show the form for editing the specified expense article.
     */
    public function edit($id)
    {
        $expenseArticle = ExpenseArticle::findOrFail($id);
        $expenseCategories = ExpenseCategory::where('status', 1)->latest()->get();
        return view('backend.expenses-articles.edit', compact('expenseArticle', 'expenseCategories'));
    }

    /**
     * Update the specified expense article in storage.
     */
    public function update(Request $request, ExpenseArticle $article)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'notes'  => 'nullable|string',
            'status' => 'required|boolean',
            'expense_category_id' => 'required|exists:expense_categories,id',
            'is_approved'          => 'required|boolean',
        ]);

        $article->update([
            'name'       => $request->name,
            'notes'      => $request->notes,
            'status'     => $request->status,
            'expense_category_id' => $request->expense_category_id,
            'is_approved'          => $request->is_approved,
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('expenses.article.index')
            ->with('success', 'Expense Article updated successfully.');
    }


    /**
     * Remove the specified expense article from storage.
     */
    public function destroy($id)
    {
        $article = ExpenseArticle::findOrFail($id);
        $article->delete();

        return redirect()->route('expenses.article.index')
            ->with('success', 'Expense Article deleted successfully.');
    }

    public function getArticles($categoryId)
    {
        $articles = ExpenseArticle::where('expense_category_id', $categoryId)
            ->where('status', 1)
            ->get();
        return response()->json($articles);
    }
}
