@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">GDPR Compliance Tools</h2>
            <p class="text-sm text-gray-500 mt-1">Manage user data export and anonymization requests.</p>
        </div>
    </div>

    @if (session('success'))
        <div class="p-4 bg-green-50 text-green-700 border border-green-200 rounded-xl font-semibold flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="p-4 bg-rose-50 text-rose-700 border border-rose-200 rounded-xl font-semibold flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Export Data -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <div class="flex items-center space-x-3 mb-4">
                <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800">Export User Data</h3>
            </div>
            <p class="text-sm text-gray-500 mb-6">
                Export all personally identifiable information (PII) including orders, contact messages, and newsletter subscriptions for a specific email address in JSON format.
            </p>
            <form action="{{ route('admin.gdpr.export') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">User Email Address</label>
                    <input type="email" name="email" required placeholder="customer@example.com" class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
                </div>
                <button type="submit" class="w-full px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition-colors shadow-sm">
                    Export JSON Data
                </button>
            </form>
        </div>

        <!-- Anonymize Data -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-rose-100">
            <div class="flex items-center space-x-3 mb-4">
                <div class="p-3 bg-rose-50 text-rose-600 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800">Anonymize / Delete Data</h3>
            </div>
            <p class="text-sm text-gray-500 mb-6">
                Permanently scrub all PII from orders and contact messages, and delete newsletter subscriptions associated with an email address. <strong class="text-rose-600">This action cannot be undone.</strong>
            </p>
            <form action="{{ route('admin.gdpr.anonymize') }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently anonymize data for this email? This cannot be undone.');">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">User Email Address</label>
                    <input type="email" name="email" required placeholder="customer@example.com" class="w-full px-4 py-2 border border-rose-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-rose-500 bg-white">
                </div>
                <button type="submit" class="w-full px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-sm font-bold rounded-xl transition-colors shadow-sm flex items-center justify-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    Permanently Anonymize Data
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
