@extends('id.layouts.app')

@section('title', 'JAYA NIAGA SEMESTA - Innovative Industrial Materials')

@section('content')
    <div class="bg-slate-50 text-slate-800 font-sans">

        <!-- HERO SECTION -->
        <section class="bg-white">
            <div class="container mx-auto px-6 grid md:grid-cols-2 gap-12 items-center py-20 md:py-32">
                <div class="text-center md:text-left">
                    <h1 class="text-4xl md:text-5xl font-bold text-slate-900 leading-tight">
                        The Future of High-Performance Materials is Here.
                    </h1>
                    <p class="mt-6 text-lg text-slate-600">
                        We engineer and supply advanced polymer components that push the boundaries of performance in
                        aerospace, automotive, and industrial applications.
                    </p>
                    <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
                        <a href="#products"
                            class="inline-block bg-teal-600 text-white font-semibold px-8 py-3 rounded-lg shadow-md hover:bg-teal-700 transition-all duration-300 transform hover:-translate-y-1">
                            Explore Our Solutions
                        </a>
                        <a href="#contact"
                            class="inline-block bg-slate-200 text-slate-700 font-semibold px-8 py-3 rounded-lg hover:bg-slate-300 transition-colors duration-300">
                            Speak to an Engineer
                        </a>
                    </div>
                </div>
                <div class="relative w-full h-[300px] sm:h-[360px] md:h-[420px] lg:h-[480px]">
                    <div
                        class="absolute inset-0 bg-teal-200 rounded-full transform -rotate-12 scale-105 blur-2xl opacity-40">
                    </div>

                    <img src="{{ asset('backend/assets/media/judul.jpg') }}" alt="Industrial Machinery"
                        class="relative w-full h-full object-cover rounded-2xl shadow-xl">
                </div>
            </div>
        </section>

        <!-- CORE PILLARS SECTION -->
        <section class="py-24 bg-slate-100">
            <div class="container mx-auto px-6">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-slate-900">Our Commitment to Excellence</h2>
                    <p class="text-lg text-slate-600 mt-4 max-w-3xl mx-auto">We build our reputation on three core pillars
                        that guarantee your success.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-white p-8 rounded-xl shadow-sm border border-slate-200 text-center">
                        <div
                            class="flex items-center justify-center h-20 w-20 rounded-full bg-teal-100 text-teal-600 mx-auto mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M12 6V3m0 18v-3M5.636 5.636l-1.414-1.414M19.778 19.778l-1.414-1.414M4.222 19.778l1.414-1.414M18.364 5.636l1.414-1.414" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-slate-800">Unrivaled Innovation</h3>
                        <p class="mt-2 text-slate-500">Constantly pushing the limits of material science to deliver
                            next-generation solutions.</p>
                    </div>
                    <div class="bg-white p-8 rounded-xl shadow-sm border border-slate-200 text-center">
                        <div
                            class="flex items-center justify-center h-20 w-20 rounded-full bg-teal-100 text-teal-600 mx-auto mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-slate-800">Absolute Precision</h3>
                        <p class="mt-2 text-slate-500">Manufacturing to the tightest tolerances, ensuring perfect fit and
                            function every time.</p>
                    </div>
                    <div class="bg-white p-8 rounded-xl shadow-sm border border-slate-200 text-center">
                        <div
                            class="flex items-center justify-center h-20 w-20 rounded-full bg-teal-100 text-teal-600 mx-auto mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-slate-800">Collaborative Partnership</h3>
                        <p class="mt-2 text-slate-500">Working with you as an extension of your team to solve your toughest
                            engineering challenges.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- FEATURED PRODUCTS -->
        <section class="py-24 bg-white" id="products">
            <div class="container mx-auto px-6">
                <div class="text-left mb-16 max-w-2xl">
                    <span class="text-teal-600 font-semibold tracking-wider uppercase">Our Brochure</span>
                    <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mt-2">Engineered Material</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Product Card -->
                    <div class="bg-slate-50 rounded-xl overflow-hidden group border border-slate-200/80">
                        <div class="p-8">
                            <h3 class="text-2xl font-bold text-slate-800">Rulon® Series</h3>
                            <p class="text-slate-500 mt-2 mb-6">The industry standard for high-performance bearings and
                                components with low-friction, self-lubricating properties.</p>
                            <a href="#"
                                class="font-semibold text-teal-600 hover:text-teal-700 flex items-center gap-2">
                                View Rulon® Products <span
                                    class="transform transition-transform group-hover:translate-x-1">&rarr;</span>
                            </a>
                        </div>
                        <img src="{{ asset('backend/downloads/rulon.png') }}" alt="Rulon Products"
                            class="w-full h-172 object-cover">
                    </div>
                    <!-- Product Card -->
                    <div class="bg-slate-50 rounded-xl overflow-hidden group border border-slate-200/80">
                        <div class="p-8">
                            <h3 class="text-2xl font-bold text-slate-800">Meldin® Series</h3>
                            <p class="text-slate-500 mt-2 mb-6">High-temperature polyimide materials offering exceptional
                                dimensional stability and thermal resistance for demanding applications.</p>
                            <a href="#"
                                class="font-semibold text-teal-600 hover:text-teal-700 flex items-center gap-2">
                                View Meldin® Products <span
                                    class="transform transition-transform group-hover:translate-x-1">&rarr;</span>
                            </a>
                        </div>
                        <img src="{{ asset('backend/downloads/meldin.png') }}" alt="Meldin Products"
                            class="w-full h-172 object-cover">
                    </div>
                    <!-- Product Card -->
                    <div class="bg-slate-50 rounded-xl overflow-hidden group border border-slate-200/80">
                        <div class="p-8">
                            <h3 class="text-2xl font-bold text-slate-800">OmniSeal® Series</h3>
                            <p class="text-slate-500 mt-2 mb-6">Spring-energized seals designed for ultimate performance in
                                critical sealing applications across a wide range of temperatures and pressures.</p>
                            <a href="#"
                                class="font-semibold text-teal-600 hover:text-teal-700 flex items-center gap-2">
                                View OmniSeal® Products <span
                                    class="transform transition-transform group-hover:translate-x-1">&rarr;</span>
                            </a>
                        </div>
                        <img src="{{ asset('backend/downloads/omniseal.png') }}" alt="OmniSeal Products"
                            class="w-full h-172 object-cover">
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA SECTION -->
        <section class="py-24" id="contact">
            <div class="container mx-auto px-6">
                <div
                    class="bg-slate-800 rounded-2xl shadow-xl p-12 md:p-20 flex flex-col md:flex-row items-center justify-between gap-8">
                    <div>
                        <h2 class="text-3xl font-bold text-white">Ready to Solve Your Next Challenge?</h2>
                        <p class="text-slate-300 mt-3 max-w-2xl">Let our experts help you select the right material for
                            your needs. Get in touch for a custom quote or technical consultation.</p>
                    </div>
                    <div class="flex-shrink-0">
                        <a href="#"
                            class="inline-block bg-teal-500 text-white font-bold px-10 py-4 rounded-lg hover:bg-teal-600 transition-colors duration-300 shadow-lg">
                            Request Consultation
                        </a>
                    </div>
                </div>
            </div>
        </section>

    </div>
@endsection
