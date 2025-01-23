<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Group;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\BudgetPlan;
use App\Models\Item;

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
       
        $group = Group::where('id', $request->groupId)->first();
        Log::info($group);
    
        if (!$group) {
            return response()->json([
                'message' => 'Group not found',
            ], 404);
        }
    
        $budgetPlan = BudgetPlan::create([
            'name' => $request->name,
            'allocated_amount' => $request->allocatedAmount,
            'spent_amount' => 0,
            'group_id' => $request->groupId,
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

    public function getItems(Request $request) {
        $user = Auth::user();

        $planId = $request->query('planId');

        $items = Item::where('budget_plan_id', $planId)->get();
        return response()->json([
            'items' => $items
        ]);
    }


    public function addItem(Request $request) {
        $user = Auth::user();
    }
   
}
