<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - UR Consultant</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">
    <div class="max-w-md w-full bg-white p-10 rounded-3xl shadow-sm border border-gray-100">
        <div class="mb-10 text-center">
            <h1 class="text-2xl font-bold text-gray-800 tracking-tight"><span class="text-indigo-600">LYMETALES</span></h1>
            <p class="mt-2 text-gray-500 font-medium">Admin Portal</p>
        </div>

        <form action="{{ route('admin.login') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                <input type="email" name="email" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-100 focus:border-indigo-400 outline-none transition-all duration-200 placeholder-gray-400" placeholder="admin@example.com">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                <input type="password" name="password" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-100 focus:border-indigo-400 outline-none transition-all duration-200 placeholder-gray-400" placeholder="••••••••">
            </div>

            @if($errors->any())
                <div class="p-3 bg-red-50 text-red-600 text-sm rounded-xl border border-red-100 italic">
                    {{ $errors->first() }}
                </div>
            @endif

            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 shadow-md shadow-indigo-100">
                Sign In to Dashboard
            </button>
        </form>
    </div>
</body>
</html>
