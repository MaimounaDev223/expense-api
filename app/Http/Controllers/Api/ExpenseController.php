<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Expense;

use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
{
    return $request->user()->expenses()->with('category')->get();
}

    public function store(Request $request)
{
    $data = $request->validate([
        'category_id' => 'nullable|exists:categories,id',
        'amount' => 'required|numeric|min:0.01',
        'description' => 'nullable|string',
        'spent_at' => 'required|date',
    ]);

    $expense = $request->user()->expenses()->create($data);

    return response()->json($expense, 201);
}
    public function destroy(Request $request, Expense $expense)
{
    if ($expense->user_id !== $request->user()->id) {
        return response()->json(['message' => 'Non autorisé'], 403);
    }

    $expense->delete();

    return response()->noContent();
}
}
