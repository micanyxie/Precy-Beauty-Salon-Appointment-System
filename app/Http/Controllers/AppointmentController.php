<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Service;
use App\Models\Staff;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $settings = \App\Models\SalonSetting::first();

        $services = Service::all();
        $staffMembers = Staff::all();

        // 1. CALENDAR NAVIGATION LOGIC
        // Read the date from the URL (e.g. ?date=2026-03). If none exists, use Today.
        $baseDate = $request->has('date')
            ? \Carbon\Carbon::parse($request->date, 'Asia/Manila')
            : \Carbon\Carbon::now('Asia/Manila');

        // Calculate the "Previous" and "Next" month strings for your buttons
        $prevMonth = $baseDate->copy()->subMonth()->format('Y-m');
        $nextMonth = $baseDate->copy()->addMonth()->format('Y-m');

        // Define the start and end of this specific month
        $startOfMonth = $baseDate->copy()->startOfMonth();
        $endOfMonth = $baseDate->copy()->endOfMonth();

        // Fetch ONLY the appointments for the chosen month
        $appointments = Appointment::with(['services', 'staff'])
            ->where('approval_status', 'Approved')
            ->where('appointment_at', '>=', $startOfMonth->format('Y-m-d 00:00:00'))
            ->where('appointment_at', '<=', $endOfMonth->format('Y-m-d 23:59:59'))
            ->orderBy('appointment_at', 'asc')
            ->get();

        // Get today's date
        $today = Carbon::today('Asia/Manila')->toDateString();

        // Fetch only approved appointments for today
        $todayAppointments = Appointment::with(['services', 'staff'])
            ->where('approval_status', 'Approved')
            ->whereDate('appointment_at', $today)
            ->orderBy('appointment_at', 'asc')
            ->get();

        $settings = \App\Models\SalonSetting::first() ?? (object)['opening_time' => '09:00', 'closing_time' => '18:00'];

        // DYNAMIC SLOTS based on Admin Business Hours
        $slots = [];
        $start = $settings ? strtotime($settings->opening_time) : strtotime('09:00');
        $end = $settings ? strtotime($settings->closing_time) : strtotime('18:00');

        for ($i = $start; $i < $end; $i += (30 * 60)) {
            $slots[] = date('H:i', $i);
        }

        // Identify busy times (including the duration stretch)
        $busyTimes = [];
        foreach ($appointments as $app) {
            $appStartTime = strtotime($app->appointment_at);
            $duration = $app->services->sum('duration') ?: 30;

            for ($j = 0; $j < $duration; $j += 30) {
                $busyTimes[] = date('H:i', $appStartTime + ($j * 60));
            }
        }

        // 3. SEND EVERYTHING TO THE VIEW
        return view('calendar.index', compact(
            'services',
            'staffMembers',
            'appointments',
            'slots',
            'busyTimes',
            'baseDate',
            'prevMonth',
            'nextMonth',
            'settings',
            'todayAppointments'
        ));
    }

    public function store(Request $request)
    {
        $settings = \App\Models\SalonSetting::first();
        $minDate = \Carbon\Carbon::now('Asia/Manila')->addDay()->startOfDay();

        // 1. VALIDATION
        $request->validate([
            'first_name'       => 'required|string|max:100',
            'last_name'        => 'required|string|max:100',
            'phone_number'     => 'required|digits:11',
            'service_ids'      => 'required|array|min:1',
            'staff_id'         => 'required|exists:staff,id',
            'appointment_time' => 'required',
            'appointment_date' => [
                'required',
                'date',
                'after_or_equal:' . $minDate->toDateString(),
            ],
        ], [
            'appointment_date.after_or_equal' => 'Sorry, we only accept bookings at least 24 hours in advance.'
        ]);

        // 2. HOLIDAY CHECK
        if ($settings && $settings->holidays && isset($settings->holidays[$request->appointment_date])) {
            $holiday = $settings->holidays[$request->appointment_date];
            $holidayName = is_array($holiday) ? ($holiday['name'] ?? 'Holiday') : $holiday;
            $isFullDay = is_array($holiday) ? ($holiday['full_day'] ?? true) : true;

            if ($isFullDay) {
                return back()->withErrors(['appointment_date' => "Sorry, the salon is closed all day for: {$holidayName}"])->withInput();
            }

            if (is_array($holiday)) {
                $selectedTime = strtotime($request->appointment_time);
                $closedStart = strtotime($holiday['start']);
                $closedEnd = strtotime($holiday['end']);

                if ($selectedTime >= $closedStart && $selectedTime < $closedEnd) {
                    return back()->withErrors(['appointment_time' => "The salon is closed from {$holiday['start']} to {$holiday['end']} for {$holidayName}."])->withInput();
                }
            }
        }

        $fullName = trim($request->first_name . ' ' . $request->last_name);
        $requestedDateTime = $request->appointment_date . ' ' . $request->appointment_time;

        // 3. CHECK IF STAFF IS BUSY (DURATION-AWARE)
        $requestedStart = \Carbon\Carbon::parse($requestedDateTime);
        $requestedEnd = $requestedStart->copy()->addMinutes(
            Service::whereIn('id', $request->service_ids)->sum('duration')
        );

        $isTaken = Appointment::with('services')
            ->where('staff_id', $request->staff_id)
            ->whereIn('approval_status', ['Approved', 'Pending'])
            ->get()
            ->contains(function ($app) use ($requestedStart, $requestedEnd) {
                $start = \Carbon\Carbon::parse($app->appointment_at);
                $end = $start->copy()->addMinutes($app->services->sum('duration'));

                return $requestedStart < $end && $requestedEnd > $start;
            });

        if ($isTaken) {
            return back()->withErrors(['appointment_time' => 'Oops! That slot is already taken. Please choose another time.'])->withInput();
        }

        // 4. CREATE APPOINTMENT
        $appointment = Appointment::create([
            'first_name'      => $request->first_name,
            'last_name'       => $request->last_name,
            'phone_number'    => $request->phone_number,
            'service_id'      => $request->service_ids[0],
            'staff_id'        => $request->staff_id,
            'appointment_at'  => $requestedDateTime,
            'status'          => 'Unpaid',
            'approval_status' => 'Pending'
        ]);

        $appointment->services()->attach($request->service_ids);
        $this->sendMessage(
            $appointment->phone_number,
            "Hello {$appointment->first_name}, your booking is pending."
        );

        // 5. CONSTRUCT SMS MESSAGE
        $readableDate = \Carbon\Carbon::parse($appointment->appointment_at)
            ->format('M d, Y \a\t h:i A');

        $serviceNames = $appointment->services->pluck('name')->toArray();
        $serviceList = implode(', ', $serviceNames);

        $message = "Hi {$appointment->first_name}! 🎉\n";
        $message .= "Your appointment at Precy Beauty Salon is confirmed.\n";
        $message .= "Date & Time: {$readableDate}\n";
        $message .= "Services: {$serviceList}\n";
        $message .= "Staff: {$appointment->staff->name}\n";
        $message .= "Please arrive 10 minutes early. See you soon!";

        // 7. SEND SMS via PhilSMS API
        // Just pass the raw number from the database.
        // The sendMessage function below will clean it up properly.
        return redirect('/')->with('booking_success', "Thank you, {$request->first_name}! Wait for the booking confirmation booking.");
    }

    public function sendMessage($phone_number, $message)
    {
        // Clean the number
        $phone_number = preg_replace('/[^0-9]/', '', $phone_number);
        if (str_starts_with($phone_number, '0')) {
            $phone_number = '63' . substr($phone_number, 1);
        } elseif (str_starts_with($phone_number, '9')) {
            $phone_number = '63' . $phone_number;
        }

        $url = "https://dashboard.philsms.com/api/v3/sms/send";
        $token = env('PHILSMS_API_TOKEN');
        $senderId = env('PHILSMS_SENDER_ID', 'PhilSMS');

        $payload = [
            'recipient' => $phone_number,
            'sender_id' => $senderId,
            'type'      => 'plain',
            'message'   => $message,
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
            'Accept: application/json',
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            Log::error('PhilSMS CURL Error: ' . curl_error($ch));
        }

        Log::info('PhilSMS API Response: ' . $response);
        curl_close($ch);

        return $response;
    }

    public function customerCreate(Request $request)
    {
        $settings = \App\Models\SalonSetting::first();
        // 1. Group services by their category column
        $servicesByCategory = \App\Models\Service::with('category')
            ->get()
            ->groupBy(function ($service) {
                // This ensures we get 'Hair' instead of the ID or the whole object
                return $service->category->name ?? 'Uncategorized';
            });

        $staffMembers = Staff::all();
        $selectedDate = $request->date ?? today()->format('Y-m-d');

        // ... (Keep your Business Hours $slots logic exactly the same) ...
        // UPDATE SLOTS TO BE DYNAMIC
        $start = $settings ? strtotime($settings->opening_time) : strtotime('09:00');
        $end = $settings ? strtotime($settings->closing_time) : strtotime('18:00');
        $slots = [];
        for ($i = $start; $i < $end; $i += (30 * 60)) {
            $slots[] = date('H:i', $i);
        }

        // ... (Keep your $busyTimes logic exactly the same) ...
        $bookedAppointments = Appointment::where('staff_id', $request->staff_id)
            ->whereDate('appointment_at', $selectedDate)
            ->get();

        $busyTimes = [];
        foreach ($bookedAppointments as $app) {
            $appStartTime = strtotime($app->appointment_at);
            $duration = $app->services->sum('duration') ?: 30;
            for ($j = 0; $j < $duration; $j += 30) {
                $busyTimes[] = date('H:i', $appStartTime + ($j * 60));
            }
        }

        // Pass 'servicesByCategory' instead of 'services'
        return view('customer_booking', compact(
            'servicesByCategory',
            'staffMembers',
            'slots',
            'busyTimes',
            'settings' // <--- Add this
        ));
    }
    public function getBusySlots(Request $request)
    {
        $date = $request->date;
        $staffId = $request->staff_id;
        $settings = \App\Models\SalonSetting::first();
        $busyTimes = [];

        if ($settings && $settings->holidays && isset($settings->holidays[$date])) {
            $holiday = $settings->holidays[$date];
            $isFullDay = is_array($holiday) ? ($holiday['full_day'] ?? true) : true;

            if ($isFullDay) {
                // If full day, return a special flag so frontend can disable the whole day
                return response()->json([
                    'isHoliday' => true,
                    'reason' => is_array($holiday) ? $holiday['name'] : $holiday
                ]);
            } else {
                // If partial day, add the closed hours to the busyTimes array
                $start = strtotime($holiday['start']);
                $end = strtotime($holiday['end']);
                for ($i = $start; $i < $end; $i += (30 * 60)) {
                    $busyTimes[] = date('H:i', $i);
                }
            }
        }

        // Fetch existing appointments as usual
        $bookedAppointments = Appointment::with('services')
            ->where('staff_id', $staffId)
            ->whereDate('appointment_at', $date)
            ->whereIn('approval_status', ['Approved', 'Pending']) // CRITICAL FIX
            ->get();

        foreach ($bookedAppointments as $app) {
            $startTime = \Carbon\Carbon::parse($app->appointment_at);
            $totalDuration = $app->services->sum('duration') ?: 30;
            for ($i = 0; $i < $totalDuration; $i += 30) {
                $busyTimes[] = $startTime->copy()->addMinutes($i)->format('H:i');
            }
        }

        return response()->json(array_values(array_unique($busyTimes)));
    }
    public function paymentList(Request $request)
    {
        // 1. Get all appointments with their services for the main table
        $query = Appointment::with('services')
            ->where('approval_status', 'Approved');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        $appointments = $query->orderBy('appointment_at', 'desc')->get();

        // 2. AJAX response for the Search/Table update
        if ($request->ajax()) {
            $html = '';
            foreach ($appointments as $app) {
                // Check 'status' column for Payment badge
                $statusClass = $app->status == 'Paid' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700';

                $formattedDate = \Carbon\Carbon::parse($app->appointment_at)->format('d M, Y');
                $totalPrice = $app->services->sum('price');
                $price = number_format($totalPrice, 0);
                $fullName = "{$app->first_name} {$app->last_name}";

                $html .= "
            <tr class='hover:bg-green-50/50 transition duration-150'>
                <td class='py-4 px-6 font-bold text-gray-700 uppercase'>{$fullName}</td>
                <td class='py-4 px-6'>
                    <span class='px-3 py-1 rounded-full text-[11px] font-black uppercase shadow-sm {$statusClass}'>
                        {$app->status}
                    </span>
                    <div class='text-[9px] mt-1 text-gray-400 font-bold uppercase'>Booking: {$app->approval_status}</div>
                </td>
                <td class='py-4 px-6 text-gray-500 text-sm font-medium italic'>Payment</td>
                <td class='py-4 px-6 text-gray-600 text-sm font-bold'>" . ($app->payment_method ?? 'Cash') . "</td>
                <td class='py-4 px-6 font-black text-[#81b281] text-lg'>Php {$price}</td>
                <td class='py-4 px-6 text-gray-500 text-sm font-bold italic'>{$formattedDate}</td>
                <td class='py-4 px-6 text-right'>
                    <div class='flex items-center justify-end space-x-2'>";

                if ($app->status !== 'Paid') {
                    $html .= "
                <button type='button'
                        onclick=\"openConfirmModal(event, {$app->id}, '{$fullName}')\"
                        class='flex items-center space-x-1 bg-green-500 text-white px-3 py-1 rounded-md text-xs font-bold hover:bg-green-600 transition shadow-sm'>
                    <i data-lucide='check' class='w-3 h-3'></i>
                    <span>Pay</span>
                </button>";
                } else {
                    $html .= "
                <span class='text-gray-400 text-[10px] font-bold uppercase flex items-center'>
                    <i data-lucide='check-circle-2' class='w-3 h-3 mr-1 text-green-500'></i>
                    Settled
                </span>";
                }
                $html .= "</div></td></tr>";
            }
            return response($html);
        }

        // 3. For the "Record Payment" Modal Dropdown:
        // Only show people who are APPROVED but haven't PAID yet.
        $unpaidAppointments = Appointment::where('status', 'Unpaid')
            ->where('approval_status', 'Approved')
            ->with('services')
            ->get();

        return view('payment.index', compact('appointments', 'unpaidAppointments'));
    }

    public function markAsPaid($id)
    {
        // Find the appointment or fail with a 404
        $appointment = Appointment::findOrFail($id);

        // Update the status
        $appointment->update([
            'status' => 'Paid',
            // If you have a paid_at column, it's good practice to log the time
            // 'paid_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Appointment marked as paid successfully!');
    }
    // 1. This shows the search form (find.blade.php)
    public function findPage()
    {
        return view('appointment.find');
    }

    // 2. This searches the SQLite database for the customer's phone number
    public function search(Request $request)
    {
        $phone = $request->phone;

        // We use the Database Layer to find records matching the phone
        $appointments = Appointment::where('phone_number', $phone)
            ->with('services') // This is critical for the service name to show
            ->get();

        if ($appointments->isEmpty()) {
            return back()->with('error', 'No appointments found.');
        }

        return view('appointment.my_list', compact('appointments', 'phone'));
    }
    public function showByPhone($phone)
    {
        // Find appointments by phone number
        $appointments = Appointment::where('phone_number', $phone)
            ->with('services')
            ->orderBy('appointment_at', 'asc')
            ->get();

        // If no appointments, send them back to search
        if ($appointments->isEmpty()) {
            return redirect()->route('appointment.find')->with('error', 'No appointments found.');
        }

        // Return the list view (make sure the view name matches your file: appointment.my_list)
        return view('appointment.my_list', compact('appointments', 'phone'));
    }
    public function edit($id)
    {
        $appointment = Appointment::findOrFail($id);
        $services = Service::all();
        // This sends the customer to a new reschedule form
        return view('appointment.edit', compact('appointment', 'services'));
    }
    // Update logic
    public function update(Request $request, $id)
    {
        // Define the strict cutoff (Start of tomorrow in Manila)
        $minDate = \Carbon\Carbon::now('Asia/Manila')->addDay()->startOfDay();
        // Get the start of tomorrow (midnight)
        $request->validate([
            'appointment_date' => [
                'required',
                'date',
                // This forces the date to be tomorrow or later
                'after_or_equal:' . $minDate->toDateString(),
            ],
            'appointment_time' => 'required',
        ], [
            'appointment_date.after_or_equal' => 'Same-day rescheduling is not allowed. Please select a date from tomorrow onwards.'
        ]);
        $appointment = Appointment::findOrFail($id);

        $appointment->update([
            'appointment_at' => $request->appointment_date . ' ' . $request->appointment_time,
        ]);

        // --- LOG RESCHEDULE MESSAGE ---
        $formattedTime = date('M d, Y h:i A', strtotime($appointment->appointment_at));
        $message = "Hi {$appointment->first_name} {$appointment->last_name}, your appointment for {$appointment->services->pluck('name')->join(', ')} has been RESCHEDULED to {$formattedTime}. - Precy Beauty Salon";

        $this->sendMessage($appointment->phone_number, $message);

        // Change this line at the end of your update function:
        return redirect()->route('appointment.list', ['phone' => $appointment->phone_number])
            ->with('reschedule_success', "Thank you, {$appointment->first_name} {$appointment->last_name}! Your appointment is rescheduled.");
    }

    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);

        // Store data in variables BEFORE deleting the record
        $phone = $appointment->phone_number;
        $name = $appointment->first_name . ' ' . $appointment->last_name;
        $serviceName = $appointment->services->pluck('name')->join(', ');
        $appointment->delete();

        // --- LOG CANCELLATION MESSAGE ---
        $message = "Hi {$name}, your appointment for {$serviceName} has been CANCELED. We hope to see you another time! - Precy Beauty Salon";

        $this->sendMessage($phone, $message);

        // Change this line at the end of your destroy function:
        return redirect()->route('appointment.list', ['phone' => $phone])
            ->with('cancel_success', "Thank you, {$name}! Your appointment is cancelled.");
    }

    // The Simulation Helper
    public function updateStatus($id, $approval_status)
    {
        $appointment = \App\Models\Appointment::findOrFail($id);

        // If the admin clicked the red 'X' (Declined)
        if (!in_array($approval_status, ['Approved', 'Declined'])) {
            return back()->with('error', 'Invalid status selection.');
        }

        // 3. ARCHIVE DON'T DELETE
        // We update the database, not delete, so it shows in the notification center
        $appointment->update([
            'approval_status' => $approval_status
        ]);

        // If the admin clicked the green check (Approved)
        if ($approval_status === 'Approved') {
            $appointment->update(['approval_status' => 'Approved']);

            $this->sendMessage($appointment->phone_number, "Hello {$appointment->first_name}, your booking is confirmed!");

            return back()->with('success', "Appointment has been approved.");
        } else if ($approval_status === 'Declined') {
            $this->sendMessage($appointment->phone_number, "Hello {$appointment->first_name}, your booking has been declined. Please contact us for more details.");
        }

        return back()->with('error', 'Invalid status action.');
    }
    public function showReservations()
    {
        // Fetch only 'Unpaid' (Pending) appointments and load their services
        $appointments = Appointment::with('services')
            ->where('approval_status', 'Pending') // Only show pending requests
            ->latest()
            ->get();

        // Make sure the view path matches your folder structure
        return view('admin.appt_approve', compact('appointments'));
    }
    public function complete($id)
    {
        $appointment = Appointment::findOrFail($id);
        $now = now(); // Get current time

        $appointment->update([
            'appt_status' => 'Completed',
            'completed_at' => $now,
        ]);

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Appointment confirmed as completed!',
                // Send the time back to the browser
                'completed_time' => $now->format('g:i A')
            ]);
        }

        return redirect()->back()->with('success', 'Appointment completed!');
    }
    public function todaysApprovedAppointments()
    {
        $today = Carbon::today('Asia/Manila')->toDateString();

        // Fetch only appointments that are approved and scheduled for today
        $appointments = Appointment::with(['services', 'staff'])
            ->where('approval_status', 'Approved')
            ->whereDate('appointment_at', $today)
            ->orderBy('appointment_at', 'asc')
            ->get();

        // You can use the same Blade you already have, e.g., 'calendar.index'
        return view('calendar.index', compact('appointments'));
    }
}
