<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Support\Facades\Log;

class NewBudgetPlanCreated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $budgetPlan;
    public $groupId; // Define groupId

    public function __construct($budgetPlan, $groupId) // Pass groupId in constructor
    {
        $this->budgetPlan = $budgetPlan;
        $this->groupId = $groupId; // Assign to class property
        Log::info("📢 Broadcasting event for group: " . $this->groupId);
    }

    public function broadcastOn()
    {
        return new PrivateChannel("budget-plans.{$this->groupId}"); // Now it's defined
    }

    public function broadcastAs()
    {
        return "BudgetPlanCreated";
    }
}
