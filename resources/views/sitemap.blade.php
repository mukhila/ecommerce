<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

    {{-- Static pages --}}
    <url>
        <loc>{{ url('/') }}</loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>{{ url('/about-us') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    <url>
        <loc>{{ url('/contact-us') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    <url>
        <loc>{{ url('/page/faqs') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.5</priority>
    </url>
    <url>
        <loc>{{ url('/page/privacy-policy') }}</loc>
        <changefreq>yearly</changefreq>
        <priority>0.3</priority>
    </url>
    <url>
        <loc>{{ url('/page/terms-and-conditions') }}</loc>
        <changefreq>yearly</changefreq>
        <priority>0.3</priority>
    </url>
    <url>
        <loc>{{ url('/support/create') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.4</priority>
    </url>

    {{-- CMS Pages --}}
    @foreach($pages as $page)
    <url>
        <loc>{{ url('/page/' . $page->slug) }}</loc>
        @if($page->updated_at)
        <lastmod>{{ $page->updated_at->toAtomString() }}</lastmod>
        @endif
        <changefreq>monthly</changefreq>
        <priority>0.5</priority>
    </url>
    @endforeach

    {{-- Categories --}}
    @foreach($categories as $category)
    <url>
        <loc>{{ url('/category/' . $category->slug) }}</loc>
        @if($category->updated_at)
        <lastmod>{{ $category->updated_at->toAtomString() }}</lastmod>
        @endif
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach

    {{-- Products --}}
    @foreach($products as $product)
    <url>
        <loc>{{ url('/product/' . $product->slug) }}</loc>
        @if($product->updated_at)
        <lastmod>{{ $product->updated_at->toAtomString() }}</lastmod>
        @endif
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach

</urlset>
