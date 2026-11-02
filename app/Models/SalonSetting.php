<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalonSetting extends Model
{
    protected $casts = [
        'holidays' => 'array', // This lets you treat the JSON as a PHP array
        'archived_holidays' => 'array', // Add this
    ];

    protected $fillable = [
        'max_services_per_appointment',
        'opening_time',
        'closing_time',
        'holidays',
        'salon_name',
        'contact_number',
        'email',
        'address',
        'archived_holidays'
    ];
}
