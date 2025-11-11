<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin Jalur Langit Coffee</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-gradient-to-br from-[#FFF8E1] to-[#F2E8CF] flex items-center justify-center min-h-screen px-4">

    <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-2xl w-full max-w-xs sm:max-w-sm">
        <!-- Logo dan Judul -->
        <div class="flex flex-col items-center mb-6">
            <div class="text-4xl sm:text-5xl mb-2">☕</div>
            <h2 class="text-xl sm:text-2xl font-extrabold text-[#5a3721] text-center">Jalur Langit Coffee Admin</h2>
        </div>

        @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 mb-4 rounded-lg text-xs sm:text-sm">
            <i class="fas fa-exclamation-circle mr-2"></i> {{ $errors->first() }}
        </div>
        @endif

        <form action="{{ route('pegawai.login.submit') }}" method="POST" class="space-y-4 sm:space-y-5">
            @csrf

            <!-- Email -->
            <div>
                <label class="block mb-2 text-gray-700 font-medium text-sm sm:text-base">Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="fas fa-envelope"></i>
                    </span>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#C69C6D] focus:border-transparent text-sm sm:text-base"
                        placeholder="Masukkan email">
                </div>
                <p class="text-xs text-gray-500 mt-1 ml-1">Contoh: example@gmail.com</p>
            </div>

            <!-- Password -->
            <div>
                <label class="block mb-2 text-gray-700 font-medium text-sm sm:text-base">Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="password" name="password" id="password" required
                        class="w-full pl-10 pr-12 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#C69C6D] focus:border-transparent text-sm sm:text-base"
                        placeholder="Masukkan password">
                    <button type="button" onclick="togglePasswordVisibility()"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400">
                        <i id="eyeIcon" class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <!-- Tombol Login -->
            <button type="submit"
                class="w-full bg-[#6B4226] text-white py-2 sm:py-2.5 rounded-lg font-semibold text-base sm:text-lg shadow-md hover:bg-[#5a3721] transition duration-300 flex items-center justify-center gap-2">
                <span>Masuk</span>
            </button>

            <!-- Link Daftar -->
            <div class="text-center mt-4">
                <span class="text-sm text-gray-600">Belum punya akun? </span>
                <a href="{{ route('pegawai.register') }}" class="text-sm text-[#6B4226] font-semibold hover:underline">
                    Daftar di sini
                </a>
            </div>
        </form>
    </div>

    <!-- Script Toggle Password -->
    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>

</body>

</html>