<?php

namespace App\Http\Controllers;

use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use App\Models\Staff;

class AdminController extends Controller
{
    public function settings()
    {
        // Fetch active categories
        $categories = ServiceCategory::all();

        // Fetch soft-deleted categories
        $archivedCategories = ServiceCategory::onlyTrashed()->get();

        // Fetch soft-deleted staff
        $archivedStaff = Staff::onlyTrashed()->get();

        // Fetch your salon settings row
        $settings = \App\Models\SalonSetting::firstOrCreate(['id' => 1]);

        return view('admin.settings', compact('categories', 'archivedCategories', 'settings', 'archivedStaff'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:service_categories,name']);

        ServiceCategory::create(['name' => $request->name]);

        return back()->with('success', 'New Category added!');
    }

    public function archiveCategory($id)
    {
        $category = ServiceCategory::findOrFail($id);

        // Archive it (sets deleted_at timestamp)
        $category->delete();

        return back()->with('success', 'Category moved to archive.');
    }
    public function restoreCategory($id)
    {
        // Find the item even if it's in the trash
        $category = \App\Models\ServiceCategory::withTrashed()->findOrFail($id);

        $category->restore(); // Removes the deleted_at timestamp

        return back()->with('success', '✨ ' . $category->name . ' has been restored to active services!');
    }
    public function updateOperational(Request $request)
    {
        $request->validate([
            'max_services' => 'required|integer|min:1',
            'opening_time' => 'required',
            'closing_time' => 'required'
        ]);

        $settings = \App\Models\SalonSetting::firstOrCreate(['id' => 1]);
        $settings->update([
            'max_services_per_appointment' => $request->max_services,
            'opening_time' => $request->opening_time,
            'closing_time' => $request->closing_time
        ]);
        return back()->with('success', 'Operational limits and hours updated!');
    }
    public function updateHours(Request $request)
    {
        $settings = \App\Models\SalonSetting::firstOrCreate(['id' => 1]);
        $settings->update([
            'opening_time' => $request->opening_time,
            'closing_time' => $request->closing_time
        ]);
        return back()->with('success', 'Business hours updated!');
    }
    // Add this to your existing AdminController.php

    /**
     * Update Salon Profile Info (Business Info Tab)
     */
    public function updateInfo(Request $request)
    {
        $settings = \App\Models\SalonSetting::firstOrCreate(['id' => 1]);

        $settings->update([
            'salon_name'     => $request->salon_name,
            'contact_number' => $request->contact_number,
            'email'          => $request->email,
            'address'        => $request->address,
        ]);

        return back()->with('success', 'Business information updated!');
    }

    /**
     * Add a Holiday (Days Off Tab)
     */
    public function addHoliday(Request $request)
    {
        // Find settings or create a default row
        $settings = \App\Models\SalonSetting::firstOrCreate(['id' => 1]);

        // Get existing holidays or start fresh
        $holidays = $settings->holidays ?? [];

        // Add the new date with partial-day support
        $holidays[$request->holiday_date] = [
            'name'     => $request->holiday_name,
            'full_day' => $request->has('is_full_day'), // Checkbox from your modal
            'start'    => $request->closed_from,        // "From" time input
            'end'      => $request->closed_until,       // "Until" time input
        ];

        $settings->holidays = $holidays;
        $settings->save();

        return back()->with('success', 'Closure period added successfully!');
    }

    /**
     * Remove a Holiday
     */
    // ARCHIVE A HOLIDAY
    public function archiveHoliday($date)
    {
        $settings = \App\Models\SalonSetting::first();
        $holidays = $settings->holidays ?? [];

        if (isset($holidays[$date])) {
            // Move the entry to an 'archived' key within the array
            $archived = $settings->archived_holidays ?? [];
            $archived[$date] = $holidays[$date];

            unset($holidays[$date]); // Remove from active

            $settings->holidays = $holidays;
            $settings->archived_holidays = $archived; // Make sure this is in your $fillable/$casts
            $settings->save();
        }

        return back()->with('success', 'Holiday moved to archive.');
    }

    // RESTORE A HOLIDAY
    public function restoreHoliday($date)
    {
        $settings = \App\Models\SalonSetting::first();
        $archived = $settings->archived_holidays ?? [];
        $active = $settings->holidays ?? [];

        if (isset($archived[$date])) {
            $active[$date] = $archived[$date];
            unset($archived[$date]);

            $settings->holidays = $active;
            $settings->archived_holidays = $archived;
            $settings->save();
        }

        return back()->with('success', 'Holiday restored!');
    }
    public function restoreStaff($id)
    {
        $staff = Staff::withTrashed()->findOrFail($id);
        $staff->restore();

        return back()->with('success', "✨ {$staff->name} has been restored!");
    }
}
