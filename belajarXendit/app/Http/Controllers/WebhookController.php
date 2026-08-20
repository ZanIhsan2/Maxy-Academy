<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        $xenditId = $request->input('id');
        $status = $request->input('status');

        $order = Order::where('xendit_id', $xenditId)->first();

        if ($order){
            $order->update(['status' => strtoupper($status)]);
        }

        return response()->json(['message' => 'Webhook received'], 200);
    }
}