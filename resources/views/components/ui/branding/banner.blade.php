@use(Illuminate\Support\Facades\Vite)
<img
    src="{{ Vite::asset('resources/images/svg/logo-full-dark.svg') }}"
    alt="{{ __('navigation.logo_alt') }}"
    {{ $attributes->merge(['class' => 'hidden dark:block']) }}
/>
<img
    src="{{ Vite::asset('resources/images/svg/logo-full-title.svg') }}"
    alt="{{ __('navigation.logo_alt') }}"
    {{ $attributes->merge(['class' => 'block dark:hidden']) }}
/>
