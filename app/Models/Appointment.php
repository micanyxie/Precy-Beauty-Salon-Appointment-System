<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'customer_name', // Keep this in fillable for now
        'phone_number',
        'service_id',
        'staff_id',
        'appointment_at',
        'status',
        'reminder_sent',
        'approval_status',
        'appt_status',
        'completed_at'
    ];
    protected $casts = [
        'reminder_sent' => 'boolean',
        'appointment_at' => 'datetime', // Add this line
    ];
    public function services()
    {
        // This allows one appointment to have many services
        return $this->belongsToMany(Service::class, 'appointment_service');
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
