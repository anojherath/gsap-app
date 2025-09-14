<?php

namespace App\Http\Controllers\SeedProvider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SeedOrder;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class SeedOrderReportController extends Controller
{
    // Show Seed Orders report
    public function index(Request $request)
    {
        $search = $request->input('search');

        $seedOrders = SeedOrder::with(['user', 'paddy'])
            ->when($search, function($query, $search) {
                $query->whereHas('user', function($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%");
                })
                ->orWhereHas('paddy', function($q) use ($search) {
                    $q->where('type', 'like', "%{$search}%");
                })
                ->orWhere('qty', 'like', "%{$search}%");
            })
            ->orderBy('creation_date', 'desc')
            ->paginate(10);

        return view('seed_provider.reports.seed_orders', compact('seedOrders'));
    }

    // Export Excel
    public function exportExcel(Request $request)
    {
        $search = $request->input('search');
        return Excel::download(new \App\Exports\SeedOrderExport($search), 'seed_orders.xlsx');
    }

    // Export PDF
    public function exportPDF(Request $request)
    {
        $search = $request->input('search');
        $seedOrders = SeedOrder::with(['user', 'paddy'])
            ->when($search, function($query, $search) {
                $query->whereHas('user', function($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%");
                })
                ->orWhereHas('paddy', function($q) use ($search) {
                    $q->where('type', 'like', "%{$search}%");
                })
                ->orWhere('qty', 'like', "%{$search}%");
            })
            ->orderBy('creation_date', 'desc')
            ->get();

        $pdf = Pdf::loadView('seed_provider.reports.exports.seed_orders_pdf', compact('seedOrders'));
        return $pdf->download('seed_orders.pdf');
    }
}
