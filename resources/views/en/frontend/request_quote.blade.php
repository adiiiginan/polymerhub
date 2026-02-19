@extends('en.layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-10">
        <div class="max-w-5xl mx-auto bg-white border p-6 rounded shadow">
            <h1 class="text-3xl font-bold mb-6">Request Quote</h1>
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded">
                    <strong>There were some problems with your submission:</strong>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="request-quote-form" action="{{ route('frontend.request.quote.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf

                <!-- hidden user id -->
                <input type="hidden" name="iduser" value="{{ auth('customer')->id() }}">


                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left column: Intro + Product fields -->
                    <div>
                        <p class="text-sm text-gray-600 mb-4">Untuk meminta penawaran, Anda dapat mengisi formulir di bawah
                            ini atau mengunduh salah satu formulir standar kami, mengisinya, lalu mengunggah kembali file
                            yang telah diisi.</p>

                        <div class="flex gap-3 mb-6">
                            <a href="{{ asset('downloads/omniseal.pdf') }}"
                                class="inline-block bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded"
                                download>Download Omniseal (Form)</a>
                            <a href="{{ asset('downloads/rulon.pdf') }}"
                                class="inline-block bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded"
                                download>Download Rulon (Form)</a>
                        </div>

                        <ol class="list-decimal list-inside mb-4 text-sm text-gray-700">
                            <li>Download form sesuai kebutuhan.</li>
                            <li>Isi form yang telah diunduh pada perangkat Anda.</li>
                            <li>Kembali ke halaman ini dan unggah file yang telah diisi menggunakan bagian "Upload Completed
                                Form", lalu kirim permintaan.</li>
                        </ol>

                        <h3 class="text-md font-semibold mt-4 mb-2">Detail Produk</h3>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Produk</label>
                                <input type="text" class="w-full border rounded p-2 bg-gray-100" readonly
                                    value="{{ $product->nama_produk ?? '' }}">
                                <input type="hidden" name="product_id" value="{{ $product->id ?? '' }}">
                            </div>

                            @if ($type)
                                <div>
                                    <label class="block text-sm font-medium mb-1">Tipe</label>
                                    <input type="text" class="w-full border rounded p-2 bg-gray-100" readonly
                                        value="{{ $type->nama_jenis ?? '' }}">
                                    <input type="hidden" name="type_id" value="{{ $type->id ?? '' }}">
                                </div>
                            @endif

                            @if ($dimension)
                                <div>
                                    <label class="block text-sm font-medium mb-1">Ukuran</label>
                                    <input type="text" class="w-full border rounded p-2 bg-gray-100" readonly
                                        value="{{ $dimension->nama_ukuran ?? '' }}">
                                    <input type="hidden" name="dimension_id" value="{{ $dimension->id ?? '' }}">
                                </div>
                            @endif

                            <div>
                                <label class="block text-sm font-medium mb-1">Jumlah</label>
                                <input type="number" name="quantity" class="w-full border rounded p-2" value="1"
                                    min="1">
                            </div>
                        </div>
                    </div>

                    <!-- Right column: Customer readonly + Message, Uploads, Submit -->
                    <div>
                        <h3 class="text-md font-semibold mb-3">Data Customer</h3>
                        <div class="grid grid-cols-1 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Nama</label>
                                <input name="name" id="rq-name" type="text"
                                    class="w-full border rounded p-2 bg-gray-100 cursor-not-allowed" readonly
                                    value="{{ old('name', optional(optional(auth('customer')->user())->detail)->nama ?? (optional(auth('customer')->user())->username ?? '')) }}">
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">Email</label>
                                <input name="email" id="rq-email" type="email"
                                    class="w-full border rounded p-2 bg-gray-100 cursor-not-allowed" readonly
                                    value="{{ old('email', optional(auth('customer')->user())->email ?? '') }}">
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">Perusahaan</label>
                                <input name="company" id="rq-company" type="text"
                                    class="w-full border rounded p-2 bg-gray-100 cursor-not-allowed" readonly
                                    value="{{ old('company', optional(optional(auth('customer')->user())->detail)->perusahaan ?? '') }}">
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">Telepon</label>
                                <input name="phone" id="rq-phone" type="text"
                                    class="w-full border rounded p-2 bg-gray-100 cursor-not-allowed" readonly
                                    value="{{ old('phone', optional(optional(auth('customer')->user())->detail)->no_hp ?? '') }}">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Pesan / Keterangan</label>
                            <textarea name="catatan" id="rq-message" rows="6" class="w-full border rounded p-2"
                                placeholder="Tuliskan spesifikasi tambahan atau pertanyaan"></textarea>
                        </div>

                        <div class="mt-4">
                            <label class="block text-sm font-medium mb-2">Unggah Form yang Telah Diisi (opsional)</label>
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="block text-sm mb-1">Omniseal (PDF/DOCX)</label>
                                    <input type="file" name="file_request" id="rq-upload-omniseal"
                                        accept=".pdf,.doc,.docx" class="w-full">
                                </div>

                            </div>
                            <p class="text-xs text-gray-500 mt-2">Format yang diterima: PDF, DOCX. Maks ukuran file: 10MB
                                (server-side validation diperlukan).</p>
                        </div>

                        <div class="flex items-center justify-end gap-3 mt-6">
                            <a href="{{ url()->previous() }}"
                                class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded">Kembali</a>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">Submit
                                Request</button>
                        </div>

                        <div class="text-sm text-gray-600 mt-3">
                            <em>Catatan:</em> Anda harus mengimplementasikan route/controller untuk menyimpan file. Jika
                            belum, submit tidak akan tersimpan.
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Prefill product info from URL query params if available (e.g., ?id=123&name=Produk+ABC&shape=...&dimension=...&qty=2)
        (function() {
            const params = new URLSearchParams(window.location.search);
            const id = params.get('id') || '';
            const name = params.get('name') || '';
            const shape = params.get('shape') || '';
            const dimension = params.get('dimension') || '';
            const qty = params.get('qty') || '1';

            const setIf = (selector, value) => {
                const el = document.getElementById(selector);
                if (el && value) el.value = value;
            };

            setIf('rq-product-id', id);
            setIf('rq-product-name', name);
            setIf('rq-product-shape', shape);
            setIf('rq-product-dimension', dimension);
            setIf('rq-product-qty', qty);
        })();
    </script>
@endsection
