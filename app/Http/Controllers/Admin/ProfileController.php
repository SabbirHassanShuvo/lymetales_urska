<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Show the admin profile management page.
     */
    public function index()
    {
        $user = auth()->user();
        return view('admin.profile.index', compact('user'));
    }

    /**
     * Update the admin profile.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'email'      => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone_number' => 'nullable|string|max:30',
            'avatar'     => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'password'   => 'nullable|confirmed|min:8',
        ]);

        $data = [
            'first_name'   => $request->input('first_name'),
            'last_name'    => $request->input('last_name'),
            'email'        => $request->input('email'),
            'phone_number' => $request->input('phone_number'),
        ];

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists and is a local file
            if ($user->avatar && file_exists(public_path($user->avatar))) {
                @unlink(public_path($user->avatar));
            }

            $file = $request->file('avatar');
            $filename = 'avatar_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();

            if (!is_dir(public_path('uploads/avatars'))) {
                mkdir(public_path('uploads/avatars'), 0755, true);
            }

            $file->move(public_path('uploads/avatars'), $filename);
            $data['avatar'] = 'uploads/avatars/' . $filename;
        }

        // Handle password change
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->input('password'));
        }

        $user->update($data);

        return redirect()->route('admin.profile.index')->with('success', 'Profile updated successfully!');
    }
}
