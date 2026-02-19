<!-- resources/views/pages/home.blade.php -->
@extends('layouts.app')


@section('title', 'JAYA NIAGA SEMESTA - Contact Us')

@section('content')
    <div class="max-w-5xl mx-auto bg-white border rounded-lg p-2 shadow-md mt-12 mb-12">
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        <form method="POST" action="{{ route('en.contact.store') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @csrf

            <!-- First Name -->
            <div>
                <label class="block text-sm font-semibold text-red-600">* First Name</label>
                <input type="text" name="nama" class="w-full border rounded px-3 py-2" id="nama" />
            </div>

            <!-- Last Name -->
            <div>
                <label class="block text-sm font-semibold text-red-600">* Last Name</label>
                <input type="text" name="belakang" class="w-full border rounded px-3 py-2" id="belakang" />
            </div>

            <!-- Country -->
            <div>
                <label class="block text-sm font-semibold text-red-600">* Country</label>
                <select name="negara" class="w-full border rounded px-3 py-2" id="negara">
                    <option>Select Country</option>
                    <option>Indonesia</option>
                    <option>Malaysia</option>
                    <option>Singapore</option>
                </select>
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-semibold text-red-600">* Email</label>
                <input type="email" name="email" class="w-full border rounded px-3 py-2" id="email" />
            </div>

            <!-- Phone -->
            <div>
                <label class="block text-sm font-semibold text-red-600">* Phone Number</label>
                <input type="number" name="notlp" class="w-full border rounded px-3 py-2" id="notlp" />
            </div>

            <!-- Company -->
            <div>
                <label class="block text-sm font-semibold text-red-600">* Company</label>
                <input type="text" name="perusahaan" class="w-full border rounded px-3 py-2" id="perusahaan" />
            </div>

            <!-- Details (Full Width) -->
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-red-600">* Details</label>
                <textarea name="catatan" class="w-full border rounded px-3 py-2 h-24" id="catatan"></textarea>
            </div>

            <!-- Privacy & Updates -->
            <div class="md:col-span-2 text-sm text-gray-700">
                <p class="font-semibold text-blue-800 mb-2">
                    We want you to know exactly how our service works and why we need your registration details.
                    Please confirm that you have read and agreed to these terms before you continue.
                </p>

                <div class="flex items-center mb-2">
                    <input type="checkbox" name="privacy" id="privacy" class="mr-2" />
                    <label for="privacy">
                        I agree to the <a href="#" class="text-blue-600 underline">Privacy Policy</a>.
                    </label>
                </div>

                <div class="flex items-center mb-2">
                    <input type="checkbox" name="updates" id="updates" class="mr-2" />
                    <label for="updates">
                        Yes, I would also like to sign up for product updates (optional)
                    </label>
                </div>

                <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mt-2" role="alert">
                    <p class="text-xs">We would like to send you our latest news on our products...</p>
                </div>
                <div class="bg-blue-100 border-t-4 border-blue-500 rounded-b text-blue-900 px-4 py-3 shadow-md mt-4"
                    role="alert">
                    <div class="flex">
                        <div class="py-1"><svg class="fill-current h-6 w-6 text-blue-500 mr-4"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path
                                    d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z" />
                            </svg></div>
                        <div>
                            <p class="font-bold">Contact Information | Mobile 1 : +62 878-2330-9818 | Mobile 2 : +62
                                813-2184-0775
                                | Email : infojns.co.id</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="md:col-span-2">
                <button type="submit" class="w-full bg-blue-800 text-white py-2 rounded">
                    Submit
                </button>
            </div>

        </form>
    </div>
@endsection
