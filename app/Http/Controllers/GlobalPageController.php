<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class GlobalPageController extends Controller
{
    public function dashboard()
    {
        return Inertia::render('Dashboard/Index');
    }

    public function accounting()
    {
        return Inertia::render('Accounting/Index');
    }
    public function createAccounting()
    {
        return Inertia::render('Accounting/Create');
    }

    public function crm()
    {
        return Inertia::render('CRM/Index');
    }

    public function hr()
    {
        return Inertia::render('HR/Index');
    }

    public function projects()
    {
        return Inertia::render('Projects/Index');
    }

    public function profile()
    {
        return Inertia::render('Profile/Index');
    }
}
