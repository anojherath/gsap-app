<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Harvest;
use App\Models\Paddy;
use App\Models\Field;
use App\Models\User;
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
            ->with(['paddy', 'field.fertilizerOrders.fertilizer', 'buyer'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('paddy', function ($q2) use ($search) {
                        $q2->where('type', 'like', "%{$search}%");
                    })
                    ->orWhereHas('field', function ($q3) use ($search) {
                        $q3->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('buyer', function ($q4) use ($search) {
                        $q4->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    })
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

        $orders = Harvest::whereHas('field', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->where('status', 'Rejected')
            ->with(['buyer', 'paddy', 'field'])
            ->latest('creation_date')
            ->paginate(10);

        return view('farmer.harvest_orders.rejected', [
            'orders' => $orders,
        ]);
    }

    public function create()
    {
        $paddies = Paddy::all();

        $harvestBuyers = User::whereHas('userType', function ($query) {
            $query->where('user_type', 'Harvest Buyer');
        })->get();

        return view('farmer.harvest_orders.create', compact('paddies', 'harvestBuyers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'paddy_id' => 'required|exists:paddy,id',
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
            'field_id' => $field->id,
            'qty' => $request->qty,
            'buyer_id' => $request->buyer_id,
            'status' => 'Pending',
            'creation_date' => Carbon::now(),
        ]);

        return redirect()->route('farmer.harvest_orders.index')->with('success', 'Harvest order added successfully.');
    }

    // -------------- New methods below -----------------

    public function edit($id)
    {
        $userId = Auth::id();

        $order = Harvest::where('id', $id)
            ->whereHas('field', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->firstOrFail();

        $paddies = Paddy::all();

        $harvestBuyers = User::whereHas('userType', function ($query) {
            $query->where('user_type', 'Harvest Buyer');
        })->get();

        return view('farmer.harvest_orders.edit', compact('order', 'paddies', 'harvestBuyers'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'paddy_id' => 'required|exists:paddy,id',
            'field_name' => 'required|string|max:255',
            'qty' => 'required|numeric|min:1',
            'buyer_id' => 'required|exists:users,id',
        ]);

        $userId = Auth::id();

        $order = Harvest::where('id', $id)
            ->whereHas('field', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->firstOrFail();

        // Update or create the field
        $field = Field::firstOrCreate(
            ['name' => $request->field_name, 'user_id' => $userId],
            ['size' => 0, 'address' => '', 'creation_date' => now()]
        );

        $order->update([
            'paddy_id' => $request->paddy_id,
            'field_id' => $field->id,
            'qty' => $request->qty,
            'buyer_id' => $request->buyer_id,
            'status' => 'Pending', // Reset status to Pending on update/resubmit
            'creation_date' => Carbon::now(),
        ]);

        return redirect()->route('farmer.harvest_orders.rejected')->with('success', 'Harvest order updated and resubmitted successfully.');
    }

    public function destroy($id)
    {
        $userId = Auth::id();

        $order = Harvest::where('id', $id)
            ->whereHas('field', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->firstOrFail();

        $order->delete();

        return redirect()->route('farmer.harvest_orders.rejected')->with('success', 'Harvest order deleted successfully.');
    }
}
