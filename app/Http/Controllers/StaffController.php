<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;
// Save a new staff member
use Illuminate\Support\Facades\Hash;


class StaffController extends Controller
{
    // Show the list of staff
    public function index()
    {
        $staffMembers = Staff::all(); // ✅ automatically excludes archived
        return view('staff.index', compact('staffMembers'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Staff cannot create records.');
        }

        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'availability' => 'required|string'
        ]);

        // 1. Create USER (login account)
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'staff' // IMPORTANT
        ]);

        // 2. Create STAFF record
        Staff::create([
            'name' => $request->name,
            'availability' => $request->availability,
            'user_id' => $user->id // link them
        ]);

        return back()->with('success', 'Staff account created successfully!');
    }

    public function update(Request $request, Staff $staff)
    {
        // Add security here too!
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $staff->update($request->all());
        return redirect()->back()->with('success', 'Updated!');
    }

    public function archive($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $staff = Staff::findOrFail($id);
        $staff->delete(); // ✅ soft delete

        return back()->with('success', 'Staff archived successfully!');
    }
}
