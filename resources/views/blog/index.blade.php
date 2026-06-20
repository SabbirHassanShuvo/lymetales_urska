<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Journal - Lymetales</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Instrument Sans', sans-serif;
            background-color: #fdfdfb;
        }
        .serif-font {
            font-family: 'Playfair Display', serif;
        }
    </style>
</head>
<body class="text-gray-800 antialiased">

    <!-- Simple Navigation Header -->
    <header class="border-b border-gray-100/80 bg-white/70 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
            <a href="/" class="text-lg font-bold tracking-tight text-gray-900">
                <span class="text-indigo-600">LYMETALES</span>
            </a>
            <div class="flex items-center gap-6">
                <a href="/" class="text-sm font-semibold text-gray-600 hover:text-gray-900 transition-colors">Shop Books</a>
                <a href="/blog" class="text-sm font-semibold text-indigo-600">Our Blog</a>
                <a href="/admin/login" class="px-4 py-1.5 bg-gray-50 border border-gray-200 hover:bg-gray-100 text-gray-700 text-xs font-bold rounded-lg transition-all">
                    Admin Panel
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="max-w-6xl mx-auto px-6 py-12 md:py-16">
        
        <!-- Blog Top Title Section -->
        <div class="text-center max-w-2xl mx-auto mb-16 md:mb-20">
            <span class="text-[11px] font-bold uppercase tracking-widest text-gray-400">
                {{ \App\Models\Setting::getVal('blog_header_badge', 'THE JOURNAL') }}
            </span>
            <h1 class="serif-font text-4xl md:text-5xl lg:text-[54px] text-gray-900 font-normal leading-tight mt-3 mb-5">
                {{ \App\Models\Setting::getVal('blog_header_title', 'Stories, ideas, and quiet inspiration') }}
            </h1>
            <p class="text-gray-500 text-sm md:text-base leading-relaxed">
                {{ \App\Models\Setting::getVal('blog_header_subtitle', 'Thoughts on storytelling, parenting, and the small rituals that make childhood feel like magic.') }}
            </p>
        </div>

        @if($featured)
            <!-- Featured Article (2 Column Layout) -->
            <div class="mb-20 md:mb-24">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                    
                    <!-- Cover Image -->
                    <div class="lg:col-span-7">
                        <a href="{{ route('blog.show', $featured->slug) }}" class="block overflow-hidden rounded-2xl shadow-sm hover:shadow-md transition-all duration-300">
                            @if($featured->cover_image)
                                <img src="{{ asset($featured->cover_image) }}" alt="{{ $featured->title }}" class="w-full aspect-[4/3] md:aspect-[16/10] object-cover hover:scale-[1.01] transition-transform duration-500">
                            @else
                                <div class="w-full aspect-[16/10] bg-gray-100 flex items-center justify-center text-gray-300 text-4xl">📝</div>
                            @endif
                        </a>
                    </div>
                    
                    <!-- Details Info -->
                    <div class="lg:col-span-5 flex flex-col justify-center">
                        <div class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-widest text-emerald-600">
                            <span>Featured</span>
                            <span class="text-gray-300">•</span>
                            <span>{{ $featured->category }}</span>
                        </div>
                        
                        <a href="{{ route('blog.show', $featured->slug) }}" class="group block mt-4">
                            <h2 class="serif-font text-2xl md:text-3xl lg:text-[34px] font-bold text-gray-900 leading-tight group-hover:text-indigo-600 transition-colors">
                                {{ $featured->title }}
                            </h2>
                        </a>
                        
                        <p class="text-gray-500 text-sm md:text-base leading-relaxed mt-4 mb-6">
                            {{ $featured->excerpt }}
                        </p>
                        
                        <div class="flex items-center gap-4 text-xs text-gray-400">
                            <span>{{ $featured->published_at ? $featured->published_at->format('F d, Y') : $featured->created_at->format('F d, Y') }}</span>
                            <span>•</span>
                            <span>{{ $featured->reading_time }}</span>
                        </div>
                        
                        <div class="mt-6">
                            <a href="{{ route('blog.show', $featured->slug) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-600 hover:text-indigo-700 hover:underline">
                                Read the story 
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Grid of Other Posts -->
        @if($gridPosts->count() > 0)
            <div class="border-t border-gray-100 pt-16">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10 md:gap-8 lg:gap-10">
                    @foreach($gridPosts as $post)
                        <article class="flex flex-col">
                            
                            <!-- Cover Image -->
                            <a href="{{ route('blog.show', $post->slug) }}" class="block overflow-hidden rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 mb-5">
                                @if($post->cover_image)
                                    <img src="{{ asset($post->cover_image) }}" alt="{{ $post->title }}" class="w-full aspect-[4/3] object-cover hover:scale-[1.01] transition-transform duration-500">
                                @else
                                    <div class="w-full aspect-[4/3] bg-gray-100 flex items-center justify-center text-gray-300 text-3xl">📝</div>
                                @endif
                            </a>
                            
                            <!-- Category -->
                            <span class="text-[10px] font-bold uppercase tracking-widest text-emerald-600">
                                {{ $post->category }}
                            </span>
                            
                            <!-- Title -->
                            <a href="{{ route('blog.show', $post->slug) }}" class="group block mt-3 flex-grow">
                                <h3 class="serif-font text-lg md:text-xl font-bold text-gray-900 leading-snug group-hover:text-indigo-600 transition-colors">
                                    {{ $post->title }}
                                </h3>
                            </a>
                            
                            <!-- Excerpt -->
                            <p class="text-gray-500 text-xs md:text-sm leading-relaxed mt-2.5 mb-4">
                                {{ Str::limit($post->excerpt, 120) }}
                            </p>
                            
                            <!-- Meta Footer -->
                            <div class="flex items-center gap-3 text-[11px] text-gray-400 mt-auto">
                                <span>{{ $post->published_at ? $post->published_at->format('F d, Y') : $post->created_at->format('F d, Y') }}</span>
                                <span>•</span>
                                <span>{{ $post->reading_time }}</span>
                            </div>
                            
                        </article>
                    @endforeach
                </div>
            </div>
        @else
            @if(!$featured)
                <div class="text-center py-20 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                    <p class="text-gray-400 text-lg">No posts published yet.</p>
                </div>
            @endif
        @endif

    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100 py-12 mt-20">
        <div class="max-w-6xl mx-auto px-6 text-center text-xs text-gray-400">
            <p>&copy; {{ date('Y') }} Lymetales HQ, Inc. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>
