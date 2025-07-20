<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('dashboards.admin.dashboard'); // maybe a landing page
    }

    public function users()
    {
        // you can fetch user data here if needed
        return view('dashboards.admin.users');
    }

    public function reports()
    {
        // fetch reports data
        return view('dashboards.admin.reports');
    }

    public function notifications()
    {
        return view('dashboards.admin.notifications');
    }
}

