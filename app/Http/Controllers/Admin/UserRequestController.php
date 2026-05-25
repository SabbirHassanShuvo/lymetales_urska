<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserRequestController extends Controller
{
    public function index()
    {
        $users = User::where('role', '!=', 'admin')->orderBy('created_at', 'desc')->get();
        return view('admin.users.index', compact('users'));
    }

    public function approve(string $id)
    {
        $user = User::findOrFail($id);
        $user->update(['status' => 'approved']);
        return back()->with('success', 'User approved.');
    }

    public function reject(string $id)
    {
        $user = User::findOrFail($id);
        $user->update(['status' => 'rejected']);
        return back()->with('success', 'User rejected.');
    }

    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->only(['first_name', 'last_name', 'email', 'status', 'role']));
        return back()->with('success', 'User updated.');
    }

    public function destroy(string $id)
    {
        User::findOrFail($id)->delete();
        return back()->with('success', 'User deleted.');
    }
}
