<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @class(['dark' => ($appearance ?? 'system') === 'dark'])>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- Inline script to detect system dark mode preference and apply it immediately --}}
    <script>
        (function() {
            const appearance = '{{ $appearance ?? "system" }}';

            if (appearance === 'system') {
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

                if (prefersDark) {
                    document.documentElement.classList.add('dark');
                }
            }
        })();
    </script>

    <title inertia>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.tsx', "resources/js/pages/{$page['component']}.tsx"])
    @inertiaHead
    @cookieconsentscripts
</head>
<body class="font-inter antialiased">
<noscript>
    <div class="fixed inset-0 flex items-center justify-center p-6 text-center font-sans bg-zinc-50 text-black">
        <div class="w-full max-w-lg">
            <img src="{{ Vite::asset('resources/images/svg/logo-full-title.svg') }}" alt="VHeart Logo" class="mx-auto mb-6 max-w-75">

            <h1 class="text-xl font-bold">{{ __('app-blade.title') }}</h1>
            <p class="mt-4 leading-normal">
                {{ __('app-blade.description') }}
            </p>

            <div class="mt-6 flex flex-wrap justify-center gap-4">
                @foreach(['imprint', 'privacy'] as $link)
                    <a href="/{{ $link }}" class="px-4 py-2 border border-current rounded hover:underline">
                        {{ __('app-blade.' . $link) }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</noscript>
@inertia
@cookieconsentview
</body>
</html>
