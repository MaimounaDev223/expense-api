<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function byCategory(Request $request)
{
    $expenses = $request->user()->expenses()->with('category')->get();

    return $expenses
        ->groupBy(fn ($expense) => $expense->category?->name ?? 'Sans catégorie')
        ->map(fn ($group, $name) => [
            'category' => $name,
            'total' => round($group->sum('amount'), 2),
        ])
        ->sortByDesc('total')
        ->values();
}
}
