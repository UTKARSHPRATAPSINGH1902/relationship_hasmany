<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    // Fetch all customers with orders
    public function index(): JsonResponse
    {
        $customers = Customer::withCount('orders')
            ->with(['orders' => function ($query) {
                $query->orderBy('created_at', 'asc');
            }])
            ->get()
            ->map(function ($customer) {
                return [
                    'name' => $customer->name,
                    'phone' => $customer->phone,
                    'total_orders' => $customer->orders_count,
                    'first_order_date' => optional($customer->orders->first())->created_at,
                    'last_order_date' => optional($customer->orders->last())->created_at,
                ];
            });

        return response()->json(['customers' => $customers]);
    }

    // Create a customer and orders
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:customers,phone',
            'orders' => 'required|array', // Orders must be an array
        ]);

        // Create Customer
        $customer = Customer::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
        ]);

        // Create Orders
        foreach ($validated['orders'] as $order) {
            Order::create([
                'customer_id' => $customer->id,
            ]);
        }

        return response()->json([
            'message' => 'Customer and orders created successfully!',
            'customer' => $customer->load('orders'),
        ], 201);
    }
}
