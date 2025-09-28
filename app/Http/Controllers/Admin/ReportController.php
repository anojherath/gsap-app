<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Harvest;
use App\Models\SeedOrder;
use App\Models\FertilizerOrder;
use App\Exports\UsersExport;
use App\Exports\CustomerReportExport;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class ReportController extends Controller
{
    /**
     * Show the main reports page with list of report options
     */
    public function index()
    {
        return view('admin.reports.index');
    }

    /**
     * Display the user report
     */
    public function userReport(Request $request)
    {
        $search = $request->input('search');

        $users = User::with('userType')
            ->when($search, function ($query, $search) {
                $query->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('nic', 'like', "%{$search}%")
                      ->orWhere('mobile_number', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.reports.user_report', compact('users', 'search'));
    }

    /**
     * Export the user report to Excel
     */
    public function exportExcel()
    {
        return Excel::download(new UsersExport, 'user_report.xlsx');
    }

    /**
     * Export the user report to PDF
     */
    public function exportPDF()
    {
        $users = User::with('userType')->orderBy('created_at', 'desc')->get();

        $pdf = PDF::loadView('admin.reports.user_report_pdf', compact('users'));
        return $pdf->download('user_report.pdf');
    }

    /**
     * Display the customer report list with search, pagination, and latest seed/fertilizer dates
     */
    public function customerReport(Request $request)
    {
        $search = $request->input('search');

        $query = Harvest::with(['paddy', 'field', 'fertilizerOrder', 'user'])
            ->whereIn('status', ['confirmed', 'accepted']);

        if ($search) {
            $query->where(function ($q) use ($search) {
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

        return view('admin.reports.customer_report', compact('results'));
    }

    /**
     * Show detailed info of a single customer report record
     */
    public function customerDetails($id)
    {
        $order = Harvest::with(['paddy', 'field', 'fertilizerOrder', 'user'])->findOrFail($id);

        $farmerId = $order->user_id;
        $fertilizerType = optional($order->fertilizerOrder)->type;
        $paddyType = optional($order->paddy)->type;

        $latestFertilizer = FertilizerOrder::where('user_id', $farmerId)
            ->where('type', $fertilizerType)
            ->whereNotNull('farmer_confirmed')
            ->orderBy('creation_date', 'desc')
            ->first();
        $latestFertilizerDate = $latestFertilizer ? $latestFertilizer->creation_date : null;

        $latestSeed = SeedOrder::where('user_id', $farmerId)
            ->whereHas('paddy', fn($q) => $q->where('type', $paddyType))
            ->whereNotNull('farmer_confirmed')
            ->orderBy('creation_date', 'desc')
            ->first();
        $latestSeedDate = $latestSeed ? $latestSeed->creation_date : null;

        $data = (object)[
            'harvest_type' => optional($order->paddy)->type ?? '-',
            'field' => optional($order->field)->name ?? '-',
            'harvest_date' => $order->creation_date,
            'seed_provider_date' => $latestSeedDate,
            'fertilizer_type' => $fertilizerType ?? '-',
            'fertilizer_applied_date' => $latestFertilizerDate,
            'origin' => $order->origin ?? '-',
        ];

        return view('admin.reports.customer_report_detail', compact('data'));
    }

    /**
     * Export the customer report to Excel
     */
    public function exportCustomerExcel()
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
     * Export the customer report to PDF
     */
    public function exportCustomerPDF()
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

        $pdf = PDF::loadView('admin.reports.customer_report_pdf', compact('harvests'));
        return $pdf->download('confirmed_harvests.pdf');
    }
}
