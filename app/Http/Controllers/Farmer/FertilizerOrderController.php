<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\FertilizerOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FertilizerOrderController extends Controller
{
    public function index(Request $request)
    {
        $farmerId = Auth::id();

        // Base query with eager loading of related user (fertilizer provider)
        $query = FertilizerOrder::with(['user'])  // Assuming 'user' is the fertilizer provider
            ->where('user_id', $farmerId)
            ->orderBy('creation_date', 'desc');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->where('type', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('company_name', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                  })
                  ->orWhere('qty', 'like', "%{$search}%");
            });
        }

        $fertilizerOrders = $query->paginate(10);

        return view('farmer.fertilizer_orders.index', compact('fertilizerOrders'));
    }

    public function confirm($id)
    {
        $order = FertilizerOrder::where('user_id', Auth::id())->findOrFail($id);
        $order->farmer_confirmed = true;
        $order->save();

        return redirect()->back()->with('success', 'Order confirmed.');
    }

    public function reject($id)
    {
        $order = FertilizerOrder::where('user_id', Auth::id())->findOrFail($id);
        $order->farmer_confirmed = false;
        $order->save();

        return redirect()->back()->with('success', 'Order rejected.');
    }
}
