<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Group;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\BudgetPlan;

class BudgetController extends Controller
{
    public function createGroup(Request $request) {
        $user = Auth::user();

        $group = Group::create([
            'name' => $request->name,
            'user_id' => $user->id
        ]);

        return response()->json([
            'new group' => $group
        ]);
    }

    public function createBudgetPlan(Request $request) {
        $user = Auth::user();

        $budgetPlan = BudgetPlan::create([
            'name' => $request->name,
            'allocated_amount' => $request->allocatedAmount,
            'spent_amount' => $request->spentAmount,
            'group_id' => $request->group_id,
        ]);

        return response()->json([
            'message' => 'new budget plan created'
        ]);
    }

    public function getGroups(Request $request) {
        $user = Auth::user();
        $groups = Group::where('user_id', $user->id)->get();
        return response()->json([
            'groups' => $groups,
        ]);
    }

    public function getBudgetPlan(Request $request){
        $user = Auth::user();

        $groupId = $request->query('groupId');

        $budgetPlans = BudgetPlan::where('group_id', $groupId)->get();
        return response()->json([
            'budgetPlans' => $budgetPlans
        ]);


    }
   
}
