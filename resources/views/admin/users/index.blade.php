@extends('layouts.admin')
@section('title', 'Users')

@section('content')

    <div class="flex items-center justify-between mb-5">
        <h1 class="text-2xl font-bold">👥 Users</h1>
        <form method="GET" class="flex gap-2">
            <input type="text" name="search"
                   value="{{ request('search') }}"
                   placeholder="Search name or email..."
                   class="bg-gray-800 border border-gray-700 rounded-xl px-3 py-1.5
                          text-sm text-white focus:outline-none focus:border-indigo-500 w-56">
            <select name="filter"
                    class="bg-gray-800 border border-gray-700 rounded-xl px-3 py-1.5
                           text-sm text-white focus:outline-none">
                <option value="">All Users</option>
                <option value="banned"
                    {{ request('filter') === 'banned' ? 'selected' : '' }}>
                    Banned
                </option>
                <option value="verified"
                    {{ request('filter') === 'verified' ? 'selected' : '' }}>
                    Verified
                </option>
            </select>
            <button class="bg-indigo-600 hover:bg-indigo-500 text-white
                           px-4 py-1.5 rounded-xl text-sm font-semibold transition">
                Search
            </button>
        </form>
    </div>

    <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-800">
                    <th class="text-left px-4 py-3 text-xs text-gray-500 font-bold uppercase tracking-wider">User</th>
                    <th class="text-left px-4 py-3 text-xs text-gray-500 font-bold uppercase tracking-wider">Joined</th>
                    <th class="text-left px-4 py-3 text-xs text-gray-500 font-bold uppercase tracking-wider">Listings</th>
                    <th class="text-left px-4 py-3 text-xs text-gray-500 font-bold uppercase tracking-wider">Sales</th>
                    <th class="text-left px-4 py-3 text-xs text-gray-500 font-bold uppercase tracking-wider">Wallet</th>
                    <th class="text-left px-4 py-3 text-xs text-gray-500 font-bold uppercase tracking-wider">Status</th>
                    <th class="text-left px-4 py-3 text-xs text-gray-500 font-bold uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr class="border-b border-gray-800/50 hover:bg-gray-800/30 last:border-0">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-indigo-600 rounded-full flex items-center
                                        justify-center text-xs font-bold flex-shrink-0">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="text-sm font-semibold flex items-center gap-1">
                                    {{ $user->name }}
                                    @if($user->is_verified)
                                    <span class="text-sky-400 text-xs">✓</span>
                                    @endif
                                    @if($user->isAdmin())
                                    <span class="text-xs bg-sky-500/15 text-sky-400
                                                 px-1.5 py-0.5 rounded">Admin</span>
                                    @endif
                                </div>
                                <div class="text-xs text-gray-500">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-xs text-gray-400">
                        {{ $user->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-300">
                        {{ $user->listings_count }}
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-300">
                        {{ $user->sales_count }}
                    </td>
                    <td class="px-4 py-3 text-sm font-bold text-green-400">
                        ${{ number_format($user->wallet_balance, 2) }}
                    </td>
                    <td class="px-4 py-3">
                        @if($user->is_banned)
                        <span class="text-xs px-2 py-1 rounded-full font-semibold
                                     bg-red-500/10 text-red-400 border border-red-500/20">
                            Banned
                        </span>
                        @else
                        <span class="text-xs px-2 py-1 rounded-full font-semibold
                                     bg-green-500/10 text-green-400 border border-green-500/20">
                            Active
                        </span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        @if(!$user->isAdmin())
                        <div class="flex gap-2">
                            @if($user->is_banned)
                            <form method="POST"
                                  action="{{ route('admin.users.unban', $user) }}">
                                @csrf @method('PATCH')
                                <button class="text-xs bg-green-600/20 hover:bg-green-600/40
                                               text-green-400 px-2 py-1 rounded-lg transition">
                                    Unban
                                </button>
                            </form>
                            @else
                            <form method="POST"
                                  action="{{ route('admin.users.ban', $user) }}"
                                  onsubmit="return confirm('Ban {{ $user->name }}?')">
                                @csrf @method('PATCH')
                                <button class="text-xs bg-red-600/20 hover:bg-red-600/40
                                               text-red-400 px-2 py-1 rounded-lg transition">
                                    Ban
                                </button>
                            </form>
                            @endif
                            @if(!$user->is_verified)
                            <form method="POST"
                                  action="{{ route('admin.users.verify', $user) }}">
                                @csrf @method('PATCH')
                                <button class="text-xs bg-sky-600/20 hover:bg-sky-600/40
                                               text-sky-400 px-2 py-1 rounded-lg transition">
                                    ✓ Verify
                                </button>
                            </form>
                            @endif
                        </div>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7"
                        class="px-4 py-10 text-center text-gray-500 text-sm">
                        No users found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3 border-t border-gray-800">
            {{ $users->withQueryString()->links() }}
        </div>
    </div>

@endsection
