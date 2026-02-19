@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- HERO SECTION --}}
        <section class="py-16 my-8 rounded-lg">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center">

                    {{-- LEFT CONTENT --}}
                    <div>
                        <h1 class="text-5xl font-bold mb-4 text-gray-800">
                            {{ $article->judul }}
                        </h1>

                        <div class="prose max-w-none text-lg text-gray-600">
                            @php
                                $content = $article->content;
                                // Convert markdown-like list to HTML list
                                $content = preg_replace_callback(
                                    '/((?:^[ \t]*[\*\-][ \t].*(?:\r?\n|$))+)/m',
                                    function ($matches) {
                                        $items = explode("\n", trim($matches[0]));
                                        $list = '<ul>';
                                        foreach ($items as $item) {
                                            if (trim($item)) {
                                                $text = preg_replace('/^[ \t]*[\*\-][ \t]/', '', $item);
                                                $list .= '<li>' . trim($text) . '</li>';
                                            }
                                        }
                                        $list .= '</ul>';
                                        return $list;
                                    },
                                    $content,
                                );

                                // For the rest, convert newlines to breaks, but not inside the list we just made.
                                $content = nl2br($content, false);
                                // But nl2br might add <br> after </ul>, let's remove them.
$content = str_replace('</ul><br />', '</ul>', $content);
                            @endphp
                            {!! $content !!}
                        </div>


                    </div>

                    {{-- RIGHT IMAGE --}}
                    <div class="flex justify-center md:justify-end">
                        <img src="{{ asset('storage/' . $article->gambar) }}" alt="{{ $article->judul }}"
                            class="max-h-[420px] rounded shadow-lg object-cover">
                    </div>

                </div>
            </div>
        </section>


    </div>
@endsection
