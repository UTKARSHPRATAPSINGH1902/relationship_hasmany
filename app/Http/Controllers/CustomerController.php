<?php
// filepath: app/Http/Controllers/CustomerController.php


namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    // Fetch all customers with orders and payment methods
    public function index(): JsonResponse
    {
        $customers = Customer::withCount('orders')
            ->with(['orders' => function ($query) {
                $query->orderBy('created_at', 'asc');
            }, 'paymentMethods'])
            ->get()
            ->map(function ($customer) {
                return [
                    'name' => $customer->name,
                    'phone' => $customer->phone,
                    'total_orders' => $customer->orders_count,
                    'first_order_date' => optional($customer->orders->first())->created_at,
                    'last_order_date' => optional($customer->orders->last())->created_at,
                    'payment_methods' => $customer->paymentMethods->pluck('title'),
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
        'payment_methods' => 'required|array', // Payment methods must be an array
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

    // Attach Payment Methods
    $customer->paymentMethods()->attach($validated['payment_methods']);

    // Load the orders and payment methods relationships
    $customer->load('orders', 'paymentMethods');

    return response()->json([
        'message' => 'Customer, orders, and payment methods created successfully!',
        'customer' => $customer,
    ], 201);
}
}