@extends('layouts.partner')
@section('title', 'Partner Dashboard')
@section('content')
<div class="p-6">
    <h1 class="text-3xl font-bold mb-6">Welcome, {{ $partner->company_name }}</h1>
    
    <div class="grid md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-gray-500 text-sm mb-2">Total Orders</h3>
            <p class="text-3xl font-bold">{{ $stats['total_orders'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-gray-500 text-sm mb-2">Total Revenue</h3>
            <p class="text-3xl font-bold">${{ number_format($stats['total_revenue'], 2) }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-gray-500 text-sm mb-2">Total Commission</h3>
            <p class="text-3xl font-bold text-green-600">${{ number_format($stats['total_commission'], 2) }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-gray-500 text-sm mb-2">Pending Payout</h3>
            <p class="text-3xl font-bold">${{ number_format($stats['pending_payout'], 2) }}</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold mb-4">Recent Orders</h2>
        <table class="w-full">
            <thead>
                <tr class="border-b">
                    <th class="text-left py-2">Order #</th>
                    <th class="text-left py-2">Service</th>
                    <th class="text-left py-2">Price</th>
                    <th class="text-left py-2">Commission</th>
                    <th class="text-left py-2">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recent_orders as $order)
                <tr class="border-b">
                    <td class="py-2">{{ $order->order_number }}</td>
                    <td>{{ $order->service_type }}</td>
                    <td>${{ number_format($order->price, 2) }}</td>
                    <td class="text-green-600">${{ number_format($order->commission, 2) }}</td>
                    <td><span class="px-2 py-1 bg-{{ $order->status == 'completed' ? 'green' : 'yellow' }}-100 text-{{ $order->status == 'completed' ? 'green' : 'yellow' }}-800 rounded text-sm">{{ $order->status }}</span></td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-4 text-gray-500">No orders yet</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
