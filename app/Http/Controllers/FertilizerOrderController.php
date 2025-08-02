<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\FertilizerOrder;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class FertilizerOrderController extends Controller
{
    public function index(Request $request)
    {
        // Show fertilizer orders with eager loading 'user' (farmer)
        $query = FertilizerOrder::with('user')
            ->whereHas('user', fn ($q) => $q->where('user_type_id', 2)); // Only farmers

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn ($u) =>
                    $u->where('first_name', 'like', "%$search%")
                      ->orWhere('last_name', 'like', "%$search%")
                )
                ->orWhere('type', 'like', "%$search%")
                ->orWhere('qty', 'like', "%$search%");
            });
        }

        $fertilizerOrders = $query->latest('creation_date')->paginate(10);

        return view('fertilizer_provider.fertilizer_orders.index', compact('fertilizerOrders'));
    }

    public function rejected()
    {
        $rejectedOrders = FertilizerOrder::with('user')
            ->where('farmer_confirmed', false)
            ->latest('creation_date')
            ->paginate(10);

        return view('fertilizer_provider.fertilizer_orders.rejected', compact('rejectedOrders'));
    }

    public function create()
    {
        // Get farmers for dropdown (user_type_id = 2 assumed to be farmers)
        $farmers = User::where('user_type_id', 2)->get();

        return view('fertilizer_provider.fertilizer_orders.create', compact('farmers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|string|max:255',
            'qty' => 'required|integer|min:1',
        ]);

        FertilizerOrder::create([
            'user_id' => $request->user_id,
            'fertilizer_provider_id' => Auth::id(), // assuming you track which provider made order
            'type' => $request->type,
            'qty' => $request->qty,
            'creation_date' => Carbon::now(),
            'farmer_confirmed' => null,
        ]);

        return redirect()->route('fertilizer_orders.index')->with('success', 'Order created successfully.');
    }

    public function edit($id)
    {
        $order = FertilizerOrder::findOrFail($id);
        $farmers = User::where('user_type_id', 2)->get();

        return view('fertilizer_provider.fertilizer_orders.edit', compact('order', 'farmers'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|string|max:255',
            'qty' => 'required|integer|min:1',
        ]);

        $order = FertilizerOrder::findOrFail($id);

        $order->user_id = $request->user_id;
        $order->type = $request->type;
        $order->qty = $request->qty;
        $order->creation_date = now();  // optionally update creation_date on edit
        $order->farmer_confirmed = null; // reset confirmation on update
        $order->save();

        return redirect()->route('fertilizer_orders.rejected')->with('success', 'Order updated successfully.');
    }

    public function destroy($id)
    {
        $order = FertilizerOrder::findOrFail($id);
        $order->delete();

        return redirect()->route('fertilizer_orders.rejected')->with('success', 'Order deleted successfully.');
    }
}
