<footer class="bg-[#06183a] text-white py-12">
    <div class="container mx-auto px-6 max-w-screen-xl grid grid-cols-1 md:grid-cols-3 gap-12">

        <!-- Kolom 1: Logo & Deskripsi -->
        <div class="space-y-6"style="margin-left: 127px;">
            <div class="flex items-center space-x-3">
                <!-- Logo -->

            </div>
            <p class="text-gray-300">
                Memberdayakan bisnis dengan solusi TI yang inovatif untuk meningkatkan kinerja dan hasil.
            </p>

            <!-- Sosial Media -->
            <div class="flex space-x-4 text-gray-400">
                <a href="#" class="hover:text-white transition"><i class="fab fa-x-twitter"></i></a>
                <a href="#" class="hover:text-white transition"><i class="fab fa-linkedin"></i></a>
                <a href="#" class="hover:text-white transition"><i class="fab fa-instagram"></i></a>
                <a href="#" class="hover:text-white transition"><i class="fab fa-facebook"></i></a>
            </div>

            <!-- Back to Top -->
            <div>
                <a href="#top"
                    class="inline-block border border-gray-500 px-4 py-2 rounded-md hover:bg-gray-700 transition">
                    Kembali Ke Atas
                </a>
            </div>
        </div>

        <!-- Kolom 2: Site Map -->
        <div class="space-y-2">
            <h2 class="font-bold text-lg"></h2>
            <div class="flex flex-col space-y-2 text-gray-300">
                <a href="/" class="hover:text-white transition"></a>
                <a href="/technology" class="hover:text-white transition"></a>
                <a href="/products" class="hover:text-white transition"></a>

            </div>
        </div>

        <!-- Kolom 3: Legal -->
        <div class="space-y-4">
            <h2 class="font-bold text-lg">Legal</h2>
            <div class="flex flex-col space-y-2 text-gray-300">
                <a href="{{ route('id.privacy') }}" class="hover:text-white transition">Kebijakan Privasi</a>
                <a href="/terms" class="hover:text-white transition">Syarat dan Ketentuan</a>
                <a href="/legal" class="hover:text-white transition">Pemberitahuan Legal</a>
            </div>
        </div>

    </div>

    <!-- Copyright -->
    <div class="text-center text-gray-400 mt-12 pt-6 border-t border-gray-700">
        &copy; {{ date('Y') }} JNS. All Rights Reserved.
    </div>
</footer>
