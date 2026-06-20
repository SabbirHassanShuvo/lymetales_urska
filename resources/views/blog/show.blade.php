<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $post->title }} - The Journal - Lymetales</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Instrument Sans', sans-serif;
            background-color: #fdfdfb;
        }
        .serif-font {
            font-family: 'Playfair Display', serif;
        }
        .content-body p {
            margin-bottom: 1.75rem;
            font-size: 1.05rem;
            line-height: 1.8;
            color: #374151;
        }
        .content-body h2 {
            font-family: 'Playfair Display', serif;
            font-size: 1.75rem;
            font-weight: 700;
            color: #111827;
            margin-top: 2rem;
            margin-bottom: 1rem;
        }
        .content-body h3 {
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem;
            font-weight: 700;
            color: #111827;
            margin-top: 1.75rem;
            margin-bottom: 0.85rem;
        }
        .content-body blockquote {
            border-left: 3px solid #6366f1;
            padding-left: 1.5rem;
            font-style: italic;
            color: #4b5563;
            margin: 2rem 0;
            font-size: 1.125rem;
        }
        .content-body ul {
            list-style-type: disc;
            margin-left: 1.5rem;
            margin-bottom: 1.75rem;
            color: #374151;
            line-height: 1.75;
        }
        .content-body ol {
            list-style-type: decimal;
            margin-left: 1.5rem;
            margin-bottom: 1.75rem;
            color: #374151;
            line-height: 1.75;
        }
        .content-body li {
            margin-bottom: 0.5rem;
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

    <!-- Main Container -->
    <article class="py-12 md:py-16">
        
        <!-- Header: Centered -->
        <div class="max-w-3xl mx-auto px-6 text-center mb-10 md:mb-12">
            <span class="text-[11px] font-bold uppercase tracking-widest text-emerald-600">
                {{ $post->category }}
            </span>
            
            <h1 class="serif-font text-3xl md:text-4xl lg:text-[44px] text-gray-900 font-normal leading-tight mt-4 mb-6">
                {{ $post->title }}
            </h1>
            
            <div class="flex items-center justify-center gap-3 text-xs text-gray-400">
                <span>{{ $post->published_at ? $post->published_at->format('F d, Y') : $post->created_at->format('F d, Y') }}</span>
                <span>•</span>
                <span>{{ $post->reading_time }}</span>
            </div>
        </div>

        <!-- Cover Image: Centered -->
        <div class="max-w-4xl mx-auto px-6 mb-12 md:mb-16">
            <div class="overflow-hidden rounded-2xl shadow-sm">
                @if($post->cover_image)
                    <img src="{{ asset($post->cover_image) }}" alt="{{ $post->title }}" class="w-full aspect-[16/9] object-cover">
                @else
                    <div class="w-full aspect-[16/9] bg-gray-100 flex items-center justify-center text-gray-300 text-5xl">📝</div>
                @endif
            </div>
        </div>

        <!-- Content Body: Centered and formatted -->
        <div class="max-w-2xl mx-auto px-6 content-body">
            {!! $post->content !!}
        </div>
        
        <!-- Back to blog navigation link -->
        <div class="max-w-2xl mx-auto px-6 mt-16 pt-8 border-t border-gray-100">
            <a href="/blog" class="inline-flex items-center gap-2 text-sm font-bold text-indigo-600 hover:text-indigo-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to The Journal
            </a>
        </div>

    </article>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100 py-12">
        <div class="max-w-6xl mx-auto px-6 text-center text-xs text-gray-400">
            <p>&copy; {{ date('Y') }} Lymetales HQ, Inc. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>
