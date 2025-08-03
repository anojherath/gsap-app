<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HarvestOrder;
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

        $orders = HarvestOrder::with(['harvestBuyer', 'paddy'])
            ->whereHas('field', fn($q) => $q->where('user_id', $userId))
            ->when($search, function ($query, $search) {
                $query->whereHas('harvestBuyer', fn($q) => $q->where('name', 'like', "%$search%"))
                      ->orWhereHas('paddy', fn($q) => $q->where('name', 'like', "%$search%"))
                      ->orWhere('qty', 'like', "%$search%")
                      ->orWhere('status', 'like', "%$search%");
            })
            ->latest()
            ->paginate(10);

        return view('farmer.harvest_orders.index', compact('orders'));
    }

    public function rejected()
    {
        $userId = Auth::id();

        $orders = HarvestOrder::with(['harvestBuyer', 'paddy', 'field'])
            ->where('status', 'Rejected')
            ->whereHas('field', fn($q) => $q->where('user_id', $userId))
            ->latest()
            ->paginate(10);

        return view('farmer.harvest_orders.rejected', compact('orders'));
    }

    public function create()
    {
        $paddies = Paddy::all();
        $buyers = User::whereHas('userType', fn($q) => $q->where('name', 'Harvest Buyer'))->get();
        $fields = Field::where('user_id', Auth::id())->get();

        return view('farmer.harvest_orders.create', compact('paddies', 'buyers', 'fields'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'paddy_id' => 'required|exists:paddy,id',
            'buyer_id' => 'required|exists:users,id',
            'qty' => 'required|numeric|min:1',
            'field_id' => 'required|exists:fields,id',
        ]);

        HarvestOrder::create([
            'paddy_id' => $request->paddy_id,
            'buyer_id' => $request->buyer_id,
            'qty' => $request->qty,
            'field_id' => $request->field_id,
            'creation_date' => Carbon::now(),
            'status' => 'Pending',
        ]);

        return redirect()->route('harvest_orders.index')->with('success', 'Harvest order created successfully.');
    }
}
