<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use Carbon\Carbon;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        // 1. Filter for Approved status and select distinct names
        $query = Appointment::select('first_name', 'last_name')
            ->where('approval_status', 'Approved') // Added this filter
            ->distinct();

        // 2. Handle Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        $customers = $query->orderBy('first_name', 'asc')->get();

        // 3. AJAX response (for live search)
        if ($request->ajax()) {
            $html = '';
            foreach ($customers as $customer) {
                $fullName = $customer->first_name . ' ' . $customer->last_name;
                // Get initials (First letter of First Name and First letter of Last Name)
                $initial = strtoupper(substr($customer->first_name, 0, 1) . substr($customer->last_name, 0, 1));

                $html .= "
                <tr onclick=\"showHistory('{$fullName}')\"
                    class='hover:bg-green-50/50 cursor-pointer transition group'>
                    <td class='py-5 px-6 flex items-center justify-between'>
                        <div class='flex items-center space-x-3'>
                            <div class='w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-[#81b281] font-bold text-xs uppercase'>
                                {$initial}
                            </div>
                            <span class='font-bold text-gray-700 uppercase tracking-tight group-hover:text-[#81b281]'>
                                {$fullName}
                            </span>
                        </div>
                        <div class='flex items-center space-x-2'>
                            <span class='text-xs font-bold text-gray-400 uppercase tracking-widest group-hover:text-[#81b281] group-hover:translate-x-1 transition-all'>View History</span>
                            <i data-lucide='chevron-right' class='w-4 h-4 text-gray-300 group-hover:text-[#81b281] group-hover:translate-x-1 transition-all'></i>
                        </div>
                    </td>
                </tr>";
            }
            return response($html);
        }

        return view('customers.index', compact('customers'));
    }

    public function getCustomerHistory($name)
    {
        $parts = explode(' ', trim($name));
        $firstName = $parts[0] ?? '';
        $lastName = count($parts) > 1 ? implode(' ', array_slice($parts, 1)) : '';

        // 4. Also filter history to only show Approved appointments
        $history = Appointment::where('first_name', $firstName)
            ->where('last_name', $lastName)
            ->where('approval_status', 'Approved') // Added this filter
            ->with('services')
            ->latest('appointment_at')
            ->get();

        $html = '';
        foreach ($history as $record) {
            // Check status for coloring (Paid/Unpaid etc)
            $statusClass = $record->status == 'Paid' ? 'text-green-600' : 'text-yellow-600';
            $date = Carbon::parse($record->appointment_at)->format('d M, Y');

            $totalPrice = $record->services->sum('price');

            $html .= "
            <div class='border-b border-gray-100 py-4'>
                <div class='flex justify-between items-start mb-1'>
                    <div class='flex flex-wrap gap-1'>";

            foreach ($record->services as $svc) {
                $html .= "<span class='font-bold text-gray-800 bg-gray-50 px-2 py-0.5 rounded border text-sm'>{$svc->name}</span>";
            }

            $html .= "
                    </div>
                    <span class='text-xs font-black uppercase {$statusClass}'>{$record->status}</span>
                </div>
                <div class='flex justify-between text-sm text-gray-500 italic mt-2'>
                    <span>{$date}</span>
                    <span class='font-bold text-gray-700'>Php " . number_format($totalPrice, 0) . "</span>
                </div>
            </div>";
        }

        return response($html ?: "<p class='text-gray-400 italic text-center py-8'>No approved appointments found.</p>");
    }
}
