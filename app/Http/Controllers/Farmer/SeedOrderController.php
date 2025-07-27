<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\SeedOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SeedOrderController extends Controller
{
    public function index(Request $request)
    {
        $farmerId = Auth::id();

        // Start base query
        $query = SeedOrder::with(['paddy', 'seedProvider'])
            ->where('user_id', $farmerId)
            ->latest();

        // Apply search filter if provided
        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->whereHas('paddy', function ($p) use ($search) {
                        $p->where('type', 'like', "%{$search}%");
                    })
                    ->orWhereHas('seedProvider', function ($sp) use ($search) {
                        $sp->where('company_name', 'like', "%{$search}%")
                           ->orWhere('first_name', 'like', "%{$search}%")
                           ->orWhere('last_name', 'like', "%{$search}%");
                    })
                    ->orWhere('qty', 'like', "%{$search}%");
            });
        }

        // Paginate and return
        $seedOrders = $query->paginate(10);

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
