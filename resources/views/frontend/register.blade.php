@extends('layouts.app')

@section('content')


    <div class="container mx-auto px-4 py-10">
        <div class="bg-white shadow-md rounded-lg p-8">
            <h2 class="text-2xl font-bold mb-6">Register Form</h2>

            <!-- Success Message -->
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <strong>Error</strong>
                    <ul class="mt-2">
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register.submit') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Company -->
                    <div>
                        <label class="block mb-1 font-medium">Company<span class="text-red-500">*</span></label>
                        <input type="text" name="perusahaan" class="w-full border rounded-lg px-3 py-2"
                            value="{{ old('perusahaan') }}">
                    </div>


                    <!-- VAT Number -->
                    <div>
                        <label class="block font-semibold mb-1">VAT<span class="text-red-500">*</span></label>
                        <input type="text" name="vat_number" class="w-full border rounded-lg px-3 py-2" required>
                    </div>

                    <div>
                        <label class="block mb-1 font-medium">Company Type<span class="text-red-500">*</span></label>
                        <select name="idperusahaan" class="w-full border rounded-lg px-3 py-2">
                            <option value="">Select Type</option>
                            @foreach ($perusahaan->unique('nama') as $item)
                                <option value="{{ $item->id }}">{{ strtoupper($item->nama) }}</option>
                            @endforeach
                        </select>
                    </div>


                    <div>
                        <label class="block mb-1 font-medium">Business Classification<span
                                class="text-red-500">*</span></label>
                        <select name="idkuali" class="w-full border rounded-lg px-3 py-2" required>
                            <option value="">Select Classification</option>
                            @foreach ($klasifikasi->unique('nama') as $item)
                                <option value="{{ $item->id }}">{{ strtoupper($item->nama) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- First Name -->
                    <div>
                        <label class="block font-semibold mb-1">First Name<span class="text-red-500">*</span></label>
                        <input type="text" name="nama" class="w-full border rounded-lg px-3 py-2" required>
                    </div>

                    <!-- Last Name -->
                    <div>
                        <label class="block font-semibold mb-1">Last Name<span class="text-red-500">*</span></label>
                        <input type="text" name="lengkap" class="w-full border rounded-lg px-3 py-2" required>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block mb-1 font-medium">Email<span class="text-red-500">*</span></label>
                        <input type="email" name="email"
                            class="w-full border rounded-lg px-3 py-2 @error('email') border-red-500 @enderror"
                            value="{{ old('email') }}" required>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Phone Number -->
                        <div>
                            <label class="block mb-1 font-medium">Phone Number<span class="text-red-500">*</span></label>
                            <input type="text" name="no_hp" class="w-full border rounded-lg px-3 py-2"
                                value="{{ old('no_hp') }}" required>
                        </div>

                        <!-- Jabatan -->
                        <div>
                            <label class="block mb-1 font-medium">Position<span class="text-red-500">*</span></label>
                            <input type="text" name="jabatan" class="w-full border rounded-lg px-3 py-2"
                                value="{{ old('jabatan') }}" required>
                        </div>
                    </div>

                    <!-- Street -->
                    <div>
                        <label class="block mb-1 font-medium">Address Company<span class="text-red-500">*</span></label>
                        <textarea name="alamat" rows="2" class="w-full border rounded-lg px-3 py-2" required>{{ old('alamat') }}</textarea>
                    </div>

                    <!-- City -->
                    <div>
                        <label class="block font-semibold mb-1">City<span class="text-red-500">*</span></label>
                        <select name="nama_kota" id="city-select" class="w-full border rounded-lg px-3 py-2" required>
                            <option value="">Select Country First</option>
                        </select>
                    </div>

                    <!-- Zip Code -->
                    <div>
                        <label class="block font-semibold mb-1">Zip Postal Code<span class="text-red-500">*</span></label>
                        <input type="text" name="zip_code" id="zip-code-input" class="w-full border rounded-lg px-3 py-2"
                            required readonly>
                    </div>

                    <!-- Country -->
                    <div>
                        <label class="block font-semibold mb-1">Country<span class="text-red-500">*</span></label>
                        <select name="idcountry" id="country-select" class="w-full border rounded-lg px-3 py-2" required>
                            <option value="">Select Country</option>
                            @foreach ($negara as $item)
                                <option value="{{ $item->id }}" data-iso="{{ $item->kode_iso }}">
                                    {{ $item->nama_negara }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <input type="hidden" name="kode_iso" id="kode_iso">

                <!-- Comments (Full Width) -->
                <div class="mt-6">
                    <label class="block font-semibold mb-1">Comments<span class="text-red-500">*</span></label>
                    <textarea name="comment" class="w-full border rounded-lg px-3 py-2" rows="4" required></textarea>
                </div>

                <div>
                    <label class="block mb-1 font-medium">Password<span class="text-red-500">*</span></label>
                    <input type="password" name="password" id="password"
                        class="w-full border rounded-lg px-3 py-2 @error('password') border-red-500 @enderror" required>
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block mb-1 font-medium">Confirm Password<span class="text-red-500">*</span></label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="w-full border rounded-lg px-3 py-2 @error('password_confirmation') border-red-500 @enderror"
                        required>
                    @error('password_confirmation')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Checkbox -->

                <div class="mt-6 space-y-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="agree" required class="mr-2">
                        <span>Agree to <a href="#" class="text-blue-600 underline">Privacy
                                Policy</a>.</span>
                    </label>

                    <label class="flex items-center">
                        <input type="checkbox" name="updates" class="mr-2">
                        <span>Sign Up for Updates</span>
                    </label>
                </div>




                <!-- Submit -->
                <div class="mt-8">

                    <button type="submit"
                        class="w-full bg-blue-700 hover:bg-blue-800 text-white font-semibold py-3 px-4 rounded-md transition-colors focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        Register Now
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const countrySelect = document.getElementById('country-select');
        const citySelect = document.getElementById('city-select');
        const zipCodeInput = document.getElementById('zip-code-input');
        const kodeIsoInput = document.getElementById('kode_iso');

        countrySelect.addEventListener('change', function() {
            const countryId = this.value;
            const selectedOption = this.options[this.selectedIndex];
            const iso = selectedOption.getAttribute('data-iso');
            kodeIsoInput.value = iso;

            citySelect.innerHTML = '<option value="">Loading cities...</option>';
            zipCodeInput.value = '';

            if (countryId) {
                fetch(`/get-cities/${countryId}`)
                    .then(response => response.json())
                    .then(data => {
                        citySelect.innerHTML = '<option value="">Select City</option>';
                        data.forEach(city => {
                            citySelect.innerHTML +=
                                `<option value="${city.id}">${city.nama_kota}</option>`;
                        });
                    });
            } else {
                citySelect.innerHTML = '<option value="">Select Country First</option>';
            }
        });

        citySelect.addEventListener('change', function() {
            const cityId = this.value;
            zipCodeInput.value = 'Loading...';

            if (cityId) {
                fetch(`/get-zip-code/${cityId}`)
                    .then(response => response.json())
                    .then(data => {
                        zipCodeInput.value = data.zip_code || '';
                    });
            } else {
                zipCodeInput.value = '';
            }
        });
    });
</script>
