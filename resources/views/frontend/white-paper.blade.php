@extends('layouts.app')

@section('content')

    <body class="bg-slate-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">

            <!-- Header -->
            <header class="text-center mb-16">
                <h1 class="text-4xl md:text-5xl font-bold text-slate-900">
                    White Paper Library
                </h1>
                <p class="mt-4 text-lg text-slate-600 max-w-3xl mx-auto">
                    Access our collection of in-depth research, market analysis, and expert insights on key industry topics.
                </p>
            </header>

            @if ($articles->count() > 0)
                <!-- Symmetrical Articles Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

                    @foreach ($articles as $article)
                        <article
                            class="group bg-white rounded-lg shadow-sm overflow-hidden transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                            <a href="{{ route('frontend.article.show', $article) }}" class="block">
                                <!-- Image Container -->
                                <div class="overflow-hidden">
                                    <img src="{{ asset('storage/' . $article->gambar) }}" alt="{{ $article->heading }}"
                                        class="w-full h-64 object-cover transform transition-transform duration-300 group-hover:scale-105">
                                </div>

                                <!-- Content Container -->
                                <div class="p-6">
                                    @if ($article->category)
                                        <p class="text-xs font-semibold text-blue-600 uppercase tracking-wide">
                                            {{ $article->category->name }}</p>
                                    @endif

                                    <h3
                                        class="mt-2 text-lg font-bold text-slate-800 leading-tight group-hover:text-blue-600 transition-colors duration-300">
                                        {{ $article->judul }}
                                    </h3>

                                    <p class="mt-3 text-sm text-slate-500">
                                        {{ Str::limit(strip_tags($article->content), 110) }}
                                    </p>

                                    <div class="mt-4 text-xs text-slate-400">
                                        {{ $article->created_at->format('F d, Y') }}
                                    </div>
                                </div>
                            </a>
                        </article>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-16">
                    {{ $articles->links() }}
                </div>
            @else
                <!-- No Articles Found -->
                <div class="text-center py-24">
                    <div class="inline-block bg-slate-200 p-5 rounded-full">
                        <svg class="h-12 w-12 text-slate-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="mt-6 text-xl font-semibold text-slate-800">No Publications Yet</h3>
                    <p class="mt-2 text-base text-slate-500">
                        Our expert analyses are being drafted. Please check back soon.
                    </p>
                </div>
            @endif
        </div>
    </body>

@endsection
