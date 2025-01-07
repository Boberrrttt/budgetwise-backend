<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Group;

class BudgetController extends Controller
{
    public function createGroup(Request $request) {
        $group = Group::create([
            'name' => $request->name,
        ]);

        return response()->json([
            'new group' => $group
        ]);
    }

    public function createBudgetPlan(Request $request) {
        
    }
}
