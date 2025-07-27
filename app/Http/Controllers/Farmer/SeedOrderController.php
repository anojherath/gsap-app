<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\SeedOrder;
use Illuminate\Support\Facades\Auth;

class SeedOrderController extends Controller
{
    public function index()
    {
        $farmerId = Auth::id();

        $seedOrders = SeedOrder::with(['paddy', 'seedProvider'])
            ->where('user_id', $farmerId)
            ->latest()
            ->paginate(10);

        return view('farmer.seed_orders.index', compact('seedOrders'));
    }

    public function confirm($id)
    {
        $order = SeedOrder::where('user_id', Auth::id())->findOrFail($id);
        $order->farmer_confirmed = true;
        $order->save();

        return redirect()->back()->with('success', 'Order confirmed.');
    }

    public function reject($id)
    {
        $order = SeedOrder::where('user_id', Auth::id())->findOrFail($id);
        $order->farmer_confirmed = false;
        $order->save();

        return redirect()->back()->with('success', 'Order rejected.');
    }
}
