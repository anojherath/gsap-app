<?php

namespace App\Http\Controllers\HarvestBuyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Harvest;
use Illuminate\Support\Facades\Auth;

class HarvestOrderController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $orders = Harvest::where('buyer_id', $userId)
            ->with(['paddy', 'field', 'buyer', 'user', 'fertilizerOrder']) // ✅ added fertilizerOrder
            ->orderBy('creation_date', 'desc')
            ->paginate(10);

        return view('harvest_buyer.harvest_orders.index', compact('orders'));
    }

    public function accept($id)
    {
        $order = Harvest::findOrFail($id);

        if ($order->buyer_id != Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $order->status = 'Accepted';
        $order->save();

        return redirect()->route('harvest_buyer.orders.index')->with('success', 'Harvest order accepted.');
    }

    public function reject($id)
    {
        $order = Harvest::findOrFail($id);

        if ($order->buyer_id != Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $order->status = 'Rejected';
        $order->save();

        return redirect()->route('harvest_buyer.orders.index')->with('success', 'Harvest order rejected.');
    }
}
