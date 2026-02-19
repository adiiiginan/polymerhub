<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Jaya Niaga Semesta</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Kiri: Form Login -->
        <div class="w-full md:w-1/2 flex items-center justify-center px-8 bg-white shadow-lg">
            <div class="max-w-md w-full space-y-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Selamat Datang</h1>
                    <p class="text-sm text-gray-500 mt-1">Silakan masuk untuk mengakses akun Anda.</p>
                </div>

                <!-- Form Login -->
                <form method="POST" action="{{ route('admin.login.authenticate') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Username or Email</label>
                        <input type="text" name="username" required placeholder="Masukkan Username or Email"
                            class="mt-1 w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Kata Sandi</label>
                        <input type="password" name="password" required placeholder="••••••••"
                            class="mt-1 w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none" />
                        <div class="text-right text-sm mt-1">
                            <a href="#" class="text-blue-600 hover:underline">Lupa Kata Sandi?</a>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="text-red-600 text-sm text-center">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-md transition">
                        Masuk ke Akun
                    </button>
                </form>

                <p class="text-sm text-gray-600 text-center">
                    Belum punya akun?
                    <a href="" class="text-blue-600 hover:underline font-medium">Daftar
                        Sekarang</a>
                </p>
            </div>
        </div>

        <!-- Kanan: Ilustrasi atau Branding -->
        <div class="hidden md:block w-1/2 relative overflow-hidden">
            <!-- Background image -->
            <div class="absolute inset-0">
                <img src="https://images.unsplash.com/photo-1509395062183-67c5ad6faff9?auto=format&fit=crop&w=1200&q=80"
                    alt="Gedung Modern" class="w-full h-full object-cover brightness-75" />
            </div>

            <!-- Overlay konten -->
            <div class="relative z-10 h-full flex flex-col items-center justify-center text-center px-10 text-white">
                <h2
                    class="text-4xl md:text-5xl font-extrabold mb-4 drop-shadow-[0_4px_10px_rgba(0,0,0,0.6)] tracking-wide">
                    Jaya Niaga Semesta
                </h2>
                <p class="text-lg max-w-md drop-shadow-[0_2px_6px_rgba(0,0,0,0.4)] text-white font-medium">
                    Platform terpercaya untuk solusi bisnis Anda. Didesain untuk perusahaan masa kini, tampil modern dan
                    efisien.
                </p>
            </div>
        </div>
    </div>
</body>

</html>
