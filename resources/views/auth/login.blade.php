<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Klinik PT JAI</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">
    <div class="bg-white p-10 rounded-2xl shadow-xl w-full max-w-md border border-gray-100">
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Klinik PT JAI</h1>
            <p class="text-gray-500 mt-2 text-sm">Silakan masuk ke akun Anda</p>
        </div>
        
        @if($errors->any())
            <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 text-sm border border-red-100">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="mb-5">
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors outline-none bg-gray-50 focus:bg-white text-gray-900">
            </div>

            <div class="mb-6">
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                <input type="password" name="password" id="password" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors outline-none bg-gray-50 focus:bg-white text-gray-900">
            </div>

            <div class="flex items-center justify-between mb-8">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 cursor-pointer">
                    <span class="ml-2 text-sm text-gray-600 font-medium">Ingat Saya</span>
                </label>
            </div>

            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3.5 px-4 rounded-xl transition-all active:scale-[0.98] shadow-md hover:shadow-lg">
                Masuk
            </button>
        </form>
    </div>
</body>
</html>