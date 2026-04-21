<div
    class="bg-white rounded-lg overflow-hidden group flex flex-col border border-gray-200 hover:shadow-xl hover:border-[var(--main-color)]/50 transition-all duration-300">
    <!-- Image -->
    <div class="h-52 w-full flex items-center justify-center bg-white p-4 overflow-hidden">
        <img src="{{ asset('backend/assets/media/produk/' . $p->gambar) }}" alt="{{ $p->nama_produk }}"
            class="max-h-full w-auto object-contain transition-transform duration-300 ease-in-out group-hover:scale-105"
            loading="lazy">
    </div>

    <!-- Content -->
    <div class="p-5 flex flex-col flex-grow">
        <h3 class="text-base font-bold text-gray-800 flex-grow mb-2 leading-tight">
            {{ $p->nama_produk }}
        </h3>
        <p class="text-xs text-gray-500 mb-4">Kategori: Tubing</p>

        <div class="space-y-3 text-xs text-gray-600 mb-5">
            <div class="flex justify-between items-center border-b border-gray-100 py-1">
                <span class="font-semibold text-gray-700">Max Pressure (73°F):</span>
                <span class="font-medium text-gray-800">{{ $p->max_pressure_73f ?? 'N/A' }}</span>
            </div>
            <div class="flex justify-between items-center border-b border-gray-100 py-1">
                <span class="font-semibold text-gray-700">Max Pressure (320°F):</span>
                <span class="font-medium text-gray-800">{{ $p->max_pressure_320f ?? 'N/A' }}</span>
            </div>
            <div class="flex justify-between items-center border-b border-gray-100 py-1">
                <span class="font-semibold text-gray-700">Vacuum Rating (73°F):</span>
                <span class="font-medium text-gray-800">{{ $p->vacuum_rating_73f ?? 'N/A' }}</span>
            </div>
            <div class="flex justify-between items-center py-1">
                <span class="font-semibold text-gray-700">Vacuum Rating (320°F):</span>
                <span class="font-medium text-gray-800">{{ $p->vacuum_rating_320f ?? 'N/A' }}</span>
            </div>
        </div>

        <div class="mt-auto">
            <button data-url="{{ route('id.frontend.produk.show', $p->id) }}"
                class="view-options-button w-full text-center bg-[var(--accent-color)] text-white hover:bg-opacity-90 font-bold px-4 py-2.5 rounded-md text-sm transition-all duration-300 transform group-hover:scale-105">
                Lihat Opsi
            </button>
        </div>
    </div>
</div>
