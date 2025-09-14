<?php

namespace App\Http\Controllers\HarvestBuyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class HarvestBuyerReportController extends Controller
{
    /**
     * Show customer report list (with pagination).
     */
    public function customersReport()
    {
        $results = DB::table('harvest as h')
            ->leftJoin('paddy as p', 'h.paddy_id', '=', 'p.id')
            ->leftJoin('seed_orders as so', 'p.id', '=', 'so.paddy_id')
            ->leftJoin('users as u', 'so.user_id', '=', 'u.id')
            ->leftJoin('fertiliser_order as fo', 'u.id', '=', 'fo.user_id')
            ->select(
                'h.id as id',
                'p.type as harvest_type',
                'h.creation_date as harvest_date',
                'u.address as origin',
                'so.creation_date as seed_provider_date',
                'fo.type as fertilizer_type',
                'fo.creation_date as fertilizer_applied_date'
            )
            ->orderBy('h.creation_date', 'desc')
            ->paginate(10);

        return view('harvest_buyer.reports.customers', compact('results'));
    }

    /**
     * Export customer report to Excel.
     */
    public function exportExcel()
    {
        return Excel::download(new \App\Exports\CustomerReportExport, 'customer_report.xlsx');
    }

    /**
     * Export customer report to PDF.
     */
    public function exportPDF()
    {
        $data = DB::table('harvest as h')
            ->leftJoin('paddy as p', 'h.paddy_id', '=', 'p.id')
            ->leftJoin('seed_orders as so', 'p.id', '=', 'so.paddy_id')
            ->leftJoin('users as u', 'so.user_id', '=', 'u.id')
            ->leftJoin('fertiliser_order as fo', 'u.id', '=', 'fo.user_id')
            ->select(
                'p.type as harvest_type',
                'h.creation_date as harvest_date',
                'u.address as origin',
                'so.creation_date as seed_provider_date',
                'fo.type as fertilizer_type',
                'fo.creation_date as fertilizer_applied_date'
            )
            ->orderBy('h.creation_date', 'desc')
            ->get();

        $pdf = Pdf::loadView('harvest_buyer.reports.exports.customer_report_pdf', ['data' => $data]);

        return $pdf->download('customer_report.pdf');
    }

    /**
     * Show customer detail page (QR code link).
     */
    public function customerDetails($id)
    {
        $data = DB::table('harvest as h')
            ->leftJoin('paddy as p', 'h.paddy_id', '=', 'p.id')
            ->leftJoin('seed_orders as so', 'p.id', '=', 'so.paddy_id')
            ->leftJoin('users as u', 'so.user_id', '=', 'u.id')
            ->leftJoin('fertiliser_order as fo', 'u.id', '=', 'fo.user_id')
            ->select(
                'h.id as id',
                'p.type as harvest_type',
                'h.creation_date as harvest_date',
                'u.address as origin',
                'so.creation_date as seed_provider_date',
                'fo.type as fertilizer_type',
                'fo.creation_date as fertilizer_applied_date'
            )
            ->where('h.id', $id)
            ->first();

        if (!$data) {
            abort(404, 'Customer record not found');
        }

        // Now passing as $data (matches your Blade)
        return view('harvest_buyer.reports.customer_details', compact('data'));
    }
}
