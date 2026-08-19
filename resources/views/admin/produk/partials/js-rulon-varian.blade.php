{{-- File: resources/views/admin/produk/partials/js-rulon-varian.blade.php --}}

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const shapeSelect = document.getElementById('shapeSelect');
        const varianContainer = document.getElementById('varian-container');
        const addVariantBtn = document.getElementById('add-rulon-variant');
        const variantTemplate = document.getElementById('rulon-variant-template');
        let variantIndex = {{ $produk->variants->count() }};

        // Fungsi untuk menambah varian baru
        addVariantBtn.addEventListener('click', function() {
            const templateContent = variantTemplate.innerHTML.replace(/__INDEX__/g, variantIndex);
            const newVarian = document.createElement('div');
            newVarian.innerHTML = templateContent;
            varianContainer.appendChild(newVarian.firstElementChild);
            variantIndex++;
        });

        // Fungsi untuk menghapus varian
        varianContainer.addEventListener('click', function(e) {
            if (e.target.closest('.remove-variant')) {
                e.target.closest('.varian-item').remove();
            }
        });

        // Fungsi untuk memuat ulang ukuran berdasarkan shape
        shapeSelect.addEventListener('change', function() {
            const shapeId = this.value;
            const url = `/api/get-ukurans-by-shape/${shapeId}`;

            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Hapus semua varian yang ada
                    varianContainer.innerHTML = '';
                    variantIndex = 0;

                    // Update template dengan opsi ukuran yang baru
                    const ukuranSelect = variantTemplate.content.querySelector('select');
                    ukuranSelect.innerHTML = '<option value="">-- Pilih Ukuran --</option>';
                    data.forEach(ukuran => {
                        const option = new Option(ukuran.nama_ukuran, ukuran.id);
                        ukuranSelect.add(option);
                    });

                    // Tambahkan satu baris varian kosong secara otomatis
                    addVariantBtn.click();
                })
                .catch(error => {
                    console.error('Error fetching new sizes:', error);
                    varianContainer.innerHTML =
                        '<p class="text-danger">Gagal memuat ukuran untuk shape ini.</p>';
                });
        });
    });
</script>
