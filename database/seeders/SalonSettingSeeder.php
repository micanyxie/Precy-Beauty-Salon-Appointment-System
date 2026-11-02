<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SalonSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\SalonSetting::updateOrCreate(
            ['id' => 1], // Ensures we only ever have one row of settings
            [
                'max_services_per_day' => 2,
                'opening_time' => '08:00',
                'closing_time' => '18:00',
                'holidays' => [
                    '2026-03-12' => 'Salon Anniversary / Staff Outing'
                ]
            ]
        );
    }
}
