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

        // make request form
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $group = Group::create([
            'name' => $request->name,
            'user_id' => $user->id
        ]);

        return response()->json([
            'new group' => $group
        ]);
    }

    public function createBudgetPlan(Request $request) {
       // you can use find instead of where
        $group = Group::where('id', $request->groupId)->first();
        
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

        $groupId = $request->query('groupId');

        $budgetPlans = BudgetPlan::where('group_id', $groupId)->get();
        return response()->json([
            'budgetPlans' => $budgetPlans
        ]);
    }

    public function getItems(Request $request) {

        $budgetPlan = BudgetPlan::find($request->query('planId'));
        
        if (!$budgetPlan) {
            return response()->json([
                'error' => 'budget plan not found'
            ], 404);
        }

        $planId = $request->query('planId');

        $items = Item::where('budget_plan_id', $planId)->get();
        return response()->json([
            'items' => $items,
            'updatedSpentAmount' => $budgetPlan->spent_amount
        ]);
    }

    public function addItem(Request $request) {
        
        $budgetPlan = BudgetPlan::find($request->input('planId'));
    
        if (!$budgetPlan) {
            return response()->json([
                'error' => 'budget plan not found'
            ], 404);
        }
    
        $budgetPlan->update([
            'spent_amount' => $budgetPlan->spent_amount + $request->input('price')
        ]);
    
        Item::create([
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'budget_plan_id' => $request->input('planId')
        ]);
    
        return response()->json([
            'message' => 'item added'
        ]);
    }

    public function deleteItem(Request $request) {
        $item = Item::find($request->query('itemId'));        
    
        if (!$item) {
            return response()->json([
                'error' => 'item not found'
            ], 404);
        }

        $budgetPlan = BudgetPlan::find($item->budget_plan_id);
        
        $budgetPlan->update([
            'spent_amount' => $budgetPlan->spent_amount - $item->price
        ]);

        $item->delete();

        return response()->json([
            'message' => 'item deleted'
        ]);
    }

    public function deletePlan(Request $request) {
        $budgetPlan = BudgetPlan::find($request->query('planId'));

        if (!$budgetPlan) {
            return response()->json([
                'error' => 'budget plan not found'
            ], 404);
        }

        $budgetPlan->delete();

        return response()->json([
            'message' => 'budget plan deleted'
        ]);
    }
   
}
