<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIMRS Work Order</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-900 to-blue-700 min-h-screen flex items-center justify-center">
<div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md">
    <div class="text-center mb-8">
        <div class="text-5xl mb-3">🏥</div>
        <h1 class="text-2xl font-bold text-gray-800">SIMRS Work Order</h1>
        <p class="text-gray-500 text-sm mt-1">Sistem Informasi Manajemen Rumah Sakit</p>
    </div>

    <form action="{{ route('login') }}" method="POST" class="space-y-5">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition
                          @error('email') border-red-400 @enderror"
                   placeholder="email@rumahsakit.id">
            @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input type="password" name="password" required
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition"
                   placeholder="••••••••">
        </div>

        <div class="flex items-center gap-2">
            <input type="checkbox" name="remember" id="remember" class="rounded">
            <label for="remember" class="text-sm text-gray-600">Ingat saya</label>
        </div>

        <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition">
            Masuk
        </button>
    </form>
</div>
</body>
</html>