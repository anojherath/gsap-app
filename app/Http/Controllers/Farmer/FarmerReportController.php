<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Harvest;
use App\Models\SeedOrder;
use App\Models\FertilizerOrder;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\HarvestOrdersExport;
use App\Exports\SeedOrdersExport;
use App\Exports\FertilizerOrdersExport;
use PDF;

class FarmerReportController extends Controller
{
    /**
     * Reports index page with 3 buttons
     */
    public function index()
    {
        return view('farmer.reports.index');
    }

    /**
     * Harvest Orders with search & pagination
     */
    public function harvestOrders(Request $request)
    {
        $farmerId = Auth::id();
        $query = Harvest::where('user_id', $farmerId);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('paddy', fn($p) => $p->where('type', 'like', "%{$search}%"))
                  ->orWhereHas('buyer', fn($b) =>
                        $b->where('first_name', 'like', "%{$search}%")
                          ->orWhere('last_name', 'like', "%{$search}%"))
                  ->orWhere('status', 'like', "%{$search}%")
                  ->orWhere('qty', 'like', "%{$search}%")
                  ->orWhere('creation_date', 'like', "%{$search}%");
            });
        }

        $orders = $query->orderBy('creation_date', 'desc')->paginate(10);

        return view('farmer.reports.harvest_orders', compact('orders'));
    }

    public function exportHarvestOrdersPdf(Request $request)
    {
        $farmerId = Auth::id();
        $query = Harvest::where('user_id', $farmerId);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('paddy', fn($p) => $p->where('type', 'like', "%{$search}%"))
                  ->orWhereHas('buyer', fn($b) =>
                        $b->where('first_name', 'like', "%{$search}%")
                          ->orWhere('last_name', 'like', "%{$search}%"))
                  ->orWhere('status', 'like', "%{$search}%")
                  ->orWhere('qty', 'like', "%{$search}%")
                  ->orWhere('creation_date', 'like', "%{$search}%");
            });
        }

        $orders = $query->orderBy('creation_date', 'desc')->get();
        $pdf = PDF::loadView('farmer.reports.exports.harvest_orders_pdf', compact('orders'));
        return $pdf->download('harvest_orders_report.pdf');
    }

    public function exportHarvestOrdersExcel(Request $request)
    {
        $farmerId = Auth::id();
        $search = $request->search;
        return Excel::download(new HarvestOrdersExport($farmerId, $search), 'harvest_orders_report.xlsx');
    }

    /**
     * Seed Orders with search & pagination
     */
    public function seedOrders(Request $request)
    {
        $farmerId = Auth::id();
        $query = SeedOrder::with(['seedProvider', 'paddy'])->where('user_id', $farmerId);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('paddy', fn($p) => $p->where('type', 'like', "%{$search}%"))
                  ->orWhereHas('seedProvider', fn($sp) =>
                        $sp->where('company_name', 'like', "%{$search}%"))
                  ->orWhere('qty', 'like', "%{$search}%")
                  ->orWhere('creation_date', 'like', "%{$search}%");
            });
        }

        $seedOrders = $query->orderBy('creation_date', 'desc')->paginate(10);
        return view('farmer.reports.seed_orders', compact('seedOrders'));
    }

    public function exportSeedOrdersPdf(Request $request)
    {
        $farmerId = Auth::id();
        $query = SeedOrder::with(['seedProvider', 'paddy'])->where('user_id', $farmerId);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('paddy', fn($p) => $p->where('type', 'like', "%{$search}%"))
                  ->orWhereHas('seedProvider', fn($sp) =>
                        $sp->where('company_name', 'like', "%{$search}%"))
                  ->orWhere('qty', 'like', "%{$search}%")
                  ->orWhere('creation_date', 'like', "%{$search}%");
            });
        }

        $seedOrders = $query->orderBy('creation_date', 'desc')->get();
        $pdf = PDF::loadView('farmer.reports.exports.seed_orders_pdf', compact('seedOrders'));
        return $pdf->download('seed_orders_report.pdf');
    }

    public function exportSeedOrdersExcel(Request $request)
    {
        $farmerId = Auth::id();
        $search = $request->search;
        return Excel::download(new SeedOrdersExport($farmerId, $search), 'seed_orders_report.xlsx');
    }

    /**
     * Fertilizer Orders with search & pagination
     */
    public function fertilizerOrders(Request $request)
    {
        $farmerId = Auth::id();
        $query = FertilizerOrder::with('user')->where('user_id', $farmerId);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn($u) =>
                        $u->where('company_name', 'like', "%{$search}%"))
                  ->orWhere('type', 'like', "%{$search}%")
                  ->orWhere('qty', 'like', "%{$search}%")
                  ->orWhere('creation_date', 'like', "%{$search}%");
            });
        }

        $fertilizerOrders = $query->orderBy('creation_date', 'desc')->paginate(10);
        return view('farmer.reports.fertilizer_orders', compact('fertilizerOrders'));
    }

    public function exportFertilizerOrdersPdf(Request $request)
    {
        $farmerId = Auth::id();
        $query = FertilizerOrder::with('user')->where('user_id', $farmerId);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn($u) =>
                        $u->where('company_name', 'like', "%{$search}%"))
                  ->orWhere('type', 'like', "%{$search}%")
                  ->orWhere('qty', 'like', "%{$search}%")
                  ->orWhere('creation_date', 'like', "%{$search}%");
            });
        }

        $fertilizerOrders = $query->orderBy('creation_date', 'desc')->get();
        $pdf = PDF::loadView('farmer.reports.exports.fertilizer_orders_pdf', compact('fertilizerOrders'));
        return $pdf->download('fertilizer_orders_report.pdf');
    }

    public function exportFertilizerOrdersExcel(Request $request)
    {
        $farmerId = Auth::id();
        $search = $request->search;
        return Excel::download(new FertilizerOrdersExport($farmerId, $search), 'fertilizer_orders_report.xlsx');
    }
}
