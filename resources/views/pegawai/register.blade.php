<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Pegawai - Jalur Langit Coffee</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHq+2zDd1rvZh6z5z6nT1up4dVJkN6NaxpI1D8fZzIvxJ8C5tDf6udY8bBt5jwK6Q5gkP8yA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-gradient-to-br from-[#FFF8E1] to-[#F2E8CF] flex items-center justify-center min-h-screen px-4">

    <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-2xl w-full max-w-xs sm:max-w-md">
        <!-- Logo dan Judul -->
        <div class="flex flex-col items-center mb-6">
            <div class="text-4xl sm:text-5xl mb-2">☕</div>
            <h2 class="text-xl sm:text-2xl font-extrabold text-[#6B4226] text-center">Daftar Pegawai</h2>
        </div>

        @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 mb-4 rounded-lg text-xs sm:text-sm">
            <i class="fas fa-exclamation-circle mr-2"></i> {{ $errors->first() }}
        </div>
        @endif

        <form action="{{ route('pegawai.register.submit') }}" method="POST" class="space-y-4 sm:space-y-5">
            @csrf

            <!-- Nama -->
            <div>
                <label class="block mb-2 text-gray-700 font-medium text-sm sm:text-base">Nama Lengkap</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="fas fa-user"></i>
                    </span>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#C69C6D] focus:border-transparent text-sm sm:text-base"
                        placeholder="Masukkan nama lengkap">
                </div>
            </div>

            <!-- Email -->
            <div>
                <label class="block mb-2 text-gray-700 font-medium text-sm sm:text-base">Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="fas fa-envelope"></i>
                    </span>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#C69C6D] focus:border-transparent text-sm sm:text-base"
                        placeholder="Masukkan email aktif">
                </div>
            </div>

            <!-- Password -->
            <div>
                <label class="block mb-2 text-gray-700 font-medium text-sm sm:text-base">Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="password" name="password" required
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#C69C6D] focus:border-transparent text-sm sm:text-base"
                        placeholder="Buat password">
                </div>
            </div>

            <!-- Konfirmasi Password -->
            <div>
                <label class="block mb-2 text-gray-700 font-medium text-sm sm:text-base">Konfirmasi Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="password" name="password_confirmation" required
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#C69C6D] focus:border-transparent text-sm sm:text-base"
                        placeholder="Ulangi password">
                </div>
            </div>

            <!-- Tombol Daftar -->
            <button type="submit"
                class="w-full bg-[#6B4226] text-white py-2 sm:py-2.5 rounded-lg font-semibold text-base sm:text-lg shadow-md hover:bg-[#5a3721] transition duration-300 flex items-center justify-center gap-2">
                <i class="fas fa-user-plus"></i>
                <span>Daftar</span>
            </button>

            <!-- Link Login -->
            <p class="text-center text-gray-600 mt-4 text-xs sm:text-sm">
                Sudah punya akun? <a href="{{ route('pegawai.login') }}" class="text-[#C69C6D] hover:underline font-semibold">Login di sini</a>
            </p>
        </form>
    </div>

</body>

</html>