<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SeedOrder;
use App\Models\Paddy;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SeedOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = SeedOrder::with(['user', 'paddy'])
            ->whereHas('user', fn ($q) => $q->where('user_type_id', 2)); // Only farmers

        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn ($u) =>
                    $u->where('first_name', 'like', "%$search%")
                      ->orWhere('last_name', 'like', "%$search%")
                )
                ->orWhereHas('paddy', fn ($p) =>
                    $p->where('type', 'like', "%$search%")
                )
                ->orWhere('qty', 'like', "%$search%");
            });
        }

        $seedOrders = $query->latest()->paginate(10);
        return view('seed_provider.seed_orders.index', compact('seedOrders'));
    }

    public function rejected()
    {
        $rejectedOrders = SeedOrder::with(['user', 'paddy'])
            ->where('farmer_confirmed', 0)
            ->latest()
            ->paginate(10);

        return view('seed_provider.seed_orders.rejected', compact('rejectedOrders'));
    }

    public function create()
    {
        $farmers = User::where('user_type_id', 2)->get();
        $paddyTypes = Paddy::all();
        return view('seed_provider.seed_orders.create', compact('farmers', 'paddyTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'paddy_id' => 'required|exists:paddy,id',
            'qty' => 'required|integer|min:1',
        ]);

        SeedOrder::create([
            'user_id' => $request->user_id,
            'seed_provider_id' => Auth::id(), // ✅ Add logged-in seed provider ID
            'paddy_id' => $request->paddy_id,
            'qty' => $request->qty,
            'creation_date' => Carbon::now(),
            //'farmer_confirmed' => null,
        ]);

        return redirect()->route('seed_orders.index')->with('success', 'Order placed successfully.');
    }

    public function edit($id)
    {
        $order = SeedOrder::findOrFail($id);
        $farmers = User::where('user_type_id', 2)->get();
        $paddyTypes = Paddy::all();
        return view('seed_provider.seed_orders.edit', compact('order', 'farmers', 'paddyTypes'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'paddy_id' => 'required|exists:paddy,id',
            'qty' => 'required|integer|min:1',
        ]);

        $order = SeedOrder::findOrFail($id);
        $order->update([
            'user_id' => $request->user_id,
            'seed_provider_id' => Auth::id(), // ✅ Update seed provider ID
            'paddy_id' => $request->paddy_id,
            'qty' => $request->qty,
            'creation_date' => Carbon::now(),
            //'farmer_confirmed' => false,
        ]);

        return redirect()->route('seed_orders.rejected')->with('success', 'Order resent successfully.');
    }

    public function destroy($id)
    {
        $order = SeedOrder::findOrFail($id);
        $order->delete();

        return redirect()->route('seed_orders.rejected')->with('success', 'Order deleted successfully.');
    }
}
