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
        <h3 class="text-base font-bold text-gray-800 flex-grow mb-4 leading-tight">
            {{ $p->nama_produk }}
        </h3>

        <div class="grid grid-cols-2 gap-3 text-xs text-gray-500 mb-5 text-center">
            <div class="bg-gray-50 border border-gray-200/80 p-2 rounded-md">
                <p class="font-semibold">Temperatur</p>
                <p class="text-sm font-medium text-gray-800 mt-1">
                    {{ $p->tempratur }}
                </p>
            </div>
            <div class="bg-gray-50 border border-gray-200/80 p-2 rounded-md">
                <p class="font-semibold">Tekanan Maks</p>
                <p class="text-sm font-medium text-gray-800 mt-1">
                    {{ $p->maximum_p }}
                </p>
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
