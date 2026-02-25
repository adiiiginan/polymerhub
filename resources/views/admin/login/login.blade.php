<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Jaya Niaga Semesta | Admin Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .form-input {
            background-color: #F3F4F6;
            /* gray-100 */
            border-width: 1px;
            border-color: #D1D5DB;
            /* gray-300 */
            color: #111827;
            /* gray-900 */
            border-radius: 0.5rem;
            padding: 0.875rem 1.25rem;
            width: 100%;
            transition: all 0.2s ease-in-out;
        }

        .form-input::placeholder {
            color: #6B7280;
            /* gray-500 */
        }

        .form-input:focus {
            outline: none;
            border-color: #0EA5E9;
            /* sky-500 */
            box-shadow: 0 0 0 2px rgba(14, 165, 233, 0.3);
        }
    </style>
</head>

<body class="bg-white">
    <div class="flex min-h-screen">
        <!-- Left Panel (Login Form) -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 lg:p-12">
            <div class="w-full max-w-md space-y-8">
                <!-- Header -->
                <div>
                    <h1 class="text-4xl font-bold text-gray-900">Polymer-Hub</h1>
                    <p class="mt-3 text-gray-600">Selamat datang! Silakan masuk ke akun admin Anda.</p>
                </div>

                <!-- Form -->
                <form method="POST" action="{{ route('admin.login.authenticate') }}" class="space-y-6 pt-4">
                    @csrf
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username atau
                            Email</label>
                        <input id="username" type="text" name="username" required placeholder="email@anda.com"
                            class="form-input" />
                    </div>

                    <div>
                        <div class="flex justify-between items-baseline">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Kata
                                Sandi</label>
                            <a href="#" class="text-sm text-sky-600 hover:text-sky-500 hover:underline">Lupa Kata
                                Sandi?</a>
                        </div>
                        <input id="password" type="password" name="password" required placeholder="••••••••"
                            class="form-input" />
                    </div>

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg text-sm"
                            role="alert">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <button type="submit"
                        class="w-full bg-sky-500 hover:bg-sky-600 text-white font-bold py-3 px-4 rounded-lg transition duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500">
                        Masuk
                    </button>
                </form>
                <p class="text-center text-sm text-gray-500 pt-6">
                    &copy; {{ date('Y') }} Jaya Niaga Semesta. All rights reserved.
                </p>
            </div>
        </div>

        <!-- Right Panel (Image) -->
        <div class="hidden lg:flex w-1/2 bg-gray-50 items-center justify-center p-12">
            <img src="https://cdni.iconscout.com/illustration/premium/thumb/login-3305943-2757111.png"
                alt="Login Illustration" class="max-w-full h-auto">
        </div>
    </div>
</body>

</html>
