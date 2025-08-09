<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Exports\UsersExport;
use App\Exports\CustomerReportExport;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
            ->get(); // no pagination for reports

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
     * Display the customer report based on your SQL logic
     */
    public function customerReport(Request $request)
    {
        // Paginate 10 per page
        $results = DB::table('harvest as h')
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
            ->paginate(10);

        return view('admin.reports.customer_report', compact('results'));
    }

    /**
     * Export the customer report to Excel
     */
    public function exportCustomerExcel()
    {
        return Excel::download(new CustomerReportExport, 'customer_report.xlsx');
    }

    /**
     * Export the customer report to PDF
     */
    public function exportCustomerPDF()
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
            ->get();

        $pdf = PDF::loadView('admin.reports.customer_report_pdf', compact('data'));
        return $pdf->download('customer_report.pdf');
    }
}
