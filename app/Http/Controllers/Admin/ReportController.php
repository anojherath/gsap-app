<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Exports\UsersExport;
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
}
