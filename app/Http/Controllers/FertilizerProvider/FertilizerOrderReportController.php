<?php

namespace App\Http\Controllers\FertilizerProvider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FertilizerOrder;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class FertilizerOrderReportController extends Controller
{
    // Show Fertilizer Orders report
    public function index(Request $request)
    {
        $search = $request->input('search');

        $fertilizerOrders = FertilizerOrder::with('user')
            ->when($search, function($query, $search) {
                $query->whereHas('user', function($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%");
                })
                ->orWhere('type', 'like', "%{$search}%")
                ->orWhere('qty', 'like', "%{$search}%");
            })
            ->orderBy('creation_date', 'desc')
            ->paginate(10);

        return view('fertilizer_provider.reports.fertilizer_orders', compact('fertilizerOrders'));
    }

    // Export Excel
    public function exportExcel(Request $request)
    {
        $search = $request->input('search');
        return Excel::download(new \App\Exports\FertilizerOrderExport($search), 'fertilizer_orders.xlsx');
    }

    // Export PDF
    public function exportPDF(Request $request)
    {
        $search = $request->input('search');
        $fertilizerOrders = FertilizerOrder::with('user')
            ->when($search, function($query, $search) {
                $query->whereHas('user', function($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%");
                })
                ->orWhere('type', 'like', "%{$search}%")
                ->orWhere('qty', 'like', "%{$search}%");
            })
            ->orderBy('creation_date', 'desc')
            ->get();

        $pdf = Pdf::loadView('fertilizer_provider.reports.exports.fertilizer_orders_pdf', compact('fertilizerOrders'));
        return $pdf->download('fertilizer_orders.pdf');
    }
}
