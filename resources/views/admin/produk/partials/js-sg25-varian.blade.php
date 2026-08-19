{{-- File: resources/views/admin/produk/partials/js-sg25-varian.blade.php --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const addSg25Button = document.getElementById('btn-add-sg25-varian');
        const sg25TableBody = document.getElementById('sg25-varian-body');
        const sg25Template = document.getElementById('sg25-row-template');
        let sg25Index = 0;

        // Tambahkan baris pertama jika tabel kosong
        if (sg25TableBody && sg25TableBody.rows.length === 0) {
            addSg25Row();
        }

        if (addSg25Button) {
            addSg25Button.addEventListener('click', addSg25Row);
        }

        function addSg25Row() {
            const clone = sg25Template.content.cloneNode(true);
            const newRow = clone.querySelector('tr');

            // Ganti placeholder index dengan index unik
            const newHtml = newRow.innerHTML.replace(/__INDEX__/g, sg25Index);
            newRow.innerHTML = newHtml;

            sg25TableBody.appendChild(newRow);
            sg25Index++;
        }

        if (sg25TableBody) {
            sg25TableBody.addEventListener('click', function(e) {
                const removeButton = e.target.closest('.btn-remove-sg25');
                if (removeButton) {
                    const row = removeButton.closest('tr');
                    // Jangan hapus jika hanya ada satu baris tersisa
                    if (sg25TableBody.querySelectorAll('tr').length > 1) {
                        row.remove();
                    } else {
                        alert('Setidaknya harus ada satu baris varian.');
                    }
                }
            });
        }
    });
</script>
