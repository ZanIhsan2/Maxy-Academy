<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{
    public function store (Request $request)
    {
        $request->validate([
            'event_id' => 'required|exist:events_id',
        ]);

        $user = auth()->user();
        $event = Event::findOrFail($request->event_id);

        $order = Order::create([
            'user_id' =>$user->id,
            'event_id' =>$event->id,
            'status' =>'PENDING',
            'amount' =>$event->price, 
        ]);

        $response = Http::withBasicAuth(env('XENDIT_API_KEY'),'')
            ->post('https://api.xendit.co/v2/invoices',[
                'external_id' => 'order' . $order->id,
                'payer_email' => $user->email,
                'description' => 'Tiket untuk event ' . $event->title,
                'amount' => $event->price,
                'success_redirect_url' => 'https://example.com/success'
            ]);

        if ($response->failed()){
            return response()->json(['message' => 'Gagal membuat invoice'], 500);
        }

        $xendit = $response->json();

        $order->update(['xendit_id' => $xendit['id']]);

        return response()->json([
            'message' => 'invoice created',
            'invoice' => $xendit,
        ]);
    }

    public function myOrders()
    {
        return auth()->user()->orders->with('event')->get();
    }
}