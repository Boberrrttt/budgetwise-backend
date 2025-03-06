<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Group;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Item;
class ChatController extends Controller
{
    public function addMember(Request $request) {
        $request->validate([
            'group_id' => 'required',
            'user_id' => 'required',
        ]);

        $groupId = $request->group_id;
        $userId = $request->user_id;
        $authUser = Auth::user();

        $group = Group::find($groupId);

        if (!$group) {
            return response()->json([
                'error' => 'group not found'
            ], 404);
        }

        




    }
}
