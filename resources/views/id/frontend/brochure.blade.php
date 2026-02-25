@section('title', 'Polymer-Hub - Brochure')
@extends('id.layouts.app')
@section('content')
    <!-- Title -->
    <br>
    <br>
    <h1 class="text-3xl md:text-4xl font-semibold text-blue-900 text-center mb-12 tracking-wide">
        Brochure
    </h1>

    <!-- Brochure Section -->
    <section class="w-full max-w-7xl mx-auto px-6 mt-10 mb-16">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @php
                $brochures = [
                    ['name' => 'OmniLip Brochure', 'pdf' => 'omnibrochure.pdf', 'image' => 'ominilip.png'],
                    ['name' => 'Omni Seal Brochure', 'pdf' => 'omnisealbrochure.pdf', 'image' => 'omniseal.png'],
                    ['name' => 'Rulon Brochure', 'pdf' => 'rulonbrochure.pdf', 'image' => 'rulon.png'],
                    ['name' => 'Meldin Brochure', 'pdf' => 'meldinbrochure.pdf', 'image' => 'meldin.png'],
                ];
            @endphp

            @foreach ($brochures as $brochure)
                <a href="{{ asset('backend/downloads/' . $brochure['pdf']) }}" target="_blank" rel="noopener noreferrer"
                    class="block bg-white shadow-lg rounded-lg p-4 text-center cursor-pointer transform hover:scale-105 transition-transform duration-300">
                    <img src="{{ asset('backend/downloads/' . $brochure['image']) }}" alt="{{ $brochure['name'] }}"
                        class="w-full h-80 object-cover mb-4 rounded">
                    <h3 class="text-lg font-medium text-blue-900">{{ $brochure['name'] }}</h3>
                </a>
            @endforeach
        </div>
    </section>
@endsection
