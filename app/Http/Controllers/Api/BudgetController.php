<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Group;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

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

    public function getGroups(Request $request) {
        $user = Auth::user();
    
        $groups = Group::where('user_id', $user->id)->get(); 

        return response()->json([
            'groups' => $groups,
        ]);
    }

    public function createBudgetPlan(Request $request) {
        
    }
}
