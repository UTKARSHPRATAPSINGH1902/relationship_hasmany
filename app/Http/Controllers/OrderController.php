<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Customer;
use App\Models\PaymentMethod;

class OrderController extends Controller {
    public function placeOrder(Request $request) {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'amount' => 'required|numeric|min:1',
        ]);
    
        $order = Order::create([
            'customer_id' => $request->customer_id,
            'payment_method_id' => $request->payment_method_id,
            'amount' => $request->amount,
            'status' => 'pending', // Default status
        ]);
    
        return response()->json([
            'message' => 'Order placed successfully!',
            'order' => [
                'id' => $order->id,
                'customer_id' => $order->customer_id,
                'payment_method_id' => $order->payment_method_id,
                'amount' => $order->amount,
                'status' => $order->status,
                'created_at' => $order->created_at,
                'updated_at' => $order->updated_at
            ]
        ], 201);
    }
    
}
