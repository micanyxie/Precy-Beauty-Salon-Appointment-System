<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SendAppointmentReminders extends Command
{
    protected $signature = 'appointments:send-reminders';
    protected $description = 'Send SMS reminders to customers 12 hours before their appointment';
    public function handle()
    {
        // Look for anything happening within the next 12 hours
        $twelveHoursFromNow = Carbon::now()->addHours(12);

        // Get appointments that are due soon but haven't been reminded
        $appointments = Appointment::where('appointment_at', '<=', $twelveHoursFromNow)
            ->where('reminder_sent', false)
            ->get();

        foreach ($appointments as $app) {
            Log::info("SMS REMINDER AUTO-SENT TO " . $app->phone_number);

            $app->reminder_sent = true;
            $app->save();
        }
    }
}
