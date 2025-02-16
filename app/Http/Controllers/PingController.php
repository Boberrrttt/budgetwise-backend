<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Events\PingEvent; 

class PingController extends Controller
{
    public function __invoke(Request $request)
    {
        event(new PingEvent(['message' => 'Hello, WebSockets!']));  // Trigger the event
        return response()->json(['status' => 'Event broadcasted']);
    }
}
