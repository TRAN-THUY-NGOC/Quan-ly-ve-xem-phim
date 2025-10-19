<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $order = Order::create([
            'user_id' => auth()->id(),
            'total'   => $request->input('total'),
            'status'  => 'pending',
        ]);

        session(['order_id' => $order->id]);
        return redirect()->route('thanhtoan');
    }
}
