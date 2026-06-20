@extends('layouts.admin')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-50 overflow-hidden">
    <div class="px-8 py-6 border-b border-gray-50 flex justify-between items-center flex-wrap gap-4">
        <h3 class="text-xl font-bold text-gray-800">{{ __('admin.registration_requests') }}</h3>
        <div class="relative">
            <input type="text" id="searchInput" placeholder="Search users..." class="pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm w-64 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table id="usersTable" class="w-full text-left">
            <thead>
                <tr class="bg-gray-50 text-gray-400 text-xs font-bold uppercase tracking-wider">
                    <th class="px-8 py-4">{{ __('admin.name') }}</th>
                    <th class="px-8 py-4">{{ __('admin.contacts') }}</th>
                    <th class="px-8 py-4">{{ __('admin.date') }}</th>
                    <th class="px-8 py-4">{{ __('admin.status') }}</th>
                    <th class="px-8 py-4 text-right">{{ __('admin.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50/50 transition-colors duration-150 user-row" data-name="{{ strtolower($user->first_name . ' ' . $user->last_name) }}" data-email="{{ strtolower($user->email) }}">
                    <td class="px-8 py-5">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold mr-3">
                                {{ strtoupper(substr($user->first_name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">{{ $user->first_name }} {{ $user->last_name }}</p>
                                <p class="text-xs text-gray-400">ID: #{{ $user->id }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-5">
                        <p class="text-sm text-gray-600">{{ $user->email }}</p>
                        <p class="text-xs text-gray-400">{{ $user->phone_number }}</p>
                    </td>
                    <td class="px-8 py-5">
                        <p class="text-sm text-gray-600">{{ $user->created_at->format('M d, Y') }}</p>
                    </td>
                    <td class="px-8 py-5">
                        @if($user->status === 'pending')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700">
                                {{ __('admin.pending') }}
                            </span>
                        @elseif($user->status === 'approved')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700">
                                {{ __('admin.approved') }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-50 text-red-700">
                                {{ __('admin.rejected') }}
                            </span>
                        @endif
                    </td>
                    <td class="px-8 py-5">
                        <div class="flex items-center space-x-3">
                            @if($user->status === 'pending')
                                <form action="{{ route('admin.users.approve', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 bg-green-600 text-white text-xs font-bold rounded-lg hover:bg-green-700 transition-colors">{{ __('admin.approve') }}</button>
                                </form>
                                <form action="{{ route('admin.users.reject', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 bg-white border border-gray-200 text-gray-600 text-xs font-bold rounded-lg hover:bg-red-50 hover:text-red-700 transition-colors">{{ __('admin.reject') }}</button>
                                </form>
                            @endif

                            <div class="flex items-center space-x-1 border-l border-gray-100 pl-3">
                                <button onclick="editUser({{ $user->id }}, '{{ addslashes($user->first_name) }}', '{{ addslashes($user->last_name) }}', '{{ addslashes($user->email) }}', '{{ addslashes($user->phone_number) }}')" 
                                    class="p-2 text-indigo-500 hover:bg-indigo-50 rounded-lg transition-colors" title="{{ __('admin.edit') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('{{ __('admin.delete_user_confirm') }}')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-400 hover:text-red-600 transition-colors" title="{{ __('admin.delete') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-8 py-10 text-center text-gray-400 italic">{{ __('admin.no_users_found') }}</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div id="tablePagination" class="px-8 py-4 border-t border-gray-50"></div>
</div>

<!-- Edit User Modal -->
<div id="editUserModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl w-full max-w-md overflow-hidden shadow-2xl">
        <div class="px-8 py-6 border-b border-gray-50 flex justify-between items-center">
            <h3 class="text-xl font-bold text-gray-800">{{ __('admin.edit_user_details') }}</h3>
            <button onclick="document.getElementById('editUserModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form id="editUserForm" method="POST" class="p-8">
            @csrf
            @method('PUT')
            <div class="space-y-6">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">{{ __('admin.first_name') }}</label>
                        <input type="text" id="edit_first_name" name="first_name" required class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-indigo-100 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">{{ __('admin.last_name') }}</label>
                        <input type="text" id="edit_last_name" name="last_name" required class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-indigo-100 transition-all">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">{{ __('admin.email_address') }}</label>
                    <input type="email" id="edit_email" name="email" required class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-indigo-100 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">{{ __('admin.phone_number') }}</label>
                    <input type="text" id="edit_phone_number" name="phone_number" class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-indigo-100 transition-all">
                </div>
            </div>
            <div class="mt-8">
                <button type="submit" class="w-full bg-indigo-600 text-white py-4 rounded-xl font-bold hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100">
                    {{ __('admin.update_user_details') }}
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function editUser(id, firstName, lastName, email, phone) {
        const form = document.getElementById('editUserForm');
        form.action = `/admin/users-request/${id}`;
        document.getElementById('edit_first_name').value = firstName;
        document.getElementById('edit_last_name').value = lastName;
        document.getElementById('edit_email').value = email;
        document.getElementById('edit_phone_number').value = phone;
        document.getElementById('editUserModal').classList.remove('hidden');
    }

    document.addEventListener('DOMContentLoaded', () => {
        new TableHelper('#usersTable', '#searchInput', '#tablePagination', 10);
    });
</script>
@endpush
@endsection
