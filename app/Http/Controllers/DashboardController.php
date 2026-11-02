<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Staff;
use App\Models\Appointment;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today('Asia/Manila')->toDateString();
        // 1. Get the summary counts
        $serviceCount = Service::count();
        $staffCount = Staff::count();
        $customerCount = Appointment::whereDate('appointment_at', $today)
            ->distinct()
            ->count('phone_number'); // better to use phone for uniqueness
        $today = Carbon::today('Asia/Manila')->toDateString();

        $appointmentCount = Appointment::where('approval_status', 'Approved')
            ->whereDate('appointment_at', $today)
            ->count();
        $unpaidCount = Appointment::where('status', 'Unpaid')->count();

        // 2. Get today's date strictly (e.g., "2026-02-28")
        $today = Carbon::now('Asia/Manila')->format('Y-m-d');

        // 3. CALCULATE TODAY'S SALES
        // We get today's approved appointments and sum the price of all their services
        $todaySales = Appointment::where('appointment_at', 'like', $today . '%')
            ->where('status', 'Paid')
            ->with('services')
            ->get()
            ->sum(function ($appointment) {
                return $appointment->services->sum('price');
            });

        // 3. The safest SQLite query: Look for any appointment starting with today's date
        $appointments = Appointment::with(['services', 'staff'])
            ->where('approval_status', 'Approved')
            ->whereDate('appointment_at', $today)
            ->orderBy('appointment_at', 'asc')
            ->get();

        // 4. Send everything to the welcome dashboard
        return view('dashboard', compact(
            'serviceCount',
            'staffCount',
            'customerCount',
            'appointmentCount',
            'unpaidCount',
            'appointments',
            'todaySales'
        ));
    }
}
