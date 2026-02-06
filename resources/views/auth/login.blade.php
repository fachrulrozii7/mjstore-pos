<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | MJ Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Instrument Sans', sans-serif; }</style>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-4">
    <div class="max-w-md w-full">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-600 rounded-2xl shadow-lg shadow-blue-200 mb-4">
                <span class="text-white text-2xl font-black">MJ</span>
            </div>
            <h2 class="text-2xl font-bold text-gray-900">Selamat Datang Kembali</h2>
            <p class="text-sm text-gray-500 mt-1">Silakan login untuk mengelola toko Anda.</p>
        </div>

        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100">
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="space-y-5">
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Username</label>
                        <input type="username" name="username" value="{{ old('username') }}" required
                            class="w-full mt-1.5 px-4 py-3 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-blue-500 text-sm font-semibold">
                        @error('username') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Password</label>
                        <input type="password" name="password" required
                            class="w-full mt-1.5 px-4 py-3 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-blue-500 text-sm font-semibold">
                    </div>

                    <button type="submit" 
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-blue-100 transition-all transform active:scale-[0.98]">
                        Masuk ke System
                    </button>
                </div>
            </form>
        </div>

        <p class="text-center text-gray-400 text-xs mt-8 italic">
            &copy; 2026 MJ Store Ecosystem. All rights reserved.
        </p>
    </div>
</body>
</html>