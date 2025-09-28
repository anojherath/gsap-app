<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Harvest;
use App\Models\Paddy;
use App\Models\Field;
use App\Models\User;
use App\Models\SeedOrder;
use App\Models\FertilizerOrder;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class HarvestOrderController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        $search = $request->input('search');

        $harvestOrders = Harvest::whereHas('field', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->with(['paddy', 'field', 'buyer', 'fertilizerOrder']) // ✅ updated relation
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('paddy', fn($q2) => $q2->where('type', 'like', "%{$search}%"))
                      ->orWhereHas('field', fn($q3) => $q3->where('name', 'like', "%{$search}%"))
                      ->orWhereHas('buyer', fn($q4) => $q4->where('first_name', 'like', "%{$search}%")
                                                        ->orWhere('last_name', 'like', "%{$search}%"))
                      ->orWhereHas('fertilizerOrder', fn($q5) => $q5->where('type', 'like', "%{$search}%"))
                      ->orWhere('status', 'like', "%{$search}%");
                });
            })
            ->latest('creation_date')
            ->paginate(10);

        return view('farmer.harvest_orders.index', [
            'orders' => $harvestOrders,
            'search' => $search,
        ]);
    }

    public function rejected()
    {
        $userId = Auth::id();

        $orders = Harvest::whereHas('field', fn($query) => $query->where('user_id', $userId))
            ->where('status', 'Rejected')
            ->with(['buyer', 'paddy', 'field', 'fertilizerOrder'])
            ->latest('creation_date')
            ->paginate(10);

        return view('farmer.harvest_orders.rejected', [
            'orders' => $orders,
        ]);
    }

    public function create()
    {
        $userId = Auth::id();

        // ✅ confirmed seed orders
        $confirmedSeedOrders = SeedOrder::where('user_id', $userId)
            ->where('farmer_confirmed', true)
            ->pluck('paddy_id')
            ->unique();
        $paddies = Paddy::whereIn('id', $confirmedSeedOrders)->get();

        // ✅ confirmed fertilizer orders
        $confirmedFertilizerOrders = FertilizerOrder::where('user_id', $userId)
            ->where('farmer_confirmed', true)
            ->pluck('id') // use order IDs
            ->unique();
        $fertilizers = FertilizerOrder::whereIn('id', $confirmedFertilizerOrders)->get();

        // ✅ harvest buyers
        $harvestBuyers = User::whereHas('userType', fn($q) => $q->where('user_type', 'Harvest Buyer'))->get();

        return view('farmer.harvest_orders.create', compact('paddies', 'fertilizers', 'harvestBuyers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'paddy_id' => 'required|exists:paddy,id',
            'fertilizer_id' => 'required|exists:fertiliser_order,id', // ✅ updated
            'field_name' => 'required|string|max:255',
            'qty' => 'required|numeric|min:1',
            'buyer_id' => 'required|exists:users,id',
        ]);

        $userId = Auth::id();

        $field = Field::firstOrCreate(
            ['name' => $request->field_name, 'user_id' => $userId],
            ['size' => 0, 'address' => '', 'creation_date' => now()]
        );

        Harvest::create([
            'user_id' => $userId,
            'paddy_id' => $request->paddy_id,
            'fertilizer_id' => $request->fertilizer_id, // points to FertilizerOrder ID
            'field_id' => $field->id,
            'qty' => $request->qty,
            'buyer_id' => $request->buyer_id,
            'status' => 'Pending',
            'creation_date' => Carbon::now(),
        ]);

        return redirect()->route('farmer.harvest_orders.index')->with('success', 'Harvest order added successfully.');
    }

    public function edit($id)
    {
        $userId = Auth::id();

        $order = Harvest::where('id', $id)
            ->whereHas('field', fn($query) => $query->where('user_id', $userId))
            ->firstOrFail();

        $confirmedSeedOrders = SeedOrder::where('user_id', $userId)
            ->where('farmer_confirmed', true)
            ->pluck('paddy_id')
            ->unique();
        $paddies = Paddy::whereIn('id', $confirmedSeedOrders)->get();

        $confirmedFertilizerOrders = FertilizerOrder::where('user_id', $userId)
            ->where('farmer_confirmed', true)
            ->pluck('id')
            ->unique();
        $fertilizers = FertilizerOrder::whereIn('id', $confirmedFertilizerOrders)->get();

        $harvestBuyers = User::whereHas('userType', fn($q) => $q->where('user_type', 'Harvest Buyer'))->get();

        return view('farmer.harvest_orders.edit', compact('order', 'paddies', 'fertilizers', 'harvestBuyers'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'paddy_id' => 'required|exists:paddy,id',
            'fertilizer_id' => 'required|exists:fertiliser_order,id',
            'field_name' => 'required|string|max:255',
            'qty' => 'required|numeric|min:1',
            'buyer_id' => 'required|exists:users,id',
        ]);

        $userId = Auth::id();

        $order = Harvest::where('id', $id)
            ->whereHas('field', fn($query) => $query->where('user_id', $userId))
            ->firstOrFail();

        $field = Field::firstOrCreate(
            ['name' => $request->field_name, 'user_id' => $userId],
            ['size' => 0, 'address' => '', 'creation_date' => now()]
        );

        $order->update([
            'paddy_id' => $request->paddy_id,
            'fertilizer_id' => $request->fertilizer_id,
            'field_id' => $field->id,
            'qty' => $request->qty,
            'buyer_id' => $request->buyer_id,
            'status' => 'Pending',
            'creation_date' => Carbon::now(),
        ]);

        return redirect()->route('farmer.harvest_orders.rejected')->with('success', 'Harvest order updated and resubmitted successfully.');
    }

    public function destroy($id)
    {
        $userId = Auth::id();

        $order = Harvest::where('id', $id)
            ->whereHas('field', fn($query) => $query->where('user_id', $userId))
            ->firstOrFail();

        $order->delete();

        return redirect()->route('farmer.harvest_orders.rejected')->with('success', 'Harvest order deleted successfully.');
    }
}
