<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::withCount(['listings', 'purchases', 'sales'])
            ->when($request->search, fn($q) =>
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
            )
            ->when($request->filter === 'banned',
                fn($q) => $q->where('is_banned', true)
            )
            ->when($request->filter === 'verified',
                fn($q) => $q->where('is_verified', true)
            )
            ->latest()
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function ban(Request $request, User $user)
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'Cannot ban an admin.');
        }

        $user->update(['is_banned' => true]);
        return back()->with('success', $user->name . ' has been banned.');
    }

    public function unban(User $user)
    {
        $user->update(['is_banned' => false]);
        return back()->with('success', $user->name . ' has been unbanned.');
    }

    public function verify(User $user)
    {
        $user->update(['is_verified' => true]);
        return back()->with('success', $user->name . ' is now a verified seller.');
    }
}
