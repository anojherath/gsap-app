<?php

namespace App\Http\Controllers\HarvestBuyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Harvest;
use Illuminate\Support\Facades\Auth;

class HarvestOrderController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        $search = $request->input('search');

        $orders = Harvest::where('buyer_id', $userId)
            ->with(['paddy', 'field', 'buyer', 'user', 'fertilizerOrder'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('user', fn($q1) => $q1->where('first_name', 'like', "%{$search}%")
                                                     ->orWhere('last_name', 'like', "%{$search}%"))
                      ->orWhereHas('paddy', fn($q2) => $q2->where('type', 'like', "%{$search}%"))
                      ->orWhereHas('fertilizerOrder', fn($q3) => $q3->where('type', 'like', "%{$search}%"))
                      ->orWhereHas('field', fn($q4) => $q4->where('name', 'like', "%{$search}%"))
                      ->orWhere('qty', 'like', "%{$search}%");
                });
            })
            ->orderBy('creation_date', 'desc')
            ->paginate(10);

        return view('harvest_buyer.harvest_orders.index', compact('orders', 'search'));
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
