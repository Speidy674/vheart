@props(["title" => null])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @class(['dark' => ($appearance ?? 'system') === 'dark'])>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script>
        (function () {
            const stored = '{{ $appearance ?? "system" }}';
            const mql = window.matchMedia('(prefers-color-scheme: dark)');

            function apply(mode) {
                const isDark = mode === 'dark' || (mode === 'system' && mql.matches);
                document.documentElement.classList.toggle('dark', isDark);
            }

            apply(stored);

            if (stored === 'system') {
                const onChange = () => apply('system');
                if (mql.addEventListener) mql.addEventListener('change', onChange);
                else mql.addListener(onChange);
            }

            window.addEventListener('storage', (e) => {
                if (e.key !== 'appearance') return;
                const next = e.newValue || 'system';
                apply(next);
            });
        })();
    </script>

    <style>
        html {
            background-color: oklch(1 0 0);
        }

        html.dark {
            background-color: oklch(0.145 0 0);
        }
    </style>

    <title>
        @if($title)
            {{ $title }} -
        @endif
        {{ config('app.name', 'Laravel') }}
    </title>

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/static/app.ts'])
    @cookieconsentscripts
</head>
<body class="font-inter antialiased">
    <div class="flex flex-col m-auto max-w-480 min-h-screen">
        <x-layout.header />

        <main class="grow">
            {{ $slot }}
        </main>

        <x-layout.footer />
    </div>

    {{-- use `@pushonce('elements', 'unique identifier') ... @endpushonce` to insert elements we may need only once per page (e.g. modals) --}}
    {{-- otherwise, loops on them will explode the page in size lol --}}
    {{-- @see https://laravel.com/docs/12.x/blade#the-once-directive --}}
    @stack('elements')

    {{-- BladeUI puts deferred SVG icons in this placeholder which gets used via id "pointers" --}}
    {{-- especially useful for icons that get used a ton like the clock or similar --}}
    {{-- this reduces the size of the page and the time to parse the DOM as many SVGs can create very deep structures --}}
    {{-- @see https://github.com/driesvints/blade-icons?tab=readme-ov-file#deferring-icons --}}
    <svg hidden class="hidden">
        @stack('bladeicons')
    </svg>

    @cookieconsentview
</body>
</html>
