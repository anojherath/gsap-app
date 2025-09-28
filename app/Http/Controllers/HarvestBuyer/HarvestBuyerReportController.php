<?php

namespace App\Http\Controllers\HarvestBuyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Harvest;
use App\Models\FertilizerOrder;
use App\Models\SeedOrder;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class HarvestBuyerReportController extends Controller
{
    /**
     * Show confirmed harvest orders with search, pagination, and latest seed/fertilizer dates
     */
    public function customersReport(Request $request)
    {
        $search = $request->input('search');

        $query = Harvest::with(['paddy', 'field', 'fertilizerOrder', 'user'])
            ->whereIn('status', ['confirmed', 'accepted']); // only confirmed

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereHas('paddy', fn($q) => $q->where('type', 'like', "%{$search}%"))
                  ->orWhereHas('field', fn($q) => $q->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('fertilizerOrder', fn($q) => $q->where('type', 'like', "%{$search}%"));
            });
        }

        $results = $query->orderBy('creation_date', 'desc')->paginate(10);

        // Attach latest fertilizer & seed dates
        foreach ($results as $order) {
            $farmerId = $order->user_id;
            $fertilizerType = optional($order->fertilizerOrder)->type;
            $paddyType = optional($order->paddy)->type;

            // Latest fertilizer date for this farmer + fertilizer
            $latestFertilizer = FertilizerOrder::where('user_id', $farmerId)
                ->where('type', $fertilizerType)
                ->whereNotNull('farmer_confirmed')
                ->orderBy('creation_date', 'desc')
                ->first();
            $order->latest_fertilizer_date = $latestFertilizer ? $latestFertilizer->creation_date : null;

            // Latest seed date for this farmer + paddy
            $latestSeed = SeedOrder::where('user_id', $farmerId)
                ->whereHas('paddy', fn($q) => $q->where('type', $paddyType))
                ->whereNotNull('farmer_confirmed')
                ->orderBy('creation_date', 'desc')
                ->first();
            $order->latest_seed_date = $latestSeed ? $latestSeed->creation_date : null;
        }

        return view('harvest_buyer.reports.customers', compact('results'));
    }

    /**
     * Export Excel
     */
    public function exportExcel()
    {
        $harvests = Harvest::with(['paddy', 'field', 'fertilizerOrder', 'user'])
            ->whereIn('status', ['confirmed', 'accepted'])
            ->get();

        foreach ($harvests as $order) {
            $farmerId = $order->user_id;
            $fertilizerType = optional($order->fertilizerOrder)->type;
            $paddyType = optional($order->paddy)->type;

            $latestFertilizer = FertilizerOrder::where('user_id', $farmerId)
                ->where('type', $fertilizerType)
                ->whereNotNull('farmer_confirmed')
                ->orderBy('creation_date', 'desc')
                ->first();
            $order->latest_fertilizer_date = $latestFertilizer ? $latestFertilizer->creation_date : null;

            $latestSeed = SeedOrder::where('user_id', $farmerId)
                ->whereHas('paddy', fn($q) => $q->where('type', $paddyType))
                ->whereNotNull('farmer_confirmed')
                ->orderBy('creation_date', 'desc')
                ->first();
            $order->latest_seed_date = $latestSeed ? $latestSeed->creation_date : null;
        }

        return Excel::download(new \App\Exports\HarvestBuyerReportExport($harvests), 'confirmed_harvests.xlsx');
    }

    /**
     * Export PDF
     */
    public function exportPDF()
    {
        $harvests = Harvest::with(['paddy', 'field', 'fertilizerOrder', 'user'])
            ->whereIn('status', ['confirmed', 'accepted'])
            ->get();

        foreach ($harvests as $order) {
            $farmerId = $order->user_id;
            $fertilizerType = optional($order->fertilizerOrder)->type;
            $paddyType = optional($order->paddy)->type;

            $latestFertilizer = FertilizerOrder::where('user_id', $farmerId)
                ->where('type', $fertilizerType)
                ->whereNotNull('farmer_confirmed')
                ->orderBy('creation_date', 'desc')
                ->first();
            $order->latest_fertilizer_date = $latestFertilizer ? $latestFertilizer->creation_date : null;

            $latestSeed = SeedOrder::where('user_id', $farmerId)
                ->whereHas('paddy', fn($q) => $q->where('type', $paddyType))
                ->whereNotNull('farmer_confirmed')
                ->orderBy('creation_date', 'desc')
                ->first();
            $order->latest_seed_date = $latestSeed ? $latestSeed->creation_date : null;
        }

        $pdf = PDF::loadView('harvest_buyer.reports.exports.customer_report_pdf', compact('harvests'));
        return $pdf->download('confirmed_harvests.pdf');
    }

    /**
     * Show individual harvest order details
     */
    public function customerDetails($id)
    {
        // Fetch the harvest order with relationships
        $order = Harvest::with(['paddy', 'field', 'fertilizerOrder', 'user'])->findOrFail($id);

        $farmerId = $order->user_id;
        $fertilizerType = optional($order->fertilizerOrder)->type;
        $paddyType = optional($order->paddy)->type;

        // Latest fertilizer date for this farmer + fertilizer
        $latestFertilizer = FertilizerOrder::where('user_id', $farmerId)
            ->where('type', $fertilizerType)
            ->whereNotNull('farmer_confirmed')
            ->orderBy('creation_date', 'desc')
            ->first();
        $latestFertilizerDate = $latestFertilizer ? $latestFertilizer->creation_date : null;

        // Latest seed date for this farmer + paddy
        $latestSeed = SeedOrder::where('user_id', $farmerId)
            ->whereHas('paddy', fn($q) => $q->where('type', $paddyType))
            ->whereNotNull('farmer_confirmed')
            ->orderBy('creation_date', 'desc')
            ->first();
        $latestSeedDate = $latestSeed ? $latestSeed->creation_date : null;

        // Prepare data object for blade
        $data = (object)[
            'harvest_type' => optional($order->paddy)->type ?? '-',
            'field' => optional($order->field)->name ?? '-',
            'harvest_date' => $order->creation_date,
            'seed_provider_date' => $latestSeedDate,
            'fertilizer_type' => $fertilizerType ?? '-',
            'fertilizer_applied_date' => $latestFertilizerDate,
            'origin' => $order->origin ?? '-',
        ];

        return view('harvest_buyer.reports.customer_details', compact('data'));
    }
}
