<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ theme: localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light') }" x-init="$watch('theme', val => { localStorage.setItem('theme', val); val === 'dark' ? document.documentElement.classList.add('dark') : document.documentElement.classList.remove('dark'); })" :class="{ 'dark': theme === 'dark' }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $pageTitle ?? $siteSettings['site_name'] }}</title>

    @if($isPostShowPage && isset($metaDescription))
<meta name="description" content="{{ $metaDescription }}">
    @else
<meta name="description" content="{{ $siteSettings['site_description'] }}">
    @endif

    <meta property="og:title" content="{{ $pageTitle ?? $siteSettings['site_name'] }}">
    @if($isPostShowPage && isset($metaDescription))
<meta property="og:description" content="{{ $metaDescription }}">
    @else
<meta property="og:description" content="{{ $siteSettings['site_description'] }}">
    @endif
<meta property="og:type" content="{{ $isPostShowPage ? 'article' : 'website' }}">
    <meta property="og:url" content="{{ url()->current() }}">

    <link rel="icon" type="image/x-icon" href="/favicon.ico">

    @if(!empty($siteSettings->google_analytics_id))
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $siteSettings->google_analytics_id }}"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());
          gtag('config', '{{ $siteSettings->google_analytics_id }}');
        </script>
    @endif

    <script>
        (function() {
            const theme = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            if (theme === 'dark') document.documentElement.classList.add('dark');
            window.appTheme = theme;
        })();
    </script>

    @if($isPostShowPage)
<script src="{{ asset('assets/js/highlight.min.js') }}" defer></script>
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @if($includeQuill ?? false)
        <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    @endif
    
    <style>
        html { transition: background-color 0.3s ease, color 0.3s ease; }
    </style>
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100 font-sans p-6">
    <x-posts-header />
    
    {{ $slot }}
    
    <x-posts-footer />



</body>
</html>
